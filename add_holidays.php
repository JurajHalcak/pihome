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

require_once(__DIR__.'/st_inc/session.php');
confirm_logged_in();
require_once(__DIR__.'/st_inc/connection.php');
require_once(__DIR__.'/st_inc/functions.php');
require_once(__DIR__.'/lang/sk.inc');
?>
<?php include("header.php"); ?>
        <div id="page-wrapper">
<br>
            <div class="row">
                <div class="col-lg-12">
				<div class="panel panel-primary">
                        <div class="panel-heading">
                           <i class="fa fa-paper-plane fa-1x"></i> <?php echo $LANG['holiday']; ?>   
						<div class="pull-right"> <div class="btn-group"><?php echo date("H:i"); ?></div> </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                <form data-toggle="validator" role="form" method="post" action="holidays.php" id="form-join">

				<div class="checkbox checkbox-default checkbox-circle">
                <input id="checkbox1" class="styled" type="checkbox" name="holidays_enable" value="1" >
                <label for="checkbox1"> <?php echo $LANG['enable']; ?> </label>
                <div class="help-block with-errors"></div></div>


				<div class="form-group" class="control-label"><label> <i class="fa fa-paper-plane fa-1x"></i> <?php echo $LANG['departure']; ?> </label>
				<input class="form-control input-sm" id="start_date_time" name="start_date_time" value="" placeholder="<?php echo $LANG['holidays_start']; ?> " required>
                <div class="help-block with-errors"></div></div>
				
				<div class="form-group" class="control-label"><label>  <i class="fa fa-home fa-fw fa-1x"></i> <?php $LANG['return']; ?> </label>
				<input class="form-control input-sm" id="end_date_time" name="end_date_time" value="" placeholder="<?php echo $LANG['holidays_end']; ?> " required>
                <div class="help-block with-errors"></div></div>				


                <a href="holidays.php"><button type="button" class="btn btn-primary btn-sm" ><?php echo $LANG['cancel']; ?></button></a>
                <input type="submit" name="submit" value="<?php echo $LANG['submit']; ?>" class="btn btn-default btn-sm">
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
