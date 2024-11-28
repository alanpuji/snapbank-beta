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

?>
<div class="container-fluid">
    <h2 class="mt-4">Customer QRCode &middot <?php echo $data['nama_lengkap'] ?></h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="?page=customer" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a></li>
        <li class="breadcrumb-item active"><a target="_blank" href="dist/pages/customer_qrcode_cetak.php?id=<?php echo $id_customer ?>" class="btn btn-success"><i class="fas fa-print mr-1"></i> Cetak</a></li>
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
            </form>
        </div>
    </div>
</div>