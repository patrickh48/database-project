<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

$query = 'SELECT 
College,
SUM(Points) as Points
FROM (
SELECT 
    p.College,
    s.Points
FROM 
    PLAYER p
JOIN 
    STATS s ON p.PlayerID = s.PlayerID
WHERE s.Points != 0
) as RankedPlayers
GROUP BY College 
ORDER By Points DESC
Limit 5;
';
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
    <h1>Top college players by Points</h1>
    <table>
		<tr>
			<th>College (NAN = No college)</th>
			<th>Points</th>
			
		</tr>	
		<?php foreach ($all_rows as $player) {
			echo "<tr>";
			echo "<td>".$player['College']."</td>";
			echo "<td>".$player['Points']."</td>";
			echo "</tr>";
		}
		?>
	</table>
</main>
</html>