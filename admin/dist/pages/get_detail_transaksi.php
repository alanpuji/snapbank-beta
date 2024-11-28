<?php
include "../../../bin/koneksi.php";

try {
    $database = new Connection();
    $db = $database->openConnection();

    if (isset($_GET['id_unik'])) {
        $id_unik = $_GET['id_unik'];

        $stmt = $db->prepare("SELECT A.nama_lengkap, B.id_trans, B.no_rekening, B.nominal, B.date_modified, B.time_modified, B.jenis_trans, B.status_trans, B.tgl_trans, B.jam_trans, A.nomor_hp FROM customer A LEFT JOIN transaksi_tab B ON B.id_customer=A.id_customer WHERE A.id_unik = :id_unik AND B.status_trans='0' ORDER BY B.id_trans DESC LIMIT 1");
        $stmt->execute(['id_unik' => $id_unik]);

        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            echo json_encode(['success' => true, 'data' => $transaction]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No transaction ID provided']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
