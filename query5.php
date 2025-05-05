<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

$query = 'SELECT p.TeamName,SUM(s.Points) as TotalPoints
FROM PLAYER p
JOIN STATS s ON p.PlayerID = s.PlayerID
GROUP BY p.TeamName
ORDER BY TotalPoints DESC;';
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
    <h1>Total Points of each Team</h1>
    <table>
		<tr>
			<th>Team</th>
			<th>Total Points</th>
		</tr>	
		<?php foreach ($all_rows as $player) {
			echo "<tr>";
			echo "<td>".$player['TeamName']."</td>";
			echo "<td>".$player['TotalPoints']."</td>";
			echo "</tr>";
		}
		?>
	</table>
</main>
</html>