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


// $aluno = unserialize($_SESSION["s_aluno"]);
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

if(isset($_SESSION["s_usuario_logado"])){
	$str = $_SESSION["s_usuario_logado"];
	if($str instanceof Usuario){
		$usuario_logado = $str;
	}else{
		$usuario_logado = unserialize($_SESSION["s_usuario_logado"]);
	}
}

if(isset($_SESSION["s_periodo"])){
	$periodo_atual = $_SESSION["s_periodo"];
// 	echo "periodo: ".$periodo_atual;
}else{
	header("Location: index.php");
}

if(isset($_SESSION["s_periodo"])){
	$cursos_coordenados = $_SESSION["s_cursos_coordenados"];	
}
// $cursos_coordenados = array("Tecnologia em Gest�o Comercial", "Tecnologia em Gest�o de Cooperativas", "Psicologia", "Enfermagem");



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

<link type="text/css"
	href="css/unicampo-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>

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
    <?php include_once 'inc/menu_coord_inc.php';?>      
    
    <div class="white">
    <br />
    	<h3>Alunos com Avaliações Pendentes</h3>
    	<?php 
    	
    	if(isset($_POST["semestre-selecionado"]) && $_POST["semestre-selecionado"] != ""){
    		//filtro por semestre
    		$ss = $_POST["semestre-selecionado"];    		
    		$semestre_escolhido = $ss."� SEMESTRE";
//     		$semestre_escolhido = $ss;
    		$where_semestre = "and turma.serie = '".$semestre_escolhido."'";
    		$where_semestre2 = "and t.serie = '".$semestre_escolhido."'";
    		
    	}
    	else{
//     		$semestre_escolhido = "Todos";
    		$where_semestre = "";
    	}
    	
    	if(isset($_POST["curso-selecionado"]) && $_POST["curso-selecionado"] != ""){
    		//filtro por semestre
    		$cs = $_POST["curso-selecionado"];
    		$curso_escolhido = $cs;
    		$where_curso = "and turma.curso='".utf8_decode($curso_escolhido)."'";  		
    	}
    	else{
    		//pega o primeiro curso como sendo o default
    		$where_curso = "and turma.curso='".$cursos_coordenados[0]."'";
    	}
    	
    	if(isset($_POST["turma-selecionada"]) && $_POST["turma-selecionada"] != ""){
    		$ts = $_POST["turma-selecionada"];
    		$turma_escolhida = $ts;
    		$where_turma = "and turma.turma='".utf8_decode($turma_escolhida)."'";
    		$where_turma2 = "and t.turma = '".$turma_escolhida."'";
    	}
    	else{
    		//pega o primeiro curso como sendo o default
    		$where_turma = "";
    	}
    	
    	?>
    	<form action="" method="post" style="float: right;">
    	<?php
    		if(sizeof($cursos_coordenados) >1) {   	
    	?>
    	<div class="selectFiltro botaoGoogleGrey">
    	<select name="curso-selecionado">
    		<option value="">Escolha o curso</option>
    		<?php 
    		foreach ($cursos_coordenados as $curso) {
    			//select serie from turma where (curso="Servi�o Social"  or curso = "Psicologia") and periodo_letivo = '2/2011' group by serie ;
    			echo "<option value='".utf8_encode($curso)."'>".utf8_encode($curso)."</option>";
    		}
    		
    		?>
    	</select>
    	</div>
    	
    	
    		<?php
    		
			}//fecha if(sizeof)
    		
    		$turmaA = new Turma();
    		$turmaA->alias("turma");
    		$turmaA->select("turma.curso, turma.coordenadorId, turma.turma");
    		$turmaA->where("turma.curso = '".$cursos_coordenados[0]."'");
    		//$turmaA->group("turma.curso");
    		$turmaA->group("turma.turma");
    		$qtdTurmas = $turmaA->find();
    		
    		if($qtdTurmas >1){
    		?>
    		
    	<div class="selectFiltro botaoGoogleGrey">
    	<select name="turma-selecionada">
    	<option value="">Escolha a turma</option>
    		
    		<?php
    		while($turmaA->fetch()) {
				if($turmaA->turma != ""){
    		?>
    		<option value="<?php echo $turmaA->turma;?>"><?php echo $turmaA->turma;?></option>
    		<?php
				}//fecha if
			}//fecha while
			?>
    	</select>
    	</div>
    	<?php 
    		}//fecha if
    	?>
    	
    	
    	
    		<?php
    		
    		$turmaA = new Turma();
    		$turmaA->alias("turma");
    		$turmaA->select("turma.curso, turma.coordenadorId, turma.turma, turma.serie");
    		$turmaA->where("turma.curso = '".$cursos_coordenados[0]."'");
    		//$turmaA->group("turma.curso");
    		$turmaA->group("turma.serie");
    		$qtdSemestres = $turmaA->find();
    		
    		if($qtdSemestres >1){
    		?>    		
    	<div class="selectFiltro botaoGoogleGrey">
    	<select name="semestre-selecionado">
    	<option value="">Escolha o semestre</option>
    		
    		<?php
    		 		
    		while($turmaA->fetch()) {
    			$value = explode("�", $turmaA->serie);
    			if($turmaA->serie != ""){
    		?>
    		<option value="<?php echo $value[0];?>"><?php echo utf8_encode($turmaA->serie);?></option>
    		<?php 
				}//fecha if
    		}//fecha while
			?>

    	</select>
    	</div>
    	<?php 
    		}//fecha if
    	?>
    	
    	<button class="botaoGoogleBlue float-right" type="submit" name="enviar" >Carregar</button>
    	
    	</form>
    	<br />
    	<br />
    	<br />
    	<br />
    	<?php
    	
    	$qtd_pendente = 0;
    	$qtd_avaliada = 0;
    	
    	//pega todos os alunos ativos
//     	$lista_alunos = new Aluno();
//     	$lista_alunos->sitAcademica = 1;    	    	
//     	$qtd_alunos = $lista_alunos->find();
    	
    	
    	//pega todos alunos do periodo, do curso que o coordenador coordena
    	$cursoDoCoordenador = "Psicologia";
    	
    	
    	
    	$alunos = new Aluno();
    	$alunos->alias('alunos');
    	
    	$turma = new Turma();
    	$alunos->join($turma, 'INNER', 'turma');
    	
    	$thaa = new TurmaHasAluno();
    	
    	$alunos->join($thaa,'INNER','thaa');
    	
    	$alunos->select("alunos.nome, turma.periodoLetivo, alunos.sitAcademica, alunos.ra, count(thaa.avaliado) as total, count(thaa.avaliado is null) as totalP");
//     	$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1");
//     	$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1 and turma.curso='".$cursoDoCoordenador."' ");
    	
//     	if($semestre_escolhido == "Todos"){
//     		$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1 and turma.curso='".$cursoDoCoordenador."' ");
//     	}else{
//     		$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1 and turma.curso='".$cursoDoCoordenador."' and turma.serie = '".$semestre_escolhido."' ");
//     	}
    	
    	$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1 
    	                                                            and thaa.turmaIdTurma = turma.idTurma ".
    		$where_curso." ".$where_turma." ".$where_semestre);
    	    	
    	//teste
//     	$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1 and alunos.ra");
    	$alunos->group("alunos.ra");
    	$alunos->order("alunos.nome");
    	$qtd_alunos = $alunos->find();
    	
    	
    	
//     	echo "TOTAL DE ALUNOS ATIVOS DO PERIODO ATUAL: ".$qtd_alunos;
//     	echo "<br />";

    	?>
    	
    	
    	
    	<script>
    	$(function() {
    		    		
    		$( "#accordion" ).accordion({
    			active: false,     //inicia com o accordion fechado
    			collapsible: true, //fecha o accordion qdo clicado
    			autoHeight: false, //ajusta o accordion ao conteudo
    			navigation: true
    		});
    	});

    	
    	</script>
    		
    	<div id="accordion">

    	<?php     	
    	while( $alunos->fetch() ) {
    		
    		$ra_aluno = $alunos->ra;
    		$cur = $alunos->curso;
    		
    		$total = $alunos->total;
    		$totalP = $alunos->totalP;
    		
//     		echo "total: ".$total;
//     		echo "<br />";
//     		echo "totalPendentes: ".$totalP;
    		
    		$qtd_pendente_aluno = 0;
    		
    		if($total == $totalP){
    			 
    		}else{
    			echo "<h4><a href='#'>".utf8_encode($alunos->nome)."</a></h4>";
    		}
    		?>
    		
    		
    		<?php 
//     		echo "Nome: ".$alunos->nome;
//     		echo "<br />";
//     		echo "RA: ".$ra_aluno;
    	
    		$a = new Aluno();
    		$a->get($ra_aluno);
    		
    		$a->alias('a');
    		
    		$t = new Turma();
    		$a->join($t,'INNER','t');
    		    		
    		$tha = new TurmaHasAluno();
    		
    		$a->join($tha,'INNER','tha');
    		
    		$a->select("t.idTurma, t.nomeDisciplina, t.professorId, t.periodoLetivo, t.serie, t.curso, t.turma, a.nome, a.curso, tha.avaliado");
    		$a->where("t.periodoLetivo = '".$periodo_atual."' and a.ra = '".$ra_aluno."' and tha.turmaIdTurma = t.idTurma ".$where_semestre2." ".$where_turma2);
    		
    		//$a->groupBy("t.idTurma");
    		
    		$a->find();
    		
    		if($total == $totalP){
    		
    		}else{
    			
    		?>
    		
    		<div>
    		<table>
    		<caption>Avaliações Pendentes: <?php //echo $qtd_pendente_aluno;?></caption>
    	<tr>
    	<th>TURMA</th>
    	<th>DISCIPLINA</th>
    	<th>SERIE</th>
    	<th>CURSO</th>
    	</tr>
    		
    		<?php 
    		
    		}
    		
    		while( $a->fetch() ) {
    			
    			if($a->avaliado == "Avaliado"){
    				    				
    				//incrementa total de pendentes
    				$qtd_avaliada++;
    			}else{
    				
    				echo "<tr>";
    				echo "<td style='width: 5%;'>".$a->turma."</td>";
    				echo "<td>".$a->id_turma." - ".utf8_encode($a->nome_disciplina)."</td>";
    				echo "<td style='width: 10%;'>".utf8_encode($a->serie)."</td>";
    				echo "<td style='width: 30%;'>".utf8_encode($a->curso)."</td>";
    				   						
    				echo "</tr>";
    				
    				
    				//incrementa total de pendentes
    				$qtd_pendente++;
    				$qtd_pendente_aluno++;
    			}
    			
    		}
    		
    		if($total == $totalP){
    		
    		}else{
    		?>
    		</table>
    		</div>
    		    		
    		<?php 
    		}

    		
    	}
    	?>
<!--     	    		</table> -->
    	    		
    	    		</div>
    	    		</div><!-- fecha div white -->
    	    		<?php
    	
    	
    	
    	
    	
    	
    	
    	/*-----------------------------------------------------*/
    	
    	
//     	while( $lista_alunos->fetch() ) {
//     		//pega o ra do aluno pra obter as turmas e avaliacoes
//     		$ra_aluno = $lista_alunos->getRa();
//     		$cur = $lista_alunos->getCurso();
    		
//     		//recupera avaliacoes pendentes do aluno
//     		echo $lista_alunos->getNome();
//     		$a = new Aluno();
//     		$a->get($ra_aluno);
// //     		$a->getRa();
    		
//     		$a->alias('a');
    		
//     		$t = new Turma();
//     		$av = new Avaliacao();
//     		// une as classes
//     		$a->join($t,'INNER','t');
    		
//     		$tha = new TurmaHasAluno();
//     		$a->join($tha,'INNER','tha');
    		 
//     		$a->join($av, 'INNER', 'av');
//     		$a->select("t.idTurma, t.nomeDisciplina, t.professorId, t.periodoLetivo, t.curso, a.curso, tha.avaliado");
//     		$a->where(" t.periodoLetivo = '".$periodo_atual."' and a.ra = '".$ra_aluno."' and t.idTurma not in(SELECT av.turmaIdTurma FROM avaliacao av where av.alunoRa = '".$ra_aluno."')");
// //     		$a->where("t.curso = '".$cur."' and t.periodoLetivo = '".$periodo_atual."' and a.ra = '".$ra_aluno."'");
//     		$a->groupBy("t.idTurma");
    		
//     		$qtd1 = $a->find();
    		
//     		if($qtd1 != 0){
//     			echo "qtd 1: ".$qtd1;
//     			echo "<br />";
    			
//     			echo "<ul>";
//     			while( $a->fetch() ) {
//     				echo "<li>";
//     				echo $a->nome_disciplina. " - " . $a->id_turma. " - " . $a->curso . " - " .$a->periodo_letivo;
//     				echo "</li>";
    				 
//     				//incrementa total de pendentes
//     				$qtd_pendente++;
//     			}
//     			echo "</ul>";
    			
//     			echo "<br />";
    			
//     		}
    		
    		
    		
    		
// //     		if($a->fetch() == ""){
//     		if($qtd1 == 0){
    			 
//     			//� necessario pegar dados do aluno NOVAMENTE
//     			$a = new Aluno();
//     			$a->get($ra_aluno);
    			
//     			$a->alias('a');
    		
//     			$t1 = new Turma();
    			
//     			$a->join($t1,'INNER','t1');
    		
//     			//faz consulta diferenciada sem o JOIN e sem SUBSELECT no WHERE
//     			$a->select("t1.idTurma, t1.nomeDisciplina, t1.professorId");
//     			$a->where("t1.periodoLetivo = '".$periodo_atual."' and a.ra = '".$ra_aluno."'");
    		
//     			$a->groupBy("t1.idTurma");
    			
//     			$qtd2 = $a->find();
//     			echo "qtd 2: ".$qtd2;
//     			echo "<br />";
    			 
//     			echo "<ul>";
//     			while( $a->fetch() ) {
//     				echo "<li>";
//     				echo $a->nome_disciplina. " - " . $a->id_turma. " - " . $a->curso . " - " .$a->periodo_letivo;
//     				echo "</li>";
    			
//     				//incrementa total de pendentes
//     				$qtd_pendente++;
//     			}
//     			echo "</ul>";
    			
//     			echo "<br />"; 
    			
//     		}
    		 
    		;
   		
    	
//     	}
    	
    	
    	/*-------------------- PEGA AS AVALIA��ES CONCLUIDAS -------------------------*/
//     	$lista_alunos = new Aluno();
//     	$lista_alunos->sitAcademica = 1;
//     	$qtd_alunos = $lista_alunos->find();
    	 
//     	echo "TOTAL DE ALUNOS: ".$qtd_alunos;
//     	echo "<br />";
//     	while( $lista_alunos->fetch() ) {
//     		//pega o ra do aluno pra obter as turmas e avaliacoes
//     		$ra_aluno = $lista_alunos->getRa();
//     		$cur = $lista_alunos->getCurso();
    	
//     		//recupera avaliacoes pendentes do aluno
//     		echo $lista_alunos->getNome();
//     		$a = new Aluno();
//     		$a->getRa();
    	
//     		$a->alias('a');
    	
//     		$t = new Turma();
//     		$av = new Avaliacao();
//     		// une as classes
//     		$a->join($t,'INNER','t');
    	
//     		$a->join($av, 'INNER', 'av');
//     		$a->select("t.idTurma, t.nomeDisciplina, t.professorId, t.periodoLetivo, t.curso, a.curso");
//     		$a->where("t.periodoLetivo = '".$periodo_atual."' and a.ra = '".$ra_aluno."' and t.idTurma = av.turmaIdTurma");
    	
//     		$a->groupBy("t.idTurma");
    	
//     		$a->find();
//     		echo "<ul>";
//     		while( $a->fetch() ) {
//     			echo "<li>";
//     			echo $a->nome_disciplina. " - " . $a->id_turma. " - " . $a->curso . " - " .$a->periodo_letivo;
//     			echo "</li>";
//     			//incrementa total de concluidas
//     			$qtd_avaliada++;
//     		}
//     		echo "</ul>";
    	
//     		echo "<br />";
    		
//     	}
    	
    	
    	/* -------------------------- FIM ---------------------------------*/
    	    	
// 		$awe = new QuestionarioHasQuestao();
// 		$awe->setOrdem($value);
    	
    	
//     	echo "TOTAL DE AVALIA&Ccedil;&Otilde;ES PENDENTES: ".$qtd_pendente;
//     	echo "<br />";
//     	echo "TOTAL DE AVALIA&Ccedil;&Otilde;ES CONCLUIDAS: ".$qtd_avaliada;
    	
    	?>
        
        <br />
        
        
                
        
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;<?php echo date("Y");?> - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
<?php 

//$_SESSION["aluno"] = serialize($aluno);
// $_SESSION["processo"] = serialize($processo);
?>
</body>
</html>
