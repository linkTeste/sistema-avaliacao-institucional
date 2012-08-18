<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
require_once '../system/application/models/dao/Log.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Funcionario.php';
require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

if(isset($_SESSION["action"])){
	if($_SESSION["action"] == "new"){
		//echo "na sessao ".$_SESSION["action"];
		$new = true;
	}
	if($_SESSION["action"] == "edit"){
		$edit = true;
	}
	$_SESSION["action"] = null;
}

if(isset($_SESSION["s_usuario_logado"])){
	$str = $_SESSION["s_usuario_logado"];
	if($str instanceof Usuario){
		$usuario_logado = $str;
	}else{
		$usuario_logado = unserialize($_SESSION["s_usuario_logado"]);
	}
}
if(isset($_SESSION["s_usuario_logado_permissoes"])){
	$usuario_logado_permissoes = $_SESSION["s_usuario_logado_permissoes"];
}else{
	header("Location: login.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Acessos</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />




<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript"
	src="js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "..Utils/server_processing.php"
				} );
			} );
		</script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.js"></script>

</head>

<body style="background: #fafafa;">









<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>
	
	
	
	
	
	
	
		
	
	
	
<?php } ?>

<div id="wrapper" class="container">

	<?php include_once 'inc/header_inc.php';?>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>       
    
    <div class="white">
		<br />
        

        <h3>Configurações</h3>
        
        <div id="questionarios">
        <h3>Aparência</h3>
        
        <?php
        $themes = array();
			
			 // atribuição a variável $dir
			 $theme_directory = "./css/themes";
   			$dir = new DirectoryIterator($theme_directory);
 
   			$pos = 0;
   			foreach($dir as $file ){
     			// verifica se o valor de $file é diferente de '.' ou '..'
     			// e é um diretório (isDir)
     			if (!$file->isDot() && $file->isDir()){
					// atribuição a variável $dname
					
     				//verifica se não é diretorio .svn
     				if($file->getFilename() != ".svn"){
        			$dname = utf8_encode($file->getFilename());
					
								$themes[$pos]["nome"] = $dname;
								$themes[$pos]["filename"] = $theme_directory."/".$dname."/style.css";
								$themes[$pos]["preview_image"] = $theme_directory."/".$dname."/preview.png";
     				}
					
     			}
				$pos++;
   			}
   			
   			if(isset($_SESSION["s_theme"])){
   				$theme_active = $_SESSION["s_theme"];
   			}else{
   				$theme_active = "RedGradient_3 Theme";
   			}
   			
   			
   			foreach($themes as $theme){
   			
   				?>
   			            <div class="theme_preview">
   			            	<div class="theme_img">                       
   			            		<img alt="<?php echo $theme["nome"]?>" src="<?php echo $theme["preview_image"]?>" />
   			            	</div>
   			            	<div class="theme_info">
   			            		<h4><?php echo $theme["nome"]?></h4>
   			            		<?php   			            		
   			            		if($theme["nome"] == $theme_active){
													echo "<a href='../Controller/themeController.php?action=ativar&theme=".$theme["nome"]."' class='botao_right botaoGoogleBlue' title='Desativar'>Ativado</a>";
												}else{
													echo "<a href='../Controller/themeController.php?action=ativar&theme=".$theme["nome"]."' class='botao_right botaoGoogleGrey' title='Ativar'>Ativar</a>";
												}
												?>		            
   			            	</div>
   			            </div>
   			            <?php
   						}
   						?>           
        	<br style="clear: both;" />
        	<hr />
        	
        	<h3>Alertas</h3>
        	
        	<ul>
        		<li>Abrir Processo de Avaliação</li>
        		<li>Fechar Processo de Avaliação</li>
        		<li>Prorrogar Processo de Avaliação</li>
        	</ul>
        	
        	<div>
        		<h3>Senhas: </h3>
        		<?php
        			echo passwordGenerator();
        			echo "<br />";
        		?>
        	</div>
           </div>
        </div><!-- fecha div white -->
        
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
</html>
