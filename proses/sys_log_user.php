<?php
    try
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_pengguna = $_SESSION['id_pengguna'];
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
    }
    catch (PDOException $e)
    {
        $pesan=$e->getMessage();
        echo $pesan;
    }
?>
