<?php
date_default_timezone_set('Asia/Jakarta');
// kode untuk menyimpan data setoran tabungan
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$database = new Connection();
$db = $database->openConnection();

try {
    // Ambil data JSON dari request
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi data
    if (empty($input['id_customer']) || empty($input['action'])) {
        throw new Exception("Invalid input data.");
    }

    $id_customer = $input['id_customer'];
    $action = $input['action'];
    $date_modified = date('Y-m-d'); // Tanggal sekarang
    $time_modified = date('H:i:s'); // Waktu sekarang

    // Simpan log ke database
    $stmt = $db->prepare("INSERT INTO sys_log_customer (id_customer, date_modified, time_modified, action) VALUES (:id_customer, :date_modified, :time_modified, :action)");
    $stmt->execute([
        'id_customer' => $id_customer,
        'date_modified' => $date_modified,
        'time_modified' => $time_modified,
        'action' => $action
    ]);

    // Kirim respon sukses
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Kirim respon error
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Tutup koneksi database
    $database->closeConnection();
}
