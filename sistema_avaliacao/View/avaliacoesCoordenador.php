<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/TurmaHasAluno.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';

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

// // $aluno = unserialize($_SESSION["s_aluno"]);
// if(isset($_SESSION["s_aluno"])){
// 	$str = $_SESSION["s_aluno"];
// 	if($str instanceof Aluno){
// 		$aluno = $str;
// 	}else{
// 		$aluno = unserialize($_SESSION["s_aluno"]);
// 	}

// 	//� importante guardar o ra pois a cada nova consulta sql precisaremos de um 'novo' aluno
// 	//e para obter o 'novo' aluno precisamos do ra dele
// 	$ra = $aluno->getRa();
// }



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
    		<h2><?php echo $msgAvaliacao;?></h2>
        	<span class="btn-default">
        		<a href='javascript:;' onclick="document.getElementById('status').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">OK</a>
        	</span>
       	</div>
    </div>
<?php } ?>
	<?php include_once 'inc/header_inc.php';?>
    <div id="content">
		<?php include_once 'inc/menu_coord_inc.php';?> 	      
    
    <?php 
    if($dentro_do_prazo){
    ?>
    
    	
    	<?php
    	//
    	$coordenadores_avaliados = new Turma();
    	$coordenadores_avaliados->alias('pC');
    	
    	$avC = new Avaliacao();
    	$coordenadores_avaliados->join($avC, 'INNER', 'avC', "coordenadorId", "avaliador");
    	$coordenadores_avaliados->select("pC.periodoLetivo, avC.dataAvaliacao, pC.coordenadorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.subtipoAvaliacao, avC.avaliador");
    	$coordenadores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and avC.avaliador = '".$professor_id."' and (avC.subtipoAvaliacao = 'Auto-avaliação-coordenador' or avC.subtipoAvaliacao = 'Coordenador')");
    	$coordenadores_avaliados->groupBy("avC.itemAvaliado");
    	
    	$coordenadores_avaliados->find();
    	$coordenadoresAvaliadoslista = array();
    	while ($coordenadores_avaliados->fetch()) {
    		$coordenadoresAvaliadoslista[] = $coordenadores_avaliados->item_avaliado;
    	}
    	//debug
    	//sprint_r($coordenadoresAvaliadoslista);
    	 
    	    	
    	
    	//verifica se a instituicao foi avaliada
    	$professorB = new Professor();
    	$professorB->get($professor_id);
    	 
    	$professorB->alias('pB');
    	 
    	$tB = new Turma();
    	$avB = new Avaliacao();
    	$professorB->join($tB,'INNER','tB',"id","coordenadorId");
    	 
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
    	$autoAvaliacao->alias('pD');
    	
    	$avD = new Avaliacao();
    	$autoAvaliacao->join($avC, 'INNER', 'avD', "professorId", "avaliador");
    	$autoAvaliacao->select("pD.periodoLetivo, avD.dataAvaliacao, pD.coordenadorId, avD.itemAvaliado, avD.tipoAvaliacao, avD.avaliador");
    	$autoAvaliacao->where("pD.periodoLetivo = '".$periodo_atual."' and pD.professorId = avD.avaliador and avD.avaliador = '".$professor_id."' and avD.subtipoAvaliacao = 'Auto-avaliação-professor' ");
    	$autoAvaliacao->groupBy("avD.itemAvaliado");
    	
    	$auto_avaliacao_realizada = $autoAvaliacao->find(true);
    		
    	//FIM verificacao auto-avaliacao
    	
    	
    	//verifica quais professores foram avaliados
    	$professores_avaliados = new Turma();
    	$professores_avaliados->alias('pF');
    	
    	$avF = new Avaliacao();
    	$professores_avaliados->join($avF, 'INNER', 'avF', "coordenadorId", "avaliador");
    	$professores_avaliados->select("pF.periodoLetivo, avF.dataAvaliacao, pF.coordenadorId, avF.itemAvaliado, avF.tipoAvaliacao, avF.subtipoAvaliacao, avF.avaliador");
    	$professores_avaliados->where("pF.periodoLetivo = '".$periodo_atual."' and avF.tipoAvaliacao = 'Coordenador' and avF.subtipoAvaliacao = 'Docente' and pF.coordenadorId = avF.avaliador and avF.avaliador = '".$professor_id."' ");
    	$professores_avaliados->groupBy("avF.itemAvaliado");
    	
    	$professores_avaliados->find();
    	$professoresAvaliadoslista = array();
    	while ($professores_avaliados->fetch()) {
    		$professoresAvaliadoslista[] = $professores_avaliados->item_avaliado;    		
    	}
    	
    	//debug
    	//print_r($professoresAvaliadoslista);
    	
    	//FIM verificacao professores avaliados
    	
    	
    	//verifica se todas as avaliacoes foram feitas
    	$avaliou_tudo = false;
    	if($coordenadores_avaliados->fetch() != "" && $professores_avaliados->fetch() != "" && $auto_avaliacao_realizada != 0 && $instituicao_foi_avaliada != 0){
    		$avaliou_tudo = true;
    	}
    	
    	//if($avaliou_tudo == false){
    	    	
    	?>
    	<div class="white">
    	<h3>Avaliações Pendentes</h3>
    	<?php
    	
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
    	    	
    	<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Coordenador&subtipo=Instituição"  title="Avaliar a Instituição" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    	<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Coordenador&subtipo=Instituição");?>"  title="Avaliar a Instituição" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	    	
    	</div>
    	<?php
    	}
    	
    	//pega a avaliacao dos coordenadores
    	$professor = new Professor();
    	$professor->get($professor_id);
    	
    	$professor->alias('p');
    	$t = new Turma();
    	$av = new Avaliacao();
    	
    	$professor->join($t,'INNER','t',"id","professorId");
    	    	 
    	//$professor->select("t.periodoLetivo, t.curso, t.coordenadorId, av.itemAvaliado");
    	$professor->select("t.periodoLetivo, t.curso, t.professorId, t.coordenadorId");
    	$professor->where("t.periodoLetivo = '".$periodo_atual."'");
    	$professor->groupBy("t.coordenadorId");
    	 
    	 
    	$qtd = $professor->find();
    	
    	//se qtd = 0 ent�o o professor � s� coordenador
    	if($qtd == 0){
    		$professor = new Professor();
    		$professor->get($professor_id);
    		 
    		$professor->alias('p');
    		$t = new Turma();
    		$av = new Avaliacao();
    		 
    		$professor->join($t,'INNER','t',"id","coordenadorId");
    		 
    		//$professor->select("t.periodoLetivo, t.curso, t.coordenadorId, av.itemAvaliado");
    		$professor->select("t.periodoLetivo, t.curso, t.professorId, t.coordenadorId");
    		$professor->where("t.periodoLetivo = '".$periodo_atual."'");
    		$professor->groupBy("t.coordenadorId");
    		
    		$qtd = $professor->find();
    	}
    	
    	//debug
    	//echo "Cursos encontrados: ".$qtd;
    	$contador = 1;
    	while( $professor->fetch() ) {
    		$id_coordenador = $professor->coordenador_id;
    		 
    		//verifica se o professor esta na lista de avaliados
    		if(!in_array($id_coordenador, $coordenadoresAvaliadoslista)){
    			//pega o professor
    			$prof = new Professor();
    			$prof->get($id_coordenador);
    			 
    			?>
    			<?php 
    		    	    		
    		    	    		if($id_coordenador == $professor_id){
    		    	    		
    		    	    		
    		    	    		if($contador == 1){
    		    	    		?>
    		    	    		<div id="avaliacao_box">
	    		    	    		<div class="div1">
	    		    	    			<div class="photo">
	    		    	    				<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
	    		    	    			</div>
    		    	    				<div class="description">
		    		    	    			<h4><?php echo utf8_encode($prof->getNome())?></h4>
		    		    	    			<h4><span>Coordenador (Auto-avalia&ccedil;&atilde;o)</span></h4>
    		    	    				</div>
    		    	    			</div>
    		    	    			 
    		    	    			<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Coordenador&subtipo=Auto-avaliação-coordenador"  title="Auto-avalia&ccedil;&atilde;o como Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    		    	    			<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Coordenador&subtipo=Auto-avaliação-coordenador");?>"  title="Auto-avalia&ccedil;&atilde;o como Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    		    	    			 
    		    	    		<?php 
    		    	    		}//fecha if contador
    		    	    		$contador++;
    		    	    		}
    		    	    		else{
    		    	    		?>
    		    	    		<div id="avaliacao_box">
    		    	    		<div class="div1">
    		    	    		<div class="photo">
    		    	    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    		    	    		</div>
    		    	    		<div class="description">
    		    	    			<h4><?php echo utf8_encode($prof->getNome())?></h4>
    		    	    			<h4><span>Coordenador</span></h4>
    		    	    			</div>
    		    	    			</div>
    		    	    			 
    		    	    			<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Professor&subtipo=Coordenador&coordenador_id=<?php //echo $prof->getId();?>"  title="Avaliar Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    		    	    			<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Coordenador&coordenador_id=".$prof->getId());?>"  title="Avaliar Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    		    	    			 
    		    	    		<?php }?>
    		    	    		    		    	
    		    	    		</div>
    			
    	    	    		
    	    	    		
    	    	    		<?php    	    
    	    	    	}//fecha if in_array()
    				}//fecha while
    				
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
    							    		<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Auto-avaliação-professor");?>"  title="Auto-avaliação como Docente" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    							
    							    		</div>
    							    		<?php 
    							    					    
    							    	}  //fecha while
    						}//fecha if-else  	
    				    	    	
    				    	    	
    				    	    	
    						// fecha auto-avaliacao----------------------
    				
    	$curso_foi_avaliado = 1;
    	if($curso_foi_avaliado == 0){
    		//debug
//     		echo "curso n�o foi avaliado";
    		//pega a avaliacao do curso
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
    		 
    		$professor->groupBy("t.curso");
    		 
    		//colocar true pra pegar s� o primeiro registro,
    		//nesse caso s� interessa pegar um curso dele
    		$qtd = $professor->find();
    		
    		
    		$contador = 1;
    		while( $professor->fetch() ) {
    			$id_coordenador = $professor->coordenador_id;
    			 
    			//pega o professor
    			$prof = new Professor();
    			$prof->get($id_coordenador);
    			 
    			?>
    		    	    		
    		    	    		<?php 
    		    	    		
    		    	    		if($id_coordenador == $professor_id){
    		    	    		
    		    	    		
    		    	    		if($contador == 1){
    		    	    		?>
    		    	    		<div id="avaliacao_box">
    		    	    		<div class="div1">
    		    	    		<div class="photo">
    		    	    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    		    	    		</div>
    		    	    		<div class="description">
    		<!--     	    			<h4><span>Auto-avalia&ccedil;&atilde;o</span></h4> -->
    		    	    			<h4><span>Coordenador: </span><?php echo utf8_encode($prof->getNome())?> (Auto-avalia&ccedil;&atilde;o)</h4>
    		    	    			</div>
    		    	    			</div>
    		    	    			 
    		    	    			<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Coordenador&subtipo=Auto-avaliação");?>"  title="Auto-avalia&ccedil;&atilde;o" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    		    	    			 
    		    	    		<?php 
    		    	    		}//fecha if contador
    		    	    		$contador++;
    		    	    		}
    		    	    		else{
    		    	    		?>
    		    	    		<div id="avaliacao_box">
    		    	    		<div class="div1">
    		    	    		<div class="photo">
    		    	    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    		    	    		</div>
    		    	    		<div class="description">
    		    	    			<h4><span>Coordenador: </span><?php echo utf8_encode($prof->getNome())?></h4>
    		    	    			</div>
    		    	    			</div>
    		    	    			 
    		    	    			<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Professor&subtipo=Coordenador&coordenador_id=".$prof->getId());?>"  title="Avaliar Coordenador" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    		    	    			 
    		    	    		<?php }?>
    		    	    		    		    	
    		    	    		</div>
    		    	    		
    		    	    		<?php    	    
    		    	    	}//fecha while
    	}else{    		
    	
    	
		}//fecha avaliacao do curso
		

		//pega professores da coordenacao
		$professoresdacoordenacao = new Turma();
		//$professoresdacoordenacao->alias("pdc");
		$professoresdacoordenacao->periodoLetivo = $periodo_atual;
		$professoresdacoordenacao->coordenadorId = $professor_id;
		$professoresdacoordenacao->where("professor_id != ".$professor_id);
		$professoresdacoordenacao->group("professorId");
		
		//$professoresdacoordenacao->select("pdc.idTurma, pdc.nomeDisciplina, pdc.professorId, pdc.periodoLetivo, pdc.coordenadorId, pdc.curso");
		
		$qtd = $professoresdacoordenacao->find();
    	
   		while( $professoresdacoordenacao->fetch() ) {
    		//pega o id do professor
    		$id_professor = $professoresdacoordenacao->professorId;
    		
    		//verifica se o professor esta na lista de avaliados
    		if(!in_array($id_professor, $professoresAvaliadoslista)){
    		
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
    		<h4><span>Docente</span></h4>
    		</div>
    		</div>

    		<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Coordenador&subtipo=Docente&docenteId=".$prof->getId());?>"  title="Avaliar o docente" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>

    		</div>
    		<?php 
    		}//fecha if
    
    	}

    	if($avaliou_tudo == true){
    		echo "<h4>Você concluiu todas as avaliações. A instituição agradece a sua colaboração.</h4>";
    	}
//     	if($coordenadores_avaliados->fetch() != "" && $professores_avaliados->fetch() != "" && $auto_avaliacao_realizada != 0 && $instituicao_foi_avaliada != 0){
//     		echo "<h4>Não existem avaliações pendentes.</h4>";
//     	}
    	
    	?>
    	
    	</div><!-- fecha div white -->
        <br />
        <br />
        
        <?php 
    	//}//FECHA if que verifica se acabaram as avaliacoes pendentes
        ?>
        
        
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
    	$curso_foi_avaliado = 0;
    	if($curso_foi_avaliado != 0){
    	//pega a avaliacao do curso
    		$professorX = new Professor();
    		$professorX->get($professor_id);
    	
    		$professorX->alias('pX');
    		$tX = new Turma();
    		$avX = new Avaliacao();
    	
    		$professorX->join($tX,'INNER','tX',"id","professorId");
    		$professorX->join($avX, 'INNER', 'avX', "id", "avaliador");
    		$professorX->select("tX.periodoLetivo, tX.curso, tX.coordenadorId, avX.dataAvaliacao");
    		$professorX->where("tX.periodoLetivo = '".$periodo_atual."'");
    		$professorX->groupBy("tX.curso");
    		
    		//true pra pegar s� o primeiro registro
    		$qtd = $professorX->find();

    		$contador = 1;
    		while( $professorX->fetch() ) {
      			 
    			$id_coordenador = $professorX->coordenador_id;
    			 
    			//pega o professor
    			$prof = new Professor();
    			$prof->get($id_coordenador);
    			 
    			?>
    			<?php 
    	    		
    	    		if($id_coordenador == $professor_id){
    	    		
    	    		
    	    		if($contador == 1){
    	    		?>
    	    		<div id="avaliacao_box">
    	    	    	<div class="div1">
    	    	    		<div class="photo">
    	    	    			<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    	    	    		</div>
    	    	    		<div class="description">
    	    					<h4><?php echo utf8_encode($prof->getNome())?></h4>
    	    					<h4><span>Coordenador (Auto-avalia&ccedil;&atilde;o)</span></h4>
    	    				</div>
    	    			</div>
    	    			<div class="div2">
			    	    	<img class="ok" src="css/images/img-ok.png" /><br />
			    	    	<h4>Avaliado em:</h4>
			    	    	<h4><?php echo $professorX->data_avaliacao;?></h4>
			    	    </div>
			    	</div>    	
    	    		<?php 
    	    		}//fecha if contador
    	    		$contador++;
    	    		}
    	    		else{
    	    		?>
    	    		<div id="avaliacao_box">
    	    	    	<div class="div1">
    	    	    		<div class="photo">
    	    	    		<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    	    	    		</div>
    	    	    		<div class="description">
    	    	    		<h4><span>Auto-avalia&ccedil;&atilde;o</span></h4>
			    	    	</div>
			    	    	</div>
    	    		<div class="div2">
			    	    		<img class="ok" src="css/images/img-ok.png" /><br />
			    	    	    <h4>Avaliado em:</h4>
			    	    	    <h4><?php echo $professorX->data_avaliacao;?></h4>
			    	    	</div>
    	    		</div>	 
    	    		<?php }?>
    	    	    		
    	    	    		
    	    	    		<?php 
    	    	    
    	    	    	}//fecha while
    	}else{
    		//faz nada    		
    	}
    	    	
    	//////
    	//pega a avaliacao dos coordenadores
    	$coordenadores_avaliados = new Turma();
    	$coordenadores_avaliados->alias('pC');
    	
    	$avC = new Avaliacao();
    	$coordenadores_avaliados->join($avC, 'INNER', 'avC', "coordenadorId", "avaliador");
    	$coordenadores_avaliados->select("pC.periodoLetivo, avC.dataAvaliacao, pC.coordenadorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.subtipoAvaliacao, avC.avaliador");
    	$coordenadores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and pC.coordenadorId = avC.avaliador and avC.avaliador = '".$professor_id."' and (avC.subtipoAvaliacao = 'Auto-avaliação-coordenador' or avC.subtipoAvaliacao = 'Coordenador')");
//     	$coordenadores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and avC.tipoAvaliacao = 'Professor' and pC.professorId = avC.avaliador and avC.avaliador = '".$professor_id."' and avC.itemAvaliado != 'Instituição' and avC.itemAvaliado != 'Auto-avaliação'");
    	$coordenadores_avaliados->groupBy("avC.itemAvaliado");
    	
    	$coordenadores_avaliados->find();
    	 
    	while( $coordenadores_avaliados->fetch() ) {
    		$id_coordenador = $coordenadores_avaliados->item_avaliado;
    		 
    		//pega o professor
    		$prof = new Professor();
    		$prof->get($id_coordenador);
    	
    		?>
    		
    		<?php 
    		    	    		
    		    	    		if($id_coordenador == $professor_id){
    		    	    		
    		    	    		
    		    	    		if($contador == 1){
    		    	    		?>
    		    	    		<div id="avaliacao_box">
    		    	    			<div class="div1">
    		    	    				<div class="photo">
    		    	    					<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    		    	    				</div>
    		    	    				<div class="description">
		    		    	    			<h4><?php echo utf8_encode($prof->getNome())?></h4>
		    		    	    			<h4><span>Coordenador (Auto-avalia&ccedil;&atilde;o)</span></h4>
    		    	    				</div>
    		    	    			</div>
    		    	    			 
    		    	    			<div class="div2">
    				    	    		<img class="ok" src="css/images/img-ok.png" /><br />
    				    	    	    <h4>Avaliado em:</h4>
    				    	    	    <h4><?php echo datetime_to_ptbr($coordenadores_avaliados->data_avaliacao);?></h4>
    				    	    	</div>
    				    	    </div>
    		    	    			 
    		    	    		<?php 
    		    	    		}//fecha if contador
    		    	    		$contador++;
    		    	    		}
    		    	    		else{
    		    	    		?>
    		    	    		<div id="avaliacao_box">
    		    	    			<div class="div1">
    		    	    				<div class="photo">
    		    	    					<img src="<?php echo pegaImagem($prof->getId()); ?>" alt="<?php echo utf8_encode($prof->getNome())?>" />
    		    	    				</div>
    		    	    				<div class="description">
		    		    	    			<h4><?php echo utf8_encode($prof->getNome())?></h4>
		    		    	    			<h4><span>Coordenador</span></h4>
    		    	    				</div>
    		    	    			</div>
    		    	    			 
    		    	    			<div class="div2">
    				    	    		<img class="ok" src="css/images/img-ok.png" /><br />
    				    	    	    <h4>Avaliado em:</h4>
    				    	    	    <h4><?php echo $coordenadores_avaliados->data_avaliacao;?></h4>
    				    	    	</div>
    		    	    		</div>	 
    		    	    		<?php }?>
    		    	    		
    		    	    		
    	    	    	    		
    	    	    	    		
    	    	    	    		<?php    	    
    	    	    	    	}
    	    				//fecha while
    	//fecha avaliacao dos coordenadores
    	    	
    	    	    	    	//auto-avaliacao-----------------------------
    	    	    	    	if($auto_avaliacao_realizada != 0){
    	    	    	    	
    	    	    	    		//pega professores da coordenacao
    	    	    	    		$autoAvaliacao = new Turma();
    	    	    	    		$autoAvaliacao->alias('pE');
    	    	    	    	
    	    	    	    		$avE = new Avaliacao();
    	    	    	    		$autoAvaliacao->join($avE, 'INNER', 'avE', "professorId", "avaliador");
    	    	    	    		$autoAvaliacao->select("pE.periodoLetivo, avE.dataAvaliacao, pE.professorId, avE.itemAvaliado, avE.tipoAvaliacao, avE.avaliador, avE.dataAvaliacao");
    	    	    	    		$autoAvaliacao->where("pE.periodoLetivo = '".$periodo_atual."' and avE.tipoAvaliacao = 'Professor' and avE.subtipoAvaliacao = 'Auto-avaliação-professor' and pE.professorId = avE.avaliador and avE.avaliador = '".$professor_id."' ");
    	    	    	    		$autoAvaliacao->groupBy("avE.itemAvaliado");
    	    	    	    		$autoAvaliacao->orderBy("avE.dataAvaliacao DESC");
    	    	    	    		 
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
    	    	    	    	    	    	    			
    	    	    	    	    	    	    			
    	//pega professores da coordenacao
    	$professores_avaliados = new Turma();
    	$professores_avaliados->alias('pC');
    	
    	$avC = new Avaliacao();
    	$professores_avaliados->join($avC, 'INNER', 'avC', "coordenadorId", "avaliador");
    	$professores_avaliados->select("pC.periodoLetivo, avC.dataAvaliacao, pC.coordenadorId, avC.itemAvaliado, avC.tipoAvaliacao, avC.avaliador, avC.dataAvaliacao");
    	$professores_avaliados->where("pC.periodoLetivo = '".$periodo_atual."' and avC.tipoAvaliacao = 'Coordenador' and avC.subtipoAvaliacao = 'Docente' and pC.coordenadorId = avC.avaliador and avC.avaliador = '".$professor_id."' ");
    	$professores_avaliados->groupBy("avC.itemAvaliado");
    	$professores_avaliados->orderBy("avC.dataAvaliacao DESC");
    	
    	$professores_avaliados->find();
    	 
    	while( $professores_avaliados->fetch() ) {
    		//pega o id do professor
    		$id_professor = $professores_avaliados->item_avaliado;
    	
    		//verifica se o professor esta na lista de avaliados
    		//if(in_array($id_professor, $professoresAvaliadoslista)){
    	
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
		    	    			<h4><span>Docente</span></h4>
		    	    		</div>
    	    			</div>
    	
	    	    		<div class="div2">
	    					<img class="ok" src="css/images/img-ok.png" /><br />
	    	    			<h4>Avaliado em:</h4>
	    	    			<h4><?php echo datetime_to_ptbr($professores_avaliados->data_avaliacao);?></h4>
	    				</div>
    	
    	    		</div>
    	    		<?php 
    	    		//}//fecha if
    	    
    	    	}

    	
    	
    	if($coordenadores_avaliados->fetch() == "" && $professores_avaliados->fetch() == "" && $auto_avaliacao_realizada == 0 && $instituicao_foi_avaliada == 0){
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
