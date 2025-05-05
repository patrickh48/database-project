<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

// Check connection
if (!$db_conn) {
    die("<h2>Connection failed:</h2><p>" . mysqli_connect_error() . "</p>");
}

// Query: All players on the Lakers
$query = '
    SELECT p.PlayerFName, p.PlayerLName, p.TeamName, p.Pic
    FROM PLAYER p
    WHERE TeamName = "Lakers"
';

$result = mysqli_query($db_conn, $query);

if ($result) {
    $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "<h2>We are unable to process this request right now.</h2>";
    echo "<h3>Error: " . mysqli_error($db_conn) . "</h3>";
    exit;
}

mysqli_close($db_conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lakers Players</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>All Players on the Lakers</h1>
        <aside>
            <?php foreach ($all_rows as $player): ?>
                <figure>
                    <img src="<?= htmlspecialchars($player['Pic']) ?>" alt="Pic of <?= htmlspecialchars($player['PlayerFName']) ?>">
                    <figcaption>
                        <?= htmlspecialchars($player['PlayerFName']) . ' ' . htmlspecialchars($player['PlayerLName']) ?> - <?= htmlspecialchars($player['TeamName']) ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </aside>
    </main>
</body>
</html>
