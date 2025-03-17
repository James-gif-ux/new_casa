<?php
class Connector {
    private $host = 'localhost';
    private $db_name = 'resort_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }

    // Method to execute update queries (INSERT, UPDATE, DELETE)
    public function executeUpdate($sql, $params = []) {
        try {
            // Validate SQL query
            if (empty($sql)) {
                throw new InvalidArgumentException("SQL query cannot be empty");
            }

            // Validate parameters
            if (!is_array($params)) {
                throw new InvalidArgumentException("Parameters must be an array");
            }

            $stmt = $this->conn->prepare($sql);
            
            // Execute and return result
            $result = $stmt->execute($params);
            
            if (!$result) {
                throw new PDOException("Query execution failed");
            }
            
            return $result;

        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        } catch (InvalidArgumentException $e) {
            error_log("Invalid Input: " . $e->getMessage());
            return false;
        }
    }

    // Method to execute select queries and fetch results
    public function executeSelect($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all results as an associative array
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
