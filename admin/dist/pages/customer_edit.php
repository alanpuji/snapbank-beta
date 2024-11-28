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
        $id_nasabah = filter_input(INPUT_POST, 'cif_nasabah', FILTER_SANITIZE_STRING);
        $nama_lengkap = filter_input(INPUT_POST, 'nama_lengkap', FILTER_SANITIZE_STRING);
        $alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
        $nomor_identitas = filter_input(INPUT_POST, 'no_id', FILTER_SANITIZE_STRING);
        $jenis_kelamin = filter_input(INPUT_POST, 'jenis_kelamin', FILTER_SANITIZE_STRING);
        $no_hp = filter_input(INPUT_POST, 'no_hp', FILTER_SANITIZE_STRING);
        $date_modified = date("Y-m-d");
        $time_modified = date('H:i:s');
        $kode_user = $_SESSION['id_pengguna'];

        $query = $db->prepare("UPDATE customer SET id_nasabah=:id_nasabah, nama_lengkap=:nama_lengkap, alamat=:alamat, nomor_identitas=:nomor_identitas, 
        jenis_kelamin=:jenis_kelamin, date_modified=:date_modified, time_modified=:time_modified, kode_user=:kode_user, nomor_hp=:nomor_hp WHERE id_customer=:id_customer");
        $query->bindParam(":id_customer", $id_customer);
        $query->bindParam(":id_nasabah", $id_nasabah);
        $query->bindParam(":nama_lengkap", $nama_lengkap);
        $query->bindParam(":alamat", $alamat);
        $query->bindParam(":nomor_identitas", $nomor_identitas);
        $query->bindParam(":jenis_kelamin", $jenis_kelamin);
        $query->bindParam(":date_modified", $date_modified);
        $query->bindParam(":time_modified", $time_modified);
        $query->bindParam(":kode_user", $kode_user);
        $query->bindParam(":nomor_hp", $no_hp);

        $query->execute();

        echo "<script>
        alert('Data dikoreksi');
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
    <h2 class="mt-4">Customer Koreksi &middot <?php echo $data['nama_lengkap'] ?></h2>
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
                    <label for="id_nasabah" class="col-sm-2 col-form-label">ID Nasabah</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_nasabah" class="form-control" id="id_nasabah" readonly value="<?php echo $data['id_nasabah'] ?>">
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
                    <label for="nik" class="col-sm-2 col-form-label">Nomor Identitas</label>
                    <div class="col-sm-10">
                        <input type="text" name="nik" class="form-control" id="nik" value="<?php echo $data['nomor_identitas'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nomor_hp" class="col-sm-2 col-form-label">Nomor HP</label>
                    <div class="col-sm-10">
                        <input type="text" name="nomor_hp" class="form-control" id="nomor_hp" value="<?php echo $data['nomor_hp'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="button" name="cek_nasabah_from_core" id="cek_nasabah_from_core" class="btn btn-success" value="Cek Nasabah">
                    </div>
                </div>
                <div id="detail_nasabah_from_cbs"></div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" name="tombol_edit" id="btnSubmit" class="btn btn-primary" value="Koreksi">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>