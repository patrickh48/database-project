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
        $all_rows3 = mysqli_fetch_all($result3, MYSQLI_ASSOC
