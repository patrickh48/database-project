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

// Query to use the concat function
$query = '
    SELECT concatNames(p.PlayerFName, p.PlayerLName) AS Name
    FROM PLAYER p
    WHERE p.TeamName = "Hawks"
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
    <title>Concatenated Player Names - Hawks</title>
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Concatenated Player Names (Hawks)</h1>
        <table>
            <tr>
                <th>Name</th>
            </tr>
            <?php foreach ($all_rows as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player['Name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
