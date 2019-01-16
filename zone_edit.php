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
*************************************************************************"
*/
require_once(__DIR__.'/st_inc/session.php');
confirm_logged_in();
require_once(__DIR__.'/st_inc/connection.php');
require_once(__DIR__.'/st_inc/functions.php');

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		redirect_to("home.php");
	}
//when form is submit
if (isset($_POST['submit'])) {
	$zone_status = isset($_POST['zone_status']) ? $_POST['zone_status'] : "0";
	$index_id = mysqli_prepare($_POST['index_id']);
	$name = mysqli_prepare($_POST['name']);
	$type = mysqli_prepare($_POST['type']);
	$max_c = mysqli_prepare($_POST['max_c']);
	$max_operation_time = mysqli_prepare($_POST['max_operation_time']);
	$hysteresis_time = mysqli_prepare($_POST['hysteresis_time']);
	$sensor_id = mysqli_prepare($_POST['sensor_id']);
	$controler = mysqli_prepare($_POST['controler_id']);
	$controler_id = mysqli_prepare($_POST['controler_id']);
	$controler_child_id = mysqli_prepare($_POST['controler_child_id']);
	$boost_button_id = mysqli_prepare($_POST['boost_button_id']);
	$boost_button_child_id = mysqli_prepare($_POST['boost_button_child_id']);
	$boiler = explode('-', $_POST['boiler_id'], 2);
	$boiler_id = $boiler[0];

	//query to search node id for temperature sensors
	$query = "SELECT * FROM nodes WHERE node_id = '{$sensor_id}' LIMIT 1";
	$result = $conn->query($query);
	$found_product = mysqli_fetch_array($result);
	$sensor_id = $found_product['id'];
		
	//query to search node id for zone controller
	$query = "SELECT * FROM nodes WHERE node_id = '{$controler_id}' LIMIT 1";
	$result = $conn->query($query);
	$found_product = mysqli_fetch_array($result);
	$controler_id = $found_product['id'];
	
	//query to search node id for boost button
	$query = "SELECT * FROM nodes WHERE node_id = '{$boost_button_id}' LIMIT 1";
	$result = $conn->query($query);
	$found_product = mysqli_fetch_array($result);
	$boost_button_id = $found_product['node_id'];
	
	$query = "INSERT INTO zone (status, index_id, name, type, max_c, max_operation_time, hysteresis_time, sensor_id, sensor_child_id, controler_id, controler_child_id, boiler_id) 
	VALUES ('{$zone_status}', '{$index_id}', '{$name}', '{$type}', '{$max_c}', '{$max_operation_time}', '{$hysteresis_time}', '{$sensor_id}', '{$sensor_child_id}', '{$controler_id}', '{$controler_child_id}', '{$boiler_id}');";
	$result = $conn->query($query);
	$zone_id = mysqli_insert_id($conn);

	if ($result) {
		$message_success = "<p>".$LANG['zone_add_ok']."</p>";
	} else {
		$error = "<p>".$LANG['zone_add_fail'].mysqli_error($conn). "</p>";
	}
	//Add Zone to message out table at same time to send out instructions to controller for each zone. 
	$query = "INSERT INTO messages_out (node_id, child_id, sub_type, payload, sent, zone_id)VALUES ('{$controler}','{$controler_child_id}', '2', '0', '1', '{$zone_id}');";
	$result = $conn->query($query);
	if ($result) {
		$message_success .= "<p>".$LANG['ctrl_add_ok']."</p>";
	} else {
		$error = "<p>".$LANG['ctrl_add_fail'].mysqli_error($conn). "</p>";
	}

	//Add Zone Boost Button Console to message out table at same time
	$query = "INSERT INTO messages_out (node_id, child_id, sub_type, payload, sent, zone_id)VALUES ('{$boost_button_id}','{$boost_button_child_id}', '2', '0', '1', '{$zone_id}');";
	$result = mysqli_fetch_array($query, $connection);
	if ($result) {
		$message_success .= "<p>".$LANG['zone_add_ok']."</p>";
	} else {
		$error = "<p>".$LANG['zone_add_fail'].mysqli_error($conn). "</p>";
	}
	
	//Add Zone to boost table at same time
	$query = "INSERT INTO boost (status, zone_id, temperature, minute, boost_button_id, boost_button_child_id)VALUES ('0', '{$zone_id}','{$max_c}','{$max_operation_time}', '{$boost_button_id}', '{$boost_button_child_id}');";
	$result = $conn->query($query);
	if ($result) {
		$message_success .= "<p>".$LANG['boost_add_ok']."</p>";
	} else {
		$error = "<p>".$LANG['boost_add_fail'].mysqli_error($conn). "</p>";
	}
	//Add Zone to override table at same time
	$query = "INSERT INTO override (status, zone_id, temperature) VALUES ('0', '{$zone_id}','{$max_c}');";
	$result = $conn->query($query);
	if ($result) {
		$message_success .= "<p>".$LANG['over_add_ok']."</p>";
	} else {
		$error = "<p>".$LANG['over_add_ok'].mysqli_error($conn). "</p>";
	}
	/* No need to modify Night Schedule as its only Zone editing
	//Add Zone to schedule_night_climat_zone table at same time
	$query = "INSERT INTO schedule_night_climat_zone (status, zone_id, schedule_night_climate_id, min_temperature, max_temperature) VALUES ('0', '{$zone_id}', '1', '18','21');";
	$result = $conn->query($query);
	if ($result) {
		$message_success .= "<p>Zone Night Climate Record Added Successfuly. </p>";
		header("Refresh: 5; url=home.php");
	} else {
		$error = "<p>Zone Night Climate Record Addition Failed!!!</p> <p>" .mysqli_error($conn). "</p>";
	}
	*/
	$alert_message=$LANG['zone_schedule_no'].$name.$LANG['zone_schedule_no2'];
}
?>
<?php include("header.php");  ?>

<?php $alert_message = "<p>Zone Editing is Still Under work!!! All Code is transfered into php7 but need some working to update records, untill then only work around is to delete zone and create again. </p>"; ?>

<?php include_once("notice.php"); ?>
<?php 
$query = "SELECT * FROM zone WHERE id = {$id} limit 1;";
$result = $conn->query($query);
$row = mysqli_fetch_assoc($result);
?>


<div id="page-wrapper">
<br>
            <div class="row">
                <div class="col-lg-12">
                   <div class="panel panel-primary">
                        <div class="panel-heading">
                            <?php echo $LANG['zone_edit']; echo $row['name'] ;?>
						<div class="pull-right"> <div class="btn-group"><?php echo date("H:i"); ?></div> </div>
                        </div>
                        <!-- /.panel-heading -->
<div class="panel-body">


<form data-toggle="validator" role="form" method="post" action="<?php $_SERVER['PHP_SELF'];?>" id="form-join">

<div class="checkbox checkbox-default checkbox-circle">
<input id="checkbox0" class="styled" type="checkbox" name="zone_status" value="1" <?php $check = ($row['status'] == 1) ? 'checked' : ''; echo $check; ?>>
<label for="checkbox0"> <?php echo $LANG['zone_enable']; ?> </label>
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_index']; ?></label>
<input class="form-control" placeholder="<?php echo $LANG['zone_index']; ?>" value="<?php echo $row['index_id']; ?>" id="index_id" name="index_id" data-error="<?php echo $LANG['zone_index_what']; ?>" pattern="[0-9]+([\,|\.][0-9]+)?" autocomplete="off" required>
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_name']; ?></label>
<input class="form-control" placeholder="<?php echo $LANG['zone_name']; ?>" value="<?php echo $row['name']; ?>" id="name" name="name" data-error="<?php echo $LANG['zone_no_empty']; ?>" autocomplete="off" required>
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_typ']; ?></label>
<select id="type" name="type" class="form-control select2" placeholder="<?php echo $LANG['zone_type_ie']; ?>"  data-error="<?php echo $LANG['zone_type_either']; ?>" autocomplete="off" required>
<?php echo "<option selected >".$row['type']."</option>"; ?>
<option><?php echo $LANG['zone_heating']; ?></option>
<option><?php echo $LANG['zone_water']; ?></option>
</select>				
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_temp_max']; ?></label>
<input class="form-control" placeholder="<?php echo $LANG['zone_temp_max']; ?>" value="<?php echo $row['max_c']; ?>" id="max_c" name="max_c" data-error="<?php echo $LANG['zone_temp_max_safety']; ?>" pattern="[0-9]+([\,|\.][0-9]+)?"  autocomplete="off" required>
<div class="help-block with-errors"></div></div>
				
<div class="form-group" class="control-label"><label><?php echo $LANG['zone_op_time']; ?></label>
<input class="form-control" placeholder="<?php echo $LANG['zone_op_time_opt']; ?>" value="<?php echo $row['max_operation_time']; ?>" id="max_operation_time" name="max_operation_time" data-error="<?php echo $LANG['zone_op_time_safety']; ?>" pattern="[0-9]+([\,|\.][0-9]+)?"  autocomplete="off" required>
<div class="help-block with-errors"></div></div>				

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_hyst']; ?></label>
<input class="form-control" placeholder="<?php echo $LANG['zone_hyst_what']; ?>" value="<?php echo $row['hysteresis_time']; ?>" id="hysteresis_time" name="hysteresis_time" data-error="<?php echo $LANG['zone_hyst_what']; ?>" pattern="[0-9]+([\,|\.][0-9]+)?"  autocomplete="off" required>
<div class="help-block with-errors"></div></div>	

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_sensor_ID']; ?></label>
<select id="sensor_id" name="sensor_id" class="form-control select2" data-error="<?php echo $LANG['zone_sensor_empty']; ?>" autocomplete="off" required>
<?php 
$query = "SELECT * FROM nodes WHERE id = {$row['sensor_id']} LIMIT 1;";
$result = $conn->query($query);
$s_row = mysqli_fetch_assoc($result);
?>

<?php echo "<option selected >".$s_row['node_id']."</option>"; ?>
<?php  $query = "SELECT node_id, child_id_1 FROM nodes WHERE name = 'Temperature Sensor'";
$result = $conn->query($query);
echo "<option></option>";
while ($datarw=mysqli_fetch_array($result)) {
$node_id=$datarw["node_id"];
echo "<option>".$node_id."</option>";} ?>
</select>				
<div class="help-block with-errors"></div></div>

<!-- Child Sensors ID is always zero -->
<input type="hidden" name="sensor_child_id" value="0">	
		
<div class="form-group" class="control-label"><label><?php echo $LANG['zone_relay']; ?></label>
<select id="controler_id" name="controler_id" class="form-control select2" data-error="<?php echo $LANG['zone_relay_what']; ?>" autocomplete="off" required>
<?php 
$query = "SELECT * FROM nodes WHERE id = {$row['controler_id']} LIMIT 1;";
$result = $conn->query($query);
$z_row = mysqli_fetch_assoc($result);
echo "<option selected >".$z_row['node_id']."</option>";
$query = "SELECT node_id FROM nodes WHERE name = 'Zone Controller Relay'";
$result = $conn->query($query);
echo "<option></option>";
while ($datarw=mysqli_fetch_array($result)) {
	$node_id=$datarw["node_id"];
	echo "<option>".$node_id."</option>";} 
	?>
</select>				
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_relay_child']; ?></label>
<select id="controler_child_id" name="controler_child_id" class="form-control select2" placeholder="<?php echo $LANG['zone_relay_child']; ?>"  data-error="<?php echo $LANG['zone_relay_child_what']; ?>" autocomplete="off" required>
<?php echo "<option selected >".$row['controler_child_id']."</option>"; ?>
<option></option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
</select>				
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_boost_button']; ?></label>
<select id="boost_button_id" name="boost_button_id" class="form-control select2" data-error="<?php echo $LANG['zone_boost_button_what']; ?>" autocomplete="off" required>
<?php 
$query = "SELECT * FROM boost WHERE zone_id = {$id}  LIMIT 1;";
$result = $conn->query($query);
$b_result = mysqli_fetch_assoc($result);
echo "<option selected >".$b_result['boost_button_id']."</option>";
$query = "SELECT node_id FROM nodes WHERE name = 'Button Console'";
$result = $conn->query($query);
echo "<option></option>";
	while ($datarw=mysqli_fetch_array($result)) {
	$node_id=$datarw["node_id"];
	echo "<option>".$node_id."</option>";} 
	?>
</select>				
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['zone_boost_child_ID']; ?></label>
<select id="boost_button_child_id" name="boost_button_child_id" class="form-control select2" placeholder="<?php echo $LANG['zone_boost_child_ID']; ?>"  data-error="<?php echo $LANG['zone_boost_child_what']; ?>" autocomplete="off" required>
<?php echo "<option selected >".$b_result['boost_button_child_id']."</option>"; ?>
<option></option>
<option>0</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
</select>				
<div class="help-block with-errors"></div></div>

<div class="form-group" class="control-label"><label><?php echo $LANG['boiler']; ?></label>
<select id="boiler_id" name="boiler_id" class="form-control select2" data-error="<?php echo $LANG['boiler_empty']; ?>" autocomplete="off" required>
<?php if(isset($_POST['boiler_id'])) { echo "<option selected >".$_POST['boiler_id']."</option>"; } ?>
<?php  $query = "SELECT id, node_id, name FROM boiler;";
$result = $conn->query($query);
while ($datarw=mysqli_fetch_array($result)) {
	$boiler_id=$datarw["id"]."-".$datarw["name"].$LANG['boiler_node'].$datarw["node_id"] ;
echo "<option>".$boiler_id."</option>";} ?>
</select>				
<div class="help-block with-errors"></div></div>

<a href="home.php"><button type="button" class="btn btn-primary btn-sm"><?php echo $LANG['cancel']; ?></button></a>
</form>
                        </div>
                        <!-- /.panel-body -->
						<div class="panel-footer">
<?php 
ShowWeather($conn);
?>
                            <div class="pull-right">
                                <div class="btn-group">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<?php include("footer.php");  ?>
