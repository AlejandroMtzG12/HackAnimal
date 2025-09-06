<?php
session_start();
include_once('../conf/db.config.php');

// Conexión
$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petId = $_POST['id'];

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

    // Foto nueva (opcional)
    $photoPath = null;

    try {
        $pdo->beginTransaction();

        // Buscar fileId e imagen actual de la mascota
        $stmtFind = $pdo->prepare("SELECT fileId, image 
                                   FROM pet 
                                   WHERE id = :id AND adoptionCenterId = :centerId");
        $stmtFind->execute([
            ':id' => $petId,
            ':centerId' => $_SESSION['user_id']
        ]);
        $petRow = $stmtFind->fetch(PDO::FETCH_ASSOC);

        if (!$petRow) {
            throw new Exception("Mascota no encontrada o no pertenece a tu refugio");
        }

        $fileId = $petRow['fileId'];
        $oldImage = $petRow['image'];

        // Procesar nueva foto
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../petImages/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid('pet_') . "_" . basename($_FILES['photo']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                $photoPath = 'petImages/' . $fileName;

                // 🔥 Si había imagen anterior, eliminarla
                if ($oldImage && file_exists(__DIR__ . '/../' . $oldImage)) {
                    unlink(__DIR__ . '/../' . $oldImage);
                }
            }
        }

        // Actualizar file
        $stmtFile = $pdo->prepare("
            UPDATE file 
            SET status = :status, sterilization = :sterilization
            WHERE id = :id
        ");
        $stmtFile->execute([
            ':status' => $status,
            ':sterilization' => $sterilization,
            ':id' => $fileId
        ]);

        // Actualizar pet
        $sqlPet = "
            UPDATE pet
            SET species = :species, name = :name, age = :age, coat = :coat, size = :size, 
                color = :color, breed = :breed, weight = :weight, description = :description, 
                gender = :gender
        ";
        if ($photoPath) {
            $sqlPet .= ", image = :image";
        }
        $sqlPet .= " WHERE id = :id AND adoptionCenterId = :centerId";

        $stmtPet = $pdo->prepare($sqlPet);
        $paramsPet = [
            ':species' => $species,
            ':name' => $name,
            ':age' => $age,
            ':coat' => $coat,
            ':size' => $size,
            ':color' => $color,
            ':breed' => $breed,
            ':weight' => $weight,
            ':description' => $description,
            ':gender' => $gender,
            ':id' => $petId,
            ':centerId' => $_SESSION['user_id']
        ];
        if ($photoPath) {
            $paramsPet[':image'] = $photoPath;
        }
        $stmtPet->execute($paramsPet);

        // Vacunas: eliminar las anteriores y volver a insertar
        $pdo->prepare("DELETE FROM vaccines WHERE idFile = :idFile")
            ->execute([':idFile' => $fileId]);

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

        echo "<script>alert('Mascota actualizada con éxito'); window.location.href='../myanimals.html';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al actualizar: " . $e->getMessage();
    }
}
