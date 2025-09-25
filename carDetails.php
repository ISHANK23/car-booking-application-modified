<?php
require('inc/header.inc.php');
require_once('inc/connection.inc.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Invalid vehicle selected.'}).then(() => { window.location.href = 'cars.php'; });</script>";
    require('inc/footer.inc.php');
    exit;
}

$stmt = $con->prepare('SELECT * FROM cars WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();

if (!$car) {
    echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Vehicle not found.'}).then(() => { window.location.href = 'cars.php'; });</script>";
    require('inc/footer.inc.php');
    exit;
}

if (isset($_POST['book'])) {
    if (!isset($_SESSION['username'])) {
        echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Please login before making a booking.'});</script>";
    } else {
        $submittedToken = $_POST['csrf_token'] ?? null;
        if (!validateCsrfToken($submittedToken)) {
            echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Invalid session, please try again.'});</script>";
        } else {
            $username = $_SESSION['username'];
            $fromDateInput = trim($_POST['fromDate'] ?? '');
            $toDateInput = trim($_POST['toDate'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $status = 0;

            $fromDate = DateTime::createFromFormat('d/m/Y', $fromDateInput) ?: DateTime::createFromFormat('Y-m-d', $fromDateInput);
            $toDate = DateTime::createFromFormat('d/m/Y', $toDateInput) ?: DateTime::createFromFormat('Y-m-d', $toDateInput);

            if (!$fromDate || !$toDate) {
                echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Please provide valid dates.'});</script>";
            } elseif ($toDate < $fromDate) {
                echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'The end date must be after the start date.'});</script>";
            } else {
                $message = mb_substr(strip_tags($message), 0, 500);

                $insert = $con->prepare('INSERT INTO carbooking(userEmail, VehicleId, FromDate, ToDate, message, Status) VALUES(?, ?, ?, ?, ?, ?)');
                $fromDateFormatted = $fromDate->format('Y-m-d');
                $toDateFormatted = $toDate->format('Y-m-d');
                $insert->bind_param('sisssi', $username, $id, $fromDateFormatted, $toDateFormatted, $message, $status);

                try {
                    $insert->execute();
                    echo '<script>swal({
                        title: "Booking Success!",
                        text: "Redirecting in 2 seconds.",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false
                      }, function(){
                            window.location.href = "my_account.php";
                      });</script>';
                } catch (mysqli_sql_exception $exception) {
                    error_log('Booking failed: ' . $exception->getMessage());
                    echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Unable to complete booking at this time.'});</script>";
                }
            }
        }
    }
}

$csrfToken = generateCsrfToken();
?>
<section class="car-details">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div id="custCarousel" class="carousel slide" data-ride="carousel" align="center">
               <!-- slides -->
               <div class="carousel-inner">
                  <div class="carousel-item active"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage1']); ?>" alt="Vehicle image"> </div>
                  <div class="carousel-item"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage2']); ?>" alt="Vehicle image"> </div>
                  <div class="carousel-item"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage3']); ?>" alt="Vehicle image"> </div>
                  <div class="carousel-item"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage4']); ?>" alt="Vehicle image"> </div>
               </div>
               <!-- Left right --> <a class="carousel-control-prev" href="#custCarousel" data-slide="prev"> <span class="carousel-control-prev-icon"></span> </a> <a class="carousel-control-next" href="#custCarousel" data-slide="next"> <span class="carousel-control-next-icon"></span> </a> <!-- Thumbnails -->
               <ol class="carousel-indicators list-inline">
                  <li class="list-inline-item active"> <a id="carousel-selector-0" class="selected" data-slide-to="0" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage1']); ?>" class="img-fluid" alt="Vehicle image"> </a> </li>
                  <li class="list-inline-item"> <a id="carousel-selector-1" data-slide-to="1" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage2']); ?>" class="img-fluid" alt="Vehicle image"> </a> </li>
                  <li class="list-inline-item"> <a id="carousel-selector-2" data-slide-to="2" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage3']); ?>" class="img-fluid" alt="Vehicle image"> </a> </li>
                  <li class="list-inline-item"> <a id="carousel-selector-3" data-slide-to="3" data-target="#custCarousel"> <img src="<?php echo 'admin/img/vehicleimages/' . escape($car['Vimage4']); ?>" class="img-fluid" alt="Vehicle image"> </a> </li>
               </ol>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="details">
   <div class="container">
   <h2 class="text-center"><?php echo escape($car['VehiclesTitle']); ?></h2>
   <div class="row">
      <div class="col-3">
         <h5>Registered Year</h5>
         <i class="fas fa-calendar-alt fa-3x"></i>
         <?php echo escape($car['ModelYear']); ?>
      </div>
      <div class="col-3">
         <h5>Fuel Type</h5>
         <i class="fas fa-gas-pump fa-3x"></i>
         <?php echo escape($car['FuelType']); ?>
      </div>
      <div class="col-3">
         <h5>No of Seats</h5>
         <i class="fas fa-user-plus fa-3x"></i>
         <?php echo escape($car['SeatingCapacity']); ?>
      </div>
      <div class="col-3">
         <h5>Price Per Day</h5>
         <i class="fas fa-dollar-sign fa-3x"></i>
         <?php echo 'Rs ' . escape($car['PricePerDay']); ?>
      </div>
   </div>
</section>
<section class="book-now">
   <!-- Button trigger modal -->
   <div class="col-md-10 text-right">
      <button type="button" class="btn btn-success" data-toggle="modal"
         data-target="<?php echo isset($_SESSION['username']) ? '#exampleModalScrollable' : '#warning'; ?>">
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
                     <label for="fromDate">From Date</label>
                     <input data-date-format="dd/mm/yyyy" name="fromDate" id="fromDate" class="form-control" required>
                  </div>
                  <div class="form-group">
                     <label for="toDate">To Date</label>
                     <input data-date-format="dd/mm/yyyy" name="toDate" id="toDate" class="form-control" required>
                  </div>
                  <div class="form-group">
                     <label for="message">Message</label>
                     <textarea class="form-control" name="message"  id="message" rows="3" maxlength="500"></textarea>
                  </div>
                  <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
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
         <div class="card-header">Options of <?php echo escape($car['VehiclesTitle']); ?> </div>
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
                     <td><?php echo ((int) $car['AirConditioner'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
                  <tr>
                     <td>Power Door Locks</td>
                     <td><?php echo ((int) $car['PowerDoorLocks'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
                  <tr>
                     <td>Anti Lock BrakingSystem</td>
                     <td><?php echo ((int) $car['AntiLockBrakingSystem'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
                  <tr>
                     <td>Brake Assist</td>
                     <td><?php echo ((int) $car['BrakeAssist'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
                  <tr>
                     <td>Power Steering</td>
                     <td><?php echo ((int) $car['PowerSteering'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
                  <tr>
                     <td>Driver Air Bag</td>
                     <td><?php echo ((int) $car['DriverAirbag'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
                  <tr>
                     <td>Passenger Air Bag</td>
                     <td><?php echo ((int) $car['PassengerAirbag'] === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'; ?></td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</section>

<?php
   require('inc/footer.inc.php');
?>
