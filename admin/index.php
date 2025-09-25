<?php
require('includes/connection.inc.php');

$errors = [];
if (isset($_POST['login'])) {
    $submittedToken = $_POST['csrf_token'] ?? null;
    if (!validateCsrfToken($submittedToken)) {
        $errors[] = 'Invalid session. Please try again.';
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please provide both username and password.';
    }

    if (!$errors) {
        $stmt = $con->prepare('SELECT id, UserName, Password FROM admin WHERE UserName = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin) {
            $isValid = password_verify($password, $admin['Password']);
            if (!$isValid && isLegacyMd5Hash($admin['Password']) && hash_equals($admin['Password'], md5($password))) {
                $isValid = true;
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upgradeStmt = $con->prepare('UPDATE admin SET Password = ? WHERE id = ?');
                $upgradeStmt->bind_param('si', $newHash, $admin['id']);
                $upgradeStmt->execute();
            }

            if ($isValid) {
                session_regenerate_id(true);
                $_SESSION['id'] = (int) $admin['id'];
                $_SESSION['admin'] = $admin['UserName'];
                echo '<script>alert("Login success"); window.location.href="dashboard.php";</script>';
            } else {
                $errors[] = 'Invalid username or password';
            }
        } else {
            $errors[] = 'Invalid username or password';
        }
    }
}

if ($errors) {
    $message = escape(implode('\n', $errors));
    echo "<script>alert('" . addslashes($message) . "');</script>";
}

$csrfToken = generateCsrfToken();
?>
<!doctype html>
<html lang="en" class="no-js">

<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Car Rental Portal | Admin Login</title>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <link rel="stylesheet" href="css/fileinput.min.css">
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="css/style.css">
</head>

<body>

        <div class="login-page bk-img" style="background-image: url(img/vehicleimages/bg.png);">
                <div class="form-content">
                        <div class="container">
                                <div class="row">
                                        <div class="col-md-6 col-md-offset-3">
                                                <h1 class="text-center text-bold text-light mt-4x">Sign in</h1>
                                                <div class="well row pt-2x pb-3x bk-light">
                                                        <div class="col-md-8 col-md-offset-2">
                                                                <form method="post">

                                                                        <label for="username" class="text-uppercase text-sm">Your Username </label>
                                                                        <input type="text" placeholder="Username" id="username" name="username" class="form-control mb" required>

                                                                        <label for="password" class="text-uppercase text-sm">Password</label>
                                                                        <input type="password" placeholder="Password" id="password" name="password" class="form-control mb" autocomplete="current-password" required>

                                                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

                                                                        <button class="btn btn-primary btn-block" name="login" type="submit">LOGIN</button>

                                                                </form>
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
