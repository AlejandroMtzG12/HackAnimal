<?php
require 'conf/db.config.php';

if (!isset($_GET['id'])) {
    die("No se proporcionó ID de mascota.");
}

$petId = intval($_GET['id']);

try {
$db = new Database();
$pdo = $db->getConnection();

    $stmt = $pdo->prepare("
        SELECT p.*, f.status AS file_status, f.sterilization, GROUP_CONCAT(v.name) AS vaccines
        FROM pet p
        INNER JOIN file f ON p.fileId = f.id
        LEFT JOIN vaccines v ON v.idFile = f.id
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$petId]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        die("Mascota no encontrada.");
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Animal</title>
  <link href="css/styles_addanimal.css" rel="stylesheet" type="text/css" />
</head>
<body>

  <div class="form-container">
    <h2>Edit Pet</h2>
    <form action="apis/editpet.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $pet['id']; ?>">

      <label for="photo">Photo</label>
      <div class="photobox" id="previewBox">
        <?php if ($pet['image']): ?>
          <img src="<?php echo $pet['image']; ?>" alt="Preview">
        <?php else: ?>
          <span>+</span>
        <?php endif; ?>
      </div>
      <input type="file" name="photo" id="photoInput">

      <label for="name">Name</label>
      <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($pet['name']); ?>">

      <label for="species">Species</label>
      <select name="species" id="species">
        <option value="Dog" <?php if ($pet['species']=="Dog") echo "selected"; ?>>Dog</option>
        <option value="Cat" <?php if ($pet['species']=="Cat") echo "selected"; ?>>Cat</option>
        <option value="SmallMammal" <?php if ($pet['species']=="SmallMammal") echo "selected"; ?>>Small Mammal</option>
      </select>

      <label for="gender">Gender</label>
      <select name="gender" id="gender">
        <option value="male" <?php if ($pet['gender']=="male") echo "selected"; ?>>Male ♂</option>
        <option value="female" <?php if ($pet['gender']=="female") echo "selected"; ?>>Female ♀</option>
        <option value="intersex" <?php if ($pet['gender']=="intersex") echo "selected"; ?>>Intersex ⚥</option>
      </select>

      <label for="status">Status</label>
      <select name="status" id="status">
        <option value="UpForAdoption" <?php if ($pet['file_status']=="UpForAdoption") echo "selected"; ?>>Up for adoption</option>
        <option value="InQuarantine" <?php if ($pet['file_status']=="InQuarantine") echo "selected"; ?>>In quarantine</option>
        <option value="Adopted" <?php if ($pet['file_status']=="Adopted") echo "selected"; ?>>Adopted</option>
      </select>

      <label for="age">Age (years)</label>
      <input type="number" name="age" id="age" min="0" value="<?php echo $pet['age']; ?>">

      <label for="breed">Breed</label>
      <input type="text" name="breed" id="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>">

      <label for="size">Size</label>
      <select name="size" id="size">
        <option value="ExtraSmall" <?php if ($pet['size']=="ExtraSmall") echo "selected"; ?>>Extra Small</option>
        <option value="Small" <?php if ($pet['size']=="Small") echo "selected"; ?>>Small</option>
        <option value="Medium" <?php if ($pet['size']=="Medium") echo "selected"; ?>>Medium</option>
        <option value="Large" <?php if ($pet['size']=="Large") echo "selected"; ?>>Large</option>
        <option value="ExtraLarge" <?php if ($pet['size']=="ExtraLarge") echo "selected"; ?>>Extra Large</option>
      </select>

      <label for="color">Color</label>
      <input type="text" name="color" id="color" value="<?php echo htmlspecialchars($pet['color']); ?>">

      <label for="coat">Coat</label>
      <select name="coat" id="coat">
        <option value="none" <?php if ($pet['coat']=="none") echo "selected"; ?>>None</option>
        <option value="short" <?php if ($pet['coat']=="short") echo "selected"; ?>>Short</option>
        <option value="medium" <?php if ($pet['coat']=="medium") echo "selected"; ?>>Medium</option>
        <option value="large" <?php if ($pet['coat']=="large") echo "selected"; ?>>Large</option>
      </select>

      <label for="weight">Weight (kg)</label>
      <input type="number" name="weight" id="weight" step="0.1" value="<?php echo $pet['weight']; ?>">

      <label for="sterilization">Sterilized</label>
      <select name="sterilization" id="sterilization">
        <option value="1" <?php if ($pet['sterilization']=="1") echo "selected"; ?>>Yes</option>
        <option value="0" <?php if ($pet['sterilization']=="0") echo "selected"; ?>>No</option>
      </select>

      <label for="vaccines">Vaccines</label>
      <div id="vaccines-container">
        <?php
        if ($pet['vaccines']) {
            foreach (explode(",", $pet['vaccines']) as $vac) {
                echo '<input type="text" name="vaccines[]" value="'.htmlspecialchars(trim($vac)).'">';
            }
        } else {
            echo '<input type="text" name="vaccines[]" placeholder="Enter vaccine">';
        }
        ?>
      </div>
      <button type="button" id="add-vaccine">+ Add Vaccine</button>

      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($pet['description']); ?></textarea>

      <button type="submit">Save Animal</button>
    </form>
  </div>

<script>
  const photoInput = document.getElementById("photoInput");
  const previewBox = document.getElementById("previewBox");

  photoInput.addEventListener("change", function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewBox.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
      }
      reader.readAsDataURL(file);
    }
  });

  document.getElementById("add-vaccine").addEventListener("click", function() {
    const container = document.getElementById("vaccines-container");
    const input = document.createElement("input");
    input.type = "text";
    input.name = "vaccines[]";
    input.placeholder = "Enter vaccine";
    container.appendChild(input);
  });
</script>

</body>
</html>
