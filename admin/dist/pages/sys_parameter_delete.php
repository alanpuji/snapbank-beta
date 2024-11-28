<?php
    include_once "../../../bin/koneksi.php";
    if(isset($_GET["id"])){
        $kode_parameter=$_GET["id"];
        $database = new Connection();
        $db = $database->openConnection();
        $query = $db->prepare("DELETE FROM sys_parameter WHERE kode_parameter=:kode_parameter");
        $query->bindParam(":kode_parameter", $kode_parameter);
        $query->execute();
        echo "<script>
        alert('Data dihapus');
        document.location.href='../../index.php?page=sys_parameter';
        </script>";    
    }
?>