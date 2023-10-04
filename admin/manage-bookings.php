<?php

error_reporting(0);
include('includes/connection.inc.php');
require('includes/session.php');
if(isset($_GET['del']))
	{
$id=$_GET['del'];
$sql = "DELETE FROM cars WHERE VehicleId='$id'";
$res=mysqli_query($con,$sql);
if($res){
$msg="Vehicle  record deleted successfully";
}
}

if (isset($_GET['aid'])){
$aid=$_GET['aid'];
$status=1;
$sql="UPDATE carbooking SET Status='$status' WHERE VehicleId='$aid'";
$res=mysqli_query($con,$sql);
if($res){
	$msg="Booking confirmed";
}	
}

if (isset($_GET['cid'])){
	$cid=$_GET['cid'];
	$status=0;
	$sql="UPDATE carbooking SET Status='$status' WHERE VehicleId='$cid'";
	$res=mysqli_query($con,$sql);
	if($res){
		$msg="Booking cancelled";
	}	
	}

	if (isset($_GET['rid'])){
		$rid=$_GET['rid'];
		$status=0;
		$sql="DELETE FROM carbooking WHERE VehicleId='$rid'";
		$res=mysqli_query($con,$sql);
		if($res>0){
			$msg="Booking Removed";
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
	
	<title>Car Rental Portal |Admin Manage testimonials   </title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
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
					<div class="col-md-12">

						<h2 class="page-title">Manage Bookings</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">Bookings Info</div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
<?php
	$sql="SELECT cars.id,cars.VehiclesTitle,carbooking.userEmail,carbooking.FromDate,carbooking.ToDate,carbooking.message,carbooking.Status FROM cars,carbooking WHERE cars.id=carbooking.VehicleId";
	$res=mysqli_query($con,$sql);
	while($row=mysqli_fetch_assoc($res)){	
?>	

										<tr>
											<td></td>
											<td><?php echo $row['userEmail']?></td>
											<td><?php echo $row['VehiclesTitle']?></td>
											<td><?php echo $row['FromDate']?></td>
											<td><?php echo $row['ToDate']?></td>
											<td><?php echo $row['message']?></td>
											<td><?php 
if($row['Status']==0)
{
echo htmlentities('Not Confirmed yet');
} else if ($row['Status']==1) {
echo htmlentities('Confirmed');
}
 else{
 	echo htmlentities('Cancelled');
 }
?></td>
											
<td><a href="manage-bookings.php?aid=<?php echo $row['id'];?>" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a> /


<a href="manage-bookings.php?cid=<?php echo $row['id'];?>" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a> / 
<a href="manage-bookings.php?rid=<?php echo $row['id'];?>" onclick="return remove('Do you really want to Remove this Booking')"> Remove</a>
</td>

										</tr>
									<?php }?>
										
									</tbody>
								</table>

						

							</div>
						</div>

					

					</div>
				</div>

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
</body>
</html>
