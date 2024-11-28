<?php
set_time_limit(0);

header('Content-Type: application/json');

session_start();
date_default_timezone_set('Asia/Jakarta');

function getClientIpAddress()
{
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    return $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    return $_SERVER['REMOTE_ADDR'];
  }
}
$clientIp = getClientIpAddress();

function getNextId($db_core, $id_user_cbs)
{
  $sql = "DELETE FROM my_id_generator WHERE userid = :id_user_cbs";
  $stmt = $db_core->prepare($sql);
  $stmt->bindParam(':id_user_cbs', $id_user_cbs, PDO::PARAM_INT);
  $stmt->execute();

  $query = "INSERT INTO my_id_generator (next_id, userid) VALUES (null, :id_user_cbs)";
  $stmt = $db_core->prepare($query);
  $stmt->bindParam(':id_user_cbs', $id_user_cbs, PDO::PARAM_INT);
  $stmt->execute();

  $query = "SELECT MAX(next_id) as id_trans FROM my_id_generator WHERE userid = :id_user_cbs";
  $stmt = $db_core->prepare($query);
  $stmt->bindParam(':id_user_cbs', $id_user_cbs);
  $stmt->execute();

  return $stmt->fetch(PDO::FETCH_ASSOC)['id_trans'];
}

function AccPerkiraanPostSaldoDK($db_core, $nDebet, $nKredit, $KODE_PERK)
{
  $query = $db_core->prepare("
      UPDATE perkiraan 
      SET saldo_debet=saldo_debet+:nDebet, saldo_kredit=saldo_kredit+:nKredit  
      WHERE kode_perk = :KODE_PERK
  ");
  $query->bindParam(':nDebet', $nDebet, PDO::PARAM_INT);
  $query->bindParam(':nKredit', $nKredit, PDO::PARAM_INT);
  $query->bindParam(':KODE_PERK', $KODE_PERK, PDO::PARAM_STR);
  $query->execute();
}

function AccPerkiraanSynchronizeSaldo($db_core, $KODE_PERK)
{
  $query = $db_core->prepare("
      UPDATE perkiraan
      SET saldo_akhir = saldo_awal + saldo_debet - saldo_kredit
      WHERE kode_perk = :KODE_PERK AND d_or_k = 'D'
  ");
  $query->bindParam(':KODE_PERK', $KODE_PERK, PDO::PARAM_STR);
  $query->execute();

  $query = $db_core->prepare("
      UPDATE perkiraan
      SET saldo_akhir = saldo_awal + saldo_kredit - saldo_debet
      WHERE kode_perk = :KODE_PERK AND d_or_k = 'K'
  ");
  $query->bindParam(':KODE_PERK', $KODE_PERK, PDO::PARAM_STR);
  $query->execute();
}

function AccPerkiraanUpdateInduk($db_core, $KODE_PERK)
{
  $query = "SELECT kode_induk FROM perkiraan WHERE kode_perk = :KODE_PERK";
  $stmt = $db_core->prepare($query);
  $stmt->bindParam(':KODE_PERK', $KODE_PERK);
  $stmt->execute();
  $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
  $kode_induk = $transaction['kode_induk'];

  $query = "SELECT 
              SUM(saldo_awal) AS saldo_awal_perkiraan, 
              SUM(saldo_debet) AS saldo_debet_perkiraan, 
              SUM(saldo_kredit) AS saldo_kredit_perkiraan, 
              SUM(saldo_akhir) AS saldo_akhir_perkiraan 
            FROM perkiraan 
            WHERE kode_induk = :kode_induk";
  $stmt = $db_core->prepare($query);
  $stmt->bindParam(':kode_induk', $kode_induk);
  $stmt->execute();
  $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
  $saldo_awal_perkiraan = $transaction['saldo_awal_perkiraan'];
  $saldo_debet_perkiraan = $transaction['saldo_debet_perkiraan'];
  $saldo_kredit_perkiraan = $transaction['saldo_kredit_perkiraan'];
  $saldo_akhir_perkiraan = $transaction['saldo_akhir_perkiraan'];

  $updateQuery = "UPDATE perkiraan 
                  SET saldo_awal = :saldo_awal_perkiraan, 
                      saldo_debet = :saldo_debet_perkiraan, 
                      saldo_kredit = :saldo_kredit_perkiraan, 
                      saldo_akhir = :saldo_akhir_perkiraan 
                  WHERE kode_perk = :kode_induk";
  $query = $db_core->prepare($updateQuery);
  $query->bindParam(':saldo_awal_perkiraan', $saldo_awal_perkiraan, PDO::PARAM_STR);
  $query->bindParam(':saldo_debet_perkiraan', $saldo_debet_perkiraan, PDO::PARAM_STR);
  $query->bindParam(':saldo_kredit_perkiraan', $saldo_kredit_perkiraan, PDO::PARAM_STR);
  $query->bindParam(':saldo_akhir_perkiraan', $saldo_akhir_perkiraan, PDO::PARAM_STR);
  $query->bindParam(':kode_induk', $kode_induk, PDO::PARAM_STR);
  $query->execute();
}

function AccPerkiraanSetSaldoRL($db_core)
{
  $query = "SELECT 
              SUM(saldo_awal) AS saldo_awal_perkiraan, 
              SUM(saldo_debet) AS saldo_debet_perkiraan, 
              SUM(saldo_kredit) AS saldo_kredit_perkiraan, 
              SUM(saldo_akhir) AS saldo_akhir_perkiraan 
            FROM perkiraan 
            WHERE type_perk = 'PENDAPATAN' AND level_perk = 0";
  $stmt = $db_core->prepare($query);
  $stmt->execute();
  $pendapatan = $stmt->fetch(PDO::FETCH_ASSOC);

  $query = "SELECT 
              SUM(saldo_awal) AS saldo_awal_perkiraan, 
              SUM(saldo_debet) AS saldo_debet_perkiraan, 
              SUM(saldo_kredit) AS saldo_kredit_perkiraan, 
              SUM(saldo_akhir) AS saldo_akhir_perkiraan 
            FROM perkiraan 
            WHERE type_perk = 'BIAYA' AND level_perk = 0";
  $stmt = $db_core->prepare($query);
  $stmt->execute();
  $biaya = $stmt->fetch(PDO::FETCH_ASSOC);

  $query = "SELECT 
              SUM(saldo_awal) AS saldo_awal_perkiraan, 
              SUM(saldo_debet) AS saldo_debet_perkiraan, 
              SUM(saldo_kredit) AS saldo_kredit_perkiraan, 
              SUM(saldo_akhir) AS saldo_akhir_perkiraan 
            FROM perkiraan 
            WHERE type_perk = 'PAJAK' AND level_perk = 0";
  $stmt = $db_core->prepare($query);
  $stmt->execute();
  $pajak = $stmt->fetch(PDO::FETCH_ASSOC);

  $query = "UPDATE perkiraan 
            SET 
              saldo_awal = :nPendAwal - :nBiayaAwal - :nPajakAwal, 
              saldo_debet = :nBiayaDebet + :nPajakDebet - :nPendDebet, 
              saldo_kredit = :nPendKredit - :nBiayaKredit - :nPajakKredit, 
              saldo_akhir = :nPendAkhir - :nBiayaAkhir - :nPajakDebet
            WHERE type_perk = 'LABA RUGI' AND g_or_d = 'D'";
  $query = $db_core->prepare($query);
  $query->bindParam(':nPendAwal', $pendapatan['saldo_awal_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nBiayaAwal', $biaya['saldo_awal_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nPajakAwal', $pajak['saldo_awal_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nBiayaDebet', $biaya['saldo_debet_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nPajakDebet', $pajak['saldo_debet_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nPendDebet', $pendapatan['saldo_debet_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nPendKredit', $pendapatan['saldo_kredit_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nBiayaKredit', $biaya['saldo_kredit_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nPajakKredit', $pajak['saldo_kredit_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nPendAkhir', $pendapatan['saldo_akhir_perkiraan'], PDO::PARAM_STR);
  $query->bindParam(':nBiayaAkhir', $biaya['saldo_akhir_perkiraan'], PDO::PARAM_STR);
  $query->execute();

  $query = "SELECT kode_perk FROM perkiraan WHERE type_perk = 'LABA RUGI' AND g_or_d = 'D'";
  $stmt = $db_core->prepare($query);
  $stmt->execute();
  $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
  $kode_perk_labarugi = $transaction['kode_perk'];

  return $kode_perk_labarugi;
}

function insertTransactionDetail($db_core, $transIdDetail, $transIdMaster, $kodePerk, $nDebet, $nKredit)
{
   
    $kodeKantorDetail = isset($_SESSION['kode_kantor']) ? $_SESSION['kode_kantor'] : '001'; 
    $query = $db_core->prepare("
        INSERT INTO TRANSAKSI_DETAIL (TRANS_ID, MASTER_ID, KODE_PERK, DEBET, KREDIT, KODE_KANTOR_DETAIL) 
        VALUES (:TRANS_ID_DETAIL, :TRANS_ID_MASTER, :KODE_PERK, :nDebet, :nKredit, :KODE_KANTOR_DETAIL)
    ");
    $query->bindParam(':TRANS_ID_DETAIL', $transIdDetail, PDO::PARAM_INT);
    $query->bindParam(':TRANS_ID_MASTER', $transIdMaster, PDO::PARAM_STR);
    $query->bindParam(':KODE_PERK', $kodePerk, PDO::PARAM_STR);
    $query->bindParam(':nDebet', $nDebet, PDO::PARAM_INT);
    $query->bindParam(':nKredit', $nKredit, PDO::PARAM_INT);
    $query->bindParam(':KODE_KANTOR_DETAIL', $kodeKantorDetail, PDO::PARAM_STR);
    $query->execute();
}


include "../../../bin/koneksi.php";
$database = new Connection();
$db = $database->openConnection();
$database = new Connection_Core();
$db_core = $database->openConnection();

$sql = "SELECT value_parameter FROM sys_parameter WHERE nama_parameter='KODE_TRANS_SETORAN_TABUNGAN'";
$result = $db->prepare($sql);
$result->execute();
$KODE_TRANS_SETORAN_TABUNGAN = $result->fetchColumn();
$sql = "SELECT value_parameter FROM sys_parameter WHERE nama_parameter='KODE_TRANS_PENARIKAN_TABUNGAN'";
$result = $db->prepare($sql);
$result->execute();
$KODE_TRANS_PENARIKAN_TABUNGAN = $result->fetchColumn();

if (isset($_POST['id_trans']) && isset($_POST['otp'])) {
  try {
    $id_trans = $_POST['id_trans'];
    $kode_otp = $_POST['otp']; // Get the OTP from POST data

    // Fetch the OTP from the database for verification
    $query = "SELECT no_rekening,jenis_trans,kode_otp,nominal,id_customer FROM transaksi_tab WHERE id_trans = :id_trans";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_trans', $id_trans);
    $stmt->execute();
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
      $no_rekening = $transaction['no_rekening'];
      $jenis_trans = $transaction['jenis_trans'];
      $nominal = $transaction['nominal'];
      $id_customer = $transaction['id_customer'];

      if ($transaction['kode_otp'] === $kode_otp) {
        $status_trans = "3";
        $date_modified = date("Y-m-d");
        $time_modified2 = date('Y-m-d H:i:s');
        $bukti = 'SNAP-' . substr(str_shuffle('123456789'), 0, 9);
        $kode_user = $_SESSION['id_pengguna'];
        $id_user_cbs = $_SESSION['id_user_cbs'];
        $kode_kantor = $_SESSION['kode_kantor'];
        $kode_kas = $_SESSION['kode_kas'];
        $username_cbs = $_SESSION['username_cbs'];

        $query = "SELECT nama_lengkap from customer WHERE id_customer = :id_customer";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_customer', $id_customer);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        $nama_lengkap = $transaction['nama_lengkap'];

        //proses core
        $query = "SELECT KODE_INTEGRASI from TABUNG WHERE NO_REKENING = :no_rekening";
        $stmt = $db_core->prepare($query);
        $stmt->bindParam(':no_rekening', $no_rekening);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        $KODE_INTEGRASI = $transaction['KODE_INTEGRASI'];

        $query = "SELECT KODE_PERK_HUTANG_POKOK from TAB_INTEGRASI WHERE KODE_INTEGRASI = :KODE_INTEGRASI";
        $stmt = $db_core->prepare($query);
        $stmt->bindParam(':KODE_INTEGRASI', $KODE_INTEGRASI);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        $KODE_PERK_HUTANG_POKOK = $transaction['KODE_PERK_HUTANG_POKOK'];

        $common_id = getNextId($db_core, $id_user_cbs);
        $TABTRANS_ID = getNextId($db_core, $id_user_cbs);

        if ($jenis_trans == "100") {
          $berita_trans = 'Setoran Tabungan Tunai Via SnapBank an: ' . $no_rekening . ' ' . $nama_lengkap;
          $kode_trans = $KODE_TRANS_SETORAN_TABUNGAN;
        } else {
          $berita_trans = 'Pengambilan Tabungan Tunai Via SnapBank an: ' . $no_rekening . ' ' . $nama_lengkap;
          $kode_trans = $KODE_TRANS_PENARIKAN_TABUNGAN;
        }
        $query = $db_core->prepare("INSERT INTO TABTRANS (NO_SLIP,TABTRANS_ID, TGL_TRANS, NO_REKENING, MY_KODE_TRANS, KUITANSI, TOB,POKOK,ADM,KETERANGAN, KETERANGAN1,VERIFIKASI, USERID, KODE_TRANS,SANDI_TRANS,OTORISASI,KODE_PERK_OB, NO_REKENING_VS, KODE_KOLEKTOR,KODE_KANTOR,ADM_PENUTUPAN,COMMON_ID,JAM,IP_ADD,NO_REKENING_ABA,RETURN_BUNGA,TITIPAN_BUNGA,PAJAK_TRANS,INSENTIF_TRANS,PREMI_TRANS,VOUCHER_TRANS,POINT,NO_REKENING_ABP,jam_trans) 
        VALUES ('', :TABTRANS_ID, :TGL_TRANS, :no_rekening, :jenis_trans,:bukti , 'T', :nominal, 0, :berita_trans, '', '1', :id_user_cbs, :kode_trans, :jenis_trans, 0,'', '', '',:kode_kantor,0, :common_id, :time_modified, :clientIp,'',0,0,0,0,0,0, 0,'', :time_modified2)");
        $query->bindParam(':TABTRANS_ID', $TABTRANS_ID, PDO::PARAM_INT);
        $query->bindParam(':TGL_TRANS', $date_modified, PDO::PARAM_STR);
        $query->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
        $query->bindParam(':jenis_trans', $jenis_trans, PDO::PARAM_INT);
        $query->bindParam(':bukti', $bukti, PDO::PARAM_STR);
        $query->bindParam(':nominal', $nominal, PDO::PARAM_INT);
        $query->bindParam(':berita_trans', $berita_trans, PDO::PARAM_STR);
        $query->bindParam(':id_user_cbs', $id_user_cbs, PDO::PARAM_INT);
        $query->bindParam(':kode_kantor', $kode_kantor, PDO::PARAM_STR);
        $query->bindParam(':common_id', $common_id, PDO::PARAM_INT);
        $query->bindParam(':time_modified', $time_modified, PDO::PARAM_STR);
        $query->bindParam(':time_modified2', $time_modified2, PDO::PARAM_STR);
        $query->bindParam(':clientIp', $clientIp, PDO::PARAM_STR);
        $query->bindParam(':kode_trans', $kode_trans, PDO::PARAM_STR);
        $query->execute();

        $query = $db_core->prepare("INSERT INTO ibs_log_transaksi (log_date, log_userid, log_username, log_action, table_name, field_key_value_new) 
        VALUES (now(), :id_user_cbs, :username_cbs, 'insert', 'tabtrans', :TABTRANS_ID)");
        $query->bindParam(':id_user_cbs', $id_user_cbs, PDO::PARAM_INT);
        $query->bindParam(':username_cbs', $username_cbs, PDO::PARAM_INT);
        $query->bindParam(':TABTRANS_ID', $TABTRANS_ID, PDO::PARAM_INT);
        $query->execute();

        //TRANSAKSI_MASTER
        $TRANS_ID_MASTER = getNextId($db_core, $id_user_cbs);

        $query = $db_core->prepare("INSERT INTO TRANSAKSI_MASTER (TRANS_ID, KODE_JURNAL, NO_BUKTI, TGL_TRANS, URAIAN, MODUL_ID_SOURCE,TRANS_ID_SOURCE,USERID,KODE_KANTOR,TRANSFER) 
        VALUES (:TRANS_ID, 'TAB',:bukti, :TGL_TRANS, :berita_trans, 'TAB', :TABTRANS_ID, :id_user_cbs,:kode_kantor,0)");
        $query->bindParam(':TRANS_ID', $TRANS_ID_MASTER, PDO::PARAM_INT);
        $query->bindParam(':TGL_TRANS', $date_modified, PDO::PARAM_STR);
        $query->bindParam(':bukti', $bukti, PDO::PARAM_STR);
        $query->bindParam(':berita_trans', $berita_trans, PDO::PARAM_STR);
        $query->bindParam(':TABTRANS_ID', $TABTRANS_ID, PDO::PARAM_INT);
        $query->bindParam(':id_user_cbs', $id_user_cbs, PDO::PARAM_INT);
        $query->bindParam(':kode_kantor', $kode_kantor, PDO::PARAM_STR);
        $query->execute();

        //TRANSAKSI_DETAIL
        if ($jenis_trans == "100") {
          //KREDIT
          $TRANS_ID_DETAIL = getNextId($db_core, $id_user_cbs);
          insertTransactionDetail($db_core, $TRANS_ID_DETAIL, $TRANS_ID_MASTER, $KODE_PERK_HUTANG_POKOK, 0, $nominal);
          AccPerkiraanPostSaldoDK($db_core, 0, $nominal, $KODE_PERK_HUTANG_POKOK);
          AccPerkiraanSynchronizeSaldo($db_core, $KODE_PERK_HUTANG_POKOK);
          AccPerkiraanUpdateInduk($db_core, $KODE_PERK_HUTANG_POKOK);
          $kode_perk_labarugi = AccPerkiraanSetSaldoRL($db_core);
          AccPerkiraanUpdateInduk($db_core, $kode_perk_labarugi);
          //DEBET
          $TRANS_ID_DETAIL = getNextId($db_core, $id_user_cbs);
          insertTransactionDetail($db_core, $TRANS_ID_DETAIL, $TRANS_ID_MASTER, $kode_kas, $nominal, 0);
          AccPerkiraanPostSaldoDK($db_core, $nominal, 0, $kode_kas);
          AccPerkiraanSynchronizeSaldo($db_core, $kode_kas);
          AccPerkiraanUpdateInduk($db_core, $kode_kas);
          $kode_perk_labarugi = AccPerkiraanSetSaldoRL($db_core);
          AccPerkiraanUpdateInduk($db_core, $kode_perk_labarugi);
        } else {
          //DEBET
          $TRANS_ID_DETAIL = getNextId($db_core, $id_user_cbs);
          insertTransactionDetail($db_core, $TRANS_ID_DETAIL, $TRANS_ID_MASTER, $KODE_PERK_HUTANG_POKOK, $nominal, 0);
          AccPerkiraanPostSaldoDK($db_core, $nominal, 0, $KODE_PERK_HUTANG_POKOK);
          AccPerkiraanSynchronizeSaldo($db_core, $KODE_PERK_HUTANG_POKOK);
          AccPerkiraanUpdateInduk($db_core, $KODE_PERK_HUTANG_POKOK);
          $kode_perk_labarugi = AccPerkiraanSetSaldoRL($db_core);
          AccPerkiraanUpdateInduk($db_core, $kode_perk_labarugi);
          //KREDIT
          $TRANS_ID_DETAIL = getNextId($db_core, $id_user_cbs);
          insertTransactionDetail($db_core, $TRANS_ID_DETAIL, $TRANS_ID_MASTER, $kode_kas, 0, $nominal);
          AccPerkiraanPostSaldoDK($db_core, 0, $nominal, $kode_kas);
          AccPerkiraanSynchronizeSaldo($db_core, $kode_kas);
          AccPerkiraanUpdateInduk($db_core, $kode_kas);
          $kode_perk_labarugi = AccPerkiraanSetSaldoRL($db_core);
          AccPerkiraanUpdateInduk($db_core, $kode_perk_labarugi);
        }

        //UPDATE SALDO
        $query = "SELECT tabung.kode_produk,tabung.status,SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS SETORAN, 
        SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS PENARIKAN
        FROM tabung,TABTRANS 
        WHERE tabung.no_rekening=tabtrans.no_rekening and tabung.NO_REKENING=:no_rekening GROUP BY TABUNG.NO_REKENING";
        $stmt = $db_core->prepare($query);
        $stmt->bindParam(':no_rekening', $no_rekening);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        $saldo_akhir = $transaction['SETORAN'] - $transaction['PENARIKAN'];

        $query = "UPDATE TABUNG SET SALDO_AKHIR=:saldo_akhir, STATUS=1, POINT_MILYADER=0 WHERE NO_REKENING=:no_rekening";
        $query = $db_core->prepare($query);
        $query->bindParam(':saldo_akhir', $saldo_akhir);
        $query->bindParam(':no_rekening', $no_rekening);
        $query->execute();

        //UPDATE TRANSAKSI
        $updateQuery = "UPDATE transaksi_tab SET status_trans = :status_trans, date_modified = :date_modified, time_modified = :time_modified, kode_user = :kode_user, tabtrans_id = :tabtrans_id WHERE id_trans = :id_trans";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':id_trans', $id_trans);
        $updateStmt->bindParam(':status_trans', $status_trans);
        $updateStmt->bindParam(':date_modified', $date_modified);
        $updateStmt->bindParam(':time_modified', $time_modified);
        $updateStmt->bindParam(':kode_user', $kode_user);
        $updateStmt->bindParam(':tabtrans_id', $TABTRANS_ID);
        $updateStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Transaksi berhasil diproses']);
      } else {
        echo json_encode(['success' => false, 'message' => 'OTP salah, silahkan coba lagi']);
      }
    } else {
      echo json_encode(['success' => false, 'message' => 'ID Transaksi tidak ditemukan']);
    }
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Kesalahan Database: ' . $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'ID Transaksi atau OTP tidak valid']);
}
