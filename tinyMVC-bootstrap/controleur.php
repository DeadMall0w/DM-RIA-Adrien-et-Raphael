<?php

  session_start();

	include_once "libs/modele.php"; 
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/rs_2i.php";
	include_once "libs/qr_2i.php";

	// Cette page recoit des demandes de traitement de base de données 
	// Elle est sécurisée, et ne doit pouvoir être utilisée que si l'utilisateur connecté est un administrateur
	// Si ce n'est pas le cas, elle redirige vers la page appelante si elle existe ou le formulaire de login sinon

	// Toute demande contient un champ 'action' indiquant l'action à réaliser
	// Une fois le traitement effectué, la page redirige vers la page appelante 
	// en renvoyant les données les plus pertinentes transmises et un message de feedback

	// veiller à vérifier les données transmises à l'aide de la fonction valider()
	// et à vous prémunir des injections SQL
  
  // Routine de connexion
  	if (valider("action") == "Connexion") {
		$nom = valider("nom", "GET");
		$prenom = valider("prenom", "GET");
		$passe = valider("passe", "GET");
		if ($nom && $prenom && $passe) {
			if (verifUserBdd($nom,$prenom, $passe)) {
				// Rediriger vers l'accueil
				$_SESSION["ID"]=verifUserBdd($nom,$prenom, $passe);
				$_SESSION["nom"]=$nom;
				$_SESSION["prenom"]=$prenom;
				$_SESSION["code"]=$passe;
				$_SESSION["professeur"]=isProf($_SESSION["ID"]);
				$_SESSION["connecte"]=1;
				rediriger("index.php",
						["view" => "accueil",
						"message" => "Connexion réussie, bienvenue " . $_SESSION["nom"] . " " . $_SESSION["prenom"] . " !"]);
			} 
		}
	}else if (valider("action") == "changerRecompense"){




	}
	else if (valider("action") == "deconnexion") {
			// Rediriger vers l'accueil
	        $_SESSION = [];
      	    session_destroy();
			rediriger("index.php",
			["view" => "accueil",
			"message" => "Déonnexion réussie !"]);
		}else if (valider("action") == "resterconnecte"){
			rediriger("index.php",
                  ["view" => "accueil",
                   "message" => "Connexion réussie, bienvenue " . $_SESSION["nom"] . " " . $_SESSION["prenom"] . " !"]);


		}else if (valider("action") == "creer_utilisateur"){
			$nom = valider("nom","GET");
			$prenom = valider("prenom","GET");
			$passe = valider("passe", "GET"); 
			if ($nom && $prenom && $passe) {
				$_SESSION["connecte"]=1;
				creatU($nom,$prenom,$passe);

				// phase de connexion
				// on vérifie quand meme que l’utilisateur à bien été créé
				if (verifUserBdd($nom,$prenom, $passe)) {
					// Rediriger vers l'accueil
					$_SESSION["ID"]=verifUserBdd($nom,$prenom, $passe);
					$_SESSION["nom"]=$nom;
					$_SESSION["prenom"]=$prenom;
					$_SESSION["code"]=$passe;
					$_SESSION["professeur"]=isProf($_SESSION["ID"]);
					rediriger("index.php",
							["view" => "accueil",
							"message" => "Compte créé ! Bienvenue " . $_SESSION["nom"] . " " . $_SESSION["prenom"] . " !"]);
				} 
			}

		}else {
        	// Message d'erreur
       		 $_SESSION["tentatives"] = valider("tentatives", "SESSION") + 1;
       		 rediriger("index.php",
    	          ["view" => "login",
                   "message" => "Connexion échouée !"]);
      }
    
  
	//   die("connecte :" . $_SESSION["connecte"]);
	securiser("index.php",
	          ["view" => "login",
	           "message" => "Connexion requise (controleur) !"]); 
  
  // On reproduit la querystring dans la redirection
  $qs = $_GET;

	if ($action = valider("action"))
	{
		// Un paramètre action a été soumis, on fait le boulot...

		switch($action)
		{
		  // Actions sur les utilisateurs
		  case 'changerRecompense':
			$id_utilisateur = intval($_GET['id_utilisateur'] ?? 0);
			$id_illustration = intval($_GET['id_illustration'] ?? 0);

			$ajouter = isset($_GET['ajouter']) && $_GET['ajouter'] == '1' ? true : false;
			
			// Vérification des valeurs
			if ($id_utilisateur > 0 && $id_illustration > 0) {
				changerRecompenseUtilisateur($ajouter, $id_utilisateur, $id_illustration);
			}

			rediriger("index.php",
    	          ["view" => "afficher_info", "id" => $id_illustration]);

			break;

			case 'Interdire' : 
			  if (($idUser = valider("idUser", "GET")) &&
			      valider("admin", "SESSION")) {
		      interdireUtilisateur($idUser);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;
			case 'Autoriser' : 
			  if (($idUser = valider("idUser", "GET")) &&
			      valider("admin", "SESSION")) {
		      autoriserUtilisateur($idUser);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Ajouter admin' : 
			  if (($idUser = valider("idUser", "GET")) &&
			      valider("admin", "SESSION")) {
		      promouvoirAdmin($idUser);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Retirer admin' : 
			  if (($idUser = valider("idUser", "GET")) &&
			      valider("admin", "SESSION")) {
		      retrograderUser($idUser);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Changer couleur' : 
			  if (($idUser = valider("idUser", "GET")) &&
			      ($couleur = valider("couleur", "GET")) &&
			      valider("admin", "SESSION")) {
		      changerCouleur($idUser, $couleur);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Changer pseudo' : 
			  if (($idUser = valider("idUser", "GET")) &&
			      ($pseudo = valider("pseudo", "GET")) &&
			      valider("admin", "SESSION")) {
		      changerPseudo($idUser, $pseudo);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

		  // Actions sur les conversations
			case 'Archiver' : 
			  if (($idConv = valider("idConv", "GET")) &&
			      valider("admin", "SESSION")) {
		      archiverConversation($idConv);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Reactiver' : 
			  if (($idConv = valider("idConv", "GET")) &&
			      valider("admin", "SESSION")) {
		      reactiverConversation($idConv);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Supprimer' : 
			  if (($idConv = valider("idConv", "GET")) &&
			      (valider("view", "GET") == "conversations") &&
			      valider("admin", "SESSION")) {
		      supprimerConversation($idConv);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

			case 'Nouvelle conversation' : 
			  if (($theme = valider("theme", "GET")) &&
			      valider("admin", "SESSION")) {
		      creerConversation($theme);
			  } else {
		      $qs["message"] = "Opération échouée";
			  }
			break;

		  // Messages
			case 'Envoyer' :  // Nouveau message
			  if (($contenu = valider("contenu", "GET")) &&
			      ($idConv = valider("idConv", "GET")) &&
			      valider("connecte", "SESSION") &&
			      ($idUser = valider("idUser", "SESSION"))) {
		      enregistrerMessage($idConv, $idUser, $contenu);
			  } else {
		      $qs["message"] = "Le message n'a pas pu être envoyé";
			  }
			break;

		  // Logout
			case 'Logout' : 
        session_destroy();
        $_SESSION = [];
        rediriger("index.php",
                  ["view" => "login",
                   "message" => "Déconnexion réussie !"]);
			break;			
		}
	}

	// On redirige vers la page appelante 
	$url = explode("?", $_SERVER["HTTP_REFERER"])[0];
	rediriger($url, $qs);
	
?>
