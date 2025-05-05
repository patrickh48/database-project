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

// Query: Total points per team
$query = '
    SELECT p.TeamName, SUM(s.Points) AS TotalPoints
    FROM PLAYER p
    JOIN STATS s ON p.PlayerID = s.PlayerID
    GROUP BY p.TeamName
    ORDER BY TotalPoints DESC
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
    <title>Total Team Points</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Total Points by Team</h1>
        <table>
            <tr>
                <th>Team</th>
                <th>Total Points</th>
            </tr>
            <?php foreach ($all_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    <td><?= htmlspecialchars($row['TotalPoints']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
