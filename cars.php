<?php
   require('inc/header.inc.php');
   require('inc/connection.inc.php');
   ?>
<section class="hero">
   <div class="jumbotron text-white" style="background-image: url(img/slider4.jpg); opacity:23;">
      <h1 class="display-4">Choose Your Car</h1>
   </div>
</section>
<!--Show Cars-->
<section class="cars">
   <div class="container">
      <div class="row no-gutter">
         <?php
            $sql="SELECT * FROM cars";
            $res=mysqli_query($con,$sql);
            while($row=mysqli_fetch_assoc($res))
            {
            ?>
         <div class="col">
            <div class="showCars">
               <div class="card" style="width: 20rem;">
                  <img class="card-img-top" src="admin/img/vehicleimages/<?php echo $row['Vimage1'];?>" alt="Card image cap">
                  <div class="card-body">
                  <div class="row">
                           <div class="col"><h5 class="card-title"><?php echo $row['VehiclesTitle'];?></h5></div>
                           <div class="col">Price Per Day<h5 class="card-title"><?php echo 'Rs '.$row['PricePerDay'];?></h5></div>
                     </div>
                     
                     <p class="card-text"></p>
                     <a href="carDetails.php?id=<?php echo $row['id']?>" class="btn btn-primary">Book Now</a>
                     <a href="carDetails.php?id=<?php echo $row['id']?>" class="btn btn-success">Details</a>
                  </div>
               </div>
            </div>
         </div>
         <?php
            }
            ?>
      </div>
   </div>
   </div>
   </div>
</section>
<?
   require('inc/footer.inc.php');
   ?>