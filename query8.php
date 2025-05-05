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

// Query: Height vs Three Pointers Made
$query = '
    SELECT concatNames(p.PlayerFName, p.PlayerLName) AS Name, p.Height, s.ThreePointersMade
    FROM PLAYER p
    JOIN STATS s ON p.PlayerID = s.PlayerID
    WHERE s.ThreePointersMade != 0
    ORDER BY s.ThreePointersMade DESC
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
    <title>Height vs Three Pointers Made</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Height vs Three Pointers Made</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Height</th>
                <th>Avg Three's</th>
            </tr>
            <?php foreach ($all_rows as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player['Name']) ?></td>
                    <td><?= htmlspecialchars($player['Height']) ?></td>
                    <td><?= htmlspecialchars($player['ThreePointersMade']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
