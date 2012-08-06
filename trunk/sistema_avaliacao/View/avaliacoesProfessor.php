<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/TurmaHasAluno.php';
require_once '../system/application/models/dao/TurmaHasLaboratorio.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Laboratorio.php';

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
if(isset($_SESSION["s_usuario_logado_permissoes"])){
	$usuario_logado_permissoes = $_SESSION["s_usuario_logado_permissoes"];
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
// 		$dentro_do_prazo = false;
		//$dentro_do_prazo = true;//para testes remover depois
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
<?php include_once 'inc/analytics_inc.php';?>
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
			<li id="username">Ol&aacute;, <?php //echo utf8_encode($usuario_logado->getNome());?> - <a
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
	<?php include_once 'inc/header_inc.php';?>
    <div id="content">
    <?php include_once 'inc/menu_prof_inc.php';?>      
    
    <?php 
    if($dentro_do_prazo){
    ?>
    <div class="white">
    	<h3>Avaliações Pendentes</h3>
    	     	        
    	<?php
    	//
    	$coordenadores_avaliados = new Turma();
    	$coordenadores_avaliados->alias('pC');
    	 
    	$avC = new Avaliacao();
    	$coordenadores_avaliados->join($avC, 'INNER', 'avC', "professorId", "avaliador");
    	$coordenadores_avaliados->select("pC.periodoLetivo, avC.dataAvaliacao, pC.coordenadorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.subtipoAvaliacao, avC.avaliador");
    	$coordenadores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and avC.tipoAvaliacao = 'Professor' and avC.subtipoAvaliacao = 'Coordenador' and pC.professorId = avC.avaliador and avC.avaliador = '".$professor_id."' and avC.itemAvaliado != 'Instituição' and avC.itemAvaliado != 'Auto-avaliação'");
    	$coordenadores_avaliados->groupBy("avC.itemAvaliado");
    	 
    	$coordenadores_avaliados->find();
    	$coordenadoresAvaliadoslista = array();
    	while ($coordenadores_avaliados->fetch()) {
    		$coordenadoresAvaliadoslista[] = $coordenadores_avaliados->item_avaliado;
    	}
    	//debug
    	//print_r($coordenadoresAvaliadoslista);
    	
    	//verifica quais laboratorios o professor usa
    	
    	
    	//verifica quais laboratorios o aluno usa
    	$turmasDoProfessor_array[] = array();
    	 
    	$turmasProfessor = new Turma();
    	$turmasProfessor->periodoLetivo = $periodo_atual;
    	$turmasProfessor->where("professor_id = ".$professor_id);
    	$turmasProfessor->groupBy("nomeDisciplina");
    	    	 
    	$qtd = $turmasProfessor->find();
    	//echo "total de turmas encontradas: ".$qtd;
    	while ($turmasProfessor->fetch()) {
    		$turmasDoProfessor_array[] = $turmasProfessor->idTurma;
    	}
    	//print_r($turmasDoProfessor_array);
    	
    	$labs = new TurmaHasLaboratorio();
    	$labs->find();
    	 
    	$laboratorios = array();
    	 
    	while ($labs->fetch()) {
    		if(in_array($labs->turmaIdTurma, $turmasDoProfessor_array)){
    			$lab_name = new Laboratorio();
    			$lab_name->get($labs->laboratorioId);
    		    			 
    			$laboratorios[] = array("id" => $labs->laboratorioId, "nome" => $lab_name->getNome(),
    	    									"usado" => "sim", "avaliado" => "não");
    		}
    	}
    	//print_r($laboratorios);
    	//
    	
    	//verifica se laboratorios foram avaliados
    	//echo "tamanho: ".sizeof($laboratorios);
    	for ($i = 0; $i < sizeof($laboratorios); $i++) {
    		$lab_avaliado = 0;
    		if($laboratorios[$i]["usado"] == "sim"){
    			//$avaliou_lab[$i+1] = "avaliado";
    			 
    			$lab = new Laboratorio();
    			$lab->get($laboratorios[$i]["id"]);
    			 
    			//verifica se foi avaliado
    			$professorL = new Professor();
    			$professorL->get($professor_id);
    			 
    			 
    			$professorL->alias('pL');
    			 
    			$tC = new Turma();
    			$avC = new Avaliacao();
    			$professorL->join($tC,'INNER','tC');
    			 
    			//ra = id na tabela aluno
    			//avaliador = correspondente na tabela avaliacao
    			$professorL->join($avC, 'INNER', 'avC', "id", "avaliador");
    			$professorL->select("tC.periodoLetivo, avC.dataAvaliacao, pL.id, avC.avaliador");
    			$professorL->where("tC.periodoLetivo = '".$periodo_atual."' and avC.itemAvaliado= 'Lab_".$lab->getNome()."' and pL.id = avC.avaliador");
    			$professorL->groupBy("avC.itemAvaliado");
    			 
    			$lab_avaliado = $professorL->find(true);
    			if($lab_avaliado != 0){
    				$laboratorios[$i]["avaliado"] = "sim";
    			}else{
    				$laboratorios[$i]["avaliado"] = "não";
    			}
    			 
    			 
    			if($laboratorios[$i]["avaliado"] == "não"){
    	
    				?>
    	    	<div id="avaliacao_box">
    	    	<div class="div1">
    	    	<div class="photo">
    	    	<img src="css/images/avatar/default_instituicao.png" alt="" />
    	    	</div>
    	    	<div class="description">
    	    	<h4>FACULDADE UNICAMPO</h4>
    	    	<h4><span>Laboratório de <?php echo utf8_encode($lab->getNome());?></span></h4>
    	    	</div>
    	    	</div>
    	    	
    	    	<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Professor&subtipo=Lab_<?php //echo utf8_encode($lab->getNome());?>"  title="Avaliar o Laboratório de <?php //echo $lab->getNome();?>" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    	    	<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Lab_".utf8_encode($lab->getNome()));?>"  title="Avaliar o Laboratório de <?php echo $lab->getNome();?>" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	    	    	    	
    	    	</div>
    	    	<?php
    	    			}//fecha IF verificação da avaliacao
    	    		}//fecha IF
    	    	}//fecha FOR
    	    	
    	    	//print_r($laboratorios);
    	    	//laboratorios
    	
    	//verifica se a instituicao foi avaliada
    	$professorB = new Professor();
    	$professorB->get($professor_id);
    	 
    	$professorB->alias('pB');
    	 
    	$tB = new Turma();
    	$avB = new Avaliacao();
    	$professorB->join($tB,'INNER','tB',"id","professorId");
    	 
    	//ra = id na tabela aluno
    	//avaliador = correspondente na tabela avaliacao
    	$professorB->join($avB, 'INNER', 'avB', "id", "avaliador");
    	$professorB->select("tB.periodoLetivo, avB.dataAvaliacao, pB.id, avB.avaliador");
    	$professorB->where("tB.periodoLetivo = '".$periodo_atual."' and avB.itemAvaliado= 'Instituição' and pB.id = avB.avaliador");
    	$professorB->groupBy("avB.itemAvaliado");
    	 
    	$instituicao_foi_avaliada = $professorB->find(true);
    	//FIM verificacao avaliacao instituicao
    	
    	//verifica se auto-avaliacao foi feita
    	$autoAvaliacao = new Turma();
    	$autoAvaliacao->alias('pC');
    	 
    	$avC = new Avaliacao();
    	$autoAvaliacao->join($avC, 'INNER', 'avC', "professorId", "avaliador");
    	$autoAvaliacao->select("pC.periodoLetivo, avC.dataAvaliacao, pC.coordenadorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.avaliador");
    	$autoAvaliacao->where("pC.periodoLetivo = '".$periodo_atual."' and pC.professorId = avC.avaliador and avC.avaliador = '".$professor_id."' and avC.subtipoAvaliacao = 'Auto-avaliação-professor' ");
    	$autoAvaliacao->groupBy("avC.itemAvaliado");
    	 
    	$auto_avaliacao_realizada = $autoAvaliacao->find(true);
    	   	
    	    	
    	//FIM verificacao auto-avaliacao
    	
    	
    	
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
    	    	
    	<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Professor&subtipo=Instituição"  title="Avaliar a Instituição" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    	<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Instituição");?>"  title="Avaliar a Instituição" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	    	
    	</div>
    	<?php
    	}
    	
//     	if($curso_foi_avaliado != 0){
//     		//debug
// //     		echo "curso foi avaliado";
//     	}else{    		
    	
    	//pega a avaliacao dos coordenadores
    	$professor = new Professor();
    	$professor->get($professor_id);
    	 
    	$professor->alias('p');
    	$t = new Turma();
    	$av = new Avaliacao();
    	    	
    	$professor->join($t,'INNER','t',"id","professorId");
    	//$professor->join($av,'INNER','av', 'ra', 'avaliador');
    	
    	//$professor->select("t.periodoLetivo, t.curso, t.coordenadorId, av.itemAvaliado");
    	$professor->select("t.periodoLetivo, t.curso, t.coordenadorId");
    	
//     	$professor->where("t.periodoLetivo = '".$periodo_atual."' and t.curso not in(SELECT av.itemAvaliado FROM avaliacao av)");
    	$professor->where("t.periodoLetivo = '".$periodo_atual."'");
//     	$professor->where("t.periodoLetivo = '".$periodo_atual."' and tha.turmaIdTurma = t.idTurma and tha.avaliado is null");
//     	$professor->where("t.periodoLetivo = '".$periodo_atual."' and t.curso = av.itemAvaliado and a.ra = av.avaliador");
    	
    	$professor->groupBy("t.coordenadorId");
    	
    	
    	$qtd = $professor->find();

		while( $professor->fetch() ) {
    		$id_coordenador = $professor->coordenador_id;
    	
    		//verifica se o professor esta na lista de avaliados
    		if(!in_array($id_coordenador, $coordenadoresAvaliadoslista)){
    		//pega o professor
    		$prof = new Professor();
    		$prof->get($id_coordenador);
    	
    		?>
    	    		<div id="avaliacao_box">
    	    		<div class="div1">
    	    		<div class="photo">
    	    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    	    		</div>
    	    		<div class="description">
    	    		<h4><?php echo strtoupper(utf8_encode($prof->getNome())); ?></h4>
    	    		<h4><span>Coordenador</span></h4>
    	    		</div>
    	    		</div>
    	
    	    		<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Professor&subtipo=Coordenador&coordenador_id=<?php //echo $prof->getId();?>"  title="Avaliar Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    	    		<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Coordenador&coordenador_id=".$prof->getId());?>"  title="Avaliar Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	
    	    		</div>
    	    		
    	    		<?php    	    
    	    	}
			}//fecha while
    	//}//fecha if in_array()
		//fecha avaliacao dos coordenadores
		
			
			
    	//auto-avaliacao-----------------------------
		if($auto_avaliacao_realizada != 0){
			//foi auto-avaliado
		}else{
				
			//pega professores da coordenacao
			$autoAvaliacao = new Turma();
			//$professoresdacoordenacao->alias("pdc");
			$autoAvaliacao->periodoLetivo = $periodo_atual;
			$autoAvaliacao->professorId = $professor_id;
			//$professoresdacoordenacao->where("professor_id != ".$professor_id);
			$autoAvaliacao->group("professorId");
			
			//$professoresdacoordenacao->select("pdc.idTurma, pdc.nomeDisciplina, pdc.professorId, pdc.periodoLetivo, pdc.coordenadorId, pdc.curso");
			
			$qtd = $autoAvaliacao->find();
			 
			while( $autoAvaliacao->fetch() ) {
				//pega o id do professor
				$id_professor = $autoAvaliacao->professorId;
			
									//pega o professor
					$prof = new Professor();
					$prof->get($id_professor);
			
			
			
					?>
			    		<div id="avaliacao_box">
			    		<div class="div1">
			    		<div class="photo">
			    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
			    		</div>
			    		<div class="description">
			    		<h4><?php echo strtoupper(utf8_encode($prof->getNome())); ?></h4>
			    		<h4><span>Docente (Auto-avaliação)</span></h4>
			    		</div>
			    		</div>
			
			    		<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Professor&subtipo=Auto-avaliação-professor"  title="Auto-avaliação como Docente" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
			    		<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Auto-avaliação-professor"); ?>"  title="Auto-avaliação como Docente" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
			
			    		</div>
			    		<?php 
			    					    
			    	}  //fecha while
		}//fecha if-else  	
    	    	
    	    	
    	    	
		// fecha auto-avaliacao----------------------

		
    		
    		

    		?>
    	
        </div><!-- fecha div white -->
        <br />
        <br />
        <div class="white">
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
    	    <h4><?php echo datetime_to_ptbr($professorB->data_avaliacao);?></h4>
    	</div>
    	    	
    	</div>
    	
    	<?php
    	}
    	/////
    	
    	//pega a avaliacao dos coordenadores
    	$coordenadores_avaliados = new Turma();
    	$coordenadores_avaliados->alias('pC');
    	 
    	$avC = new Avaliacao();
    	$coordenadores_avaliados->join($avC, 'INNER', 'avC', "professorId", "avaliador");
    	$coordenadores_avaliados->select("pC.periodoLetivo, avC.dataAvaliacao, pC.coordenadorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.avaliador");
    	$coordenadores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and avC.tipoAvaliacao = 'Professor' and avC.subtipoAvaliacao = 'Coordenador' and pC.professorId = avC.avaliador and avC.avaliador = '".$professor_id."' ");
    	$coordenadores_avaliados->groupBy("avC.itemAvaliado");
    	 
    	$coordenadores_avaliados->find();
    	
    	while( $coordenadores_avaliados->fetch() ) {
    		$id_coordenador = $coordenadores_avaliados->item_avaliado;
    		
    			//pega o professor
    			$prof = new Professor();
    			$prof->get($id_coordenador);
    			 
    			?>
    	    	    		<div id="avaliacao_box">
    	    	    		<div class="div1">
    	    	    		<div class="photo">
    	    	    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    	    	    		</div>
    	    	    		<div class="description">
    	    	    		<h4><?php echo strtoupper(utf8_encode($prof->getNome())); ?></h4>
    	    	    		<h4><span>Coordenador</span></h4>
			    	    	</div>
			    	    	</div>
			    	    	    	
			    	    	<div class="div2">
			    	    		<img class="ok" src="css/images/img-ok.png" /><br />
			    	    	    <h4>Avaliado em:</h4>
			    	    	    <h4><?php echo $coordenadores_avaliados->data_avaliacao;?></h4>
			    	    	</div>
			    	    	    	
			    	    	</div>
    	    	    		
    	    	    		<?php    	    
    	    	    	}
    				//fecha while
//fecha avaliacao dos coordenadores
    	
    	    	    	//auto-avaliacao-----------------------------
    	    	    	if($auto_avaliacao_realizada != 0){
    	    	    	    	    	    	
    	    	    		//pega professores da coordenacao
    	    	    		$autoAvaliacao = new Turma();
					    	$autoAvaliacao->alias('pC');
					    	
					    	$avC = new Avaliacao();
					    	$autoAvaliacao->join($avC, 'INNER', 'avC', "professorId", "avaliador");
					    	$autoAvaliacao->select("pC.periodoLetivo, avC.dataAvaliacao, pC.professorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.avaliador, avC.dataAvaliacao");
					    	$autoAvaliacao->where("pC.periodoLetivo = '".$periodo_atual."' and avC.tipoAvaliacao = 'Professor' and avC.subtipoAvaliacao = 'Auto-avaliação-professor' and pC.professorId = avC.avaliador and avC.avaliador = '".$professor_id."' ");
					    	$autoAvaliacao->groupBy("avC.itemAvaliado");
					    	$autoAvaliacao->orderBy("avC.dataAvaliacao DESC");
    	    	    			
    	    	    		$qtd = $autoAvaliacao->find();
    	    	    		
    	    	    		
    	    	    	
    	    	    		while( $autoAvaliacao->fetch() ) {
    	    	    			//pega o id do professor
    	    	    			$id_professor = $autoAvaliacao->professorId;
    	    	    				
    	    	    			//pega o professor
    	    	    			$prof = new Professor();
    	    	    			$prof->get($id_professor);
    	    	    				
    	    	    				
    	    	    				
    	    	    			?>
    	    	    				    		<div id="avaliacao_box">
    	    	    				    		<div class="div1">
    	    	    				    		<div class="photo">
    	    	    				    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    	    	    				    		</div>
    	    	    				    		<div class="description">
    	    	    				    		<h4><?php echo strtoupper(utf8_encode($prof->getNome())); ?></h4>
    	    	    				    		<h4><span>Docente (Auto-avaliação)</span></h4>
    	    	    				    		</div>
    	    	    				    		</div>
    	    	    				
    	    	    				    		<div class="div2">
							    					<img class="ok" src="css/images/img-ok.png" /><br />
							    	    			<h4>Avaliado em:</h4>
							    	    			<h4><?php echo datetime_to_ptbr($autoAvaliacao->data_avaliacao);?></h4>
							    				</div>
    	    	    				
    	    	    				    		</div>
    	    	    				    		<?php 
    	    	    				    					    
    	    	    				    	}  //fecha while
    	    	    			}//fecha if   	    	    	    	    	
    	    	    	    	    	
    	    	    	    	    	
    	    	    			// fecha auto-avaliacao----------------------
    	

    	    	
    	//////
    	    	
    	
    	//pega professores da coordenacao
//     	$professores_avaliados = new Turma();
//     	$professores_avaliados->alias('pC');
    	
//     	$avC = new Avaliacao();
//     	$professores_avaliados->join($avC, 'INNER', 'avC', "professorId", "avaliador");
//     	$professores_avaliados->select("pC.periodoLetivo, avC.dataAvaliacao, pC.professorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.avaliador, avC.dataAvaliacao");
//     	$professores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and avC.tipoAvaliacao = 'Coordenador' and pC.professorId = avC.avaliador and avC.avaliador = '".$professor_id."' and avC.itemAvaliado != 'Instituição' and avC.itemAvaliado != 'Auto-avaliação'");
//     	$professores_avaliados->groupBy("avC.itemAvaliado");
    	
//     	$professores_avaliados->find();
    	 
//     	while( $professores_avaliados->fetch() ) {
//     		//pega o id do professor
//     		$id_professor = $professores_avaliados->item_avaliado;
    	    	
//     			//pega o professor
//     			$prof = new Professor();
//     			$prof->get($id_professor);
    	
    	
    	
    			?>
    	    		<!-- <div id="avaliacao_box">
    	    		<div class="div1">
    	    		<div class="photo">
    	    		<img src="<?php //echo pegaImagem($prof->getId()); ?>" alt="<?php //echo utf8_encode($prof->getNome())?>" />
    	    		</div>
    	    		<div class="description">
    	    		<h4><span>Professor: </span><?php //echo strtoupper(utf8_encode($prof->getNome())); ?></h4>
    	    		</div>
    	    		</div>
    	
    	    		<div class="div2">
    					<img class="ok" src="css/images/img-ok.png" /><br />
    	    			<h4>Avaliado em:</h4>
    	    			<h4><?php //echo datetime_to_ptbr($professores_avaliados->data_avaliacao);?></h4>
    				</div>
    	
    	    		</div> -->
    	    		<?php 
    	    		//}//fecha if
    	    
    	    	//}
//     	    	if($professoresdacoordenacao->fetch() == ""){
//     	    		echo "Nenhuma avalia&ccedil;&atilde;o pendente";
//     	    	}
    	
    	
    	if($curso_foi_avaliado == 0 && $instituicao_foi_avaliada == 0){
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
</body>
</html>
