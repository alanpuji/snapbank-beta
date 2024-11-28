<?php
// Ambil data yang sudah ada sebelumnya dari database (untuk mempertahankan nilai yang ada)
$query = $db->prepare("SELECT * FROM sys_lobby WHERE id = 1");
$query->execute();
$existing_data = $query->fetch(PDO::FETCH_ASSOC);

// Cek apakah tombol simpan sudah diklik
if (isset($_POST['tombol_simpan'])) {
    // Ambil data dari form
    $nama_lobby = $_POST['nama_lobby'];

    // Cek dan buat folder upload jika belum ada
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Membuat folder uploads jika belum ada
    }

    // Inisialisasi variabel target gambar
    $logo_lobby_target = $existing_data['logo_lobby']; // Default ke gambar lama
    $background_image = $existing_data['background_image']; // Default ke gambar lama
    $musik_lobby = $existing_data['musik_lobby']; 
    $slide1_target = $existing_data['slide1']; // Default ke slide lama
    $slide2_target = $existing_data['slide2']; // Default ke slide lama
    $slide3_target = $existing_data['slide3']; // Default ke slide lama
    $slide4_target = $existing_data['slide4']; // Default ke slide lama
    $slide5_target = $existing_data['slide5']; // Default ke slide lama

    // Fungsi untuk proses upload
    function upload_file($file, $upload_dir, $existing_file) {
        if (!empty($file['name'])) {
            $file_name = $file['name'];
            $target_path = $upload_dir . basename($file_name);
            if ($file['error'] != 0) {
                return null; // Error jika ada masalah saat upload
            } else {
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    return $target_path; // Kembalikan path file jika berhasil
                }
            }
        }
        return $existing_file; // Jika tidak ada file baru, kembalikan file yang sudah ada
    }

    // Proses upload logo_lobby jika ada
    $logo_lobby_target = upload_file($_FILES['logo_lobby'], $upload_dir, $logo_lobby_target);
    
    // Proses upload background_image jika ada
    $background_image = upload_file($_FILES['background_image'], $upload_dir, $background_image);
     // Proses upload background_image jika ada
     $musik_lobby = upload_file($_FILES['musik_lobby'], $upload_dir, $musik_lobby);

    // Proses upload slide1 jika ada
    $slide1_target = upload_file($_FILES['slide1'], $upload_dir, $slide1_target);
    // Proses upload slide2 jika ada
    $slide2_target = upload_file($_FILES['slide2'], $upload_dir, $slide2_target);
    // Proses upload slide3 jika ada
    $slide3_target = upload_file($_FILES['slide3'], $upload_dir, $slide3_target);
    // Proses upload slide4 jika ada
    $slide4_target = upload_file($_FILES['slide4'], $upload_dir, $slide4_target);
    // Proses upload slide5 jika ada
    $slide5_target = upload_file($_FILES['slide5'], $upload_dir, $slide5_target);

    // Update data ke database (hanya ada satu data)
    try {
        $query = $db->prepare("UPDATE sys_lobby 
                               SET nama_lobby = :nama_lobby, 
                                   logo_lobby = :logo_lobby, 
                                   background_image = :background_image, 
                                   musik_lobby = :musik_lobby, 
                                   slide1 = :slide1, 
                                   slide2 = :slide2, 
                                   slide3 = :slide3, 
                                   slide4 = :slide4, 
                                   slide5 = :slide5
                               WHERE id = 1");

        // Binding parameter
        $query->bindParam(":nama_lobby", $nama_lobby);
        $query->bindParam(":logo_lobby", $logo_lobby_target);
        $query->bindParam(":background_image", $background_image);
        $query->bindParam(":musik_lobby", $musik_lobby);
        $query->bindParam(":slide1", $slide1_target);
        $query->bindParam(":slide2", $slide2_target);
        $query->bindParam(":slide3", $slide3_target);
        $query->bindParam(":slide4", $slide4_target);
        $query->bindParam(":slide5", $slide5_target);

        // Eksekusi query
        $query->execute();
        echo "<script>
                alert('Data berhasil diperbarui');
                document.location.href='index.php?page=sys_lobby'; // Redirect ke halaman lain setelah berhasil
              </script>";
    } catch (PDOException $e) {
        echo "<script>
                alert('Data gagal diperbarui: " . $e->getMessage() . "');
                document.location.href='index.php?page=sys_lobby'; // Redirect ke halaman lain jika gagal
              </script>";
    }
}
?>

<style>
    /* Penataan Form */
.form-group {
    margin-bottom: 1.5rem; /* Memberikan jarak antar form */
}

.form-group label {
    font-weight: bold; /* Membuat label lebih tegas */
    margin-bottom: 0.5rem; /* Menambahkan jarak antara label dan input */
}

.form-group .form-control {
    padding: 0.75rem; /* Memberikan padding agar input lebih besar */
    font-size: 1rem; /* Menyesuaikan ukuran font agar lebih jelas */
    border-radius: 0.25rem; /* Sedikit bulatkan border */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Menambahkan sedikit bayangan pada input */
}

.form-group img {
    margin-top: 10px; /* Memberikan jarak antara gambar dan input */
    border-radius: 0.5rem; /* Membuat gambar sedikit melengkung */
}

.btn {
    padding: 0.75rem 1.5rem;
    font-size: 1.2rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.btn-success {
    background-color: #28a745; /* Hijau */
    color: white;
    border: none;
    transition: background-color 0.3s ease;
}

.btn-success:hover {
    background-color: #218838; /* Mengubah warna saat hover */
}

/* Responsif untuk ukuran mobile */
@media (max-width: 768px) {
    .form-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-group label {
        width: 100%; /* Membuat label memenuhi lebar form */
    }

    .form-group .form-control {
        width: 100%; /* Membuat input memenuhi lebar form */
    }

    /* Mengatur tampilannya agar lebih menarik */
.file-upload-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
}

.file-upload-wrapper .file-input {
    font-size: 16px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    opacity: 0; /* Sembunyikan input file asli */
}

.file-upload-wrapper .file-upload-label {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    text-align: center;
    line-height: 36px;
    transition: background-color 0.3s ease;
}

.file-upload-wrapper .file-upload-label:hover {
    background-color: #45a049;
}

/* Styling untuk menampilkan nama file yang di-upload */
.file-name {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

.file-name p {
    margin: 0;
}

/* Audio player styling */
audio {
    margin-top: 10px;
    width: 100%;
}

}

</style>
<!-- HTML Form untuk Pengisian Data Lobby -->
<div class="container-fluid">
    <h2 class="mt-4">Pengisian Konfigurasi Lobby</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            <a href="?page=sys_lobby" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus mr-1"></i> Edit Data Lobby
        </div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                <!-- Mulai row untuk kanan kiri -->
                <div class="row">
                    <!-- Kolom kiri: label -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama_lobby">Nama Lobby</label>
                        </div>
                    </div>
                    <!-- Kolom kanan: input -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" name="nama_lobby" class="form-control" id="nama_lobby" placeholder="Nama Lobby" 
                                   value="<?php echo isset($existing_data['nama_lobby']) ? $existing_data['nama_lobby'] : ''; ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Kolom logo lobby -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="logo_lobby">Logo Lobby</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="logo_lobby" class="form-control" id="logo_lobby">
                            <?php if (isset($existing_data['logo_lobby']) && !empty($existing_data['logo_lobby'])) { ?>
                                <img src="<?php echo $existing_data['logo_lobby']; ?>" alt="Logo" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom background image -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="background_image">Background Lobby</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="background_image" class="form-control" id="background_image">
                            <?php if (isset($existing_data['background_image']) && !empty($existing_data['background_image'])) { ?>
                                <img src="<?php echo $existing_data['background_image']; ?>" alt="Background" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="musik_lobby">Musik Lobby</label>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <div class="file-upload-wrapper">
                <input type="file" name="musik_lobby" class="form-control file-input" id="musik_lobby">
                <label for="musik_lobby" class="file-upload-label">Pilih File Musik</label>
                <div class="file-name" id="file-name"></div>
                <?php if (isset($existing_data['musik_lobby']) && !empty($existing_data['musik_lobby'])) { ?>
                    <audio controls>
                        <source src="<?php echo $existing_data['musik_lobby']; ?>" type="audio/mp3">
                        Your browser does not support the audio element.
                    </audio>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


                <!-- Kolom slide 1 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="slide1">Slide 1</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="slide1" class="form-control" id="slide1">
                            <?php if (isset($existing_data['slide1']) && !empty($existing_data['slide1'])) { ?>
                                <img src="<?php echo $existing_data['slide1']; ?>" alt="Slide 1" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom slide 2 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="slide2">Slide 2</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="slide2" class="form-control" id="slide2">
                            <?php if (isset($existing_data['slide2']) && !empty($existing_data['slide2'])) { ?>
                                <img src="<?php echo $existing_data['slide2']; ?>" alt="Slide 2" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom slide 3 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="slide3">Slide 3</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="slide3" class="form-control" id="slide3">
                            <?php if (isset($existing_data['slide3']) && !empty($existing_data['slide3'])) { ?>
                                <img src="<?php echo $existing_data['slide3']; ?>" alt="Slide 3" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom slide 4 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="slide4">Slide 4</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="slide4" class="form-control" id="slide4">
                            <?php if (isset($existing_data['slide4']) && !empty($existing_data['slide4'])) { ?>
                                <img src="<?php echo $existing_data['slide4']; ?>" alt="Slide 4" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom slide 5 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="slide5">Slide 5</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" name="slide5" class="form-control" id="slide5">
                            <?php if (isset($existing_data['slide5']) && !empty($existing_data['slide5'])) { ?>
                                <img src="<?php echo $existing_data['slide5']; ?>" alt="Slide 5" width="100">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Tombol simpan -->
                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <input type="submit" name="tombol_simpan" class="btn btn-success" value="Simpan">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
