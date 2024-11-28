<?php
include_once "../bin/koneksi.php";
$database = new Connection();
$db = $database->openConnection();
$first_use = htmlentities($_POST['first_use']);
$id_pengguna = htmlentities($_POST['id_pengguna']);
$password_lama = htmlentities($_POST['password_lama']);

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

    if(password_verify($password_lama,$data['password_user'])){
      $password_baru = htmlentities($_POST['password_baru']);
      if(password_verify($password_baru,$data['password_user'])){
        echo "<script>
        alert('Password lama dan baru tidak boleh sama.');
        document.location.href='../renew_user.php?id_pengguna=$id_pengguna&first_use=$first_use';
        </script>";        
      }else{
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password_baru);
        $lowercase = preg_match('@[a-z]@', $password_baru);
        $number    = preg_match('@[0-9]@', $password_baru);
        $specialChars = preg_match('@[^\w]@', $password_baru);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password_baru) < 8) {
          echo "<script>
          alert('Kata sandi harus setidaknya 8 karakter dan harus mencakup setidaknya satu huruf besar, satu angka, dan satu karakter khusus.');
          document.location.href='../renew_user.php?id_pengguna=$id_pengguna&first_use=$first_use';
          </script>";        
        }else{
          $status_aktif=1;
          if($first_use=="false"){
            $password_baru = password_hash($password_baru,PASSWORD_DEFAULT);
            $status_login=0;
            $tgl_expired = $data['tgl_expired'];
            $hari_ini = date("Y-m-d");
            $tgl_expired = strtotime(date("Y-m-d", strtotime($hari_ini)) . " +6 month");
            $tgl_expired = date("Y-m-d",$tgl_expired);                
            $login_failure=0;
    
            $query = $db->prepare("UPDATE sys_user SET password_user=:password_user,flag=:flag,tgl_expired=:tgl_expired,login_failure=:login_failure,status_aktif=:status_aktif WHERE id_pengguna=:id_pengguna");
            $query->bindParam(":password_user"  , $password_baru);
            $query->bindParam(":flag"           , $status_login);
            $query->bindParam(":tgl_expired"    , $tgl_expired);    
            $query->bindParam(":login_failure"  , $login_failure);    
            $query->bindParam(":status_aktif"  , $status_aktif);    
            $query->bindParam(":id_pengguna"    , $id_pengguna);
            $query->execute();  
          }else{
            $password_baru = password_hash($password_baru,PASSWORD_DEFAULT);
            $first_use="1";
    
            $query = $db->prepare("UPDATE sys_user SET password_user=:password_user,first_use=:first_use,status_aktif=:status_aktif WHERE id_pengguna=:id_pengguna");
            $query->bindParam(":status_aktif"   , $status_aktif);
            $query->bindParam(":password_user"  , $password_baru);
            $query->bindParam(":first_use"      , $first_use);
            $query->bindParam(":id_pengguna"    , $id_pengguna);
            $query->execute();  
          }

          $date_modified = date("Y-m-d");
          $time_modified = date("H:i:s");
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
        }        
      }
    }else{
        echo "<script>
        alert('Password Lama anda salah.');
        document.location.href='../renew_user.php?id_pengguna=$id_pengguna&first_use=$first_use';
        </script>";            
    }
}
?> 