<?php
include "../../../bin/koneksi.php";
$nik = $_POST['nik'];

if (empty($nik)) {
    echo "<div class='alert alert-danger'><p class='mb-0'><i class='fas fa-info mr-1'></i> Kolom NIK masih kosong</p></div>";
} else {
    $database = new Connection_Core();
    $db_core = $database->openConnection();
    $query = $db_core->prepare("SELECT nasabah_id,nama_nasabah,alamat,no_id,jenis_kelamin,hp FROM nasabah WHERE no_id=:nik");
    $query->bindParam(":nik", $nik);
    $query->execute();
    if ($query->rowCount() == 0) {
        echo "<div class='alert alert-danger'><p class='mb-0'><i class='fas fa-info mr-1'></i> Nasabah tidak ditemukan</p></div>";
    } else {
        $data = $query->fetch();
        echo "<div class='alert alert-success'><p class='mb-0'><i class='fas fa-info mr-1'></i> Nasabah ditemukan</p></div>";
        echo "
          <div class='alert alert-primary'><p class='mb-0'><i class='fas fa-info mr-1'></i> Pastikan Detail Nasabah sesuai dan aktif</p></div>
          <div class='form-group row'>
          <label for='cif_nasabah' class='col-sm-2 col-form-label'>CIF Nasabah</label>
          <div class='col-sm-10'>
              <input type='text' name='cif_nasabah' class='form-control' id='cif_nasabah' value='" . $data['nasabah_id'] . "' readonly>
          </div>
          </div>
          <div class='form-group row'>
          <label for='nama_lengkap' class='col-sm-2 col-form-label'>Nama Nasabah</label>
          <div class='col-sm-10'>
              <input type='text' name='nama_lengkap' class='form-control' id='nama_lengkap' value='" . $data['nama_nasabah'] . "' readonly>
          </div>
          </div>
          <div class='form-group row'>
          <label for='alamat' class='col-sm-2 col-form-label'>Alamat Nasabah</label>
          <div class='col-sm-10'>
              <input type='text' name='alamat' class='form-control' id='alamat' value='" . $data['alamat'] . "' readonly>
          </div>
          </div>  
          <div class='form-group row'>
          <label for='no_id' class='col-sm-2 col-form-label'>Nomor Identitas</label>
          <div class='col-sm-10'>
              <input type='text' name='no_id' class='form-control' id='no_id' value='" . $data['no_id'] . "' readonly>
          </div>
          </div>  
          <div class='form-group row'>
          <label for='no_hp' class='col-sm-2 col-form-label'>Nomor HP</label>
          <div class='col-sm-10'>
              <input type='text' name='no_hp' class='form-control' id='no_hp' value='" . $data['hp'] . "' readonly>
          </div>
          </div>  
          <div class='form-group row'>
          <label for='jenis_kelamin' class='col-sm-2 col-form-label'>Jenis Kelamin</label>
          <div class='col-sm-10'>
              <input type='text' name='jenis_kelamin' class='form-control' id='jenis_kelamin' value='" . $data['jenis_kelamin'] . "' readonly>
          </div>
          </div>  
          ";
    }
}
