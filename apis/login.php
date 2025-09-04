<?php
session_start();
include_once('../conf/db.config.php'); 
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT id, name, password FROM adopter WHERE user = ?");
    $query->execute([$user]);
    $r = $query->fetch(PDO::FETCH_ASSOC);

    if ($r && password_verify($password, $r['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $r['id'];
        $_SESSION['name'] = $r['name'];
        header('Location: ../index.html');
        exit();
    } else {
        echo "
    <!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error de Inicio de Sesión</title>

    <!-- Bootstrap CSS -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet'>

    <!-- Google Font -->
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap' rel='stylesheet'>
    <link href='../css/style.css' rel='stylesheet'>
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

        .footer {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body id='fondo'>
    <div class='error-container'>
        <div class='error-icon'>😕</div>
        <h2>¡Uy! Datos incorrectos</h2>
        <p>El correo o la contraseña no coinciden. <br>Inténtalo de nuevo, por favor.</p>
        <a href='login.html' class='retry-btn'>Volver al inicio</a>
    </div>

    <!-- Bootstrap JS -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>

    "; // Mensaje de error
    }
}
    

?>
