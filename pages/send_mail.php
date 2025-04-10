<?php
use \HPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        require_once '../model/server.php';
        $connector = new Connector();
        
        // Check if this is a reservation email or general reply
        $reservation_id = $_POST['reservation_id'] ?? null;
        $message_id = $_POST['message_id'] ?? null;
        
        if ($reservation_id) {
            // Handle reservation email
            $sql = "SELECT r.email, r.name, r.checkin, r.checkout, s.services_name 
                    FROM reservations r
                    LEFT JOIN services_tb s ON r.res_services_id = s.services_id
                    WHERE r.reservation_id = ?";
            
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$reservation_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                throw new Exception('Reservation not found');
            }
            
            $recipient = $data['email'];
            $fullMessage = "Dear " . $data['name'] . ",\n\n";
            $fullMessage .= $_POST['message'] . "\n\n";
            $fullMessage .= "Reservation Details:\n";
            $fullMessage .= "Check-in: " . date('F d, Y', strtotime($data['checkin'])) . "\n";
            $fullMessage .= "Check-out: " . date('F d, Y', strtotime($data['checkout'])) . "\n";
            $fullMessage .= "Service: " . $data['services_name'] . "\n\n";
            $fullMessage .= "Best regards,\nCasa Marcos Team";
            
        } else if ($message_id) {
            // Handle general reply
            $recipient = $_POST['recipient_email'];
            $fullMessage = $_POST['reply_content'] . "\n\n";
            $fullMessage .= "Best regards,\nCasa Marcos Team";
            
            // Update message status in database
            $sql = "UPDATE messages SET status = 1, reply_date = NOW() WHERE message_id = ?";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$message_id]);
            
            // Store reply in database
            $sql = "INSERT INTO replies (message_id, reply_content, date_sent) VALUES (?, ?, NOW())";
            $stmt = $connector->getConnection()->prepare($sql);
            $stmt->execute([$message_id, $_POST['reply_content']]);
        } else {
            throw new Exception('Invalid request parameters');
        }

        $mail = new PHPMailer(true);
        
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jjbright0402@gmail.com';
        $mail->Password = 'tuyh dazt wthj flio';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email Settings
        $mail->setFrom('jjbright0402@gmail.com', 'CASA MARCOS');
        $mail->addAddress($recipient);
        $mail->Subject = $_POST['subject'] ?? 'Response from Casa Marcos';
        $mail->Body = $fullMessage;

        if ($mail->send()) {
            echo json_encode(['success' => true, 'message' => 'Email sent successfully!']);
        } else {
            throw new Exception('Failed to send email');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error: ' . strip_tags($e->getMessage())
        ]);
    }
    exit();
}