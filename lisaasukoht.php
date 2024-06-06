<?php
include ("config.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bananananananan</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"
    >
</head>
<body>
    <div class="container">
        <h1>Lisa asukoht</h1>
        <hr>
        <a href="admin.php" class="btn btn-secondary mb-3">Tagasi</a>
        <form method="post">
            <div class="mb-3">
                <label for="nimi" class="form-label">Name</label>
                <input type="text" name="nimi" id="nimi" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="aadress" class="form-label">Address</label>
                <input type="text" name="aadress" id="aadress" class="form-control" required>
            </div>       

            <input type="submit" class="btn btn-primary" value="Save">
        </form>
        <?php
        if(!empty($_POST['nimi']) && !empty($_POST['aadress'])){
            $nimi = $_POST['nimi'];
            $aadress = $_POST['aadress'];

            $lisahoone = "INSERT INTO asukohad (nimi, asukoht) VALUES ('$nimi', '$aadress')";
            if ($yhendus -> query($lisahoone) === TRUE){
                echo "<div class='alert alert-success mt-3'>Asukoht on edukalt lisatud</div>";
                header("Location: admin.php");
                exit;
            } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . $yhendus->error . "</div>";
            }
        }
        ?>

        <?php $yhendus->close(); ?>   
    </div>
</body>
</html>
