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
      <img src="images/snapbank.png" alt="Logo" class="logo" />
      <form
        class="login-form"
        action="proses/cek_login.php"
        method="post"
        autocomplete="off">
        <h2>Login :</h2>
        <input
          type="text"
          name="nama_user"
          class="form-control"
          placeholder="Username"
          style="text-transform: uppercase"
          value=""
          required />
        <input
          type="password"
          name="password_user"
          class="form-control"
          placeholder="Password"
          value=""
          autocomplete="off"
          required />
        <img
          src="bin/captcha.php"
          width="100"
          height="40"
          alt="Security Code" />
        <input
          type="text"
          name="input_captcha"
          id="input_captcha"
          placeholder="Masukan security code"
          maxlength="6"
          value=""
          autocomplete="off"
          required />

        <input type="submit" class="btn btn-primary" value="Login" />
        <div class="forgot-password-link" hidden>
          Lupa Password?<a href="lupa_password.php"> klik disini</a>
        </div>
      </form>
      <h4>
        &copy; <?php echo date("Y") ?> SnapBank v1.0.0 <br> <h5>Menggunakan Teknologi PT ESTU WEB HOST</h5>
      </h4>
    </div>
  </div>
</body>

</html>