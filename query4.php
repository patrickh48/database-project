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

$NoCollegePlayers = '
    SELECT p.PlayerFName, p.PlayerLName, p.TeamName, p.Pic
    FROM PLAYER p
    WHERE College = "nan"
';

$result = mysqli_query($db_conn, $NoCollegePlayers);

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
    <title>Players Without College</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
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
        <h1>Players Who Did Not Attend College</h1>
        <aside>
            <?php foreach ($all_rows as $player): ?>
                <figure>
                    <img src="<?= htmlspecialchars($player['Pic']) ?>" alt="Pic of <?= htmlspecialchars($player['PlayerFName']) ?>">
                    <figcaption>
                        <?= htmlspecialchars($player['PlayerFName']) ?> <?= htmlspecialchars($player['PlayerLName']) ?> - 
                        <?= htmlspecialchars($player['TeamName']) ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </aside>
    </div>
</body>
</html>
