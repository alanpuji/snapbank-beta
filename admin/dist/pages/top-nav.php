<?php
$nama_user = $_SESSION['nama_user'];
?>
<div class="container-fluid g-0">
    <div class="row">
        <div class="col-lg-12 p-0">
            <div class="header_iner d-flex justify-content-between align-items-center">
                <!-- Sidebar Icon (Toggle) -->
                <div class="sidebar_icon d-lg-none">
                    <i class="ti-menu"></i>
                </div>

                <!-- Search Field -->
                <div class="serach_field-area">
                    <div class="search_inner">
                        <form action="#">
                            <div class="search_field">
                                <input type="text" placeholder="Search here...">
                            </div>
                            <button type="submit"><img src="dist/new/img/icon/icon_search.svg" alt=""></button>
                        </form>
                    </div>
                </div>

                <!-- Header Right Section -->
                <div class="header_right d-flex justify-content-between align-items-center">
                    <!-- Notification Icon -->
                    <div class="header_notification_warp d-flex align-items-center">
                        <li>
                            <a class="bell_notification_clicker" href="#"> 
                                <img src="dist/new/img/icon/bell.svg" alt="">
                                <span>04</span>
                            </a>
                            <!-- Notification Dropdown -->
                            <div class="Menu_NOtification_Wrap">
                                <div class="notification_Header">
                                    <h4>Notifications</h4>
                                </div>
                                <div class="Notification_body">
                                    <!-- Example Notification -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="dist/new/img/staf/2.png" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>Cool Directory </h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="nofity_footer">
                                    <div class="submit_button text-center pt_20">
                                        <a href="#" class="btn_1">See More</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Chatbox Icon -->
                        <li>
                            <a class="CHATBOX_open" href="#"> 
                                <img src="dist/new/img/icon/msg.svg" alt=""> 
                                <span>01</span> 
                            </a>
                        </li>
                    </div>

                    <!-- Profile Info -->
                    <div class="profile_info">
                        <img src="dist/new/img/client_img.png" alt="#">
                        <div class="profile_info_iner">
                            <div class="profile_author_name">
                                
                            <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$username_cbs = $_SESSION['username_cbs'];
$latency = $_SESSION['latency'];

// Menentukan teks berdasarkan nilai latency
$latency_text = '';
switch ($latency) {
    case 1:
        $latency_text = 'SUPEREDP';
        break;
    case 2:
        $latency_text = 'ADMIN';
        break;
    case 3:
        $latency_text = 'CS';
        break;
    case 4:
        $latency_text = 'TELLER';
        break;
    default:
        $latency_text = 'Unknown';
        break;
}
?>

<p><?php echo $latency_text; ?></p>
<h5><?php echo $username_cbs; ?></h5>



                            </div>
                            <div class="profile_info_details">
                                <a href="#">My Profile</a>
                                <a href="#">Settings</a>
                                <a href="../proses/logout.php" onClick="return confirm('Keluar dari aplikasi ?')">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
