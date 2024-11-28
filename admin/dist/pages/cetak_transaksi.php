<?php
date_default_timezone_set('Asia/Jakarta');

define('FPDF_FONTPATH', '../../../fpdf17/font/');
require('../../../fpdf17/fpdf.php');
include '../../../bin/koneksi.php';
session_start();
$id_trans = $_GET['id'];

class PDF extends FPDF
{
    function Content($id_trans)
    {
        // Menggunakan font Courier untuk printer dot matrix
        $this->SetFont('Courier', '', 8);
        $lineHeight = 3; // Jarak antar baris
        $colWidth = 40; // Lebar kolom untuk label
        $valueWidth = 120; // Lebar kolom untuk nilai (data)

        try {
            $database = new Connection();
            $db = $database->openConnection();
            $sql = "SELECT B.nama_lengkap, A.tabtrans_id, A.kode_user, A.id_trans, A.no_rekening, A.nominal, A.date_modified, A.time_modified, A.jenis_trans, A.status_trans, A.tgl_trans, A.jam_trans 
            FROM transaksi_tab A 
            LEFT JOIN customer B ON A.id_customer = B.id_customer 
            WHERE A.id_trans = :id_trans";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_trans', $id_trans, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                // Menampilkan informasi transaksi dengan jarak antar baris yang bisa diatur
                $this->Cell($colWidth, $lineHeight, 'No Rekening/Nama:', 0, 0, 'L');
                $this->Cell($valueWidth, $lineHeight, $data['no_rekening'] . '/' . $data['nama_lengkap'], 0, 1, 'L');

                $this->Cell($colWidth, $lineHeight, 'Nominal:', 0, 0, 'L');
                $this->Cell($valueWidth, $lineHeight, 'Rp. ' . number_format($data['nominal'], 2, ',', '.'), 0, 1, 'L');

                $this->Cell($colWidth, $lineHeight, 'Jenis Transaksi:', 0, 0, 'L');
                $this->Cell($valueWidth, $lineHeight, $data['jenis_trans'], 0, 1, 'L');

                $this->Cell($colWidth, $lineHeight, 'Tanggal/Jam Transaksi:', 0, 0, 'L');
                $this->Cell($valueWidth, $lineHeight, $data['date_modified'] . '/' . $data['time_modified'] . 'WIB', 0, 1, 'L');

                $this->Cell($colWidth, $lineHeight, 'ID:', 0, 0, 'L');
                $this->Cell($valueWidth, $lineHeight, $data['tabtrans_id'], 0, 1, 'L');

                $this->Cell($colWidth, $lineHeight, 'User:', 0, 0, 'L');
                $this->Cell($valueWidth, $lineHeight, $data['kode_user'] . '/' . $_SESSION['id_user_cbs'], 0, 1, 'L');

                $this->Ln($lineHeight); // Jarak antar baris sebelum selesai
            } else {
                $this->Cell(0, $lineHeight, 'Data transaksi tidak ditemukan.', 0, 1, 'C');
            }
        } catch (PDOException $e) {
            echo "Terjadi masalah dalam koneksi: " . $e->getMessage();
        }
    }
}

$pdf = new PDF();
$pdf->AddPage(); // Portrait by default
$pdf->Content($id_trans);
$pdf->Output('VALIDASI_SNAPBANK.pdf', 'I');
