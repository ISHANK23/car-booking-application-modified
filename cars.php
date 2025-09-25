<?php
require('inc/header.inc.php');
require_once('inc/connection.inc.php');
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
            $sql = "SELECT * FROM cars";
            $res = $con->query($sql);
            while ($row = $res->fetch_assoc())
            {
            ?>
         <div class="col">
            <div class="showCars">
               <div class="card" style="width: 20rem;">
                  <img class="card-img-top" src="admin/img/vehicleimages/<?php echo escape($row['Vimage1']); ?>" alt="<?php echo escape($row['VehiclesTitle']); ?>">
                  <div class="card-body">
                  <div class="row">
                           <div class="col"><h5 class="card-title"><?php echo escape($row['VehiclesTitle']); ?></h5></div>
                           <div class="col">Price Per Day<h5 class="card-title"><?php echo 'Rs ' . escape($row['PricePerDay']); ?></h5></div>
                     </div>

                     <p class="card-text"></p>
                     <a href="carDetails.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-primary">Book Now</a>
                     <a href="carDetails.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-success">Details</a>
                  </div>
               </div>
            </div>
         </div>
         <?php
            }
            ?>
      </div>
   </div>
</section>
<?php
   require('inc/footer.inc.php');
?>
