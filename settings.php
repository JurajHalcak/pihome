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

if(isset($_GET["frost"])) {
	$frost_temp = $_GET['frost'];
	$info_message = $LANG['frost_change'].$frost_temp."&deg;";
}
if(isset($_GET["reboot"])) {
	$info_message = $LANG['rbt_start'];
}
if(isset($_GET["shutdown"])) {
	$info_message = $LANG['off_start'];
}

if(isset($_GET["del_user"])) {
	$info_message = $LANG['account_rem'];
}

if(isset($_GET["zone_deleted"])) {
	$info_message = $LANG['zone_rem'];
}

if(isset($_GET["zone_deleted"])) {
	$info_message = $LANG['search_gw'];
}

//backup process start
 if(isset($_GET["db_backup"])) {
$info_message = $LANG['bck_start'] ;
include("start_backup.php");
 }
//query to frost protection temperature 
$query = "SELECT * FROM frost_protection LIMIT 1 ";
$result = $conn->query($query);
$frosttemp = mysqli_fetch_array($result);
$frost_temp = $frosttemp['temperature'];
?>
<?php include("header.php");  ?>
<?php include("notice.php");  ?>
<div id="page-wrapper">
<br>
            <div class="row">
                <div class="col-lg-12">
                  	<div id="settingslist" >
				   <div class="text-center"><br><br><p><?php echo $LANG['wait_sys_db']; ?>(Info 03)</p>
				   <br><br><img src="images/loader.gif">
				   </div>
				   </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<?php include("footer.php");  ?>
