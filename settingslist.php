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
* Language support by Juraj Halcak :: juraj@halcak.sk :: 19.01.14       *"
*************************************************************************"
*/

require_once(__DIR__.'/st_inc/session.php'); 
confirm_logged_in();
require_once(__DIR__.'/st_inc/connection.php');
require_once(__DIR__.'/st_inc/functions.php');
require_once(__DIR__.'/lang/sk.inc');
//query to frost protection temperature 
$query = "SELECT * FROM frost_protection LIMIT 1 ";
$result = $conn->query($query);
$frosttemp = mysqli_fetch_array($result);
$frost_temp = $frosttemp['temperature'];
?>                      <div class="panel panel-primary">
                        <div class="panel-heading">
                        <i class="fa fa-cog fa-fw"></i>   <?php echo $LANG['settings']; ?>   
						<div class="pull-right"> <div class="btn-group"><?php echo date("H:i"); ?></div> </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						
							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_piconnect.php" data-toggle="modal" data-target="#piconnect">
							<h3 class="buttontop"><small><?php echo $LANG['piconnect']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-plug green"></i></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>


							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#add_frost">
							<h3 class="buttontop"><small><?php echo $LANG['frost']; ?></small></h3>
							<i class="ion-ios-snowy larger blue"></i>
							<h3 class="status" style="margin-top:-11px;"><small style="color:#048afd;"><i class="fa fa-circle fa-fw"></i></small>
							<small class="statusdegree"><?php echo $frost_temp ;?>&deg;</small><small class="zoonstatus"> <i class="fa"></i></small>
							</h3></button>	

							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_boiler.php" data-toggle="modal" data-target="#boiler_safety_setup">
							<h3 class="buttontop"><small><?php echo $LANG['boiler']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-flame fa-1x red"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_gpio.php" data-toggle="modal" data-target="#boost_setup">
							<h3 class="buttontop"><small><?php echo $LANG['boost']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-rocket fa-1x blueinfo"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							
							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_override.php" data-toggle="modal" data-target="#override_setup">
							<h3 class="buttontop"><small><?php echo $LANG['override']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-refresh fa-1x blue"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_sensors.php" data-toggle="modal" data-target="#temperature_sensor">
							<h3 class="buttontop"><small><?php echo $LANG['sensors']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-thermometer red"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_gpio.php" data-toggle="modal" data-target="#zone_setup">
							<h3 class="buttontop"><small><?php echo $LANG['zone']; ?></small></h3>
							<h3 class="degre" ><i class="glyphicon glyphicon-th-large orange"></i> </h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>							

							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_sensors.php" data-toggle="modal" data-target="#sensor_gateway">
							<h3 class="buttontop"><small><?php echo $LANG['gateway']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-heartbeat red"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							<button class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_gpio.php" data-toggle="modal" data-target="#cron_jobs">
							<h3 class="buttontop"><small><?php echo $LANG['cron']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-ios-timer-outline blue"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>
<?php 
	$query = "SELECT * FROM messages_in WHERE node_id = 0 ORDER BY datetime DESC LIMIT 1";
	$result = $conn->query($query);
	$result = mysqli_fetch_array($result);
	$system_cc = $result['payload'];
	if ($system_cc < 40){$system_cc="#0bb71b"; $fan=" ";}elseif ($system_cc < 50){$system_cc="#F0AD4E"; $fan="fa-pulse";}elseif ($system_cc > 50){$system_cc="#ff0000"; $fan="fa-pulse";}
?>							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#system_c">
							<h3 class="buttontop"><small><?php echo $LANG['system']; ?> C&deg;</small></h3>
							<h3 class="degre" ><i class="fa fa-server fa-1x green"></i></h3>
							<h3 class="status"><small style="color:<?php echo $system_cc;?>"><i class="fa fa-circle fa-fw"></i></small>
							<small class="statusdegree"><?php echo number_format($result['payload'],0);?>&deg;</small><small class="zoonstatus"> <i class="fa fa-asterisk <?php echo $fan;?>"></i></small>
							</h3></button>	
							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#os_version">
							<h3 class="buttontop"><small><?php echo $LANG['os_v']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-linux"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>
							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#pihome_update">
							<h3 class="buttontop"><small><?php echo $LANG['pihome_up']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-download fa-1x blueinfo"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>
							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#system_uptime">
							<h3 class="buttontop"><small><?php echo $LANG['uptime']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-clock red"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#backup_image">
							<h3 class="buttontop"><small><?php echo $LANG['bck']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-clone fa-1x blue"></i> </h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>
							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#wifi_setup">
							<h3 class="buttontop"><small><?php echo $LANG['wifi']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-signal green"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>							

							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-toggle="modal" data-target="#eth_setup">
							<h3 class="buttontop"><small><?php echo $LANG['eth']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-network orange"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>
							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_users.php" data-toggle="modal" data-target="#user_setup">
							<h3 class="buttontop"><small><?php echo $LANG['user_accounts']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-person blue"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>

							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" data-href="edit_users.php" data-toggle="modal" data-target="#big_thanks">
							<h3 class="buttontop"><small><?php echo $LANG['credits']; ?></small></h3>
							<h3 class="degre" ><i class="ionicons ion-help-buoy blueinfo"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>
							
							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" onClick="reboot()">
							<h3 class="buttontop"><small><?php echo $LANG['restart_pi']; ?></small></h3>
							<i class="ion-ios-refresh-outline larger orange"></i>
							<h3 class="status" style="margin-top:-11px;"><small style="color:#fff;"><i class="fa"></i></small>
							<small class="statusdegree"></small>
							</h3></button>

							<button type="button" class="btn btn-default btn-circle btn-xxl mainbtn animated fadeIn" onClick="shutdown()">
							<h3 class="buttontop"><small><?php echo $LANG['shtdwn']; ?></small></h3>
							<h3 class="degre" ><i class="fa fa-power-off fa-1x red"></i></h3>
							<h3 class="status"><small style="color:#fff;"><i class="fa"></i></small>
							</h3></button>	
				
<?php include("model.php");  ?>
                        </div>
                        <!-- /.panel-body -->
						<div class="panel-footer">
<?php 
ShowWeather($conn);
?>
                        </div>
                    </div>
