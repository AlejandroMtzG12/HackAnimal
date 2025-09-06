<?php
session_start();
include_once('../conf/db.config.php'); // Ajusta la ruta según tu proyecto

// Conexión
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibir datos del formulario
    $name        = $_POST['name'] ?? null;
    $email       = $_POST['email'] ?? null;
    $country     = $_POST['country'] ?? null;
    $state       = $_POST['state'] ?? null;
    $postalCode  = $_POST['postalCode'] ?? null;
    $street      = $_POST['street'] ?? null;
    $age         = $_POST['age'] ?? null;
    $housingType = $_POST['housingType'] ?? null;
    $yard        = $_POST['yard'] ?? null;
    $otherPets   = $_POST['otherPets'] ?? null;
    $children    = $_POST['children'] ?? null;
    $freeTime    = $_POST['freeTime'] ?? null;
    $phone       = $_POST['phone'] ?? null;
    $description = $_POST['description'] ?? null;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO adopter 
            (name, email, country, state, postalCode, street, age, housingType, yard, otherPets, children, freeTime, phone, description) 
            VALUES 
            (:name, :email, :country, :state, :postalCode, :street, :age, :housingType, :yard, :otherPets, :children, :freeTime, :phone, :description)
        ");

        $stmt->execute([
            ':name'        => $name,
            ':email'       => $email,
            ':country'     => $country,
            ':state'       => $state,
            ':postalCode'  => $postalCode,
            ':street'      => $street,
            ':age'         => $age,
            ':housingType' => $housingType,
            ':yard'        => $yard,
            ':otherPets'   => $otherPets,
            ':children'    => $children,
            ':freeTime'    => $freeTime,
            ':phone'       => $phone,
            ':description' => $description
        ]);

        echo "<script>alert('Formulario enviado con éxito'); window.location.href='forms.html';</script>";

    } catch (PDOException $e) {
        echo "Error al guardar: " . $e->getMessage();
    }
}
?>
