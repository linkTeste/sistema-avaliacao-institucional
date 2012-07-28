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


// $aluno = unserialize($_SESSION["s_aluno"]);
if(isset($_SESSION["s_aluno"])){
	$str = $_SESSION["s_aluno"];
	if($str instanceof Aluno){
		$aluno = $str;
	}else{
		$aluno = unserialize($_SESSION["s_aluno"]);
	}

	//� importante guardar o ra pois a cada nova consulta sql precisaremos de um 'novo' aluno
	//e para obter o 'novo' aluno precisamos do ra dele
	$ra = $aluno->getRa();
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
<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<link href="css/lwtCountdown/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/functions.min.js"></script>
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

<?php include_once 'inc/ie_bugfixes_inc.php';?>

</head>

<body style="background: #fafafa;">








<?php if(isset($_GET['status'])){	?>
	<div id="blackout"></div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<?php } ?>
<!-- <div id="menu_usuario">
		<ul>
			<li><a href="http://www.faculdadeunicampo.edu.br/" target="_blank">Faculdade
					Unicampo</a></li>
			<li><a href="http://mail.faculdadeunicampo.edu.br/" target="_blank">E-mail
					Unicampo</a></li>
			<li id="username">Ol&aacute;, <?php //echo $aluno->getNome();?> - <a
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
    <?php include_once 'inc/menu_aluno_inc.php';?>      
    
        
    <?php 
    if($dentro_do_prazo){
    ?>
    <div class="avaliacoes-group">
    	<h3>Avaliações Pendentes</h3>
     	        
    	<?php
    	//verifica se o curso foi avaliado
    	$alunoA = new Aluno();
    	$alunoA->get($ra);
    	
    	$alunoA->alias('aA');
    	
    	$tA = new Turma();
    	$avA = new Avaliacao();
    	$alunoA->join($tA,'INNER','tA');
    	
    	//ra = id na tabela aluno
    	//avaliador = correspondente na tabela avaliacao
    	$alunoA->join($avA, 'INNER', 'avA', "ra", "avaliador");
    	$alunoA->select("tA.periodoLetivo, tA.curso, tA.coordenadorId, avA.dataAvaliacao, aA.ra, avA.avaliador, avA.processoAvaliacaoId");
    	$alunoA->where("tA.periodoLetivo = '".$periodo_atual."' and avA.processoAvaliacaoId = ".$processo->getId()." and tA.curso = avA.itemAvaliado and aA.ra = avA.avaliador");
    	$alunoA->groupBy("avA.itemAvaliado");
    	
    	$curso_foi_avaliado = $alunoA->find();
    	//FIM verificacao avaliacao curso
    	
    	
    	//verifica se a instituicao foi avaliada
    	$alunoB = new Aluno();
    	$alunoB->get($ra);
    	 
    	$alunoB->alias('aB');
    	 
    	$tB = new Turma();
    	$avB = new Avaliacao();
    	$alunoB->join($tB,'INNER','tB');
    	 
    	//ra = id na tabela aluno
    	//avaliador = correspondente na tabela avaliacao
    	$alunoB->join($avB, 'INNER', 'avB', "ra", "avaliador");
    	$alunoB->select("tB.periodoLetivo, avB.dataAvaliacao, aB.ra, avB.avaliador");
    	$alunoB->where("tB.periodoLetivo = '".$periodo_atual."' and avB.itemAvaliado= 'Instituição' and aB.ra = avB.avaliador");
    	$alunoB->groupBy("avB.itemAvaliado");
    	 
    	$instituicao_foi_avaliada = $alunoB->find(true);
    	//FIM verificacao avaliacao instituicao
    	
    	//verifica se o sistema foi avaliado
//     	$alunoD = new Aluno();
//     	$alunoD->get($ra);
    	
//     	$alunoD->alias('aD');
    	
//     	$tD = new Turma();
//     	$avD = new Avaliacao();
//     	$alunoD->join($tD,'INNER','tD');
    	
//     	$alunoD->join($avD, 'INNER', 'avD', "ra", "avaliador");
//     	$alunoD->select("tD.periodoLetivo, avD.dataAvaliacao, aD.ra, avD.avaliador");
//     	$alunoD->where("tD.periodoLetivo = '".$periodo_atual."' and avD.itemAvaliado= 'Sistema' and aD.ra = avD.avaliador");
//     	$alunoD->groupBy("avD.itemAvaliado");
    	
//     	$sistema_foi_avaliado = $alunoD->find(true);
    	//FIM verificacao avaliacao sistema
    	
    	//verifica se a DIREÇÃO foi avaliada
//     	$alunoDirecao = new Aluno();
//     	$alunoDirecao->get($ra);
    	
//     	$alunoDirecao->alias('aDirecao');
    	
//     	$tDirecao = new Turma();
//     	$avDirecao = new Avaliacao();
//     	$alunoDirecao->join($tDirecao,'INNER','tDirecao');
    	
//     	//ra = id na tabela aluno
//     	//avaliador = correspondente na tabela avaliacao
//     	$alunoDirecao->join($avDirecao, 'INNER', 'avDirecao', "ra", "avaliador");
//     	$alunoDirecao->select("tDirecao.periodoLetivo, avDirecao.dataAvaliacao, aDirecao.ra, avDirecao.avaliador");
//     	$alunoDirecao->where("tDirecao.periodoLetivo = '".$periodo_atual."' and avDirecao.itemAvaliado= 'Direção' and aDirecao.ra = avDirecao.avaliador");
//     	$alunoDirecao->groupBy("avDirecao.itemAvaliado");
    	
//     	$direcao_foi_avaliada = $alunoDirecao->find(true);
    	//FIM verificacao avaliacao DIREÇÃO
    	
    	//verifica se a SECRETARIA foi avaliada --------------------------------------------------------------
//     	$alunoSecretaria = new Aluno();
//     	$alunoSecretaria->get($ra);
    	 
//     	$alunoSecretaria->alias('aSecretaria');
    	 
//     	$tSecretaria = new Turma();
//     	$avSecretaria = new Avaliacao();
//     	$alunoSecretaria->join($tSecretaria,'INNER','tSecretaria');
    	 
//     	//ra = id na tabela aluno
//     	//avaliador = correspondente na tabela avaliacao
//     	$alunoSecretaria->join($avSecretaria, 'INNER', 'avSecretaria', "ra", "avaliador");
//     	$alunoSecretaria->select("tSecretaria.periodoLetivo, avSecretaria.dataAvaliacao, aSecretaria.ra, avSecretaria.avaliador");
//     	$alunoSecretaria->where("tSecretaria.periodoLetivo = '".$periodo_atual."' and avSecretaria.itemAvaliado= 'Direção' and aSecretaria.ra = avSecretaria.avaliador");
//     	$alunoSecretaria->groupBy("avSecretaria.itemAvaliado");
    	 
//     	$Secretaria_foi_avaliada = $alunoSecretaria->find(true);
    	//FIM verificacao avaliacao SECRETARIA
    	
    	//verifica se a BIBLIOTECA foi avaliada --------------------------------------------------------------
//     	$alunoBiblioteca = new Aluno();
//     	$alunoBiblioteca->get($ra);
    	
//     	$alunoBiblioteca->alias('aBiblioteca');
    	
//     	$tBiblioteca = new Turma();
//     	$avBiblioteca = new Avaliacao();
//     	$alunoBiblioteca->join($tBiblioteca,'INNER','tBiblioteca');
    	
//     	//ra = id na tabela aluno
//     	//avaliador = correspondente na tabela avaliacao
//     	$alunoBiblioteca->join($avBiblioteca, 'INNER', 'avBiblioteca', "ra", "avaliador");
//     	$alunoBiblioteca->select("tBiblioteca.periodoLetivo, avBiblioteca.dataAvaliacao, aBiblioteca.ra, avBiblioteca.avaliador");
//     	$alunoBiblioteca->where("tBiblioteca.periodoLetivo = '".$periodo_atual."' and avBiblioteca.itemAvaliado= 'Direção' and aBiblioteca.ra = avBiblioteca.avaliador");
//     	$alunoBiblioteca->groupBy("avBiblioteca.itemAvaliado");
    	
//     	$Biblioteca_foi_avaliada = $alunoBiblioteca->find(true);
    	//FIM verificacao avaliacao BIBLIOTECA
    	
    	//verifica se a TI foi avaliada --------------------------------------------------------------
//     	$alunoTI = new Aluno();
//     	$alunoTI->get($ra);
    	
//     	$alunoTI->alias('aTI');
    	
//     	$tTI = new Turma();
//     	$avTI = new Avaliacao();
//     	$alunoTI->join($tTI,'INNER','tTI');
    	
//     	//ra = id na tabela aluno
//     	//avaliador = correspondente na tabela avaliacao
//     	$alunoTI->join($avTI, 'INNER', 'avTI', "ra", "avaliador");
//     	$alunoTI->select("tTI.periodoLetivo, avTI.dataAvaliacao, aTI.ra, avTI.avaliador");
//     	$alunoTI->where("tTI.periodoLetivo = '".$periodo_atual."' and avTI.itemAvaliado= 'Direção' and aTI.ra = avTI.avaliador");
//     	$alunoTI->groupBy("avTI.itemAvaliado");
    	
//     	$TI_foi_avaliada = $alunoTI->find(true);
    	//FIM verificacao avaliacao TI
    	
    	//verifica quais laboratorios o aluno usa
    	$turmasDoAluno_array[] = array();
    	
    	$turmasAluno = new Aluno();
    	$turmasAluno->get($ra);
    	 
    	$turmasAluno->alias('a');
    	 
    	$t = new Turma();
    	$av = new Avaliacao();
    	// une as classes
    	$turmasAluno->join($t,'INNER','t');
    	 
    	$tha = new TurmaHasAluno();
    	 
    	$turmasAluno->join($tha,'INNER','tha');
    	    	 
    	$turmasAluno->select("t.idTurma, t.nomeDisciplina, t.professorId, t.periodoLetivo, t.curso, tha.avaliado");
    	$turmasAluno->where("t.periodoLetivo = '".$periodo_atual."' and tha.turmaIdTurma = t.idTurma");
    	$turmasAluno->groupBy("t.idTurma");    	
    	    	 
    	$qtd = $turmasAluno->find();
    	while ($turmasAluno->fetch()) {
    		$turmasDoAluno_array[] = $turmasAluno->id_turma;
    	}
    	//print_r($turmasDoAluno_array);
    	
    	$labs = new TurmaHasLaboratorio();
    	$labs->find();
    	
    	$laboratorios = array();
    	
    	while ($labs->fetch()) {
   			if(in_array($labs->turmaIdTurma, $turmasDoAluno_array)){
    			$lab_name = new Laboratorio();
    			$lab_name->get($labs->laboratorioId);
    			
//     			$laboratorios[$labs->laboratorioId]["nome"] = $lab_name->getNome();
//     			$laboratorios[$labs->laboratorioId]["usado"] = "sim";
//     			$laboratorios[$labs->laboratorioId]["avaliado"] = "não";
    			
    			$laboratorios[] = array("id" => $labs->laboratorioId, "nome" => $lab_name->getNome(),
    									"usado" => "sim", "avaliado" => "não");
    			
//     			$laboratorios[]["id"] = $labs->laboratorioId;
//     			$laboratorios[]["nome"] = $lab_name->getNome();
//     			$laboratorios[]["usado"] = "sim";
//     			$laboratorios[]["avaliado"] = "não";
    		}
    	}
    	//print_r($laboratorios);
    	
    	//verifica se laboratorios foram avaliados
    	//echo "tamanho: ".sizeof($laboratorios);
    	for ($i = 0; $i < sizeof($laboratorios); $i++) {
    		$lab_avaliado = 0;
    		if($laboratorios[$i]["usado"] == "sim"){
    			//$avaliou_lab[$i+1] = "avaliado";
    			
    			$lab = new Laboratorio();
    			$lab->get($laboratorios[$i]["id"]);
    			
    			//verifica se foi avaliado
    			$alunoC = new Aluno();
    			$alunoC->get($ra);
    			
    			
    			$alunoC->alias('aC');
    			
    			$tC = new Turma();
    			$avC = new Avaliacao();
    			$alunoC->join($tC,'INNER','tC');
    			
    			//ra = id na taCela aluno
    			//avaliador = correspondente na taCela avaliacao
    			$alunoC->join($avC, 'INNER', 'avC', "ra", "avaliador");
    			$alunoC->select("tC.periodoLetivo, avC.dataAvaliacao, aC.ra, avC.avaliador");
    			$alunoC->where("tC.periodoLetivo = '".$periodo_atual."' and avC.itemAvaliado= 'Lab_".$lab->getNome()."' and aC.ra = avC.avaliador");
    			$alunoC->groupBy("avC.itemAvaliado");
    			
    			$lab_avaliado = $alunoC->find(true);
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
    	
    	<!-- <a href="../Controller/avaliacaoController.php?action=avaliar&tipo=Aluno&subtipo=Lab_<?php //echo utf8_encode($lab->getNome());?>"  title="Avaliar o Laboratório de <?php //echo $lab->getNome();?>" class="botao_right btn_avaliacao botaoWhite">Avaliar</a> -->
    	<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Aluno&subtipo=Lab_".utf8_encode($lab->getNome()));?>"  title="Avaliar o Laboratório de <?php echo $lab->getNome();?>" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	
    	</div>
    	<?php
    			}//fecha IF verificação da avaliacao
    		}//fecha IF
    	}//fecha FOR
    	
    	//print_r($laboratorios);
    	//laboratorios
    	
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
    	    	
    	<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Aluno&subtipo=Instituição");?>"  title="Avaliar a Instituição" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    	    	
    	</div>
    	<?php
    	}
    	?>
    	
    	<?php
    	if($curso_foi_avaliado != 0){
    		//debug
//     		echo "curso foi avaliado";
    	}else{    		
    	
    	//pega a avaliacao do curso
    	$aluno = new Aluno();
    	$aluno->get($ra);
    	 
    	$aluno->alias('a');
    	$t = new Turma();
    	$av = new Avaliacao();
    	    	
    	$aluno->join($t,'INNER','t');
    	//$aluno->join($av,'INNER','av', 'ra', 'avaliador');
    	
    	//$aluno->select("t.periodoLetivo, t.curso, t.coordenadorId, av.itemAvaliado");
    	$aluno->select("t.periodoLetivo, t.curso, t.coordenadorId");
    	
//     	$aluno->where("t.periodoLetivo = '".$periodo_atual."' and t.curso not in(SELECT av.itemAvaliado FROM avaliacao av)");
    	$aluno->where("t.periodoLetivo = '".$periodo_atual."'");
//     	$aluno->where("t.periodoLetivo = '".$periodo_atual."' and tha.turmaIdTurma = t.idTurma and tha.avaliado is null");
//     	$aluno->where("t.periodoLetivo = '".$periodo_atual."' and t.curso = av.itemAvaliado and a.ra = av.avaliador");
    	
    	$aluno->groupBy("t.curso");
    	
    	$qtd = $aluno->find();
    	//debug
//     	echo "cursos encontrados: ".$qtd;

    	while( $aluno->fetch() ) {
    		//pega o id do professor
    	
    		$id_coordenador = $aluno->coordenador_id;
    	
    		//pega o professor
    		$professor = new Professor();
    		$professor->get($id_coordenador);
    	
    		?>
    	    		<div id="avaliacao_box">
    	    		<div class="div1">
    	    		<div class="photo">
    	    		<img src="<?php echo pegaImagem($professor->getId()); ?>" alt="<?php echo utf8_encode($professor->getNome())?>" />
    	    		</div>
    	    		<div class="description">
    	    		    	    		
    	    		<h4><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    	    		<h4><span>Coordenador de <?php echo utf8_encode($aluno->curso); ?></span></h4>
    	    		</div>
    	    		</div>
    					<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Aluno&subtipo=Curso/Coordenador&curso=".utf8_encode($aluno->curso)."&coordenador_id=".$aluno->coordenador_id);?>"  title="Avaliar o professor" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>
    				
    	    		</div>
    	    		
    	    		<?php 
    	    
    	    	}
}
		//fecha avaliacao do curso
		

    	    	
    	$aluno = new Aluno();
    	$aluno->get($ra);
    	
    	$aluno->alias('a');
    	
    	$t = new Turma();
    	$av = new Avaliacao();
    	// une as classes
    	$aluno->join($t,'INNER','t');
    	
    	$tha = new TurmaHasAluno();
    	
    	$aluno->join($tha,'INNER','tha');
    	
    	$aluno->select("t.idTurma, t.nomeDisciplina, t.professorId, t.periodoLetivo, t.curso, tha.avaliado");
    	$aluno->where("t.periodoLetivo = '".$periodo_atual."' and tha.turmaIdTurma = t.idTurma and tha.avaliado is null");
    	
//     	$aluno->join($av, 'INNER', 'av');
//     	$aluno->select("t.idTurma, t.nomeDisciplina, t.professorId");
//     	$aluno->where("t.periodoLetivo = '".$periodo_atual."' and t.idTurma not in(SELECT av.turmaIdTurma FROM avaliacao av where av.alunoRa = '".$ra."')");
    	
    	//todos alunos
//     	$aluno->where("t.periodoLetivo = '".$periodo_atual."' and t.idTurma not in(SELECT av.turmaIdTurma FROM avaliacao av)");
//     	$aluno->where("t.idTurma not in(SELECT av.turmaIdTurma FROM avaliacao av)");
    	    	
    	$aluno->groupBy("t.idTurma");
    	 
    	// recupera os registros
//     	$aluno->find();
    	
    	
//     	if($aluno->fetch() == 0){
//     	  		//� necessario pegar dados do aluno NOVAMENTE
//     		$aluno = new Aluno();
// 			$aluno->get($ra);
    		    		
//     		$t1 = new Turma();
//     		//$av = new Avaliacao();
//     		// une as classes
//     		$aluno->join($t1,'INNER','t1');
    		
//     		//teste
// //     		$aluno->join($tha1,'INNER','tha1');    		 
// //     		$aluno->select("t1.idTurma, t1.nomeDisciplina, t1.professorId, t1.periodoLetivo, t1.curso, tha1.avaliado");
// //     		$aluno->where("t1.periodoLetivo = '".$periodo_atual."' and tha1.turmaIdTurma = t1.idTurma and tha1.avaliado != 'Avaliado'");
    		   		
//     		//faz consulta diferenciada sem o JOIN e sem SUBSELECT no WHERE
//     		$aluno->select("t1.idTurma, t1.nomeDisciplina, t1.professorId");
//     		$aluno->where("t1.periodoLetivo = '".$periodo_atual."'");
    		
//     		$aluno->groupBy("t1.idTurma");
//     	}
    	
    	$qtd = $aluno->find();
//     	echo $qtd;
    	
    	
    	while( $aluno->fetch() ) {
    		//pega o id do professor
    		
    		$id_professor = $aluno->professor_id;
    		
    		//pega o professor
    		$professor = new Professor();
    		$professor->get($id_professor);

    		?>
    		<div id="avaliacao_box">
    		<div class="div1">
    		<div class="photo">
    		<img src="<?php echo pegaImagem($professor->getId()); ?>" alt="<?php echo utf8_encode($professor->getNome())?>" />
    		</div>
    		<div class="description">
    		    		
    		<h4><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    		<h4><span><?php echo utf8_encode($aluno->nome_disciplina); ?></span></h4>
    		</div>
    		</div>
			<div class="popupAvaliar">
				<h6><?php echo $aluno->id_turma." - ".utf8_encode($aluno->nome_disciplina); ?></h6>
				<h6><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h6></div>
    		<a href="../Controller/avaliacaoController.php?p=<?php echo codifica("action=avaliar&tipo=Aluno&subtipo=Professor/Disciplina&turma=".$aluno->id_turma); ?>"  title="Avaliar o professor"  id="xsd" class="botao_right btn_avaliacao botaoWhite">Avaliar</a>

    		</div>
    		<?php 
    
    	}

    		?>

    	 <?php

    	
    	if($aluno->fetch() == ""){
    		echo "<h4>Nenhuma avalia&ccedil;&atilde;o pendente</h4>";
    	}
    	
    	
    	?>
    	</div>
        <br />
        <br />
        <div class="avaliacoes-group">
        <h3>Avaliações Realizadas</h3>
        <?php
        //verifica avaliacoes dos laboratorios, pendentes
        for ($i = 0; $i < sizeof($laboratorios); $i++) {
        	if($laboratorios[$i]["usado"] == "sim"){
        		$lab_avaliado = 0;
        		 
        		$lab = new Laboratorio();
        		$lab->get($laboratorios[$i]["id"]);
        		 
        		//verifica se foi avaliado
        		$alunoC = new Aluno();
        		$alunoC->get($ra);
        		 
        		$alunoC->alias('aC');
        		 
        		$tC = new Turma();
        		$avC = new Avaliacao();
        		$alunoC->join($tC,'INNER','tC');
        		 
        		//ra = id na taCela aluno
        		//avaliador = correspondente na taCela avaliacao
        		$alunoC->join($avC, 'INNER', 'avC', "ra", "avaliador");
        		$alunoC->select("tC.periodoLetivo, avC.dataAvaliacao, aC.ra, avC.avaliador");
        		$alunoC->where("tC.periodoLetivo = '".$periodo_atual."' and avC.itemAvaliado= 'Lab_".$lab->getNome()."' and aC.ra = avC.avaliador");
        		$alunoC->groupBy("avC.itemAvaliado");
        		        		 
        			$lab_avaliado = $alunoC->find(true);
        			if($lab_avaliado == 0){
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
        <h4><span>Laboratorio de <?php echo utf8_encode($lab->getNome())?></span></h4>
        </div>
        </div>
        
        <div class="div2">
        <img class="ok" src="css/images/img-ok.png" /><br />
        <h4>Avaliado em:</h4>
        <h4><?php echo datetime_to_ptbr($alunoC->data_avaliacao);?></h4>
            	</div>
            	    	
            	</div>
        <?php			
        		}//fecha IF
        	}//fecha IF
        }//fecha FOR
        ?>
        
        <?php
        //� necessario pegar dados do aluno NOVAMENTE
    	$aluno2 = new Aluno();
		$aluno2->get($ra);
			
    	$aluno2->alias('a');
    	
    	$t = new Turma();
    	$av2 = new Avaliacao();
    	// une as classes
    	$aluno2->join($t,'INNER','t');
    	
    	//ra = id na tabela aluno
    	//avaliador = correspondente na tabela avaliacao
    	$aluno2->join($av2, 'INNER', 'av2', "ra", "avaliador");
    	
    	// seleciona os dados desejados
    	$aluno2->select("t.idTurma, t.nomeDisciplina, t.professorId, av2.dataAvaliacao, a.ra, av2.avaliador");
    	
    	//modificado pra suportar todas os tipos de avaliacao
//     	$aluno2->where("t.periodoLetivo = '".$periodo_atual."' and t.idTurma = av2.turmaIdTurma");
    	$aluno2->where("t.periodoLetivo = '".$periodo_atual."' and t.idTurma = av2.itemAvaliado and a.ra = av2.avaliador");
    	    	
    	//agrupamos para n�o listar as avaliacoes de cada questao
//     	$aluno2->groupBy("av2.turmaIdTurma");
    	$aluno2->groupBy("av2.itemAvaliado");
    	
    	// recupera os registros
    	$aluno2->find();
    	
    	while( $aluno2->fetch() ) {
    		//pega o id do professor    		
    		$id_professor = $aluno2->professor_id;
    		
    		//pega o professor
    		$professor = new Professor();
    		$professor->get($id_professor);

    		?>
    		<div id="avaliacao_box">
    		<div class="div1">
    		<div class="photo">
    		<img src="<?php echo pegaImagem($professor->getId()); ?>" alt="<?php echo utf8_encode($professor->getNome())?>" />
    		</div>
    		<div class="description">
    		<h4><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    		<h4><span><?php echo utf8_encode($aluno2->nome_disciplina); ?></span></h4>
    		
    		</div>
    		</div>
    		<div class="div2">
            	<img class="ok" src="css/images/img-ok.png" /><br />
            	<h4>Avaliado em:</h4>
            	<h4><?php echo datetime_to_ptbr($aluno2->data_avaliacao);?></h4>
            </div>
    		
    		</div>
    		<?php 
    
    	}
    	//� necessario pegar dados do aluno NOVAMENTE
    	$aluno3 = new Aluno();
    	$aluno3->get($ra);
    		
    	$aluno3->alias('a3');
    	 
    	$t3 = new Turma();
    	$av3 = new Avaliacao();
    	$aluno3->join($t3,'INNER','t3');
    	 
    	//ra = id na tabela aluno
    	//avaliador = correspondente na tabela avaliacao
    	$aluno3->join($av3, 'INNER', 'av3', "ra", "avaliador");
    	 
    	$aluno3->select("t3.periodoLetivo, t3.curso, t3.coordenadorId, av3.dataAvaliacao, a3.ra, av3.avaliador");
    	 
    	$aluno3->where("t3.periodoLetivo = '".$periodo_atual."' and av3.processoAvaliacaoId = ".$processo->getId()." and t3.curso = av3.itemAvaliado and a3.ra = av3.avaliador");
    	 
    	$aluno3->groupBy("av3.itemAvaliado");    	
    	 
    	// recupera os registros
    	$aluno3->find();
    	while( $aluno3->fetch() ) {
    		//pega o id do professor
    		$id_coordenador = $aluno3->coordenador_id;
    	
    		//pega o professor
    		$professor = new Professor();
    		$professor->get($id_coordenador);
    	
    		?>
    	    		<div id="avaliacao_box">
    	    		<div class="div1">
    	    		<div class="photo">
    	    		<img src="<?php echo pegaImagem($professor->getId()); ?>" alt="" />
    	    		</div>
    	    		<div class="description">
    	    		
    	    		<h4><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    	    		<h4><span>Coordenador de <?php echo utf8_encode($aluno3->curso); ?></span></h4>
    	    		</div>
    	    		</div>
    	    		<div class="div2">
    	            	<img class="ok" src="css/images/img-ok.png" /><br />
    	            	<h4>Avaliado em:</h4>
    	            	<h4><?php echo datetime_to_ptbr($aluno3->data_avaliacao);?></h4>
    	            </div>
    	    		
    	    		</div>
    	    		<?php 
    	    
    	    	}
    	    	
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
    	    <h4><?php echo datetime_to_ptbr($alunoB->data_avaliacao);?></h4>
    	</div>
    	    	
    	</div>
    	
    	
    	<?php
    	}
    	
    	
    	if($aluno2->fetch() == "" && $aluno3->fetch() == "" && $instituicao_foi_avaliada == 0){
    		echo "<h4>Nenhuma avalia&ccedil;&atilde;o foi realizada ainda</h4>";
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
</body>
</html>
