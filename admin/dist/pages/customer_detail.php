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
    <h2 class="mt-4">Customer Detail &middot <?php echo $data['nama_lengkap'] ?></h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="?page=customer" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li><a class="btn btn-primary" data-toggle="tab" href="#detail"><i class="fas fa-user mr-1"></i> Detail</a></li>
                <li><a class="btn btn-primary" data-toggle="tab" href="#transaksi"><i class="fas fa-list mr-1"></i> Transaksi</a></li>
            </ul>
            <div class="tab-content">
                <div id="detail" class="tab-pane fade in active">
                    <div class="card mb-4">
                        <div class="card-header bg-warning"><i class="fas fa-user mr-1"></i> Detail</div>
                        <div class="card-body">
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
                                <label for="nomor_identitas" class="col-sm-2 col-form-label">Nomor Identitas</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nomor_identitas" class="form-control" id="nomor_identitas" value="<?php echo $data['nomor_identitas'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nomor_hp" class="col-sm-2 col-form-label">Nomor HP</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nomor_hp" class="form-control" id="nomor_hp" value="<?php echo $data['nomor_hp'] ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="transaksi" class="tab-pane fade in">
                    <div class="card mb-4">
                        <div class="card-header bg-warning"><i class="fas fa-user mr-1"></i> Detail</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal Jam</th>
                                            <th>Nomor Rekening</th>
                                            <th>Jenis</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Cetak</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Jam</th>
                                            <th>Nomor Rekening</th>
                                            <th>Jenis</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Cetak</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        try {
                                            $no = 1;
                                            $sql = "SELECT * FROM transaksi_tab 
                                                            WHERE id_customer='$id_customer' ORDER BY id_trans DESC";
                                            foreach ($db->query($sql) as $row) {
                                                $cetak_trans = "";
                                                if ($row['jenis_trans'] == "100") {
                                                    $jenis_trans = "Setoran Tunai";
                                                } elseif ($row['jenis_trans'] == "200") {
                                                    $jenis_trans = "Pengambilan Tunai";
                                                }
                                                if ($row['status_trans'] == "0") {
                                                    $status_trans = "<a href='#' title='Belum Diproses' class='btn btn-secondary'>Belum Diproses</a>";
                                                } elseif ($row['status_trans'] == "1") {
                                                    $status_trans = "<a href='#' title='OTP Terkirim' class='btn btn-warning'>OTP Terkirim</a>";
                                                } elseif ($row['status_trans'] == "2") {
                                                    $status_trans = "<a href='#' title='Ditolak' class='btn btn-success'>Ditolak</a>";
                                                } elseif ($row['status_trans'] == "3") {
                                                    $status_trans = "<a href='#' title='Diproses' class='btn btn-success'>Diproses</a>";
                                                    $cetak_trans = "<a target='_blank' href='dist/pages/cetak_transaksi.php?id=$row[id_trans]' title='Cetak Transaksi' class='btn btn-primary'><i class='fas fa-print mr-1'></i> Cetak Transaksi</a>";
                                                }
                                        ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo DatetoIndo($row['tgl_trans']) ?> &middot <?php echo $row['jam_trans'] ?></td>
                                                    <td><?php echo $row['no_rekening'] ?></td>
                                                    <td><?php echo $jenis_trans ?></td>
                                                    <td><?php echo number_format($row['nominal']) ?></td>
                                                    <td><?php echo $status_trans ?></td>
                                                    <td><?php echo $cetak_trans ?></td>
                                                </tr>
                                        <?php
                                                $no++;
                                            }
                                        } catch (PDOException $e) {
                                            echo "There is some problem in connection: " . $e->getMessage();
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</div>
</div>