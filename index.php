<?php include ("config.php"); session_start(); ?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bananamees</title>
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
        <h1>Bananaman.ru</h1><br>
        
        <a href="admin.php" class="btn btn-primary admin-login">Admin Login</a>

        <form method="get">
            <label for="otsi">Otsi asukohta</label><br>
            <input type="text" name="otsi" id="otsi">
            <input type="submit" class="btn btn-primary my-2" value="otsi">
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nimi</th>
                    <th scope="col">Asukoht</th>
                    <th scope="col">Keskmine Hinne</th>
                    <th scope="col">Hinnatud</th>
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
                        $locationName = $row['nimi'];
                        $locationId = $row['id'];

                        $queryRatingsCount = "SELECT COUNT(hinnang) AS ratings_count FROM hinnangud WHERE asukohad_id = '$locationId'";
                        $ratingsResult = $yhendus->query($queryRatingsCount);
                        $ratingsCount = $ratingsResult->fetch_assoc()['ratings_count'];

                        $queryAverageRating = "SELECT AVG(hinnang) AS average_rating FROM hinnangud WHERE asukohad_id = '$locationId'";
                        $averageRatingResult = $yhendus->query($queryAverageRating);
                        $averageRating = $averageRatingResult->fetch_assoc()['average_rating'];
                        $averageRatingRounded = round($averageRating, 1);

                        $updateQuery = "UPDATE asukohad SET kesk_hinne = '$averageRatingRounded', hinnete_arv = '$ratingsCount' WHERE id = '$locationId'";
                        $yhendus->query($updateQuery);
                        ?>
                        <tr>
                            <td><a href="hindamine.php?asukoht=<?php echo urlencode($locationId); ?>"><?php echo $row["nimi"]; ?></a></td>
                            <td><?php echo $row["asukoht"]; ?></td>
                            <td><?php echo $averageRatingRounded; ?></td>
                            <td><?php echo $ratingsCount; ?></td>
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
    </div>
</body>
</html>
