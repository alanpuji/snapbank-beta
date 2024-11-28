<?php
// Include the database connection file
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Initialize the database connection
$database = new Connection();
$db = $database->openConnection();
$database = new Connection_Core();
$db_core = $database->openConnection();

try {
    if (isset($_GET['otpCode']) && isset($_GET['no_rekening'])) {
        $otpCode = $_GET['otpCode'];
        $no_rekening = $_GET['no_rekening'];

        // Define the SQL query with placeholders
        $query = "
            SELECT *
            FROM 
                log_cek_saldo
            WHERE 
                kode_otp = :otp_code AND status_log=1 AND no_rekening= :no_rekening
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':otp_code', $otpCode, PDO::PARAM_STR);
        $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            // Reuse the same database connection
            $query = "
                SELECT 
                    SUM(IF(FLOOR(TABTRANS.MY_KODE_TRANS / 100) = 1, TABTRANS.POKOK, 0)) AS SETORAN, 
                    SUM(IF(FLOOR(TABTRANS.MY_KODE_TRANS / 100) = 2, TABTRANS.POKOK, 0)) AS PENARIKAN
                FROM 
                    tabung
                INNER JOIN 
                    TABTRANS ON tabung.no_rekening = TABTRANS.no_rekening
                WHERE 
                    tabung.NO_REKENING = :no_rekening
                GROUP BY 
                    tabung.NO_REKENING
            ";

            $stmt = $db_core->prepare($query);
            $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
            $stmt->execute();
            $accountData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($accountData) {
                $saldo_akhir = $accountData['SETORAN'] - $accountData['PENARIKAN'];

                // Update OTP status to 0 after successful verification
                $updateQuery = "
                                UPDATE log_cek_saldo
                                SET status_log = 0
                                WHERE kode_otp = :otp_code
                            ";

                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bindParam(':otp_code', $otpCode, PDO::PARAM_STR);
                $updateStmt->execute();

                echo json_encode([
                    "saldo_akhir" => $saldo_akhir
                ]);
            } else {
                echo json_encode(["error" => "Account not found."]);
            }
        } else {
            echo json_encode(["error" => "Invalid OTP code."]);
        }
    } else {
        echo json_encode(["error" => "Missing parameters."]);
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    // Close the connection
    $db = null;
    $db_core = null;
}
