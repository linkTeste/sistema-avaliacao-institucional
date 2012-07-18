<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
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
}

$descricao = "";
 
if(isset($_SESSION["s_processo"])){
	$processo = unserialize($_SESSION["s_processo"]);
	//debug
	//print_r($questionario);
	$id = $processo->getId();
	$descricao = $processo->getDescricao();
	
	if($edit == true){
		$inicio = datetime_to_ptbr($processo->getInicio());
		$fim = datetime_to_ptbr($processo->getFim());
	}else{
		$inicio = "";
		$fim = "";
	}	
	
	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Questionários</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<link type="text/css"
	href="css/unicampo-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.checkbox.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script>
	$(function() {
		
		//adiciona o timepicker
		$("#input-inicio").datetimepicker({
			monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
			timeText: 'Hora',
			hourText: 'Hora',
			minuteText: 'Minuto',
			currentText: 'Agora',
			closeText: 'Pronto',
			dateFormat: 'dd/mm/yy',
			timeFormat: 'hh:mm:ss'}
			);
		$("#input-fim").datetimepicker({
			monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
			timeText: 'Hora',
			hourText: 'Hora',
			minuteText: 'Minuto',
			currentText: 'Agora',
			closeText: 'Pronto',
			dateFormat: 'dd/mm/yy',
			timeFormat: 'hh:mm:ss'}
			);

		$('input:radio').checkbox();
	});
	</script>
	
</head>

<body style="background: #fafafa;">





<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>
	
	
	
	
	
	
<?php } ?>
<!-- 
	<div id="menu_usuario">
		<ul>
			<li><a href="http://www.faculdadeunicampo.edu.br/" target="_blank">Faculdade
					Unicampo</a></li>
			<li><a href="http://mail.faculdadeunicampo.edu.br/" target="_blank">E-mail
					Unicampo</a></li>
			<li id="username">Ol&aacute;, <?php //echo $usuario_logado->getNome();?> - <a
				href="../Controller/loginController.php?action=logout">Sair</a>
			</li>
			
		</ul>
	</div>
	 -->
<div id="wrapper" class="container">

	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <div id="menu">
    <ul>
    <?php 
    	foreach ($usuario_logado_permissoes as $value) {
    		$permissao = new Permissao();
    		$permissao->get($value);
    ?>
    <li><a href="<?php echo $permissao->getLink();?>"  title="<?php echo $permissao->getNome();?>" class="botao_left botaoGoogleGrey"><?php echo $permissao->getNome();?></a></li>
    <?php		
    	}    
    ?>	
    </ul>
    <?php include_once 'inc/info_usuario_inc.php';?>    
    </div>      
    
    <div class="white">
    <br />
		<?php 
			$p_selecionado = new ProcessoAvaliacao();
			$p_selecionado->get(6);
			
		?>
        <h3>Processo de Avalia&ccedil;&atilde;o n° <?php echo $p_selecionado->getId();?></h3>
        <h5>Nome: <?php echo $p_selecionado->getDescricao();?></h5>
        <h5>Início em: <?php echo $p_selecionado->getInicio();?></h5>
        <h5>Término em: <?php echo $p_selecionado->getFim();?></h5>
        <h5>Status: <?php echo $p_selecionado->getAtivo();?></h5>
        <hr />
        
        </div><!-- fecha div white -->
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;<?php echo date("Y");?> - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
</body>

</html>
