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

<?php
$texte = $Illustration[0]["Texte"];
$largeur = $Illustration[0]["Dimension_IX"];
$hauteur = $Illustration[0]["Dimension_IY"];
$posTexte = $Illustration[0]["P_Texte"];
$url = $Illustration[0]["Image"];



?>

<img src="templates/image_controller.php?action=afficher_image&texte=<?= urlencode($texte) ?>&x=<?= intval($largeur) ?>&y=<?= intval($hauteur) ?>&posTexte=<?= intval($posTexte) ?>&urlImage=<?= urlencode($url) ?>"
     alt="Illustration générée"
     style="border:1px solid black;">


<p> <?php echo $Illustration[0]["complement"];?> </p>

<?php 

if ($_SESSION["professeur"] == 1 && $_SESSION["ID"] == $Illustration[0]["Createur"]) {

    $id_illustration =  $Illustration[0]["ID"];
    
    echo "<h2>Élèves ayant reçu la récompense :</h2>";
    $recu = liste_recu($ID);
    if (!empty($recu)) {
        foreach ($recu as $v) {
            echo '<div>';
            echo htmlspecialchars($v["Nom"]) . ", " . htmlspecialchars($v["Prenom"]) . " - " . $v["DateReception"];
            // Lien pour retirer la récompense
            echo ' <a href="controleur.php?action=changerRecompense&id_utilisateur='
     . $v["ID"] . '&ajouter=0&id_illustration=' . $id_illustration . '">Retirer</a>';

            echo '</div>';
        }
    }

    echo "<h2>Élèves n'ayant pas reçu la récompense :</h2>";
    $pasrecu = liste_pas_recu($ID);
    if (!empty($pasrecu)) {
        foreach ($pasrecu as $v) {
            echo '<div>';
            echo htmlspecialchars($v["Nom"]) . ", " . htmlspecialchars($v["Prenom"]);
            // Lien pour ajouter la récompense
            echo ' <a href="controleur.php?action=changerRecompense&id_utilisateur='
     . $v["ID"] . '&ajouter=1&id_illustration=' . $id_illustration . '">Ajouter</a>';

            echo '</div>';
        }
    }
}
?>
