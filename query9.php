<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

if (!$db_conn) {
    die("<h2>Connection failed:</h2><p>" . mysqli_connect_error() . "</p>");
}

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
    <title>Hawks Player Names</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .container {
            background: white;
            margin-top: 50px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #222;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f0f0f0;
            color: #333;
        }
        tr:hover {
            background-color: #f7f7f7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hawks Player Names</h1>
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
    </div>
</body>
</html>
