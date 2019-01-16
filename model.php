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
* Language support by Juraj Halcak :: juraj@halcak.sk :: 19.01.10       *"
*************************************************************************"
*/
//PiConnect Settings
echo "
<div class=\"modal fade\" id=\"piconnect\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['piconn_set']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['manage_piconn']."</p>";
$query = "SELECT * FROM piconnect";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	if ($row['status'] ==1){
		echo " <a target=\"_blank\" href=\"http://www.pihome.eu/piconnect/\" class=\"list-group-item\">
		<i class=\"fa fa-plug green\"></i> API: ".$row['api_key']."
		<span class=\"pull-right \"><em>&nbsp;&nbsp;<i class=\"fa fa-thumbs-up\"></i> &nbsp;&nbsp;".$LANG['active']." </em></span></a>";
	}else {
		echo " <a target=\"_blank\" href=\"http://www.pihome.eu/piconnect/\" class=\"list-group-item\">
		<i class=\"fa fa-plug \"></i> API: ".$row['api_key']."
		<span class=\"pull-right \"><em>&nbsp;&nbsp;<i class=\"fa fa-frown-o\"></i> &nbsp;&nbsp;".$LANG['disabled']." </em></span></a>";
	}
}
echo "<br /><br />
<h5 class=\"strong red\" >".$LANG['piconn_simplify']."</h5>";

echo "</div></div>
            <div class=\"modal-footer\">
				<button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
             </div>
        </div>
    </div>
</div>";


//query to frost protection temperature 
$query = "SELECT * FROM frost_protection LIMIT 1 ";
$result = $conn->query($query);
$frosttemp = mysqli_fetch_array($result);
$frost = $frosttemp['temperature'];
echo "
<div class=\"modal fade\" id=\"add_frost\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['frost_protect']."</h5>
            </div>
            <div class=\"modal-body\">
                <p class=\"text-muted\"> ".$LANG['system_protect']."</p>
				<form data-toggle=\"validator\" role=\"form\" method=\"post\" action=\"settings.php\" id=\"form-join\">
				<div class=\"form-group\" class=\"control-label\"><label>".$LANG['temperature']."</label>
				<select class=\"form-control input-sm\" type=\"number\" id=\"frost_temp\" name=\"frost_temp\" placeholder=\"".$LANG['temp_protect']."\" >
				<option selected>".$frost."</option>
				<option>-1</option>
				<option>0</option>
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
				<option>9</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
				</select>
                <div class=\"help-block with-errors\"></div></div>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-primary btn-sm\" data-dismiss=\"modal\">".$LANG['cancel']."</button>
                <input type=\"button\" name=\"submit\" value=\"".$LANG['save']."\" class=\"btn btn-default login btn-sm\" onclick=\"update_frost()\">
            </div>
        </div>
    </div>
</div>";

//Boiler Safety settings
echo "
<div class=\"modal fade\" id=\"boiler_safety_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['boiler_settings']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['boiler_safety']." <small><i class=\"ionicons ion-ios-timer blue\"></i> ".$LANG['hysteresis_what']."</p>";
$query = "SELECT * FROM boiler_view";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	echo " <a href=\"#\" class=\"list-group-item\">
	<i class=\"ionicons ion-flame fa-1x red\"></i> ".$row['name']." - Node: ".$row['node_id']." Child: ".$row['node_child_id']."
	<span class=\"pull-right \"><em>&nbsp;&nbsp;<i class=\"ionicons ion-ios-timer blue\"></i> ".$row['hysteresis_time']. " </em></span>
	</a>";
}
echo "</div></div>
            <div class=\"modal-footer\">
				<button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
             </div>
        </div>
    </div>
</div>";

//boost model
echo "
<div class=\"modal fade\" id=\"boost_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['boost_temperature']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['boost_setting']."</p>";
$query = "SELECT * FROM boost_view ORDER BY index_id ASC";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	echo "<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-rocket fa-fw blueinfo\"></i> ".$row['name']."
	<span class=\"pull-right text-muted small\"><em>".$row['minute']." ".$LANG['minute'].", ".$row['temperature']."&deg;  </em></span></a>"; 
}
echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>				
            </div>
        </div>
    </div>
</div>";

//override model
echo "
<div class=\"modal fade\" id=\"override_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['override_setup']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['override_setting']." </p>";
$query = "SELECT * FROM override_view ORDER BY index_id ASC";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	echo "<a href=\"#\" class=\"list-group-item\">
	<i class=\"fa fa-refresh fa-1x blue\"></i> ".$row['name']." 
    <span class=\"pull-right text-muted small\"><em>".$row['temperature']."&deg; </em></span></a>";
}
echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//Sensor location model
echo "
<div class=\"modal fade\" id=\"temperature_sensor\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['temperature_sensor']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['sensor_abaout']." </p>";
$query = "SELECT * FROM nodes WHERE name = 'Temperature Sensor' ORDER BY node_id ASC";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	$batquery = "SELECT * FROM nodes_battery WHERE node_id = {$row['node_id']} ORDER BY id DESC LIMIT 1;";
	$batresults = $conn->query($batquery);
	$brow = mysqli_fetch_array($batresults);
	if ($row['ms_version'] > 0){
		echo "<a href=\"#\" class=\"list-group-item\">
		<i class=\"ionicons ion-thermometer red\"></i> ".$row['node_id']." - <i class=\"fa fa-battery-full\"></i> ".round($brow ['bat_level'],0)."% - ".$brow ['bat_voltage']."V
		<span class=\"pull-right text-muted small\"><em>".$row['last_seen']."</em></span></a>"; 	
	}else {
		echo "<a href=\"#\" class=\"list-group-item\">
		<i class=\"ionicons ion-thermometer red\"></i> ".$row['node_id']."<span class=\"pull-right text-muted small\"><em>".$row['last_seen']."</em></span></a>"; 
	}
}
echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//Zone model	
echo "
<div class=\"modal fade\" id=\"zone_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['zone_settings']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\">
".$LANG['hysteresis_what']." <br />
".$LANG['max_time']." <br />
".$LANG['max_zone_temp']." </p>";

$query = "SELECT * FROM zone_view ORDER BY index_id ASC";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	if ($row['gpio_pin'] == 0){
		echo "<div class=\"list-group-item\">
		<i class=\"glyphicon glyphicon-th-large orange\"></i> ".$row['name']."
		<span class=\"pull-right \"><em>&nbsp;&nbsp;<small> ".$LANG['max']." ".$row['max_c']."&deg; </em> - ".$LANG['sensor'].": ".$row['sensors_id']." - ".$LANG['ctr'].": ".$row['controler_id']."-".$row['controler_child_id']."</small></span> 
		<br /><span class=\"pull-right \"><small>
		<a href=\"zone_edit.php?id=".$row['id']."\" class=\"btn btn-default btn-xs login\"><span class=\"ionicons ion-edit\"></span></a>&nbsp;&nbsp;
		<a href=\"javascript:delete_zone(".$row['id'].");\"><button class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-trash\"></span></button></a>
		</small></span>
		<br />
		</div>";
	} else {
		echo "<div class=\"list-group-item\">
		<i class=\"glyphicon glyphicon-th-large orange\"></i> ".$row['name']."
		<span class=\"pull-right \"><em>&nbsp;&nbsp;<small> ".$LANG['max']." ".$row['max_c']."&deg; </em> - ".$LANG['sensor'].": ".$row['sensors_id']." - ".$LANG['gpio'].": ".$row['gpio_pin']."</small></span>
		<br /><span class=\"pull-right \"><small>
		<a href=\"zone_edit.php?id=".$row['id']."\" class=\"btn btn-default btn-xs login\"><span class=\"ionicons ion-edit\"></span></a>&nbsp;&nbsp;
		<a href=\"javascript:delete_zone(".$row['id'].");\"><button class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-trash\"></span></button></a>
		</small></span>
		<br />		
		</div>";
	}
}
echo "
</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//sensor gateway model
echo "
<div class=\"modal fade\" id=\"sensor_gateway\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['mysensor_gw']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['mysensors']." ".ucwords(gw_logs($conn, 'type'))." ".$LANG['gw_status']."</p>";
echo "	<div class=\"list-group\">";
echo "
<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-heartbeat red\"></i> ".$LANG['gw_loc'].": <span class=\"pull-right text-muted small\"><em> ".gw_logs($conn, 'location')."</em></span></a>
<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-heartbeat red\"></i> ".$LANG['gw_pid'].": <span class=\"pull-right text-muted small\"><em> ".gw_logs($conn, 'pid')."</em></span></a>
<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-heartbeat red\"></i> ".$LANG['gw_since'].": <span class=\"pull-right text-muted small\"><em>".gw_logs($conn, 'pid_start_time')."</em></span></a>";

$query = "SELECT * FROM gateway_logs WHERE pid_datetime >= NOW() - INTERVAL 5 MINUTE;";
$result = $conn->query($query);
if (mysqli_num_rows($result) != 0){
	$gw_restarted = mysqli_num_rows($result);
} else {
	$gw_restarted = "0";
}
echo "<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-heartbeat red\"></i> ".$LANG['gw_rst5'].": <span class=\"pull-right text-muted small\"><em>".$gw_restarted."</em></span></a>";
echo "</div></div>
            <div class=\"modal-footer\">
               
				<a href=\"javascript:resetgw(".gw_logs($conn, 'pid').");\" class=\"btn btn-default login btn-sm btn-edit\">".$LANG['gw_rst']."</a>
				<a href=\"javascript:find_gw();\" class=\"btn btn-default login btn-sm btn-edit\">".$LANG['gw_search']."</a>
				<button type=\"button\" class=\"btn btn-primary btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";


//cronetab model	
echo "
<div class=\"modal fade\" id=\"cron_jobs\" tabindex=\"-1\" role=\dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['cron']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['cron_list']." </p>";
echo "	<div class=\"list-group\">";
//exec ("crontab -l >/var/www/cronjob.txt"); this didnt work need to investigate 
$file_handle = fopen("/var/www/cronjob.txt", "r");
while (!feof($file_handle)) {
	$line = fgets($file_handle);
	echo "<a href=\"#\" class=\"list-group-item\">
    <i class=\"ionicons ion-ios-timer-outline red\"></i> ".$line."
    <span class=\"pull-right text-muted small\"><em></em></span>
    </a>";
}
fclose($file_handle);
echo " </div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>				
            </div>
        </div>
    </div>
</div>";

//System temperature
echo "
<div class=\"modal fade\" id=\"system_c\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['sys_t']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['sys_t5']." </p>";
$query = "SELECT * FROM messages_in WHERE node_id = 0 ORDER BY datetime DESC LIMIT 5";
$results = $conn->query($query);
echo "	<div class=\"list-group\">";
while ($row = mysqli_fetch_assoc($results)) {
	echo "<a href=\"#\" class=\"list-group-item\">
	<i class=\"fa fa-server fa-1x green\"></i> ".$row['datetime']." 
	<span class=\"pull-right text-muted small\"><em>".$row['payload']."&deg;</em></span>
    </a>"; 
}
echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//OS version model
//$osversion = exec ("cat /etc/os-release");
//$lines=file('/etc/os-release');
$lines=array();
$fp=fopen('/etc/os-release', 'r');
while (!feof($fp)){
    $line=fgets($fp);
    //process line however you like
    $line=trim($line);
    //add to array
    $lines[]=$line;
}
fclose($fp);
echo "
<div class=\"modal fade\" id=\"os_version\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['os_v']."</h5>
            </div>
            <div class=\"modal-body\">
			   <div class=\"list-group\">
				<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-linux\"></i> ".$lines[1]."</a>
				<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-linux\"></i> ".$lines[3]."</a>
				</div>				
           </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//Pihome Update
echo "
<div class=\"modal fade\" id=\"pihome_update\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['pihome_up']."</h5>
            </div>
            <div class=\"modal-body\">
			<p class=\"text-muted\"> ".$LANG['pihome_soft']." </p>";

		
echo "	<div class=\"list-group\">";
echo "                            <a href=\"#\" class=\"list-group-item\">
                                    <i class=\"fa fa-server fa-1x blueinfo\"></i> ".$LANG['current_v']." 
                                    <span class=\"pull-right text-muted small\"><em>".settings($conn, 'version')."</em>
                                    </span>
                                </a>"; 
ini_set('max_execution_time',90);
$getVersions = file_get_contents(''.settings($conn, 'update_location').''.settings($conn, 'update_file').'');
if ($getVersions != ''){
$versionList = explode("\n", $getVersions);	
	foreach ($versionList as $aV)
	{
		echo "<a href=\"settings.php?uid=10\"  class=\"list-group-item\">
        <i class=\"fa fa-download fa-1x blueinfo\"></i> ".$LANG['a_update']." 
        <span class=\"pull-right text-muted small\"><em>".$aV."</em></span>
         </a>";
	}
}	
echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

// systems up time
echo "
<div class=\"modal fade\" id=\"system_uptime\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['uptime']."</h5>
            </div>
            <div class=\"modal-body\">
			<p class=\"text-muted\"> ".$LANG['pi_uptime']." </p>
			<i class=\"fa fa-clock-o fa-1x red\"></i>
			";
$uptime = (exec ("cat /proc/uptime"));
$uptime=substr($uptime, 0, strrpos($uptime, ' '));
echo secondsToWords($uptime);
echo "</div>
            <div class=\"modal-footer\">
			<button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

// backup_image
echo "
<div class=\"modal fade\" id=\"backup_image\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['backup']."</h5>
            </div>
            <div class=\"modal-body\">
			<p class=\"text-muted\"> ".$LANG['backup_wait']." </p>
			<i class=\"fa fa-clone fa-1x blue\"></i> ".$LANG['backup']."
			";

echo "            </div>
            <div class=\"modal-footer\">
			<button type=\"button\" class=\"btn btn-primary btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
			<a href=\"javascript:db_backup();\" class=\"btn btn-default login btn-sm\">".$LANG['start']."</a>
            </div>
        </div>
    </div>
</div>";

//wifi model
$rxwifidata = exec ("cat /sys/class/net/wlan0/statistics/rx_bytes");
$txwifidata = exec ("cat /sys/class/net/wlan0/statistics/tx_bytes");
$rxwifidata = $rxwifidata/1024; // convert to kb
$rxwifidata = $rxwifidata/1024; // convert to mb

$txwifidata = $txwifidata/1024; // convert to kb
$txwifidata = $txwifidata/1024; // convert to mb
$wifimac = exec ("cat /sys/class/net/wlan0/address");
//$wifipeed = exec ("cat /sys/class/net/wlan0/speed");
//$wifipeed = exec("iwconfig wlan0 | grep -i --color quality");
$wifistatus = exec ("cat /sys/class/net/wlan0/operstate");
echo "
<div class=\"modal fade\" id=\"wifi_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['wifi_s']."</h5>
            </div>
            <div class=\"modal-body\">
			<p class=\"text-muted\"> ".$LANG['wifi_status']." </p>
<div class=\"list-group\">
<a href=\"#\" class=\"list-group-item\">
<i class=\"fa fa-signal green\"></i> ".$LANG['status']." ".$wifistatus."
</a>
<a href=\"#\" class=\"list-group-item\">
<i class=\"fa fa-signal green\"></i> ".$LANG['mac']." ".$wifimac."
</a>
<a href=\"#\" class=\"list-group-item\">
<i class=\"fa fa-signal green\"></i> ".$LANG['dw']." ".number_format($rxwifidata,0)." MB 
</a>
<a href=\"#\" class=\"list-group-item\">
<i class=\"fa fa-signal green\"></i> ".$LANG['upload']." ".number_format($txwifidata,0)." MB 
</a>
</div>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//ethernet model
$rxdata = exec ("cat /sys/class/net/eth0/statistics/rx_bytes");
$txdata = exec ("cat /sys/class/net/eth0/statistics/tx_bytes");
$rxdata = $rxdata/1024;
$txdata = $txdata/1024;
$nicmac = exec ("cat /sys/class/net/eth0/address");
$nicpeed = exec ("cat /sys/class/net/eth0/speed");
$nicactive = exec ("cat /sys/class/net/eth0/operstate");
echo "
<div class=\"modal fade\" id=\"eth_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['eth_s']."</h5>
            </div>
            <div class=\"modal-body\">
			   <div class=\"list-group\">
				<a href=\"#\" class=\"list-group-item\"><i class=\"ionicons ion-network green\"></i>
				".$LANG['status']." ".$nicactive."</a>
				<a href=\"#\" class=\"list-group-item\"><i class=\"ionicons ion-network green\"></i>
				".$LANG['speed']." ".$nicpeed." Mbs</a>
				<a href=\"#\" class=\"list-group-item\"><i class=\"ionicons ion-network green\"></i>
				".$LANG['mac']." ".$nicmac."</a>
				<a href=\"#\" class=\"list-group-item\"><i class=\"ionicons ion-network green\"></i>
				".$LANG['dw']." ".number_format($rxdata,0)." MB </a> 
				<a href=\"#\" class=\"list-group-item\"><i class=\"ionicons ion-network green\"></i>
				".$LANG['upload']." ".number_format($txdata,0)." MB </a>
				</div>
           </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//user accounts model 
echo "
<div class=\"modal fade\" id=\"user_setup\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['user_accounts']."</h5>
            </div>
            <div class=\"modal-body\">
			<p class=\"text-muted\"> ".$LANG['pi_user']." </p>";
echo "<div class=\"list-group\">";
$query = "SELECT * FROM user";
$results = $conn->query($query);
while ($row = mysqli_fetch_assoc($results)) {
	$full_name=$row['fullname'];
	$username=$row['username'];
	echo "<div href=\"settings.php?uid=".$row['id']."\"  class=\"list-group-item\"> 
    <i class=\"ionicons ion-person blue\"></i> ".$full_name."
    <span class=\"pull-right text-muted small\"><em>
	<a href=\"javascript:del_user(".$row["id"].");\"><button class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-trash\"></span></button> </a>
	<a href=\"user_password.php?uid=".$row["id"]."\"><button class=\"btn btn-primary btn-xs\"><span class=\"fa fa-user fa-key\"></span></button> </a>
	</em></span></div>";
}
echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default login btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

//Big Thank you 	
echo "
<div class=\"modal fade\" id=\"big_thanks\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
			<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>
                <h5 class=\"modal-title\">".$LANG['credits']."</h5>
            </div>
            <div class=\"modal-body\">
<p class=\"text-muted\"> ".$LANG['pihome_thx']." </p>";
echo "	<div class=\"list-group\">";
echo " 

<a href=\"http://startbootstrap.com/template-overviews/sb-admin-2\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> SB Admin 2 Template <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"http://www.cssscript.com/pretty-checkbox-radio-inputs-bootstrap-awesome-bootstrap-checkbox-css\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> Pretty Checkbox <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"https://fortawesome.github.io/Font-Awesome\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> Font-Awesome <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"http://ionicons.com\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> Ionicons <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"http://www.cssmatic.com/box-shadow\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> Box Shadow CSS <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"https://daneden.github.io/animate.css\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> Animate.css <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"https://www.mysensors.org\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> MySensors <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"http://www.pihome.eu\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> All others if forget them... <span class=\"pull-right text-muted small\"><em>...</em></span></a>
<a href=\"http://pihome.harkemedia.de\" class=\"list-group-item\"><i class=\"ionicons ion-help-buoy blueinfo\"></i> RaspberryPi Home Automation <span class=\"pull-right text-muted small\"><em>...</em></span></a>
";

echo "</div></div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default btn-sm\" data-dismiss=\"modal\">".$LANG['close']."</button>
            </div>
        </div>
    </div>
</div>";

// <a class="btn btn-default login btn-sm btn-edit">Edit</a>
?>
