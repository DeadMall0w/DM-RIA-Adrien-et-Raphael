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
	header("Location:../index.php?view=afficher_qr_code_recus");
	die("");
}


?>


<div class="page-header">
  <h1>Liste des QR-codes reçus : </h1>
</div>

<?php 

    $SQL=liste_recompense($_SESSION["ID"]);
    if(isset($SQL) && !empty($SQL)){
        foreach ($SQL as $v) {
            echo '<button style="display:inherit;" id="bouton" onclick="window.location.href = \'index.php?view=afficher_info&id=';
  			echo $v["ID"];
			echo '\';">';
			echo  $v["Texte"];
			echo '</button>';
            echo "<BR>";
        }
    }

?>





