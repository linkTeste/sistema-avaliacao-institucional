<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/TurmaHasAluno.php';
require_once '../system/application/models/dao/Funcionario.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Laboratorio.php';
require_once '../system/application/models/dao/TurmaHasLaboratorio.php';

require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

if(isset($_GET['status'])){
	$status = $_GET['status'];
	if($status == "sucesso"){
		$msgAvaliacao = "Avaliação Realizada com sucesso!";
	}
}

if(isset($_SESSION["s_usuario_logado"])){
	$str = $_SESSION["s_usuario_logado"];
	if($str instanceof Professor){
		$usuario_logado = $str;
	}else{
		$usuario_logado = unserialize($_SESSION["s_usuario_logado"]);
	}

	$professor_id = $usuario_logado->getId();
}



if(isset($_SESSION["s_periodo"])){
	$periodo_atual = $_SESSION["s_periodo"];
	// 	echo "periodo: ".$periodo_atual;
}else{
	header("Location: login.php");
}

if(isset($_SESSION["s_processo"])){
	$processo = unserialize($_SESSION["s_processo"]);

	$hoje = date("Y-m-d H:i:s");
	//debug
	// 	echo "hoje: ".$hoje;
	// 	echo "<br />";
	// 	echo "inicio: ".$processo->getInicio();
	// 	echo "<br />";

	$datetime = $processo->getInicio();
	$yr=strval(substr($datetime,0,4));
	$mo=strval(substr($datetime,5,2));
	$da=strval(substr($datetime,8,2));

	$hr=strval(substr($datetime,11,2));
	$mi=strval(substr($datetime,14,2));
	$sg=strval(substr($datetime,17,2));



	if($hoje >= $processo->getInicio() && $hoje <= $processo->getFim()){
		//echo "dentro do prazo";

		$dentro_do_prazo = true;
	}else{
		if($hoje > $processo->getFim()){
			$prazo_expirado = true;
		}
		//echo "fora";
		$dentro_do_prazo = false;
	}

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Página Inicial</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<link href="css/lwtCountdown/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.lwtCountdown-1.0.js"></script>
<script type="text/javascript">
$(function() {
	$('#countdown_dashboard').countDown({
		targetDate: {
			'day': 		<?php echo $da;?>,
			'month': 	<?php echo $mo;?>,
			'year': 	<?php echo $yr;?>,
			'hour': 	<?php echo $hr;?>,
			'min': 		<?php echo $mi;?>,
			'sec': 		<?php echo $sg;?>,
			// time set as UTC 
			'utc':		true			
		},
		omitWeeks: true
	
	});
	

});

</script>


</head>

<body style="background: #fafafa;">








<?php if(isset($_GET['status'])){	?>
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
<?php if(isset($_GET['status'])){	?>
    <div id="status">
    	<div id="status_inside">
    		<h2><?php echo $msgAvaliacao?></h2>
        	<span class="btn-default">
        		<a href='javascript:;' onclick="document.getElementById('status').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">OK</a>
        	</span>
       	</div>
    </div>
<?php } ?>
	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <?php include_once 'inc/menu_funcionario_inc.php';?>      
    
        
    <?php 
    if($dentro_do_prazo){
    ?>
    <div class="avaliacoes-group">
    	<h3>Avaliações Pendentes</h3>
    	    	        
    	<?php
    	    	
    	//verifica se a instituicao foi avaliada
    	$funcionario = new Funcionario();
    	$funcionario->get($professor_id);
    	
    	$funcionario->alias('f');
    	
    	$t = new Turma();
    	$av = new Avaliacao();
    	//$funcionario->join($t,'INNER','t',"id","professorId");
    	
    	//ra = id na tabela aluno
    	//avaliador = correspondente na tabela avaliacao
    	$funcionario->join($av, 'INNER', 'av', "id", "avaliador");
    	$funcionario->select("av.dataAvaliacao, f.id, av.avaliador");
    	$funcionario->where("av.itemAvaliado= 'Instituição' and f.id = av.avaliador");
    	$funcionario->groupBy("av.itemAvaliado");
    	
    	$instituicao_foi_avaliada = $funcionario->find(true);
    	//FIM verificacao avaliacao instituicao
    	
    	
    	
    	if($instituicao_foi_avaliada != 0){
    		//debug
//     		echo "instituicao foi avaliada";
    	}else{
    	?>
    	<div id="avaliacao_box">
    	<div class="div1">
    	<div class="photo">
    	<img src="css/images/avatar/default_instituicao.png" alt="" />
    	</div>
    	<div class="description">
    	<h4>FACULDADE UNICAMPO</h4>
    	<h4><span>Institui&ccedil;&atilde;o</span></h4>
    	</div>
    	</div>
    	    	
    	<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Funcionário&subtipo=Instituição");?>"  title="Avaliar a Instituição" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	    	
    	</div>
    	<?php
    	}
    	?>
    	
    	
    	    	
    	<?php
    	if($instituicao_foi_avaliada != 0){
    		echo "Nenhuma avalia&ccedil;&atilde;o pendente";
    	}
    	
    	
    	?>
    	</div>
        <br />
        <br />
        <div class="avaliacoes-group">
        <h3>Avaliações Realizadas</h3>
        
        
        <?php
        //exibir avalicao da instituicao
    	if($instituicao_foi_avaliada != 0){
		?>
		
		<div id="avaliacao_box">
    	<div class="div1">
    	<div class="photo">
    	<img src="css/images/avatar/default_instituicao.png" alt="" />
    	</div>
    	<div class="description">
    	<h4>FACULDADE UNICAMPO</h4>
    	<h4><span>Institui&ccedil;&atilde;o</span></h4>
    	</div>
    	</div>
    	    	
    	<div class="div2">
    		<img class="ok" src="css/images/img-ok.png" /><br />
    	    <h4>Avaliado em:</h4>
    	    <h4><?php echo datetime_to_ptbr($funcionario->data_avaliacao);?></h4>
    	</div>
    	    	
    	</div>
    	
    	
    	<?php
    	}
    	
    	
    	if($instituicao_foi_avaliada == 0){
    		echo "Nenhuma avalia&ccedil;&atilde;o foi realizada ainda";
    	}
    	
    	?>
        
        </div><!-- fecha div white -->
        <br />
                
        <?php 
    }//fecha verificacao de processo ativo
    else{
    	if($prazo_expirado){
    		?>
    		<h3>O prazo de Avalia&ccedil;&atilde;o expirou!</h3>
    		<?php
    	}else{
    		?>
    		<h3>Inicio do Processo de Avalia&ccedil;&atilde;o em:</h3>
    		<!-- Countdown dashboard start -->
    		<div id="countdown_dashboard">
<!--     		<div class="dash weeks_dash"> -->
<!--     		<span class="dash_title">semanas</span> -->
<!--     		<div class="digit">0</div> -->
<!--     		<div class="digit">0</div> -->
<!--     		</div> -->
    		
    		<div class="dash days_dash">
    						<span class="dash_title">dias</span>
    		<div class="digit">0</div>
    		<div class="digit">0</div>
    		</div>
    		
    		<div class="dash hours_dash">
    						<span class="dash_title">horas</span>
    		<div class="digit">0</div>
    		<div class="digit">0</div>
    		</div>
    		
    		<div class="dash minutes_dash">
    						<span class="dash_title">minutos</span>
    		<div class="digit">0</div>
    		<div class="digit">0</div>
    		</div>
    		
    		<div class="dash seconds_dash">
    						<span class="dash_title">segundos</span>
    		<div class="digit">0</div>
    		<div class="digit">0</div>
    		</div>
    		
    		</div>
    		<!-- Countdown dashboard end -->
    		<?php
    	}
    }
        ?>
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
<?php 

//$_SESSION["aluno"] = serialize($aluno);
// $_SESSION["processo"] = serialize($processo);
?>

<script type="text/javascript">

/*$("a.btn_avaliacao").hover(function(){
	var p = $(this).parent();
	var d = p.get(0).tagName + ".popupAvaliar";

	var s = $(d);
	//alert(s);
	//s.fadeIn();
 if (s.is(":hidden")) {
		//alert("hidden");
		s.fadeIn("slow");
	} else {
		//alert("not hidden");
		s.fadeOut();
	}

});*/

//



</script>

</body>
</html>
