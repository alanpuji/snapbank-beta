<?php
include "../bin/koneksi.php";
include "../proses/cek_status_login.php";
date_default_timezone_set('Asia/Jakarta');

function DateToIndo($date)
{
    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
    $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
    return ($result);
}

$database = new Connection();
$db = $database->openConnection();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Directory</title>

    <link rel="icon" href="dist/new/img/logo.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="dist/new/css/bootstrap1.min.css" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="dist/new/vendors/themefy_icon/themify-icons.css" />
    <!-- swiper slider CSS -->
    <link rel="stylesheet" href="dist/new/vendors/swiper_slider/css/swiper.min.css" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="dist/new/vendors/select2/css/select2.min.css" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="dist/new/vendors/niceselect/css/nice-select.css" />
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="dist/new/vendors/owl_carousel/css/owl.carousel.css" />
    <!-- gijgo css -->
    <link rel="stylesheet" href="dist/new/vendors/gijgo/gijgo.min.css" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="dist/new/vendors/font_awesome/css/all.min.css" />
    <link rel="stylesheet" href="dist/new/vendors/tagsinput/tagsinput.css" />

    <!-- date picker -->
     <link rel="stylesheet" href="dist/new/vendors/datepicker/date-picker.css" />

    <!-- datatable CSS -->
    <link rel="stylesheet" href="dist/new/vendors/datatable/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="dist/new/vendors/datatable/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="dist/new/vendors/datatable/css/buttons.dataTables.min.css" />
    <!-- text editor css -->
    <link rel="stylesheet" href="dist/new/vendors/text_editor/summernote-bs4.css" />
    <!-- morris css -->
    <link rel="stylesheet" href="dist/new/vendors/morris/morris.css">
    <!-- metarial icon css -->
    <link rel="stylesheet" href="dist/new/vendors/material_icon/material-icons.css" />

    <!-- menu css  -->
    <link rel="stylesheet" href="dist/new/css/metisMenu.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="dist/new/css/style1.css" />
    <link rel="stylesheet" href="dist/new/css/colors/default.css" id="colorSkinCSS">
    
    
    <script src="dist/js/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>

    <!-- SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    #layoutSidenav {
    display: flex;
    height: 100vh; /* Mengatur tinggi halaman agar full */
    width: 100%;   /* Pastikan layout mengambil lebar penuh */
}

#layoutSidenav_nav {
    width: 250px; /* Menentukan lebar sidebar */
    background-color: #f8f9fa; /* Contoh warna sidebar */
    height: 100%; /* Sidebar harus memanjang sepanjang halaman */
    position: fixed; /* Tetap di tempat saat scroll */
    z-index: 100; /* Menjaga sidebar di atas konten */
}

#layoutSidenav_content {
    flex: 1;
    margin-left: 280px; /* Memberikan ruang agar konten tidak tertutup sidebar */
    padding: 20px; /* Menambahkan padding di konten */
    overflow-y: auto; /* Agar konten yang panjang bisa scroll */
    background-color: #fff; /* Contoh warna konten */
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
}

</style>
</head>

<body class="sb-nav-fixed">
    <?php include "dist/pages/top-nav.php" ?>

    <div id="layoutSidenav">
        <?php include "dist/pages/side-nav.php" ?>

        <div id="layoutSidenav_content">
            <main>
                <?php include "dist/pages/paging.php" ?>
            </main>
            <?php include "dist/pages/footer.php" ?>
        </div>
    </div>
</body>

    <script src="dist/js/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/scripts.js"></script>
    <script src="dist/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="dist/assets/demo/datatables-demo.js"></script>
    <!-- Instascan JS library -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var detik = 0;
            var menit = 0;
            var jam = 2;

            function hitung() {
                setTimeout(hitung, 1000);
                if (menit < 5 && jam == 0) {
                    var peringatan = 'style="color:red"';
                };
                $('#timer').html('<i class="fas fa-stopwatch fa-fw"></i> Sisa waktu &middot ' + jam + ' : ' + menit + ' : ' + detik);
                detik--;
                if (detik < 0) {
                    detik = 59;
                    menit--;
                    if (menit < 0) {
                        menit = 59;
                        jam--;
                        if (jam < 0) {
                            alert('Session anda telah habis, silahkan login.');
                            document.location.href = '../proses/logout.php';
                        }
                    }
                }
            }
            hitung();
        });
    </script>

    <script type="text/javascript">
        $("#cek_nasabah_from_core").click(function() {
            var nik = $("#nik").val();
            $.ajax({
                type: 'POST',
                url: "dist/pages/cek_nasabah_from_core.php",
                data: {
                    nik: nik
                },
                cache: false,
                success: function(msg) {
                    $("#detail_nasabah_from_cbs").html(msg);
                }
            });
        });
        $("#generate_no_id").click(function() {
            var nik = $("#nomor_identitas").val();
            $.ajax({
                type: 'POST',
                url: "dist/pages/generate_no_id.php",
                data: {
                    nik: nik
                },
                cache: false,
                success: function(msg) {
                    $("#result_id_unik").html(msg);
                }
            });
        });
    </script>

    <script>
        document.getElementById('generate_no_id').addEventListener('click', function() {
            const nik = document.getElementById('nomor_identitas').value;
            const uniqueId = nik + '-' + new Date().getTime(); // Unique ID format: NIK-TIMESTAMP
            const encryptedId = btoa(uniqueId); // Simple base64 encoding
            document.getElementById('id_unik').value = encryptedId;
            document.getElementById('result_id_unik').innerText = 'Generated ID: ' + uniqueId;
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formCustomerGenerateID');
            form.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Mencegah form untuk dikirim
                }
            });
        });
    </script>

    <script>
        let scanner;

        // Function to start the camera
        function startCamera() {
            document.getElementById('preview').style.display = 'block';

            if (!scanner) {
                scanner = new Instascan.Scanner({
                    video: document.getElementById('preview')
                });

                // Add listener for scan event
                scanner.addListener('scan', function(content) {
                    fetchTransactionData(content); // Call function to fetch data
                });
            }

            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                    alert('Kamera tidak ditemukan.');
                }
            }).catch(function(e) {
                console.error('Error accessing camera:', e);
            });
        }

        // Function to stop the camera
        function stopCamera() {
            if (scanner) {
                scanner.stop();
            }
            document.getElementById('preview').style.display = 'none';
        }

        // Automatically start the camera when the page loads
        window.addEventListener('load', function() {
            startCamera(); // Start the camera when the page is loaded
        });

        // Fetch transaction data from server
        function fetchTransactionData(id_trans) {
            fetch(`dist/pages/get_detail_transaksi_qrcode.php?id_trans=${id_trans}`)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data) {
                        const transaction = data.data;

                        // Populate the fields with the transaction data
                        document.getElementById('id_trans').value = id_trans;
                        document.getElementById('nama_lengkap').value = transaction.nama_lengkap;
                        document.getElementById('nomor_hp').value = transaction.nomor_hp;

                        // Format and set the date
                        document.getElementById('tgl_trans').value = formatDateLong(transaction.tgl_trans);
                        document.getElementById('jam_trans').value = transaction.jam_trans;

                        // Set transaction type
                        const jenisTrans = (transaction.jenis_trans === '200') ? "Pengambilan" : "Setoran";
                        document.getElementById('jenis_trans').value = jenisTrans;

                        document.getElementById('no_rekening').value = transaction.no_rekening;

                        // Format the nominal and set it to the input
                        const formattedNominal = formatRupiah(transaction.nominal);
                        document.getElementById('nominal').value = formattedNominal;

                        // Map the transaction status to a human-readable form
                        const statusMapping = {
                            '0': "Belum Diproses",
                            '1': "OTP Terkirim",
                            '2': "Ditolak",
                            '3': "Diproses" // Default case
                        };
                        const humanReadableStatus = statusMapping[transaction.status_trans] || "Diproses";
                        document.getElementById('status_trans').value = humanReadableStatus;

                        // Display success message
                        showSweetAlert('success', 'Transaksi ditemukan!', `Atas nama : ${transaction.nama_lengkap}`);

                        // Automatically stop the camera after successful scan
                        setTimeout(() => {
                            stopCamera();
                        }, 1000); // Adjust the timeout as needed

                    } else {
                        showSweetAlert('error', 'Gagal!', 'Transaksi tidak ditemukan atau tanggal transaksi kadaluarsa!');
                    }
                })
                .catch(error => {
                    alert('Gagal mengambil data transaksi.');
                });
        }

        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function formatDateLong(dateString) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        function showSweetAlert(icon, title, text, confirmButtonText = 'OK', timer = 3000) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonText: confirmButtonText,
                timer: timer,
                timerProgressBar: true
            });
        }

        function updateOTP() {
            const id_trans = document.getElementById('id_trans').value;
            const nomor_hp = document.getElementById('nomor_hp').value;
            if (!id_trans) {
                alert('ID Transaksi tidak valid');
                return;
            }

            // Send AJAX request to update OTP
            $.ajax({
                url: 'dist/pages/update_otp.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_trans: id_trans,
                    nomor_hp: nomor_hp
                },
                success: function(response) {
                    if (response.success) {
                        openModal();
                        showSweetAlert('success', 'Berhasil!', response.message);
                        document.getElementById('idTransInput').value = id_trans; // Set hidden field value for OTP modal
                    } else {
                        showSweetAlert('error', 'Gagal!', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error); // Log the error
                    alert('Terjadi kesalahan saat memproses data. Silakan coba lagi.');
                }
            });
        }

        function openModal() {
            document.getElementById('otpModal').style.display = 'flex'; // Use flex to center the modal
        }

        function closeModal() {
            document.getElementById('otpModal').style.display = 'none';
        }

        function submitOTP() {
            const otpValue = document.getElementById('otpInput').value.trim();
            const idTransValue = document.getElementById('idTransInput').value.trim();
            const submitButton = document.querySelector('#otpForm .btn');

            if (!otpValue || !idTransValue) {
                showSweetAlert('error', 'Error!', 'OTP and Transaction ID cannot be empty.');
                return;
            }

            // Disable the Submit OTP button to prevent multiple clicks
            submitButton.disabled = true;
            submitButton.textContent = "Processing..."; // Optional: Change button text

            $.ajax({
                url: 'dist/pages/update_transaksi_otp.php',
                method: 'POST',
                data: {
                    otp: otpValue,
                    id_trans: idTransValue
                },
                success: function(response) {
                    if (response.success) {
                        showSweetAlert('success', 'Success!', response.message);
                        closeModal(); // Tutup modal setelah sukses
                        // Refresh halaman setelah modal ditutup
                        setTimeout(function() {
                            location.reload(); // Refresh halaman setelah 2 detik
                        }, 2000);
                    } else {
                        showSweetAlert('error', 'Error!', response.message);
                        submitButton.disabled = false; // Re-enable button on error
                        submitButton.textContent = "Submit OTP";
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText); // Log the response from the server
                    showSweetAlert('error', 'Error!', 'Failed to submit OTP. Please try again.');
                    submitButton.disabled = false; // Re-enable button on error
                    submitButton.textContent = "Submit OTP";
                }
            });
        }

        // Attach the openModal function to the button click
        document.getElementById('openModal').onclick = openModal;
    </script>
<!-- footer  -->
<!-- jquery slim -->
<script src="dist/new/js/jquery1-3.4.1.min.js"></script>
<!-- popper js -->
<script src="dist/new/js/popper1.min.js"></script>
<!-- bootstarp js -->
<script src="dist/new/js/bootstrap1.min.js"></script>
<!-- sidebar menu  -->
<script src="dist/new/js/metisMenu.js"></script>
<!-- waypoints js -->
<script src="dist/new/vendors/count_up/jquery.waypoints.min.js"></script>
<!-- waypoints js -->
<script src="dist/new/vendors/chartlist/Chart.min.js"></script>
<!-- counterup js -->
<script src="dist/new/vendors/count_up/jquery.counterup.min.js"></script>
<!-- swiper slider js -->
<script src="dist/new/vendors/swiper_slider/js/swiper.min.js"></script>
<!-- nice select -->
<script src="dist/new/vendors/niceselect/js/jquery.nice-select.min.js"></script>
<!-- owl carousel -->
<script src="dist/new/vendors/owl_carousel/js/owl.carousel.min.js"></script>
<!-- gijgo css -->
<script src="dist/new/vendors/gijgo/gijgo.min.js"></script>
<!-- responsive table -->
<script src="dist/new/vendors/datatable/js/jquery.dataTables.min.js"></script>
<script src="dist/new/vendors/datatable/js/dataTables.responsive.min.js"></script>
<script src="dist/new/vendors/datatable/js/dataTables.buttons.min.js"></script>
<script src="dist/new/vendors/datatable/js/buttons.flash.min.js"></script>
<script src="dist/new/vendors/datatable/js/jszip.min.js"></script>
<script src="dist/new/vendors/datatable/js/pdfmake.min.js"></script>
<script src="dist/new/vendors/datatable/js/vfs_fonts.js"></script>
<script src="dist/new/vendors/datatable/js/buttons.html5.min.js"></script>
<script src="dist/new/vendors/datatable/js/buttons.print.min.js"></script>

<!-- date picker  -->
<script src="dist/new/vendors/datepicker/datepicker.js"></script>
<script src="dist/new/vendors/datepicker/datepicker.en.js"></script>
<script src="dist/new/vendors/datepicker/datepicker.custom.js"></script>

<script src="dist/new/js/chart.min.js"></script>
<!-- progressbar js -->
<script src="dist/new/vendors/progressbar/jquery.barfiller.js"></script>
<!-- tag input -->
<script src="dist/new/vendors/tagsinput/tagsinput.js"></script>
<!-- text editor js -->
<script src="dist/new/vendors/text_editor/summernote-bs4.js"></script>
<script src="dist/new/vendors/am_chart/amcharts.js"></script>

<script src="dist/new/vendors/apex_chart/apexcharts.js"></script>
<script src="dist/new/vendors/apex_chart/apex_realestate.js"></script>
<!-- <script src="vendors/apex_chart/default.js"></script> -->

<script src="dist/new/vendors/chart_am/core.js"></script>
<script src="dist/new/vendors/chart_am/charts.js"></script>
<script src="dist/new/vendors/chart_am/animated.js"></script>
<script src="dist/new/vendors/chart_am/kelly.js"></script>
<script src="dist/new/vendors/chart_am/chart-custom.js"></script>
<!-- custom js -->
<script src="dist/new/js/custom.js"></script>

<script src="dist/new/vendors/apex_chart/bar_active_1.js"></script>
<script src="dist/new/vendors/apex_chart/apex_chart_list.js"></script>
</body>

</html>