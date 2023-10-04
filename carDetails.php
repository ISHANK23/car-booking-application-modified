<?php
   require('inc/header.inc.php');
   require('inc/connection.inc.php');
   ?>
<?php
   if(isset($_GET['id'])) {
      $id=$_GET['id'];
   
      $sql="SELECT * FROM cars WHERE id='$id'";
      $res=mysqli_query($con,$sql);
      $row=mysqli_fetch_assoc($res);

      if(isset($_POST['book']))
      {
      $username=$_SESSION['username'];
      $vehicleId=$id;
      $fromDate=$_POST['fromDate'];
      $toDate=$_POST['toDate'];
      $message=$_POST['message'];
      $status=0;
      $sql="INSERT INTO carbooking(userEmail,VehicleId,FromDate,ToDate,message,Status) 
      VALUES('$username','$vehicleId','$fromDate','$toDate','$message','$status')";
      $res=mysqli_query($con,$sql);
      if(!$res){
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Email already registered!',
        })
          </script>";
      }else{
          echo '<script>swal({
              title: "Booking Success!",
              text: "Redirecting in 2 seconds.",
              type: "success",
              timer: 2000,
              showConfirmButton: false
            }, function(){
                  window.location.href = "my_account.php";
            });</script>';
      }
      //header('Location:register.php');
      }
   ?>
<section class="car-details">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div id="custCarousel" class="carousel slide" data-ride="carousel" align="center">
               <!-- slides -->
               <div class="carousel-inner">
                  <div class="carousel-item active"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage1']?>" alt="Hills"> </div>
                  <div class="carousel-item"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage2']?>" alt="Hills"> </div>
                  <div class="carousel-item"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage3']?>" alt="Hills"> </div>
                  <div class="carousel-item"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage4']?>" alt="Hills"> </div>
               </div>
               <!-- Left right --> <a class="carousel-control-prev" href="#custCarousel" data-slide="prev"> <span class="carousel-control-prev-icon"></span> </a> <a class="carousel-control-next" href="#custCarousel" data-slide="next"> <span class="carousel-control-next-icon"></span> </a> <!-- Thumbnails -->
               <ol class="carousel-indicators list-inline">
                  <li class="list-inline-item active"> <a id="carousel-selector-0" class="selected" data-slide-to="0" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage1']?>" class="img-fluid"> </a> </li>
                  <li class="list-inline-item"> <a id="carousel-selector-1" data-slide-to="1" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage2']?>" class="img-fluid"> </a> </li>
                  <li class="list-inline-item"> <a id="carousel-selector-2" data-slide-to="2" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage3']?>" class="img-fluid"> </a> </li>
                  <li class="list-inline-item"> <a id="carousel-selector-2" data-slide-to="3" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/'.$row['Vimage4']?>" class="img-fluid"> </a> </li>
               </ol>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="details">
   <div class="container">
   <h2 class="text-center"><?php echo $row['VehiclesTitle'];?></h2>
   <div class="row">
      <div class="col-3">
         <h5>Registered Year</h5>
         <i class="fas fa-calendar-alt fa-3x"></i>
         <?php echo $row['ModelYear'];?>
      </div>
      <div class="col-3">
         <h5>Fuel Type</h5>
         <i class="fas fa-gas-pump fa-3x"></i>
         <?php echo $row['FuelType'];?>
      </div>
      <div class="col-3">
         <h5>No of Seats</h5>
         <i class="fas fa-user-plus fa-3x"></i>
         <?php echo $row['SeatingCapacity'];?>
      </div>
      <div class="col-3">
         <h5>Price Per Day</h5>
         <i class="fas fa-dollar-sign fa-3x"></i>
         <?php echo"Rs ".$row['PricePerDay'];?>
      </div>
   </div>
</section>
<section class="book-now">
   <!-- Button trigger modal -->
   <div class="col-md-10 text-right">
      <button type="button" class="btn btn-success" data-toggle="modal" 
         data-target="<?php 
            if(isset($_SESSION['username']))
            {
               echo '#exampleModalScrollable';
            }else{
               echo '#warning';
            } ?>">
      Book Now
      </button>
   </div>
   <!-- Modal -->
   <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalScrollableTitle">Booking Information</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <form method="POST">
                  <div class="form-group">
                     <label for="exampleInputPassword1">From Date</label>
                     <input data-date-format="dd/mm/yyyy" name="fromDate" id="datepicker" class="form-control">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">To Date</label>
                     <input data-date-format="dd/mm/yyyy" name="toDate" id="datepicker2" class="form-control">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Message</label>
                     <textarea class="form-control" name="message"  id="exampleFormControlTextarea1" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" name="book" class="btn btn-primary">Submit</button>
                  </div>
               </form>
            </div>
            
         </div>
      </div>
   </div>
   <div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <h2 class="text-danger">Please login to the Account</h2>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="features">
   <div class="container">
      <h2>Feature of car</h2>
      <div class="card">
         <div class="card-header">Options of <?php echo $row['VehiclesTitle'];?> </div>
         <div class="card-body">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th scope="col">Features</th>
                     <th scope="col">Available</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>AC</td>
                     <td><?php if($row['AirConditioner']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Power Door Locks</td>
                     <td><?php if($row['PowerDoorLocks']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Anti Lock BrakingSystem</td>
                     <td><?php if($row['AntiLockBrakingSystem']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Brake Assist</td>
                     <td><?php if($row['BrakeAssist']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Power Steering</td>
                     <td><?php if($row['PowerSteering']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Driver Air Bag</td>
                     <td><?php if($row['DriverAirbag']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Passenger Air Bag</td>
                     <td><?php if($row['PassengerAirbag']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Power Windows</td>
                     <td><?php if($row['PowerWindows']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>CD Player</td>
                     <td><?php if($row['CDPlayer']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Central Locking</td>
                     <td><?php if($row['CentralLocking']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Crash Sensor</td>
                     <td><?php if($row['CrashSensor']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
                  <tr>
                     <td>Leather Seats</td>
                     <td><?php if($row['LeatherSeats']==1){echo '<i class="fas fa-check"></i>';}else{ echo '<i class="fas fa-times"></i>';}?></td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</section>
<?php
   }
   ?>
<?php
   require('inc/footer.inc.php');
   ?>
<script type="text/javascript">
   $('#datepicker').datepicker({
       weekStart: 1,
       daysOfWeekHighlighted: "6,0",
       autoclose: true,
       todayHighlight: true,
   });
   $('#datepicker').datepicker("setDate", new Date());
   
   $('#datepicker2').datepicker({
       weekStart: 1,
       daysOfWeekHighlighted: "6,0",
       autoclose: true,
       todayHighlight: true,
   });
   $('#datepicker2').datepicker("setDate", new Date());
</script>