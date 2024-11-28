<?php
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/logo-min.png" />

    <title>SnapBank &middot Lobby</title>
    <script src="https://cdn.jsdelivr.net/gh/schmich/instascan-builds@latest/instascan.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <div id="loading-overlay">
        <div id="loading-spinner"></div>
    </div>

    <div id="header">
        <h1>SnapBank</h1>
        <h2 hidden><span id="date"></span> &middot; <span id="clock"></span></h2>
    </div>
    <div id="container">
        <div id="kiri">
            <form action="proses/simpan_absen.php" method="post" enctype="multipart/form-data">
                <label for="employee_qr">Arahkan QR ke Kamera dengan jarak sekitar 15cm</label>
                <video width="320" height="240" id="employee_qr" playsinline></video>
                <div id="watermark-box" class="watermark-box"></div>
                <img id="captured_photo" style="display: none; max-width: 300px; margin: 10px 0;" alt="Foto yang Diambil" hidden>
                <input type="text" id="qr_result" name="qr_result" readonly hidden>
                <input type="text" id="captured_photo_data" name="captured_photo_data" readonly hidden>
            </form>
        </div>
        <div id="kanan">
            <h2>Petunjuk :</h2>
            <p align="left">1. Pastikan kecerahan layar cukup untuk melakukan scan.</p>
            <p align="left">2. Pastikan kode QR berada dalam jangkauan kamera.</p>
            <p align="left">3. Tahan kode QR selama beberapa detik.</p>
            <p align="left">4. Setelah berhasil, sistem akan menampilkan menu transaksi.</p>
            <p align="left">5. Silahkan pilih tranksasi yang akan dilakukan.</p>

            <br>
            <table width="90%" align="center">
                <tr align="center">
                    <td>
                        <button id="start-camera" type="button" onclick="startCamera()"><i class="fas fa-camera"></i> Kamera ON</button>
                        <button id="stop-camera" type="button" onclick="stopCamera()"><i class="fas fa-camera"></i> Kamera OFF</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div id="footer">
        <p>
            Copyright &copy; 2024 - <script>
                document.write(new Date().getFullYear());
            </script>. All Rights Reserved.
        </p>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2 id="modalTitle">Halo, <span id="customerName"></span>!</h2>
            <div class="modal-body">
                <table id="modalTable">
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
                <p id="modalResult" class="result-text"></p>
                <img id="modalPhoto" class="captured-photo" alt="Captured Photo">
            </div>
            <!-- New buttons for cash deposit and withdrawal -->
            <div class="modal-actions">
                <button class="btn" id="cekBalanceBtn">Cek Saldo</button>
                <button class="btn" id="cashDepositBtn">Setoran</button>
                <button class="btn" id="cashWithdrawalBtn">Pengambilan</button>
            </div>
        </div>
    </div>

    <!-- Modal Setoran -->
    <div id="depositModal" class="modal">
        <div class="modal-content">
            <span id="closeDepositModalBtn" class="close">&times;</span>
            <h2>Setoran Tunai</h2>
            <label for="accountNumberDeposit">Nomor Rekening Tujuan:</label>
            <select id="accountNumberDeposit" class="modal-select" onchange="fetchSaldoAkhir('deposit')">
                <option name="-">- Pilih -</option>
            </select>
            <br>
            <label for="depositAmount">Nominal Setoran:</label>
            <input type="text" id="depositAmount" class="modal-input" readonly />
            <span hidden id="depositSaldoAkhir"></span>

            <div class="suggested-amounts">
                <button type="button" class="suggestion-btn" onclick="selectAmount(100000, 'depositAmount')">Rp 100.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(500000, 'depositAmount')">Rp 500.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(1000000, 'depositAmount')">Rp 1.000.000</button>
            </div>

            <div class="numpad-container">
                <button type="button" class="numpad-btn" onclick="pressNumpad(1, 'depositAmount')">1</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(2, 'depositAmount')">2</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(3, 'depositAmount')">3</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(4, 'depositAmount')">4</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(5, 'depositAmount')">5</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(6, 'depositAmount')">6</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(7, 'depositAmount')">7</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(8, 'depositAmount')">8</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(9, 'depositAmount')">9</button>
                <button type="button" class="numpad-btn" onclick="clearAmount('depositAmount')">C</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(0, 'depositAmount')">0</button>
                <button type="button" class="numpad-btn" onclick="deleteLast('depositAmount')">&larr;</button>
            </div>

            <input type="text" id="depositjenistrans" class="modal-input" value="100" hidden readonly />

            <button class="btn" onclick="submitTransaction('deposit')">Kirim Setoran</button>
        </div>
    </div>

    <!-- Modal Penarikan -->
    <div id="withdrawModal" class="modal">
        <div class="modal-content">
            <span id="closeWithdrawalModalBtn" class="close">&times;</span>
            <h2>Penarikan Tunai</h2>
            <label for="accountNumberWithdraw">Nomor Rekening:</label>
            <select id="accountNumberWithdraw" class="modal-select" onchange="fetchSaldoAkhir('withdraw')">
                <option name="-">- Pilih -</option>
            </select>
            <br>
            <label for="withdrawAmount">Nominal Penarikan:</label>
            <input type="text" id="withdrawAmount" class="modal-input" readonly />
            <span hidden id="withdrawSaldoAkhir"></span>

            <div class="suggested-amounts">
                <button type="button" class="suggestion-btn" onclick="selectAmount(100000, 'withdrawAmount')">Rp 100.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(500000, 'withdrawAmount')">Rp 500.000</button>
                <button type="button" class="suggestion-btn" onclick="selectAmount(1000000, 'withdrawAmount')">Rp 1.000.000</button>
            </div>

            <div class="numpad-container">
                <button type="button" class="numpad-btn" onclick="pressNumpad(1, 'withdrawAmount')">1</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(2, 'withdrawAmount')">2</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(3, 'withdrawAmount')">3</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(4, 'withdrawAmount')">4</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(5, 'withdrawAmount')">5</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(6, 'withdrawAmount')">6</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(7, 'withdrawAmount')">7</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(8, 'withdrawAmount')">8</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(9, 'withdrawAmount')">9</button>
                <button type="button" class="numpad-btn" onclick="clearAmount('withdrawAmount')">C</button>
                <button type="button" class="numpad-btn" onclick="pressNumpad(0, 'withdrawAmount')">0</button>
                <button type="button" class="numpad-btn" onclick="deleteLast('withdrawAmount')">&larr;</button>
            </div>

            <input type="text" id="withdrawjenistrans" class="modal-input" value="200" hidden readonly />

            <button class="btn" onclick="submitTransaction('withdraw')">Kirim Penarikan</button>
        </div>
    </div>

    <!-- Modal Setoran -->
    <div id="cekBalanceModal" class="modal">
        <div class="modal-content">
            <span id="closeCekBalanceModalBtn" class="close">&times;</span>
            <h2>Cek Saldo Simpanan</h2>
            <label for="accountNumberCekBalance">Nomor Rekening:</label>
            <select id="accountNumberCekBalance" class="modal-select">
                <option name="-">- Pilih -</option>
            </select>
            <button class="btn" onclick="kirimOTP()">Proses</button>
        </div>
    </div>

    <div id="cekBalanceOTPModal" class="modal">
        <div class="modal-content">
            <span id="closeCekBalanceOTPModalBtn" class="close">&times;</span>
            <h2 id="modalTitle">Cek Saldo Simpanan</h2>
            <div class="modal-body">
                <table id="modalTable">
                    <tr>
                        <th>Nomor Rekening</th>
                        <td id="accountNumberCekBalanceOTP"></td>
                    </tr>
                </table>
                <br>
                <label for="otpCode">Kode OTP:</label><br>
                <input type="text" id="otpCode" class="modal-input" placeholder="Masukkan Kode OTP" maxlength="6" aria-label="Kode OTP" />
                <hr>
                <div class="numpad-container" id="numpad-container">
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(1, 'otpCode')">1</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(2, 'otpCode')">2</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(3, 'otpCode')">3</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(4, 'otpCode')">4</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(5, 'otpCode')">5</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(6, 'otpCode')">6</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(7, 'otpCode')">7</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(8, 'otpCode')">8</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(9, 'otpCode')">9</button>
                    <button type="button" class="numpad-btn" onclick="clearAmount('otpCode')">C</button>
                    <button type="button" class="numpad-btn" onclick="pressNumpadOTP(0, 'otpCode')">0</button>
                </div>

                <span id="label_output_saldo_akhir_cbs"></span><br>
                <span id="output_saldo_akhir_cbs"></span><br>
                <span id="sublabel_output_saldo_akhir_cbs"></span>

            </div>
            <div class="modal-actions">
                <button class="btn" onclick="prosesOTP()" id="btnprosescekBalanceOTPModal">Proses</button>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('start-camera').disabled = true;

        function stopCamera(event) {
            if (scanner) {
                scanner.stop();
                document.getElementById('watermark-box').style.display = 'none';
                document.getElementById('start-camera').disabled = false;
            }
            if (event) {
                event.preventDefault();
            }
        }

        function startCamera(event) {
            if (scanner) {
                scanner.start();
                document.getElementById('watermark-box').style.display = 'block';
                document.getElementById('start-camera').disabled = true;
            }
            if (event) {
                event.preventDefault();
            }
        }

        let scanner = new Instascan.Scanner({
            video: document.getElementById('employee_qr')
        });
        scanner.addListener('scan', function(content) {
            document.getElementById('qr_result').value = content;
            document.getElementById('loading-overlay').style.display = 'flex';
            takePicture(content); // Pass the scanned content to takePicture
        });

        // Close modal
        document.getElementById('closeModal').onclick = function() {
            document.getElementById('myModal').style.display = "none"; // Close the modal
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('myModal')) {
                document.getElementById('myModal').style.display = "none"; // Close the modal if clicked outside
            }
        }

        Instascan.Camera.getCameras()
            .then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                    document.getElementById('start-camera').disabled = true;
                    alert('No cameras found.');
                }
            })
            .catch(function(e) {
                console.error(e);
            });

        const dateElement = document.getElementById("date");
        const clockElement = document.getElementById("clock");

        function updateClock() {
            const now = new Date();
            dateElement.innerText = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            clockElement.innerText = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        setInterval(updateClock, 1000);

        let id_customer; // Declare id_nasabah in a higher scope
        let id_nasabah; // Declare id_nasabah in a higher scope
        let saldo_akhir_cbs; // Declare id_nasabah in a higher scope

        function takePicture(content) {
            const video = document.getElementById('employee_qr');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const photoData = canvas.toDataURL('image/png');
            document.getElementById('captured_photo_data').value = photoData;
            document.getElementById('captured_photo').src = photoData;

            // Log the scanned content to the console
            console.log("Scanned QR code content:", content);

            // Set the QR result in the modal and display the modal
            document.getElementById('modalPhoto').src = photoData; // Set the captured photo in the modal
            document.getElementById('modalPhoto').style.display = "none"; // Show the captured photo
            document.getElementById('myModal').style.display = "block"; // Show the modal

            // Make an AJAX call to get customer data
            fetch(`bin/get_detail_customer.php?qrcode=${encodeURIComponent(content)}`)
                .then(response => {
                    console.log("Received response from server:", response); // Log the raw response
                    return response.json();
                })
                .then(data => {
                    console.log("Parsed JSON data:", data); // Log the parsed data

                    if (data.error) {
                        // Handle the error
                        console.warn("Error from server:", data.error);
                        document.getElementById('modalResult').innerText = data.error;
                        document.getElementById('modalPhoto').style.display = "none"; // Hide photo on error
                    } else {
                        // Display the customer data in the modal
                        console.log("Customer data retrieved:", data);
                        id_customer = data.id_customer; // Set the global id_nasabah
                        id_nasabah = data.id_nasabah; // Set the global id_nasabah
                        document.getElementById("customerName").textContent = data.nama_lengkap; // Set the name in the modal
                        document.getElementById("modalNomorIdentitas").textContent = data.nomor_identitas; // Set the name in the modal
                        document.getElementById("modalAlamat").textContent = data.alamat; // Set the name in the modal
                        document.getElementById("modalCIF").textContent = id_nasabah; // Set the name in the modal
                    }
                })
                .catch(error => {
                    console.error('Error fetching customer data:', error);
                    document.getElementById('modalResult').innerText = "Failed to retrieve customer data.";
                    document.getElementById('modalPhoto').style.display = "none"; // Hide photo on fetch error
                });

            // Hide loading overlay
            document.getElementById('loading-overlay').style.display = 'none';
        }

        // Mendapatkan elemen modal
        const depositModal = document.getElementById("depositModal");
        const closeDepositModalBtn = document.getElementById("closeDepositModalBtn");
        const withdrawModal = document.getElementById("withdrawModal");
        const closeWithdrawalModalBtn = document.getElementById("closeWithdrawalModalBtn");
        const cekBalanceModal = document.getElementById("cekBalanceModal");
        const closeCekBalanceModalBtn = document.getElementById("closeCekBalanceModalBtn");
        const cekBalanceOTPModal = document.getElementById("cekBalanceOTPModal");
        const closeCekBalanceOTPModalBtn = document.getElementById("closeCekBalanceOTPModalBtn");

        // Function to populate the dropdown with account numbers
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

        // Menampilkan modal dengan mengisi nomor rekening
        function showModal(type) {
            const modal = type === 'deposit' ? depositModal : withdrawModal;
            const accountSelectId = type === 'deposit' ? 'accountNumberDeposit' : 'accountNumberWithdraw';
            populateAccountNumbers(id_nasabah, accountSelectId);
            modal.style.display = "block";
        }

        const cashDepositBtn = document.getElementById("cashDepositBtn");
        const cashWithdrawalBtn = document.getElementById("cashWithdrawalBtn");
        const cekBalanceBtn = document.getElementById("cekBalanceBtn");
        cashDepositBtn.addEventListener("click", function() {
            showModal("deposit");
        });
        cashWithdrawalBtn.addEventListener("click", function() {
            showModal("withdraw");
        });
        cekBalanceBtn.addEventListener("click", function() {
            populateAccountNumbers(id_nasabah, 'accountNumberCekBalance');
            cekBalanceModal.style.display = "block";
        });

        // Menutup modal
        closeDepositModalBtn.addEventListener("click", () => depositModal.style.display = "none");
        closeWithdrawalModalBtn.addEventListener("click", () => withdrawModal.style.display = "none");
        closeCekBalanceModalBtn.addEventListener("click", () => cekBalanceModal.style.display = "none");
        closeCekBalanceOTPModalBtn.addEventListener("click", () => cekBalanceOTPModal.style.display = "none");

        // Fungsi untuk memproses input dari numpad
        function pressNumpad(number, inputId) {
            const inputField = document.getElementById(inputId);
            let currentValue = inputField.value.replace(/Rp\s?|[.,]/g, "");
            currentValue += number;
            inputField.value = formatRupiah(currentValue);
        }

        // Fungsi untuk memproses input dari numpad
        function pressNumpadOTP(number, inputId) {
            const inputField = document.getElementById(inputId);
            let currentValue = inputField.value;
            currentValue += number;
            inputField.value = currentValue;
        }

        function clearAmount(inputId) {
            document.getElementById(inputId).value = "";
        }

        function deleteLast(inputId) {
            const inputField = document.getElementById(inputId);
            let currentValue = inputField.value.replace(/Rp\s?|[.,]/g, "");
            currentValue = currentValue.slice(0, -1);
            inputField.value = formatRupiah(currentValue);
        }

        // Fungsi format Rupiah
        function formatRupiah(value) {
            let numberString = value.replace(/[^,\d]/g, "").toString();
            let split = numberString.split(",");
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            return "Rp " + (split[1] !== undefined ? rupiah + "," + split[1] : rupiah);
        }

        function fetchSaldoAkhir(type) {
            const accountId = type === 'deposit' ? 'accountNumberDeposit' : 'accountNumberWithdraw';
            const accountBalance = type === 'deposit' ? 'depositSaldoAkhir' : 'withdrawSaldoAkhir';
            const accountNumber = document.getElementById(accountId).value;

            if (accountNumber) {
                fetch(`bin/get_account_balance.php?no_rekening=${encodeURIComponent(accountNumber)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.saldo_akhir !== undefined) {
                            saldo_akhir_cbs = data.saldo_akhir;
                            document.getElementById(accountBalance).innerText = "Saldo Akhir: " + saldo_akhir_cbs;
                        } else {
                            document.getElementById(accountBalance).innerText = "Error: " + data.error;
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching saldo:", error);
                        document.getElementById(accountBalance).innerText = "Error: Could not fetch saldo";
                    });
            } else {
                document.getElementById(accountBalance).innerText = "Saldo Akhir: -";
            }
        }

        function submitTransaction(type) {
            // Tentukan ID elemen berdasarkan jenis transaksi
            const inputId = type === 'deposit' ? 'depositAmount' : 'withdrawAmount';
            const accountId = type === 'deposit' ? 'accountNumberDeposit' : 'accountNumberWithdraw';
            const amount = document.getElementById(inputId).value.replace(/[^0-9]/g, ''); // Pastikan hanya angka
            const accountNumber = document.getElementById(accountId).value;
            const inputjenistrans = type === 'deposit' ? 'depositjenistrans' : 'withdrawjenistrans';
            const jenistrans = document.getElementById(inputjenistrans).value;

            // Validasi inputan
            if (!amount || !accountNumber || !id_customer || !id_nasabah || !jenistrans) {
                showAlert('Error!', 'Harap isi semua data yang diperlukan.', 'error');
                return;
            }

            if (jenistrans == '200') {
                if (saldo_akhir_cbs >= amount) {
                    processTransaction(amount, accountNumber, id_customer, id_nasabah, jenistrans, inputId);
                } else {
                    showAlert('Error!', 'Saldo tidak cukup untuk transaksi ini.', 'error');
                }
            } else {
                processTransaction(amount, accountNumber, id_customer, id_nasabah, jenistrans, inputId);
            }
        }

        // Fungsi untuk memproses transaksi
        function processTransaction(amount, accountNumber, id_customer, id_nasabah, jenistrans, inputId) {
            $.ajax({
                type: "POST",
                url: 'bin/save_transaksi.php',
                data: {
                    amount: amount,
                    accountNumber: accountNumber,
                    id_customer: id_customer,
                    id_nasabah: id_nasabah,
                    jenistrans: jenistrans
                },
                success: function(response) {
                    document.getElementById('myModal').style.display = "none";
                    showAlert('Success!', 'Transaksi berhasil diproses, silahkan menuju Teller.', 'success');
                    document.getElementById(inputId).value = ""; // Reset inputan nominal
                    if (jenistrans === 'deposit') {
                        document.getElementById('depositModal').style.display = "none";
                    } else {
                        document.getElementById('withdrawModal').style.display = "none";
                    }
                    setTimeout(function() {
                        location.reload(); // Refresh halaman setelah jeda
                    }, 3500); // 2000 ms = 2 detik
                },
                error: function(xhr, status, error) {
                    showAlert('Error!', 'Gagal memproses transaksi.', 'error');
                }
            });
        }

        // Fungsi notifikasi
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

        function selectAmount(amount, inputId) {
            const inputField = document.getElementById(inputId);
            inputField.value = amount.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
        }

        function kirimOTP() {
            const accountNumber = document.getElementById("accountNumberCekBalance").value;

            if (accountNumber === "") {
                showAlert('Error!', 'Pilih nomor rekening', 'error');
                return;
            }

            if (id_customer === "") {
                showAlert('Error!', 'ID Customer tidak ditemukan', 'error');
                return;
            }

            // Generate a random 6-digit OTP
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
                    document.getElementById('myModal').style.display = "none";
                    showAlert('Success!', 'OTP berhasil dikirim.', 'success');
                    document.getElementById("accountNumberCekBalanceOTP").textContent = accountNumber; // Set the name in the modal

                    document.getElementById('cekBalanceModal').style.display = "none";
                    document.getElementById('cekBalanceOTPModal').style.display = "block";
                },
                error: function(xhr, status, error) {
                    showAlert('Error!', 'Gagal memproses transaksi.', 'error');
                }
            });
        }

        function formatRupiahOTP(amount) {
            return "Rp " + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
            const btnprosescekBalanceOTPModal = document.getElementById("btnprosescekBalanceOTPModal");
            const numpadcontainer = document.getElementById("numpad-container");

            if (/^\d{6}$/.test(otpCode)) {
                if (accountNumber) {
                    fetch(`bin/get_account_balance.php?no_rekening=${encodeURIComponent(accountNumber)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.saldo_akhir !== undefined) {
                                const formattedBalance = formatRupiahOTP(data.saldo_akhir);
                                const currentDateTime = getCurrentDateTime();
                                labelsaldoOutput.textContent = `Saldo Akhir`;
                                saldoOutput.textContent = formattedBalance;
                                sublabelsaldoOutput.textContent = currentDateTime + ` WIB`;
                                btnprosescekBalanceOTPModal.style.display = 'none';
                                numpadcontainer.style.display = 'none';
                            } else {
                                saldoOutput.textContent = "Error: " + (data.error || "Data not available");
                            }
                        })
                        .catch(error => {
                            saldoOutput.textContent = "Error: Could not fetch saldo";
                        });
                } else {
                    saldoOutput.textContent = "Saldo Akhir: -";
                }
            } else {
                alert("Kode OTP harus berupa 6 digit angka.");
            }
        }
    </script>

</body>

</html>