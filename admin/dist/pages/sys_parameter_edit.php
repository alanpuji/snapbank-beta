<?php
if(!isset($_GET['id'])){
    die("Error: ID Tidak Dimasukkan");
}
    $kode_parameter=$_GET['id'];
    $query = $db->prepare("SELECT * FROM sys_parameter WHERE kode_parameter=:kode_parameter");
    $query->bindParam(":kode_parameter", $kode_parameter);
    $query->execute();
    if($query->rowCount() == 0){
        die("Error: ID Tidak Ditemukan");
    }else{
        $data = $query->fetch();
    }

if(isset($_POST['tombol_simpan'])){
    try
    {
        $kode_parameter = htmlentities($_POST['kode_parameter']);
        $nama_parameter = htmlentities($_POST['nama_parameter']);
        $value_parameter = htmlentities($_POST['value_parameter']);
        $keterangan = htmlentities($_POST['keterangan']);
        $kode_user=$_SESSION['id_pengguna'];
        $date_modified = date("Y-m-d");
        $time_modified = date("H:i:s");

        $query = $db->prepare("UPDATE sys_parameter SET nama_parameter=:nama_parameter
        ,value_parameter=:value_parameter,keterangan=:keterangan,kode_user=:kode_user,date_modified=:date_modified
        ,time_modified=:time_modified WHERE kode_parameter=:kode_parameter");

        $query->bindParam(":kode_parameter"    , $kode_parameter);
        $query->bindParam(":nama_parameter"      , $nama_parameter);
        $query->bindParam(":value_parameter"   , $value_parameter);
        $query->bindParam(":keterangan"     , $keterangan);
        $query->bindParam(":kode_user"           , $kode_user);
        $query->bindParam(":date_modified"     , $date_modified);
        $query->bindParam(":time_modified"    , $time_modified);
        $query->execute();
        echo "<script>
        alert('Data disimpan');
        document.location.href='index.php?page=sys_parameter';
        </script>";

    }
    catch (PDOException $e)
    {
        echo "<script>
        alert('Data gagal disimpan');
        document.location.href='index.php?page=sys_parameter';
        </script>";
    }
}
?>
                    <div class="container-fluid">
                        <h2 class="mt-4">Ubah Parameter</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><a href="?page=sys_parameter" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a></li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
                            <div class="card-body">
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="kode_parameter" class="col-sm-2 col-form-label">Kode Parameter</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="kode_parameter" class="form-control" id="kode_parameter" placeholder="Kode Parameter" value="<?php echo $data['kode_parameter']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama_parameter" class="col-sm-2 col-form-label">Nama Parameter</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nama_parameter" class="form-control" id="nama_parameter" placeholder="Nama Parameter" value="<?php echo $data['nama_parameter']?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="value_parameter" class="col-sm-2 col-form-label">Value Parameter</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="value_parameter" class="form-control" id="value_parameter" placeholder="Value Parameter" value="<?php echo $data['value_parameter']?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-10">
                                            <textarea name="keterangan" class="form-control" id="keterangan" placeholder="Keterangan"><?php echo $data['keterangan']?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <input type="submit" name="tombol_simpan" id="btnSubmit" class="btn btn-primary" value="Edit">                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>