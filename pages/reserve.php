<?php
    require_once '../model/reservationModel.php';
    require_once '../model/server.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reservation_id = $_POST['reservation_id'] ?? null;
        $action = $_POST['action'] ?? null;
        
        if (!$reservation_id || !$action) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            exit;
        }
        
        $model = new Reservation_Model();
        $status = $action === 'approved' ? 'Approved' : 'Cancelled';
        
        try {
            $result = $model->updateReservationStatus($reservation_id, $status);
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
?>