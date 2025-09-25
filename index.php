<?php
   require('inc/header.inc.php');
   require('inc/connection.inc.php');
   ?>
<!--Slider Section-->
<section class="slider">
   <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-bs-interval="500">
      <ol class="carousel-indicators">
         <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
         <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
         <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
         <div class="carousel-item active">
            <img src="img/slider3.jpg" alt="...">
            <div class="carousel-caption d-none d-md-block">
               <h1>Fast & Easy Way To Rent A Car</h1>
               <p>
                  Fast & Easy Way To Rent A Car
                  A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts
               </p>
            </div>
         </div>
         <div class="carousel-item">
            <img src="img/slider4.jpg" alt="...">
            <div class="carousel-caption d-none d-md-block">
               <h1>Fast & Easy Way To Rent A Car</h1>
               <p>
                  Fast & Easy Way To Rent A Car
                  A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts
               </p>
            </div>
         </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
      </a>
   </div>
</section>
<!--Show Cars-->
<section class="cars">
   <p>WHAT WE OFFER</p>
   <div class="title">
      <h1>Featured Cars</h1>
   </div>
   <div class="container">
      <div class="row no-gutter">
         <?php
            $sql="SELECT * FROM cars LIMIT 3";
            $res=mysqli_query($con,$sql);
            while($row=mysqli_fetch_assoc($res))
            {
               
            ?>
         <div class="col">
            <div class="showCars">
               <div class="card" style="width: 20rem;">
                  <img class="img-fluid img-thumbnail" style="" src="admin/img/vehicleimages/<?php echo escape($row['Vimage1']); ?>" alt="Card image cap">
                  <div class="card-body">
                     <div class="row">
                           <div class="col"><h5 class="card-title"><?php echo escape($row['VehiclesTitle']); ?></h5></div>
                           <div class="col">Price Per Day<h5 class="card-title"><?php echo 'Rs ' . escape($row['PricePerDay']); ?></h5></div>
                     </div>
                     <p class="card-text"></p>
                     <a href="carDetails.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-primary">Book Now</a>
                     
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
                                 <form action="carbook.php" method="POST">
                                   
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
                                       <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                 <button type="submit" name="book" class="btn btn-primary">Submit</button>
                                 </form>
                              </div>
                              <div class="modal-footer">
                                 
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
<!--Welcome section-->
<section class="welcome">
   <div class="row">
      <div class="top-img">
         <img src="img/welcomeimg.jpg" alt="" srcset="">
      </div>
      <div class="left-side">
      </div>
      <div class="right-side">
         <h1>Welcome to CARBOOK</h1>
         <p>Welcome to CARBOOK Rent A Car website. CARBOOK Rent A Car team is a happy team which leads to happy customers. As such we treat all our team members from top to bottom with the respect they deserve.
         </p>
         <p>
         Our team works hard to satisfy our customers.Having experience of 18 years, we are dedicated to providing the highest standards of service ranging from low budget cars to luxury coaches that could meet all your transport needs within Sri Lanka.
         </p>
         <p>All customers will be treated fairly. We provide the clearest rental contract in the market and every sales agent explains the rental agreement line by line.CARBOOK Rent A Car offers extremely competitive rates in Sri Lanka to plan your time, using the resources that we have provided.</p>
      </div>
   </div>
</section>
<section class="services">
   <p id="txt">SERVICES</p>
   <div class="title">
      <h1>Our Latest Services</h1>
   </div>
   <div class="row">
      <div class="box1">
         <div class="icon">
         </div>
         <img src="img/wedding-car.png" alt="">
         <div class="title">
            <h5>Wedding Ceremony</h5>
         </div>
         <div class="description">
            <p></p>
         </div>
      </div>
      <div class="box2">
         <div class="icon">
         </div>
         <img src="img/transportation.png" alt="">
         <div class="title">
            <h5>City Transfer</h5>
         </div>
         <div class="description">
            <p></p>
         </div>
      </div>
      <div class="box3">
         <div class="icon">
         </div>
         <img src="img/car.png" alt="">
         <div class="title">
            <h5>Airport Transfer</h5>
         </div>
         <div class="description">
            <p></p>
         </div>
      </div>
      <div class="box4">
         <div class="icon">
         </div>
         <img src="img/transportation.png" alt="">
         <div class="title">
            <h5>Whole City Tour</h5>
         </div>
         <div class="description">
            <p></p>
         </div>
      </div>
   </div>
</section>
<!-- Footer -->
<div class="mt-5 pt-5 pb-5 footer">
   <div class="container">
      <div class="row">
         <div class="col-lg-5 col-xs-12 about-company">
            <span class="text1">CAR</span><span class="text2">BOOK</span>
            <p class="pr-5 text-white-50"> Welcome to Newanjith Rent A Car website.As such we treat all our team members from top to bottom with the respect they deserve. Our team works hard to satisfy our customers. </p>
            <p><a href="#"><i class="fa fa-facebook-square mr-1"></i></a><a href="#"><i class="fa fa-linkedin-square"></i></a></p>
         </div>
         <div class="col-lg-3 col-xs-12 links">
            <h4 class="mt-lg-0 mt-sm-3">Links</h4>
            <ul class="m-0 p-0">
               <li><a href="index.php">Home</a></li>
               <li><a href="services.php">Services</a></li>
               <li><a href="my_account.php">My Account</a></li>
               <li><a href="login.php">Login</a></li>
               <li><a href="register.php">Register</a></li>
               
            </ul>
         </div>
         <div class="col-lg-4 col-xs-12 location">
            <h4 class="mt-lg-0 mt-sm-4">Location</h4>
            <p>Address: 56, Kadawatha Road, Kalubowila, Dehiwala</p>
            <p class="mb-0"><i class="fa fa-phone mr-3"></i> 071 4402885 </p>
            <p><i class="fa fa-envelope-o mr-3"></i>hello@carbook.lk</p>
         </div>
      </div>
      <div class="row mt-5">
         <div class="col copyright">
            <p class=""><small class="text-white-50">© 2022. All Rights Reserved.</small></p>
         </div>
      </div>
   </div>
</div>
<script>
   $('.carousel').carousel({
   interval: 2000
   })
   
   $('#').spSticyheader({
   scrollHeader: 200
   })
</script>
<?php
   require('inc/footer.inc.php');
   ?>