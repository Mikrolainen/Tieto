<?php
include ("config.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
  header('Location: login.php');
  exit();
}

if(isset($_GET['location'])) {
    $valitudkohtid = $_GET['location'];
    $valitudkohtparing = "SELECT nimi FROM asukohad WHERE id = '$valitudkohtid'";
    $valitud_koht = $yhendus -> query($valitudkohtparing);
    $row = mysqli_fetch_assoc($valitud_koht);

    $sqlKustutaAsutus = "DELETE FROM asukohad WHERE id = '$valitudkohtid'";
    if ($yhendus -> query($sqlKustutaAsutus) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        echo "viga kutsutsatmisel " . $yhendus -> error;
    }
} else {
    header("Location: admin.php");
}

$yhendus->close(); 
?>
