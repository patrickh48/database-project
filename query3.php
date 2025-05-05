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

// Query: All players grouped by college
$query = '
    SELECT DISTINCT 
        p.TeamName AS Team, 
        p.PlayerFName AS p1f, 
        p.PlayerLName AS p1l, 
        p.College
    FROM PLAYER p
    JOIN PLAYER p2 ON p.College = p2.College AND p.PlayerID != p2.PlayerID
    WHERE p.College != "nan"
    ORDER BY College, Team
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
    <title>Players by College</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>All Players Grouped by College</h1>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>College</th>
                <th>Team</th>
            </tr>
            <?php foreach ($all_rows as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player['p1f']) ?></td>
                    <td><?= htmlspecialchars($player['p1l']) ?></td>
                    <td><?= htmlspecialchars($player['College']) ?></td>
                    <td><?= htmlspecialchars($player['Team']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
