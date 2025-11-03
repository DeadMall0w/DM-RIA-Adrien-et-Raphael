<?php
// Désactiver les warnings pour éviter qu'ils perturbent le header
error_reporting(E_ERROR | E_PARSE);

ob_start(); 
require_once("../libs/qr_2i.php"); 
ob_end_clean();

// fonction pour utiliser une image venant d'une url
function imageFromUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // suit les redirections
    $data = curl_exec($ch);
    curl_close($ch);

    if ($data === false) return false;

    $im = @imagecreatefromstring($data); // supporte PNG, JPEG, GIF
    return $im ?: false;
}

header("Content-Type: image/png");

// ========== PARTIE 1 : IMAGE DE FOND AVEC TEXTE ==========
$largeurFinale = isset($_GET['x']) ? intval($_GET['x']) : 400;
$hauteurFinale = isset($_GET['y']) ? intval($_GET['y']) : 400;
$texte = isset($_GET['texte']) ? $_GET['texte'] : "Bonjour";
$posTexte = isset($_GET['posTexte']) ? intval($_GET['posTexte']) : 2;
$url = isset($_GET['urlImage']) ? $_GET['urlImage'] : "https://4ddig.tenorshare.com/images/photo-recovery/images-not-found.jpg";
$data = isset($_GET['data']) ? intval($_GET['data']) : 0;


// Image de fond
$background = imageFromUrl($url);

if (!$background) {
    $background = imagecreatefrompng('https://4ddig.tenorshare.com/images/photo-recovery/images-not-found.jpg'); 
}

// Redimensionné l'image de fond
$imageFinal = imagecreatetruecolor($largeurFinale, $hauteurFinale);
imagecopyresampled($imageFinal, $background, 0, 0, 0, 0, $largeurFinale, $hauteurFinale, imagesx($background), imagesy($background));

// ===== GENERATION DU QR CODE ==========
$versionNumber = 2; 
$errorCorrectLevel = QR_ERROR_CORRECT_LEVEL_H; 
$data = "150"; 
$maskPattern = QR_MASK_PATTERN000; 

$moduleCount = $versionNumber * 4 + 17;
$modules = create_matrix($moduleCount);

setupFinderPattern($modules, 0, 0);
setupFinderPattern($modules, $moduleCount - 7, 0);
setupFinderPattern($modules, 0, $moduleCount - 7);
setupAlignmentPatterns($modules, $versionNumber);
setupTimingPatterns($modules);
setupFormatPatterns($modules, $errorCorrectLevel, $maskPattern);
setupVersionPatterns($modules, $versionNumber); 

$dataQR = createData($versionNumber, $errorCorrectLevel, $data); 
mapData($modules, $dataQR, $maskPattern);

// Générer l'image du QR Code
$pixelSize = 3; // Réduit pour que le QR ne soit pas trop grand sur l'image
$qrImageSize = $moduleCount * $pixelSize;

$imageQR = imagecreatetruecolor($qrImageSize, $qrImageSize);
$white = imagecolorallocate($imageQR, 255, 255, 255);
$blackQR = imagecolorallocate($imageQR, 0, 0, 0);
imagefill($imageQR, 0, 0, $white);

for ($y = 0; $y < $moduleCount; $y++) {
    for ($x = 0; $x < $moduleCount; $x++) {
        $color = $modules[$y][$x] ? $blackQR : $white;
        imagefilledrectangle(
            $imageQR,
            $x * $pixelSize,
            $y * $pixelSize,
            ($x + 1) * $pixelSize - 1,
            ($y + 1) * $pixelSize - 1,
            $color
        );
    }
}

// ========== PARTIE 3 : INCRUSTER LE QR CODE SUR L'IMAGE ==========
// Position du QR code en haut et centré
$margeHaut = 10; // Marge depuis le haut
$xQR = intval(($largeurFinale - $qrImageSize) / 2); // Centré horizontalement

// Coller le QR code sur l'image de fond
imagecopy($imageFinal, $imageQR, $xQR, $margeHaut, 0, 0, $qrImageSize, $qrImageSize);

// ========== PARTIE 4 : AJOUTER LE TEXTE ==========
$cTexte = imagecolorallocate($imageFinal, 255, 255, 255);
$largeurTexte = imagefontwidth(5) * strlen($texte);
$xTexte = intval(($largeurFinale - $largeurTexte) / 2);

switch($posTexte) {
    case 1: $yTexte = $qrImageSize + $margeHaut + 20; break; // Sous le QR code
    case 3: $yTexte = $hauteurFinale - 20; break;    // Bas
    default: $yTexte = intval(($hauteurFinale - 10) / 2);    // Milieu
}

imagestring($imageFinal, 5, $xTexte, $yTexte, $texte, $cTexte);

// Afficher l'image finale
imagepng($imageFinal);

// Libérer la mémoire
imagedestroy($imageFinal);
imagedestroy($imageQR);
imagedestroy($background);
?>