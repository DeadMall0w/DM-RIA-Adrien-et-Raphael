<?php
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=messages");
	die("");
}

$idConv = valider("idConv", "GET");
if (!$idConv) {
  rediriger("index.php", ["view" => "conversations"]);
}

?>

<div class="page-header">
  <h1>Messages</h1>
</div>

<?php

// Extraire les infos de la conversation
$convCourante = getConversation($idConv);
if (count($convCourante) > 0) {
  $convCourante = $convCourante[0];
} else { // Si la conversation n'existe pas...
  rediriger("index.php", ["view" => "conversations"]);
}

$theme = $convCourante["theme"];
$active = $convCourante["active"];

echo "<h2>$theme</h2>\n";

// On affiche les messages
$messages = listerMessages($idConv);
echo "<div>\n";
foreach ($messages as $m) {
  echo "  <p style=\"color: " . $m["couleur"] .
    ";\">[" . $m["auteur"] . "] " .
    $m["contenu"] . "</p>\n";
}
echo "</div>\n";

// Formulaire de nouveau message
if (valider("connecte", "SESSION") && $active) {
  mkForm();
    mkInput("text", "contenu", "");
    mkInput("submit", "action", "Envoyer");
    mkInput("hidden", "view", "messages");
    mkInput("hidden", "idConv", $idConv);
  endForm();
}
header("Refresh:10");
?>











