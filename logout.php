<?php
 require('inc/header.inc.php');
    require('inc/connection.inc.php');
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    unset($_SESSION['user_display_name']);
    unset($_SESSION['oauth_provider']);
    session_destroy();
    echo '<script>swal({
        title: "You are loggin out!",
        text: "Redirecting in 2 seconds.",
        type: "success",
        timer: 2000,
        showConfirmButton: false
      }, function(){
            window.location.href = "index.php";
      });</script>';
      die();
?>