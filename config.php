<?php
$kasutaja = "Mirko";
$dbserver = "localhost";
$andmebaas ="tieto";
$passwd = "MVPkodara202";

$yhendus = mysqli_connect ($dbserver, $kasutaja, $passwd, $andmebaas);

if (!$yhendus) {
    die("Viga: " . $yhendus->connect_error);
}
?>