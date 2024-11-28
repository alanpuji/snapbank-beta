<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

include "../../../bin/koneksi.php";
$database = new Connection();
$db = $database->openConnection();

if (isset($_POST['id_trans']) && isset($_POST['nomor_hp'])) {
  try {
    // Generate a 6-digit OTP
    $kode_otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Input dari pengguna
    $id_trans = filter_var($_POST['id_trans'], FILTER_VALIDATE_INT);
    $nomor_hp = preg_match('/^\d+$/', $_POST['nomor_hp']) ? $_POST['nomor_hp'] : null;
    if (!$id_trans || !$nomor_hp) {
      echo json_encode(['success' => false, 'message' => 'Data input tidak valid']);
      exit;
    }
    $status_trans = "1";
    $date_modified = date("Y-m-d");
    $time_modified = date('H:i:s');
    $kode_user = $_SESSION['id_pengguna'];

    // Update transaksi dengan kode OTP
    $query = "UPDATE transaksi_tab SET status_trans = :status_trans, date_modified = :date_modified, time_modified = :time_modified, kode_user = :kode_user, kode_otp = :kode_otp WHERE id_trans = :id_trans";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_trans', $id_trans);
    $stmt->bindParam(':status_trans', $status_trans);
    $stmt->bindParam(':date_modified', $date_modified);
    $stmt->bindParam(':time_modified', $time_modified);
    $stmt->bindParam(':kode_user', $kode_user);
    $stmt->bindParam(':kode_otp', $kode_otp);
    $stmt->execute();

    // Hubungkan ke database WAGW untuk mengirimkan pesan
    $database = new Connection_WAGW();
    $db_wagw = $database->openConnection();

    // Format pesan OTP
    $pesan = sprintf(
      "Halo Nasabah Yang Terhormat,\n" .
        "Berikut adalah kode OTP Anda untuk verifikasi di aplikasi kami: *%s*.\n" .
        "Jangan berikan kode ini kepada siapa pun. Kode ini akan kedaluwarsa dalam 2 menit.\n\n" .
        "Terima kasih",
      $kode_otp
    );

    // Simpan pesan ke tabel `outbox`
    $query = $db_wagw->prepare("INSERT INTO outbox(wa_mode, wa_no, wa_text, wa_time, status) VALUES (0, :nomor_hp, :pesan, CURRENT_TIMESTAMP, 0)");
    $query->bindParam(":nomor_hp", $nomor_hp);
    $query->bindParam(":pesan", $pesan);

    if ($query->execute()) {
      echo json_encode(['success' => true, 'message' => 'OTP berhasil terkirim']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Gagal mengirim OTP']);
    }
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Kesalahan Database: ' . $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'ID Transaksi tidak valid']);
}
