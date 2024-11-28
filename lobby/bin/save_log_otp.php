<?php
date_default_timezone_set('Asia/Jakarta');

// kode untuk menyimpan data setoran tabungan
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$database = new Connection();
$db = $database->openConnection();

// Check if required POST data is received
if (isset($_POST['accountNumber']) && isset($_POST['otp']) && isset($_POST['id_customer'])) {
    $id_customer = $_POST['id_customer'];
    $accountNumber = $_POST['accountNumber'];
    $otp = $_POST['otp'];

    // Get current date and time for date_modified and time_modified columns
    $date_modified = date("Y-m-d");
    $time_modified = date("H:i:s");

    try {
        // Insert OTP, account number, and customer ID into log_cek_saldo table
        $stmt = $db->prepare("INSERT INTO log_cek_saldo (id_customer, no_rekening, kode_otp, date_modified, time_modified, status_log) 
                              VALUES (:id_customer, :accountNumber, :otp, :date_modified, :time_modified, '1')");
        $stmt->bindParam(":id_customer", $id_customer);
        $stmt->bindParam(":accountNumber", $accountNumber);
        $stmt->bindParam(":otp", $otp);
        $stmt->bindParam(":date_modified", $date_modified);
        $stmt->bindParam(":time_modified", $time_modified);
        $stmt->execute();

        $query = "SELECT nomor_hp from customer WHERE id_customer = :id_customer";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_customer', $id_customer);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        $nomor_hp = $transaction['nomor_hp'];

        // Hubungkan ke database WAGW untuk mengirimkan pesan
        $database = new Connection_WAGW();
        $db_wagw = $database->openConnection();

        // Format pesan OTP
        $pesan = sprintf(
            "Halo Nasabah Yang Terhormat,\n" .
                "Berikut adalah kode OTP Anda untuk verifikasi di aplikasi kami: *%s*.\n" .
                "Jangan berikan kode ini kepada siapa pun. Kode ini akan kedaluwarsa dalam 2 menit.\n\n" .
                "Terima kasih",
            $otp
        );

        // Simpan pesan ke tabel `outbox`
        $query = $db_wagw->prepare("INSERT INTO outbox(wa_mode, wa_no, wa_text, wa_time, status) VALUES (0, :nomor_hp, :pesan, CURRENT_TIMESTAMP, 0)");
        $query->bindParam(":nomor_hp", $nomor_hp);
        $query->bindParam(":pesan", $pesan);
        $query->execute();

        echo json_encode(["success" => true, "message" => "OTP saved successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}
