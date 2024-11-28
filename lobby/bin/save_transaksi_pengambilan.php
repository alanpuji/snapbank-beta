<?php
date_default_timezone_set('Asia/Jakarta');
// kode untuk menyimpan data setoran tabungan
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$database = new Connection();
$db = $database->openConnection();

try {
    // Check if required parameters are present
    if (isset($_POST['amount']) && isset($_POST['accountNumber']) && isset($_POST['id_customer']) && isset($_POST['id_nasabah']) && isset($_POST['penarikName']) && isset($_POST['penerimaName'])) {
        $amount = $_POST['amount'];
        $no_rekening = $_POST['accountNumber'];
        $id_customer = $_POST['id_customer'];
        $id_nasabah = $_POST['id_nasabah'];
        $date_modified = date('Y-m-d');
        $time_modified = date('H:i:s');
        $jenistrans = $_POST['jenistrans'];
        $gunaDana = $_POST['gunaDana'];
        $penarikName = $_POST['penarikName'];
        $penerimaName = $_POST['penerimaName'];

        // Prepare the SQL statement for inserting deposit data
        $stmt = $db->prepare("INSERT INTO transaksi_tab (id_customer, id_nasabah, no_rekening, nominal, date_modified, time_modified, jenis_trans, status_trans, tgl_trans, jam_trans, penggunaan_dana, nama_penarik, nama_penerima) 
        VALUES (:id_customer, :id_nasabah, :no_rekening, :nominal, :date_modified, :time_modified, :jenis_trans, '0', :tgl_trans, :jam_trans, :penggunaan_dana, :nama_penarik, :nama_penerima)");

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_STR);
        $stmt->bindParam(':id_nasabah', $id_nasabah, PDO::PARAM_STR);
        $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
        $stmt->bindParam(':nominal', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':date_modified', $date_modified, PDO::PARAM_STR);
        $stmt->bindParam(':time_modified', $time_modified, PDO::PARAM_STR); // Using the same timestamp for time_modified
        $stmt->bindParam(':jenis_trans', $jenistrans, PDO::PARAM_STR); // Using the same timestamp for time_modified
        $stmt->bindParam(':tgl_trans', $date_modified, PDO::PARAM_STR);
        $stmt->bindParam(':jam_trans', $time_modified, PDO::PARAM_STR);
        $stmt->bindParam(':penggunaan_dana', $gunaDana, PDO::PARAM_STR);
        $stmt->bindParam(':nama_penarik', $penarikName, PDO::PARAM_STR);
        $stmt->bindParam(':nama_penerima', $penerimaName, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            $id_trans = $db->lastInsertId();
            echo json_encode([
                "success" => "Transaksi berhasil diproses, silahkan menuju Teller.",
                "id_trans" => $id_trans
            ]);
        } else {
            echo json_encode(["error" => "Failed to record deposit."]);
        }
    } else {
        echo json_encode(["error" => "Required parameters are missing."]);
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    // Close the connection
    $db = null;
}
