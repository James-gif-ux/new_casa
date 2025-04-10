<?php
require_once '../model/server.php';
$connector = new Connector();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Get reservation details
    $sql = "SELECT r.name, r.email, r.checkin, r.checkout, s.services_name 
            FROM reservations r
            LEFT JOIN services_tb s ON r.res_services_id = s.services_id
            WHERE r.reservation_id = ?";
    
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($reservation) {
        $to = $reservation['email'];
        
        // Add reservation details to message
        $fullMessage = "Dear " . $reservation['name'] . ",\n\n";
        $fullMessage .= $message . "\n\n";
        $fullMessage .= "Reservation Details:\n";
        $fullMessage .= "Check-in: " . date('F d, Y', strtotime($reservation['checkin'])) . "\n";
        $fullMessage .= "Check-out: " . date('F d, Y', strtotime($reservation['checkout'])) . "\n";
        $fullMessage .= "Service: " . $reservation['services_name'] . "\n\n";
        $fullMessage .= "Best regards,\nCasa Marcos Team";
        
        // Email headers
        $headers = "From: casamarcos@example.com\r\n";
        $headers .= "Reply-To: casamarcos@example.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if(mail($to, $subject, $fullMessage, $headers)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send email']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Reservation not found']);
    }
}
?>