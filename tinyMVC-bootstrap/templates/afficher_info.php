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
	header("Location:../index.php?view=afficher_info");
	die("");
}

$ID = valider("id","GET");
$Illustration=select_illustation($ID);




?>


<h1> <?php echo $Illustration[0]["Texte"];?></h1>

// Illustration

<p> <?php echo $Illustration[0]["complement"];?> </p>

<?php 

    if ( $_SESSION["professeur"]==1){
        echo "<h2>éléves ayant reçu la récompense : </h2>";
        $recu=liste_recu($ID);
        if(isset($recu) && !empty($recu)){
        foreach ($recu as $v) {
            echo " - ";
            echo $v["Nom"];
			echo ", ";
			echo  $v["Prenom"];
            echo ", ";
            echo  $v["DateReception"];
            echo "<BR>";
        }
    }
        echo "<h2>éléves n'ayant pas reçu la récompense : </h2>";
        $pasrecu=liste_pas_recu($ID);
        if(isset($pasrecu) && !empty($pasrecu)){
        foreach ($pasrecu as $v) {
            echo " - ";
            echo $v["Nom"];
			echo ", ";
			echo  $v["Prenom"];
            echo "<BR>";
        }
        }
    }
?>