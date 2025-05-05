<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

$query = 'SELECT DISTINCT p.TeamName as Team,p.PlayerFName as p1f,p.PlayerLName as p1l, p.College
FROM PLAYER p
JOIN PLAYER p2 ON p.College = p2.College and p.PlayerID != p2.PlayerID
WHERE p.College != "nan" 
ORDER BY College, Team';
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
    <h1>All Players grouped by college</h1>
    <table>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
		
            <th>College</th>
			<th>Team</th>
		</tr>	
		<?php foreach ($all_rows as $player) {
			echo "<tr>";
			echo "<td>".$player['p1f']."</td>";
			echo "<td>".$player['p1l']."</td>";
		
            echo "<td>".$player['College']."</td>";
			echo "<td>".$player['Team']."</td>";
			echo "</tr>";
		}
		?>
	</table>
</main>
</html>