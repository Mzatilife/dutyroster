<div class="brand clearfix">
	<a href="dashboard.php" style="float:left;font-size: 20px; margin:10px 55px; color:#fff">
	   <div>SMART SOLID WASTE MANAGEMENT SYSTEM(Driver)</div>
	</a>  
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<ul class="ts-profile-nav">
			<?php
			  $querruserprofile=mysqli_query($con,"select * from driver");
			  $row=mysqli_fetch_array($querruserprofile);
			  $rowuser=$row['firstname'];
			
			?>
			<li class="ts-account">
				<a href="#"><img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt=""><?php echo $rowuser; ?> <i class="fa fa-angle-down hidden-side"></i></a>
				<ul>
					<li><a href="change-password.php">Change Password</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
	</div>
