<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include_once('../conf/db.config.php');

// Conexión
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos de mascota
    $name = $_POST['name'];
    $species = $_POST['species'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $age = $_POST['age'];
    $breed = $_POST['breed'];
    $size = $_POST['size'];
    $coat = $_POST['coat'];
    $color = $_POST['color'];
    $weight = $_POST['weight'];
    $sterilization = $_POST['sterilization'];
    $description = $_POST['description'];

    // Foto
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../petImages/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('pet_') . "_" . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photoPath = 'petImages/' . $fileName;
        }
    }

    try {
        $pdo->beginTransaction();

        // 1. Insertar en file
        $stmtFile = $pdo->prepare("
            INSERT INTO file (status, sterilization) 
            VALUES (:status, :sterilization)
        ");
        $stmtFile->execute([
            ':status' => $status,
            ':sterilization' => $sterilization
        ]);
        $fileId = $pdo->lastInsertId();

        // 2. Insertar en pet
        $stmtPet = $pdo->prepare("
            INSERT INTO pet 
            (adoptionCenterId, species, name, age, coat, size, color, breed, weight, description, image, gender, fileId)
            VALUES 
            (:adoptionCenterId, :species, :name, :age, :coat, :size, :color, :breed, :weight, :description, :image, :gender, :fileId)
        ");
        $stmtPet->execute([
            ':adoptionCenterId' => $_SESSION['adoptionCenterId'],
            ':species' => $species,
            ':name' => $name,
            ':age' => $age,
            ':coat' => $coat,
            ':size' => $size,
            ':color' => $color,
            ':breed' => $breed,
            ':weight' => $weight,
            ':description' => $description,
            ':image' => $photoPath,
            ':gender' => $gender,
            ':fileId' => $fileId
        ]);

        // 3. Insertar vacunas (si hay)
        if (!empty($_POST['vaccines'])) {
            $stmtVac = $pdo->prepare("INSERT INTO vaccines (name, idFile) VALUES (:name, :idFile)");
            foreach ($_POST['vaccines'] as $vaccineName) {
                if (!empty(trim($vaccineName))) {
                    $stmtVac->execute([
                        ':name' => $vaccineName,
                        ':idFile' => $fileId
                    ]);
                }
            }
        }

        $pdo->commit();
        echo "<script>alert('Mascota registrada con éxito'); window.location.href='../myanimals.html';</script>";

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al guardar: " . $e->getMessage();
    }
}
?>
