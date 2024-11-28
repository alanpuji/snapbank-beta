<?php
if (!isset($_GET['id'])) {
    die("Error: ID Tidak Dimasukkan");
}
$id_customer = $_GET['id'];

$query = $db->prepare("SELECT * FROM customer WHERE id_customer=:id_customer");
$query->bindParam(":id_customer", $id_customer);
$query->execute();
if ($query->rowCount() == 0) {
    die("Error: Customer Tidak Ditemukan");
} else {
    $data = $query->fetch();
}

if (isset($_POST['tombol_edit'])) {
    try {
        // Retrieve and sanitize form inputs
        $id_customer = filter_input(INPUT_POST, 'id_customer', FILTER_SANITIZE_STRING);
        $id_unik = filter_input(INPUT_POST, 'id_unik', FILTER_SANITIZE_STRING);
        $status_aktif = "1";
        $date_modified = date("Y-m-d");
        $time_modified = date('H:i:s');
        $kode_user = $_SESSION['id_pengguna'];

        $query = $db->prepare("UPDATE customer SET status_aktif=:status_aktif,date_modified=:date_modified,time_modified=:time_modified,kode_user=:kode_user,id_unik=:id_unik WHERE id_customer=:id_customer");
        $query->bindParam(":id_customer", $id_customer);
        $query->bindParam(":status_aktif", $status_aktif);
        $query->bindParam(":date_modified", $date_modified);
        $query->bindParam(":time_modified", $time_modified);
        $query->bindParam(":kode_user", $kode_user);
        $query->bindParam(":id_unik", $id_unik);

        $query->execute();

        echo "<script>
        alert('Data disimpan');
        document.location.href='?page=customer';
        </script>";
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage(); // Capture error message
        echo "<script>
            console.error('Database Error: " . addslashes($errorMessage) . "'); // Log error in console
            alert('Data gagal disimpan. Silakan coba lagi.');
            document.location.href='?page=customer';
        </script>";
    }
}
?>

<div class="container-fluid">
    <h2 class="mt-4">Customer Generate &middot <?php echo $data['nama_lengkap'] ?></h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="?page=customer" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group row">
                    <label for="id_customer" class="col-sm-2 col-form-label">ID Customer</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_customer" class="form-control" id="id_customer" readonly value="<?php echo $id_customer ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap" value="<?php echo $data['nama_lengkap'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" name="alamat" class="form-control" id="alamat" value="<?php echo $data['alamat'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nomor_identitas" class="col-sm-2 col-form-label">Nomor Identitas</label>
                    <div class="col-sm-10">
                        <input type="text" name="nomor_identitas" class="form-control" id="nomor_identitas" value="<?php echo $data['nomor_identitas'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="button" name="scan_ktp" id="scan_ktp" class="btn btn-success" value="Scan KTP"> atau
                        <input type="button" name="generate_no_id" id="generate_no_id" class="btn btn-danger" value="Generate Nomor Identitas">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="id_unik" class="col-sm-2 col-form-label">ID Unik</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_unik" class="form-control" id="id_unik">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" name="tombol_edit" id="btnSubmit" class="btn btn-primary" value="Simpan">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>