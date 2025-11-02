<?php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=login");
	die("");
}

// Chargement eventuel des données en cookies
$nom = valider("nom","COOKIE");
$prenom = valider("prenom","COOKIE");
$passe = valider("passe", "COOKIE"); 
if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 

?>

<div class="page-header">
	<h1>Connexion</h1>
</div>

<p class="lead">

 <form role="form" action="controleur.php">
  <div class="form-group">
    <label for="Nom">Nom</label>
    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom;?>" >
  </div>
  <div class="form-group">
    <label for="Prenom">Prénom</label>
    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom;?>" >
  </div>
  <div class="form-group">
    <label for="pwd">Mot de passe</label>
    <input type="password" class="form-control" id="pwd" name="passe" value="<?php echo $passe;?>">
  </div>
  <div class="checkbox">
    <label><input type="checkbox" name="remember" <?php echo $checked;?> >Se souvenir de moi</label>
  </div>
  <a href="index.php?view=creer_utilisateur">Créer un compte</a>
  <br>
  <button type="submit" name="action" value="Connexion" class="btn btn-default">Connexion</button>
  <!-- <button type="submit" name="action" value="redirectionU" class="btn btn-default">Créer un compte </button> -->
</form>

</p>




