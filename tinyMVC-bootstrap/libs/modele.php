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

function creat_illustration($image,$logo,$texte,$dimension_IX,$dimension_IY,$dimension_QR,$complement,$P_QR,$P_Texte,$id)
{
	$SQL = "INSERT INTO `Illustration` (`ID`, `Image`, `Logo`, `Texte`, `Dimension_QR`, `Dimension_IX`, `Dimension_IY`, `P_Texte`, `P_QR`, `Createur`, `complement`) VALUES (NULL, '$image', '$logo', '$texte', '$dimension_QR', '$dimension_IX', '$dimension_IY', '$P_Texte', '$P_QR', '$id', '$complement')";
	return SQLGetChamp($SQL);
}
?>
