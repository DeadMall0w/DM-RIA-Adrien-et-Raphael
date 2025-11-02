<?php

//C'est la propriété php_self qui nous l'indique : 
// Quand on vient de index : 
// [PHP_SELF] => /chatISIG/index.php 
// Quand on vient directement par le répertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=deconnexion");
	die("");
}

$idConv = valider("idConv", "GET");

?>


<div class="page-header">
  <h1>Liste QR creer </h1>
</div>

<h2>êtes-vous sûr de vouloir vous déconnecter ? </h2>

//Lister toutes les illustrations créé par cette personne







