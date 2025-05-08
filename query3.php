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

$PlayersByCollege = '
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

$result = mysqli_query($db_conn, $PlayersByCollege);

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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20230705/pngtree-3d-render-of-a-basketball-ball-in-an-indoor-court-image_3799313.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            margin-top: 50px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            max-width: 800px;
            width: 100%;
        }
        a.back-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        a.back-button:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }
        h1 {
            text-align: center;
            color: #333;
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
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="back-button"><strong>← Back to Main</strong></a>
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
    </div>
</body>
</html>
