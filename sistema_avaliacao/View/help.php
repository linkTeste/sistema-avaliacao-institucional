<?php 
if (!isset($_SESSION)) {
	session_start();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Ajuda</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<?php include_once 'inc/theme_inc.php';?>
<?php include_once 'inc/ie_bugfixes_inc.php';?>
</head>

<body style="background: #fafafa;">
<div id="overlay"></div>
	
	<div id="wrapper" class="container">
	<?php //include_once 'inc/header_inc.php';?>
		<div id="content">
			<div class="white">
<!-- 				<h3>Siga as etapas abaixo para realizar sua avaliação:</h3> -->
				<div id="content_tutorial">
				<?php
				if(isset($_SESSION["s_usuario_logado_type"]) && $_SESSION["s_usuario_logado_type"] == "Funcionario"){
				?>
				  <img src="css/images/tutorials/funcionario/tutoriais_topo.jpg" />
					<img src="css/images/tutorials/funcionario/tutoriais_step1.jpg" />
					<img src="css/images/tutorials/funcionario/tutoriais_step2.jpg" />
					<img src="css/images/tutorials/funcionario/tutoriais_step3.jpg" />
					<img src="css/images/tutorials/funcionario/tutoriais_step4.jpg" />
					<img src="css/images/tutorials/funcionario/tutoriais_step5.jpg" />
				<?php 
				}else{
				?>
				<img src="css/images/tutorials/tutoriais_topo.jpg" />
				<img src="css/images/tutorials/tutoriais_step1.jpg" />
				<img src="css/images/tutorials/tutoriais_step2.jpg" />
				<img src="css/images/tutorials/tutoriais_step3.jpg" />
				<img src="css/images/tutorials/tutoriais_step4.jpg" />
				<img src="css/images/tutorials/tutoriais_step5.jpg" />
				<?php 
				}
				?>
				</div>
			</div>
		</div>
		<?php //include_once 'inc/footer_inc.php';?>
	</div>
</body>

</html>
