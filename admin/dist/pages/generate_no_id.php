<?php
include "../../../bin/koneksi.php";
$id_unik = $_POST['id_unik'];

// Define a security key for encryption
$security_key = "sN4pBaNk58*"; // Ensure you use a secure key

if (empty($id_unik)) {
    echo "<div class='alert alert-danger'><p class='mb-0'><i class='fas fa-info mr-1'></i> Kolom ID Unik masih kosong</p></div>";
} else {
    // Encrypt the 'id_unik' using HMAC and SHA-256
    $encrypted_id_unik = hash_hmac('sha256', $id_unik, $security_key);

    // Output the encrypted ID Unik as a response
    echo $encrypted_id_unik;
}
