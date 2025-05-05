<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

$query = 'SELECT p.PlayerFName, p.PlayerLName, p.TeamName,p.Pic
FROM PLAYER p
WHERE College = "nan"';
$result = mysqli_query($db_conn, $query);
if($result)
		$all_rows= mysqli_fetch_all($result, MYSQLI_ASSOC); 
	else { 
		echo "<h2>We are unable to process this request right now.</h2>"; 
		echo "<h3>Please try again later.</h3>";
		exit;
	} 
	mysqli_close($db_conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NBA</title>
	<meta charset ="utf-8"> 
    <link href='nba/style.css' rel='stylesheet'>
</head>
<main>
    <h1>All Players who did not go to College</h1>
    <aside>
    <?php 
    foreach ($all_rows as $player) {
        echo '<figure>
            <img src="'.($player['Pic']).'" alt="Pic of '.($player['PlayerFName']).'">
            <figcaption>'
               .($player['PlayerFName']).' '.($player['PlayerLName']).' - '.($player['TeamName']).'
            </figcaption>
            </figure>';}?>
    </aside>
</main>
</html>