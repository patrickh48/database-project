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

// Initial data: Player before trade
$query1 = 'SELECT p.PlayerID, p.TeamName FROM PLAYER p WHERE p.PlayerID = 1';
$result1 = mysqli_query($db_conn, $query1);

if ($result1) {
    $all_rows1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);
} else {
    echo "<h2>Unable to fetch player data.</h2>";
    exit;
}

// Initialize post-trade data array
$all_rows3 = [];

if (isset($_GET['submit']) && !empty($_GET['team'])) {
    $team = mysqli_real_escape_string($db_conn, $_GET['team']);

    $query2 = "CALL playerTrade(1, '$team')";
    $query3 = 'SELECT p.PlayerID, p.TeamName FROM PLAYER p WHERE p.PlayerID = 1';

    $result2 = mysqli_query($db_conn, $query2);
    $result3 = mysqli_query($db_conn, $query3);

    if ($result3) {
        $all_rows3 = mysqli_fetch_all($result3, MYSQLI_ASSOC);
    } else {
        echo "<h2>Error after trade attempt.</h2>";
        exit;
    }
}

mysqli_close($db_conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Player Trade</title>
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
        h1, h2 {
            text-align: center;
            color: #222;
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        input[type="text"] {
            padding: 8px;
            width: 200px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 16px;
            margin-left: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Trade Player (ID: 1)</h1>
        <form method="get">
            <label for="team">New Team Name:</label>
            <input type="text" name="team" id="team" required>
            <button type="submit" name="submit">Submit Trade</button>
        </form>

        <h2>Before Trade</h2>
        <table>
            <tr><th>Player ID</th><th>Team</th></tr>
            <?php foreach ($all_rows1 as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['PlayerID']) ?></td>
                    <td><?= htmlspecialchars($row['TeamName']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (!empty($all_rows3)): ?>
            <h2>After Trade</h2>
            <table>
                <tr><th>Player ID</th><th>New Team</th></tr>
                <?php foreach ($all_rows3 as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['PlayerID']) ?></td>
                        <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
