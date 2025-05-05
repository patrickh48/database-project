<?php
require_once '../webdev/mysqli_connect2.php';
$query = 'SELECT p.Position, MAX(s.Rebounds) as Rebounds
FROM PLAYER p
JOIN STATS s ON p.PlayerID = s.PlayerID
GROUP BY p.Position
ORDER BY Rebounds DESC;';
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
    <h1>Rebounds per position</h1>
    <table>
		<tr>
			<th>Position</th>
			<th>Rebounds</th>
			
		</tr>	
		<?php foreach ($all_rows as $player) {
			echo "<tr>";
			echo "<td>".$player['Position']."</td>";
			echo "<td>".$player['Rebounds']."</td>";
			echo "</tr>";
		}
		?>
	</table>
</main>
</html>