<?php
include "../../../bin/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

$key = 'sN4pBaNk58*'; // Kunci enkripsi

function decryptData($encryptedData, $key)
{
    $cipherMethod = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipherMethod); // Dapatkan panjang IV yang dibutuhkan
    $data = base64_decode($encryptedData); // Decode data terenkripsi
    list($encryptedData, $iv) = explode('::', $data, 2); // Pisahkan data dan IV

    // Validasi panjang IV
    if (strlen($iv) !== $ivLength) {
        throw new Exception('Invalid IV length');
    }

    // Dekripsi data
    return openssl_decrypt($encryptedData, $cipherMethod, $key, 0, $iv);
}

try {
    // Set header JSON
    header('Content-Type: application/json; charset=utf-8');

    // Buka koneksi ke database
    $database = new Connection();
    $db = $database->openConnection();

    if (isset($_GET['id_trans'])) {
        $id_trans = $_GET['id_trans'];

        // Tanggal hari ini
        $today = date('Y-m-d');

        // Siapkan query
        $stmt = $db->prepare("SELECT B.nama_lengkap, A.id_trans, A.no_rekening, A.nominal, A.date_modified, A.time_modified, 
                              A.jenis_trans, A.status_trans, A.tgl_trans, A.jam_trans, B.nomor_hp 
                              FROM transaksi_tab A 
                              LEFT JOIN customer B ON A.id_customer = B.id_customer 
                              WHERE A.id_trans = :id_trans AND A.status_trans = '0' AND A.tgl_trans = :today 
                              ORDER BY A.id_trans DESC LIMIT 1");

        $stmt->execute([
            'id_trans' => $id_trans,
            'today' => $today
        ]);

        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            echo json_encode(['success' => true, 'data' => $transaction]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Transaksi tidak ditemukan atau tanggal transaksi kadaluarsa!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No transaction ID provided']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
