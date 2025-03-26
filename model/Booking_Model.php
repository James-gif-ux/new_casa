<?php
require_once 'server.php';
class Booking_Model {
    private $conn;

    public function __construct() {
        $connector = new Connector();
        $this->conn = $connector->getConnection();
        
        if (!$this->conn) {
            throw new Exception("Database connection failed");
        }
    }

    // Fetch all available services from the database
    public function get_service() {
        $sql = "SELECT * FROM services_tb";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function basename(string $path, string $suffix = ''): string {
        // Return empty string if path is empty
        if (empty($path)) {
            return '';
        }
    
    // Get the base name from the path
        $base = basename($path);
        
        // If suffix is provided and matches end of base, remove it
        if ($suffix && str_ends_with($base, $suffix)) {
            return substr($base, 0, -strlen($suffix));
        }
        
        return $base;
    }
    // Fetch all images from the database
    function add_services(){
         // Check if required fields are set
         if (!isset($_POST['services_name']) || empty($_POST['services_name'])) {
            // Handle the error - maybe return false or throw an exception
            return false;
        }
        $sql = "INSERT INTO services_tb (services_name, services_image, services_description, services_price) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $_POST['services_name']);
        $stmt->bindParam(2, $_POST['services_image']);
        $stmt->bindParam(3, $_POST['services_description']);
        $stmt->bindParam(4, $_POST['services_price']);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_images() {
        $sql = "SELECT * FROM image_tb";
        $result = $this->conn->prepare($sql);
        $images = [];
        
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $images[] = $row;
            }
        }

        return $images;
    }
    public function add_images(){
        $sql = "INSERT INTO image_tb (image_name, image_img, image_description, image_price) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $image_name);
        $stmt->bindParam(2, $image_img);
        $stmt->bindParam(3, $image_description);
        $stmt->bindParam(4, $image_price);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if a service exists by its ID
    public function get_service_name_by_id($services_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM services_tb WHERE services_id = ?");
        $stmt->bindparam("i", $services_id);  // "i" for integer
        $stmt->execute();
        $count = $stmt->execute();
        $stmt->fetch();
        $stmt = null; // Release the statement handle

        return $count > 0;  // Return true if the service exists, false otherwise
    }

    // Insert a new booking into the database
    public function insert_booking($data) {
        $sql = "INSERT INTO booking_tb (booking_fullname, booking_email, booking_number, 
                booking_check_in, booking_check_out, booking_status, total_amount) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['booking_fullname'],
            $data['booking_email'],
            $data['booking_number'],
            $data['booking_check_in'],
            $data['booking_check_out'],
            $data['booking_status'],
            $data['total_amount'],
        ]);
    }

    // Fetch booking details by booking ID
    public function get_booking_by_id($booking_id) {
        $stmt = $this->conn->prepare("SELECT * FROM booking_tb WHERE booking_id = ?");
        $stmt->bindparam("i", $booking_id);
        $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // No booking found
        }

        $stmt->close();
    }

    // Get all bookings that are pending approval
    public function get_pending_bookings() {
        $sql = "SELECT booking_id, booking_fullname, booking_email, booking_number, booking_check_in, booking_check_out FROM booking_tb WHERE booking_status = 'pending'";
        $result = $this->conn->prepare($sql);
        $bookings = [];

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $bookings[] = $row;
            }
        }

        return $bookings;
    }

    public function get_bookings(){
        $sql = "SELECT * FROM booking_tb";
        $result = $this->conn->prepare($sql);
        $bookings = [];
    }

    public function update_room($id, $name, $description, $price, $image) {
        try {
            $sql = "UPDATE services_tb SET 
                    services_name = ?, 
                    services_description = ?, 
                    services_price = ?, 
                    services_image = ? 
                    WHERE services_id = ?";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$name, $description, $price, $image, $id]);
            
            return true;
        } catch (PDOException $e) {
            return "Database Error: " . $e->getMessage();
        }
    }
    public function updateBookingStatus($booking_id, $status) {
        $sql = "UPDATE booking_tb SET booking_status = ? WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $booking_id]);
    }
    public function add_room($name, $description, $price, $image) {
        $sql = "INSERT INTO services_tb (services_description, services_name, services_price, services_image) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$description, $name, $price, $image]);
    }

    public function edit_rooms($id, $description, $name, $price, $image) {
        try {
            $sql = "UPDATE services_tb SET services_description = ?, services_name = ?, services_price = ?, services_image = ? WHERE services_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$description, $name, $price, $image, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function process_payment($reservation_id, $reference, $image, $amount, $date, $status) {
        try {
            $sql = "INSERT INTO payment_tb (payment_reservation_id, payment_reference, payment_image, 
                    payment_amount, payment_date, payment_status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$reservation_id, $reference, $image, $amount, $date, $status]);
            
            if ($stmt->rowCount() > 0) {
                // Update booking status
                $update_sql = "UPDATE booking_tb SET booking_status = ? WHERE booking_id = ?";
                $update_stmt = $this->conn->prepare($update_sql);
                $update_stmt->execute([$status, $reservation_id]);
                
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Payment Error: " . $e->getMessage());
            return false;
        }
    }
    public function get_reservations_with_payments() {
        try {
            $sql = "SELECT b.*, p.payment_reference, p.payment_amount, p.payment_date, p.payment_status 
                    FROM booking_tb b 
                    LEFT JOIN payment_tb p ON b.booking_id = p.payment_reservation_id 
                    ORDER BY b.booking_id DESC";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reservations with payments: " . $e->getMessage());
            return [];
        }
    }

    public function get_reservation_with_payment($booking_id) {
        try {
            $sql = "SELECT b.*, p.payment_reference, p.payment_amount, p.payment_date, p.payment_status 
                    FROM booking_tb b 
                    LEFT JOIN payment_tb p ON b.booking_id = p.payment_reservation_id 
                    WHERE b.booking_id = ?";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$booking_id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reservation with payment: " . $e->getMessage());
            return null;
        }
    }
}
?>
