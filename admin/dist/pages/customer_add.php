<?php
if (isset($_POST['tombol_tambah'])) {
    try {
        $date_modified = date("Y-m-d");
        $date_prefix = date("dmY"); // Format as ddmmyyyy

        // Get the latest id_customer for the current date
        $stm = $db->prepare("SELECT MAX(id_customer) as maxKode FROM customer WHERE id_customer LIKE :date_prefix");
        $prefix_like = $date_prefix . '%'; // Format for LIKE operator
        $stm->bindParam(':date_prefix', $prefix_like);
        $stm->execute();
        $data = $stm->fetch();
        $maxKode = $data["maxKode"];

        // Extract the last two digits and increment
        if ($maxKode) {
            $noUrut = (int) substr($maxKode, -2) + 1; // Increment last two digits
        } else {
            $noUrut = 1; // Start from 1 if no matching data
        }
        $id_customer = $date_prefix . sprintf("%02d", $noUrut); // Combine date prefix and two-digit number

        // Retrieve and sanitize form inputs
        $id_nasabah = filter_input(INPUT_POST, 'cif_nasabah', FILTER_SANITIZE_STRING);
        $nama_lengkap = filter_input(INPUT_POST, 'nama_lengkap', FILTER_SANITIZE_STRING);
        $alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
        $nomor_identitas = filter_input(INPUT_POST, 'no_id', FILTER_SANITIZE_STRING);
        $jenis_kelamin = filter_input(INPUT_POST, 'jenis_kelamin', FILTER_SANITIZE_STRING);
        $no_hp = filter_input(INPUT_POST, 'no_hp', FILTER_SANITIZE_STRING);
        $status_aktif = "0";
        $time_modified = date('H:i:s');
        $kode_user = $_SESSION['id_pengguna'];

        // Pengecekan apakah nomor_identitas sudah ada di database
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM customer WHERE nomor_identitas = :nomor_identitas");
        $checkStmt->bindParam(':nomor_identitas', $nomor_identitas);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();

        // Jika nomor_identitas sudah ada
        if ($exists > 0) {
            echo "<script>
                alert('Nomor Identitas sudah terdaftar.');
                document.location.href='?page=customer_add'; // Redirect kembali ke form tambah
            </script>";
            exit; // Hentikan eksekusi lebih lanjut
        }

        // Insert data into customer table
        $query = $db->prepare("INSERT INTO customer(id_customer, id_nasabah, nama_lengkap, alamat, nomor_identitas, jenis_kelamin, status_aktif, date_modified, time_modified, kode_user, tgl_daftar, nomor_hp) 
                               VALUES (:id_customer, :id_nasabah, :nama_lengkap, :alamat, :nomor_identitas, :jenis_kelamin, :status_aktif, :date_modified, :time_modified, :kode_user, :tgl_daftar, :nomor_hp)");

        $query->bindParam(":id_customer", $id_customer);
        $query->bindParam(":id_nasabah", $id_nasabah);
        $query->bindParam(":nama_lengkap", $nama_lengkap);
        $query->bindParam(":alamat", $alamat);
        $query->bindParam(":nomor_identitas", $nomor_identitas);
        $query->bindParam(":jenis_kelamin", $jenis_kelamin);
        $query->bindParam(":status_aktif", $status_aktif);
        $query->bindParam(":date_modified", $date_modified);
        $query->bindParam(":time_modified", $time_modified);
        $query->bindParam(":kode_user", $kode_user);
        $query->bindParam(":tgl_daftar", $date_modified);
        $query->bindParam(":nomor_hp", $no_hp);

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
            document.location.href='?page=customer_add';
        </script>";
    }
}

?>



<div class="container-fluid">
    <h2 class="mt-4">Tambah Customer</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="?page=customer" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user mr-1"></i>Detail</div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group row">
                    <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-10">
                        <input type="text" name="nik" class="form-control" id="nik" placeholder="Nomor Induk Kependudukan" value="" required>
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
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" name="tombol_tambah" id="btnSubmit" class="btn btn-primary" value="Simpan">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>