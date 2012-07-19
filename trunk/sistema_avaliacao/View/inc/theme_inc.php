<?php

if(isset($_SESSION['s_theme'])){
	$tema = "css/themes/".$_SESSION['s_theme']."/style.css";
}else{
	$tema = "css/themes/Blue/style.css";
}

?>

<link href="<?php echo $tema; ?>" rel="stylesheet" type="text/css" />