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

// Fetch players and teams
$player_result = mysqli_query($db_conn, 'SELECT PlayerID, PlayerFName, PlayerLName FROM PLAYER');
$players = $player_result ? mysqli_fetch_all($player_result, MYSQLI_ASSOC) : [];

$team_result = mysqli_query($db_conn, 'SELECT DISTINCT TeamName FROM PLAYER');
$teams = $team_result ? mysqli_fetch_all($team_result, MYSQLI_ASSOC) : [];

$before_trade = [];
$after_trade = [];

if (isset($_GET['submit']) && !empty($_GET['team']) && !empty($_GET['player'])) {
    $team = mysqli_real_escape_string($db_conn, $_GET['team']);
    $player_id = (int) $_GET['player'];

    $query1 = "SELECT PlayerID, PlayerFName, PlayerLName, TeamName FROM PLAYER WHERE PlayerID = $player_id";
    $result1 = mysqli_query($db_conn, $query1);
    $before_trade = $result1 ? mysqli_fetch_all($result1, MYSQLI_ASSOC) : [];

    mysqli_query($db_conn, "CALL playerTrade($player_id, '$team')");

    $query3 = "SELECT PlayerID, PlayerFName, PlayerLName, TeamName FROM PLAYER WHERE PlayerID = $player_id";
    $result3 = mysqli_query($db_conn, $query3);
    $after_trade = $result3 ? mysqli_fetch_all($result3, MYSQLI_ASSOC) : [];
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
        h1, h2 {
            text-align: center;
            color: #222;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
        }
        button {
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
            border-collapse: collapse;
            margin-top: 15px;
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
        <a href="index.html" class="back-button"><strong>← Back to Main</strong></a>
        <h1>Trade a Player</h1>
        <form method="GET">
            <label for="player">Select Player:</label>
            <select name="player" id="player" required>
                <option value="">-- Choose Player --</option>
                <?php foreach ($players as $p): ?>
                    <option value="<?= $p['PlayerID'] ?>" <?= (isset($_GET['player']) && $_GET['player'] == $p['PlayerID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['PlayerFName'] . ' ' . $p['PlayerLName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="team">Select New Team:</label>
            <select name="team" id="team" required>
                <option value="">-- Choose Team --</option>
                <?php foreach ($teams as $t): ?>
                    <option value="<?= htmlspecialchars($t['TeamName']) ?>" <?= (isset($_GET['team']) && $_GET['team'] == $t['TeamName']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['TeamName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="submit">Trade</button>
        </form>

        <?php if (!empty($before_trade)): ?>
            <h2>Before Trade</h2>
            <table>
                <tr>
                    <th>Player ID</th>
                    <th>Name</th>
                    <th>Team</th>
                </tr>
                <?php foreach ($before_trade as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['PlayerID']) ?></td>
                        <td><?= htmlspecialchars($row['PlayerFName'] . ' ' . $row['PlayerLName']) ?></td>
                        <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (!empty($after_trade)): ?>
            <h2>After Trade</h2>
            <table>
                <tr>
                    <th>Player ID</th>
                    <th>Name</th>
                    <th>Team</th>
                </tr>
                <?php foreach ($after_trade as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['PlayerID']) ?></td>
                        <td><?= htmlspecialchars($row['PlayerFName'] . ' ' . $row['PlayerLName']) ?></td>
                        <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
