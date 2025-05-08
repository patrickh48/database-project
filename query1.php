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
        aside {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        figure {
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            text-align: center;
        }
        img {
            width: 100%;
            height: auto;
            display: block;
        }
        figcaption {
            padding: 15px;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="back-button"><strong>← Back to Main</strong></a>
        <h1>Players with the Highest Average Points per Team</h1>
        <aside>
            <?php foreach ($all_rows as $player): ?>
                <figure>
                    <img src="<?= htmlspecialchars($player['Pic']) ?>" alt="Pic of <?= htmlspecialchars($player['PlayerFName']) ?>">
                    <figcaption>
                        <?= htmlspecialchars($player['PlayerFName']) ?> <?= htmlspecialchars($player['PlayerLName']) ?> - 
                        <?= htmlspecialchars($player['TeamName']) ?><br>
                        <strong><?= htmlspecialchars($player['Points']) ?> points</strong>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </aside>
    </div>
</body>
</html>
