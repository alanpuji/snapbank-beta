<?php
// Kode ambil nomor rekening customer dan cek saldo
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$database = new Connection_Core();
$db = $database->openConnection();

try {
    // Check if 'cif' parameter is present
    if (isset($_GET['cif'])) {
        $id_nasabah = $_GET['cif'];

        // Prepare and execute the SQL statement to fetch all account numbers
        $stmt = $db->prepare("SELECT no_rekening FROM tabung WHERE nasabah_id = :id_nasabah AND status=1");
        $stmt->bindParam(':id_nasabah', $id_nasabah, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch all account numbers
        $accountData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If no account data is found, return an empty array
        if ($accountData) {
            $result = [];

            // Loop through each account to get balance data
            foreach ($accountData as $account) {
                $no_rekening = $account['no_rekening'];

                // Query to calculate balance
                $query = "
                SELECT 
                    SUM(IF(FLOOR(TABTRANS.MY_KODE_TRANS / 100) = 1, TABTRANS.POKOK, 0)) AS SETORAN, 
                    SUM(IF(FLOOR(TABTRANS.MY_KODE_TRANS / 100) = 2, TABTRANS.POKOK, 0)) AS PENARIKAN
                FROM 
                    TABTRANS
                WHERE 
                    no_rekening = :no_rekening";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
                $stmt->execute();
                $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

                // Calculate saldo_akhir
                $saldo_akhir = $transaction['SETORAN'] - $transaction['PENARIKAN'];

                // Only add to result if saldo_akhir is not zero
                if ($saldo_akhir != 0) {
                    $result[] = [
                        "no_rekening" => $no_rekening,
                        "saldo_akhir" => $saldo_akhir
                    ];
                }
            }

            // Return all account numbers with non-zero saldo_akhir
            echo json_encode($result);
        } else {
            // Return an empty array if no accounts are found
            echo json_encode([]);
        }
    } else {
        echo json_encode(["error" => "CIF parameter missing."]);
    }
} catch (PDOException $e) {
    // Log the error internally and return a general error message
    error_log("Database error: " . $e->getMessage()); // Logs the actual error
    echo json_encode(["error" => "An internal error occurred."]);
} finally {
    // Close the connection
    $db = null;
}
