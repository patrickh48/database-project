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

// Query: Rebounds per position
$query = '
    SELECT p.Position, MAX(s.Rebounds) AS Rebounds
    FROM PLAYER p
    JOIN STATS s ON p.PlayerID = s.PlayerID
    GROUP BY p.Position
    ORDER BY Rebounds DESC
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
    <title>Rebounds per Position</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Rebounds per Position</h1>
        <table>
            <tr>
                <th>Position</th>
                <th>Rebounds</th>
            </tr>
            <?php foreach ($all_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Position']) ?></td>
                    <td><?= htmlspecialchars($row['Rebounds']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
