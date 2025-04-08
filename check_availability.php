<?php
require_once '../model/server.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$checkin = $data['checkin'];
$checkout = $data['checkout'];
$services_id = $data['services_id'];

try {
    $sql = "SELECT r.*, s.status 
            FROM reservations r
            JOIN services_tb s ON r.service_id = s.services_id
            WHERE r.service_id = ? 
            AND ((r.checkin <= ? AND r.checkout >= ?) 
            OR (r.checkin <= ? AND r.checkout >= ?)
            OR (r.checkin >= ? AND r.checkout <= ?))";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssss', $services_id, $checkout, $checkin, $checkin, $checkout, $checkin, $checkout);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Get room status
    $statusSql = "SELECT status FROM services_tb WHERE services_id = ?";
    $statusStmt = $conn->prepare($statusSql);
    $statusStmt->bind_param('i', $services_id);
    $statusStmt->execute();
    $roomStatus = $statusStmt->get_result()->fetch_assoc()['status'];
    
    // Check if room is available
    $isAvailable = $result->num_rows === 0 && $roomStatus === 'available';
    
    // Get calendar data for the next 3 months
    $calendarData = getCalendarData($conn, $services_id);
    
    echo json_encode([
        'available' => $isAvailable,
        'calendar_data' => $calendarData,
        'room_status' => $roomStatus
    ]);
    
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

function getCalendarData($conn, $room_id) {
    $calendar = [];
    $start = date('Y-m-d');
    $end = date('Y-m-d', strtotime('+3 months'));
    
    $sql = "SELECT r.*, s.status 
            FROM reservations r
            JOIN services_tb s ON r.service_id = s.services_id
            WHERE r.service_id = ? 
            AND r.checkin BETWEEN ? AND ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $room_id, $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $calendar[] = [
            'start' => $row['checkin'],
            'end' => $row['checkout'],
            'title' => 'Booked',
            'color' => '#ff0000',
            'status' => $row['status']
        ];
    }
    
    return $calendar;
}
?>