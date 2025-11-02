<?php

require_once("rs_2i.php");

//---------------------------------------------------------------
// QRCode for PHP5
// Copyright (c) 2009 Kazuhiko Arase
// URL: https://kazuhikoarase.github.io/qrcode-generator/js/demo/
//
// Licensed under the MIT license:
//   http://www.opensource.org/licenses/mit-license.php
//
// The word "QR Code" is registered trademark of
// DENSO WAVE INCORPORATED
//   http://www.denso-wave.com/qrcode/faqpatent-e.html
//
//---------------------------------------------------------------------

//---------------------------------------------------------------
// Notes de modification par Thomas Bourdeaud'huy 
// Refactorisation en PHP4 des fonctions de création du QR CODE (pas celles concernant les codes correcteurs)
// type <=> version 
// On ne calcule pas le masque automatiquement 
// On se limite au mode byte (on ne considère pas Numerique, alphanumérique ou Kanji) 
// TODO : faire disparaître le choix du mode dans les fonctions 
//---------------------------------------------------------------

// ------- Conversion bits -> octets -------
function bits_to_bytes($bitstring) {
	$bytes = [];
	for ($i=0; $i<strlen($bitstring); $i+=8) {
		$bytes[] = bindec(substr($bitstring, $i, 8));
	}
	return $bytes;
}

function bytes_to_bitstring($bytes) {
	$bits = '';
	foreach ($bytes as $b) {
		$bits .= str_pad(decbin($b), 8, '0', STR_PAD_LEFT);
	}
	return $bits;
}

// ------- Gestion d'un buffer binaire -------

// length : nombre de bits à écrire 
function put(&$buffer, &$bufferLength, $num, $length) {

	for ($i = 0; $i < $length; $i++) {
		putBit($buffer, $bufferLength, ( ($num >> ($length - $i - 1) ) & 1) == 1);
	}
}

// écriture d'un bit dans le buffer 
function putBit(&$buffer, &$bufferLength, $bit) {

	$bufIndex = (int)floor($bufferLength / 8); // numéro de l'octet actuel
	// Si on a déjà rempli complètement deux octets, bl vaut 16 et bufIndex vaut 2, count(b) vaut , il faut ajouter un octet 
	// Si on a écrit 15 bits (8+7), bl vaut 15 et bufIndex vaut 1, count(b) vaut 2, il ne faut pas ajouter d'octet encore 
	if (count($buffer) <= $bufIndex) {  
		$buffer[] = 0;	// Ajout d'un octet nul 
	}

	if ($bit) {	// Si le bit est nul, il n'y a rien à faire puisque les octets sont insérés avec des valeurs 0 
		$buffer[$bufIndex] |= (0x80 >> ($bufferLength % 8) );
	}

	$bufferLength++; // on vient d'ajouter un bit (s'il était nul, on incrémente quand même) 
}


// ------- Affichages -------

function print_modules($m) {
	$size = count($m);
	echo "<pre>"; 
	for ($r = 0; $r < $size; $r++) {	
		for ($c = 0; $c < $size; $c++) {
			$val = $m[$r][$c];
			if ($val === -1) echo 'x'; 
			else if ($val === null) echo 'n';
			else if ($val === 1) echo '1';
			else if ($val === true) echo '1';
			else echo '0';
		}
		echo "\n";
	}
	echo "<pre>"; 
}

function show_matrix($matrix) {
	$size = count($matrix);
	echo "<pre>";
	for ($r = 0; $r < $size; $r++) {
		
		for ($c = 0; $c < $size; $c++) {
			$val = $matrix[$r][$c];
			// echo "[$r][$c] : ." . $matrix[$r][$c] . ".<br/>";
			// 0 est égal à null par l'opérateur -1 !! 
			// Cela induit l'utilisation des opérateurs === => On utilise plutôt des -1  
			if (($val === -1) || ($val === null)) echo 'x'; 
			else if (($val == 1) || ($val == true))  echo '.'; 
			else echo ' '; 
		}
		echo "\n";
	}
	echo "</pre>";
}

function isDark($modules, $row, $col) {

	if ($modules[$row][$col] != -1) {
		// echo "[$row][$col] : ." . $modules[$row][$col] . ".<br/>";
		return $modules[$row][$col];
		
	} else {
		return false;
	}
}

function printHTML($modules, $size = "10px") {
	$moduleCount = moduleCount($modules); 
	$style = "border-style:none;border-collapse:collapse;margin:0px;padding:0px;\n";
	print("<table style='$style'>\n");
	for ($r = 0; $r < $moduleCount; $r++) {
		print("<tr style='$style'>\n");
		for ($c = 0; $c < $moduleCount; $c++) {
			$color = isDark($modules, $r, $c)? "#000000" : "#ffffff";
			print("<td style='$style;width:$size;height:$size;background-color:$color'></td>\n");
		}
		print("</tr>\n");
	}
	print("</table>\n");
}

// ----- CONSTANTES -----

// Octets de padding lorsque le texte à encoder est de taille inférieure à la capacité du QR Code 
define("QR_PAD0", 0xEC); // 11101100
define("QR_PAD1", 0x11); // 00010001

// Mode
define("QR_MODE_NUMBER", 1 << 0);
define("QR_MODE_ALPHA_NUM", 1 << 1);
define("QR_MODE_8BIT_BYTE", 1 << 2);
define("QR_MODE_KANJI", 1 << 3);

// MaskPattern
define("QR_MASK_PATTERN000", 0);
define("QR_MASK_PATTERN001", 1);
define("QR_MASK_PATTERN010", 2);
define("QR_MASK_PATTERN011", 3);
define("QR_MASK_PATTERN100", 4);
define("QR_MASK_PATTERN101", 5);
define("QR_MASK_PATTERN110", 6);
define("QR_MASK_PATTERN111", 7);

// ErrorCorrectLevel
define("QR_ERROR_CORRECT_LEVEL_L", 1); // 7%.			// 00000001
define("QR_ERROR_CORRECT_LEVEL_M", 0); // 15%.		// 00000000
define("QR_ERROR_CORRECT_LEVEL_Q", 3); // 25%.		// 00000011
define("QR_ERROR_CORRECT_LEVEL_H", 2); // 30%.		// 00000010

// Formatage des infos de format
define("QR_G15", (1 << 10) | (1 << 8) | (1 << 5) | (1 << 4) | (1 << 2) | (1 << 1) | (1 << 0) );
define("QR_G18", (1 << 12) | (1 << 11) | (1 << 10) | (1 << 9) | (1 << 8) | (1 << 5) | (1 << 2) | (1 << 0) );
define("QR_G15_MASK", (1 << 14) | (1 << 12) | (1 << 10) | (1 << 4) | (1 << 1) );

// ------- Gestion QR CODE -------

function moduleCount($m) {
	return count($m);
}

function create_matrix($size) {
	$m = array(); // lignes 
	for ($r = 0; $r < $size; $r++) {
		$m[$r] = array_fill(0, $size, -1); // colonnes : valeurs possibles : 0, 1 ou -1
	}
	return $m;
}

function setupFinderPattern(&$modules, $row, $col) {
	$moduleCount = moduleCount($modules); 
	for ($r = -1; $r <= 7; $r++) {

		for ($c = -1; $c <= 7; $c++) {

			if ($row + $r <= -1 || $moduleCount <= $row + $r
					|| $col + $c <= -1 || $moduleCount <= $col + $c) {
				continue;
			}

			if (
				   (0 <= $r && $r <= 6 && ($c == 0 || $c == 6) )
				|| (0 <= $c && $c <= 6 && ($r == 0 || $r == 6) )
				|| (2 <= $r && $r <= 4 &&  2 <= $c && $c <= 4)
				) $modules[$row + $r][$col + $c] = 1; 
			else $modules[$row + $r][$col + $c] = 0;
		}
	}
}

function setupTimingPatterns(&$modules) {
	$moduleCount = moduleCount($modules); 
	for ($i = 8; $i < $moduleCount - 8; $i++) {

		if ($modules[$i][6] != -1 || $modules[6][$i] != -1) {
			continue;
		}

		$modules[$i][6] = ($i % 2 == 0) ? 1 : 0;
		$modules[6][$i] = ($i % 2 == 0) ? 1 : 0;
	}
}



function getPatternPosition($typeNumber) {
	$QR_PATTERN_POSITION_TABLE = array(
		array(),
		array(6, 18),
		array(6, 22),
		array(6, 26),
		array(6, 30),
		array(6, 34),
		array(6, 22, 38),
		array(6, 24, 42),
		array(6, 26, 46),
		array(6, 28, 50),
		array(6, 30, 54),
		array(6, 32, 58),
		array(6, 34, 62),
		array(6, 26, 46, 66),
		array(6, 26, 48, 70),
		array(6, 26, 50, 74),
		array(6, 30, 54, 78),
		array(6, 30, 56, 82),
		array(6, 30, 58, 86),
		array(6, 34, 62, 90),
		array(6, 28, 50, 72, 94),
		array(6, 26, 50, 74, 98),
		array(6, 30, 54, 78, 102),
		array(6, 28, 54, 80, 106),
		array(6, 32, 58, 84, 110),
		array(6, 30, 58, 86, 114),
		array(6, 34, 62, 90, 118),
		array(6, 26, 50, 74, 98, 122),
		array(6, 30, 54, 78, 102, 126),
		array(6, 26, 52, 78, 104, 130),
		array(6, 30, 56, 82, 108, 134),
		array(6, 34, 60, 86, 112, 138),
		array(6, 30, 58, 86, 114, 142),
		array(6, 34, 62, 90, 118, 146),
		array(6, 30, 54, 78, 102, 126, 150),
		array(6, 24, 50, 76, 102, 128, 154),
		array(6, 28, 54, 80, 106, 132, 158),
		array(6, 32, 58, 84, 110, 136, 162),
		array(6, 26, 54, 82, 110, 138, 166),
		array(6, 30, 58, 86, 114, 142, 170)
	);

	return $QR_PATTERN_POSITION_TABLE[$typeNumber - 1];
}

function setupAlignmentPatterns(&$modules, $typeNumber) {
	$moduleCount = moduleCount($modules); 
	$pos =  getPatternPosition($typeNumber); 

	for ($i = 0; $i < count($pos); $i++) {
		for ($j = 0; $j < count($pos); $j++) {

			$row = $pos[$i];
			$col = $pos[$j];

			if ($modules[$row][$col] != -1) {
				continue;
			}

			for ($r = -2; $r <= 2; $r++) {
				for ($c = -2; $c <= 2; $c++) {
					if ($r == -2 || $r == 2 || $c == -2 || $c == 2 || ($r == 0 && $c == 0)) 
						$modules[$row + $r][$col + $c] = 1; 
					else $modules[$row + $r][$col + $c] = 0;
				}
			}
		}
	}
}

function getBCHTypeInfo($data) {
	$d = $data << 10;
	while (getBCHDigit($d) - getBCHDigit(QR_G15) >= 0) {
		$d ^= (QR_G15 << (getBCHDigit($d) - getBCHDigit(QR_G15) ) );
	}
	return ( ($data << 10) | $d) ^ QR_G15_MASK;
}

function getBCHTypeNumber($data) {
	$d = $data << 12;
	while (getBCHDigit($d) - getBCHDigit(QR_G18) >= 0) {
		$d ^= (QR_G18 << (getBCHDigit($d) - getBCHDigit(QR_G18) ) );
	}
	return ($data << 12) | $d;
}

function getBCHDigit($data) {
	$digit = 0;
	while ($data != 0) {
		$digit++;
		$data >>= 1;
	}
	return $digit;
}

function setupFormatPatterns(&$modules, $errorCorrectLevel, $maskPattern) {
	$moduleCount = moduleCount($modules); 
	$data = ($errorCorrectLevel << 3) | $maskPattern;	// On concatène au niveau binaire les bits de errorCorrectLength et les 3 bits du pattern 
	$bits = getBCHTypeInfo($data);

	for ($i = 0; $i < 15; $i++) {

		if (( ($bits >> $i) & 1) == 1) $mod = 1; else $mod = 0;

		if ($i < 6) {
			$modules[$i][8] = $mod;
		} else if ($i < 8) {
			$modules[$i + 1][8] = $mod;
		} else {
			$modules[$moduleCount - 15 + $i][8] = $mod;
		}

		if ($i < 8) {
			$modules[8][$moduleCount - $i - 1] = $mod;
		} else if ($i < 9) {
			$modules[8][15 - $i - 1 + 1] = $mod;
		} else {
			$modules[8][15 - $i - 1] = $mod;
		}
	}

	$modules[$moduleCount - 8][8] = 1; // Dark Module 
}

function setupVersionPatterns(&$modules, $typeNumber) {
	if ($typeNumber < 7) return; 
	
	$moduleCount = moduleCount($modules); 
	$bits = getBCHTypeNumber($typeNumber);

	for ($i = 0; $i < 18; $i++) {
		if ( ( ($bits >> $i) & 1) == 1) $mod = 1 ; else $mod = 0; 
		
		$modules[(int)floor($i / 3)][$i % 3 + $moduleCount - 8 - 3] = $mod;
		$modules[$i % 3 + $moduleCount - 8 - 3][floor($i / 3)] = $mod;
	}
}

function getLengthInBits($typeNumber) {
	// NB : uniquement vrai pour le mode byte 
	if ($typeNumber < 10) return 8; 
	else return 16; 
}

function createData($typeNumber, $errorCorrectLevel, $data) {

	$mode = QR_MODE_8BIT_BYTE;
	$rsBlocks = QRRSBlock::getRSBlocks($typeNumber, $errorCorrectLevel);

	// $buffer = new QRBitBuffer();
	
	$buffer = array(); // liste des octets à insérer dans le QR Code  
	$bufferLength = 0; // nombre de bits actuellement dans le buffer 

	// Hyp : $data est une seule chaîne 
	
	put($buffer, $bufferLength, $mode, 4);
	put($buffer, $bufferLength, strlen($data) , getLengthInBits($typeNumber));
	for ($i = 0; $i < strlen($data); $i++) {
		put($buffer, $bufferLength, ord($data[$i]), 8);
	}
	
	$totalDataCount = 0;
	for ($i = 0; $i < count($rsBlocks); $i++) {
		$totalDataCount += $rsBlocks[$i]->getDataCount();
	}
	
	if ($bufferLength > $totalDataCount * 8) {
		trigger_error("code length overflow. ("
			. $bufferLength
			. ">"
			.  $totalDataCount * 8
			. ")", E_USER_ERROR);
	}

	// end code.
	if ($bufferLength + 4 <= $totalDataCount * 8) {
		put($buffer, $bufferLength, 0, 4);
	}

	// padding
	while ($bufferLength % 8 != 0) {
		putBit($buffer, $bufferLength, false); 
	}

	// padding
	while (true) {

		if ($bufferLength >= $totalDataCount * 8) {
			break;
		}
		put($buffer, $bufferLength, QR_PAD0, 8); 

		if ($bufferLength >= $totalDataCount * 8) {
			break;
		}
		put($buffer, $bufferLength, QR_PAD1, 8);
		
	}
	
	return createBytes($buffer, $rsBlocks);
}

function createNullArray($length) {
	$nullArray = array();
	for ($i = 0; $i < $length; $i++) {
		$nullArray[] = null;
	}
	return $nullArray;
}

function getErrorCorrectPolynomial($errorCorrectLength) {

		$a = new QRPolynomial(array(1) );

		for ($i = 0; $i < $errorCorrectLength; $i++) {
			$a = $a->multiply(new QRPolynomial(array(1, QRMath::gexp($i) ) ) );
		}

		return $a;
	}

function createBytes($buffer, $rsBlocks) {

	$offset = 0;

	$maxDcCount = 0;
	$maxEcCount = 0;

	$dcdata = createNullArray(count($rsBlocks) );
	$ecdata = createNullArray(count($rsBlocks) );

	$rsBlockCount = count($rsBlocks);
	for ($r = 0; $r < $rsBlockCount; $r++) {

		$dcCount = $rsBlocks[$r]->getDataCount();
		$ecCount = $rsBlocks[$r]->getTotalCount() - $dcCount;

		$maxDcCount = max($maxDcCount, $dcCount);
		$maxEcCount = max($maxEcCount, $ecCount);

		$dcdata[$r] = createNullArray($dcCount);
		$dcDataCount = count($dcdata[$r]);
		
		$bdata = $buffer;
		for ($i = 0; $i < $dcDataCount; $i++) {
			// $bdata = $buffer; // $bdata = $buffer->getBuffer();
			$dcdata[$r][$i] = 0xff & $bdata[$i + $offset];
		}
		$offset += $dcCount;

		$rsPoly = getErrorCorrectPolynomial($ecCount);
		$rawPoly = new QRPolynomial($dcdata[$r], $rsPoly->getLength() - 1);

		$modPoly = $rawPoly->mod($rsPoly);
		$ecdata[$r] = createNullArray($rsPoly->getLength() - 1);

		$ecDataCount = count($ecdata[$r]);
		for ($i = 0; $i < $ecDataCount; $i++) {
			$modIndex = $i + $modPoly->getLength() - count($ecdata[$r]);
			$ecdata[$r][$i] = ($modIndex >= 0)? $modPoly->get($modIndex) : 0;
		}
	}

	$totalCodeCount = 0;
	for ($i = 0; $i < $rsBlockCount; $i++) {
		$totalCodeCount += $rsBlocks[$i]->getTotalCount();
	}

	$data = createNullArray($totalCodeCount);

	$index = 0;

	for ($i = 0; $i < $maxDcCount; $i++) {
		for ($r = 0; $r < $rsBlockCount; $r++) {
			if ($i < count($dcdata[$r]) ) {
				$data[$index++] = $dcdata[$r][$i];
			}
		}
	}

	for ($i = 0; $i < $maxEcCount; $i++) {
		for ($r = 0; $r < $rsBlockCount; $r++) {
			if ($i < count($ecdata[$r]) ) {
				$data[$index++] = $ecdata[$r][$i];
			}
		}
	}
	
	return $data;
}

function getMask($maskPattern, $i, $j) {
	switch ($maskPattern) {

	case QR_MASK_PATTERN000 : return ($i + $j) % 2 == 0;
	case QR_MASK_PATTERN001 : return $i % 2 == 0;
	case QR_MASK_PATTERN010 : return $j % 3 == 0;
	case QR_MASK_PATTERN011 : return ($i + $j) % 3 == 0;
	case QR_MASK_PATTERN100 : return (floor($i / 2) + floor($j / 3) ) % 2 == 0;
	case QR_MASK_PATTERN101 : return ($i * $j) % 2 + ($i * $j) % 3 == 0;
	case QR_MASK_PATTERN110 : return ( ($i * $j) % 2 + ($i * $j) % 3) % 2 == 0;
	case QR_MASK_PATTERN111 : return ( ($i * $j) % 3 + ($i + $j) % 2) % 2 == 0;

	default :
		trigger_error("mask:$maskPattern", E_USER_ERROR);
	}
}

function mapData(&$modules, $dataQR, $maskPattern) {
	$moduleCount = moduleCount($modules);  
	$inc = -1;
	$row = $moduleCount - 1;
	$bitIndex = 7;
	$byteIndex = 0;

	for ($col = $moduleCount - 1; $col > 0; $col -= 2) {

		if ($col == 6) $col--;

		while (true) {

			for ($c = 0; $c < 2; $c++) {

				if ($modules[$row][$col - $c] === null) {echo "Aie !"; }
				 
				if ($modules[$row][$col - $c] == -1) {
					
					$dark = false;

					if ($byteIndex < count($dataQR) ) {
						$dark = ( ( ($dataQR[$byteIndex] >> $bitIndex) & 1) == 1);
					}

					if (getMask($maskPattern, $row, $col - $c)) {
						$dark = !$dark;
					}

					if ($dark) $modules[$row][$col - $c] = 1; else $modules[$row][$col - $c] = 0; 
					$bitIndex--;

					if ($bitIndex == -1) {
						$byteIndex++;
						$bitIndex = 7;
					}
				}
			}

			$row += $inc;

			if ($row < 0 || $moduleCount <= $row) {
				$row -= $inc;
				$inc = -$inc;
				break;
			}
		}
	}
}

function qrcode(){
		$versionNumber = 1;
		$moduleCount = $versionNumber * 4 + 17;
		$modules = create_matrix($moduleCount);
		setupFinderPattern($modules,0, 0);
		setupFinderPattern($modules,$moduleCount - 7, 0);
		setupFinderPattern($modules,0, $moduleCount - 7);
		printHTML($modules);

	}


?>


