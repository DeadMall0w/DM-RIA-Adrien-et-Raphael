<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Test QR code </title>
	<?php 
    require_once("qr_2i.php");

	if (isset($_POST['bouton'])) {
	  qrcode();
	}
    ?>
</head>
<!-- **** F I N **** H E A D **** -->
<body>
	
	<div id="description" >
		<p> description </p>
	</div>
	<div id="QR" >
		<form method="post">
		  <button type="submit" name="bouton">générer un qr code </button>
		</form>
		<h1> QR code </h1>
	</div>
		
</body>
<!-- **** B O D Y **** -->

