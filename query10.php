<?php
require_once '../webdev/mysqli_connect2.php';
$query1 = 'SELECT p.PlayerID, p.TeamName
            From PLAYER p
            WHERE p.PlayerID = 1';
$result1 = mysqli_query($db_conn, $query1);
if($result1)
		$all_rows1= mysqli_fetch_all($result1, MYSQLI_ASSOC); 
	else { 
		echo "<h2>We are unable to process this request right now.</h2>"; 
		echo "<h3>Please try again later.</h3>";
		exit;
	} 
if (isset($_GET['submit'])){
$team = $_GET['team'];

$query2 = 'CALL playerTrade(1, "'.$team.'")';
$query3= 'SELECT p.PlayerID, p.TeamName
From PLAYER p
WHERE p.PlayerID = 1';



$result2 = mysqli_query($db_conn, $query2);
$result3 = mysqli_query($db_conn, $query3);
if($result3)
		$all_rows3= mysqli_fetch_all($result3, MYSQLI_ASSOC); 
	else { 
		echo "<h2>We are unable to process this request right now.</h2>"; 
		echo "<h3>Please try again later.</h3>";
		exit;
	} 
	mysqli_close($db_conn);}
	
	

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NBA</title>
	<meta charset ="utf-8"> 
    <link href='nba/style.css' rel='stylesheet'>
</head>
<main>
    <h1>Player Trade</h1>
	<form action='query10.php' method='get'>
			<label for='team'>New Team Name</label><input type=text name=team><br>
			<input type=submit name=submit>
	</form>
    <table>
		<tr>
			<th>Player ID Before Trade</th>
            <th>Team Name</th>
			
		</tr>	
		<?php foreach ($all_rows1 as $player) {
			echo "<tr>";
			echo "<td>".$player['PlayerID']."</td>";
            echo "<td>".$player['TeamName']."</td>";
			echo "</tr>";
		}
		?>
	</table>
    <table>
		<tr>
			<th>Player ID After Trade</th>
            <th>Team Name</th>		
		</tr><br>	
		<?php foreach ($all_rows3 as $player) {
			echo "<tr>";
			echo "<td>".$player['PlayerID']."</td>";
            echo "<td>".$player['TeamName']."</td>";
			echo "</tr>";
		}
		?>

		
	</table>
</main>
</html>