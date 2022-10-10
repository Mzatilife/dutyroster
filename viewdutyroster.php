<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}




 ?>
 
 <?php
/*
   System Developer: Wisdom Mhango
   Position:  Computer engineer
   E-mail: wismhango@yahoo.com or mhangowisdom0@gmail.com
   Phone:+265997195246 or +265888764489
   Nationality: Malawian	
   Year Developed:2018

*/

$conn=array();
$conn=mysqli_connect("localhost","root","","dutyroster");
date_default_timezone_set("Africa/Blantyre");



if(isset($_POST['generate'])){ 
	
  $date=date("Y-m-d H:i");
  
  $sumdate=array();
  
  $query_user=mysqli_query($conn,"select * from users order by rand()");
   
  $querytotalusers=mysqli_query($conn,"SELECT * from total_users_on_duty");
  $fetchtotalusers=mysqli_fetch_array($querytotalusers);
  $rowtotalusers=$fetchtotalusers['total'];

   $count=mysqli_num_rows($query_user);
   
   while($user=mysqli_fetch_array($query_user))
   {
   $userid=$user['user_id'];
  
  $query_duty_roster=mysqli_query($conn,"select * from dutyroster_scheduled order by date_service_end desc limit 1");
  $count_duty=mysqli_num_rows($query_duty_roster);
  $duty_row=mysqli_fetch_array($query_duty_roster);
  $rowdate=$duty_row['date_service_begin'];
  
  
  if($count_duty==0)
  {
    $sumdate=$date;
  }
  
  else
  {
	$sumdate=$rowdate;
  }
   
   
   
   
      $query_duty_date=mysqli_query($conn,"select count(date_service_begin) as dutydate,date_service_begin from dutyroster_scheduled where date_service_begin='$sumdate' group by date_service_begin");
	  $countrows=mysqli_num_rows($query_duty_date);
	  $fetchdate=mysqli_fetch_array($query_duty_date);
	 
	  $lasttime=date_create($sumdate);	
      $interval=date_interval_create_from_date_string("2 hours 0 minutes");
	  $datesum=date_add($lasttime,$interval);
	  $dateconve=date_format($datesum,"Y-m-d H:i");

    
	 
	  $newdate=array();
	  $dayservicebegin=array();
	  $dayserviceend=array();
	  $serviceend=array();
	 
     if($countrows==$rowtotalusers || $fetchdate['dutydate']==$rowtotalusers)
	 {
		$newdate=$dateconve;//service begins2
	    $dateconve1=date_format($datesum,"Y-m-d");
        $dayservicebegin=strftime("%A",strtotime($dateconve1));
		
		$serviceend=date("Y-m-d H:i:s",strtotime("2 hours 0 minutes",strtotime($dateconve)));
        $serviceendday1=date("Y-m-d",strtotime("2 hours 0 minutes",strtotime($dateconve))); 
        $dayserviceend=strftime("%A",strtotime($serviceendday1));

		
	 }
	 else
	 {
		$newdate=$sumdate; 
		$dayservicebegin=strftime("%A",strtotime($sumdate));
		
		$serviceend=date("Y-m-d H:i",strtotime("2 hours 0 minutes",strtotime($sumdate)));//
		$serviceendday2=date("Y-m-d",strtotime("2 hours 0 minutes",strtotime($sumdate))); //service end
        $dayserviceend=strftime("%A",strtotime($serviceendday2));//day service end
	 }

	 
	 $insertpersonOnDuty=mysqli_query($conn,"insert into dutyroster_scheduled(user_id,date_service_begin,date_service_end,uniquekey) values('$userid','$newdate','$serviceend','$uniquekey')") or die("".mysqli_error($con));
	 
	
   }
  
    if($insertpersonOnDuty==true)
	 {
		$msg="Duty roster created  successfully!!!";
	 }
	 else
	 {
		$error="Something went wrong. Please try again";
	 }
}
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	
	<title>Employee Duty Roster System</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>

</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
		<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					



	<?php if($_GET['duty']==2){?>
				<div class="col-md-12">

						<h3 class="page-title">Full Duty Roster</h3>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">
							  <form action="" method="post">
							    <input type="submit" class="button btn-warning btn-sm" name="generate" onClick="return confirm('Generate duty roster?');" value="generate">
								<input type="button" class="button btn-primary btn-sm" style="margin-left:4px;" name="onduty" value="View Users On Duty" onClick="return useronDuty();">
							    <input type="button" class="button btn-success btn-sm" style="margin-left:4px;" name="allduties" value="View Full Duty Roster" onClick="return viewallDuties();">
							  </form>
							</div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
											    <th>Firstname</th>
											    <th>Lastname</th>
												<th>Position</th>
												<th>Starting_time</th>
												<th>Closing_time</th>
												<th>Action</th>
										</tr>
									</thead>
									
									<tbody>

<?php 
$sql = "SELECT p.position as position,ur.firstname as firstname,ur.lastname as lastname,ds.date_service_begin as starting_time,ds.date_service_end as ending_time from  users ur inner join dutyroster_scheduled ds on ur.user_id=ds.user_id left join positions p on ur.position_id=p.position_id order by ds.date_service_begin,ds.date_service_end DESC";
$queryvehicle=mysqli_query($conn,$sql);
$countvehicle=mysqli_num_rows($queryvehicle);
$fetchvehicle=mysqli_fetch_all($queryvehicle,MYSQLI_ASSOC);

$cnt=1;
if($countvehicle>0)
{
foreach($fetchvehicle as $result)
{				?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($result['firstname'])	;?></td>
											<td><?php echo htmlentities($result['lastname']);?></td>
											<td><?php echo htmlentities($result['position']);?></td>
											<td><?php echo htmlentities($result['starting_time']);?></td>
											<td><?php echo htmlentities($result['ending_time']);?></td>

<td>
<a href="updatedriver.php?update=<?php echo $result['driver_id'];?>" class='button btn-sm btn-success'>update</a></td>										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>

						

							</div>
						</div>
					</div>		
				</div><!-- closing view users on duty only-->	
				
				
				<?php } ?>
				
		
				<?php if($_GET['duty']==1){?>
				<div class="col-md-12">

						<h3 class="page-title">Employee On Duty</h3>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">
							  <form action="" method="post">
							    <input type="submit" class="button btn-warning btn-sm" name="generate" onClick="return confirm('Generate duty roster?');" value="generate">
								<input type="button" class="button btn-primary btn-sm" style="margin-left:4px;" name="onduty" value="View Users On Duty" onClick="return useronDuty();">
							    <input type="button" class="button btn-success btn-sm" style="margin-left:4px;" name="allduties" value="View Full Duty Roster" onClick="return viewallDuties();">
							  </form>
							</div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
											    <th>Firstname</th>
											    <th>Lastname</th>
												<th>Position</th>
												<th>Starting_time</th>
												<th>Closing_time</th>
										
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody>

<?php 

$queryuseronduty=mysqli_query($conn,"select p.position as position,ur.firstname as firstname,ur.lastname as lastname,ds.date_service_begin as starting_time,ds.date_service_end as ending_time from users ur join dutyroster_scheduled ds on ur.user_id=ds.user_id left join positions p on ur.position_id=p.position_id where (date_service_begin<=NOW() and date_service_end>NOW())");

$countuseronduty=mysqli_num_rows($queryuseronduty);
$fetchuseronduty=mysqli_fetch_all($queryuseronduty,MYSQLI_ASSOC);

$cnt=1;
if($countuseronduty>0)
{
foreach($fetchuseronduty as $result)
{				?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($result['firstname'])	;?></td>
											<td><?php echo htmlentities($result['lastname']);?></td>
											<td><?php echo htmlentities($result['position']);?></td>
											<td><?php echo htmlentities($result['starting_time']);?></td>
											<td><?php echo htmlentities($result['ending_time']);?></td>

<td>
<a href="updatedriver.php?update=<?php echo $result['driver_id'];?>" class='button btn-sm btn-success'>update</a></td>
										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>

						

							</div>
						</div>
					</div>		
				</div><!-- closing view users on duty only-->	
				
				
				<?php } ?>
				
			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	
	<script type="text/javascript">
	    function useronDuty()
		{
			 window.open('viewdutyroster.php?duty=1','_self');
			
		}
	    function viewallDuties()
		{
			 window.open('viewdutyroster.php?duty=2','_self');
			
		}
	
	</script>
</body>
</html>

