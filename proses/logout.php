<?php
include_once "../bin/koneksi.php";
$database = new Connection();
$db = $database->openConnection();
session_start();
$id_pengguna=$_SESSION['id_pengguna'];
$query = $db->prepare("UPDATE sys_user SET flag='0',login_failure='0' WHERE id_pengguna=:id_pengguna");
$query->bindParam(":id_pengguna", $id_pengguna);
$query->execute();    

include_once "sys_log_user.php";

unset($_SESSION['id_pengguna']);
unset($_SESSION['nama_user']);
session_destroy();
echo "<script>
document.location.href='../index.php';
</script>";
?>