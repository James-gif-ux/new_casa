<?php
require_once '../model/server.php';
require_once '../model/reservationModel.php';

header('Content-Type: application/json');

try {
    $connector = new Connector();
    
    $checkin = $_POST['checkin'] ?? '';
    $checkout = $_POST['checkout'] ?? '';
    $service_id = $_POST['service_id'] ?? '';

    if (!$checkin || !$checkout || !$service_id) {
        throw new Exception('Missing required fields');
    }

    // Check if room is available
    $sql = "SELECT COUNT(*) as count FROM reservations 
            WHERE res_services_id = :service_id 
            AND status != 'cancelled'
            AND ((checkin BETWEEN :checkin AND :checkout) 
            OR (checkout BETWEEN :checkin AND :checkout)
            OR (:checkin BETWEEN checkin AND checkout))";

    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute([
        ':service_id' => $service_id,
        ':checkin' => $checkin,
        ':checkout' => $checkout
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'available' => $result['count'] == 0,
        'success' => true
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}