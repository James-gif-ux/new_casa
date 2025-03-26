<?php
require_once '../model/server.php';

header('Content-Type: application/json');

$field = $_GET['field'] ?? '';
$value = $_GET['value'] ?? '';

if (empty($field) || empty($value)) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

try {
    $connector = new Connector();
    $conn = $connector->getConnection();
    
    $sql = "SELECT COUNT(*) as count FROM reservations WHERE $field = :value";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':value' => $value]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['exists' => $result['count'] > 0]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>