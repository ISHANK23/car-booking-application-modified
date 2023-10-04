<?php
   require('inc/header.inc.php');
   require('inc/connection.inc.php');

   if(!isset($_SESSION['username'])){
    echo '<script>swal({
        title: "Please login to the account!",
        text: "Redirecting in 2 seconds.",
        type: "error",
        timer: 2000,
        showConfirmButton: false
      }, function(){
            window.location.href = "login.php";
      });</script>';
   }
?>

<section class="my-account">

    <div class="container">
    <h2>My Bookings</h2>
    <div class="row">
  <div class="col-2">
  <div class="list-group">
  <a href="my_account.php" class="list-group-item list-group-item-action active">My Bookings</a>
  <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
</div>
  </div>
  <div class="col-10">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">Car</th>
      <th scope="col">Image</th>
      <th scope="col">From Date</th>
      <th scope="col">To Date</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $userEmail=$_SESSION['username'];
      $sql="SELECT  cars.VehiclesTitle,cars.Vimage1,carbooking.VehicleId,carbooking.FromDate,carbooking.ToDate,carbooking.Status FROM cars,carbooking WHERE cars.id=carbooking.VehicleId AND userEmail='$userEmail'";
      $res=mysqli_query($con,$sql);

      while($row=mysqli_fetch_assoc($res)){
    ?>
    <tr>
      <td><?php echo $row['VehiclesTitle']?></td>
      <td><img class="card-img-top" src="admin/img/vehicleimages/<?php echo $row['Vimage1'];?>" alt="" srcset=""></td>
      <td><?php echo $row['FromDate']?></td>
      <td><?php echo $row['ToDate']?></td>
      <td><?php 
      if($row['Status']==0){
        echo "<p class='text-danger'>Pending</p>";
      }else{
        echo "<p class='text-success'>Confirm</p>";
      }?>
      </td>
    </tr>
    <?php }?>
  </tbody>
</table>
  </div>
</div>
    
    </div>
</section>




<?php
   require('inc/footer.inc.php');
?>