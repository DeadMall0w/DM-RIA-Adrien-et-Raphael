<?php

include_once "maLibSQL.pdo.php";

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/
function verifUserBdd($nom,$prenom, $passe)
{
	// Vérifie l'identité d'un utilisateur 
	// dont les identifiants sont passes en paramètre
	// renvoie faux si user inconnu
	// renvoie l'id de l'utilisateur si succès

	$SQL = "SELECT id FROM Personne WHERE Nom='$nom' && Prenom='$prenom' && Code='$passe' ;";

	return SQLGetChamp($SQL);
	// si on avait besoin de plus d'un champ
	// on aurait du utiliser SQLSelect
}

function isProf($id)
{


	$SQL = "SELECT Professeur FROM Personne WHERE ID='$id' ;";
	// die($SQL);
	return SQLGetChamp($SQL);

}

function creatU($nom,$prenom,$passe)
{
	// die($nom);
	$SQL = "INSERT INTO `Personne` (`ID`, `Nom`, `Prenom`, `Code`, `Professeur`) VALUES (NULL, '$nom', '$prenom', '$passe', '0')";
    return SQLGetChamp($SQL);
}

function creat_illustration($image,$logo,$texte,$dimension_IX,$dimension_IY,$dimension_QR,$complement,$P_QR,$P_Texte)
{
	$createur = $_SESSION["ID"];
	$SQL = "INSERT INTO `Illustration` 
        (`Image`, `Logo`, `Texte`, `Dimension_QR`, `Dimension_IX`, `Dimension_IY`, `P_Texte`, `P_QR`, `Createur`, `complement`)
        VALUES
        ('$image', '$logo', '$texte', $dimension_QR, $dimension_IX, $dimension_IY, $P_Texte, $P_QR, '$createur', '$complement')";

	// die($SQL);
    return SQLGetChamp($SQL);
}

function liste_creation($createur)
{
	$SQL = "SELECT ID , Texte 
			FROM `Illustration` 
			WHERE Createur='$createur';";
	$SQL=SQLSelect($SQL);
	return parcoursRs($SQL);		
}

function select_illustation($id)
{
	$SQL = "SELECT * 
	FROM `Illustration` 
	WHERE ID = '$id';";
	$SQL=SQLSelect($SQL);
	return parcoursRs($SQL);
}

function liste_recompense($id)
{
	$SQL = "SELECT `Illustration`.ID , `Illustration`.Texte
	FROM `Recevoir` JOIN `Illustration`
		ON `Recevoir`.ID_Illustration = `Illustration`.ID JOIN `Personne`
    		ON `Personne`.ID = `Recevoir`.ID_Personne
	WHERE `Recevoir`.ID_Personne = '$id';";
	$SQL=SQLSelect($SQL);
	return parcoursRs($SQL);		
}

function liste_recu($id)
{
	$SQL = "SELECT `Personne`.`Nom`, `Personne`.`Prenom`, `Recevoir`.`DateReception`, `Personne`.`ID`
	FROM `Recevoir` JOIN `Illustration`
		ON `Recevoir`.ID_Illustration = `Illustration`.ID JOIN `Personne`
    		ON `Personne`.ID = `Recevoir`.ID_Personne
	WHERE `Illustration`.ID = '$id'
	ORDER BY `Recevoir`.`DateReception`;";
	$SQL=SQLSelect($SQL);
	return parcoursRs($SQL);		
}

function changerRecompenseUtilisateur($ajouter, $id_utilisateur, $id_illustration) {
    $id_utilisateur = intval($id_utilisateur);
    $id_illustration = intval($id_illustration);

    if ($ajouter) {
        // Ajouter la récompense
        $SQL = "INSERT INTO `Recevoir` (`ID_Illustration`, `ID_Personne`, `DateReception`) 
                VALUES ($id_illustration, $id_utilisateur, NOW())";
    } else {
        // Retirer la récompense
        $SQL = "DELETE FROM `Recevoir` 
                WHERE `ID_Illustration` = $id_illustration 
                  AND `ID_Personne` = $id_utilisateur";
    }

    // Exécution de la requête
    return SQLGetChamp($SQL); // On garde ton wrapper SQL
}


function liste_pas_recu($id)
{
	$SQL = "SELECT `Personne`.Nom, `Personne`.Prenom, `Personne`.`ID`
				FROM `Personne`
					WHERE `Personne`.ID NOT IN (
    					SELECT `Recevoir`.ID_Personne
    						FROM `Recevoir` 
    							WHERE `Recevoir`.ID_Illustration = $id);";
	$SQL=SQLSelect($SQL);
	return parcoursRs($SQL);	
}
?>
