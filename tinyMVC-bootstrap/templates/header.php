<?php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}

// Pose qq soucis avec certains serveurs...
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Génération QR-code</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<!-- Liaisons aux fichiers css de Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />
	<link href="css/sticky-footer.css" rel="stylesheet" />
	<!--[if lt IE 9]>
	  <script src="js/html5shiv.js"></script>
	  <script src="js/respond.min.js"></script>
	<![endif]-->

	<script src="js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	

</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body>

<!-- style inspiré de http://www.bootstrapzero.com/bootstrap-template/sticky-footer --> 

<!-- Wrap all page content here -->
<div id="wrap">
  
  <!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
        </button>
	<a class="navbar-brand" href="index.php?view=accueil">Génération QR-code</a>
	<img src="ressources/ig2i.jpeg" alt="Logo IG2I" />
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
         	<!-- <li class="active"><a href="index.php?view=accueil">Accueil</a></li> -->
		<?=mkHeadLink("Accueil","accueil",$view)?>
		<?php
		// Si l'utilisateur n'est pas connecte, on affiche un lien de connexion 
		if (!valider("connecte","SESSION")){
			echo mkHeadLink("Se connecter","login",$view); 
			echo mkHeadLink("Créer un compte","creer_utilisateur",$view); 
		}

		// Si l'utilisateur est connecte : lien de conversations
		if (valider("connecte","SESSION"))
			echo mkHeadLink("afficher QR-code reçu","afficher_qr_code_recus",$view); 
			//echo mkHeadLink("Se deconnecter ","conversations",$view); 
			if (valider("professeur", "SESSION")) {
				echo mkHeadLink("afficher QR-code créer","afficher_qr_code_creer",$view); 
				echo mkHeadLink("Créer un QR-code","creation",$view);
  				// echo mkHeadLink("Admin","admin",$view); 
			}
		?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
  


  <!-- Begin page content -->
  <div class="container">
      <?php
        if ($message = valider("message", "GET")) {
          echo "<div id=\"message\">$message</div>\n";
        }
      ?>







