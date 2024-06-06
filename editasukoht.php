<?php
include ("config.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
  header('Location: login.php');
  exit();
}

if (isset($_GET['location'])) {
    $selectedLocationId = $_GET['location'];
    $locationQuery = "SELECT * FROM asukohad WHERE id = '$selectedLocationId'";
    $selectedLocation = $yhendus->query($locationQuery);
    if ($selectedLocation && $selectedLocation->num_rows > 0) {
        $row = mysqli_fetch_assoc($selectedLocation);
    } else {
        echo "<div class='alert alert-danger'>Seda asukohta ei leitud.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>asukohta pole valitud.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>baaaaaaaaanaaaaaaaaaaaanaaaaaaaaa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"
    >
</head>
<body>
    <div class="container">
        <h1>Edit Location <?php echo htmlspecialchars($row['nimi']); ?></h1>
        <hr>
        <a href="admin.php" class="btn btn-secondary mb-3">Back</a>
        <form method="post">
            <div class="mb-3">
                <label for="nimi" class="form-label">Name</label>
                <input type="text" name="nimi" id="nimi" class="form-control" value="<?php echo htmlspecialchars($row['nimi']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="aadress" class="form-label">Address</label>
                <input type="text" name="aadress" id="aadress" class="form-control" value="<?php echo htmlspecialchars($row['asukoht']); ?>" required>
            </div>

            <input type="submit" class="btn btn-primary" value="Save">
        </form>
        <?php
        if (!empty($_POST['nimi']) && !empty($_POST['aadress'])) {
            $nimi = $_POST['nimi'];
            $aadress = $_POST['aadress'];

            $updateLocation = "UPDATE asukohad SET nimi='$nimi', asukoht='$aadress' WHERE id = '$selectedLocationId'";
            if ($yhendus->query($updateLocation) === TRUE) {
                echo "<div class='alert alert-success mt-3'>Location updated successfully</div>";
                header("Location: admin.php");
                exit();
            } else {
                echo "<div class='alert alert-danger mt-3'>Error updating location: " . $yhendus->error . "</div>";
            }
        }
        ?>
        <?php $yhendus->close(); ?>   
    </div>
</body>
</html>
