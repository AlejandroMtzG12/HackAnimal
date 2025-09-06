<?php
require 'databaseconnection.php';
header('Content-Type: application/json; charset=utf-8');

$adoptionCenterId = $_GET['user_id'] ?? 1;
$status = $_GET['status'] ?? 'UpForAdoption'; // corregido

try {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1) Foto del shelter
    $stmt = $pdo->prepare("SELECT photo FROM adoptionCenter WHERE id = ?");
    $stmt->execute([$adoptionCenterId]);
    $shelterRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $shelterPhoto = $shelterRow ? $shelterRow['photo'] : null;

    // 2) Mascotas
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
                   p.image,
                   f.status, 
                   f.conditions, 
                   f.disability, 
                   f.sterilization, 
                   f.quarantine,
                   GROUP_CONCAT(v.name) AS vaccines
            FROM pet p
            INNER JOIN file f ON p.fileId = f.id
            LEFT JOIN vaccines v ON v.idFile = f.id
            WHERE p.adoptionCenterId = ? AND f.status = ?
            GROUP BY p.id";

    $q = $pdo->prepare($sql);
    $q->execute([$adoptionCenterId, $status]);
    $mascotas = $q->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect();

    // 3) Respuesta combinada
    echo json_encode([
        'photo' => $shelterPhoto,   // 👈 el campo real de tu tabla
        'pets' => $mascotas
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
