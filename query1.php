<?php
// Database connection settings — replace with your actual RDS credentials
$db_host = 'database-project.c960ywsie3ld.us-east-2.rds.amazonaws.com';
$db_user = 'admin';            // Your RDS master username
$db_pass = 'Doodle.101';    // Your RDS master password
$db_name = 'nba_db';           // The database you imported your SQL into

// Connect to RDS MySQL
$db_conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$db_conn) {
    die("<h2>Connection failed:</h2><p>" . mysqli_connect_error() . "</p>");
}

// SQL query to get top players by team
$TopPlayers = '
    SELECT p.PlayerFName, p.PlayerLName, p.TeamName, p.Pic, s.Points
    FROM PLAYER p
    INNER JOIN STATS s ON p.PlayerID = s.PlayerID
    INNER JOIN (
        SELECT TeamName, MAX(Points) AS MaxPoints
        FROM STATS
        JOIN PLAYER ON STATS.PlayerID = PLAYER.PlayerID
        GROUP BY TeamName
    ) AS max_points 
    ON p.TeamName = max_points.TeamName AND s.Points = max_points.MaxPoints
    ORDER BY p.TeamName, s.Points DESC
';

// Run query
$result = mysqli_query($db_conn, $TopPlayers);

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
    <title>Top NBA Players by Team</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Players with the highest average points per team</h1>
        <aside>
            <?php foreach ($all_rows as $player): ?>
                <figure>
                    <img src="<?= htmlspecialchars($player['Pic']) ?>" alt="Pic of <?= htmlspecialchars($player['PlayerFName']) ?>">
                    <figcaption>
                        <?= htmlspecialchars($player['PlayerFName']) . ' ' . htmlspecialchars($player['PlayerLName']) ?> - <?= htmlspecialchars($player['TeamName']) ?><br>
                        has an average score of <?= htmlspecialchars($player['Points']) ?> points
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </aside>
    </main>
</body>
</html>
