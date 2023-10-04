<?php
    require('inc/header.inc.php');
    require('inc/connection.inc.php');

if(isset($_POST['submit']))
{
$username=$_POST['username'];
$email=$_POST['email']; 
$phone=$_POST['phone'];
$password=md5($_POST['password']); 
$sql="INSERT INTO users(username,email,phone,password) VALUES('$username','$email','$phone','$password')";
$chekMail="SELECT email FROM users WHERE email='$email'";
$res=mysqli_query($con,$chekMail);
if($row=mysqli_num_rows($res)>0){
  echo "<script>
  Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Email already registered!',
  })
    </script>";
}else{
if(mysqli_query($con,$sql))
{
header('Location:Login.php?status=success');
  if( $_GET['status'] == 'success'):
    echo 'feedback message goes here';
endif;
}
else 
{
echo "<script>alert('Something went wrong. Please try again');</script>";
}
}
//header('Location:register.php');
}

?>

<script>
  if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
function validate()
{
 var error="";
 var name = document.getElementById( "username" );
 if( name.value == "" )
 {
  error = " Please Enter Your Name. ";
  document.getElementById( "error" ).innerHTML = error;
  return false;
 }

 var email = document.getElementById( "email" );
 if( email.value == "" || email.value.indexOf( "@" ) == -1 )
 {
  error = " Please Enter Valid Email Address. ";
  document.getElementById( "error" ).innerHTML = error;
  return false;
 }

 var phone = document.getElementById( "phone" );
 if( phone.value == "" )
 {
  error = " Please Enter your phone number ";
  document.getElementById( "error" ).innerHTML = error;
  return false;
 }

 var password = document.getElementById( "password" );
 if( password.value == "")
 {
  error = " Password Must Be More Than Or Equal To 8 Digits. ";
  document.getElementById( "error" ).innerHTML = error;
  return false;
 }

 var password2 = document.getElementById( "password2" );
 if(password.value != password2.value){
  error = " Password doesn't match ";
  document.getElementById( "error" ).innerHTML = error;
  return false;
 }

 else
 {
  return true;
  alert('Registration successfull. Now you can login');
 }
}

function resetForm(){
document.getElementById("form").reset();
}
</script>

<!--Register form-->
<section class="register">
<div class="container">
<form id="form" method="POST" onsubmit="return validate();">
  <div class="form-group col-lg-6">
    <label for="exampleFormControlInput1">Name</label>
    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your name">
  
  </div>

  <div class="form-group col-lg-6">
    <label for="exampleFormControlInput1">Email address</label>
    <input type="text" class="form-control" name="email"  id="email" placeholder="Enter your email">
    
  </div>

  <div class="form-group col-lg-6">
    <label for="exampleFormControlInput1">Phone</label>
    <input type="text" class="form-control" name="phone"  id="phone" placeholder="Enter your phone number">
   
  </div>

  <div class="form-group col-lg-6">
    <label for="exampleFormControlInput1">Password</label>
    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
   
  </div>

  <div class="form-group col-lg-6">
    <label for="exampleFormControlInput1">Confirm-password</label>
    <input type="password" class="form-control" name="password2" id="password2" placeholder="Re-Enter your password">
    
  </div>


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