<?php
date_default_timezone_set('Asia/Jakarta');

// Menyertakan file library QR Code
require_once '../../phpqrcode/qrlib.php';

// Koneksi ke database
include "../../bin/koneksi.php";
$database = new Connection();
$db = $database->openConnection();

// Mendapatkan id_transaksi dari parameter GET
$idTransaksi = isset($_GET['id_transaksi']) && $_GET['id_transaksi'] ? htmlspecialchars($_GET['id_transaksi']) : 'N/A';

try {
    // Query untuk memilih data berdasarkan id_transaksi
    $stmt = $db->prepare("SELECT B.nama_lengkap, A.id_trans, A.no_rekening, A.nominal, A.jenis_trans, A.status_trans, A.tgl_trans, A.jam_trans
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

        // Jika jenis_trans adalah 100, setel jenis transaksi sebagai "Setoran"
        if ($jenisTransaksi == 100) {
            $jenisTransaksi = "Setoran Tunai";
        } else {
            // Ubah jenis transaksi lainnya sesuai dengan kebutuhan Anda
            $jenisTransaksi = "Pengambilan Tunai";
        }

        // Membuat string untuk QR Code (misalnya, ID transaksi dan Nama Pelanggan)
        $qrcodeData = $idTransaksi;

        // Generate QR Code dan simpan dalam file sementara
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
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            width: 80mm;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            text-align: left;
            font-size: 14px;
            padding: 2px 0;
        }

        h1 {
            font-size: 24px;
            margin: 5px 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .footer {
            font-size: 10px;
            margin-top: 5px;
        }

        .qrcode {
            margin-top: 10px;
        }

        .warning {
            font-size: 12px;
            color: red;
            margin-top: 15px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
    <!-- <script>
        window.onload = function() {
            setTimeout(function() {
                window.print(); // Cetak otomatis setelah 1 detik
                window.close();
            }, 500);
        };
    </script> -->

</head>

<body>
    <div class="content">
        <h1>SnapBank</h1>
        <div class="divider"></div>
        <table>
            <tr>
                <td>Nama Bank</td>
                <td>: Bank ABC</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?php echo $tgl_trans; ?></td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>: <?php echo $jam_trans; ?></td>
            </tr>
            <tr>
                <td>Nama Nasabah</td>
                <td>: <?php echo $namaPelanggan; ?></td>
            </tr>
            <tr>
                <td>Nomor Rekening</td>
                <td>: <?php echo $nomorRekening; ?></td>
            </tr>
            <tr>
                <td>ID Transaksi</td>
                <td>: <?php echo $idTransaksi; ?></td>
            </tr>
            <tr>
                <td>Jenis Transaksi</td>
                <td>: <?php echo $jenisTransaksi; ?></td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td>: Rp <?php echo number_format($jumlahTransaksi, 0, ',', '.'); ?></td>
            </tr>
        </table>
        <div class="divider"></div>
        <div class="qrcode">
            <img src="<?php echo $tempQRFile; ?>" alt="QR Code" />
        </div>
        <div class="divider"></div>
        <p class="footer">TERIMA KASIH TELAH BERTRANSAKSI DI SNAPBANK!</p>
        <p class="footer">Untuk pertanyaan, silakan hubungi customer support kami.</p>

        <div class="warning">
            <strong>Peringatan:</strong><br>
            Bukti ini bukan transaksi sah. Harap lakukan transaksi di teller untuk memastikan transaksi Anda diproses dengan benar.
        </div>
    </div>

</body>

</html>