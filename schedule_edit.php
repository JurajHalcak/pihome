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

if(isset($_GET['id'])) {
	$time_id = $_GET['id'];
}else {
	redirect_to("schedule.php");
}
	//PHP: Bitwise operator
	//http://php.net/manual/en/language.operators.bitwise.php
	//https://www.w3resource.com/php/operators/bitwise-operators.php
if (isset($_POST['submit'])) {
	$sc_en = isset($_POST['sc_en']) ? $_POST['sc_en'] : "0";
        $mask = 0;
        $bit = isset($_POST['Sunday_en']) ? $_POST['Sunday_en'] : "0";
        if ($bit) {
			
          $mask =  $mask | (1 << 0); }
        else {$mask =  $mask & (0 << 0); }
        $bit = isset($_POST['Monday_en']) ? $_POST['Monday_en'] : "0";
        if ($bit) {
          $mask =  $mask | (1 << 1); }
        $bit = isset($_POST['Tuesday_en']) ? $_POST['Tuesday_en'] : "0";
        if ($bit) {
          $mask =  $mask | (1 << 2); }
        $bit = isset($_POST['Wednesday_en']) ? $_POST['Wednesday_en'] : "0";
        if ($bit) {
          $mask =  $mask | (1 << 3); }
        $bit = isset($_POST['Thursday_en']) ? $_POST['Thursday_en'] : "0";
        if ($bit) {
          $mask =  $mask | (1 << 4); }
        $bit = isset($_POST['Friday_en']) ? $_POST['Friday_en'] : "0";
        if ($bit) {
          $mask =  $mask | (1 << 5); }
        $bit = isset($_POST['Saturday_en']) ? $_POST['Saturday_en'] : "0";
        if ($bit) {
          $mask =  $mask | (1 << 6); }
	$start_time = $_POST['start_time'];
	$end_time = $_POST['end_time'];

	$query = "UPDATE schedule_daily_time SET sync = '0',  status = '{$sc_en}', start = '{$start_time}', end = '{$end_time}', WeekDays = '{$mask}' WHERE id = '{$time_id}' LIMIT 1";
	$result = $conn->query($query);
	$schedule_daily_time_id = mysqli_insert_id($conn);
	if ($result) {
		$message_success = $LANG['schedule_edit_ok'];
		header("Refresh: 3; url=schedule.php");
	} else {
		$error = "<p>".$LANG['schedule_edit_fail']."</p><p>".mysqli_error()."</p>";
	}

	foreach($_POST['id'] as $id){
		$id = $_POST['id'][$id];
		$status = isset($_POST['status'][$id]) ? $_POST['status'][$id] : "0";
		$status = $_POST['status'][$id];
		$temp = $_POST['temp'][$id];
		$query = "UPDATE schedule_daily_time_zone SET sync = '0', status = '{$status}', temperature = '{$temp}' WHERE id = '{$id}' LIMIT 1";
		$zoneresults = $conn->query($query);
	}
}
?>
<?php include("header.php"); ?>
<?php include_once("notice.php"); ?>
        <div id="page-wrapper">
<br>
            <div class="row">
                <div class="col-lg-12">
				<div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-clock-o fa-fw"></i> <?php echo $LANG['schedule_edit']; ?>
						<div class="pull-right"> <div class="btn-group"><?php echo date("H:i"); ?></div> </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
<form data-toggle="validator" role="form" method="post" action="<?php $_SERVER['PHP_SELF'];?>" id="form-join">
<?php 
$query = "SELECT * FROM schedule_daily_time WHERE id = {$time_id}";
$results = $conn->query($query);	
$time_row = mysqli_fetch_assoc($results);
?>
    <div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox0" class="styled" type="checkbox" name="sc_en" value="1" <?php $check = ($time_row['status'] == 1) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox0"> <?php echo $LANG['schedule_enable']; ?></label></div>
	
    <div class="row">
	<div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox1" class="styled" type="checkbox" name="Sunday_en" value="1" <?php $check = (($time_row['WeekDays'] & 1) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox1"> <?php echo $LANG['sunday']; ?></label></div></div>
	
    <div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox2" class="styled" type="checkbox" name="Monday_en" value="1" <?php $check = (($time_row['WeekDays'] & 2) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox2"> <?php echo $LANG['monday']; ?></label></div></div>
	
    <div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox3" class="styled" type="checkbox" name="Tuesday_en" value="1" <?php $check = (($time_row['WeekDays'] & 4) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox3"> <?php echo $LANG['tuesday']; ?></label></div></div>
	
    <div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox4" class="styled" type="checkbox" name="Wednesday_en" value="1" <?php $check = (($time_row['WeekDays'] & 8) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox4"> <?php echo $LANG['wednesday']; ?></label></div></div>
	
    <div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox5" class="styled" type="checkbox" name="Thursday_en" value="1" <?php $check = (($time_row['WeekDays'] & 16) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox5"> <?php echo $LANG['thursday']; ?></label></div></div>
	
    <div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox6" class="styled" type="checkbox" name="Friday_en" value="1" <?php $check = (($time_row['WeekDays'] & 32) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox6"> <?php echo $LANG['friday']; ?></label></div></div>
	
    <div class="col-xs-3"><div class="checkbox checkbox-default checkbox-circle">
    <input id="checkbox7" class="styled" type="checkbox" name="Saturday_en" value="1" <?php $check = (($time_row['WeekDays'] & 64) > 0) ? 'checked' : ''; echo $check; ?>>
    <label for="checkbox7"> <?php echo $LANG['saturday']; ?></label></div></div>
	</div>


	<div class="form-group" class="control-label"><label><?php echo $LANG['n_clima_start']; ?></label>
	<input class="form-control input-sm" type="time" id="start_time" name="start_time" value="<?php echo $time_row["start"];?>" placeholder="<?php echo $LANG['n_clima_start']; ?>" required>
    <div class="help-block with-errors"></div></div>
	
	<div class="form-group" class="control-label"><label><?php echo $LANG['n_clima_end']; ?></label>
	<input class="form-control input-sm" type="time" id="end_time" name="end_time" value="<?php echo $time_row["end"];?>" placeholder="<?php echo $LANG['n_clima_end']; ?>" required>
    <div class="help-block with-errors"></div></div>				
<?php 
$query = "SELECT * FROM schedule_daily_time_zone_view WHERE time_id = {$time_id}";
$results = $conn->query($query);	
while ($row = mysqli_fetch_assoc($results)) {
?>
	<input type="hidden" name="id[<?php echo $row["tz_id"];?>]" value="<?php echo $row["tz_id"];?>">
	
	<div class="checkbox checkbox-default  checkbox-circle">
    <input id="checkbox<?php echo $row["tz_id"];?>" class="styled" type="checkbox" name="status[<?php echo $row["tz_id"];?>]" value="1" <?php $check = ($row['tz_status'] == 1) ? 'checked' : ''; echo $check; ?> onclick="$('#<?php echo $row["tz_id"];?>').toggle();">
    <label for="checkbox<?php echo $row["tz_id"];?>"><?php echo $row["zone_name"];?></label>
    <div class="help-block with-errors"></div></div>
<?php 
if($row['tz_status'] == 1){
	echo "<div id=\"".$row["tz_id"]."\"><div class=\"form-group\" class=\"control-label\">";
}else{
	echo "<div id=\"".$row["tz_id"]."\" style=\"display:none !important;\"><div class=\"form-group\" class=\"control-label\">";
	}
?>
	<select class="form-control input-sm" type="number" id="<?php echo $row["tz_id"];?>" name="temp[<?php echo $row["tz_id"];?>]" placeholder="<?php echo $LANG['temperature']; ?>" >
	<option selected><?php echo $row["temperature"];?></option>
	<option>0</option>
	<option>16</option>
        <option>16.5</option>
	<option>17</option>
        <option>17.5</option>
        <option>18</option>
	<option>18.5</option>
	<option>19</option>
        <option>19.5</option>
	<option>20</option>
        <option>20.5</option>
	<option>21</option>
        <option>21.5</option>
        <option>22</option>
	<option>22.5</option>
        <option>23</option>
	<option>23.5</option>
	<option>24</option>
        <option>24.5</option>
	<option>25</option>
        <option>25.5</option>
	<option>26</option>
        <option>26.5</option>
	<option>27</option>
        <option>27.5</option>
	<option>28</option>
        <option>28.5</option>
	<option>29</option>
        <option>29.5</option>
	<option>30</option>
	<option>31</option>
	<option>32</option>
	<option>33</option>
	<option>34</option>
	<option>35</option>
	<option>36</option>
	<option>37</option>
	<option>38</option>
	<option>39</option>
	<option>40</option>
	<option>41</option>
	<option>42</option>
	<option>43</option>
	<option>44</option>
	<option>45</option>
	
	</select>
    <div class="help-block with-errors"></div></div></div>
<?php }?>				
                <a href="schedule.php"><button type="button" class="btn btn-primary btn-sm" ><?php echo $LANG['cancel']; ?></button></a>
                <input type="submit" name="submit" value="<?php echo $LANG['submit']; ?>" class="btn btn-default btn-sm login">
				</form>
						</div>
                        <!-- /.panel-body -->
						<div class="panel-footer">
<?php 
ShowWeather($conn);
?>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<?php include("footer.php"); ?>
