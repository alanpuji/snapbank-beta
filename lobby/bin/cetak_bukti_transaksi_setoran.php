<?php
date_default_timezone_set('Asia/Jakarta');

// Menyertakan file library QR Code
require_once '../../phpqrcode/qrlib.php';

// Koneksi ke database
include "../../bin/koneksi.php";
$database = new Connection();
$db = $database->openConnection();

function terbilang($number)
{
    $number = (int)$number;
    $words = array(
        '',
        'Satu',
        'Dua',
        'Tiga',
        'Empat',
        'Lima',
        'Enam',
        'Tujuh',
        'Delapan',
        'Sembilan',
        'Sepuluh',
        'Sebelas',
        'Dua Belas',
        'Tiga Belas',
        'Empat Belas',
        'Lima Belas',
        'Enam Belas',
        'Tujuh Belas',
        'Delapan Belas',
        'Sembilan Belas',
        'Dua Puluh',
        'Tiga Puluh',
        'Empat Puluh',
        'Lima Puluh',
        'Enam Puluh',
        'Tujuh Puluh',
        'Delapan Puluh',
        'Sembilan Puluh',
        'Seratus'
    );

    if ($number < 30) {
        return $words[$number];
    }

    $terbilang = '';
    $units = array(
        1000000000 => 'Miliar',
        1000000 => 'Juta',
        1000 => 'Ribu',
        100 => 'Ratus',
        10 => 'Puluh'
    );

    foreach ($units as $unitValue => $unitName) {
        $unitCount = floor($number / $unitValue);
        if ($unitCount > 0) {
            if ($unitValue >= 100) {
                $terbilang .= terbilang($unitCount) . ' ' . $unitName . ' ';
            } else {
                $terbilang .= $words[$unitCount] . ' ' . $unitName . ' ';
            }
        }
        $number %= $unitValue;
    }

    if ($number > 0) {
        $terbilang .= $words[$number];
    }

    return trim($terbilang);
}

$key = 'sN4pBaNk58*'; // Ganti dengan kunci yang lebih kuat dan aman
function encryptData($data, $key)
{
    $cipherMethod = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipherMethod); // Dapatkan panjang IV yang dibutuhkan
    $iv = openssl_random_pseudo_bytes($ivLength); // Buat IV dengan panjang yang sesuai
    $encryptedData = openssl_encrypt($data, $cipherMethod, $key, 0, $iv); // Enkripsi data
    return base64_encode($encryptedData . '::' . $iv); // Gabungkan data terenkripsi dan IV
}

$idTransaksi = isset($_GET['id_transaksi']) && $_GET['id_transaksi'] ? htmlspecialchars($_GET['id_transaksi']) : 'N/A';
// $idTransaksi = '65';

try {
    // Query untuk memilih data berdasarkan id_transaksi
    $stmt = $db->prepare("SELECT B.alamat, A.nama_penyetor, A.sumber_dana, B.nomor_identitas, B.nama_lengkap, A.id_trans, A.no_rekening, A.nominal, A.jenis_trans, A.status_trans, A.tgl_trans, A.jam_trans
    FROM transaksi_tab A 
    LEFT JOIN customer B ON A.id_customer=B.id_customer 
    WHERE A.id_trans = :id_transaksi");

    // Bind parameter untuk mencegah SQL Injection
    $stmt->bindParam(':id_transaksi', $idTransaksi, PDO::PARAM_STR);

    // Eksekusi query
    $stmt->execute();

    // Ambil hasil query
    $transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data ditemukan, simpan nilai transaksi
    if ($transaksi) {
        $namaPelanggan = $transaksi['nama_lengkap'] ?: 'N/A';
        $nomorRekening = $transaksi['no_rekening'] ?: 'N/A';
        $jenisTransaksi = $transaksi['jenis_trans'] ?: 'N/A';
        $jumlahTransaksi = $transaksi['nominal'] ?: 0;
        $tgl_trans = $transaksi['tgl_trans'] ?: 'N/A';
        $jam_trans = $transaksi['jam_trans'] ?: 'N/A';
        $no_identitas = $transaksi['nomor_identitas'] ?: 'N/A';
        $nama_penyetor = $transaksi['nama_penyetor'] ?: 'N/A';
        $sumber_dana = $transaksi['sumber_dana'] ?: '';
        $alamat = $transaksi['alamat'] ?: '';

        $encryptedIdTransaksi = $idTransaksi;
        // $encryptedIdTransaksi = encryptData($idTransaksi, $key);
        $qrcodeData = $encryptedIdTransaksi;

        $tempQRFile = '../../temp_qrcode/qrcode_bukti_transaksi.png';
        QRcode::png($qrcodeData, $tempQRFile);
    } else {
        // Jika data tidak ditemukan, tampilkan pesan error
        echo "Transaksi tidak ditemukan!";
        exit;
    }
} catch (PDOException $e) {
    // Tangani error jika terjadi
    echo "Database error: " . $e->getMessage();
    exit;
} finally {
    // Tutup koneksi
    $db = null;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
            /* Tambah margin untuk ruang lebih */
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            margin: 50px;
            padding: 0;
        }

        th,
        td {
            text-align: left;
            font-size: 12px;
            padding: 0px 0px;
            line-height: 1.2;
            font-family: 'Arial', 'Verdana', sans-serif;
        }

        .content {
            margin-top: 5px;
            width: 100%;
        }

        table {
            width: 100%;
        }

        @media print {
            body {
                margin: 0;
                padding: 10mm;
                /* Tambahkan padding untuk hasil cetak */
            }
        }

        .validation-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .validation-box {
            width: 80%;
            height: 100px;
            border: 1px solid #000;
            background-color: #fff;
        }

        .qrcode-box {
            width: 20%;
            text-align: center;
        }

        .qrcode-box img {
            max-width: 80%;
            height: auto;
        }

        .signature-table {
            width: 100%;
            margin-top: 5px;
        }

        .signature-table th,
        .signature-table td {
            text-align: center;
            padding: 5px 0;
            border: 1px solid #000;
        }

        .footer {
            margin-top: 5px;
            font-size: 10px;
            text-align: right;
        }
    </style>

</head>

<body>
    <div class="validation-container">
        <div class="validation-box"></div>
        <div class="qrcode-box">
            <img src="<?php echo $tempQRFile; ?>" alt="QR Code" />
        </div>
    </div>
    <div class="content">
        <table>
            <tr>
                <td width="25%">Tanggal, Jam</td>
                <td>: <?php echo $tgl_trans; ?>, <?php echo $jam_trans; ?> WIB</td>
            </tr>
            <tr>
                <td>No. KTP</td>
                <td>: <?php echo $no_identitas; ?></td>
            </tr>
            <tr>
                <td>Nama Nasabah</td>
                <td>: <?php echo $namaPelanggan; ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <?php echo $alamat; ?></td>
            </tr>
            <tr>
                <td>Nomor Rekening</td>
                <td>: <?php echo $nomorRekening; ?></td>
            </tr>
            <tr>
                <td>Jenis Transaksi</td>
                <td>: Setoran Tunai</td>
            </tr>
            <tr>
                <td>Sumber Dana</td>
                <td>: <?php echo $sumber_dana; ?></td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td>: Rp <?php echo number_format($jumlahTransaksi, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo terbilang($jumlahTransaksi); ?> Rupiah</td>
            </tr>
        </table>
    </div>
    <table class="signature-table">
        <tr>
            <th>Diproses oleh</th>
            <th>Penyetor</th>
        </tr>
        <tr>
            <td><br><br><br><br></td>
            <td><br><br><br><br></td>
        </tr>
        <tr>
            <td>Teller</td>
            <td><?php echo $nama_penyetor; ?></td>
        </tr>
    </table>
    <div class="footer">
        Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?> WIB
    </div>
</body>

</html>