<?php
set_time_limit(0);
session_start();
if (!isset($_SESSION['id_pengguna']))
{
echo "<script>
alert('Anda belum Log-in');
document.location.href='../index.php';
</script>";
exit;
}
?>