<?php
    if(!isset($_GET['id_pengguna'])){
        die("Error: ID Tidak Dimasukkan");
    }
	include "bin/koneksi.php";
	$id_pengguna=$_GET['id_pengguna'];
	$first_use=$_GET['first_use'];
	$database = new Connection();
	$db = $database->openConnection();
    $query = $db->prepare("SELECT * FROM sys_user WHERE id_pengguna=:id_pengguna");
    $query->bindParam(":id_pengguna", $id_pengguna);
    $query->execute();
    if($query->rowCount() == 0){
        die("Error: Pengguna Tidak Ditemukan");
    }else{
        $data = $query->fetch();
    }

    // Ambil data lengkap dari tabel sys_lobby (hanya satu baris karena id tunggal)
$query = $db->prepare("SELECT * FROM sys_lobby WHERE id = 1");
$query->execute();
$lobby = $query->fetch(PDO::FETCH_ASSOC);

// Pastikan data berhasil diambil
if ($lobby) {
    $nama_lobby = $lobby['nama_lobby'];
    $logo_lobby = $lobby['logo_lobby'];
    $background_image = $lobby['background_image'];
    $musik_lobby = $lobby['musik_lobby'];
    $slide1 = $lobby['slide1'];
    $slide2 = $lobby['slide2'];
    $slide3 = $lobby['slide3'];
    $slide4 = $lobby['slide4'];
    $slide5 = $lobby['slide5'];
} else {
    // Jika data tidak ada, set default
    $nama_lobby = 'Default Lobby';
    $logo_lobby = '';
    $slide1 = '';
    $slide2 = '';
    $slide3 = '';
    $slide4 = '';
    $slide5 = '';
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SnapBank</title>
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
  </head>
  <body>
    <div class="container">
      <div class="left-side">
        <div class="text-overlay">
          <h1>Selamat Datang</h1>
          <p>
          SnapBank adalah solusi modern untuk transaksi perbankan yang cepat, aman, dan mudah. Dengan memanfaatkan teknologi QR code, SnapBank memungkinkan nasabah untuk melakukan verifikasi identitas dan memilih layanan seperti setoran atau penarikan hanya dengan satu kali pindai. SnapBank dirancang khusus untuk memberikan pengalaman perbankan yang efisien dan nyaman, menghilangkan antrian panjang dan memastikan privasi nasabah tetap terlindungi. Cukup pindai, verifikasi, dan transaksi semuanya instan dengan SnapBank.
          </p>
        </div>
        <!-- Add video element -->
        <video autoplay loop muted class="video-background">
          <source src="video/vid_login.mp4" type="video/mp4" />
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="right-side">
        <img src="admin/<?php echo htmlspecialchars($logo_lobby); ?>" alt="Logo" class="logo" />
        <form
          class="login-form"
          action="proses/renew_user_save.php"
          method="post"
          novalidate=""
          autocomplete="off"
        >
          <h2>Renew User :</h2>
          <div class="alert">
            <p>User anda telah <b>kadaluwarsa</b>, lakukan ganti password dengan kombinasi huruf kapital, angka dan simbol (minimal 8 digit dan maksimal 10 digit)</p>
          </div>
          <input
            type="text"
            name="nama_user"
            class="form-control"
            placeholder="Username"
            style="text-transform: uppercase"
            value="<?php echo $data['nama_user'] ?>" 
            readonly
          />
          <input
            type="text"
            name="nama_user"
            class="form-control"
            placeholder="Username"
            style="text-transform: uppercase"
            value="<?php echo $data['nama_lengkap'] ?>"
            readonly
          />
          <input
            type="password"
            name="password_lama"
            class="form-control"
            placeholder="Password Lama"
            value=""
            required
          />
          <input
            type="password"
            name="password_baru"
            class="form-control"
            placeholder="Password Baru"
            value=""
            maxlength="10"
            required
          />
          <input type="hidden" name="id_pengguna" value="<?php echo $id_pengguna ?>" readonly>
					<input type="hidden" name="first_use" value="<?php echo $first_use ?>" readonly>

          <input type="submit" class="btn btn-primary" value="Login" />
        </form>
        <h4>
        &copy; 2024-<?php echo date("Y") ?> SnapBank v1.0.0 <br> <h5>Menggunakan Teknologi PT ESTU WEB HOST</h5>
        </h4>
      </div>
    </div>
  </body>
</html>
