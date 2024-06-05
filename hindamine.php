<?php
include("config.php");
session_start();

if (isset($_GET['asukoht'])) {
    $selectedasukohtId = $_GET['asukoht'];
    $stmt = $yhendus->prepare("SELECT nimi FROM asukohad WHERE id = ?");
    $stmt->bind_param("i", $selectedasukohtId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: hindamine.php");
        exit;
    }
} else {
    header("Location: hindamine.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rating</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .star label {
            color: gray;
            font-size: 24px;
            cursor: pointer;
        }

        .star input[type="radio"] {
            display: none;
        }

        .star label:hover,
        .star label:hover ~ label,
        .star input[type="radio"]:checked ~ label {
            color: orange;
        }

        .star input[type="radio"]:checked ~ label:hover,
        .star input[type="radio"]:checked ~ label:hover ~ label {
            color: orange;
        }

        .star input[type="radio"]:checked ~ label:nth-of-type(-n+10) {
            color: orange;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.star label');

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', () => {
                    stars.forEach((s, i) => {
                        s.style.color = i <= index ? 'orange' : 'gray';
                    });
                });

                star.addEventListener('mouseout', () => {
                    const checkedStar = document.querySelector('.star input:checked');
                    stars.forEach((s, i) => {
                        s.style.color = checkedStar && i < checkedStar.value ? 'orange' : 'gray';
                    });
                });

                star.addEventListener('click', () => {
                    const starInput = document.getElementById(`hinnang${index + 1}`);
                    starInput.checked = true;
                    starInput.dispatchEvent(new Event('change'));
                });
            });

            document.querySelectorAll('.star input').forEach(input => {
                input.addEventListener('change', () => {
                    stars.forEach((s, i) => {
                        s.style.color = i < input.value ? 'orange' : 'gray';
                    });
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-4"><?php echo htmlspecialchars($row['nimi']); ?></h1>
        <hr>
        <button class="btn btn-secondary mb-3" onclick="window.location.href='index.php'">Back</button>
        <form method="post">
            <div class="mb-3">
                <label for="nimi" class="form-label">Name:</label>
                <input type="text" name="nimi" id="nimi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment:</label>
                <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
            </div>
            <label>Hinnang:</label>
            <div class="mb-3 star">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <input type="radio" name="hinnang" id="hinnang<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                    <label for="hinnang<?php echo $i; ?>"><i class="fa fa-star"></i></label>
                <?php endfor; ?>
            </div>
            <div class="d-grid gap-2">
                <input type="submit" class="btn btn-primary" value="Rate">
            </div>
        </form>

        <?php
        if (!empty($_POST['nimi']) && !empty($_POST['comment']) && !empty($_POST['hinnang'])) {
            $nimi = $_POST['nimi'];
            $comment = $_POST['comment'];
            $hinnang = $_POST['hinnang'];

            $stmt = $yhendus->prepare("INSERT INTO hinnangud (nimi, comment, hinnang, asukohad_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $nimi, $comment, $hinnang, $selectedasukohtId);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success mt-3" role="alert">Hinne on edukalt lisatud.</div>'; 
                die();
                header("Location: index.php");
            } else {
                echo '<div class="alert alert-danger mt-3" role="alert">Error: ' . $stmt->error . '</div>';
            }
        }
        ?>

        <hr>
        <h2 class="mt-4">Teised Hinded</h2>
        <?php
        $stmt = $yhendus->prepare("SELECT * FROM hinnangud WHERE asukohad_id = ?");
        $stmt->bind_param("i", $selectedasukohtId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row["Nimi"]) . ' - ' . htmlspecialchars($row["hinnang"]) . '/10</h5>';
                echo '<p class="card-text">' . htmlspecialchars($row["comment"]) . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-info" role="alert">No ratings found.</div>';
        }
        ?>

        <?php $yhendus->close(); ?>
    </div>
</body>
</html>
