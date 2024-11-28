<?php
include_once "../bin/koneksi.php";
include "../bin/math_captcha.php";
$captcha1 = new mathcaptcha();

$database = new Connection();
$db = $database->openConnection();
$first_use = htmlentities($_POST['first_use']);
$id_pengguna = htmlentities($_POST['id_pengguna']);

$query = $db->prepare("SELECT * FROM sys_user WHERE id_pengguna=:id_pengguna");
$query->bindParam(":id_pengguna", $id_pengguna);
$query->execute();
if($query->rowCount() == 0){
    echo "<script>
    alert('Maaf, Username tidak ditemukan');
    document.location.href='../index.php';
    </script>";    
}else{
    $data = $query->fetch();
    date_default_timezone_set('Asia/Jakarta');

    $date_modified = date("Y-m-d");
    $time_modified = date("H:i:s");
    $kode_captcha = htmlentities($_POST['kode_captcha']).$_POST['kode'];
    if ($captcha1->resultcaptcha() == $_POST['kode'])
    {
      $status_login=0;
      $hasil=1;

      $query = $db->prepare("UPDATE sys_user SET flag=:flag WHERE id_pengguna=:id_pengguna");
      $query->bindParam(":flag"           , $status_login);
      $query->bindParam(":id_pengguna"    , $id_pengguna);
      $query->execute();  

      $query = $db->prepare("INSERT INTO sys_log_unflag_user(id_pengguna,date_modified,time_modified,kode_captcha,hasil) 
      VALUES (:id_pengguna,:date_modified,:time_modified,:kode_captcha,:hasil)");
      $query->bindParam(":id_pengguna"    , $id_pengguna);
      $query->bindParam(":date_modified"      , $date_modified);
      $query->bindParam(":time_modified"   , $time_modified);
      $query->bindParam(":kode_captcha"  , $kode_captcha);
      $query->bindParam(":hasil"  , $hasil);
      $query->execute();

      $page_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $query = $db->prepare("INSERT INTO sys_log_user(id_pengguna,date_modified,time_modified,page_url) 
      VALUES (:id_pengguna,:date_modified,:time_modified,:page_url)");
      $query->bindParam(":id_pengguna"    , $id_pengguna);
      $query->bindParam(":date_modified"      , $date_modified);
      $query->bindParam(":time_modified"   , $time_modified);
      $query->bindParam(":page_url"  , $page_url);
      $query->execute();

      echo "<script>
      alert('Data disimpan, Silahkan login.');
      document.location.href='../index.php';
      </script>";                            
    }else{
      $hasil=0;
      $query = $db->prepare("INSERT INTO sys_log_unflag_user(id_pengguna,date_modified,time_modified,kode_captcha,hasil) 
      VALUES (:id_pengguna,:date_modified,:time_modified,:kode_captcha,:hasil)");
      $query->bindParam(":id_pengguna"    , $id_pengguna);
      $query->bindParam(":date_modified"      , $date_modified);
      $query->bindParam(":time_modified"   , $time_modified);
      $query->bindParam(":kode_captcha"  , $kode_captcha);
      $query->bindParam(":hasil"  , $hasil);
      $query->execute();

      $page_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $query = $db->prepare("INSERT INTO sys_log_user(id_pengguna,date_modified,time_modified,page_url) 
      VALUES (:id_pengguna,:date_modified,:time_modified,:page_url)");
      $query->bindParam(":id_pengguna"    , $id_pengguna);
      $query->bindParam(":date_modified"      , $date_modified);
      $query->bindParam(":time_modified"   , $time_modified);
      $query->bindParam(":page_url"  , $page_url);
      $query->execute();

      echo "<script>
      alert('Kode captcha anda salah, mohon ulangi kembali');
      document.location.href='../unflag_user.php?id_pengguna=$id_pengguna&first_use=$first_use';
      </script>";        
    }
}
?> 