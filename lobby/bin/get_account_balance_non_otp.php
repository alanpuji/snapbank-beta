<?php
// Include the database connection file
include "../../bin/koneksi.php";

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Initialize the database connection
$database = new Connection_Core();
$db = $database->openConnection();

try {
    if (isset($_GET['no_rekening'])) {
        $no_rekening = $_GET['no_rekening'];

        $query = "
        SELECT 
            saldo_blokir
        FROM 
            tabung
        WHERE 
            tabung.NO_REKENING = :no_rekening
    ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
        $stmt->execute();
        $accountData = $stmt->fetch(PDO::FETCH_ASSOC);
        $saldo_blokir = $accountData['saldo_blokir'];

        $query = "
        SELECT 
            minimum
        FROM 
            tabung
        WHERE 
            tabung.NO_REKENING = :no_rekening
    ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
        $stmt->execute();
        $accountData = $stmt->fetch(PDO::FETCH_ASSOC);
        $saldo_minimal = $accountData['minimum'];

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

        $stmt = $db->prepare($query);
        $stmt->bindParam(':no_rekening', $no_rekening, PDO::PARAM_STR);
        $stmt->execute();
        $accountData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($accountData) {
            $saldo_akhir = $accountData['SETORAN'] - $accountData['PENARIKAN'];
            echo json_encode([
                "saldo_akhir" => $saldo_akhir,
                "saldo_minimal" => $saldo_minimal,
                "saldo_blokir" => $saldo_blokir
            ]);
        } else {
            echo json_encode(["error" => "Account not found."]);
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
}
