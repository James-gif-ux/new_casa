    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    header('Content-Type: application/json');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $recipient = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Change for Yahoo, Outlook, etc.
            $mail->SMTPAuth = true;
            $mail->Username = 'jjbright0402@gmail.com'; // Your email
            $mail->Password = 'tuyh dazt wthj flio'; // Use an App Password
            $mail->SMTPSecure = 'tls'; // Use 'ssl' for port 465
            $mail->Port = 587; // Use 465 for SSL

            // Email Settings
            $mail->setFrom('jjbright0402@gmail.com', 'CASA MARCOS'); 
            $mail->addAddress($recipient); 

            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send Email
            if ($mail->send()) {
                echo json_encode(['success' => true, 'message' => 'Email sent successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send email.']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
        exit();
    }
		/**$to = $_POST['email'];
        $headers = "From: casa-marcos@gmail.com" . "\r\n" . "CC: ".$to;
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        if (mail($to, $subject, $message, $headers)){
            echo json_encode(['success' => true, 'message' => 'Email sent successfully!']);
        } else {
            //echo json_encode(['success' => false, 'message' => 'Failed to send email.']);
            $error = error_get_last();
            if (isset($error['message'])) {
                echo json_encode(['success' => false, 'message' => $error['message']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No specific error details available.']);
            }
        }
        exit();**/
    ?>