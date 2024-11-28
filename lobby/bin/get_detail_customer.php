<?php
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$database = new Connection();
$db = $database->openConnection();

try {
    // Check if 'qrcode' parameter is present
    if (isset($_GET['id'])) {
        $id_unik = $_GET['id'];

        // Prepare and execute the SQL statement
        $stmt = $db->prepare("SELECT * FROM customer WHERE id_unik_ktp = :id_unik");
        $stmt->bindParam(':id_unik', $id_unik, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the customer data
        $customerData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if data was found and respond accordingly
        if ($customerData) {
            echo json_encode($customerData);
        } else {
            echo json_encode(["error" => "Customer not found."]);
        }
    } else {
        echo json_encode(["error" => "QR code parameter missing."]);
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    // Close the connection
    $db = null;
}
