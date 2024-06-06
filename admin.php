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
    <title>Banana</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"
    >
    <style>
        .admin-login {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bananaman.ru</h1>
        <form action="logout.php" method="post" class="btn admin-login">
         <input type="submit" value="Logi välja" name="logout">
        </form>
        <hr>
        
        <a href="index.php" class="btn btn-primary mb-3">Tagasi</a>

        <form method="get">
            <label for="otsi">Otsi asukohta</label><br>
            <input type="text" name="otsi" id="otsi">
            <input type="submit" class="btn btn-primary my-2" value="otsi">
        </form>

        <a href="lisaasukoht.php" class="btn btn-success my-2">LISA ASUKOHT</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nimi</th>
                    <th scope="col">Asukoht</th>
                    <th scope="col">Keskmine Hinne</th>
                    <th scope="col">Hinnatud</th>
                    <th scope="col">Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $itemsPerPage = 10;
                $page = isset($_GET['leht']) ? (int)$_GET['leht'] : 1;
                $offset = ($page - 1) * $itemsPerPage;
                $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'nimi';
                $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';
                $searchTerm = isset($_GET['otsi']) ? $_GET['otsi'] : '';
                $searchQuery = $searchTerm ? "WHERE nimi LIKE '%$searchTerm%'" : '';

                // Kogu elementide arvu päring
                $queryTotalItems = "SELECT COUNT(*) as total FROM asukohad $searchQuery";
                $totalItemsResult = $yhendus->query($queryTotalItems);
                $totalItems = $totalItemsResult->fetch_assoc()['total'];
                $totalPages = ceil($totalItems / $itemsPerPage);

                $queryLocations = "SELECT * FROM asukohad $searchQuery ORDER BY $sortColumn $sortOrder LIMIT $offset, $itemsPerPage";
                $result = $yhendus->query($queryLocations);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['id'];

                        $ratingCountQuery = "SELECT COUNT(*) as rating_count FROM hinnangud WHERE asukohad_id = '$id'";
                        $ratingCountResult = $yhendus->query($ratingCountQuery);
                        $ratingCount = $ratingCountResult->fetch_assoc()['rating_count'];

                        $averageRatingQuery = "SELECT AVG(hinnang) as average_rating FROM hinnangud WHERE asukohad_id = '$id'";
                        $averageRatingResult = $yhendus->query($averageRatingQuery);
                        $averageRating = $averageRatingResult->fetch_assoc()['average_rating'];
                        $roundedAverageRating = round($averageRating, 1);

                        $updateQuery = "UPDATE asukohad SET kesk_hinne = '$roundedAverageRating', hinnete_arv = '$ratingCount' WHERE id = '$id'";
                        $updateResult = $yhendus->query($updateQuery);
                        ?>

                        <tr>
                            <td><?php echo $row["nimi"]; ?></td>
                            <td><?php echo $row["asukoht"]; ?></td>
                            <td><?php echo $roundedAverageRating; ?></td>
                            <td><?php echo $ratingCount; ?></td>
                            <td>
                                <a href="editasukoht.php?location=<?php echo urlencode($id); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href='kustuta.php?location=<?php echo $id; ?>' class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?leht=<?php echo $page - 1; ?>&otsi=<?php echo urlencode($searchTerm); ?>" class="btn btn-primary">&lt; Eelmine lk</a>
            
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?leht=<?php echo $page + 1; ?>&otsi=<?php echo urlencode($searchTerm); ?>" class="btn btn-primary">Järgmine lk &gt;</a>
            <?php endif; ?>
        </div>

        <?php $yhendus->close(); ?>   
    </div>
</body>
</html>
