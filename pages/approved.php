<?php
require_once '../model/reservationModel.php';
require_once '../model/server.php';

// Handle approved status first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $action = $_POST['action'] ?? null;
    
    if (!$reservation_id || !$action) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    $model = new Reservation_Model();
    
    // Set approved status before check-in/check-out
    if ($action === 'approve') {
        $status = 'Approved';
    } else {
        $status = $action === 'checkin' ? 'Checked In' : 'Cancelled';
    }
    
    try {
        $result = $model->updateReservationStatus($reservation_id, $status);
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>