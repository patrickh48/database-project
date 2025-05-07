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

// Fetch all players for dropdown
$player_query = 'SELECT PlayerID, PlayerFName, PlayerLName FROM PLAYER';
$player_result = mysqli_query($db_conn, $player_query);
$player_options = [];

if ($player_result) {
    $player_options = mysqli_fetch_all($player_result, MYSQLI_ASSOC);
}

// Initialize player data
$all_rows1 = [];
$all_rows3 = [];

if (isset($_GET['submit']) && !empty($_GET['team']) && !empty($_GET['player'])) {
    $team = mysqli_real_escape_string($db_conn, $_GET['team']);
    $player_id = (int) $_GET['player'];

    // Pre-trade info
    $query1 = "SELECT PlayerID, PlayerFName, PlayerLName, TeamName FROM PLAYER WHERE PlayerID = $player_id";
    $result1 = mysqli_query($db_conn, $query1);
    if ($result1) {
        $all_rows1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);
    }

    // Trade execution
    $query2 = "CALL playerTrade($player_id, '$team')";
    mysqli_query($db_conn, $query2);

    // Post-trade info
    $query3 = "SELECT PlayerID, PlayerFName, PlayerLName, TeamName FROM PLAYER WHERE PlayerID = $player_id";
    $result3 = mysqli_query($db_conn, $query3);
    if ($result3) {
        $all_rows3 = mysqli_fetch_all($result3, MYSQLI_ASSOC);
    }
}

mysqli_close($db_conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Player Trade</title>
    <link href="nba/style.css" rel="stylesheet">
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
            margin: 2em auto;
        }
        th, td {
            border: 1px solid #333;
            padding: 0.75em;
            text-align: left; /* LEFT aligned */
        }
        form {
            text-align: center;
            margin-top: 2em;
        }
    </style>
</head>
<body>
    <main>
        <h1 style="text-align:center;">Trade a Player</h1>

        <form method="GET">
            <label for="player">Select Player:</label>
            <select name="player" id="player" required>
                <?php foreach ($player_options as $player): ?>
                    <option value="<?= $player['PlayerID'] ?>"
                        <?= isset($_GET['player']) && $_GET['player'] == $player['PlayerID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($player['PlayerFName'] . ' ' . $player['PlayerLName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="team">New Team:</label>
            <input type="text" name="team" id="team" required>

            <button type="submit" name="submit">Trade Player</button>
        </form>

        <?php if (!empty($all_rows1)): ?>
            <h2 style="text-align:center;">Before Trade</h2>
            <table>
                <tr>
                    <th>Player ID</th>
                    <th>Name</th>
                    <th>Team</th>
                </tr>
                <?php foreach ($all_rows1 as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['PlayerID']) ?></td>
                        <td><?= htmlspecialchars($row['PlayerFName'] . ' ' . $row['PlayerLName']) ?></td>
                        <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (!empty($all_rows3)): ?>
            <h2 style="text-align:center;">After Trade</h2>
            <table>
                <tr>
                    <th>Player ID</th>
                    <th>Name</th>
                    <th>Team</th>
                </tr>
                <?php foreach ($all_rows3 as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['PlayerID']) ?></td>
                        <td><?= htmlspecialchars($row['PlayerFName'] . ' ' . $row['PlayerLName']) ?></td>
                        <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
