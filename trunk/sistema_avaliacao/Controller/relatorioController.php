<?php

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual pï¿½gina chamar de acordo com a action

//incluir aqui as classes que serao usadas
//require "../Model/Bean/questionario.class.php";
//require "../Model/DAO/questionarioDAO.class.php";

require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/QuestionarioHasQuestao.php';
require_once '../system/application/models/dao/QuestionarioUsado.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/TurmaHasAluno.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Funcionario.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Comentarios.php';
require_once '../system/application/models/dao/Log.php';
require_once '../system/application/models/dao/Usuario.php';

require '../Utils/functions.php';

//if (!isset($_SESSION)) {
session_start();
//}

/**
 * @name questionarioController
 * @author Fabio Baï¿½a
 * @since 12/01/2012
 * controller do questionario - responsï¿½vel por tratar as requisiï¿½ï¿½es via get, post ou session.
 * Controla o fluxo da aplicaï¿½ï¿½o definindo qual pï¿½gina chamar de acordo com a action recebida.
**/
//class questionarioController {
$action;
$page;

$default_page = "home.php";

// 	$questionario;
// 	$questionarioDAO;


avaliacaoController();

/**
 * @name avaliacaoController
 * @author Fabio Baï¿½a
 * @since 12/01/2012
 * funï¿½ï¿½o que verifica a action e direciona para a action especï¿½fica
**/
function avaliacaoController() {
	
	//zera os charts na sessao
	$_SESSION["s_active_chart"] = null;
	$_SESSION["s_active_chart_comment"] = null;
	$_SESSION["s_rel_name"] = null;
	
	
	//decodifica o q veio via GET
	if(isset($_GET["p"])){
		$_GET = decodeParams($_GET["p"]);
	}	

	//fazer o tratamento aqui da codificacao utf-8, iso, etc
	if(isset($_POST["action"])){
		$action = $_POST["action"];
	}

	if(isset($_GET["action"])){
		$action = $_GET["action"];
	}
 
	if(isset($_POST["relatorio_id"])){
		$relatorio_id = $_POST["relatorio_id"];
	}
	
	//para os relatorios do coordenador(auto avaliacao e instituicao)
	if(isset($_POST["quest"])){
		$quest = $_POST["quest"];
		$t = $_POST["tipo"];
		$c = $_POST["curso"];
		
		//print_r($_POST);
		
		if($quest == "Auto-avaliação"){
			$relatorio = relatorioAutoAvaliacao($t, $c);
		}
		if($quest == "Instituição"){
			$relatorio = relatorioInstituicao2($t, $c);
		}
		$_SESSION["s_active_chart"] = $relatorio;
	}
	//
	

	if($relatorio_id == 1){
		$relatorio = relatorioAcessos();
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 2){
		$relatorio = relatorioLaboratorios();
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 3){
		$relatorio = relatorioDisciplina2();
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 4){
		if(isset($_POST["semestre"])){
			//$semestre = $_POST["semestre"]."º SEMESTRE";
			$semestre = $_POST["semestre"];
		}else{
			
		}
		
		$curso = $_POST["curso"];
		
		$relatorio = relatorioInstituicao($semestre, $curso);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	
	if($relatorio_id == 5){
		if(isset($_POST["semestre"])){
			//$semestre = $_POST["semestre"]."º SEMESTRE";
			$semestre = $_POST["semestre"];
		}else{
				
		}
	
	
		$curso = $_POST["curso"];
	
		$relatorio = relatorioCoordenador($semestre, $curso);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 6){
		$t = $_POST["tipo"];
		$c = $_POST["curso"];
		$relatorio = relatorioInstituicao2($t, $c);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 7){
		$t = $_POST["tipo"];
		$sub = $_POST["subtipo"];
		$relatorio = relatorioComentarios($t, $sub);
		$_SESSION["s_active_chart_comment"] = $relatorio;
	}
	
	if($relatorio_id == 8){
		$t = $_POST["tipo"];
		$t_id = $_POST["turma_id"];
		$relatorio = relatorioProfessor($t, $t_id);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	
	if($relatorio_id == 9){
		$curso = $_POST["curso"];
		$relatorio = relatorioCoordenador2($curso);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 10){
		$t = $_POST["tipo"];
		$s = $_POST["subtipo"];
		$c = $_POST["curso"];
		$relatorio = relatorioLab($t, $s, $c);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 11){
		$t = $_POST["tipo"];
		$c = $_POST["curso"];
		$relatorio = relatorioAutoAvaliacao($t, $c);
	}
	
	if($action == "load_"){
		$avaliador = $_POST["avaliador"];
		
		/*$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
		
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);*/
		
		$quest = new Questionario();
		$quest->tipo = utf8_decode($avaliador);
		$quest->find();
		
		echo "<div>";
		echo "<select name='select2' size='6'>";
		while($quest->fetch()){
			echo "<option>".utf8_encode($quest->getSubtipo())."</option>";
		}
		echo "</select>";
		echo "</div>";
		
		exit;
	}
	
	
	
	///////////////////////////////////////////////////////////////////////
	
	
	
	
	if($action == "load"){
		$filtro = $_GET["filtro"];
		$parametro = $_GET["param"];
		
		//semestres
		$param_semestres = $_GET["param_semestres"];
		//cursos
		$param_cursos = $_GET["param_cursos"];
		
		if($filtro == 1){
			$_SESSION["rel_filtro_1"] = $parametro;
			
			$quest = new Questionario();
			$quest->tipo = utf8_decode($parametro);
			$quest->where("subtipo != 'Sistema'");
			$quest->find();
			
			//se questionario do funcionario
			if($_SESSION["rel_filtro_1"] == "Funcionário"){
				echo "<div class='box_op'>";
				
				echo "<h4>Questionário:</h4>";
				echo "<br />";
				
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='6' />";
				echo "<input type='hidden' name='tipo' value='Funcionário' />";
				echo "<input type='hidden' name='curso[]' value='Todos' />";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&tipo=Funcionário' class='chart'>Instituição</a>";
				echo "<label><input type='radio' value ='Funcionário' name='quest' />Instituição</label>";
				
				
				echo "</div>";
					
				exit;
			}
			if($_SESSION["rel_filtro_1"] == "Professor"){
				echo "<div class='box_op'>";
				
				echo "<h4>Questionario:</h4>";
				echo "<br />";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&tipo=Professor' class='chart'>Instituição</a>";
				while($quest->fetch()){
					//echo "<a href='#' id='op2_".utf8_encode($quest->getSubtipo())."' onclick='loadOptions(2, \"".utf8_encode($quest->getSubtipo())."\");'>".utf8_encode($quest->getSubtipo())."</a>";
					echo "<label><input type='radio' value ='".utf8_encode($quest->getSubtipo())."' name='quest' id='op2_".utf8_encode($quest->getSubtipo())."' onclick='loadOptions(2, \"".utf8_encode($quest->getSubtipo())."\", this);' />".utf8_encode($quest->getSubtipo())."</label>";
					//echo "<a href='#' id='op2_Laboratorios' onclick='loadOptions(2, \"Laboratorios\");'>Laboratórios</a>";
					//echo "<br />";
				}
				echo "</div>";
					
				exit;
			}
			if($_SESSION["rel_filtro_1"] == "Coordenador"){
				echo "<div class='box_op'>";
				
				echo "<h4>Questionário:</h4>";
				echo "<br />";
				
				//inputs
				//echo "<input type='hidden' name='relatorio_id' value='6' />";
				echo "<input type='hidden' name='tipo' value='Coordenador' />";
				echo "<input type='hidden' name='curso[]' value='Todos' />";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&tipo=Coordenador' class='chart'>Instituição</a>";
				echo "<label><input type='radio' value ='Instituição' name='quest' />Instituição</label>";
				echo "<label><input type='radio' value ='Auto-avaliação' name='quest' />Auto avaliação</label>";
				
				echo "</div>";
					
				exit;
			}else{
				echo "<div class='box_op'>";
				
				echo "<h4>Questionário:</h4>";
				echo "<br />";
				
				while($quest->fetch()){
					//echo "<a href='#' id='op2_".utf8_encode($quest->getSubtipo())."' onclick='loadOptions(2, \"".utf8_encode($quest->getSubtipo())."\");'>".utf8_encode($quest->getSubtipo())."</a>";
					echo "<label><input type='radio' value ='".utf8_encode($quest->getSubtipo())."' name='quest' id='op2_".utf8_encode($quest->getSubtipo())."' onclick='loadOptions(2, \"".utf8_encode($quest->getSubtipo())."\", this);' />".utf8_encode($quest->getSubtipo())."</label>";
				}
				echo "</div>";
					
				exit;
			}
			
			
			
		}
		if($filtro == 2){
			$_SESSION["rel_filtro_2"] = $parametro;
			
			$temp = substr($_SESSION["rel_filtro_2"], 0, 3);
			if($temp == "Lab"){
				$tipo = $_SESSION["rel_filtro_1"];
				//executa relatorio
				//$relatorio = relatorioInstituicao2($_SESSION["rel_filtro_1"]);
				//$_SESSION["s_active_chart"] = $relatorio;
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
				
				//echo "lab";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=10&tipo=".$tipo."&subtipo=".$parametro."&curso=Psicologia' class='chart'>Psicologia</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=10&tipo=".$tipo."&subtipo=".$parametro."&curso=Enfermagem' class='chart'>Enfermagem</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=10&tipo=".$tipo."&subtipo=".$parametro."&curso=Serviço Social' class='chart'>Serviço Social</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=10&tipo=".$tipo."&subtipo=".$parametro."&curso=Tecnologia em Gestão Comercial' class='chart'>Tecnologia em Gestão Comercial</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=10&tipo=".$tipo."&subtipo=".$parametro."&curso=Tecnologia em Gestão de Cooperativas' class='chart'>Tecnologia em Gestão de Cooperativas</a>";
			
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='10' />";
				echo "<input type='hidden' name='tipo' value='".$tipo."' />";
				echo "<input type='hidden' name='subtipo' value='".$parametro."' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt3\");' />Marcar Todos</label>";
				echo "<hr />";
				
				echo "<label><input type='checkbox' name='curso[]' value='Psicologia' onClick='marcaOpcaoCheckbox(this);'/>Psicologia</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Enfermagem' onClick='marcaOpcaoCheckbox(this);'/>Enfermagem</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Serviço Social' onClick='marcaOpcaoCheckbox(this);'/>Serviço Social</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão Comercial' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão Comercial</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão de Cooperativas' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão de Cooperativas</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Todos' onClick='marcaOpcaoCheckbox(this);'/>Geral(soma dos cursos)</label>";
				
				
				
				//redirectTo("relatorios.php");
				echo "</div>";
				exit;
			}
			
			//
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Coordenador"){
					
				//executa relatorio
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
					
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Psicologia' class='chart'>Psicologia</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Enfermagem' class='chart'>Enfermagem</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Serviço Social' class='chart'>Serviço Social</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Tecnologia em Gestão Comercial' class='chart'>Tecnologia em Gestão Comercial</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Tecnologia em Gestão de Cooperativas' class='chart'>Tecnologia em Gestão de Cooperativas</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Todos' class='chart'>Todos os Cursos</a>";

				//inputs
				echo "<input type='hidden' name='relatorio_id' value='9' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt3\");' />Marcar Todos</label>";
				echo "<hr />";
				
				echo "<label><input type='checkbox' name='curso[]' value='Psicologia' onClick='marcaOpcaoCheckbox(this);'/>Psicologia</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Enfermagem' onClick='marcaOpcaoCheckbox(this);'/>Enfermagem</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Serviço Social' onClick='marcaOpcaoCheckbox(this);'/>Serviço Social</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão Comercial' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão Comercial</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão de Cooperativas' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão de Cooperativas</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Todos' onClick='marcaOpcaoCheckbox(this);'/>Geral(soma dos cursos)</label>";
				
				echo "</div>";
					
				exit;
			}
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Instituição"){
				//executa relatorio
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
					
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Psicologia&tipo=Professor' class='chart'>Psicologia</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Enfermagem&tipo=Professor' class='chart'>Enfermagem</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Serviço Social&tipo=Professor' class='chart'>Serviço Social</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Tecnologia em Gestão Comercial&tipo=Professor' class='chart'>Tecnologia em Gestão Comercial</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Tecnologia em Gestão de Cooperativas&tipo=Professor' class='chart'>Tecnologia em Gestão de Cooperativas</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Todos&tipo=Professor' class='chart'>Todos os Cursos</a>";
					
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='6' />";
				echo "<input type='hidden' name='tipo' value='Professor' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt3\");' />Marcar Todos</label>";
				echo "<hr />";
				
				echo "<label><input type='checkbox' name='curso[]' value='Psicologia' onClick='marcaOpcaoCheckbox(this);'/>Psicologia</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Enfermagem' onClick='marcaOpcaoCheckbox(this);'/>Enfermagem</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Serviço Social' onClick='marcaOpcaoCheckbox(this);'/>Serviço Social</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão Comercial' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão Comercial</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão de Cooperativas' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão de Cooperativas</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Todos' onClick='marcaOpcaoCheckbox(this);'/>Geral(soma dos cursos)</label>";
				
				echo "</div>";
					
				exit;
				
			}
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Auto-avaliação-professor"){
				//executa relatorio
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
					
				//inputs
				echo "<input type='hidden' name='quest' value='Auto-avaliação' />";
				echo "<input type='hidden' name='tipo' value='Professor' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt3\");' />Marcar Todos</label>";
				echo "<hr />";
			
				echo "<label><input type='checkbox' name='curso[]' value='Psicologia' onClick='marcaOpcaoCheckbox(this);'/>Psicologia</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Enfermagem' onClick='marcaOpcaoCheckbox(this);'/>Enfermagem</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Serviço Social' onClick='marcaOpcaoCheckbox(this);'/>Serviço Social</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão Comercial' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão Comercial</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão de Cooperativas' onClick='marcaOpcaoCheckbox(this);'/>Tecnologia em Gestão de Cooperativas</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Todos' onClick='marcaOpcaoCheckbox(this);'/>Geral(soma dos cursos)</label>";
			
				echo "</div>";
					
				exit;
			
			}
			
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Professor/Disciplina"){
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
						
				//inputs
				//echo "<input type='hidden' name='relatorio_id' value='6' />";
				//echo "<input type='hidden' name='tipo' value='Professor' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt3\");loadOptionsMultiple(3, \"1\", this);' />Marcar Todos</label>";
				echo "<hr />";
				
				echo "<label><input type='checkbox' name='curso[]' value='Psicologia' onClick='loadOptionsMultiple(3, \"1\", this);'/>Psicologia</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Enfermagem' onClick='loadOptionsMultiple(3, \"2\", this);'/>Enfermagem</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Serviço Social' onClick='loadOptionsMultiple(3, \"3\", this);'/>Serviço Social</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão Comercial' onClick='loadOptionsMultiple(3, \"4\", this);'/>Tecnologia em Gestão Comercial</label>";
				echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão de Cooperativas' onClick='loadOptionsMultiple(3, \"5\", this);'/>Tecnologia em Gestão de Cooperativas</label>";
				
				echo "</div>";
				exit;
			}
			
			//seleciona os cursos
			echo "<div class='box_op'>";
				
			echo "<h4>Curso:</h4>";
				
			//echo "<a href='#' id='op3_1' onclick='loadOptions(3, \"1\");'>Psicologia</a>";
			//echo "<a href='#' id='op3_2' onclick='loadOptions(3, \"2\");'>Enfermagem</a>";
			//echo "<a href='#' id='op3_3' onclick='loadOptions(3, \"3\");'>Serviço Social</a>";
			//echo "<a href='#' id='op3_4' onclick='loadOptions(3, \"4\");'>Tecnologia em Gestão Comercial</a>";
			//echo "<a href='#' id='op3_5' onclick='loadOptions(3, \"5\");'>Tecnologia em Gestão de Cooperativas</a>";
			//echo "<a href='#' id='op3_6' onclick='loadOptions(3, \"6\");'>Todos os Cursos</a>";
			echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt3\");loadOptions(3, \"1\", this);' />Marcar Todos</label>";
			echo "<hr />";
				
			echo "<label><input type='checkbox' name='curso[]' value='Psicologia' onclick='loadOptions(3, \"1\", this);'/>Psicologia</label>";
			echo "<label><input type='checkbox' name='curso[]' value='Enfermagem' onclick='loadOptions(3, \"2\", this);'/>Enfermagem</label>";
			echo "<label><input type='checkbox' name='curso[]' value='Serviço Social' onclick='loadOptions(3, \"3\", this);'/>Serviço Social</label>";
			echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão Comercial' onclick='loadOptions(3, \"4\", this);'/>Tecnologia em Gestão Comercial</label>";
			echo "<label><input type='checkbox' name='curso[]' value='Tecnologia em Gestão de Cooperativas' onclick='loadOptions(3, \"5\", this);'/>Tecnologia em Gestão de Cooperativas</label>";
			echo "<label><input type='checkbox' name='curso[]' value='Todos' onclick='loadOptions(3, \"0\", this);'/>Geral(soma dos cursos)</label>";
			echo "</div>";
			
			exit;
			
		}
		
		if($filtro == 3){
			$_SESSION["rel_filtro_3"] = $parametro;
			
			//pega o nome do curso pelo id
			$curso;
			switch ($_SESSION["rel_filtro_3"]){
				case 1:
					//psicologia
					$curso = "Psicologia";
					break;
				case 2:
					//enfermagem
					$curso = "Enfermagem";
					break;
				case 3:
					//Serviço Social
					$curso = "Serviço Social";
					break;
				case 4:
					//Comercial
					$curso = "Tecnologia em Gestão Comercial";
					break;
				case 5:
					//Cooperativas
					$curso = "Tecnologia em Gestão de Cooperativas";
					break;
				case 0:
					//Todos os cursos
					$curso = "Todos";
					break;			
			}
			//$curso = utf8_decode($curso);
			
			//
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Instituição"){
			
				//executa relatorio
				echo "<div class='box_op'>";
				
				echo "<h4>Período:</h4>";
				
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."&semestre=1' class='chart'>1° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."&semestre=3' class='chart'>3° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."&semestre=5' class='chart'>5° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."' class='chart'>Todos os Períodos</a>";
				
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='4' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt4\")' />Marcar Todos</label>";
				echo "<hr />";
				 
				echo "<label><input type='checkbox' name='semestre[]' value='1' />1° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='3' />3° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='5' />5° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='Todos' />Geral(soma dos semestres)</label>";
				
				echo "</div>";
				
				exit;
			}
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Curso/Coordenador"){
					
				//executa relatorio
				echo "<div class='box_op'>";
				
				echo "<h4>Período:</h4>";
			
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."&semestre=1' class='chart'>1° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."&semestre=3' class='chart'>3° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."&semestre=5' class='chart'>5° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."' class='chart'>Todos os Períodos</a>";
				
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='5' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt4\")' />Marcar Todos</label>";
				echo "<hr />";
					
				echo "<label><input type='checkbox' name='semestre[]' value='1' onClick='marcaOpcaoCheckbox(this);'/>1° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='3' onClick='marcaOpcaoCheckbox(this);'/>3° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='5' onClick='marcaOpcaoCheckbox(this);'/>5° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='Todos' onClick='marcaOpcaoCheckbox(this);'/>Geral(soma dos semestres)</label>";
				
				echo "</div>";
			
				exit;
			}
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Professor/Disciplina"){
				
				echo "<div class='box_op'>";
			
				echo "<h4>Período:</h4>";
					
				//echo "<a href='#' id='op4_1' onclick='loadOptions(4, \"1\");'>1° Período</a>";
				//echo "<a href='#' id='op4_3' onclick='loadOptions(4, \"3\");'>3° Período</a>";
				//echo "<a href='#' id='op4_5' onclick='loadOptions(4, \"5\");'>5° Período</a>";
				//echo "<a href='#' id='op4_todos' onclick='loadOptions(4, \"todos\");'>Todos os Períodos</a>";
					
				//inputs
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt4\");loadOptionsMultiple(4, \"1\", this);' />Marcar Todos</label>";
				echo "<hr />";
								
				echo "<label><input type='checkbox' name='semestre[]' value='1' onClick='loadOptionsMultiple(4, \"1\", this);'/>1° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='3' onClick='loadOptionsMultiple(4, \"3\", this);'/>3° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='5' onClick='loadOptionsMultiple(4, \"5\", this);'/>5° Período</label>";
				//echo "<label><input type='checkbox' name='semestre[]' value='Todos' onClick='loadOptionsMultiple(4, \"0\", this);'/>Geral(soma dos semestres)</label>";
				
				echo "</div>";
				
				exit;
				
			}
			
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Coordenador"){
					
				//executa relatorio
				echo "<div class='box_op'>";
			
				echo "<h4>Período:</h4>";
					
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>1° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>3° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>5° Período</a>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>Todos os Períodos</a>";
				
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='9' />";
									
				echo "<label><input type='checkbox' name='semestre[]' value='1' onClick='marcaOpcaoCheckbox(this);'/>1° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='3' onClick='marcaOpcaoCheckbox(this);'/>3° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='5' onClick='marcaOpcaoCheckbox(this);'/>5° Período</label>";
				echo "<label><input type='checkbox' name='semestre[]' value='Todos' onClick='marcaOpcaoCheckbox(this);'/>Geral(soma dos semestres)</label>";
				
				echo "</div>";
					
				exit;
			}
			
	
		}
		if($filtro == 4){
			$_SESSION["rel_filtro_4"] = $parametro;
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Professor/Disciplina"){
				//executa o relatorio
				//
				echo "<div class='box_op'>";
					
				echo "<h4>Disciplina:</h4>";
				//echo "s curso: ".$_SESSION["rel_filtro_3"];
				//echo "<br />";
				//echo "s semestre: ".$_SESSION["rel_filtro_4"];
				
				/*$curso;
				switch ($_SESSION["rel_filtro_3"]){
					case 1:
				        //psicologia
				        $curso = "Psicologia";
				        break;
				    case 2:
				        //enfermagem
				    	$curso = "Enfermagem";
				        break;
				    case 3:
				        //Serviço Social
				    	$curso = "Serviço Social";
				        break;
			        case 4:
			        	//Comercial
			        	$curso = "Tecnologia em Gestão Comercial";
			        	break;
		        	case 5:
		        		//Cooperativas
		        		$curso = "Tecnologia em Gestão de Cooperativas";
		        		break;
		        	case 0:
		        		//Todos os cursos
		        		$curso = "Todos";
		        		break;
						
				}*/
				
								
				//$curso = utf8_decode($curso);
				$turmas = new Turma();
				
				$where = "";
				if(isset($_GET["param_cursos"])){
					$cursos_array = explode(",",$param_cursos);
					//print_r($series_array);
					//series
					$cursos_str = "";
					for ($i=0; $i<sizeof($cursos_array); $i++){
						if($i == 0){
							$cursos_str .= "'".$cursos_array[$i]."'";
						}else{
							$cursos_str .= ", '".$cursos_array[$i]."'";
						}
					}
				
					$where .= "curso in (".utf8_decode($cursos_str).")";
					//$turmas->where("curso in (".utf8_decode($cursos_str).")");
					
					if(isset($_GET["param_semestres"])){
						$series_array = explode(",",$param_semestres);
						//print_r($series_array);
						//series
						$series_str = "";
						for ($i=0; $i<sizeof($series_array); $i++){
							if($i == 0){
								$series_str .= "'".$series_array[$i]."º SEMESTRE'";
							}else{
								$series_str .= ", '".$series_array[$i]."º SEMESTRE'";
							}
						}
							
						$where .= " AND serie in (".utf8_decode($series_str).")";
						//$turmas->where("serie in (".utf8_decode($series_str).")");
					}
					
					$turmas->where($where);
					$turmas->order("curso, serie, idTurma");
					
				}
				
				
				
				$turmas->find();
				
							
				//inputs
				echo "<input type='hidden' name='relatorio_id' value='8' />";
				echo "<input type='hidden' name='tipo' value='Aluno' />";
				echo "<label><input type='checkbox' class='checkAll' onClick='checkAll(\"box_opt5\")' />Marcar Todos</label>";
				echo "<hr />";
				
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=8&tipo=Aluno&turma_id=Todas' class='chart'>Todas as Turmas</a>";
				
				$pointer_curso = 0;
				$pointer_serie = 0;
				while($turmas->fetch()){
					//agrupa por curso
					if($pointer_curso == 0){
						$temp_curso = utf8_encode($turmas->getCurso());
						echo "<label style='padding-left:20px;'><input type='checkbox' name='turma_id[]' value='Todas' />Geral de Todos os Cursos</label>";
						echo "<h4 class='ident1'>".$temp_curso."</h4>";
						echo "<label style='padding-left:20px;'><input type='checkbox' name='turma_id[]' value='C:".$temp_curso."-Todos' />Geral de ".$temp_curso."</label>";
					}else{
						if($temp_curso == utf8_encode($turmas->getCurso())){
							//echo "<label><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</label>";
							
							//agrupa por serie
							if($pointer_serie == 0){
								$temp_serie = utf8_encode($turmas->getSerie());
								echo "<h4 class='ident2'>".$temp_serie."</h4>";
								echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='C:".$temp_curso."-".$temp_serie."' />Geral do ".$temp_serie."</label>";
							}else{
								if($temp_serie == utf8_encode($turmas->getSerie())){
									echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</label>";
								}else{
									//troca a serie da vez
									$temp_serie = utf8_encode($turmas->getSerie());
									echo "<h4 class='ident2'>".$temp_serie."</h4>";
									echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='C:".$temp_curso."-".$temp_serie."' />Geral do ".$temp_serie."</label>";
									echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</label>";
										
								}
							}
							$pointer_serie++;
							//
							
						}else{
							//troca o curso da vez
							$temp_curso = utf8_encode($turmas->getCurso());
							echo "<h4 class='ident1'>".$temp_curso."</h4>";
							echo "<label style='padding-left:20px;'><input type='checkbox' name='turma_id[]' value='C:".$temp_curso."-Todos' />Geral de ".$temp_curso."</label>";
							//echo "<label><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />Geral da serie ".$temp_serie."</label>";
							//echo "<label><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</label>";
							
							//agrupa por serie
							if($pointer_serie == 0){
								$temp_serie = utf8_encode($turmas->getSerie());
								echo "<h4 class='ident2'>".$temp_serie."</h4>";
								echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='C:".$temp_curso."-".$temp_serie."' />Geral do ".$temp_serie."</label>";
							}else{
								if($temp_serie == utf8_encode($turmas->getSerie())){
									echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</label>";
								}else{
									//troca a serie da vez
									$temp_serie = utf8_encode($turmas->getSerie());
									echo "<h4 class='ident2'>".$temp_serie."</h4>";
									echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='C:".$temp_curso."-".$temp_serie."' />Geral do ".$temp_serie."</label>";
									echo "<label style='padding-left:40px;'><input type='checkbox' name='turma_id[]' value='".$turmas->getIdTurma()."' />".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</label>";
										
								}
							}
							$pointer_serie++;
							//
							
						}
					}
					
					$pointer_curso++;

				}
				
				
				//mostra o botao gerar_grafico
				echo "<script type='text/javascript'>
					botaoGerarGrafico('mostrar');	
					</script>";
				
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$_SESSION["rel_filtro_3"]."'>Todos os Períodos</a>";
					
				echo "</div>";
					
				exit;
				//
			}
		}
		
		
	
		
	
		//loadOptions();
	}

	redirectTo("relatorios.php");

}


/**
 * @name alunosPendentes
 * @author Fabio BaÃ­a
 * @since 07/08/2012 13:11:06
 * insert a description here
 **/
function avaliadoresPendentes($param) {
	$qtdTotal = 0;
	$qtdAvaliaram = 0;
	
	//possibilitar filtro por curso e semestre
	
	//obs: só contam como avaliaodos avaliadores q concluiram todas as avaliações
	//pegar semestre atual pra filtrar os avaliadores
	
	
	//verifica o tipo do avaliador
	if($param == "Aluno"){
		$alunos = new Aluno();
    	$alunos->alias('alunos');
    	
    	$turma = new Turma();
    	$alunos->join($turma, 'INNER', 'turma');
    	
    	$thaa = new TurmaHasAluno();
    	    	
    	$alunos->join($thaa,'INNER','thaa');    	
    	$alunos->select("alunos.nome, turma.periodoLetivo, alunos.sitAcademica, alunos.ra, count(thaa.avaliado) as totalA, count(thaa.avaliado is null) as total");
    	
    	$alunos->where("turma.periodoLetivo = '".$periodo_atual."' and alunos.sitAcademica=1
    	                                                            and thaa.turmaIdTurma = turma.idTurma ".
    			$where_curso." ".$where_turma." ".$where_semestre);
    	
    	$alunos->group("alunos.ra");
    	$alunos->order("alunos.nome");
    	$qtd_alunos = $alunos->find();
    	
    	echo "QTD Alunos: ".$qtd_alunos;
    	
	}
	
	//concatenar com turma has aluno pra ver qtos alunos tem na tabela
	
	
	//verifica na tabela correspondente ao avaliador a qtd de avaliadores
	
	//verifica na tabela avaliacoes a qtd de avaliadores q avaliaram
	
	//
	
	

}

/**
 * @name relatorioAcessos
 * @author Fabio BaÃ­a
 * @since 07/08/2012 12:11:08
 * insert a description here
 **/
function relatorioAcessos() {

	$lista = new Log();
	$lista->alias("l");

	$prof = new Professor();
	$u = new Usuario();
	$a = new Aluno();

	$lista->join($prof, 'LEFT', 'prof', "usuario", "id");
	$lista->join($u, 'LEFT', 'u', "usuario", "id");
	$lista->join($a, 'LEFT', 'a', "usuario", "ra");

	$lista->select("l.id, l.usuario, l.hora, l.ip, l.tipoUsuario,
			prof.nome as prof_nome, prof.id as prof_id,
			u.id as u_id, u.nome as u_nome,
			a.ra as a_id, a.nome as a_nome, count(*) as total, DATE(hora) as dia");
	$lista->where("l.usuario != '1'");
	$lista->order("dia ASC");
	$lista->groupBy("dia");
	$lista->find();

	$array_relatorio_log = array();
	$it = 0;
	while( $lista->fetch()) {
		$array_relatorio_log[$it]["dia"] = $lista->dia;
		$array_relatorio_log[$it]["qtd"] = $lista->total;
		$it++;
	}

	$chart = "function drawChart() {

			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Dia');
			data.addColumn('number', 'Acessos');";

	$chart .= 'data.addRows('.count($array_relatorio_log).');';

	$i = 0;
	for($i; $i <count($array_relatorio_log); $i++){
		$d = date_to_ptbr($array_relatorio_log[$i]["dia"]);
		$t = $array_relatorio_log[$i]["qtd"];

		$chart .= 'data.setValue('.$i.', 0, \''.$d.'\');';
		$chart .= 'data.setValue('.$i.', 1, '.$t.');';

	}


	$chart .= "var options = {
			title: 'Quantidade de Acessos',
			hAxis:{title:'Dias'},
			vAxis:{title:'Acessos'},
			height: 400,
			pointSize: 5
};";

	$chart .= "var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			chart.draw(data, options);
			 
}";

	return $chart;

}

/**
 * @name relatorioLaboratorios
 * @author Fabio BaÃ­a
 * @since 07/08/2012 15:19:04
 * insert a description here
 **/
function relatorioLaboratorios() {

	$avaliacoes;
	$av = new Avaliacao();
	$av->alias("av");
	//$av->select("item_avaliado, sum(nota) as soma, count(nota) as qtdNotas, avg(nota) as average");
	$av->select("item_avaliado, avg(nota) as average");

	$av->where("subtipo_avaliacao like 'Lab_%'");
	$av->group("item_avaliado");

	//usar o order e o limit pra pegar as "melhores" e as "piores" notas
	$av->order("average DESC");

	$av->find();
	// 	echo $qtd;
	// 	exit;
	while ($av->fetch()) {
		$avaliacoes[] = array("itemAvaliado" => $av->itemAvaliado,
				"nomeLaboratorio" => $av->itemAvaliado,
				"media" => $av->average);
	}


	$chart = "function drawChart(){
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'LaboratÃ³rio');
			data.addColumn('number', 'Media');
			//data.addColumn({type:'string',role:'tooltip'});

			";

	$arr1 = $avaliacoes;
	$chart .= 'data.addRows('.count($arr1).');';
	$i = 0;
	for($i; $i <count($arr1); $i++){
		$d = utf8_encode($arr1[$i]["nomeLaboratorio"]);
		$m = escalaDecimal($arr1[$i]["media"]);

		//       	    			$htmlTooltip = utf8_encode($arr1[$i]["nomeProfessor"]).'\n';
		//       	    			$htmlTooltip .= utf8_encode($arr1[$i]["nomeDisciplina"]).'\n';
		//       	    			$htmlTooltip .= "Nota: ".escalaDecimal($arr1[$i]["media"]);

		$chart .=  'data.setValue('.$i.', 0, \''.$d.'\');';
		$chart .=  'data.setValue('.$i.', 1, '.$m.');';
		//       	    			echo 'data.setValue('.$i.', 2, \''.$htmlTooltip.'\');';

	}

	// Create and draw the visualization.
	//graficos possÃ­veis pra esses dados
	//Area - Line - Bar - Column
	$chart .= "chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
			chart.draw(data,
			{title:'Laboratorios - Geral',
			height:300,
			colors: ['#920300'],
			hAxis: {title: 'LaboratÃ³rio'},
			vAxis:{title: 'Nota',
			maxValue: 10,
			minValue: 0
			//gridlines:{count:10}//
},
			pointSize: 5,
			allowHtml: true
}
			);
}

			";

	return $chart;

}

/**
 * @name relatorioDisciplina
 * @author Fabio BaÃ­a
 * @since 07/08/2012 15:46:55
 * insert a description here
 **/
function relatorioDisciplina() {

	// o q eu preciso:
	// id do ProcessoAvaliacao
	// Curso
	// instrumento(tipo de avaliacao)
	// avaliador
	// questionario usado

	$results;
	$av = new Avaliacao();
	$av->select("processo_avaliacao_id, questionario_has_questao_questionario_id,
			questionario_has_questao_questao_id, item_avaliado, nota, tipo_avaliacao, subtipo_avaliacao, avaliador");

	$av->where("subtipo_avaliacao = 'Professor/Disciplina' and item_avaliado = 7074");

	$av->order("questionario_has_questao_questao_id ASC, item_avaliado ASC");
	$av->find();

	$it = null;
	$pos = 0;
	$posAtual = 0;

	$nota5 = 0;
	$nota4 = 0;
	$nota3 = 0;
	$nota2 = 0;
	$nota1 = 0;

	$soma = 0;

	$avaliadorAtual = null;
	$qtd_avaliadores = 0;

	while ($av->fetch()) {
		//se o item da vez for nulo recebe o item_avaliado do resultset
		if($it == null || $it != $av->questionario_has_questao_questao_id){
			$it = $av->questionario_has_questao_questao_id;

			//zera as notas
			$nota5 = 0;
			$nota4 = 0;
			$nota3 = 0;
			$nota2 = 0;
			$nota1 = 0;

			$media = 0;
			$soma = 0;

			$posAtual = $pos;

			//obs
			$pos++;
		}

		if($it == $av->questionario_has_questao_questao_id){

			$avaliadores = new Avaliacao();
			$avaliadores->setQuestionarioHasQuestaoQuestaoId($av->questionario_has_questao_questao_id);
			$avaliadores->group("avaliador");
			$qtd_avaliadores = $avaliadores->find();

			switch ($av->nota) {
				case 5:
					$nota5++;
					$soma += 5;
					break;
				case 4:
					$nota4++;
					$soma += 4;
					break;
				case 3:
					$nota3++;
					$soma += 3;
					break;
				case 2:
					$nota2++;
					$soma += 2;
					break;
				case 1:
					$nota1++;
					$soma += 1;
					break;
			}

			//descobre o numero de questoes do questionario
			$qhq = new QuestionarioHasQuestao();
			$qhq->setQuestionarioId($av->questionarioHasQuestaoQuestionarioId);
			$qtd_questoes = $qhq->find();

			$media = ($soma/$qtd_questoes)/$qtd_avaliadores;

			$results[$posAtual] = array("processo_avaliacao_id" => $av->processoAvaliacaoId,
					"questionario_id" => $av->questionarioHasQuestaoQuestionarioId,
					"questao_id" => $av->questionarioHasQuestaoQuestaoId,
					"itemAvaliado" => $av->item_avaliado,
					"nota5" => $nota5,
					"nota4" => $nota4,
					"nota3" => $nota3,
					"nota2" => $nota2,
					"nota1" => $nota1,
					"media" => 5,
					/*"soma" => $soma,
					 "qtd" => $qtd_questoes,
			"qtdAV" => $qtd_avaliadores,*/
					"tipo_avaliacao" => $av->tipoAvaliacao,
					"subtipo_avaliacao" => $av->subtipoAvaliacao);
		}

	}


	// Create and populate the data table.
	/*var data = google.visualization.arrayToDataTable([
	 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'MÃ©dia'],
			['IntroduÃ§Ã£o Ã  AdministraÃ§Ã£o',  5,      8,         6,             22,           0,      8.2],
			['EstatÃ­stica Aplicada', 2,      7,        8,             10,          3,      6],
			['ComunicaÃ§Ã£o e Linguagem',  0,      15,       5,             0,           11,     6.2],
			['Filosofia',  3,      5,       2,             1,           8,     3.8]
			]);*/

	$chart = "function drawChart(){

			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Disciplina');
			data.addColumn('number', '5 estrelas');
			data.addColumn('number', '4 estrelas');
			data.addColumn('number', '3 estrelas');
			data.addColumn('number', '2 estrelas');
			data.addColumn('number', '1 estrela');
			data.addColumn('number', 'MÃ©dia');

			";

	$arr1 = $results;

	$chart .= 'data.addRows('.sizeof($arr1).');';
	$i = 0;
	for($i; $i <sizeof($arr1); $i++){
		//   	    			$d = utf8_encode($arr1[$i]["nomeDisciplina"]);
		$d = utf8_encode($arr1[$i]["questao_id"]);
		$m = escalaDecimal($arr1[$i]["media"]);

		$chart .=  'data.setValue('.$i.', 0, \''.$d.'\');';
		$chart .=  'data.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
		$chart .=  'data.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
		$chart .=  'data.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
		$chart .=  'data.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
		$chart .=  'data.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
		$chart .=  'data.setValue('.$i.', 6, '.$m.');';

	}


	$chart .= "var barChart = new google.visualization.ChartWrapper({
			'chartType': 'ColumnChart',
			'containerId': 'chart1',
			'options': {
			'width': 900,
			'height': 300,
			'hAxis': {'minValue': 0, 'maxValue': 10},
			'chartArea': {top: 0, right: 0, bottom: 0},
			'series': {5: {type: 'line'}},
			'pointSize': 5,
			'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9']
}
});
			 
			";

	// Define a slider control for the Age column.
	$chart .= "var slider = new google.visualization.ControlWrapper({
			'controlType': 'NumberRangeFilter',
			'containerId': 'control1',
			'options': {
			'filterColumnLabel': 'Media',
			'minValue': 1,
      		'maxValue': 5,
			'ui': {'labelStacking': 'vertical'}
}
});
			 
			";

	// Define a category picker control for the Gender column
	$chart .= "var categoryPicker = new google.visualization.ControlWrapper({
			'controlType': 'CategoryFilter',
			'containerId': 'control2',
			'options': {
			'filterColumnLabel': 'Disciplina',
			'ui': {
			'labelStacking': 'vertical',
			'allowTyping': false,
			'allowMultiple': true
}
}
});
			 
			";

	// Define a table
	$chart .= "var table = new google.visualization.ChartWrapper({
			'chartType': 'Table',
			'containerId': 'chart2',
			'options': {
			'width': '900px',
			'allowHtml': true
}
});
			 
			";

	$chart .= "var formatter = new google.visualization.ColorFormat();
			formatter.addRange(0, 5, '#CC0000', null);
			formatter.addRange(5, 10, '#006600', null);
			formatter.format(data, 6); // Apply formatter to second column

			";

	// Create a dashboard
	$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard')).
			// Establish bindings, declaring the both the slider and the category
			// picker will drive both charts.
			bind([slider, categoryPicker], [barChart, table]).
			// Draw the entire dashboard.
			draw(data);
}
			";
	return $chart;

}


/**
 * @name relatorioDisciplina
 * @author Fabio Baï¿½a
 * @since 05/09/2012 17:35:31
 * insert a description here
 **/
function relatorioDisciplina2() {

	// o q eu preciso:
	// id do ProcessoAvaliacao
	// Curso
	// instrumento(tipo de avaliacao)
	// avaliador
	// questionario usado

	$processo_id = 2;
	$quest_id = 21;
	$tipo_avaliacao = "Aluno";
	$subtipo_avaliacao = "Instituição";
	$item_avaliado = "Instituição";

	$questionario = new Questionario();
	$questionario->get($quest_id);
	$questionario->alias('q');
	$q = new Questao();
	$qhq = new QuestionarioHasQuestao();

	$questionario->join($q,'INNER','qu');
	$questionario->join($qhq,'INNER','qhq');

	$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");

	$questionario->where("qu.id = qhq.questaoId");
	$questionario->order("qhq.ordem");

	$questionario->find();

	while( $questionario->fetch() ) {

		$av = new Avaliacao();
		$av->select("processo_avaliacao_id, questionario_has_questao_questionario_id,
				questionario_has_questao_questao_id, item_avaliado, nota, tipo_avaliacao, subtipo_avaliacao, avaliador");

		$av->where("processo_avaliacao_id = '$processo_id' and tipo_avaliacao = '$tipo_avaliacao'
				and subtipo_avaliacao = '$subtipo_avaliacao' and item_avaliado = '$item_avaliado'
				and questionario_has_questao_questao_id = '$questionario->id' ");

		$av->order("nota ASC");
		$av->find();

		//
		$nota5 = 0;
		$nota4 = 0;
		$nota3 = 0;
		$nota2 = 0;
		$nota1 = 0;

		$soma = 0;

		$qtd_avaliadores = 0;
		while ($av->fetch()) {
			switch ($av->nota) {
				case 5:
					$nota5++;
					$soma += 5;
					break;
				case 4:
					$nota4++;
					$soma += 4;
					break;
				case 3:
					$nota3++;
					$soma += 3;
					break;
				case 2:
					$nota2++;
					$soma += 2;
					break;
				case 1:
					$nota1++;
					$soma += 1;
					break;
			}
			
			$qtd_avaliadores++;
				
		}
		
		$resposta[] = array("processo_avaliacao_id" => $processo_id,
				"questionario_id" => $quest_id,
				"questao_id" => $questionario->id,
				"questao_texto" => trim($questionario->texto),
				"itemAvaliado" => $item_avaliado,
				"nota5" => $nota5,
				"nota4" => $nota4,
				"nota3" => $nota3,
				"nota2" => $nota2,
				"nota1" => $nota1,
				"media" => $soma/$qtd_avaliadores,
				"tipo_avaliacao" => $tipo_avaliacao,
				"subtipo_avaliacao" => $subtipo_avaliacao);
		
		

		
			//



		}

		//debug
		//foreach ($resposta as $resp){
		//	print_r($resp);
		//	echo "<br />";
		//}
		//print_r($resposta);
		//exit;


		// Create and populate the data table.
		/*var data = google.visualization.arrayToDataTable([
		 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'MÃ©dia'],
				['IntroduÃ§Ã£o Ã  AdministraÃ§Ã£o',  5,      8,         6,             22,           0,      8.2],
				['EstatÃ­stica Aplicada', 2,      7,        8,             10,          3,      6],
				['ComunicaÃ§Ã£o e Linguagem',  0,      15,       5,             0,           11,     6.2],
				['Filosofia',  3,      5,       2,             1,           8,     3.8]
				]);*/

		$chart = "function drawChart(){

				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Questao');
				data.addColumn('number', '5 estrelas');
				data.addColumn('number', '4 estrelas');
				data.addColumn('number', '3 estrelas');
				data.addColumn('number', '2 estrelas');
				data.addColumn('number', '1 estrela');
				data.addColumn('number', 'Media');

				";

		$arr1 = $resposta;

		$chart .= 'data.addRows('.sizeof($arr1).');';
		$i = 0;
		for($i; $i <sizeof($arr1); $i++){
			$d = utf8_encode($arr1[$i]["questao_texto"]);
			//$m = escalaDecimal($arr1[$i]["media"]);
			$m = $arr1[$i]["media"];
			
			$item = $arr1[$i]["tipo_avaliacao"];

			$chart .=  'data.setValue('.$i.', 0, \''.$d.'\');';
			$chart .=  'data.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
			$chart .=  'data.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
			$chart .=  'data.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
			$chart .=  'data.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
			$chart .=  'data.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
			$chart .=  'data.setValue('.$i.', 6, '.$m.');';
			
			//print_r($arr1[$i]);

		}


		$chart .= "var barChart = new google.visualization.ChartWrapper({
				'chartType': 'ColumnChart',
				'containerId': 'chart1',
				'options': {
				'width': '100%',
				'height': 400,
				'hAxis': {'minValue': 0, 'maxValue': 10},
				'chartArea': {top: 0, right: 0, bottom: 0},
				'series': {5: {type: 'line'}},
				'pointSize': 5,
				'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
				animation:{
        			'duration': 1000,
        			'easing': 'linear'
      			}
	}
	});

				";

		// Define a slider control for the Age column.
		$chart .= "var slider = new google.visualization.ControlWrapper({
				'controlType': 'NumberRangeFilter',
				'containerId': 'control1',
				'options': {
				'filterColumnLabel': 'Media',
				'minValue': 1,
      			'maxValue': 5,
				'ui': {'labelStacking': 'vertical'}
	}
	});

				";

		// Define a category picker control for the Gender column
		$chart .= "var categoryPicker = new google.visualization.ControlWrapper({
				'controlType': 'CategoryFilter',
				'containerId': 'control2',
				'options': {
				'filterColumnLabel': 'Questao',
				'ui': {
				'labelStacking': 'vertical',
				'allowTyping': false,
				'allowMultiple': true
	}
	}
	});

				";

		// Define a table
		$chart .= "var table = new google.visualization.ChartWrapper({
				'chartType': 'Table',
				'containerId': 'chart2',
				'options': {
				'width': '900px',
				'allowHtml': true
	}
	});

				";

		$chart .= "var formatter = new google.visualization.ColorFormat();
				formatter.addRange(0, 3.99, '#CC0000', null);
				formatter.addRange(3.99, 5, '#006600', null);
				formatter.format(data, 6); 

				";
		
		$chart .= "var formatter2 = new google.visualization.NumberFormat(
				{pattern: '#.##'});
				formatter2.format(data, 6);
		
				";

		
		
		// Create a dashboard
		$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard')).
				// Establish bindings, declaring the both the slider and the category
				// picker will drive both charts.
				bind([slider, categoryPicker], [barChart, table]).
				// Draw the entire dashboard.
				draw(data);
	}
				";
		return $chart;

	}
	
	
	/**
	 * @name relatorioinstituicao
	 * @author Fabio Baï¿½a
	 * @since 11/09/2012 14:08:23
	 * insert a description here
	 **/
	function relatorioInstituicao($semestre, $curso) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
		
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		// o q eu preciso:
		// id do ProcessoAvaliacao
		// Curso
		// instrumento(tipo de avaliacao)
		// avaliador
		// questionario usado
		
		$processo_id = 2;
		$tipo_avaliacao = "Aluno";
		$subtipo_avaliacao = "Instituição";
		$item_avaliado = "Instituição";
		
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
		
		$quest_id = $quest_usado->getQuestionarioId();
		
		
		/*
		$semestre_escolhido = utf8_decode("1º SEMESTRE");
		$curso_escolhido = utf8_decode("Serviço Social");*/
		
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
		$semestre_escolhido = "";
		$pos = 0;
		//print_r($semestre);
		foreach($semestre as $s => $value){
			if($pos == 0){
				$semestre_escolhido .= "'".utf8_decode($semestre[$s]."º SEMESTRE")."'";
			}else{
				$semestre_escolhido .= ", '".utf8_decode($semestre[$s]."º SEMESTRE")."'";
			}
			$pos++;
		}
		
		
		$curso_escolhido = "";
		$pos = 0;
		//print_r($curso);
		foreach($curso as $c => $value){
			if($pos == 0){
				$curso_escolhido .= "'".utf8_decode($curso[$c])."'";
			}else{
				$curso_escolhido .= ", '".utf8_decode($curso[$c])."'";
			}
			$pos++;			
		}
		
		//debug
		//echo "cursos >>> ".$curso_escolhido;
		//echo "semestre >>> ".$semestre_escolhido;
		
		//aqui começa o for pra criar um grafico por curso/periodo
		//primeiro criamos as divs q vão conter os charts(uma pra cada curso/disciplina)
		$divs = sizeof($curso)*sizeof($semestre);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($curso as $c => $value){
			foreach ($semestre as $s => $value){
				/*
				$rel_name = "Avaliador: ".$tipo_avaliacao;
				$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
				$rel_name .= "<br/>Curso: ".utf8_encode($curso_escolhido);
				$rel_name .= "<br/>Período: ".utf8_encode($semestre_escolhido);
				*/
				
				//aqui chama a funcao que mostra os comentarios
				$comments = relatorioComentarios2($tipo_avaliacao, $subtipo_avaliacao, $curso[$c], $semestre[$s]);
				
				$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br/><span>Curso:</span> ".$curso[$c]."<br/><span>Período:</span> ".$semestre[$s]."</h3>";
					
					
				//$rel_name .= "<br/>Curso: ".utf8_encode($curso_escolhido);
				//$rel_name .= "<br/>Período: ".utf8_encode($semestre_escolhido);
					
				//$_SESSION["s_rel_name"] = $rel_name;
					
					
				$questionario = new Questionario();
				$questionario->get($quest_id);
				$questionario->alias('q');
				$q = new Questao();
				$qhq = new QuestionarioHasQuestao();
					
				$questionario->join($q,'INNER','qu');
				$questionario->join($qhq,'INNER','qhq');
					
				$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
					
				$questionario->where("qu.id = qhq.questaoId");
				$questionario->order("qhq.ordem");
					
				$questionario->find();
					
				while( $questionario->fetch() ) {
						
					if(!isset($_POST["semestre"]) || utf8_decode($semestre[$s]) == "Todos"){
						if(utf8_decode($curso[$c]) == "Todos"){
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
											and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
											WHERE tha.turma_id_turma = t.id_turma
											and questionario_has_questao_questao_id = '$questionario->id')
											";
						}else{
							/*$sql = "select * from avaliacao where processo_avaliacao_id = 2
							 and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
									WHERE tha.turma_id_turma = t.id_turma
									and t.curso = '".$curso_escolhido."'
									and questionario_has_questao_questao_id = '$questionario->id')
							";*/
								
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
							WHERE tha.turma_id_turma = t.id_turma
							and t.curso IN (".$curso_escolhido.")
											and questionario_has_questao_questao_id = '$questionario->id')
											";
						}//fecha else
							
					}else{
						if(utf8_decode($curso[$c]) == "Todos"){
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
						and questionario_has_questao_questionario_id = ".$quest_id."
						and tipo_avaliacao = '".$tipo_avaliacao."'
						and subtipo_avaliacao = '".$subtipo_avaliacao."'
						and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
						WHERE tha.turma_id_turma = t.id_turma
						and t.serie = '".utf8_decode($semestre[$s]."º SEMESTRE")."'
										and questionario_has_questao_questao_id = '$questionario->id')
										";
						}else{
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							 and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
									WHERE tha.turma_id_turma = t.id_turma
									and t.curso = '".utf8_decode($curso[$c])."'
									and t.serie = '".utf8_decode($semestre[$s]."º SEMESTRE")."'
									and questionario_has_questao_questao_id = '$questionario->id')
							";
							/*$sql = "select * from avaliacao where processo_avaliacao_id = 2
						and questionario_has_questao_questionario_id = ".$quest_id."
						and tipo_avaliacao = '".$tipo_avaliacao."'
						and subtipo_avaliacao = '".$subtipo_avaliacao."'
						and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
						WHERE tha.turma_id_turma = t.id_turma
						and t.curso IN (".$curso_escolhido.")
						and t.serie IN (".$semestre_escolhido.")
										and questionario_has_questao_questao_id = '$questionario->id')
							";*/
								
						}//fecha else
							
							
					}//fecha else
						
					//debug
					//echo $sql;
				
						
					//$sql = "select * from avaliacao";
					$query = mysql_query($sql);
					//$qt = mysql_num_rows($query);
					//echo $qt;
						
					$nota5 = 0;
					$nota4 = 0;
					$nota3 = 0;
					$nota2 = 0;
					$nota1 = 0;
						
					$soma = 0;
						
					$qtd_avaliadores = 0;
						
					while ($dados = mysql_fetch_assoc($query)) {
						//print_r($dados);
						//echo "<hr />";
						switch ($dados["nota"]) {
							case 5:
								$nota5++;
								$soma += 5;
								break;
							case 4:
								$nota4++;
								$soma += 4;
								break;
							case 3:
								$nota3++;
								$soma += 3;
								break;
							case 2:
								$nota2++;
								$soma += 2;
								break;
							case 1:
								$nota1++;
								$soma += 1;
								break;
						}//fecha switch
				
						$qtd_avaliadores++;
							
					}//fecha while
					
					if($qtd_avaliadores <= 0){
						$media = 0;
					}else{
						$media = $soma/$qtd_avaliadores;
					}
						
					$resposta[] = array("processo_avaliacao_id" => $processo_id,
							"questionario_id" => $quest_id,
							"questao_id" => $questionario->id,
							"questao_texto" => trim($questionario->texto),
							"itemAvaliado" => $item_avaliado,
							"nota5" => $nota5,
							"nota4" => $nota4,
							"nota3" => $nota3,
							"nota2" => $nota2,
							"nota1" => $nota1,
							"media" => $media,
							"tipo_avaliacao" => $tipo_avaliacao,
							"subtipo_avaliacao" => $subtipo_avaliacao);
				
				}//fecha while fetch
			
					
				//cria os conteiners pra conter os graficos
				//drawChart".$conteiner_id."();
				$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									drawComments(".$conteiner_id.",'".$comments."');
									qtd++;
								
								});									
								
							";
				
								
				$chart .= "function drawChart".$conteiner_id."(){
			
						var data_".$conteiner_id." = new google.visualization.DataTable();
						data_".$conteiner_id.".addColumn('string', 'Questao');
						data_".$conteiner_id.".addColumn('number', '5 estrelas');
						data_".$conteiner_id.".addColumn('number', '4 estrelas');
						data_".$conteiner_id.".addColumn('number', '3 estrelas');
						data_".$conteiner_id.".addColumn('number', '2 estrelas');
						data_".$conteiner_id.".addColumn('number', '1 estrela');
						data_".$conteiner_id.".addColumn('number', 'Media');
			
						";
					
				$arr1 = $resposta;
				$resposta = null;//importante -- para zerar o array pro proximo grafico
					
				$chart .= "data_".$conteiner_id.".addRows(".sizeof($arr1).");";
				$i = 0;
				for($i; $i <sizeof($arr1); $i++){
					$d = utf8_encode($arr1[$i]["questao_texto"]);
					//$m = escalaDecimal($arr1[$i]["media"]);
					$m = $arr1[$i]["media"];
						
					$item = $arr1[$i]["tipo_avaliacao"];
						
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$d.'\');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$m.');';
						
					//print_r($arr1[$i]);
						
				}//fecha for
					
					
				$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
						'chartType': 'ColumnChart',
						'containerId': 'chart1_".$conteiner_id."',
						'options': {
						'width': '100%',
						'height': 400,
						'hAxis': {'minValue': 0, 'maxValue': 10},
						'chartArea': {top: 0, right: 0, bottom: 0},
						'series': {5: {type: 'line'}},
						'pointSize': 5,
						'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
						animation:{
		        			'duration': 1000,
		        			'easing': 'linear'
		      			}
			}
			});
			
						";
					
				// Define a slider control for the Age column.
				$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
						'controlType': 'NumberRangeFilter',
						'containerId': 'control1_".$conteiner_id."',
						'options': {
						'filterColumnLabel': 'Media',
						'minValue': 1,
		      			'maxValue': 5,
						'ui': {'labelStacking': 'vertical'}
			}
			});
			
						";
					
				// Define a category picker control for the Gender column
				$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
						'controlType': 'CategoryFilter',
						'containerId': 'control2_".$conteiner_id."',
						'options': {
						'filterColumnLabel': 'Questao',
						'ui': {
						'labelStacking': 'vertical',
						'allowTyping': false,
						'allowMultiple': true
			}
			}
			});
			
						";
					
				// Define a table
				$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
						'chartType': 'Table',
						'containerId': 'chart2_".$conteiner_id."',
						'options': {
						'width': '100%',
						'allowHtml': true
			}
			});
			
						";
					
				$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
						formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
						formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
						formatter_".$conteiner_id.".format(data_".$conteiner_id.", 6);
			
						";
					
				$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
						{pattern: '#.##'});
						formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 6);
			
						";
					
					
					
				// Create a dashboard
				$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
						// Establish bindings, declaring the both the slider and the category
						// picker will drive both charts.
						bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
						// Draw the entire dashboard.
						draw(data_".$conteiner_id.");
			}
						";
				
				$conteiner_id += 1;
				$all_chart .= $chart;

							
			}//fim foreach semestre		
		}//fim foreach curso
		
		return $all_chart;
	    		
	
	}//fecha function
	
	/**
	 * @name relatorioinstituicao2
	 * @author Fabio Baï¿½a
	 * @since 18/09/2012 15:26:07
	 * insert a description here
	 **/
	function relatorioInstituicao2($tipo, $curso = null) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
	
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		$processo_id = 2;
		//$tipo_avaliacao = "Professor";
		$tipo_avaliacao = $tipo;
		$subtipo_avaliacao = "Instituição";
		$item_avaliado = "Instituição";
	
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
	
		$quest_id = $quest_usado->getQuestionarioId();
	
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
	
		//aqui começa o for pra criar um grafico por curso
		//primeiro criamos as divs q vão conter os charts(uma pra cada curso)
		$divs = sizeof($curso);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($curso as $c => $value){
			
			//aqui chama a funcao que mostra os comentarios
			$comments = relatorioComentarios($tipo_avaliacao, $subtipo_avaliacao);
			
			//aqui chama a funcao que mostra os comentarios
			//$comments = relatorioComentarios2($tipo_avaliacao, $subtipo_avaliacao, $curso[$c], $semestre[$s]);
			
			$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br/><span>Curso:</span> ".$curso[$c]."</h3>";
		
		
			$questionario = new Questionario();
			$questionario->get($quest_id);
			$questionario->alias('q');
			$q = new Questao();
			$qhq = new QuestionarioHasQuestao();
		
			$questionario->join($q,'INNER','qu');
			$questionario->join($qhq,'INNER','qhq');
		
			$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
		
			$questionario->where("qu.id = qhq.questaoId");
			$questionario->order("qhq.ordem");
		
			$questionario->find();
		
			$lista_professores = "";
			if((utf8_decode($curso[$c]) != "Todos" || utf8_decode($curso[$c]) != null) && ($tipo != "Funcionário")){
				//monta um array com todos os professores da coordenacao
				$lista = new Turma();
				$lista->curso = utf8_decode($curso[$c]);
				$lista->group("professor_id");
				$lista->find();
				$pos = 0;
				while( $lista->fetch()){
					if($pos == 0){
						$lista_professores .= "".$lista->getProfessorId();
					}else{
						$lista_professores .= ", ".$lista->getProfessorId();
					}
					$pos++;	
				}
			}
			
			
			while( $questionario->fetch() ) {
					
				if(utf8_decode($curso[$c]) != "Todos" && utf8_decode($curso[$c]) != null && $tipo_avaliacao != "Funcionário"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and avaliador in(".$lista_professores.")
									and questionario_has_questao_questao_id = '$questionario->id'
									";
					//debug
					//echo ">>> ".$sql;
					//exit;
				}else{
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
									and questionario_has_questao_questao_id = '$questionario->id'
									";
				}
			
		
			$query = mysql_query($sql);
	
				
			$nota5 = 0;
			$nota4 = 0;
			$nota3 = 0;
			$nota2 = 0;
			$nota1 = 0;
		
			$soma = 0;
		
			$qtd_avaliadores = 0;
			
			//se obteve resultados
			if($query){
				
				while ($dados = mysql_fetch_assoc($query)) {
		
					switch ($dados["nota"]) {
						case 5:
							$nota5++;
							$soma += 5;
							break;
						case 4:
							$nota4++;
							$soma += 4;
							break;
						case 3:
							$nota3++;
							$soma += 3;
							break;
						case 2:
							$nota2++;
							$soma += 2;
							break;
						case 1:
							$nota1++;
							$soma += 1;
							break;
					}
						
					$qtd_avaliadores++;
			
				}//fecha while
				
			}else{//fecha if
				//faz nada
			}
			
			if($qtd_avaliadores <= 0){
				$media = 0;
			}else{
				$media = $soma/$qtd_avaliadores;
			}
		
			$resposta[] = array("processo_avaliacao_id" => $processo_id,
					"questionario_id" => $quest_id,
					"questao_id" => $questionario->id,
					"questao_texto" => trim($questionario->texto),
					"itemAvaliado" => $item_avaliado,
					"nota5" => $nota5,
					"nota4" => $nota4,
					"nota3" => $nota3,
					"nota2" => $nota2,
					"nota1" => $nota1,
					"media" => $media,
					"tipo_avaliacao" => $tipo_avaliacao,
					"subtipo_avaliacao" => $subtipo_avaliacao);
			}
		
			//cria os conteiners pra conter os graficos
			//drawChart".$conteiner_id."();
			$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									drawComments(".$conteiner_id.",'".$comments."');
									qtd++;
								});
						
			
							";
		
			$chart .= "function drawChart".$conteiner_id."(){
		
					var data_".$conteiner_id." = new google.visualization.DataTable();
					data_".$conteiner_id.".addColumn('string', 'Id');
					data_".$conteiner_id.".addColumn('string', 'Questao');
					data_".$conteiner_id.".addColumn('number', '5 estrelas');
					data_".$conteiner_id.".addColumn('number', '4 estrelas');
					data_".$conteiner_id.".addColumn('number', '3 estrelas');
					data_".$conteiner_id.".addColumn('number', '2 estrelas');
					data_".$conteiner_id.".addColumn('number', '1 estrela');
					data_".$conteiner_id.".addColumn('number', 'Media');
		
					";
		
			$arr1 = $resposta;
			$resposta = null; //important
		
			$chart .= 'data_'.$conteiner_id.'.addRows('.sizeof($arr1).');';
			$i = 0;
			for($i; $i <sizeof($arr1); $i++){
				$q = utf8_encode($arr1[$i]["questao_texto"]);
				$id = utf8_encode($arr1[$i]["questao_id"]);
				$m = $arr1[$i]["media"];
		
				$item = $arr1[$i]["tipo_avaliacao"];
		
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$id.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, \''.$q.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota5"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota4"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota3"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota2"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$arr1[$i]["nota1"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 7, '.$m.');';
		
				//print_r($arr1[$i]);
		
			}
		
		
			$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'ColumnChart',
					'containerId': 'chart1_".$conteiner_id."',
					'options': {
					'width': '100%',
					'height': 400,
					'hAxis': {'minValue': 0, 'maxValue': 10},
					'chartArea': {top: 0, right: 0, bottom: 0},
					'series': {5: {type: 'line'}},
					'pointSize': 5,
					'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
					animation:{
	        			'duration': 1000,
	        			'easing': 'linear'
	      			}
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			// Define a slider control for the Age column.
			$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'NumberRangeFilter',
					'containerId': 'control1_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Media',
					'minValue': 1,
	      			'maxValue': 5,
					'ui': {'labelStacking': 'vertical'}
		}
		});
		
					";
		
			// Define a category picker control for the Gender column
			$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'CategoryFilter',
					'containerId': 'control2_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Questao',
					'ui': {
					'labelStacking': 'vertical',
					'allowTyping': false,
					'allowMultiple': true
		}
		}
		});
		
					";
		
			// Define a table
			$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'Table',
					'containerId': 'chart2_".$conteiner_id."',
					'options': {
					'width': '100%',
					'allowHtml': true
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
					formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
					formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
					formatter_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
					{pattern: '#.##'});
					formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			/*
			$chart .= "google.visualization.events.addListener(table, 'select', function() {
				barChart.setSelection(table.getSelection());
			});
			";
			
			$chart .= "google.visualization.events.addListener(barChart, 'select', function() {
				table.setSelection(barChart.getSelection());
			});
			";
			*/
		
		
			// Create a dashboard
			$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
					// Establish bindings, declaring the both the slider and the category
					// picker will drive both charts.
					bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
					// Draw the entire dashboard.
					draw(data_".$conteiner_id.");
		}
					";
			
			$conteiner_id += 1;
			$all_chart .= $chart;
			
			
		}//fecha foreach	
		return $all_chart;
	
	}/**
	 * @name relatorioAutoAvaliacao
	 * @author Fabio Baía
	 * @since 30/01/2013 10:38:21
	 * insert a description here
	 **/
	function relatorioAutoAvaliacao($tipo, $curso) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
	
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		$processo_id = 2;
		$tipo_avaliacao = $tipo;
		$subtipo_avaliacao = "Auto-avaliação-".strtolower($tipo);
		//$item_avaliado = "Instituição";
	
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
	
		$quest_id = $quest_usado->getQuestionarioId();
	
		
	
		//aqui começa o for pra criar um grafico por curso
		//primeiro criamos as divs q vão conter os charts(uma pra cada curso)
		$divs = sizeof($curso);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($curso as $c => $value){
						
			$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br/><span>Curso:</span> ".$curso[$c]."</h3>";
		
		
			$questionario = new Questionario();
			$questionario->get($quest_id);
			$questionario->alias('q');
			$q = new Questao();
			$qhq = new QuestionarioHasQuestao();
		
			$questionario->join($q,'INNER','qu');
			$questionario->join($qhq,'INNER','qhq');
		
			$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
		
			$questionario->where("qu.id = qhq.questaoId");
			$questionario->order("qhq.ordem");
		
			$questionario->find();
		
			$lista_professores = "";
			if((utf8_decode($curso[$c]) != "Todos" || utf8_decode($curso[$c]) != null) && ($tipo != "Funcionário")){
				//monta um array com todos os professores da coordenacao
				$lista = new Turma();
				$lista->curso = utf8_decode($curso[$c]);
				$lista->group("professor_id");
				$lista->find();
				$pos = 0;
				while( $lista->fetch()){
					if($pos == 0){
						$lista_professores .= "".$lista->getProfessorId();
					}else{
						$lista_professores .= ", ".$lista->getProfessorId();
					}
					$pos++;	
				}
			}
			
			
			while( $questionario->fetch() ) {
					
				if(utf8_decode($curso[$c]) != "Todos" && utf8_decode($curso[$c]) != null && $tipo_avaliacao != "Funcionário"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and avaliador in(".$lista_professores.")
									and questionario_has_questao_questao_id = '$questionario->id'
									";
					//debug
					//echo ">>> ".$sql;
					//exit;
				}else{
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
									and questionario_has_questao_questao_id = '$questionario->id'
									";
				}
			
		
			$query = mysql_query($sql);
	
				
			$nota5 = 0;
			$nota4 = 0;
			$nota3 = 0;
			$nota2 = 0;
			$nota1 = 0;
		
			$soma = 0;
		
			$qtd_avaliadores = 0;
			
			//se obteve resultados
			if($query){
				
				while ($dados = mysql_fetch_assoc($query)) {
		
					switch ($dados["nota"]) {
						case 5:
							$nota5++;
							$soma += 5;
							break;
						case 4:
							$nota4++;
							$soma += 4;
							break;
						case 3:
							$nota3++;
							$soma += 3;
							break;
						case 2:
							$nota2++;
							$soma += 2;
							break;
						case 1:
							$nota1++;
							$soma += 1;
							break;
					}
						
					$qtd_avaliadores++;
			
				}//fecha while
				
			}else{//fecha if
				//faz nada
			}
			
			if($qtd_avaliadores <= 0){
				$media = 0;
			}else{
				$media = $soma/$qtd_avaliadores;
			}
		
			$resposta[] = array("processo_avaliacao_id" => $processo_id,
					"questionario_id" => $quest_id,
					"questao_id" => $questionario->id,
					"questao_texto" => trim($questionario->texto),
					"itemAvaliado" => $item_avaliado,
					"nota5" => $nota5,
					"nota4" => $nota4,
					"nota3" => $nota3,
					"nota2" => $nota2,
					"nota1" => $nota1,
					"media" => $media,
					"tipo_avaliacao" => $tipo_avaliacao,
					"subtipo_avaliacao" => $subtipo_avaliacao);
			}
		
			//cria os conteiners pra conter os graficos
			//drawChart".$conteiner_id."();
			$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									qtd++;
								});
						
			
							";
		
			$chart .= "function drawChart".$conteiner_id."(){
		
					var data_".$conteiner_id." = new google.visualization.DataTable();
					data_".$conteiner_id.".addColumn('string', 'Id');
					data_".$conteiner_id.".addColumn('string', 'Questao');
					data_".$conteiner_id.".addColumn('number', '5 estrelas');
					data_".$conteiner_id.".addColumn('number', '4 estrelas');
					data_".$conteiner_id.".addColumn('number', '3 estrelas');
					data_".$conteiner_id.".addColumn('number', '2 estrelas');
					data_".$conteiner_id.".addColumn('number', '1 estrela');
					data_".$conteiner_id.".addColumn('number', 'Media');
		
					";
		
			$arr1 = $resposta;
			$resposta = null; //important
		
			$chart .= 'data_'.$conteiner_id.'.addRows('.sizeof($arr1).');';
			$i = 0;
			for($i; $i <sizeof($arr1); $i++){
				$q = utf8_encode($arr1[$i]["questao_texto"]);
				$id = utf8_encode($arr1[$i]["questao_id"]);
				$m = $arr1[$i]["media"];
		
				$item = $arr1[$i]["tipo_avaliacao"];
		
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$id.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, \''.$q.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota5"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota4"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota3"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota2"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$arr1[$i]["nota1"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 7, '.$m.');';
		
				//print_r($arr1[$i]);
		
			}
		
		
			$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'ColumnChart',
					'containerId': 'chart1_".$conteiner_id."',
					'options': {
					'width': '100%',
					'height': 400,
					'hAxis': {'minValue': 0, 'maxValue': 10},
					'chartArea': {top: 0, right: 0, bottom: 0},
					'series': {5: {type: 'line'}},
					'pointSize': 5,
					'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
					animation:{
	        			'duration': 1000,
	        			'easing': 'linear'
	      			}
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			// Define a slider control for the Age column.
			$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'NumberRangeFilter',
					'containerId': 'control1_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Media',
					'minValue': 1,
	      			'maxValue': 5,
					'ui': {'labelStacking': 'vertical'}
		}
		});
		
					";
		
			// Define a category picker control for the Gender column
			$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'CategoryFilter',
					'containerId': 'control2_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Questao',
					'ui': {
					'labelStacking': 'vertical',
					'allowTyping': false,
					'allowMultiple': true
		}
		}
		});
		
					";
		
			// Define a table
			$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'Table',
					'containerId': 'chart2_".$conteiner_id."',
					'options': {
					'width': '100%',
					'allowHtml': true
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
					formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
					formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
					formatter_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
					{pattern: '#.##'});
					formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			/*
			$chart .= "google.visualization.events.addListener(table, 'select', function() {
				barChart.setSelection(table.getSelection());
			});
			";
			
			$chart .= "google.visualization.events.addListener(barChart, 'select', function() {
				table.setSelection(barChart.getSelection());
			});
			";
			*/
		
		
			// Create a dashboard
			$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
					// Establish bindings, declaring the both the slider and the category
					// picker will drive both charts.
					bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
					// Draw the entire dashboard.
					draw(data_".$conteiner_id.");
		}
					";
			
			$conteiner_id += 1;
			$all_chart .= $chart;
			
			
		}//fecha foreach	
		return $all_chart;
	
	}
	
	
	
	/**
	 * @name relatorioLab
	 * @author Fabio Baï¿½a
	 * @since 21/11/2012 16:01:19
	 * insert a description here
	 **/
	function relatorioLab($tipo, $subtipo, $curso = null) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
	
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		$processo_id = 2;
		//$tipo_avaliacao = "Professor";
		$tipo_avaliacao = $tipo;
		$subtipo_avaliacao = $subtipo;
		$item_avaliado = $subtipo;
	
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
	
		$quest_id = $quest_usado->getQuestionarioId();
	
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
		
		//
		//aqui começa o for pra criar um grafico por curso
		//primeiro criamos as divs q vão conter os charts(uma pra cada curso)
		$divs = sizeof($curso);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($curso as $c => $value){
			
			//aqui chama a funcao que mostra os comentarios
			$comments = relatorioComentarios2($tipo_avaliacao, $subtipo_avaliacao, $curso[$c]);
			
			$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br/><span>Curso:</span> ".$curso[$c]."</h3>";
		
		
			$questionario = new Questionario();
			$questionario->get($quest_id);
			$questionario->alias('q');
			$q = new Questao();
			$qhq = new QuestionarioHasQuestao();
		
			$questionario->join($q,'INNER','qu');
			$questionario->join($qhq,'INNER','qhq');
		
			$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
		
			$questionario->where("qu.id = qhq.questaoId");
			$questionario->order("qhq.ordem");
		
			$questionario->find();
		
			
			//verifica se é professor ou aluno
			$lista_avaliadores = "";
			
			//cria uma lista com os professores do curso
			if(($curso[$c] != "Todos") && ($tipo == "Professor")){
				//monta um array com todos os professores da coordenacao
				$lista = new Turma();
				$lista->curso = $curso[$c];
				$lista->group("professor_id");
				$lista->find();
				$pos = 0;
				while( $lista->fetch()){
					if($pos == 0){
						$lista_avaliadores .= "'".$lista->getProfessorId()."'";
					}else{
						$lista_avaliadores .= ", '".$lista->getProfessorId()."'";
					}
					$pos++;
				}
			}
			
			if(($curso[$c] != "Todos") && ($tipo == "Aluno")){
				//monta um array com todos os alunos do curso
				$aluno = new Aluno();				 
				$aluno->curso = utf8_decode($curso[$c]);
				$aluno->find();
		
				$pos = 0;
				while( $aluno->fetch()){
					if($pos == 0){
						$lista_avaliadores .= "'".$aluno->getRa()."'";
					}else{
						$lista_avaliadores .= ", '".$aluno->getRa()."'";
					}
					$pos++;
				}
			}
			
			//debug
			//echo "Avaliadores: ".$lista_avaliadores."<br />";
			
			
		
			while( $questionario->fetch() ) {
		
				if($curso[$c] != "Todos"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and avaliador in(".$lista_avaliadores.")
						and questionario_has_questao_questao_id = '$questionario->id'
						";
					//debug
					//echo ">>> ".$sql;
					//exit;
				}else {
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
						and questionario_has_questao_questao_id = '$questionario->id'
						";
					
					
				}
				//debug
				//echo ">>> ".$sql."<br />";
				//exit;
				
				
						
				$query = mysql_query($sql);
								
				$nota5 = 0;
				$nota4 = 0;
				$nota3 = 0;
				$nota2 = 0;
				$nota1 = 0;
		
				$soma = 0;
		
				$qtd_avaliadores = 0;
				
				//se obteve resultados
				if($query){
					
					while ($dados = mysql_fetch_assoc($query)) {
			
						switch ($dados["nota"]) {
							case 5:
								$nota5++;
								$soma += 5;
								break;
							case 4:
								$nota4++;
								$soma += 4;
								break;
							case 3:
								$nota3++;
								$soma += 3;
								break;
							case 2:
								$nota2++;
								$soma += 2;
								break;
							case 1:
								$nota1++;
								$soma += 1;
								break;
						}
			
						$qtd_avaliadores++;
			
					}//fim do while
					
				}//fim do if
				else{
					//faz nada
				}
				
				if($qtd_avaliadores <= 0){
					$media = 0;
				}else{
					$media = $soma/$qtd_avaliadores;
				}
				
				$resposta[] = array("processo_avaliacao_id" => $processo_id,
						"questionario_id" => $quest_id,
						"questao_id" => $questionario->id,
						"questao_texto" => trim($questionario->texto),
						"itemAvaliado" => $item_avaliado,
						"nota5" => $nota5,
						"nota4" => $nota4,
						"nota3" => $nota3,
						"nota2" => $nota2,
						"nota1" => $nota1,
						"media" => $media,
						"tipo_avaliacao" => $tipo_avaliacao,
						"subtipo_avaliacao" => $subtipo_avaliacao);
			}
		
		
			//cria os conteiners pra conter os graficos
			//drawChart".$conteiner_id."();
			$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									drawComments(".$conteiner_id.",'".$comments."');
									qtd++;
								});		
													
							";

			$chart .= "function drawChart".$conteiner_id."(){
		
					var data_".$conteiner_id." = new google.visualization.DataTable();
					data_".$conteiner_id.".addColumn('string', 'Id');
					data_".$conteiner_id.".addColumn('string', 'Questao');
					data_".$conteiner_id.".addColumn('number', '5 estrelas');
					data_".$conteiner_id.".addColumn('number', '4 estrelas');
					data_".$conteiner_id.".addColumn('number', '3 estrelas');
					data_".$conteiner_id.".addColumn('number', '2 estrelas');
					data_".$conteiner_id.".addColumn('number', '1 estrela');
					data_".$conteiner_id.".addColumn('number', 'Media');
		
					";
		
			$arr1 = $resposta;
			$resposta = null;//zera o array -- importante
		
			$chart .= 'data_'.$conteiner_id.'.addRows('.sizeof($arr1).');';
			$i = 0;
			for($i; $i <sizeof($arr1); $i++){
				$q = utf8_encode($arr1[$i]["questao_texto"]);
				$id = utf8_encode($arr1[$i]["questao_id"]);
				$m = $arr1[$i]["media"];
		
				$item = $arr1[$i]["tipo_avaliacao"];
		
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$id.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, \''.$q.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota5"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota4"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota3"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota2"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$arr1[$i]["nota1"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 7, '.$m.');';
		
				//print_r($arr1[$i]);
		
			}
		
		
			$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'ColumnChart',
					'containerId': 'chart1_".$conteiner_id."',
					'options': {
					'width': '100%',
					'height': 400,
					'hAxis': {'minValue': 0, 'maxValue': 10},
					'chartArea': {top: 0, right: 0, bottom: 0},
					'series': {5: {type: 'line'}},
					'pointSize': 5,
					'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
					animation:{
	        			'duration': 1000,
	        			'easing': 'linear'
	      			}
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			// Define a slider control for the Age column.
			$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'NumberRangeFilter',
					'containerId': 'control1_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Media',
					'minValue': 1,
	      			'maxValue': 5,
					'ui': {'labelStacking': 'vertical'}
		}
		});
		
					";
		
			// Define a category picker control for the Gender column
			$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'CategoryFilter',
					'containerId': 'control2_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Questao',
					'ui': {
					'labelStacking': 'vertical',
					'allowTyping': false,
					'allowMultiple': true
		}
		}
		});
		
					";
		
			// Define a table
			$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'Table',
					'containerId': 'chart2_".$conteiner_id."',
					'options': {
					'width': '100%',
					'allowHtml': true
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
					formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
					formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
					formatter_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
					{pattern: '#.##'});
					formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			/*
				$chart .= "google.visualization.events.addListener(table, 'select', function() {
						barChart.setSelection(table.getSelection());
						});
			";
		
			$chart .= "google.visualization.events.addListener(barChart, 'select', function() {
					table.setSelection(barChart.getSelection());
					});
			";
			*/
		
		
			// Create a dashboard
			$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
					// Establish bindings, declaring the both the slider and the category
					// picker will drive both charts.
					bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
					// Draw the entire dashboard.
					draw(data_".$conteiner_id.");
		}
					";
		
			$conteiner_id += 1;
			$all_chart .= $chart;
		
		
		}//fim foreach curso
		
		return $all_chart;
	
	}
	
	
	
	/**
	 * @name relatorioProfessor
	 * @author Fabio Baï¿½a
	 * @since 02/10/2012 13:55:29
	 * insert a description here
	 **/
	function relatorioProfessor($tipo, $t_id) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
	
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		$processo_id = 2;
		$tipo_avaliacao = $tipo;
		$subtipo_avaliacao = "Professor/Disciplina";
		//$subtipo_avaliacao = "Instituição";
		//$item_avaliado = $t_id;
		
		
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
	
		$quest_id = $quest_usado->getQuestionarioId();
		
	
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
		
		//aqui começa o for pra criar um grafico por turma
		//primeiro criamos as divs q vão conter os charts(uma pra cada turma)
		$divs = sizeof($t_id);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($t_id as $t => $value){
			
			//aqui chama a funcao que mostra os comentarios
			$comments = relatorioComentarios($tipo, $t_id[$t]);
			
			//C:Psicologia-1
			$temp_turma = new Turma();
			if(utf8_decode($t_id[$t]) == "Todas"){
				//faz nada, pega todo mundo
				$temp_turma->find(true);
				$temp_turma_name = utf8_encode($temp_turma->getNomeDisciplina());
				
				//info
				$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br /><span>Curso:</span>Todos<br /><span>Per&iacute;odo: </span>Todos</h3>";
			}
			else if(utf8_decode($t_id[$t]) != "Todas" && substr(utf8_decode($t_id[$t]), 0, 1) == "C"){
				$str = explode(":", utf8_decode($t_id[$t]));
				$str2 = explode("-", $str[1]);
				
				/*if($str2[1] == "Todos"){
					$temp_turma->where("curso = '".$str2[0]."'");
				}else{
					//$str2[1] = $str2.utf8_decode("º SEMESTRE");
					$temp_turma->where("curso = '".$str2[0]."' AND serie = '".$str2[1]."'");
				}
				*/			
				
				//info
				$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br /><span>Curso:</span> ".utf8_encode($str2[0])."<br /><span>Per&iacute;odo: </span> ".utf8_encode($str2[1])."</h3>";
							
				
			}else{
				$temp_turma->idTurma = utf8_decode($t_id[$t]);
				
				$temp_turma->find(true);
				$temp_turma_name = utf8_encode($temp_turma->getNomeDisciplina());
				
				//info
				$temp_prof = new Professor();
				$temp_prof->id = $temp_turma->getProfessorId();
				$temp_prof->find(true);
					
				$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br /><span>Curso:</span> ".utf8_encode($temp_turma->getCurso())."<br /><span>Per&iacute;odo: </span> ".utf8_encode($temp_turma->getSerie())."<br /><span>Turma/Disciplina:</span> ".utf8_decode($t_id[$t])." - ".$temp_turma_name."<br /><span>Professor: </span>".utf8_encode($temp_prof->getNome())."</h3>";
			}
			
		
		
			$questionario = new Questionario();
			$questionario->get($quest_id);
			$questionario->alias('q');
			$q = new Questao();
			$qhq = new QuestionarioHasQuestao();
		
			$questionario->join($q,'INNER','qu');
			$questionario->join($qhq,'INNER','qhq');
		
			$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
		
			$questionario->where("qu.id = qhq.questaoId");
			$questionario->order("qhq.ordem");
		
			$questionario->find();
		
			while( $questionario->fetch() ) {
				
				//aqui verifica se o relatorio é por turma, semestre ou curso
				if(utf8_decode($t_id[$t]) == "Todas"){
					//pega todo mundo
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and questionario_has_questao_questao_id = '$questionario->id'
									";
				}
				else if(utf8_decode($t_id[$t]) != "Todas" && substr(utf8_decode($t_id[$t]), 0, 1) == "C"){
					//pega todo mundo do curso/periodo selecionado
					$temp_turma = new Turma();//important
					$str = explode(":", utf8_decode($t_id[$t]));
					$str2 = explode("-", $str[1]);
				
					if($str2[1] == "Todos"){
						$temp_turma->where("curso = '".$str2[0]."'");
					}else{
						$temp_turma->where("curso = '".$str2[0]."' AND serie = '".$str2[1]."'");
					}
				
					$temp_turma->find();

					//pega as turmas do curso e periodo selecionado e colocam em um array
					$turmas_curso_periodo = "";
					$p = 0;
					while($temp_turma->fetch()){
						if($p == 0){
							$turmas_curso_periodo .= "'".$temp_turma->idTurma."'";
						}else{
							$turmas_curso_periodo .= ", '".$temp_turma->idTurma."'";
						}
						$p++;
					}
					
					//echo "turmas >>>>> ".$turmas_curso_periodo;
					
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and item_avaliado IN (".$turmas_curso_periodo.")
										and questionario_has_questao_questao_id = '$questionario->id'
										";
					//echo "<br />".$sql;
						
				
				}else{
					//pega somente a turma selecionada
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and item_avaliado = '".utf8_decode($t_id[$t])."'
					and questionario_has_questao_questao_id = '$questionario->id'
									";
				}
				
				
				//
				/*
				if(utf8_decode($t_id[$t]) != "Todas"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and item_avaliado = '".utf8_decode($t_id[$t])."'
					and questionario_has_questao_questao_id = '$questionario->id'
									";
				}else{
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and questionario_has_questao_questao_id = '$questionario->id'
									";
				}
				*/
		
		
				$query = mysql_query($sql);
		
					
				$nota5 = 0;
				$nota4 = 0;
				$nota3 = 0;
				$nota2 = 0;
				$nota1 = 0;
		
				$soma = 0;
		
				$qtd_avaliadores = 0;
					
				while ($dados = mysql_fetch_assoc($query)) {
		
					switch ($dados["nota"]) {
						case 5:
							$nota5++;
							$soma += 5;
							break;
						case 4:
							$nota4++;
							$soma += 4;
							break;
						case 3:
							$nota3++;
							$soma += 3;
							break;
						case 2:
							$nota2++;
							$soma += 2;
							break;
						case 1:
							$nota1++;
							$soma += 1;
							break;
					}
		
					$qtd_avaliadores++;
		
				}
		
				if($qtd_avaliadores <= 0){
					$media = 0;
				}else{
					$media = $soma/$qtd_avaliadores;
				}
				
				$resposta[] = array("processo_avaliacao_id" => $processo_id,
						"questionario_id" => $quest_id,
						"questao_id" => $questionario->id,
						"questao_texto" => trim($questionario->texto),
						"itemAvaliado" => $item_avaliado,
						"nota5" => $nota5,
						"nota4" => $nota4,
						"nota3" => $nota3,
						"nota2" => $nota2,
						"nota1" => $nota1,
						"media" => $media,
						"tipo_avaliacao" => $tipo_avaliacao,
						"subtipo_avaliacao" => $subtipo_avaliacao);
			}
		
			//cria os conteiners pra conter os graficos
				//drawChart".$conteiner_id."();
				$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									drawComments(".$conteiner_id.",'".$comments."');
									qtd++;
								});
											
								
							";
		
			$chart .= "function drawChart".$conteiner_id."(){
		
					var data_".$conteiner_id." = new google.visualization.DataTable();
					data_".$conteiner_id.".addColumn('string', 'Id');
					data_".$conteiner_id.".addColumn('string', 'Questao');
					data_".$conteiner_id.".addColumn('number', '5 estrelas');
					data_".$conteiner_id.".addColumn('number', '4 estrelas');
					data_".$conteiner_id.".addColumn('number', '3 estrelas');
					data_".$conteiner_id.".addColumn('number', '2 estrelas');
					data_".$conteiner_id.".addColumn('number', '1 estrela');
					data_".$conteiner_id.".addColumn('number', 'Media');
		
					";
		
			$arr1 = $resposta;
			$resposta = null; //important
		
			$chart .= 'data_'.$conteiner_id.'.addRows('.sizeof($arr1).');';
			$i = 0;
			for($i; $i <sizeof($arr1); $i++){
				$q = utf8_encode($arr1[$i]["questao_texto"]);
				$id = utf8_encode($arr1[$i]["questao_id"]);
				$m = $arr1[$i]["media"];
		
				$item = $arr1[$i]["tipo_avaliacao"];
		
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$id.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, \''.$q.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota5"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota4"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota3"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota2"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$arr1[$i]["nota1"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 7, '.$m.');';
		
				//print_r($arr1[$i]);
		
			}
		
			//exit;
		
			$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'ColumnChart',
					'containerId': 'chart1_".$conteiner_id."',
					'options': {
					'width': '100%',
					'height': 400,
					'hAxis': {'minValue': 0, 'maxValue': 10},
					'chartArea': {top: 0, right: 0, bottom: 0},
					'series': {5: {type: 'line'}},
					'pointSize': 5,
					'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
					animation:{
	        			'duration': 1000,
	        			'easing': 'linear'
	      			}
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			// Define a slider control for the Age column.
			$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'NumberRangeFilter',
					'containerId': 'control1_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Media',
					'minValue': 1,
	      			'maxValue': 5,
					'ui': {'labelStacking': 'vertical'}
		}
		});
		
					";
		
			// Define a category picker control for the Gender column
			$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'CategoryFilter',
					'containerId': 'control2_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Questao',
					'ui': {
					'labelStacking': 'vertical',
					'allowTyping': false,
					'allowMultiple': true
		}
		}
		});
		
					";
		
			// Define a table
			$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'Table',
					'containerId': 'chart2_".$conteiner_id."',
					'options': {
					'width': '100%',
					'allowHtml': true
		},
					'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
		});
		
					";
		
			$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
					formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
					formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
					formatter_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
					{pattern: '#.##'});
					formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 7);
		
					";
		
			/*
				$chart .= "google.visualization.events.addListener(table, 'select', function() {
						barChart.setSelection(table.getSelection());
						});
			";
		
			$chart .= "google.visualization.events.addListener(barChart, 'select', function() {
					table.setSelection(barChart.getSelection());
					});
			";
			*/
		
		
			// Create a dashboard
			$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
					// Establish bindings, declaring the both the slider and the category
					// picker will drive both charts.
					bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
					// Draw the entire dashboard.
					draw(data_".$conteiner_id.");
		}
					";
	
			$conteiner_id += 1;
			$all_chart .= $chart;
			
		}//fecha foreach
	
		return $all_chart;
	
	}
	
	
	/**
	 * @name relatorioCoordenador
	 * @author Fabio Baï¿½a
	 * @since 11/09/2012 14:08:23
	 * insert a description here
	 **/
	function relatorioCoordenador($semestre, $curso) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
	
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		// o q eu preciso:
		// id do ProcessoAvaliacao
		// Curso
		// instrumento(tipo de avaliacao)
		// avaliador
		// questionario usado
	
		$processo_id = 2;
		$quest_id = 20;
		$tipo_avaliacao = "Aluno";
		$subtipo_avaliacao = "Curso/Coordenador";
		//$item_avaliado = utf8_decode($curso);
	
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
	
		//aqui começa o for pra criar um grafico por curso/periodo
		//primeiro criamos as divs q vão conter os charts(uma pra cada curso/disciplina)
		$divs = sizeof($curso)*sizeof($semestre);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($curso as $c => $value){
			foreach ($semestre as $s => $value){
				//aqui chama a funcao que mostra os comentarios
				$comments = relatorioComentarios2($tipo_avaliacao, $subtipo_avaliacao, $curso[$c], $semestre[$s]);
				
				$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br/><span>Curso:</span> ".$curso[$c]."<br/><span>Período:</span> ".$semestre[$s]."</h3>";
				
				
			
				$questionario = new Questionario();
				$questionario->get($quest_id);
				$questionario->alias('q');
				$q = new Questao();
				$qhq = new QuestionarioHasQuestao();
			
				$questionario->join($q,'INNER','qu');
				$questionario->join($qhq,'INNER','qhq');
			
				$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
			
				$questionario->where("qu.id = qhq.questaoId");
				$questionario->order("qhq.ordem");
			
				$questionario->find();
			
				while( $questionario->fetch() ) {
				
					if(!isset($_POST["semestre"]) || utf8_decode($semestre[$s]) == "Todos"){
						if(utf8_decode($curso[$c]) == "Todos"){
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
							WHERE tha.turma_id_turma = t.id_turma
							and questionario_has_questao_questao_id = '$questionario->id')
												";
						}else{
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
							WHERE tha.turma_id_turma = t.id_turma
							and t.curso = '".utf8_decode($curso[$c])."'
												and questionario_has_questao_questao_id = '$questionario->id')
												";
						}
						
					}else{
						if(utf8_decode($curso[$c] == "Todos")){
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
							WHERE tha.turma_id_turma = t.id_turma
							and t.serie = '".utf8_decode($semestre[$s]."º SEMESTRE")."'
												and questionario_has_questao_questao_id = '$questionario->id')
												";
						}else{
							$sql = "select * from avaliacao where processo_avaliacao_id = 2
							and questionario_has_questao_questionario_id = ".$quest_id."
							and tipo_avaliacao = '".$tipo_avaliacao."'
							and subtipo_avaliacao = '".$subtipo_avaliacao."'
							and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
							WHERE tha.turma_id_turma = t.id_turma
							and t.curso = '".utf8_decode($curso[$c])."'
							and t.serie = '".utf8_decode($semestre[$s]."º SEMESTRE")."'
												and questionario_has_questao_questao_id = '$questionario->id')
												";
						}
						
					}
					
					
				/*
				 	
					
				*/
					
				//$sql = "select * from avaliacao";
				$query = mysql_query($sql);
				//$qt = mysql_num_rows($query);
				//echo $qt;
					
				$nota5 = 0;
				$nota4 = 0;
				$nota3 = 0;
				$nota2 = 0;
				$nota1 = 0;
			
				$soma = 0;
			
				$qtd_avaliadores = 0;
					
				while ($dados = mysql_fetch_assoc($query)) {
					//print_r($dados);
					//echo "<hr />";
					switch ($dados["nota"]) {
						case 5:
							$nota5++;
							$soma += 5;
							break;
						case 4:
							$nota4++;
							$soma += 4;
							break;
						case 3:
							$nota3++;
							$soma += 3;
							break;
						case 2:
							$nota2++;
							$soma += 2;
							break;
						case 1:
							$nota1++;
							$soma += 1;
							break;
					}
						
					$qtd_avaliadores++;
			
				}
				
				if($qtd_avaliadores <= 0){
					$media = 0;
				}else{
					$media = $soma/$qtd_avaliadores;
				}
			
				$resposta[] = array("processo_avaliacao_id" => $processo_id,
						"questionario_id" => $quest_id,
						"questao_id" => $questionario->id,
						"questao_texto" => trim($questionario->texto),
						"itemAvaliado" => $item_avaliado,
						"nota5" => $nota5,
						"nota4" => $nota4,
						"nota3" => $nota3,
						"nota2" => $nota2,
						"nota1" => $nota1,
						"media" => $media,
						"tipo_avaliacao" => $tipo_avaliacao,
						"subtipo_avaliacao" => $subtipo_avaliacao);
				}
			
				//debug
				/*foreach ($resposta as $resp){
				 print_r($resp);
				echo "<br />";
				}*/
				//print_r($resposta);
				//exit;
			
			
				//cria os conteiners pra conter os graficos
			//drawChart".$conteiner_id."();
			$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									drawComments(".$conteiner_id.",'".$comments."');
									qtd++;
								});		
													
							";
			
				$chart .= "function drawChart".$conteiner_id."(){
			
						var data_".$conteiner_id." = new google.visualization.DataTable();
						data_".$conteiner_id.".addColumn('string', 'Questao');
						data_".$conteiner_id.".addColumn('number', '5 estrelas');
						data_".$conteiner_id.".addColumn('number', '4 estrelas');
						data_".$conteiner_id.".addColumn('number', '3 estrelas');
						data_".$conteiner_id.".addColumn('number', '2 estrelas');
						data_".$conteiner_id.".addColumn('number', '1 estrela');
						data_".$conteiner_id.".addColumn('number', 'Media');
			
						";
			
				$arr1 = $resposta;
				$resposta = null; //important
			
				$chart .= 'data_'.$conteiner_id.'.addRows('.sizeof($arr1).');';
				$i = 0;
				for($i; $i <sizeof($arr1); $i++){
					$d = utf8_encode($arr1[$i]["questao_texto"]);
					//$m = escalaDecimal($arr1[$i]["media"]);
					$m = $arr1[$i]["media"];
			
					$item = $arr1[$i]["tipo_avaliacao"];
			
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$d.'\');';			
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
					$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$m.');';
			
					//print_r($arr1[$i]);
			
				}
			
				/*'colors':['#006600','#00CC00','#FFCC00','#FF6600','#CC0000','#3366FF'],*/
			
				$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
						'chartType': 'ColumnChart',
						'containerId': 'chart1_".$conteiner_id."',
						'options': {
						'width': '100%',
						'height': 400,
						'hAxis': {'minValue': 0, 'maxValue': 10},
						'chartArea': {top: 0, right: 0, bottom: 0},
						'series': {5: {type: 'line'}},
						'pointSize': 5,
						'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
						animation:{
		        			'duration': 1000,
		        			'easing': 'linear'
		      			}
			}
			});
			
						";
			
				// Define a slider control for the Age column.
				$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
						'controlType': 'NumberRangeFilter',
						'containerId': 'control1_".$conteiner_id."',
						'options': {
						'filterColumnLabel': 'Media',
						'minValue': 1,
		      			'maxValue': 5,
						'ui': {'labelStacking': 'vertical'}
			}
			});
			
						";
			
				// Define a category picker control for the Gender column
				$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
						'controlType': 'CategoryFilter',
						'containerId': 'control2_".$conteiner_id."',
						'options': {
						'filterColumnLabel': 'Questao',
						'ui': {
						'labelStacking': 'vertical',
						'allowTyping': false,
						'allowMultiple': true
			}
			}
			});
			
						";
			
				// Define a table
				$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
						'chartType': 'Table',
						'containerId': 'chart2_".$conteiner_id."',
						'options': {
						'width': '100%',
						'allowHtml': true
			}
			});
			
						";
			
				$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
						formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
						formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
						formatter_".$conteiner_id.".format(data_".$conteiner_id.", 6);
			
						";
			
				$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
						{pattern: '#.##'});
						formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 6);
			
						";
			
			
			
				// Create a dashboard
				$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
						// Establish bindings, declaring the both the slider and the category
						// picker will drive both charts.
						bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
						// Draw the entire dashboard.
						draw(data_".$conteiner_id.");
			}
						";
				
				$conteiner_id += 1;
				$all_chart .= $chart;
				
			}//fecha foreach
		}//fecha foreach
		return $all_chart;
	
	}
	
	/**
	 * @name relatorioCoordenador2
	 * @author Fabio Baï¿½a
	 * @since 11/09/2012 14:08:23
	 * insert a description here
	 **/
	function relatorioCoordenador2($curso) {
		$host="mysql01-farm26.kinghost.net";
		$user="faculdadeunica05";
		$pass="avaliacaounicampo159";
		$DB="faculdadeunica05";
	
		$conexao = mysql_pconnect($host,$user,$pass) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
		$banco = mysql_select_db($DB);
	
		// o q eu preciso:
		// id do ProcessoAvaliacao
		// Curso
		// instrumento(tipo de avaliacao)
		// avaliador
		// questionario usado
	
		$processo_id = 2;
		
		$tipo_avaliacao = "Professor";
		$subtipo_avaliacao = "Coordenador";
		//$item_avaliado = utf8_decode($curso);
		
		//descobre o questionario_id
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
		
		$quest_id = $quest_usado->getQuestionarioId();
		//$quest_id = 20;
	
		/*
		 $semestre_escolhido = utf8_decode("1º SEMESTRE");
		$curso_escolhido = utf8_decode("Serviço Social");*/
	
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
	
		//aqui começa o for pra criar um grafico por curso
		//primeiro criamos as divs q vão conter os charts(uma pra cada curso)
		$divs = sizeof($curso);
		$conteiner_id = 0;
		$all_chart = "";
		foreach ($curso as $c => $value){
			
			//$rel_name = "Avaliador: ".$tipo_avaliacao;
			//$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
			//$rel_name .= "<br/>Curso: ".utf8_encode($curso_escolhido);
		
			//$_SESSION["s_rel_name"] = $rel_name;
			
			$dashinfo = "<h3><span>Avaliador:</span> ".$tipo_avaliacao."<br/><span>Questionário:</span> ".$subtipo_avaliacao."<br/><span>Curso:</span> ".$curso[$c]."</h3>";
		
			$questionario = new Questionario();
			$questionario->get($quest_id);
			$questionario->alias('q');
			$q = new Questao();
			$qhq = new QuestionarioHasQuestao();
		
			$questionario->join($q,'INNER','qu');
			$questionario->join($qhq,'INNER','qhq');
		
			$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional, qhq.ordem");
		
			$questionario->where("qu.id = qhq.questaoId");
			$questionario->order("qhq.ordem");
		
			$questionario->find();
		
			if(utf8_decode($curso[$c]) != "Todos"){
				//verificar qual o coordenador do curso
				//pra filtrar a avaliacao por curso
				//na tabela turma
				$coord_id = new Turma();
				$coord_id->curso = utf8_decode($curso[$c]);
				$coord_id->group("coordenador_id");
				$coord_id->find(true);
				$coord_id = $coord_id->getCoordenadorId();
			}
			
			while( $questionario->fetch() ) {
		
				if(utf8_decode($curso[$c]) == "Todos"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and questionario_has_questao_questao_id = ".$questionario->getId()."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'";
				}else{
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and questionario_has_questao_questao_id = ".$questionario->getId()."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and item_avaliado = '".$coord_id."'";
				}
				
		
				
			//$sql = "select * from avaliacao";
			$query = mysql_query($sql);
			//$qt = mysql_num_rows($query);
			//echo $qt;
				
			$nota5 = 0;
			$nota4 = 0;
			$nota3 = 0;
			$nota2 = 0;
			$nota1 = 0;
		
			$soma = 0;
		
			$qtd_avaliadores = 0;
				
			while ($dados = mysql_fetch_assoc($query)) {
				//print_r($dados);
				//echo "<hr />";
				switch ($dados["nota"]) {
					case 5:
						$nota5++;
						$soma += 5;
						break;
					case 4:
						$nota4++;
						$soma += 4;
						break;
					case 3:
						$nota3++;
						$soma += 3;
						break;
					case 2:
						$nota2++;
						$soma += 2;
						break;
					case 1:
						$nota1++;
						$soma += 1;
						break;
				}
		
				$qtd_avaliadores++;
		
			}
			
			if($qtd_avaliadores <= 0){
				$media = 0;
			}else{
				$media = $soma/$qtd_avaliadores;
			}
		
			$resposta[] = array("processo_avaliacao_id" => $processo_id,
					"questionario_id" => $quest_id,
					"questao_id" => $questionario->id,
					"questao_texto" => trim($questionario->texto),
					"itemAvaliado" => $item_avaliado,
					"nota5" => $nota5,
					"nota4" => $nota4,
					"nota3" => $nota3,
					"nota2" => $nota2,
					"nota1" => $nota1,
					"media" => $media,
					"tipo_avaliacao" => $tipo_avaliacao,
					"subtipo_avaliacao" => $subtipo_avaliacao);
			}
		
			//debug
			/*foreach ($resposta as $resp){
			 print_r($resp);
			echo "<br />";
			}*/
			//print_r($resposta);
			//exit;
		
		
			//cria os conteiners pra conter os graficos
			//drawChart".$conteiner_id."();
			$chart = "
								$(document).ready(function() {
									drawConteiners(".$conteiner_id.");
									drawInfo(".$conteiner_id.",'".$dashinfo."');
									qtd++;
								});
						
			
							";
			$chart .= "function drawChart".$conteiner_id."(){
		
					var data_".$conteiner_id." = new google.visualization.DataTable();
					data_".$conteiner_id.".addColumn('string', 'Questao');
					data_".$conteiner_id.".addColumn('number', '5 estrelas');
					data_".$conteiner_id.".addColumn('number', '4 estrelas');
					data_".$conteiner_id.".addColumn('number', '3 estrelas');
					data_".$conteiner_id.".addColumn('number', '2 estrelas');
					data_".$conteiner_id.".addColumn('number', '1 estrela');
					data_".$conteiner_id.".addColumn('number', 'Media');
		
					";
		
			$arr1 = $resposta;
			$resposta = null; //important
		
			$chart .= 'data_'.$conteiner_id.'.addRows('.sizeof($arr1).');';
			$i = 0;
			for($i; $i <sizeof($arr1); $i++){
				$d = utf8_encode($arr1[$i]["questao_texto"]);
				//$m = escalaDecimal($arr1[$i]["media"]);
				$m = $arr1[$i]["media"];
		
				$item = $arr1[$i]["tipo_avaliacao"];
		
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 0, \''.$d.'\');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
				$chart .=  'data_'.$conteiner_id.'.setValue('.$i.', 6, '.$m.');';
		
				//print_r($arr1[$i]);
		
			}
		
			/*'colors':['#006600','#00CC00','#FFCC00','#FF6600','#CC0000','#3366FF'],*/
		
			$chart .= "var barChart_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'ColumnChart',
					'containerId': 'chart1_".$conteiner_id."',
					'options': {
					'width': '100%',
					'height': 400,
					'hAxis': {'minValue': 0, 'maxValue': 10},
					'chartArea': {top: 0, right: 0, bottom: 0},
					'series': {5: {type: 'line'}},
					'pointSize': 5,
					'colors':['#82CCB5','#B6D884','#FFED81','#FECD7E','#F8A792','#6BBCE9'],
					animation:{
	        			'duration': 1000,
	        			'easing': 'linear'
	      			}
		}
		});
		
					";
		
			// Define a slider control for the Age column.
			$chart .= "var slider_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'NumberRangeFilter',
					'containerId': 'control1_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Media',
					'minValue': 1,
	      			'maxValue': 5,
					'ui': {'labelStacking': 'vertical'}
		}
		});
		
					";
		
			// Define a category picker control for the Gender column
			$chart .= "var categoryPicker_".$conteiner_id." = new google.visualization.ControlWrapper({
					'controlType': 'CategoryFilter',
					'containerId': 'control2_".$conteiner_id."',
					'options': {
					'filterColumnLabel': 'Questao',
					'ui': {
					'labelStacking': 'vertical',
					'allowTyping': false,
					'allowMultiple': true
		}
		}
		});
		
					";
		
			// Define a table
			$chart .= "var table_".$conteiner_id." = new google.visualization.ChartWrapper({
					'chartType': 'Table',
					'containerId': 'chart2_".$conteiner_id."',
					'options': {
					'width': '100%',
					'allowHtml': true
		}
		});
		
					";
		
			$chart .= "var formatter_".$conteiner_id." = new google.visualization.ColorFormat();
					formatter_".$conteiner_id.".addRange(0, 3.99, '#CC0000', null);
					formatter_".$conteiner_id.".addRange(3.99, 5, '#006600', null);
					formatter_".$conteiner_id.".format(data_".$conteiner_id.", 6);
		
					";
		
			$chart .= "var formatter2_".$conteiner_id." = new google.visualization.NumberFormat(
					{pattern: '#.##'});
					formatter2_".$conteiner_id.".format(data_".$conteiner_id.", 6);
		
					";
		
		
		
			// Create a dashboard
			$chart .= "new google.visualization.Dashboard(document.getElementById('dashboard_".$conteiner_id."')).
					// Establish bindings, declaring the both the slider and the category
					// picker will drive both charts.
					bind([slider_".$conteiner_id.", categoryPicker_".$conteiner_id."], [barChart_".$conteiner_id.", table_".$conteiner_id."]).
					// Draw the entire dashboard.
					draw(data_".$conteiner_id.");
		}
					";
			$conteiner_id += 1;
			$all_chart .= $chart;
			
		}//fecha foreach
		
		return $all_chart;
	
	}
	
	
	function relatorioComentarios($tipo, $subtipo) {
		$periodoLetivo = "1/2012";
		
		//conversao de caracteres
		if(substr($subtipo, 0, 4) == "Lab_"){
			$subtipo = utf8_decode($subtipo);
		}
		
		$comentarios = new Comentarios();
		$comentarios->tipoAvaliacao = $tipo;
		$comentarios->itemAvaliado = $subtipo;
		$comentarios->find();
		
				
		$html = "";
		$temp = 0;
		while ($comentarios->fetch()) {
			$html .= "<p>".trim($comentarios->getComentario())."</p>";
			
			//remove line breaks
			$html = str_replace (array("\r\n", "\n", "\r"), ' ', $html);			
			
			$temp++;
		}
		
		
		return $html;
	}
	
	
	function relatorioComentarios2($tipo, $subtipo, $curso, $semestre = null) {
		$periodoLetivo = "1/2012";
		
		//conversao de caracteres
		if(substr($subtipo, 0, 4) == "Lab_"){
			$subtipo = utf8_decode($subtipo);
		}
		
		if($tipo == "Aluno"){
						
			if($curso == "Todos"){
				
			}else{
				//monta um array de alunos tendo como filtro o curso e semestre
				$where_curso = " AND t.curso ='".utf8_decode($curso)."'";
				if($semestre == "Todos" || $semestre == null){
						
				}else{
					$where_semestre = " AND t.serie = '".utf8_decode($semestre."º SEMESTRE")."'";
				}
				
				
					
				//pega o curso e monta um array com as turmas do curso
				$a = new Aluno();
				$a->alias('a');
				
				$t = new Turma();
				$a->join($t,'INNER','t');
				
				$tha = new TurmaHasAluno();
				
				$a->join($tha,'INNER','tha');
					
				$a->select("t.idTurma, t.nomeDisciplina, t.professorId, t.periodoLetivo, t.serie, t.curso, t.turma, a.nome, a.curso, a.ra, tha.avaliado");
				$a->where("t.periodoLetivo = '".$periodoLetivo."' and tha.turmaIdTurma = t.idTurma ".$where_curso." ".$where_semestre."");
				$a->group("ra");
				$a->order("ra ASC");
					
				$a->find();
					
				$str_alunos = "";
					
				$p = 0;
				while($a->fetch()){
					if($p == 0){
						$str_alunos .= "'".$a->ra."'";
					}else{
						$str_alunos .= ", '".$a->ra."'";
					}
					$p++;
				
				}
					
				//debug
				//echo "Alunos ->> ".$str_alunos;
				//exit;
					
				$comentarios = new Comentarios();
				$comentarios->tipoAvaliacao = $tipo;
				$comentarios->subtipoAvaliacao = $subtipo;
				$comentarios->where("avaliador in(".$str_alunos.")");
				$comentarios->find();
					
				//print_r($array_alunos);
				//exit;
				
				$html = "";
				while ($comentarios->fetch()) {
					$html .= "<p>".trim($comentarios->getComentario())."</p>";
					//remove line breaks
					$html = str_replace (array("\r\n", "\n", "\r"), ' ', $html);
				}
			}
			
						
		}
			
		
		return $html;
	}


	function discoveryInfoTurma($id, $info) {

		$infoRetorno;

		$turma = new Turma();


		if($info == "nomeDisciplina"){
			$turma->get($id);
			$infoRetorno = $turma->nomeDisciplina;
		}
		if($info == "nomeProfessor"){
			$turma->get($id);
			$infoRetorno = $turma->professorId;
		}
		if($info == "nomeCoordenador"){
			$turma->get("curso", $id);
			$infoRetorno = $turma->coordenadorId;
		}
		if($info == "nomeCurso"){
			$turma->get("curso", $id);
			$infoRetorno = $turma->curso;
		}

		return $infoRetorno;

	}


	?>