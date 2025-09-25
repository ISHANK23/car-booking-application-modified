<?php
require('inc/header.inc.php');
require_once('inc/connection.inc.php');

$errors = [];
if (isset($_POST['submit'])) {
    $submittedToken = $_POST['csrf_token'] ?? null;
    if (!validateCsrfToken($submittedToken)) {
        $errors[] = 'Invalid session, please refresh and try again.';
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '') {
        $errors[] = 'Please enter your name.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($phone === '') {
        $errors[] = 'Please enter your phone number.';
    }

    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must be at least 8 characters and contain letters and numbers.';
    }

    if (!hash_equals($password, $password2)) {
        $errors[] = "Passwords don't match.";
    }

    if (!$errors) {
        $checkStmt = $con->prepare('SELECT id, oauth_provider FROM users WHERE email = ? LIMIT 1');
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        $existingUser = $checkStmt->get_result()->fetch_assoc();

        if ($existingUser) {
            if (!empty($existingUser['oauth_provider'])) {
                $errors[] = 'An account already exists via ' . escape($existingUser['oauth_provider']) . '. Please sign in using that method.';
            } else {
                $errors[] = 'Email already registered!';
            }
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare('INSERT INTO users(username, email, phone, password) VALUES(?, ?, ?, ?)');
            $stmt->bind_param('ssss', $username, $email, $phone, $hash);
            $stmt->execute();

            echo '<script>swal({
                title: "Registration successful!",
                text: "Redirecting in 2 seconds.",
                type: "success",
                timer: 2000,
                showConfirmButton: false
              }, function(){
                    window.location.href = "login.php";
              });</script>';
        }
    }
}

if ($errors) {
    $message = escape(implode('\n', $errors));
    echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: '{$message}'});</script>";
}

$csrfToken = generateCsrfToken();
?>

<script>
  if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
function validate()
{
  return true;
}

function resetForm(){
document.getElementById("form").reset();
}
</script>

<!--Register form-->
<section class="register">
<div class="container">
<form id="form" method="POST">
  <div class="form-group col-lg-6">
    <label for="username">Name</label>
    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your name" required>
  </div>

  <div class="form-group col-lg-6">
    <label for="email">Email address</label>
    <input type="email" class="form-control" name="email"  id="email" placeholder="Enter your email" required>
  </div>

  <div class="form-group col-lg-6">
    <label for="phone">Phone</label>
    <input type="text" class="form-control" name="phone"  id="phone" placeholder="Enter your phone number" required>
  </div>

  <div class="form-group col-lg-6">
    <label for="password">Password</label>
    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" autocomplete="new-password" required>
  </div>

  <div class="form-group col-lg-6">
    <label for="password2">Confirm-password</label>
    <input type="password" class="form-control" name="password2" id="password2" placeholder="Re-Enter your password" autocomplete="new-password" required>
  </div>

  <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

  <div class="form-group col-lg-6">
    <button type="submit" name="submit"  class="btn btn-success">Register</button>
  </div>

  <div class="container-fluid">
 <p class="text-secondary">Already Registered <a href="login.php">Login</a></p>
 </div>

</form>
<div class="error" id="error"></div>
</div>
</section>


<?php
    require('inc/footer.inc.php');
?>
