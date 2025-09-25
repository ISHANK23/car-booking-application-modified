<?php
if (empty($_SESSION['admin'])) {
    echo '<script>alert("Please login"); window.location.href="index.php";</script>';
    exit;
}
