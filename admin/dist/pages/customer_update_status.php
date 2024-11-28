<?php
include_once "../../../bin/koneksi.php";
if (isset($_GET["id"])) {
    $id_customer = $_GET["id"];
    $status_aktif = $_GET["status_aktif"];
    $database = new Connection();
    $db = $database->openConnection();
    if ($status_aktif == "1") {
        $status_aktif = "0";
    } else {
        $status_aktif = "1";
    }
    $query = $db->prepare("UPDATE customer SET status_aktif=:status_aktif WHERE id_customer=:id_customer");
    $query->bindParam(":id_customer", $id_customer);
    $query->bindParam(":status_aktif", $status_aktif);
    $query->execute();
    echo "<script>
        alert('Data dikoreksi');
        document.location.href='../../index.php?page=customer';
        </script>";
}
