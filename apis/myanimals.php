<?php
require 'databaseconnection.php';
header('Content-Type: application/json');

$adoptionCenterId = $_GET['adoptionCenterId'] ?? 1;
$status = $_GET['status'] ?? 'UpForAdoption'; // corregido

try {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.id AS pet_id, 
                   p.name, 
                   p.species, 
                   p.age, 
                   p.coat, 
                   p.size, 
                   p.color, 
                   p.breed, 
                   p.weight, 
                   p.description, 
                   p.gender,
                   f.status, 
                   f.vaccines, 
                   f.conditions, 
                   f.disability, 
                   f.sterilization, 
                   f.quarantine
            FROM pet p
            INNER JOIN file f ON p.fileId = f.id
            WHERE p.adoptionCenterId = ? AND f.status = ?";

    $q = $pdo->prepare($sql);
    $q->execute([$adoptionCenterId, $status]);
    $mascotas = $q->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect();

    echo json_encode($mascotas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // Para desarrollo: mostrar error en JSON
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
?>
