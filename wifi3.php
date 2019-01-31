<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once(__DIR__.'/st_inc/connection.php');
require_once(__DIR__.'/st_inc/functions.php');
echo "01";
date_default_timezone_set("Europe/Bratislava");
if (!empty($_GET['teplota']) && !empty($_GET['adresa'])) {
	ZapisDoDB($_GET['teplota'], $_GET['adresa'], $_GET['verzia'],$conn);
}


function ZapisDoDB($teplota,$adresa,$verzia,$conn) {
  $query = "SELECT COUNT(*) FROM nodes WHERE node_id = '".$adresa."'";
  $result = $conn->query($query);
  $date_time = date('Y-m-d H:i:s');
  $riadkov = mysqli_num_rows($result);
  echo "pocet riadkov :".$riadkov." ".$adresa." ".$date_time." ";
  if (!mysqli_num_rows($result)) {
	  echo "Novy senzor. Adresa :".$adresa." a teplota: ".$teplota."<br />";
	  $query1 = "INSERT INTO nodes (node_id, child_id_1, name, last_seen, sketch_version) VALUES('28-".$adresa."', '0', 'Temperature Sensor', '{$date_time}', '{$verzia}')";
      $result1 = $conn->query($query1);
       if (!$result1) {
       printf("Error: %s\n", mysqli_error($conn));
       exit();
       }
      if ($result1) {
		echo "Senzor bol uspesne pridany do DB";
		} else {
		echo "Senzor nebol pridany do DB (error01a) </p>" . mysqli_error() . "</p>";
		}}
  elseif (mysqli_num_rows($result)) {
	  $query1="SELECT id FROM nodes WHERE node_id = '28-".$adresa."'";
	  $result1 = $conn->query($query1);
	  $row = mysqli_fetch_array($result1);
	  $vysledok = $row['id'];
	  echo "Senzor id: ".$vysledok." uz existuje, updatujem hodnoty";
	  $query2 = "UPDATE nodes SET last_seen=now() WHERE node_id = '28-".$adresa."'";
	  $result2 = $conn->query($query2);
	  $query3 = "INSERT INTO messages_in(node_id, child_id, sub_type, payload, datetime) VALUES('28-".$adresa."', '0', '0', '{$teplota}', '{$date_time}')";
	  $result3 = $conn->query($query3);
	  if ($result3) {
		echo "Hodnoty zapisane do DB";
		} else {
		echo "Hodnoty neboli pridane do DB (error02a) </p>" . mysqli_error() . "</p>";
		}}
  else {
		echo "Blud";}
}


if(isset($conn)) { $conn->close();}
?>
