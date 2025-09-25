<?php
require('inc/header.inc.php');
require_once('inc/connection.inc.php');

$errors = [];
if (isset($_POST['login'])) {
    $submittedToken = $_POST['csrf_token'] ?? null;
    if (!validateCsrfToken($submittedToken)) {
        $errors[] = 'Invalid session, please refresh the page and try again.';
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please enter username and password';
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }

    if (!$errors) {
        $stmt = $con->prepare('SELECT id, username, email, password, oauth_provider FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (!empty($user['oauth_provider']) && empty($user['password'])) {
                $errors[] = 'Please sign in with your ' . escape($user['oauth_provider']) . ' account.';
            } else {
                $isValidPassword = false;
                if (!empty($user['password'])) {
                    $isValidPassword = password_verify($password, $user['password']);
                    if (!$isValidPassword && isLegacyMd5Hash($user['password']) && hash_equals($user['password'], md5($password))) {
                        $isValidPassword = true;
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $upgradeStmt = $con->prepare('UPDATE users SET password = ? WHERE id = ?');
                        $upgradeStmt->bind_param('si', $newHash, $user['id']);
                        $upgradeStmt->execute();
                    }
                }

                if ($isValidPassword) {
                    session_regenerate_id(true);
                    $_SESSION['id'] = (int) $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['email'];
                    $_SESSION['user_display_name'] = $user['username'];
                    echo '<script>swal({
                        title: "Login Success!",
                        text: "Redirecting in 2 seconds.",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false
                      }, function(){
                            window.location.href = "my_account.php";
                      });</script>';
                } else {
                    $errors[] = 'Please check your email and password';
                }
            }
        } else {
            $errors[] = 'Please check your email and password';
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
</script>
<!--Login form-->
<section class="login">
<div class="container">
<form method="POST">
  <div class="form-group col-lg-6">
    <label for="emailInput">Email address</label>
    <input type="email" class="form-control" name="username" id="emailInput" placeholder="Enter your email" required>
  </div>

  <div class="form-group col-lg-6">
    <label for="passwordInput">Password</label>
    <input type="password" class="form-control"  name="password" id="passwordInput" placeholder="Enter your password" autocomplete="current-password" required>
  </div>

  <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

  <div class="form-group col-lg-6">
    <button type="submit" name="login" class="btn btn-primary">Login</button>
  </div>

 <div class="container-fluid">
 <p class="text-secondary">Not registered? <a href="register.php">Create Account</a></p>
 <p class="text-secondary">Or sign in with <a href="oauth/google-login.php" class="btn btn-link p-0 align-baseline">Google</a></p>
 </div>

</form>
</div>
</section>


<?php
    require('inc/footer.inc.php');
?>
