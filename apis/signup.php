<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include_once('../conf/db.config.php'); 

// Instanciar la conexión
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recolectar datos
    $user = $_POST['user'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $shelterName = $_POST['shelterName'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];
    $street = $_POST['street'];
    $description = !empty($_POST['description']) ? $_POST['description'] : null;

    // Manejo de la foto
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('shelter_') . "_" . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photoPath = 'uploads/' . $fileName;
        }
    }


    try {
        // Preparar query (ajusta columnas según tu tabla adoptioncenter)
        $query = $pdo->prepare("
            INSERT INTO adoptioncenter 
            (user, password, name, country, city, state, postalCode, street, photo) 
            VALUES 
            (:user, :password, :name, :country, :city, :state, :postalCode, :street, :photo)
        ");
        
        // Bind de parámetros
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':name', $shelterName, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':state', $state, PDO::PARAM_STR);
        $query->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
        $query->bindParam(':street', $street, PDO::PARAM_STR);
        $query->bindParam(':photo', $photoPath, PDO::PARAM_STR);

        // Ejecutar
        if ($query->execute()) {
            echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Registro Exitoso</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='d-flex justify-content-center align-items-center vh-100 bg-success text-white'>
    <div class='text-center'>
        <h1>🎉 Registro Exitoso</h1>
        <p>¡Bienvenido $shelterName!</p>
        <a href='../login.html' class='btn btn-light mt-3'>Ir al inicio</a>
    </div>
</body>
</html>";
        } else {
            throw new Exception("Error durante el registro");
        }
    } catch (PDOException $e) {
        echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error al Registrar</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='d-flex justify-content-center align-items-center vh-100 bg-danger text-white'>
    <div class='text-center'>
        <h1>❌ Error</h1>
        <p>No se pudo registrar al usuario.<br> {$e->getMessage()}</p>
        <a href='../signup.html' class='btn btn-light mt-3'>Volver a intentar</a>
    </div>
</body>
</html>";
    }
}
?>
