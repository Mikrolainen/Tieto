<?php include("config.php"); session_start();

    if(isset($_GET['asukoht'])) {
        $valitudasukohtid = $_GET['asukoht'];
        $valitud_asukohtidparing = "SELECT nimi FROM asukohad WHERE id = '$valitudasukohtid'";
        $valitud_asukoht = $yhendus->query($valitud_asukohtidparing);
        $row = mysqli_fetch_assoc($valitud_asukoht);

    } else {
        header("Location: /KT/");
    }

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lisahinnaing</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .star [type="radio"]{
	        appearance: none;
        }

        .star label:has(~ :checked){
	            color: orange;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $row['nimi']; ?>i</h1>
        <hr>
        <p align="right">
            <form method="post">
                
                <label for="nimi">Name: </label>
                <p class="text-center"><input type="text" name="nimi" id="nimi" required></p><br>

                <label for="kommentaar">Comment: </label>
                <p class="text-center"><textarea name="kommentaar" id="kommentaar" rows="10" required></textarea></p><br>

                <p class="star">
                    <label for="hinnang1"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang1" value="1" required>
                    <label for="hinnang2"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang2" value="2">
                    <label for="hinnang3"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang3" value="3">
                    <label for="hinnang4"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang4" value="4">
                    <label for="hinnang5"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang5" value="5">
                    <label for="hinnang6"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang6" value="6">
                    <label for="hinnang7"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang7" value="7">
                    <label for="hinnang8"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang8" value="8">
                    <label for="hinnang9"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang9" value="9">
                    <label for="hinnang10"><i class="fa fa-star"></i></label>
                    <input type="radio" name="hinnang" id="hinnang10" value="10">
                </p>
            </p>
            <input type="submit" class="btn btn-primary my-2" value="HINDA">
        </form>
        

        <?php
        if(!empty($_POST['nimi']) && !empty($_POST['kommentaar']) && !empty($_POST['hinnang'])){
            $nimi = $_POST['nimi'];
            $kommentaar = $_POST['kommentaar'];
            $hinnang = $_POST['hinnang'];

            // pane hginnangutesse see kommentaar
            $lisahinnang = "INSERT INTO hinnangud (nimi, kommentaar, hinnang, id) VALUES ('$nimi', '$kommentaar', '$hinnang', '$valitudasukohtid')";

            if ($yhendus -> query($lisahinnang) === TRUE){
                echo "hinnagn listatud.";
                header("Location: /KT/");
                exit;
            } else {
                echo "valee bzz  " . $lisahinnang . "<br>" . $yhendus->error;
            }
        }
        ?>

        <hr>
        <h2>Teiste hinnangud</h2>
        <?php
        $hinnangud_paring2 = "SELECT * FROM hinnangud WHERE id = '$valitudasukohaid'";
        $result_hinnangud = $yhendus -> query($hinnangud_paring2);

        if ($result_hinnangud -> num_rows > 0) {
            while($row = $result_hinnangud -> fetch_assoc()) {
                echo "<b>" . $row["nimi"] . " " . $row["hinnang"] . "/10" . "</b><br>" . $row["kommentaar"] . "<br><br> ";
            }
        } else {
            echo "Hinnanguid ei leitud.";
        }
        ?>

        <?php $yhendus->close(); ?>   
    </div>
    <script>
        // muuda radio nupp varv ja see uhh taheks tee
    function changeColor(radio) {
        var stars = document.querySelectorAll('.star');
        stars.forEach(function(star) {

            star.classList.remove('checked');
        });
        radio.classList.add('checked');
    }
    </script>
    </body>
</html>
