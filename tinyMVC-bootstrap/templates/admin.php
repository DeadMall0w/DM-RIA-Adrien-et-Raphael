<?php
// Page d'administration

if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=admin");
	die("");
}

// Traiter l'action demandée
$action = valider('action', 'GET');
$idUser = valider('idUser', 'GET');
$couleur = valider('couleur', 'GET');
$message = valider('message', 'GET');

?>

<div class="page-header">
  <h1>Administration du site</h1>
</div>

<h2>Liste des utilisateurs de la base </h2>

<?php

echo "liste des utilisateurs autorises de la base :"; 
$users = listerUtilisateurs("nbl");
mkTable($users, ['pseudo', 'couleur', 'connecte']);

echo "<hr />";

echo "liste des utilisateurs non autorises de la base :"; 
$users = listerUtilisateurs("bl");
mkTable($users, ['pseudo', 'couleur', 'connecte']);
?>


<hr />

<h2>Changement de statut des utilisateurs</h2>

<?php

$users = listerUtilisateurs("both");

$tabCouleurs = [
  ["nom" => "Bleu", "valeur" => "blue"],
  ["nom" => "Vert", "valeur" => "green"],
  ["nom" => "Rouge", "valeur" => "red"],
  ["nom" => "Noir", "valeur" => "black"],
  ["nom" => "Gris", "valeur" => "#333"]
];

mkForm("controleur.php");
  mkSelect("idUser", $users, "id", "pseudo", $idUser, "couleur");
  mkInput("submit", "action", "Interdire");
  mkInput("submit", "action", "Autoriser");
  mkInput("submit", "action", "Ajouter admin");
  mkInput("submit", "action", "Retirer admin");
  //mkSelect("couleur", $users, "couleur", "couleur", $couleur);
  mkSelect("couleur", $tabCouleurs, "valeur", "nom", $couleur);
  mkInput("submit", "action", "Changer couleur");
  mkInput("text", "pseudo", "");
  mkInput("submit", "action", "Changer pseudo");
  mkInput("hidden", "view", "admin");
endForm();

?>














