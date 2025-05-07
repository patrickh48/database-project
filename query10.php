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
$player_result = mysqli_query($db_conn, 'SELECT PlayerID, PlayerFName, PlayerLName FROM PLAYER');
$players = $player_result ? mysqli_fetch_all($player_result, MYSQLI_ASSOC) : [];

// Fetch all unique teams for dropdown
$team_result = mysqli_query($db_conn, 'SELECT DISTINCT TeamName FROM PLAYER');
$teams = $team_result ? mysqli_fetch_all($team_result, MYSQLI_ASSOC) : [];

// Init rows
$before_trade = [];
$after_trade = [];

if (isset($_GET['submit']) && !empty($_GET['team']) && !empty($_GET['player'])) {
    $team = mysqli_real_escape_string($db_conn, $_GET['team']);
    $player_id = (int) $_GET['player'];

    // Get data before trade
    $query1 = "SELECT PlayerID, PlayerFName, PlayerLName, TeamName FROM PLAYER WHERE PlayerID = $player_id";
    $result1 = mysqli_query($db_conn, $query1);
    $before_trade = $result1 ? mysqli_fetch_all($result1, MYSQLI_ASSOC) : [];

    // Call stored procedure
    mysqli_query($db_conn, "CALL playerTrade($player_id, '$team')");

    // Get data after trade
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
    <link href="nba/style.css" rel="stylesheet">
</head>
<body>
    <main>
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
    </main>
</body>
</html>
