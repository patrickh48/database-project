<?php
require_once '../webdev/mysqli_connect2.php';
$query = 'SELECT concatNames(p.PLayerFName,p.PlayerLName) as Name
FROM PLAYER p
WHERE p.TeamName = "Hawks"';
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
    <h1>Function to concat Names</h1>
    <table>
		<tr>
			<th>Name</th>
			
		</tr>	
		<?php foreach ($all_rows as $player) {
			echo "<tr>";
			echo "<td>".$player['Name']."</td>";
			echo "</tr>";
		}
		?>
	</table>
</main>
</html>