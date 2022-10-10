<div class="brand clearfix">
	<a href="dashboard.php" style="float:left;font-size: 20px; margin:10px 55px; color:#fff">
	   <div><span class="glyphicon glyphicon-calendar"></span> Automated Duty Roster Scheduling System</div>
	</a>  
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<ul class="ts-profile-nav">
			<?php
			  $querruserprofile=mysqli_query($conn,"select * from admin");
			  $rowadmin=mysqli_fetch_array($querruserprofile);
			  $rowadmin=$rowadmin['firstname'];
			
			?>
			<li class="ts-account">
				<a href="#"><?php echo $rowadmin; ?> <i class="fa fa-angle-down hidden-side"></i></a>
				<ul>
					<li><a href="change-password.php">Change Password</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
	</div>
