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

// Query: Top 5 colleges by total points
$query = '
    SELECT College, SUM(Points) AS Points
    FROM (
        SELECT p.College, s.Points
        FROM PLAYER p
        JOIN STATS s ON p.PlayerID = s.PlayerID
        WHERE s.Points != 0
    ) AS RankedPlayers
    GROUP BY College
    ORDER BY Points DESC
    LIMIT 5
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
    <title>Top Colleges by Points</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Top Colleges by Total Player Points</h1>
        <table>
            <tr>
                <th>College (NAN = No college)</th>
                <th>Points</th>
            </tr>
            <?php foreach ($all_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['College']) ?></td>
                    <td><?= htmlspecialchars($row['Points']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
