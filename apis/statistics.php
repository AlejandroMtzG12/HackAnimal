<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require 'databaseconnection.php';
header('Content-Type: application/json');

$adoptionCenterId = $_GET['adoptionCenterId'] ?? 1;

try {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql1 = "SELECT species, COUNT(*) as total FROM pet GROUP BY species";
    $sql2 = "SELECT p.species, f.status, COUNT(*) AS total FROM pet p JOIN file f ON p.fileId = f.id WHERE f.status = 'UpForAdoption' GROUP BY p.species, f.status";
    $sql3 = "SELECT p.species, f.status, COUNT(*) AS total FROM pet p JOIN file f ON p.fileId = f.id WHERE f.status = 'InQuarantine' GROUP BY p.species, f.status";
    $sql4 = "SELECT p.species, f.status, COUNT(*) AS total FROM pet p JOIN file f ON p.fileId = f.id WHERE f.status = 'Adopted' GROUP BY p.species, f.status";
    $sql5 = "SELECT p.species, f.sterilization, COUNT(*) AS total FROM pet p JOIN file f ON p.fileId = f.id GROUP BY p.species, f.sterilization";

    $results = [];

    foreach ([$sql1, $sql2, $sql3, $sql4, $sql5] as $i => $sql) {
        $q = $pdo->prepare($sql);
        $q->execute();
        $results["query".($i+1)] = $q->fetchAll(PDO::FETCH_ASSOC);
    }

    Database::disconnect();

    echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
?>
