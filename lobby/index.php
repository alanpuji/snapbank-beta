<?php
include "../bin/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

$database = new Connection();
$db = $database->openConnection();

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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapBank &middot Lobby</title>
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico" />

    <!-- Link ke CSS Slick Slider -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <!-- Link ke Google Fonts untuk font yang lebih menarik -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <!-- Add SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.4/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.4/dist/sweetalert2.min.js"></script>
    <style>
        .modal-content {
            display: flex;
            flex-direction: column;
            text-align: center;
            width: 100%;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            top: 15%;
        }

        .ktp-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            visibility: hidden;
        }

        .body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f8f8;
            color: #333;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-image: url("http://<?php echo $_SERVER['HTTP_HOST']; ?>/snapbank/admin/<?php echo htmlspecialchars($background_image); ?>");
            background-size: cover;
            /* Agar gambar menutupi seluruh halaman */
            background-position: center;
            /* Menjaga gambar tetap terpusat */
            background-attachment: fixed;
            /* Membuat gambar tetap saat scroll */
        }

        /* Menyembunyikan audio supaya tidak terlihat oleh pengunjung */
        .audio {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            z-index: -1;
            /* Memastikan audio berada di belakang konten */
        }
    </style>
    <script>
        function playAudio() {
            var audio = document.getElementById('myAudio');
            audio.play(); // Memulai pemutaran audio
        }
    </script>

</head>

<body class="body">

    <audio id="myAudio" class="audio" autoplay loop>
        <source src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/snapbank/admin/<?php echo htmlspecialchars($musik_lobby); ?>" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <header>
        <?php echo htmlspecialchars($nama_lobby); ?>
        <h3>
            <div id="dateTimeDisplay"></div>
        </h3>
    </header>
    <BR>
    <center> <img src="../admin/<?php echo htmlspecialchars($logo_lobby); ?>" alt="Logo Lobby" width="20%" onclick="playAudio()"></center>
    <div class="container">
        <div id="countdownContainer" style="display: none;">
            <p>Waktu tersisa: <span id="timer">00:00</span></p>
        </div>

        <!-- Bagian Kiri (Slider Gambar) -->
        <div class="left-side">
            <div class="slider">
                <?php if (!empty($slide1)): ?>
                    <img src="../admin/<?php echo htmlspecialchars($slide1); ?>" alt="Gambar 1">
                <?php endif; ?>

                <?php if (!empty($slide2)): ?>
                    <img src="../admin/<?php echo htmlspecialchars($slide2); ?>" alt="Gambar 2">
                <?php endif; ?>

                <?php if (!empty($slide3)): ?>
                    <img src="../admin/<?php echo htmlspecialchars($slide3); ?>" alt="Gambar 3">
                <?php endif; ?>

                <?php if (!empty($slide4)): ?>
                    <img src="../admin/<?php echo htmlspecialchars($slide4); ?>" alt="Gambar 4">
                <?php endif; ?>

                <?php if (!empty($slide5)): ?>
                    <img src="../admin/<?php echo htmlspecialchars($slide5); ?>" alt="Gambar 5">
                <?php endif; ?>
            </div>
        </div>

        <!-- Bagian Kanan (Tombol Transaksi) -->
        <div class="right-side">
            <button onclick="showModal('cekSaldo')">
                <i class="fas fa-wallet"></i> Cek Saldo
            </button>
            <button onclick="showModal('setorTunai')">
                <i class="fas fa-plus-circle"></i> Setor Tunai
            </button>
            <button onclick="showModal('ambilTunai')">
                <i class="fas fa-minus-circle"></i> Ambil Tunai
            </button>
            <button class="bantuan-btn" onclick="showModalBantuan()">
                <i class="fas fa-question-circle"></i> Bantuan
            </button>
        </div>

    </div>

    <div id="ktpModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Letakkan e-KTP pada Alat Pembaca</h2>
            <p>Pastikan e-KTP Anda dalam posisi yang benar agar dapat dibaca dengan mudah oleh alat pembaca.</p>
            <div class="reader-image">
                <img src="http://localhost/snapbank/images/ilustrasi_rfid_reader.webp" alt="Alat Pembaca KTP" />
            </div>
            <input type="text" id="ktpNumber" name="ktpNumber" placeholder="Nomor KTP" class="hidden-input" required>
        </div>
    </div>

    <div id="identitasModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Halo, <span id="customerName"></span></h2>
            <p>Pastikan identitas sesuai dengan e-KTP Anda.</p>
            <table class="modal-table">
                <tr>
                    <th>Nomor Identitas</th>
                    <td id="modalNomorIdentitas"></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td id="modalAlamat"></td>
                </tr>
                <tr>
                    <th>CIF</th>
                    <td id="modalCIF"></td>
                </tr>
            </table>
            <br>
            <button class="confirm-button" onclick="proceedTransaction()"><span id="menuTransaksi"></span></button>

        </div>
    </div>

    <div id="cekSaldoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Cek Saldo Simpanan</h2>
            <label for="nomorRekeningCekSaldo">Nomor Rekening:</label>
            <select id="nomorRekeningCekSaldo" class="modal-select" required></select>
            <br>
            <button class="confirm-button" onclick="kirimOTP()">Proses</button>

        </div>
    </div>

    <div id="cekSaldoOTPModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Saldo Simpanan</h2>
            <table class="modal-table">
                <tr>
                    <th>Nomor Rekening</th>
                    <td id="accountNumberCekBalanceOTP"></td>
                </tr>
            </table>
            <input type="text" id="otpCode" name="otpCode" placeholder="Masukan Kode OTP" maxlength="6" aria-label="Kode OTP" required>
            <div class="numpad-container" id="numpad-container">
                <button type="button" class="numpad-btn" onclick="pressNumpad(1, 'otpCode', 'otp')">1</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(2, 'otpCode', 'otp')">2</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(3, 'otpCode', 'otp')">3</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(4, 'otpCode', 'otp')">4</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(5, 'otpCode', 'otp')">5</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(6, 'otpCode', 'otp')">6</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(7, 'otpCode', 'otp')">7</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(8, 'otpCode', 'otp')">8</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(9, 'otpCode', 'otp')">9</button>
                <button type="button" class="numpad-btn numpad-btn-c" onclick="clearAmount('otpCode')">C</button>
                <button type="button" class="numpad-btn numpad-btn-zero" onclick="pressNumpad(0, 'otpCode', 'otp')">0</button>
                <button type="button" class="numpad-btn numpad-btn-back" onclick="backspace('otpCode')">←</button> <!-- Backspace button -->
            </div>
            <button class="confirm-button" onclick="prosesOTP()" id="btnprosescekSaldoOTPModal">Proses</button>

            <span id="label_output_saldo_akhir_cbs"></span><br>
            <span id="output_saldo_akhir_cbs"></span><br>
            <span id="sublabel_output_saldo_akhir_cbs"></span>


        </div>
    </div>

    <div id="setoranModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Setoran Tunai</h2>
            <label for="nomorRekeningSetoran">Nomor Rekening:</label>
            <select id="nomorRekeningSetoran" class="modal-select"></select>
            <label for="nominalSetoran">Nominal Setoran:</label>
            <input type="text" id="nominalSetoran" name="nominalSetoran" placeholder="Masukan Nominal Setoran" aria-label="Nominal Setoran" required readonly>
            <input type="hidden" id="rawnominalSetoran" name="rawnominalSetoran" required>
            <input type="hidden" id="setoranJenisTrans" class="modal-input" value="100" readonly />

            <label for="depositorName">Nama Penyetor:</label>
            <div class="radio-container">
                <input type="radio" id="setoransameName" name="setoransameName" value="yes" onclick="toggleNamaPenyetor(true)" checked>
                <label for="setoransameName">Sama dengan Nama Pemilik Rekening</label><br>
                <input type="radio" id="setorandifferentName" name="setoransameName" value="no" onclick="toggleNamaPenyetor(false)">
                <label for="setorandifferentName">Berbeda dengan Nama Pemilik Rekening</label>
            </div>
            <input type="text" id="depositorName" name="depositorName" placeholder="Masukkan Nama Penyetor" required>

            <div id="sumberDanaContainer" style="display:none;">
                <label for="sumberDana">Sumber Dana (wajib diisi diatas Rp100juta):</label>
                <select id="sumberDana" name="sumberDana" aria-label="Sumber Dana" class="modal-select" required>
                    <option value="" disabled selected>- Pilih Sumber Dana -</option>
                    <option value="Hasil usaha atau dagang">Hasil usaha atau dagang</option>
                    <option value="Warisan">Warisan</option>
                    <option value="Investasi">Investasi</option>
                    <option value="Tabungan">Tabungan</option>
                    <option value="Gaji">Gaji</option>
                </select>
            </div>

            <div class="suggested-amounts">
                <button type="button" class="suggestion-btn" onclick="selectAmount(100000, 'nominalSetoran')">Rp 100.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(250000, 'nominalSetoran')">Rp 250.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(500000, 'nominalSetoran')">Rp 500.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(1000000, 'nominalSetoran')">Rp 1.000.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(2000000, 'nominalSetoran')">Rp 2.000.000</button>
            </div>
            <div class="numpad-container" id="numpad-container">
                <button type="button" class="numpad-btn" onclick="pressNumpad(1, 'nominalSetoran', 'nonotp')">1</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(2, 'nominalSetoran', 'nonotp')">2</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(3, 'nominalSetoran', 'nonotp')">3</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(4, 'nominalSetoran', 'nonotp')">4</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(5, 'nominalSetoran', 'nonotp')">5</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(6, 'nominalSetoran', 'nonotp')">6</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(7, 'nominalSetoran', 'nonotp')">7</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(8, 'nominalSetoran', 'nonotp')">8</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(9, 'nominalSetoran', 'nonotp')">9</button>
                <button type="button" class="numpad-btn numpad-btn-c" onclick="clearAmount('nominalSetoran')">C</button>
                <button type="button" class="numpad-btn numpad-btn-zero" onclick="pressNumpad(0, 'nominalSetoran', 'nonotp')">0</button>
                <button type="button" class="numpad-btn numpad-btn-back" onclick="backspace('nominalSetoran')">←</button> <!-- Backspace button -->
            </div>

            <button class="confirm-button" onclick="submitTransactionSetoran()">Proses</button>

        </div>
    </div>

    <div id="pengambilanModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Pengambilan Tunai</h2>
            <label for="nomorRekeningPengambilan">Nomor Rekening:</label>
            <select id="nomorRekeningPengambilan" class="modal-select" onchange="fetchSaldoAkhir()"></select>
            <br>
            <label for="nominalPengambilan">Nominal Pengambilan:</label>
            <input type="text" id="nominalPengambilan" name="nominalPengambilan" placeholder="Masukan Nominal Pengambilan" aria-label="Nominal Pengambilan" required readonly>
            <input type="hidden" id="rawnominalPengambilan" name="rawnominalPengambilan" required>
            <input type="hidden" id="pengambilanJenisTrans" class="modal-input" value="200" readonly />
            <span hidden id="pengambilanSaldoAkhir"></span>
            <span hidden id="pengambilanSaldoBlokir"></span>
            <span hidden id="pengambilanSaldoMinimal"></span>
            <span hidden id="pengambilanSaldoTersedia"></span>

            <label for="namaPenarik">Nama Penarik dan Penerima:</label>
            <div class="radio-container">
                <input type="radio" id="pengambilansameName" name="pengambilansameName" value="yes" onclick="toggleNamaPenarik(true)" checked>
                <label for="pengambilansameName">Sama dengan Nama Pemilik Rekening</label><br>
                <input type="radio" id="pengambilandifferentName" name="pengambilansameName" value="no" onclick="toggleNamaPenarik(false)">
                <label for="pengambilandifferentName">Berbeda dengan Nama Pemilik Rekening</label>
            </div>
            <label for="penarikName">Nama Penarik:</label>
            <input type="text" id="penarikName" name="penarikName" placeholder="Masukkan Nama Penarik" required>
            <label for="penerimaName">Nama Penerima:</label>
            <input type="text" id="penerimaName" name="penerimaName" placeholder="Masukkan Nama Penerima" required>

            <div id="gunaDanaContainer" style="display:none;">
                <label for="gunaDana">Kegunaan Dana (wajib diisi diatas Rp100juta):</label>
                <select id="gunaDana" name="gunaDana" aria-label="Kegunaan Dana" class="modal-select" required>
                    <option value="" disabled selected>Pilih Kegunaan Dana</option>
                    <option value="Investasi">Investasi</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Kesehatan">Kesehatan</option>
                    <option value="Modal Usaha">Modal Usaha</option>
                    <option value="Pembelian Properti">Pembelian Properti</option>
                    <option value="Renovasi Rumah">Renovasi Rumah</option>
                    <option value="Pembelian Kendaraan">Pembelian Kendaraan</option>
                    <option value="Liburan">Liburan</option>
                    <option value="Pernikahan">Pernikahan</option>
                    <option value="Tabungan Jangka Panjang">Tabungan Jangka Panjang</option>
                    <option value="Kebutuhan Sehari-hari">Kebutuhan Sehari-hari</option>
                </select>
            </div>

            <div class="suggested-amounts">
                <button type="button" class="suggestion-btn" onclick="selectAmount(100000, 'nominalPengambilan')">Rp 100.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(250000, 'nominalPengambilan')">Rp 250.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(500000, 'nominalPengambilan')">Rp 500.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(1000000, 'nominalPengambilan')">Rp 1.000.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(2000000, 'nominalPengambilan')">Rp 2.000.000</button>
            </div>
            <div class="numpad-container" id="numpad-container">
                <button type="button" class="numpad-btn" onclick="pressNumpad(1, 'nominalPengambilan', 'nonotp')">1</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(2, 'nominalPengambilan', 'nonotp')">2</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(3, 'nominalPengambilan', 'nonotp')">3</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(4, 'nominalPengambilan', 'nonotp')">4</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(5, 'nominalPengambilan', 'nonotp')">5</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(6, 'nominalPengambilan', 'nonotp')">6</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(7, 'nominalPengambilan', 'nonotp')">7</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(8, 'nominalPengambilan', 'nonotp')">8</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(9, 'nominalPengambilan', 'nonotp')">9</button>
                <button type="button" class="numpad-btn numpad-btn-c" onclick="clearAmount('nominalPengambilan')">C</button>
                <button type="button" class="numpad-btn numpad-btn-zero" onclick="pressNumpad(0, 'nominalPengambilan', 'nonotp')">0</button>
                <button type="button" class="numpad-btn numpad-btn-back" onclick="backspace('nominalPengambilan')">←</button> <!-- Backspace button -->
            </div>

            <button class="confirm-button" onclick="submitTransactionPengambilan()">Proses</button>

        </div>
    </div>

    <div id="bantuanModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Cara pemakaian aplikasi SnapBank</h2>
            <p>Untuk memverifikasi identitas Anda menggunakan e-KTP, ikuti langkah-langkah berikut dengan hati-hati:</p>
            <ul>
                <li>1. Pilih menu yang sesuai dengan kebutuhan Anda di layar utama.</li>
                <li>2. Pastikan posisi e-KTP Anda rata dan letakkan dengan hati-hati di atas alat pembaca.</li>
                <li>3. Pastikan chip pada e-KTP Anda menghadap ke alat pembaca untuk proses pembacaan yang optimal.</li>
                <li>4. Tunggu beberapa detik hingga sistem membaca data dari e-KTP Anda. Proses ini biasanya hanya membutuhkan waktu sesaat.</li>
                <li>5. Setelah proses selesai, Anda akan diarahkan ke langkah selanjutnya sesuai dengan instruksi pada layar.</li>
            </ul>
            <p>Jika Anda mengalami kesulitan dalam menggunakan alat pembaca, pastikan posisi e-KTP Anda sudah benar. Jika masalah masih berlanjut, coba lakukan langkah-langkah di atas sekali lagi atau hubungi petugas untuk bantuan lebih lanjut.</p>
            <button class="confirm-button" onclick="closeModal()">Mengerti</button>
        </div>
    </div>

    <footer>
        &copy; 2024 SnapBank. Semua Hak Dilindungi.
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        let currentTransaction = '';
        let id_customer;
        let nama_nasabah;
        let id_nasabah;
        let saldo_akhir_cbs = 0;
        let saldo_minimal_cbs = 0;
        let saldo_blokir_cbs = 0;
        let saldo_tersedia_cbs = 0;
        let countdownTimer;
        let countdownDuration = 120; // 3 minutes

        // JavaScript to update the date and time every second
        function updateDateTime() {
            const now = new Date();

            // Format date (e.g., 11 Nov 2024)
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const formattedDate = now.toLocaleDateString('id-ID', options);

            // Format time (e.g., 14:30:05)
            const formattedTime = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Combine date and time in a single div
            document.getElementById('dateTimeDisplay').textContent = `${formattedDate} ${formattedTime}`;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        function showAlert(title, message, iconType) {
            Swal.fire({
                title: title,
                text: message,
                icon: iconType,
                timer: 3000,
                timerProgressBar: true,
                showCloseButton: true,
                showConfirmButton: false,
            });
        }

        $(document).ready(function() {
            $('.slider').slick({
                autoplay: true,
                autoplaySpeed: 3000,
                dots: true,
                arrows: true,
                pauseOnHover: false
            });
        });

        function startCountdown(duration) {
            let timerElement = document.getElementById("timer");
            let timeRemaining = duration;

            countdownTimer = setInterval(function() {
                let minutes = Math.floor(timeRemaining / 60);
                let seconds = timeRemaining % 60;

                // Pad minutes and seconds with leading zeros if needed
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerElement.textContent = minutes + ":" + seconds;

                if (timeRemaining <= 0) {
                    clearInterval(countdownTimer); // Stop the countdown when it reaches 0
                } else {
                    timeRemaining--;
                }
            }, 1000); // Update every second
        }

        function showModal(menuTransaksi) {

            let countdownContainer = document.getElementById("countdownContainer")
            countdownContainer.style.display = "block";
            startCountdown(countdownDuration);
            currentTransaction = menuTransaksi;
            document.getElementById('ktpModal').style.display = "block";
            document.getElementById('ktpNumber').value = "";
            document.getElementById('ktpNumber').focus();
            setTimeout(function() {
                showAlert('Informasi', 'Waktu habis untuk sesi ini, halaman akan di refresh.', 'info');
                setTimeout(function() {
                    location.reload();
                }, 3000); // 3 detik
            }, countdownDuration * 1000); // 3 menit
        }

        function showModalBantuan() {
            let countdownContainer = document.getElementById("countdownContainer")
            countdownContainer.style.display = "block";
            startCountdown(countdownDuration);

            document.getElementById('bantuanModal').style.display = "block";
        }

        function closeModal() {
            clearInterval(countdownTimer); // Stop the countdown when the modal is closed
            document.getElementById('ktpModal').style.display = "none";
            document.getElementById('identitasModal').style.display = "none"; // Menutup modal identitas jika terbuka
            document.getElementById('cekSaldoModal').style.display = "none"; // Menutup modal identitas jika terbuka
            document.getElementById('cekSaldoOTPModal').style.display = "none"; // Menutup modal identitas jika terbuka
            document.getElementById('setoranModal').style.display = "none"; // Menutup modal identitas jika terbuka
            document.getElementById('pengambilanModal').style.display = "none"; // Menutup modal identitas jika terbuka
            document.getElementById('bantuanModal').style.display = "none"; // Menutup modal identitas jika terbuka
            location.reload(true);
        }

        document.getElementById('ktpNumber').addEventListener('input', function() {
            let ktpNumber = document.getElementById('ktpNumber').value;
            if (ktpNumber.length == 10) {
                cekIdentitas();
            }
        });

        function cekIdentitas() {
            let ktpNumber = document.getElementById('ktpNumber').value;
            if (ktpNumber) {
                // Make an AJAX call to get customer data
                fetch(`bin/get_detail_customer.php?id=${encodeURIComponent(ktpNumber)}`)
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            showAlert('Error!', data.error, 'error');
                            document.getElementById('ktpModal').style.display = "none";
                        } else {
                            // Show the selected modal based on the button clicked
                            if (currentTransaction === 'cekSaldo') {
                                document.getElementById("menuTransaksi").textContent = "Cek Saldo"; // Set the name in the modal
                            } else if (currentTransaction === 'setorTunai') {
                                document.getElementById("menuTransaksi").textContent = "Setoran Tunai"; // Set the name in the modal
                            } else if (currentTransaction === 'ambilTunai') {
                                document.getElementById("menuTransaksi").textContent = "Pengambilan Tunai"; // Set the name in the modal
                            }
                            id_customer = data.id_customer; // Set the global id_nasabah
                            id_nasabah = data.id_nasabah; // Set the global id_nasabah
                            nama_nasabah = data.nama_lengkap;
                            document.getElementById("customerName").textContent = nama_nasabah; // Set the name in the modal
                            document.getElementById("modalNomorIdentitas").textContent = data.nomor_identitas; // Set the name in the modal
                            document.getElementById("modalAlamat").textContent = data.alamat; // Set the name in the modal
                            document.getElementById("modalCIF").textContent = id_nasabah; // Set the name in the modal
                            document.getElementById('ktpModal').style.display = "none";
                            document.getElementById('identitasModal').style.display = "block";
                            saveCustomerLog(currentTransaction);

                        }
                    })
                    .catch(error => {
                        showAlert('Error!', 'Error fetching customer data:'.error, 'error');
                        document.getElementById('ktpModal').style.display = "none";
                    });
            } else {
                showAlert('Error!', 'Harap letakan E-KTP pada alat pembaca.', 'error');
                document.getElementById('ktpModal').style.display = "none";
            }
        }

        function proceedTransaction() {
            if (currentTransaction === 'cekSaldo') {
                document.getElementById('identitasModal').style.display = "none"; // Menutup modal identitas jika terbuka
                document.getElementById('cekSaldoModal').style.display = "block"; // Menutup modal identitas jika terbuka
                populateAccountNumbers(id_nasabah, "nomorRekeningCekSaldo");
            } else if (currentTransaction === 'setorTunai') {
                document.getElementById('identitasModal').style.display = "none"; // Menutup modal identitas jika terbuka
                document.getElementById('setoranModal').style.display = "block"; // Menutup modal identitas jika terbuka
                populateAccountNumbers(id_nasabah, "nomorRekeningSetoran");
                toggleNamaPenyetor(true);
            } else if (currentTransaction === 'ambilTunai') {
                document.getElementById('identitasModal').style.display = "none"; // Menutup modal identitas jika terbuka
                document.getElementById('pengambilanModal').style.display = "block"; // Menutup modal identitas jika terbuka
                populateAccountNumbers(id_nasabah, "nomorRekeningPengambilan");
                toggleNamaPenarik(true);
            }
        }

        function populateAccountNumbers(id_nasabah, elementId) {
            fetch(`bin/get_account_customer.php?cif=${encodeURIComponent(id_nasabah)}`)
                .then(response => response.json())
                .then(data => {
                    const accountNumberSelect = document.getElementById(elementId);
                    accountNumberSelect.innerHTML = ''; // Clear existing options

                    // Add default "Select Account" option
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = '- Pilih Nomor Rekening -';
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    accountNumberSelect.appendChild(defaultOption);

                    if (data.length === 0) {
                        // Add an option if no account numbers are found
                        const noAccountOption = document.createElement('option');
                        noAccountOption.value = '';
                        noAccountOption.textContent = 'No accounts available';
                        accountNumberSelect.appendChild(noAccountOption);
                    } else {
                        data.forEach(account => {
                            const option = document.createElement('option');
                            option.value = account.no_rekening;
                            option.textContent = account.no_rekening;
                            accountNumberSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error fetching account numbers:', error));
        }

        function kirimOTP() {
            const accountNumber = document.getElementById("nomorRekeningCekSaldo").value;

            // Memeriksa apakah nomor rekening sudah diisi
            if (!accountNumber) {
                showAlert('Error!', 'Nomor rekening tidak boleh kosong.', 'error');
                return; // Hentikan eksekusi lebih lanjut jika nomor rekening kosong
            }

            // Generate OTP
            const otp = Math.floor(100000 + Math.random() * 900000);

            $.ajax({
                type: "POST",
                url: 'bin/save_log_otp.php',
                data: {
                    id_customer: id_customer,
                    accountNumber: accountNumber,
                    otp: otp
                },
                success: function(response) {
                    showAlert('Success!', 'OTP berhasil dikirim.', 'success');
                    document.getElementById("accountNumberCekBalanceOTP").textContent = accountNumber; // Set the name in the modal

                    document.getElementById('cekSaldoModal').style.display = "none";
                    document.getElementById('cekSaldoOTPModal').style.display = "block";

                    saveCustomerLog("OTP berhasil dikirim");

                },
                error: function(xhr, status, error) {
                    showAlert('Error!', 'Gagal memproses transaksi.', 'error');
                }
            });
        }

        function formatRupiahSaldo(amount) {
            const formattedAmount = parseFloat(amount).toFixed(2);
            const amountWithComma = formattedAmount.replace(".", ",");
            return "Rp " + amountWithComma.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function pressNumpad(number, inputId, cekotp) {
            const inputField = document.getElementById(inputId);
            let currentValue = inputField.value;

            if (cekotp === "nonotp") {
                currentValue = currentValue.replace(/[^0-9]/g, ''); // Hapus semua karakter non-angka
                currentValue += number;
                currentValue = formatRupiahNumpad(currentValue);
                inputField.value = currentValue;
                const rawinputId = inputId === 'nominalSetoran' ? 'rawnominalSetoran' : 'rawnominalPengambilan';
                const rawField = document.getElementById(rawinputId);
                const rawValue = inputField.value.replace(/[^0-9]/g, '');
                rawField.value = rawValue;

                cekNominalSetoran();
                cekNominalPengambilan();

            } else {
                currentValue += number;
                inputField.value = currentValue;
            }
        }

        function backspace(inputId) {
            const inputField = document.getElementById(inputId);
            const rawFieldId = inputId === 'nominalSetoran' ? 'rawnominalSetoran' : 'rawnominalPengambilan';
            const rawField = document.getElementById(rawFieldId);
            let rawValue = rawField.value.toString();
            rawValue = rawValue.slice(0, -1);
            rawField.value = rawValue;
            inputField.value = formatRupiahNumpad(rawValue);
            cekNominalSetoran();
            cekNominalPengambilan();

        }

        function formatRupiahNumpad(angka) {
            let numberString = angka.toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            if (split[1]) {
                rupiah += ',' + split[1];
            }

            return 'Rp ' + rupiah;
        }

        function selectAmount(amount, inputId) {
            const inputField = document.getElementById(inputId);
            const rawFieldId = inputId === 'nominalSetoran' ? 'rawnominalSetoran' : 'rawnominalPengambilan';
            const rawField = document.getElementById(rawFieldId);
            rawField.value = amount;
            inputField.value = formatRupiahNumpad(amount);
            cekNominalSetoran();
            cekNominalPengambilan();

        }

        function clearAmount(inputId) {
            document.getElementById(inputId).value = "";
            const rawinputId = inputId === 'nominalSetoran' ? 'rawnominalSetoran' : 'rawnominalPengambilan';
            document.getElementById(rawinputId).value = "";
            cekNominalSetoran();
            cekNominalPengambilan();

        }

        function getCurrentDateTime() {
            const now = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            return now.toLocaleDateString('id-ID', options);
        }

        function prosesOTP() {
            const accountNumber = document.getElementById("accountNumberCekBalanceOTP").innerText;
            const otpCode = document.getElementById("otpCode").value;
            const labelsaldoOutput = document.getElementById("label_output_saldo_akhir_cbs");
            const saldoOutput = document.getElementById("output_saldo_akhir_cbs");
            const sublabelsaldoOutput = document.getElementById("sublabel_output_saldo_akhir_cbs");
            const btnprosescekSaldoOTPModal = document.getElementById("btnprosescekSaldoOTPModal");
            const numpadcontainer = document.getElementById("numpad-container");

            if (/^\d{6}$/.test(otpCode)) {
                if (accountNumber) {
                    fetch(`bin/get_account_balance.php?no_rekening=${encodeURIComponent(accountNumber)}&&otpCode=${encodeURIComponent(otpCode)}`)
                        .then(response => {
                            return response.json();
                        })
                        .then(data => {
                            if (data.saldo_akhir !== undefined) {

                                const formattedBalance = formatRupiahSaldo(data.saldo_akhir);
                                const currentDateTime = getCurrentDateTime();
                                labelsaldoOutput.textContent = `Saldo Akhir`;
                                saldoOutput.textContent = formattedBalance;
                                sublabelsaldoOutput.textContent = `${currentDateTime} WIB`;

                                btnprosescekSaldoOTPModal.style.display = 'none';
                                numpadcontainer.style.display = 'none';

                                document.getElementById("otpCode").value = "";
                                document.getElementById("otpCode").style.display = 'none';

                                saveCustomerLog("Saldo Akhir : " + data.saldo_akhir);

                            } else {
                                showAlert('Error!', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            showAlert('Error!', 'Fetch error:', error, 'error');
                        });
                } else {
                    showAlert('Error!', 'No account number provided.', 'error');
                }
            } else {
                showAlert('Error!', 'Kode OTP harus berupa 6 digit angka.', 'error');
            }
        }

        function getNamaPenyetorStatus() {
            const sameNameRadio = document.getElementById('setoransameName');
            const differentNameRadio = document.getElementById('setorandifferentName');

            // Check which radio button is selected
            if (sameNameRadio.checked) {
                return '1'; // Same name as account holder
            } else if (differentNameRadio.checked) {
                return '0'; // Different name from account holder
            } else {
                return null; // No selection made
            }
        }

        function submitTransactionSetoran() {
            const amount = document.getElementById('rawnominalSetoran').value;
            const accountNumber = document.getElementById('nomorRekeningSetoran').value;
            const jenistrans = document.getElementById('setoranJenisTrans').value;
            const depositorName = document.getElementById('depositorName').value;
            const namaPenyetorStatus = getNamaPenyetorStatus();

            if (!amount || !accountNumber || !id_customer || !id_nasabah || !jenistrans || !depositorName || !namaPenyetorStatus) {
                showAlert('Error!', 'Harap isi semua data yang diperlukan.', 'error');
                return;
            }

            if (isNaN(amount) || amount <= 0) {
                showAlert('Error!', 'Nominal setoran tidak valid.', 'error');
                return;
            }

            if (parseInt(amount) >= 100000000) {
                const sumberDana = document.getElementById("sumberDana").value;
                if (!sumberDana) {
                    showAlert('Error!', 'Harap isi sumber dana karena transaksi anda diatas Rp100juta.', 'error');
                    return;
                } else {
                    processTransactionSetoran(amount, accountNumber, id_customer, id_nasabah, jenistrans, namaPenyetorStatus, depositorName, sumberDana);
                }
            } else {
                processTransactionSetoran(amount, accountNumber, id_customer, id_nasabah, jenistrans, namaPenyetorStatus, depositorName, null);
            }
        }

        function processTransactionSetoran(amount, accountNumber, id_customer, id_nasabah, jenistrans, namaPenyetorStatus, depositorName, sumberDana) {
            $.ajax({
                type: "POST",
                url: 'bin/save_transaksi_setoran.php',
                data: {
                    amount: amount,
                    accountNumber: accountNumber,
                    id_customer: id_customer,
                    id_nasabah: id_nasabah,
                    jenistrans: jenistrans,
                    namaPenyetorStatus: namaPenyetorStatus,
                    depositorName: depositorName,
                    sumberDana: sumberDana
                },
                success: function(response) {
                    showAlert('Success!', 'Transaksi berhasil diproses, silahkan menuju Teller.', 'success');

                    saveCustomerLog("Transaksi berhasil diproses ID : " + response.id_trans);

                    window.open('bin/cetak_bukti_transaksi_setoran.php?id_transaksi=' + encodeURIComponent(response.id_trans), 'popup', 'width=1000,height=600');

                    setTimeout(function() {
                        closeModal();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Gagal memproses transaksi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert('Error!', errorMessage, 'error');
                }
            });
        }

        function submitTransactionPengambilan() {
            const amount = document.getElementById('rawnominalPengambilan').value;
            const accountNumber = document.getElementById('nomorRekeningPengambilan').value;
            const jenistrans = document.getElementById('pengambilanJenisTrans').value;
            const penarikName = document.getElementById('penarikName').value;
            const penerimaName = document.getElementById('penerimaName').value;

            if (!amount || !accountNumber || !id_customer || !id_nasabah || !jenistrans || !penarikName || !penerimaName) {
                showAlert('Error!', 'Harap isi semua data yang diperlukan.', 'error');
                return;
            }

            if (isNaN(amount) || amount <= 0) {
                showAlert('Error!', 'Nominal pengambilan tidak valid.', 'error');
                return;
            }

            if (saldo_tersedia_cbs >= amount) {
                if (parseInt(amount) >= 100000000) {
                    const gunaDana = document.getElementById("gunaDana").value;
                    if (!gunaDana) {
                        showAlert('Error!', 'Harap isi kegunaan dana karena transaksi anda diatas Rp100juta.', 'error');
                        return;
                    } else {
                        processTransactionPengambilan(amount, accountNumber, id_customer, id_nasabah, jenistrans, gunaDana, penarikName, penerimaName);
                    }
                } else {
                    processTransactionPengambilan(amount, accountNumber, id_customer, id_nasabah, jenistrans, null, penarikName, penerimaName);
                }
            } else {
                showAlert('Error!', 'Saldo tidak cukup untuk transaksi ini.', 'error');
                return;
            }

        }

        function processTransactionPengambilan(amount, accountNumber, id_customer, id_nasabah, jenistrans, gunaDana, penarikName, penerimaName) {
            $.ajax({
                type: "POST",
                url: 'bin/save_transaksi_pengambilan.php',
                data: {
                    amount: amount,
                    accountNumber: accountNumber,
                    id_customer: id_customer,
                    id_nasabah: id_nasabah,
                    jenistrans: jenistrans,
                    gunaDana: gunaDana,
                    penarikName: penarikName,
                    penerimaName: penerimaName
                },
                success: function(response) {
                    showAlert('Success!', 'Transaksi berhasil diproses, silahkan menuju Teller.', 'success');

                    saveCustomerLog("Transaksi berhasil diproses ID : " + response.id_trans);

                    window.open('bin/cetak_bukti_transaksi_pengambilan.php?id_transaksi=' + encodeURIComponent(response.id_trans), 'popup', 'width=1000,height=600');

                    setTimeout(function() {
                        closeModal();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Gagal memproses transaksi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert('Error!', errorMessage, 'error');
                }
            });
        }

        function fetchSaldoAkhir() {
            const accountNumber = document.getElementById('nomorRekeningPengambilan').value;
            const accountBalance = document.getElementById('pengambilanSaldoAkhir');
            const pengambilanSaldoMinimal = document.getElementById('pengambilanSaldoMinimal');
            const pengambilanSaldoBlokir = document.getElementById('pengambilanSaldoBlokir');
            const pengambilanSaldoTersedia = document.getElementById('pengambilanSaldoTersedia');

            if (accountNumber) {
                fetch(`bin/get_account_balance_non_otp.php?no_rekening=${encodeURIComponent(accountNumber)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.saldo_akhir !== undefined) {
                            saldo_akhir_cbs = data.saldo_akhir;
                            saldo_minimal_cbs = data.saldo_minimal;
                            saldo_blokir_cbs = data.saldo_blokir;
                            saldo_tersedia_cbs = saldo_akhir_cbs - saldo_blokir_cbs - saldo_minimal_cbs;
                            accountBalance.innerText = "Saldo Akhir: " + saldo_akhir_cbs;
                            pengambilanSaldoMinimal.innerText = "Saldo Minimal: " + saldo_minimal_cbs;
                            pengambilanSaldoBlokir.innerText = "Saldo Blokir: " + saldo_blokir_cbs;
                            pengambilanSaldoTersedia.innerText = "Saldo Tersedia: " + saldo_tersedia_cbs;
                        } else {
                            accountBalance.innerText = "Error: " + data.error;
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching saldo:", error);
                        accountBalance.innerText = "Error: Could not fetch saldo";
                    });
            } else {
                accountBalance.innerText = "Saldo Akhir: -";
            }
        }

        function toggleNamaPenyetor(same) {
            const namaPenyetorInput = document.getElementById('depositorName');
            if (same) {
                namaPenyetorInput.value = nama_nasabah; // Contoh, ganti dengan data yang relevan
                namaPenyetorInput.readOnly = true; // Nonaktifkan editing
            } else {
                namaPenyetorInput.value = ""; // Kosongkan input field
                namaPenyetorInput.readOnly = false; // Aktifkan editing
                namaPenyetorInput.focus();
            }
        }

        function cekNominalSetoran() {
            const nominalSetoran = document.getElementById('rawnominalSetoran').value;
            const sumberDanaContainer = document.getElementById('sumberDanaContainer');
            if (parseInt(nominalSetoran) >= 100000000) {
                sumberDanaContainer.style.display = 'block'; // Tampilkan input sumber dana
            } else {
                sumberDanaContainer.style.display = 'none'; // Sembunyikan input sumber dana
            }
        }

        function cekNominalPengambilan() {
            const nominalPengambilan = document.getElementById('rawnominalPengambilan').value;
            const gunaDanaContainer = document.getElementById('gunaDanaContainer');
            if (parseInt(nominalPengambilan) >= 100000000) {
                gunaDanaContainer.style.display = 'block'; // Tampilkan input sumber dana
            } else {
                gunaDanaContainer.style.display = 'none'; // Sembunyikan input sumber dana
            }
        }

        function toggleNamaPenarik(same) {
            const penarikName = document.getElementById('penarikName');
            const penerimaName = document.getElementById('penerimaName');
            if (same) {
                penarikName.value = nama_nasabah; // Contoh, ganti dengan data yang relevan
                penerimaName.value = nama_nasabah; // Contoh, ganti dengan data yang relevan
                penarikName.readOnly = true; // Nonaktifkan editing
                penerimaName.readOnly = true; // Nonaktifkan editing
            } else {
                penarikName.value = ""; // Kosongkan input field
                penerimaName.value = ""; // Kosongkan input field
                penarikName.readOnly = false; // Aktifkan editing
                penerimaName.readOnly = false; // Aktifkan editing
                penarikName.focus();
            }
        }

        function saveCustomerLog(actionLog) {
            const logData = {
                id_customer: id_customer,
                action: actionLog
            };

            // Kirim data log ke server menggunakan AJAX
            fetch('bin/sys_log_customer.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(logData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Log berhasil disimpan.");
                    } else {
                        console.error("Gagal menyimpan log.");
                    }
                })
                .catch(error => {
                    console.error("Terjadi kesalahan saat menyimpan log:", error);
                });
        }
    </script>

</body>

</html>