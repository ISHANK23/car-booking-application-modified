<?php
require('inc/header.inc.php');
require_once('inc/connection.inc.php');

if (!isset($_SESSION['username'])) {
    echo '<script>swal({
        title: "Please login to the account!",
        text: "Redirecting in 2 seconds.",
        type: "error",
        timer: 2000,
        showConfirmButton: false
      }, function(){
            window.location.href = "login.php";
      });</script>';
    require('inc/footer.inc.php');
    exit;
}

$userEmail = $_SESSION['username'];
$bookings = [];

$stmt = $con->prepare('SELECT cars.VehiclesTitle, cars.Vimage1, carbooking.VehicleId, carbooking.FromDate, carbooking.ToDate, carbooking.Status FROM cars JOIN carbooking ON cars.id = carbooking.VehicleId WHERE carbooking.userEmail = ? ORDER BY carbooking.FromDate DESC');
$stmt->bind_param('s', $userEmail);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
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
    <?php foreach ($bookings as $row): ?>
    <tr>
      <td><?php echo escape($row['VehiclesTitle']); ?></td>
      <td><img class="card-img-top" src="admin/img/vehicleimages/<?php echo escape($row['Vimage1']); ?>" alt="Vehicle image"></td>
      <td><?php echo escape($row['FromDate']); ?></td>
      <td><?php echo escape($row['ToDate']); ?></td>
      <td>
        <?php if ((int) $row['Status'] === 0): ?>
          <p class='text-danger'>Pending</p>
        <?php else: ?>
          <p class='text-success'>Confirm</p>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
  </div>
</div>

    </div>
</section>



<?php
    require('inc/footer.inc.php');
?>
