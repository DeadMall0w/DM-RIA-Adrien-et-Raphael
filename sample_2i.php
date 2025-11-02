<?php

require_once("qr_2i.php");


// ----- DONNEES D'ENTREE ------ 
$versionNumber = 5; // version (1, 2 ... )
$errorCorrectLevel = QR_ERROR_CORRECT_LEVEL_H; 
$data = "https://photos.google.com/share/AF1QipO_JhAfcTwkPaTsZx5g0RLmXqr31C8ciG01kOTA_doM-Blptm0TgwLl5J12Qv2WYQ/photo/AF1QipPwC_ZatXSFHjLpSO_ZAAFqq0wosCvvOclrE4jA?key=Wl8tVnVnU0R5NFEyQmtvUmJfeDl1aUVUR01IRnl3"; 
$maskPattern = QR_MASK_PATTERN000; 

// ----- DONNEES DE SORTIE -----
// $modules; // matrice de points 
// $moduleCount; // nombre de modules (1:21, 2:25 ...) 

// ------ Expérimentation ------

$versionNumber = 20; 
$moduleCount = $versionNumber * 4 + 17;
$modules = create_matrix($moduleCount);

setupFinderPattern($modules,0, 0);
setupFinderPattern($modules,$moduleCount - 7, 0);
setupFinderPattern($modules,0, $moduleCount - 7);
// printHTML($modules);

setupAlignmentPatterns($modules, $versionNumber); // A faire AVANT timing !
//printHTML($modules);

setupTimingPatterns($modules);
//printHTML($modules);

setupFormatPatterns($modules, $errorCorrectLevel, $maskPattern);
//printHTML($modules);

setupVersionPatterns($modules,$versionNumber); 
//printHTML($modules);

$dataQR = createData($versionNumber, $errorCorrectLevel, $data) ; 
mapData($modules, $dataQR, $maskPattern);

print_modules($modules); // affiche la structure binaire (x/0/1) d'un QR-Code (x représente une case non encore affectée)
show_matrix($modules); // affiche un QR-Code au format "ascii" (x représente une case non encore affectée)
printHTML($modules); // affiche un QR-Code pouvant être scanné dans un tableau HTML 



?>
