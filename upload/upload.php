<?php
function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}
 
// START OF PARAMETERS SECTION
define('TARGET', '../');		// Repertoire cible
define('MAX_SIZE', 2097152);// Taille max en octets du fichier
define('WIDTH_MAX', 1024);	// Largeur max de l'image en pixels
define('HEIGHT_MAX', 1024);	// Hauteur max de l'image en pixels
// END OF PARAMETERS SECTION

// Tableaux de donnees
$tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
$infosImg = array();
 
// Variables
$extension = '';
$message = '';
$nomImage = '';
$targeturl = '';

/************************************************************
 * Creation du repertoire cible si inexistant
 *************************************************************/
if( !is_dir(TARGET) ) {
  if( !mkdir(TARGET, 0755) ) {
    exit('Erreur : le repertoire cible ne peut-etre cree ! Verifiez que vous diposiez des droits suffisants pour le faire ou creez le manuellement !');
  }
}
$fail=true;
/************************************************************
 * Script d'upload
 *************************************************************/

if(isset($_FILES['myfile'])){
	$sFileName = $_FILES['myfile']['name'];
	// On verifie si le champ est rempli
	if( !empty($_FILES['myfile']['name']) ){
		// Recuperation de l'extension du fichier
		$extension  = strtolower(pathinfo($_FILES['myfile']['name'], PATHINFO_EXTENSION));

		// On verifie l'extension du fichier
		if(in_array($extension,$tabExt)){
		  // On recupere les dimensions du fichier
		  $infosImg = getimagesize($_FILES['myfile']['tmp_name']);

		  // On verifie le type de l'image
		  if($infosImg[2] >= 1 && $infosImg[2] <= 14){
			// On verifie les dimensions et taille de l'image
			if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['myfile']['tmp_name']) <= MAX_SIZE)){
			  // Parcours du tableau d'erreurs
			  if(isset($_FILES['myfile']['error']) && UPLOAD_ERR_OK === $_FILES['myfile']['error']){
				// On renomme le fichier
				$nomImage = md5(uniqid());
				$nomImageComplet= $nomImage.'.'.$extension;

				// Si c'est OK, on teste l'upload
				if(move_uploaded_file($_FILES['myfile']['tmp_name'], TARGET.$nomImageComplet)){
				  $message = 'Upload réussi !';
				  $targeturl = '../index.php?f='.$nomImage;
				  $fail=false;
				}else{
				  // Sinon on affiche une erreur systeme
				  $message = 'Problème lors de l\'upload !';
				}
			  }else{
				$message = 'Une erreur interne a empéché l\'uplaod de l\'image';
			  }
			}else{
			  // Sinon erreur sur les dimensions et taille de l'image
			  $message = 'Erreur dans les dimensions de l\'image !';
			}
		  }else{
			// Sinon erreur sur le type de l'image
			$message = 'Le fichier à uploader n\'est pas une image !';
		  }
		}else{
		  // Sinon on affiche une erreur pour l'extension
		  $message = 'L\'extension du fichier est incorrecte !';
		}
	}else{
	// Sinon on affiche une erreur pour le champ vide
	$message = 'Veuillez remplir le formulaire svp !';
	}

	if ($fail==true){
		echo <<<EOF
<div class="f">
	<p>Import impossible du fichier : {$sFileName}.</p>
	<p>Erreur : {$message}</p>
</div>
EOF;
}else{
	$sFileType = $_FILES['myfile']['type'];
	$sFileSize = bytesToSize1024($_FILES['myfile']['size'], 1);

	echo <<<EOF
<div class="s">
	<p>Le fichier : {$sFileName} a &eacute;t&eacute; correctement transf&eacute;t&eacute;.</p>
	<p>Type : {$sFileType}</p>
	<p>Taille : {$sFileSize}</p>
	<p>Acc&egrave;s : <a href="{$targeturl}">page de l'image</a></p>
</div>
EOF;
	}
}
?>