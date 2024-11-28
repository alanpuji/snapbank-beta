<?php
date_default_timezone_set('Asia/Jakarta');

function DateToIndo($date)
{
    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl = substr($date, 8, 2);
    return $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
}

function generate_tte($id_unik, $id_customer)
{
    $tempdir = "../../../temp_qrcode/";
    if (!file_exists($tempdir)) {
        mkdir($tempdir);
    }

    // QR Code content
    $isi_teks = $id_unik;
    $namafile = $id_customer . ".png";

    // Generate QR code without logo
    QRCode::png($isi_teks, $tempdir . $namafile, QR_ECLEVEL_H, 8, 0);
}

define('FPDF_FONTPATH', '../../../fpdf17/font/');
require_once('../../../fpdf17/fpdf.php');
require_once('../../../phpqrcode/qrlib.php');
include_once '../../../bin/koneksi.php';

$id_customer = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING); // Validate input

class PDF extends FPDF
{
    function Header() {}

    function Content()
    {
        $database = new Connection();
        $db = $database->openConnection();
        $id_customer = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        $filter = "SELECT * FROM customer WHERE id_customer = :id_customer";

        try {
            $query = $db->prepare($filter);
            $query->bindParam(":id_customer", $id_customer);
            $query->execute();

            if ($query->rowCount() === 0) {
                die("Error: Data tidak ditemukan.");
            } else {
                $data = $query->fetch();
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        } finally {
            $database->closeConnection(); // Close the connection
        }

        $this->AddPage();
        $this->Image('../../../images/snapbank.png', 10, 10, 20, 18);
        $this->SetY(30);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, 'ID Customer', 0, 0, 'L');
        $this->Cell(0, 5, ': ' . $id_customer, 0, 1, 'L');
        $this->Cell(30, 5, 'Nomor Identitas', 0, 0, 'L');
        $this->Cell(0, 5, ': ' . $data['nomor_identitas'], 0, 1, 'L');
        $this->Cell(30, 5, 'Nama', 0, 0, 'L');
        $this->Cell(0, 5, ': ' . $data['nama_lengkap'], 0, 1, 'L');
        $this->Cell(30, 5, 'Alamat', 0, 0, 'L');
        $this->Cell(0, 5, ': ' . $data['alamat'], 0, 1, 'L');

        generate_tte($data['id_unik_ktp_enkrip'], $id_customer);
        $this->Image("../../../temp_qrcode/" . $id_customer . ".png", 170, 5, 35, 35, "png");
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->SetFont('Arial', '', 6);
        $this->Cell(85, 10, 'Dicetak : ' . DateToIndo(date("Y-m-d")) . ' - ' . date("G:i:s"), 0, 0, 'L');
        $this->Cell(0, 10, 'SnapBank', 0, 0, 'R');
    }

    function Footer() {}
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Content();
$pdf->Output('SNAPBANK_' . $id_customer . '.pdf', 'I');
