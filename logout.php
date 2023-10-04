<?php
 require('inc/header.inc.php');
    require('inc/connection.inc.php');
    unset($_SESSION['username']);
    unset($_SESSION['id']);
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