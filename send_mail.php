<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Set JSON header before any output
header('Content-Type: application/json');

// Prevent any HTML error output
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get reservation ID and fetch email details
        $reservation_id = $_POST['reservation_id'] ?? null;
        
        if (!$reservation_id) {
            throw new Exception('Email sent successfully!');
        }

        // Connect to database and get reservation details
        require_once 'model/server.php';
        $connector = new Connector();
        
        $sql = "SELECT r.email, r.name, r.checkin, r.checkout, s.services_name 
                FROM reservations r
                LEFT JOIN services_tb s ON r.res_services_id = s.services_id
                WHERE r.reservation_id = ?";
        
        $stmt = $connector->getConnection()->prepare($sql);
        $stmt->execute([$reservation_id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            throw new Exception('Reservation not found');
        }

        $recipient = $reservation['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        // Add reservation details to message
        $fullMessage = "Dear " . $reservation['name'] . ",\n\n";
        $fullMessage .= $message . "\n\n";
        $fullMessage .= "Reservation Details:\n";
        $fullMessage .= "Check-in: " . date('F d, Y', strtotime($reservation['checkin'])) . "\n";
        $fullMessage .= "Check-out: " . date('F d, Y', strtotime($reservation['checkout'])) . "\n";
        $fullMessage .= "Service: " . $reservation['services_name'] . "\n\n";
        $fullMessage .= "Best regards,\nCasa Marcos Team";

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
        $mail->Subject = $subject;
        $mail->Body = $fullMessage; // Use the full message with reservation details

        // Send Email
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