<?php
require_once 'server.php';
class Reservation_Model {
    private $conn;
    
    public function __construct() {
        $connector = new Connector();
        $this->conn = $connector->getConnection();
        
        if (!$this->conn) {
            throw new Exception("Database connection failed");
        }
    }

    
    public function get_booking() {
        try {
            $sql = "SELECT r.*, s.*, rs.* 
                    FROM booking_tb r
                    JOIN services_tb s ON r.service_id = s.services_id
                    JOIN reservations rs ON r.booking_id = rs.reservation_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error fetching reservations: " . $e->getMessage());
            return [];
        }
    }

    public function get_reservation_by_id($reservation_id) {
        try {
            $sql = "SELECT r.*, s.*, rs.*
                    FROM booking_tb r
                    JOIN services_tb s ON r.service_id = s.services_id
                    JOIN reservations rs ON r.booking_id = rs.reservation_id
                    WHERE r.booking_id =?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$reservation_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error fetching reservation by ID: ". $e->getMessage());
            return null;
        }
    }

    public function get_reservation() {
        try {
            $sql = "SELECT r.*, s.services_name, s.services_price 
                    FROM reservations r
                    LEFT JOIN services_tb s ON r.res_services_id = s.services_id
                    WHERE r.status IN ('pending', 'approved', 'cancelled')
                    ORDER BY r.reservation_id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error fetching reservations: " . $e->getMessage());
            return [];
        }
    }

    // Check if a service exists by its ID
    public function get_service_name_by_id($services_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM services_tb WHERE services_id = ?");
        $stmt->execute([$services_id]);  // Keep only one execute() call
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    // Insert a new booking into the database
    public function insert_reservation($name, $email, $phone, $checkin, $checkout, $message, $service_id, $status = 'pending') {
        // Validate if the service exists
        if (!$this->get_service_name_by_id($service_id)) {
            return "";
        }

        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare("INSERT INTO reservations (name, email, phone, checkin, checkout, message, status) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Bind the parameters
        $stmt->execute([$name, $email, $phone, $checkin, $checkout, $message, $status]);
        
        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            // If successful, return true
// PDO statements don't need explicit closing - they are automatically closed when the reference is destroyed
            return true;
        } else {
            // If there was an error, return an error message
        $error = $stmt->errorInfo()[2];
// PDO statements don't need explicit closing - they are automatically closed when the reference is destroyed
        $stmt = null;
            return "Error: " . $error;
        }
    }

    // Fetch booking details by booking ID
    public function get_reservations_by_id($reservation_id) {
        $stmt = $this->conn->prepare("SELECT * FROM reservations WHERE reservation_id = ?");
        $stmt->execute([$reservation_id]);  // Keep only one execute() call
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // No booking found
        }

        $stmt->close();
    }

    // Get all bookings that are pending approval
    public function get_pending_reservations() {
        $sql = "SELECT reservation_id, name, email, phone, date FROM reservations WHERE status = 'pending'";
        $result = $this->conn->prepare($sql);
        $reservations = [];

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $reservations[] = $row;
            }
        }

        return $reservations;
    }


    // Close the database connection
    public function close_connection() {
    $this->conn = null; // Close PDO connection by setting it to null
    }

    public function updateReservationStatus($reservation_id, $status) {
        $sql = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $reservation_id]);
    }

    public function get_reservation_by_status($status) {
        try {
            $validStatuses = ['pending', 'approved', 'cancelled', 'checkedin', 'checkedout'];
            $status = is_array($status) ? $status[0] : $status; // Handle if status is an array
            
            if (!in_array(strtolower($status), $validStatuses)) {
                throw new InvalidArgumentException("Invalid status provided");
            }

            $sql = "SELECT r.*, s.services_name, s.services_price 
                    FROM reservations r
                    LEFT JOIN services_tb s ON r.res_services_id = s.services_id
                    WHERE r.status = :status
                    ORDER BY r.reservation_id DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result === false) {
                throw new PDOException("Error fetching data");
            }
            
            return $result;
            
        } catch(PDOException $e) {
            error_log("Error fetching reservations by status: " . $e->getMessage());
            return [];
        } catch(InvalidArgumentException $e) {
            error_log("Invalid status parameter: " . $e->getMessage());
            return [];
        }
    }
}
?>
