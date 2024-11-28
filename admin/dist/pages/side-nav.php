<?php
$id_pengguna = $_SESSION['id_pengguna'];
$nama_user = $_SESSION['nama_user'];
$latency = $_SESSION['latency'];

// Menentukan halaman yang aktif
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';  // Default ke 'dashboard'
?>

<div id="layoutSidenav_nav">
    <nav class="sidebar">
        <!-- Sidebar Logo and Close Icon -->
        <div class="logo d-flex justify-content-between">
            <a href="index.html"><img src="dist/new/img/logo.png" alt="Logo"></a>
            <div class="sidebar_close_icon d-lg-none">
                <i class="ti-close"></i>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul id="sidebar_menu">
            <!-- Dashboard Menu -->
            <li class="<?php echo ($current_page == 'dashboard') ? 'mm-active' : ''; ?>">
                <a href="index.php" aria-expanded="false">
                    <img src="dist/new/img/menu-icon/dashboard.svg" alt="Dashboard Icon">
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Transaksi Menu -->
            <li class="<?php echo ($current_page == 'transaksi_tab_qrcode') ? 'mm-active' : ''; ?>">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <img src="dist/new/img/menu-icon/2.svg" alt="Transaksi Icon">
                    <span>Transaksi</span>
                </a>
                <ul>
                    <li><a href="?page=transaksi_tab_qrcode" title="Transaksi Tabungan QRCode">
                        <div class="sb-nav-link-icon"><i class="fas fa-qrcode"></i></div>
                        Trans Tabungan
                    </a></li>
                    <li><a hidden href="?page=transaksi_tab_kartu" title="Transaksi via Kartu">
                        <div class="sb-nav-link-icon"><i class="fas fa-camera"></i></div>
                        Transaksi Kartu
                    </a></li>
                </ul>
            </li>

            <!-- Master Menu -->
            <li class="<?php echo ($current_page == 'customer') ? 'mm-active' : ''; ?>">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <img src="dist/new/img/menu-icon/3.svg" alt="Master Icon">
                    <span>Master</span>
                </a>
                <ul>
                    <li><a href="?page=customer" title="Customer">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        Customer
                    </a></li>
                </ul>
            </li>

            <!-- Tools Menu (Hidden) -->
            <li hidden>
                <a class="has-arrow" href="#" aria-expanded="false">
                    <img src="dist/new/img/menu-icon/4.svg" alt="Tools Icon">
                    <span>Tools</span>
                </a>
                <ul>
                    <li><a href="?page=generate_qrcode">
                        <div class="sb-nav-link-icon"><i class="fas fa-qrcode"></i></div>
                        Generate QRCode
                    </a></li>
                </ul>
            </li>

            <!-- Setting Menu (Visible based on Latency) -->
            <li <?php if ($latency > 1) { echo "hidden"; } ?> class="<?php echo ($current_page == 'pengguna' || $current_page == 'sys_parameter') ? 'mm-active' : ''; ?>">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <img src="dist/new/img/menu-icon/5.svg" alt="Setting Icon">
                    <span>Setting</span>
                </a>
                <ul>
                    <li><a href="?page=pengguna">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Pengguna
                    </a></li>
                    <li><a href="?page=sys_parameter">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                        Parameter
                    </a></li>
                    <li><a href="?page=sys_lobby">
                        <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                        Lobby
                    </a></li>

                </ul>
            </li>
        </ul>
    </nav>
</div>
