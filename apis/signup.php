<?php
session_start();
include_once('../conf/db.config.php'); 

// Instantiate the Database class and get the PDO connection
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data
    $user = $_POST['user'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    try {
        // Prepare the query using PDO
        $query = $pdo->prepare("INSERT INTO adopter (user, password) VALUES (:user, :password)");
        
        // Bind parameters
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        // Execute the query
        if ($query->execute()) {
            echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Registro Exitoso</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/2/28/Papel_picado.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Festive', cursive;
        }
        .success-container {
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid #28a745;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 15px;
        }
        .retry-btn {
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease-in-out;
        }
        .retry-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body id='fondo'>
    <div class='success-container'>
        <div class='success-icon'>🎉</div>
        <h2>¡Registro Exitoso!</h2>
        <p>El usuario ha sido registrado correctamente. <br> ¡Bienvenido a nuestra plataforma!</p>
        <a href='login.html' class='retry-btn'>Volver al inicio</a>
    </div>
</body>
</html>
";
        } else {
            throw new Exception("Error during registration!");
        }
    } catch (PDOException $e) {
        // Catch any database errors and display a user-friendly message
        echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error al Registrar</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/2/28/Papel_picado.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Festive', cursive;
        }
        .error-container {
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid #e74c3c;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        .error-icon {
            font-size: 80px;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        .retry-btn {
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease-in-out;
        }
        .retry-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body id='fondo'>
    <div class='error-container'>
        <div class='error-icon'>❌</div>
        <h2>¡Uy! Hubo un error</h2>
        <p>No se pudo registrar al usuario. <br> Por favor, inténtalo de nuevo más tarde.</p>
        <a href='register.html' class='retry-btn'>Volver a intentar</a>
    </div>
</body>
</html>
";
    }
}
?>
