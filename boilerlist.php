<?php 
/*
   _____    _   _    _                             
  |  __ \  (_) | |  | |                            
  | |__) |  _  | |__| |   ___    _ __ ___     ___  
  |  ___/  | | |  __  |  / _ \  | |_  \_ \   / _ \ 
  | |      | | | |  | | | (_) | | | | | | | |  __/ 
  |_|      |_| |_|  |_|  \___/  |_| |_| |_|  \___| 

     S M A R T   H E A T I N G   C O N T R O L 

*************************************************************************"
* PiHome is Raspberry Pi based Central Heating Control systems. It runs *"
* from web interface and it comes with ABSOLUTELY NO WARRANTY, to the   *"
* extent permitted by applicable law. I take no responsibility for any  *"
* loss or damage to you or your property.                               *"
* DO NOT MAKE ANY CHANGES TO YOUR HEATING SYSTEM UNTILL UNLESS YOU KNOW *"
* WHAT YOU ARE DOING                                                    *"
* Language support by Juraj Halcak :: juraj@halcak.sk :: 19.01.11       *"
*************************************************************************"
*/

//boiler usage time 
echo "<h4>".$LANG['pihome_saving']."</h4></p>".$LANG['pihome_saving_about']." </p>";
$query="SELECT date(start_datetime) AS date, 
SUM(TIMESTAMPDIFF(MINUTE, start_datetime, expected_end_date_time)) AS total_minuts,
SUM(TIMESTAMPDIFF(MINUTE, start_datetime, stop_datetime)) AS on_minuts, 
(SUM(TIMESTAMPDIFF(MINUTE, start_datetime, expected_end_date_time)) - SUM(TIMESTAMPDIFF(MINUTE, start_datetime, stop_datetime))) AS save_minuts
FROM boiler_logs WHERE start_datetime >= NOW() - INTERVAL 30 DAY GROUP BY date(start_datetime) DESC";

$result = $conn->query($query);
echo "<table id=\"example\" class=\"table table-bordered table-hover dt-responsive\" width=\"100%\">";
echo "<thead><tr><th>".$LANG['date']."</th><th>".$LANG['total_min']."</th><th class=\"all\">".$LANG['on_min']."</th><th>".$LANG['save_min']."</th><th> <i class=\"glyphicon glyphicon-leaf green\"></th></tr></thead><tbody>";
while ($row = mysqli_fetch_assoc($result)) {
	echo "
	<tr>
	<td class=\"all\">" . $row['date'] . "</td>
	<td class=\"all\">" . $row['total_minuts'] . "</td>
	<td class=\"all\">" . $row['on_minuts'] . "</td>
	<td class=\"all\">" . $row['save_minuts'] . "</td>
	<td class=\"all\">".number_format(($row['save_minuts']/$row['total_minuts'])*100,0)."%</td>
	</tr>";
}
 echo "</tbody></table>";?>
