<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_SESSION['detsuid'])) {
	header('location:dashboard.php');
}

if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$query = mysqli_query($con, "select ID from tbluser where  Email='$email' && Password='$password' ");
	$ret = mysqli_fetch_array($query);
	if ($ret > 0) {
		$_SESSION['detsuid'] = $ret['ID'];
		header('location:dashboard.php');
	} else {
		$msg = "Invalid Details.";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Daily Expense Tracker - Login</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">

</head>

<body>

	<div class="row">
		<hr />
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<h1 class="text-danger text-center">Access Denied, Please login as manager</h1>
		</div><!-- /.col-->
	</div><!-- /.row -->


	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>