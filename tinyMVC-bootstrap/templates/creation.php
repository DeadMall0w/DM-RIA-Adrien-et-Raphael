<?php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=creation_qr_code");
	die("");
}
// Chargement eventuel des données en cookies
$image = valider("image","COOKIE");
$logo = valider("logo","COOKIE");
$texte = valider("texte", "COOKIE"); 
$complement= valider("complement","COOKIE");
$dimension_IX = valider("dimension_IX","COOKIE");
$dimension_IY = valider("dimension_IY","COOKIE");
$dimension_QR= valider("dimension_QR","COOKIE");
$P_QR = valider("P_QR","COOKIE");
$P_Texte = valider("P_texte","COOKIE");


if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 
?>

<div class="page-header">
	<h1>Connexion</h1>
</div>

<p class="lead">

 <form role="form" action="controleur.php">
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
</form>

</p>




