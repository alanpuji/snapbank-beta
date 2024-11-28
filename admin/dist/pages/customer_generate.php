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

        // Key untuk mengenkripsi id_unik
        $security_key = "sN4pBaNk58*";

        if (strlen($id_unik) >= 10) {
            // Enkripsi id_unik menggunakan hash_hmac dengan algoritma sha256
            $encrypted_id_unik = hash_hmac('sha256', $id_unik, $security_key);

            // Query untuk memperbarui data pelanggan
            $query = $db->prepare("UPDATE customer SET 
                status_aktif=:status_aktif,
                date_modified=:date_modified,
                time_modified=:time_modified,
                kode_user=:kode_user,
                id_unik_ktp=:id_unik_ktp,
                id_unik_ktp_enkrip=:id_unik_ktp_enkrip
                WHERE id_customer=:id_customer");

            // Binding parameter
            $query->bindParam(":id_customer", $id_customer);
            $query->bindParam(":status_aktif", $status_aktif);
            $query->bindParam(":date_modified", $date_modified);
            $query->bindParam(":time_modified", $time_modified);
            $query->bindParam(":kode_user", $kode_user);
            $query->bindParam(":id_unik_ktp", $id_unik);
            $query->bindParam(":id_unik_ktp_enkrip", $encrypted_id_unik);

            // Menjalankan query
            if ($query->execute()) {
                // Jika berhasil, tampilkan alert dan redirect
                echo "<script>
                alert('Data disimpan');
                document.location.href='?page=customer';
                </script>";
            } else {
                // Jika query gagal
                echo "<script>
                alert('Gagal menyimpan data');
                document.location.href='?page=customer';
                </script>";
            }
        } else {
            // Jika panjang id_unik kurang dari 8
            echo "<script>
            alert('Data gagal disimpan, ID Unik tidak valid');
            document.location.href='?page=customer';
            </script>";
        }
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
            <form method="post" action="" id="formCustomerGenerateID">
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
                    <label for="id_unik" class="col-sm-2 col-form-label">ID Unik</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_unik" class="form-control" id="id_unik" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="button" id="btnScanKTP" class="btn btn-info"><i class="fas fa-id-card"></i> Scan KTP</button>
                        <button type="submit" name="tombol_edit" id="btnSubmit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('btnScanKTP').addEventListener('click', function() {
        var id_unik = document.getElementById('id_unik');
        id_unik.value = ""; // Kosongkan kolom ID Unik sebelum pemindaian
        id_unik.readOnly = false; // Izinkan pengisian manual
        id_unik.focus(); // Fokus pada kolom id_unik
        showSweetAlert('info', 'Informasi', "Letakan KTP pada alat pembaca");
    });

    document.getElementById('id_unik').addEventListener('input', function() {
        var id_unik = document.getElementById('id_unik');

        // Menggunakan timeout yang lebih pendek untuk menangani input secara real-time
        setTimeout(function() {
            var value = id_unik.value.trim(); // Ambil nilai tanpa spasi di depan/akhir

            // Jika nilai sudah diisi (tidak kosong), set readonly menjadi true
            if (value.length > 0) {
                id_unik.readOnly = true; // Matikan pengisian manual setelah ada input
            }
        }, 500); // Waktu yang lebih realistis daripada 1000ms (1 detik)
    });

    // Jika Anda ingin mencegah perubahan setelah kolom kehilangan fokus (optional)
    document.getElementById('id_unik').addEventListener('blur', function() {
        var value = this.value.trim();
        if (value.length > 0) {
            this.readOnly = true; // Membuat field readonly setelah selesai mengisi
        }
    });
</script>