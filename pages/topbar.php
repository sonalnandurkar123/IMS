
<?php   

$stmt = $conn->prepare("SELECT * FROM company_details");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>




<div class="top_nav menu_fixed">
	<div class="nav_menu">
		<div class="nav toggle">
			<a id="menu_toggle"><i class="fa fa-bars"></i></a>
		</div>
		<nav class="nav navbar-nav">
			<ul class=" navbar-right">
			    <div class="container-fluid">
					<div class="row">
						<div class="col-sm-8 ">
						<h4 class="text-center" style="color:White;"><?php echo $row[0]['company_name'];  ?></h4>
						</div>
						<div class="col-sm-4 ">
						<div class="nav-item dropdown open" style="float:right;">
							<a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
								<img src="images/img.jpg" alt="">Admin
							</a>
							<div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
								<a class="dropdown-item"  href="javascript:;"> Profile</a>
								<a class="dropdown-item"  href="../logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
							</div>
							
							</div>
						</div>
					</div>
				</div>	
				<!--<div class="nav-item" ><h4><?php echo $row[0]['company_name'];  ?></h4>
				<li class="nav-item dropdown open" style="padding-left: 15px;">
					<a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
						<img src="images/img.jpg" alt="">Admin
					</a>
					<div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
						<a class="dropdown-item"  href="javascript:;"> Profile</a>
						<a class="dropdown-item"  href="../logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
					</div>
				</li>
				</div>-->
			</ul>
			
			
		</nav>
	</div>
</div>