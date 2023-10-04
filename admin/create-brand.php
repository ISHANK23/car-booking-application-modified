<?php
    require('includes/connection.inc.php');
    require('includes/session.php');

if(isset($_POST['submit']))
{
$brandName=$_POST['brand'];
$sql="INSERT INTO brands(BrandName)VALUES('$brandName')";
if($res=mysqli_query($con,$sql)){
  echo '<script>alert("Brand added succes")</script>';
 //header('Location:register.php');
}else{
  echo "<script>
  Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Please check your email and password',
  })
    </script>";
}
//header('Location:register.php');
}

?>
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<!doctype html>
<html lang="en" class="no-js">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="theme-color" content="#3e454c">
      <title>Car Rental Portal | Admin Create Brand</title>
      <link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>  

<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
      <style>
         .errorWrap {
         padding: 10px;
         margin: 0 0 20px 0;
         background: #fff;
         border-left: 4px solid #dd3d36;
         -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
         box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
         }
         .succWrap{
         padding: 10px;
         margin: 0 0 20px 0;
         background: #fff;
         border-left: 4px solid #5cb85c;
         -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
         box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
         }
      </style>
   </head>
   <body>
      <?php include('includes/header.php');?>
      <div class="ts-main-content">
         <?php include('includes/leftbar.php');?>
         <div class="content-wrapper">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-12">
                     <h2 class="page-title">Create Brand</h2>
                     <div class="row">
                        <div class="col-md-10">
                           <div class="panel panel-default">
                              <div class="panel-heading">Form fields</div>
                              <div class="panel-body">
                                 <form method="post" name="chngpwd" class="form-horizontal" onSubmit="return valid();">
                                    <div class="form-group">
                                       <label class="col-sm-4 control-label">Brand Name</label>
                                       <div class="col-sm-8">
                                          <input type="text" class="form-control" name="brand" id="brand" required>
                                       </div>
                                    </div>
                                    <div class="hr-dashed"></div>
                                    <div class="form-group">
                                       <div class="col-sm-8 col-sm-offset-4">
                                          <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Loading Scripts -->
	   <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
	   <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
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