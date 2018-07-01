<?php
ob_start("ob_gzhandler");

if (isset($_GET["f"])) {$f = $_GET["f"];}else {$f='';}	// name of image to viex
if (isset($_GET["v"])) {$v = $_GET["v"];}else {$v=0;}	// view mode, v=1
if (isset($_GET["p"])) {$p = $_GET["p"];}else {$p=1;}	// active page
if ($f!='' && $v==0) {$v=2;}
if ($p<1) {$p=1;}

// START OF PARAMETERS SECTION
$doc_title = "Title"; 					//web page title and H1
$doc_subtitle = "SubTitle"				//for H3
$repositoryUrl = $_SERVER['HTTP_HOST']."/pics-hosting/"; //url of reporsitory to generate link 
$columns = 4;                  			//number of images per line 
$ratio = 4.56;                			//ratio imageSize / thumbnailImageSize 
$qualityJPG = 70;              			//thumbnail image quality (0: worst to 100:best)
$qualityPNG = 1;               			//thumbnail image quality (9: worst to 0:best)
$scriptname = "index.php";    			//name of this script 
$thumb_dir = "thumb";        			//directory created to stored small images
$thumb_prefix = "thumb_";    			//prefix for generated images
// END OF PARAMETERS SECTION

function get_page(array $input, $pageNum, $perPage) {
    $start = ($pageNum-1) * $perPage;
    $end = $start + $perPage;
    $count = count($input);

    // Conditionally return results
    if ($start < 0 || $count <= $start) {
        // Page is out of range
        return array(); 
    } else if ($count <= $end) {
        // Partially-filled page
        return array_slice($input, $start);
    } else {
        // Full page 
        return array_slice($input, $start, $end - $start);
    }
}

function drawPager($countItem, $pageNum, $perPage){
	$lastPage = ceil($countItem/$perPage);

	echo '<div class="pager">';
	if ($pageNum ==1){
		echo '<span class="disabled">&lt; Pr&eacute;c&eacute;dente</span>';
	}else{
		echo '<a href="index.php?p='.($pageNum-1).'">&lt; Pr&eacute;c&eacute;dent</a>';
	}
	if ($pageNum ==1){
		echo '<span class="active">1</span>';
	}else{
		echo '<a href="index.php?p=1">1</a>';
	}
	if ($pageNum !=1 && $pageNum!=2 && $pageNum!=3 && $pageNum!=4){
		echo '<span class="dot">...</span>';
	}
	if ($pageNum!=1 && $pageNum!=2 && $pageNum!=3){
		echo '<a href="index.php?p='.($pageNum-2).'">'.($pageNum-2).'</a>';
	}
	if ($pageNum!=1 && $pageNum!=2){
		echo '<a href="index.php?p='.($pageNum-1).'">'.($pageNum-1).'</a>';
	}
	if ($pageNum!=1 && $pageNum!=$lastPage){
		echo '<span class="active">'.$pageNum.'</span>';
	}
	if ($pageNum!=$lastPage && $pageNum!=$lastPage-1){
		echo '<a href="index.php?p='.($pageNum+1).'">'.($pageNum+1).'</a>';
	}
	if ($pageNum!=$lastPage && $pageNum!=$lastPage-1 && $pageNum!=$lastPage-2){
		echo '<a href="index.php?p='.($pageNum+2).'">'.($pageNum+2).'</a>';
	}
	if ($pageNum!=$lastPage && $pageNum!=$lastPage-1 && $pageNum!=$lastPage-2 && $pageNum!=$lastPage-3){
		echo '<span class="dot">...</span>';
	}
	if ($countItem > $perPage){
		if ($pageNum ==$lastPage){
			echo '<span class="active">'.$lastPage.'</span>';
		}else{
			echo '<a href="index.php?p='.$lastPage.'">'.$lastPage.'</a>';
		}
	}
	if ($pageNum ==$lastPage){
		echo '<span class="disabled">Suivant &gt;</span>';
	}else{
		echo '<a href="index.php?p='.($pageNum+1).'">Suivant &gt;</a>';
	}
	echo "</div>\n";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $doc_title;?></title>
	<link rel="stylesheet" type="text/css" href="pic.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<br><br>
<?php
$mydirectory	= '.'; //directory in which images are fetched 
$counter		= 0;
$nbfiles 		= 0;
$currfile 		= "";
$filestab[0] 	= "";
$closep			= 0;
$perPage 		= 12;

if ($handle = opendir($mydirectory)){
	//create a directory for thumbnail images
	if (!is_dir($thumb_dir)){
	   mkdir($thumb_dir, 0777);
	}

	while (false !== ($currfile = readdir($handle)))  {
	// We get the extension of the current file and keep only image files 
	   $extension= strtolower(substr( strrchr( $currfile,  "." ), 1 ));  
	   if ($extension== "jpg" || $extension== "png"){
			$currfile = trim($currfile);
			$filestab[$nbfiles] = $currfile;
		  
			$size = GetImageSize($currfile);
			$width = $size[0];
			$newwidth = $size[0] / $ratio;
			$height = $size[1] ;
			$newheight = $size[1] / $ratio;
			$format = $size[2]; 
			//1 = GIF, 2 = JPG, 3 = PNG, 5 = PSD, 6 = BMP
			$currthumbfile = "./" . $thumb_dir . "/" . $thumb_prefix . $currfile;
			if (!file_exists($currthumbfile)){
				//GIF format is not supported anymore by GD lib...
				if ($format == 2){//JPG
					$im = imagecreatefromjpeg($currfile);
				}elseif ($format == 3){ //PNG
					$im = imagecreatefrompng($currfile);
				}
				if (!$im){
				   $currthumbfile = $currfile;
				}else{   
					$imthumb = imagecreatetruecolor($newwidth, $newheight);
					imagecopyresampled($imthumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					imagepng($imthumb, $currthumbfile, $qualityPNG);
					ImageDestroy($im);
				}
			}
			$nbfiles++;
		}
	}
	closedir($handle);
}

$nbelement=count($filestab);
if ($v == 0) {
	if (($p-1)*12>$nbfiles){$p=ceil($nbelement/$perPage);}
	
	$output = get_page($filestab, $p, $perPage);
	echo "<h1>".$doc_title."</h1>";
	echo "<h3>".$doc_subtitle."</h3>\n";
	foreach ($output as $currfile){
		// We get the extension of the current file and keep only image files 
		$currfilewithoutext = substr($currfile, 0, -4);
		$closep = 0;
		if ($counter==0){
			echo "<p class=\"center\">\n";
		}
		$currthumbfile = "./" . $thumb_dir . "/" . $thumb_prefix . $currfile;
		$currthumbfile = str_replace(" ","%20",$currthumbfile); // Allow fs with space characters

		echo  "<a href=\"$scriptname?v=1&f=$currfilewithoutext\">";
		if (file_exists($currthumbfile.".png")){
			$currthumbfile=$currthumbfile.".png";
		} elseif (file_exists($currthumbfile.".jpg")){
			$currthumbfile=$currthumbfile.".jpg";
		}

		echo  "<img src=\"$currthumbfile\">";
		echo  "</a>\n";
		$counter++;
		if ($counter == $columns){
			$counter = 0;
			$closep = 1;
			echo "</p>\n";
		}
	}
	if ($closep == 0){
		echo "</p>\n";
	}

	drawPager($nbelement, $p, $perPage);
}
if ($v == 1 || $v == 2){ 
	$file=$f;
	if (file_exists($f.".png")){
		$file=$f.".png";
	} elseif (file_exists($f.".jpg")){
		$file=$f.".jpg";
	} else {
		$file='404';
	}

	if ($file!='404'){
		echo '<p class="center">';
		$file = str_replace(" ","%20",$file);   // Allow fs with space characters
		$file = stripslashes($file);   // Allow fs with ' characters
		echo  "<img src=\"$file\">\n";
		$display = str_replace("%20"," ",$file);   // Clean display of fs with space characters
		echo "<br><b>$display</b><br>";

		$filenumber = array_search($file, $filestab);

		if ($v == 1){
			if ($filenumber != 0){
			  $prevnumber = $filenumber - 1;
			  $prevlink = substr(str_replace(" ","%20",$filestab[$prevnumber]), 0, -4);   // Allow fs with space characters
			  echo  "[<a href=\"$scriptname?v=1&f=$prevlink\">";
			  echo  " << Previous</a>]  \n";
			}

			echo  " &nbsp;&nbsp;[<a href=\"$scriptname\">Main Page</a>]&nbsp;&nbsp;\n";
			echo  "  \n" ;
			if ($filenumber != $nbfiles-1){
				$nextnumber = $filenumber + 1;
				$nextlink = substr(str_replace(" ","%20",$filestab[$nextnumber]), 0, -4);   // Allow fs with space characters
				echo  "[<a href=\"$scriptname?v=1&f=$nextlink\">";
				echo  "Next >></a>]\n";
			}
		}
		echo "<br>\n\n";
	?>
		<div class="link">
		<div><label for="show_image">Adresse de la page : </label><input class="txtlinks links" type="text" name="show_image" value="<?php echo $repositoryUrl.$scriptname."?f=".$f;?>" readonly="readonly" onclick="this.focus();this.select();" /></div>
		<div><label for="direct_link">Adresse de l'image : </label><input class="txtlinks links" type="text" name="direct_link" value="<?php echo $repositoryUrl.$file;?>" readonly="readonly" onclick="this.focus();this.select();" /></div>
		</div>
		<div class="link">
		<div><label for="bb_mignature">BBCode - Lien miniature : </label><input class="txtlinks links" type="text" name="bb_mignature" value="[url=<?php echo $repositoryUrl.$scriptname."?f=".$f;?>][img]<?php echo $repositoryUrl.$thumb_dir."/".$thumb_prefix.$f;?>[/img][/url]" readonly="readonly" onclick="this.focus();this.select();" /></div>
		<div><label for="bb_full">BBCode - Lien image r&eacute;elle : </label><input class="txtlinks links" type="text" name="bb_full" value="[url=<?php echo $repositoryUrl.$scriptname."?f=".$f;?>][img]<?php echo $repositoryUrl.$file;?>[/img][/url]"  readonly="readonly" onclick="this.focus();this.select();" /></div>
		</div>
	  <?php 
	   echo "</p>\n\n";
	} else {
		// 404
		echo "404";
	}
}
?>
</body>
</html>
<?php ob_end_flush();?>