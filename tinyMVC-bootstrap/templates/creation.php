<?php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=creation_qr_code");
	die("");
}
// Chargement eventuel des données en cookies
// $image = valider("image","COOKIE");
// $logo = valider("logo","COOKIE");
// $texte = valider("texte", "COOKIE"); 
// $complement= valider("complement","COOKIE");
// $dimension_IX = valider("dimension_IX","COOKIE");
// $dimension_IY = valider("dimension_IY","COOKIE");
// $dimension_QR= valider("dimension_QR","COOKIE");
// $P_QR = valider("P_QR","COOKIE");
// $P_Texte = valider("P_texte","COOKIE");


// if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 
?>

<div class="page-header">
	<!-- <h1>Connexion</h1> -->
</div>

<p class="lead">

<?php
// Sécurité : redirection si on appelle directement
if (basename($_SERVER["PHP_SELF"]) != "index.php") {
    header("Location:../index.php?view=deconnexion");
    die("");
}

$action = valider('action', 'GET'); // ou $_GET['action'] directement



// Valeurs par défaut pour le formulaire
$texte = valider("texte", "GET") ?? "";
$largeur = valider("x", "GET") ?? 600;
$hauteur = valider("y", "GET") ?? 600;
$url = valider("urlImage", "GET") ?? "";

$posTexte = valider("posTexte", "GET") ?? 2;


if ($action === 'voir') {
    // comportement classique
  } else if ($action === 'sauvegarder') {
    $logo = ""; // pas encore implémenté
  // creat_illustration
  creat_illustration($url,$logo,$texte,$largeur,$hauteur,10,"",1,$posTexte);
    // echo "enregistrement...";
    rediriger("index.php",
                  ["view" => "accueil",
                   "message" => "creation réussi !"]);
    // return;
}
?>

<h2>Création de l'illustration :</h2>

<form method="get" action="index.php">
    <input type="hidden" name="view" value="creation">

    <label>Texte : 
        <input type="text" name="texte" value="<?= htmlentities($texte) ?>">
    </label>
    <br><br>

    <label>Largeur : 
        <input type="number" name="x" value="<?= intval($largeur) ?>">
    </label>

    <label>Hauteur : 
        <input type="number" name="y" value="<?= intval($hauteur) ?>">
    </label>
    <br>
    <label>Url image : 
        <input type="text" name="urlImage" value="<?= htmlentities($url) ?>">
    </label>
    <br><br>

    <label>Position du texte : 
        <select name="posTexte">
            <option value="1" <?= $posTexte==1?'selected':'' ?>>Haut</option>
            <option value="2" <?= $posTexte==2?'selected':'' ?>>Milieu</option>
            <option value="3" <?= $posTexte==3?'selected':'' ?>>Bas</option>
        </select>
    </label>
    <br><br>

    <button type="submit" name="action" value="voir">Prévisualiser</button>
<button type="submit" name="action" value="sauvegarder">Sauvegarder</button>

</form>

<h3>Illustration générée :</h3>
<img src="templates/image_controller.php?action=afficher_image&texte=<?= urlencode($texte) ?>&x=<?= intval($largeur) ?>&y=<?= intval($hauteur) ?>&posTexte=<?= intval($posTexte) ?>&urlImage=<?= urlencode($url) ?>"
     alt="Illustration générée"
     style="border:1px solid black;">


 <!-- <form role="form" action="controleur.php">
  <div class="form-group">
    <label for="image">image</label>
    <input type="text" class="form-control" id="image" name="image" value="<?php echo $image;?>" >
  </div>
  <div class="form-group">
    <label for="logo">logo</label>
    <input type="text" class="form-control" id="logo" name="logo" value="<?php echo $logo;?>" >
  </div>
  <div class="form-group">
    <label for="texte">texte</label>
    <input type="text" class="form-control" id="texte" name="texte" value="<?php echo $texte;?>" >
  </div>
    <label for="complement">complement</label>
    <textarea rows="4" cols="50" class="form-control" id="complement" name="complement" value="<?php echo $complement;?>"> </textarea>
  <div class="form-group">
    <label for="dimension_IX">dimension_IX</label>
    <input type="number" class="form-control" id="dimension_IX" name="dimension_IX" value="<?php echo $dimension_IX;?>" >
  </div>
  <div class="form-group">
    <label for="dimension_IY">dimension_IY</label>
    <input type="number" class="form-control" id="dimension_IY" name="dimension_IY" value="<?php echo $dimension_IY;?>" >
  </div>
  <div class="form-group">
    <label for="dimension_QR">dimension_QR</label>
    <input type="number" class="form-control" id="dimension_QR" name="dimension_QR" value="<?php echo $dimension_QR;?>" >
  </div>
    <label for="P_QR">P_QR</label>
    <select class="form-control" id="P_QR" name="P_QR" value="<?php echo $P_QR;?>" >
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
    </select>
    <label for="P_Texte">P_Texte</label>
    <select  class="form-control" id="P_Texte" name="P_Texte" value="<?php echo $P_Texte;?>" >
        <option>1</option>
        <option>2</option>
        <option>3</option>
    </select>
  </div>



  <button type="submit" name="action" value="creation" class="btn btn-default">Créer QR-code</button>
</form> -->

</p>




