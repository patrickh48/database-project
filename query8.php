<?php
$db_conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

$query = 'SELECT concatNames(p.PLayerFName,p.PlayerLName) as Name, p.Height, s.ThreePointersMade
FROM PLAYER p 
JOIN STATS s on p.PlayerID = s.PlayerID
WHERE s.ThreePointersMade != 0
ORDER BY s.ThreePointersMade DESC
LIMIT 5';
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
    <h1>Height vs ThreePointersMade</h1>
    <table>
		<tr>
			<th>Name</th>
            <th>Height</th>
            <th>Avg Three's</th>
			
		</tr>	
		<?php foreach ($all_rows as $player) {
			echo "<tr>";
			echo "<td>".$player['Name']."</td>";
            echo "<td>".$player['Height']."</td>";
            echo "<td>".$player['ThreePointersMade']."</td>";
			echo "</tr>";
		}
		?>
	</table>
</main>
</html>