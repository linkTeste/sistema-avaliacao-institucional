<?php

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual p�gina chamar de acordo com a action

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
 * @author Fabio Ba�a
 * @since 12/01/2012
 * controller do questionario - respons�vel por tratar as requisi��es via get, post ou session.
 * Controla o fluxo da aplica��o definindo qual p�gina chamar de acordo com a action recebida.
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
 * @author Fabio Ba�a
 * @since 12/01/2012
 * fun��o que verifica a action e direciona para a action espec�fica
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

	if(isset($_GET["relatorio_id"])){
		$relatorio_id = $_GET["relatorio_id"];
	}

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
		if(isset($_GET["semestre"])){
			$semestre = $_GET["semestre"]."º SEMESTRE";
		}else{
			
		}
		
		$curso = $_GET["curso"];
		
		$relatorio = relatorioInstituicao($semestre, $curso);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	
	if($relatorio_id == 5){
		if(isset($_GET["semestre"])){
			$semestre = $_GET["semestre"]."º SEMESTRE";
		}else{
				
		}
	
	
		$curso = $_GET["curso"];
	
		$relatorio = relatorioCoordenador($semestre, $curso);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 6){
		$t = $_GET["tipo"];
		$c = $_GET["curso"];
		$relatorio = relatorioInstituicao2($t, $c);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	if($relatorio_id == 7){
		$t = $_GET["tipo"];
		$sub = $_GET["subtipo"];
		$relatorio = relatorioComentarios($t, $sub);
		$_SESSION["s_active_chart_comment"] = $relatorio;
	}
	
	if($relatorio_id == 8){
		$t = $_GET["tipo"];
		$t_id = $_GET["turma_id"];
		$relatorio = relatorioProfessor($t, $t_id);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	
	if($relatorio_id == 9){
		$curso = $_GET["curso"];
		$relatorio = relatorioCoordenador2($curso);
		$_SESSION["s_active_chart"] = $relatorio;
	}
	
	
	if($action == "load_"){
		$avaliador = $_GET["avaliador"];
		
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
				
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&tipo=Funcionário' class='chart'>Instituição</a>";
				
				echo "</div>";
					
				exit;
			}
			if($_SESSION["rel_filtro_1"] == "Professor"){
				echo "<div class='box_op'>";
				
				echo "<h4>Questionario:</h4>";
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=6&tipo=Professor' class='chart'>Instituição</a>";
				while($quest->fetch()){
					echo "<a href='#' id='op2_".utf8_encode($quest->getSubtipo())."' onclick='loadOptions(2, \"".utf8_encode($quest->getSubtipo())."\");'>".utf8_encode($quest->getSubtipo())."</a>";
					//echo "<br />";
				}
				echo "</div>";
					
				exit;
			}
			if($_SESSION["rel_filtro_1"] == "Coordenador"){
				echo "<div class='box_op'>";
				
				echo "<h4>Questionário:</h4>";
				
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&tipo=Coordenador' class='chart'>Instituição</a>";
				
				echo "</div>";
					
				exit;
			}else{
				echo "<div class='box_op'>";
				
				echo "<h4>Questionario:</h4>";
				
				while($quest->fetch()){
					echo "<a href='#' id='op2_".utf8_encode($quest->getSubtipo())."' onclick='loadOptions(2, \"".utf8_encode($quest->getSubtipo())."\");'>".utf8_encode($quest->getSubtipo())."</a>";
					//echo "<br />";
				}
				echo "</div>";
					
				exit;
			}
			
			
			
		}
		if($filtro == 2){
			$_SESSION["rel_filtro_2"] = $parametro;
			
			$temp = substr($_SESSION["rel_filtro_2"], 0, 3);
			if($temp == "Lab"){
			
				//executa relatorio
				//$relatorio = relatorioInstituicao2($_SESSION["rel_filtro_1"]);
				//$_SESSION["s_active_chart"] = $relatorio;
				
				echo "lab";
			
				//redirectTo("relatorios.php");
				exit;
			}
			
			//
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Coordenador"){
					
				//executa relatorio
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
					
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Psicologia' class='chart'>Psicologia</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Enfermagem' class='chart'>Enfermagem</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Serviço Social' class='chart'>Serviço Social</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Tecnologia em Gestão Comercial' class='chart'>Tecnologia em Gestão Comercial</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Tecnologia em Gestão de Cooperativas' class='chart'>Tecnologia em Gestão de Cooperativas</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=Todos' class='chart'>Todos os Cursos</a>";
					
				echo "</div>";
					
				exit;
			}
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Instituição"){
				//executa relatorio
				echo "<div class='box_op'>";
					
				echo "<h4>Curso:</h4>";
					
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Psicologia&tipo=Professor' class='chart'>Psicologia</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Enfermagem&tipo=Professor' class='chart'>Enfermagem</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Serviço Social&tipo=Professor' class='chart'>Serviço Social</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Tecnologia em Gestão Comercial&tipo=Professor' class='chart'>Tecnologia em Gestão Comercial</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Tecnologia em Gestão de Cooperativas&tipo=Professor' class='chart'>Tecnologia em Gestão de Cooperativas</a>";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=6&curso=Todos&tipo=Professor' class='chart'>Todos os Cursos</a>";
					
				echo "</div>";
					
				exit;
				
			}
			
			//seleciona os cursos
			echo "<div class='box_op'>";
				
			echo "<h4>Curso:</h4>";
				
			echo "<a href='#' id='op3_1' onclick='loadOptions(3, \"1\");'>Psicologia</a>";
			//echo "<br />";
			echo "<a href='#' id='op3_2' onclick='loadOptions(3, \"2\");'>Enfermagem</a>";
			//echo "<br />";
			echo "<a href='#' id='op3_3' onclick='loadOptions(3, \"3\");'>Serviço Social</a>";
			//echo "<br />";
			echo "<a href='#' id='op3_4' onclick='loadOptions(3, \"4\");'>Tecnologia em Gestão Comercial</a>";
			//echo "<br />";
			echo "<a href='#' id='op3_5' onclick='loadOptions(3, \"5\");'>Tecnologia em Gestão de Cooperativas</a>";
				
			echo "<a href='#' id='op3_6' onclick='loadOptions(3, \"6\");'>Todos os Cursos</a>";
				
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
				case 6:
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
				
				echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."&semestre=1' class='chart'>1° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."&semestre=3' class='chart'>3° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."&semestre=5' class='chart'>5° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=4&curso=".$curso."' class='chart'>Todos os períodos</a>";
				
				echo "</div>";
				
				exit;
			}
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Curso/Coordenador"){
					
				//executa relatorio
				echo "<div class='box_op'>";
				
				echo "<h4>Período:</h4>";
			
				echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."&semestre=1' class='chart'>1° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."&semestre=3' class='chart'>3° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."&semestre=5' class='chart'>5° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$curso."' class='chart'>Todos os períodos</a>";
			
				echo "</div>";
			
				exit;
			}
			
			if($_SESSION["rel_filtro_1"] == "Aluno" && $_SESSION["rel_filtro_2"] == "Professor/Disciplina"){
				
				echo "<div class='box_op'>";
			
				echo "<h4>Período:</h4>";
					
				echo "<a href='#' id='op4_1' onclick='loadOptions(4, \"1\");'>1° Período</a>";
				//echo "<br />";
				echo "<a href='#' id='op4_3' onclick='loadOptions(4, \"3\");'>3° Período</a>";
				//echo "<br />";
				echo "<a href='#' id='op4_5' onclick='loadOptions(4, \"5\");'>5° Período</a>";
				//echo "<br />";
				echo "<a href='#' id='op4_todos' onclick='loadOptions(4, \"todos\");'>Todos os Períodos</a>";
					
				echo "</div>";
				
				exit;
				
			}
			
			if($_SESSION["rel_filtro_1"] == "Professor" && $_SESSION["rel_filtro_2"] == "Coordenador"){
					
				//executa relatorio
				echo "<div class='box_op'>";
			
				echo "<h4>Período:</h4>";
					
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>1° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>3° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>5° Período</a>";
				//echo "<br />";
				echo "<a href='../Controller/relatorioController.php?relatorio_id=9&curso=".$curso."' class='chart'>Todos os períodos</a>";
					
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
		        	case 6:
		        		//Todos os cursos
		        		$curso = "Todos";
		        		break;
						
				}
				
				$curso = utf8_decode($curso);
				$turmas = new Turma();
				
				//filtra os cursos
				if($curso == "Todos"){
					
				}else{
					$turmas->curso = $curso;
				}
				
				if($_SESSION["rel_filtro_4"] == "todos"){
					
				}else{
					$turmas->serie = utf8_decode($_SESSION["rel_filtro_4"]."º SEMESTRE");
				}
				
				$turmas->find();
				echo "<a href='../Controller/relatorioController.php?relatorio_id=8&tipo=Aluno&turma_id=Todas' class='chart'>Todas as Turmas</a>";
				while($turmas->fetch()){
					echo "<a href='../Controller/relatorioController.php?relatorio_id=8&tipo=Aluno&turma_id=".$turmas->getIdTurma()."' class='chart'>".$turmas->getIdTurma()." - ".utf8_encode($turmas->getNomeDisciplina())."</a>";
					//echo "<br />";
				}
				
				//echo "<a href='../Controller/relatorioController.php?relatorio_id=5&curso=".$_SESSION["rel_filtro_3"]."'>Todos os períodos</a>";
					
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
 * @author Fabio Baía
 * @since 07/08/2012 13:11:06
 * insert a description here
 **/
function alunosPendentes($param) {


}

/**
 * @name relatorioAcessos
 * @author Fabio Baía
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
 * @author Fabio Baía
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
			data.addColumn('string', 'Laboratório');
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
	//graficos possíveis pra esses dados
	//Area - Line - Bar - Column
	$chart .= "chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
			chart.draw(data,
			{title:'Laboratorios - Geral',
			height:300,
			colors: ['#920300'],
			hAxis: {title: 'Laboratório'},
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
 * @author Fabio Baía
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
	 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'Média'],
			['Introdução à Administração',  5,      8,         6,             22,           0,      8.2],
			['Estatística Aplicada', 2,      7,        8,             10,          3,      6],
			['Comunicação e Linguagem',  0,      15,       5,             0,           11,     6.2],
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
			data.addColumn('number', 'Média');

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
			'filterColumnLabel': 'Média',
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
 * @author Fabio Ba�a
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
		 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'Média'],
				['Introdução à Administração',  5,      8,         6,             22,           0,      8.2],
				['Estatística Aplicada', 2,      7,        8,             10,          3,      6],
				['Comunicação e Linguagem',  0,      15,       5,             0,           11,     6.2],
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
	 * @author Fabio Ba�a
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
		
		$semestre_escolhido = utf8_decode($semestre);
		$curso_escolhido = utf8_decode($curso);
		
		$rel_name = "Avaliador: ".$tipo_avaliacao;
		$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
		$rel_name .= "<br/>Curso: ".utf8_encode($curso_escolhido);
		$rel_name .= "<br/>Período: ".utf8_encode($semestre_escolhido);
		
		$_SESSION["s_rel_name"] = $rel_name;
		
	
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

			if(!isset($_GET["semestre"])){
			if($curso == "Todos"){
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
				and t.curso = '".$curso_escolhido."'
				and questionario_has_questao_questao_id = '$questionario->id')
										";
			}
				
			}else{
				if($curso == "Todos"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
				and questionario_has_questao_questionario_id = ".$quest_id."
				and tipo_avaliacao = '".$tipo_avaliacao."'
				and subtipo_avaliacao = '".$subtipo_avaliacao."'
				and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
				WHERE tha.turma_id_turma = t.id_turma
				and t.serie = '".$semestre_escolhido."'
				and questionario_has_questao_questao_id = '$questionario->id')
									";
				}else{
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
				and questionario_has_questao_questionario_id = ".$quest_id."
				and tipo_avaliacao = '".$tipo_avaliacao."'
				and subtipo_avaliacao = '".$subtipo_avaliacao."'
				and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
				WHERE tha.turma_id_turma = t.id_turma 
				and t.curso = '".$curso_escolhido."'
				and t.serie = '".$semestre_escolhido."'
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
		}
	
		//debug
		/*foreach ($resposta as $resp){
			print_r($resp);
			echo "<br />";
		}*/
		//print_r($resposta);
		//exit;
	
	
		// Create and populate the data table.
		/*var data = google.visualization.arrayToDataTable([
		 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'Média'],
				['Introdução à Administração',  5,      8,         6,             22,           0,      8.2],
				['Estatística Aplicada', 2,      7,        8,             10,          3,      6],
				['Comunicação e Linguagem',  0,      15,       5,             0,           11,     6.2],
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
				'width': '100%',
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
	 * @name relatorioinstituicao2
	 * @author Fabio Ba�a
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
	
		$semestre_escolhido = utf8_decode($semestre);
		$curso_escolhido = utf8_decode($curso);
	
		$rel_name = "Avaliador: ".$tipo_avaliacao;
		$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
		$rel_name .= "<br/>Curso: ".$curso;
	
		$_SESSION["s_rel_name"] = $rel_name;
	
	
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
		if(($curso != "Todos") && ($tipo != "Funcionário")){
			//monta um array com todos os professores da coordenacao
			$lista = new Turma();
			$lista->curso = $curso_escolhido;
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
				
			if($curso != "Todos" && $tipo_avaliacao != "Funcionário"){
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
		}
	
		
	
		$chart = "function drawChart(){
	
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Id');
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
			$q = utf8_encode($arr1[$i]["questao_texto"]);
			$id = utf8_encode($arr1[$i]["questao_id"]);
			$m = $arr1[$i]["media"];
	
			$item = $arr1[$i]["tipo_avaliacao"];
	
			$chart .=  'data.setValue('.$i.', 0, \''.$id.'\');';
			$chart .=  'data.setValue('.$i.', 1, \''.$q.'\');';
			$chart .=  'data.setValue('.$i.', 2, '.$arr1[$i]["nota5"].');';
			$chart .=  'data.setValue('.$i.', 3, '.$arr1[$i]["nota4"].');';
			$chart .=  'data.setValue('.$i.', 4, '.$arr1[$i]["nota3"].');';
			$chart .=  'data.setValue('.$i.', 5, '.$arr1[$i]["nota2"].');';
			$chart .=  'data.setValue('.$i.', 6, '.$arr1[$i]["nota1"].');';
			$chart .=  'data.setValue('.$i.', 7, '.$m.');';
	
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
	},
				'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
	});
	
				";
	
		// Define a slider control for the Age column.
		$chart .= "var slider = new google.visualization.ControlWrapper({
				'controlType': 'NumberRangeFilter',
				'containerId': 'control1',
				'options': {
				'filterColumnLabel': 'Media',
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
				'width': '100%',
				'allowHtml': true
	},
				'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
	});
	
				";
	
		$chart .= "var formatter = new google.visualization.ColorFormat();
				formatter.addRange(0, 3.99, '#CC0000', null);
				formatter.addRange(3.99, 5, '#006600', null);
				formatter.format(data, 7);
	
				";
	
		$chart .= "var formatter2 = new google.visualization.NumberFormat(
				{pattern: '#.##'});
				formatter2.format(data, 7);
	
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
	 * @name relatorioProfessor
	 * @author Fabio Ba�a
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
		$item_avaliado = $t_id;
	
		$quest_usado = new QuestionarioUsado();
		$quest_usado->tipo = $tipo_avaliacao;
		$quest_usado->subtipo = $subtipo_avaliacao;
		$quest_usado->processoAvaliacaoId = $processo_id;
		$quest_usado->find(true);
	
		$quest_id = $quest_usado->getQuestionarioId();
		
	
		//$semestre_escolhido = utf8_decode($semestre);
		//$curso_escolhido = utf8_decode($curso);
		if($t_id != "Todas"){
			$temp_turma = new Turma();
			$temp_turma->idTurma = $t_id;
			$temp_turma->find(true);
			$temp_turma_name = utf8_encode($temp_turma->getNomeDisciplina());
			
			$rel_name = "Avaliador: ".$tipo_avaliacao;
			$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
			$rel_name .= "<br/>Turma/Disciplina: ".$t_id." - ".$temp_turma_name;
		}else{
			$temp_turma = new Turma();
			$temp_turma->find(true);
			
			$rel_name = "Avaliador: ".$tipo_avaliacao;
			$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
			$rel_name .= "<br/>Turma/Disciplina: Todas";
		}
		
	
		$_SESSION["s_rel_name"] = $rel_name;
	
	
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
			if($t_id != "Todas"){
				$sql = "select * from avaliacao where processo_avaliacao_id = 2
				and questionario_has_questao_questionario_id = ".$quest_id."
				and tipo_avaliacao = '".$tipo_avaliacao."'
				and subtipo_avaliacao = '".$subtipo_avaliacao."'
				and item_avaliado = '".$item_avaliado."'
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
		}
	
	//print_r($resposta);
	//exit;
	
		$chart = "function drawChart(){
	
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Id');
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
			$q = utf8_encode($arr1[$i]["questao_texto"]);
			$id = utf8_encode($arr1[$i]["questao_id"]);
			$m = $arr1[$i]["media"];
	
			$item = $arr1[$i]["tipo_avaliacao"];
	
			$chart .=  'data.setValue('.$i.', 0, \''.$id.'\');';
			$chart .=  'data.setValue('.$i.', 1, \''.$q.'\');';
			$chart .=  'data.setValue('.$i.', 2, '.$arr1[$i]["nota5"].');';
			$chart .=  'data.setValue('.$i.', 3, '.$arr1[$i]["nota4"].');';
			$chart .=  'data.setValue('.$i.', 4, '.$arr1[$i]["nota3"].');';
			$chart .=  'data.setValue('.$i.', 5, '.$arr1[$i]["nota2"].');';
			$chart .=  'data.setValue('.$i.', 6, '.$arr1[$i]["nota1"].');';
			$chart .=  'data.setValue('.$i.', 7, '.$m.');';
	
			//print_r($arr1[$i]);
	
		}
	
		//exit;
	
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
	},
				'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
	});
	
				";
	
		// Define a slider control for the Age column.
		$chart .= "var slider = new google.visualization.ControlWrapper({
				'controlType': 'NumberRangeFilter',
				'containerId': 'control1',
				'options': {
				'filterColumnLabel': 'Media',
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
				'width': '100%',
				'allowHtml': true
	},
				'view': {'columns': [1, 2, 3, 4, 5, 6, 7]}
	});
	
				";
	
		$chart .= "var formatter = new google.visualization.ColorFormat();
				formatter.addRange(0, 3.99, '#CC0000', null);
				formatter.addRange(3.99, 5, '#006600', null);
				formatter.format(data, 7);
	
				";
	
		$chart .= "var formatter2 = new google.visualization.NumberFormat(
				{pattern: '#.##'});
				formatter2.format(data, 7);
	
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
	 * @name relatorioCoordenador
	 * @author Fabio Ba�a
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
		$item_avaliado = utf8_decode($curso);
	
		/*
			$semestre_escolhido = utf8_decode("1º SEMESTRE");
		$curso_escolhido = utf8_decode("Serviço Social");*/
	
		$semestre_escolhido = utf8_decode($semestre);
		$curso_escolhido = utf8_decode($curso);
	
		$rel_name = "Avaliador: ".$tipo_avaliacao;
		$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
		$rel_name .= "<br/>Curso: ".utf8_encode($curso_escolhido);
		$rel_name .= "<br/>Período: ".utf8_encode($semestre_escolhido);
		
		$_SESSION["s_rel_name"] = $rel_name;
	
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
		
			if(!isset($_GET["semestre"])){
				if($curso == "Todos"){
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
					and t.curso = '".$curso_escolhido."'
										and questionario_has_questao_questao_id = '$questionario->id')
										";
				}
				
			}else{
				if($curso == "Todos"){
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
					WHERE tha.turma_id_turma = t.id_turma
					and t.serie = '".$semestre_escolhido."'
										and questionario_has_questao_questao_id = '$questionario->id')
										";
				}else{
					$sql = "select * from avaliacao where processo_avaliacao_id = 2
					and questionario_has_questao_questionario_id = ".$quest_id."
					and tipo_avaliacao = '".$tipo_avaliacao."'
					and subtipo_avaliacao = '".$subtipo_avaliacao."'
					and avaliador IN (SELECT tha.aluno_ra FROM turma t, turma_has_aluno tha
					WHERE tha.turma_id_turma = t.id_turma
					and t.curso = '".$curso_escolhido."'
					and t.serie = '".$semestre_escolhido."'
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
		}
	
		//debug
		/*foreach ($resposta as $resp){
		 print_r($resp);
		echo "<br />";
		}*/
		//print_r($resposta);
		//exit;
	
	
		// Create and populate the data table.
		/*var data = google.visualization.arrayToDataTable([
		 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'Média'],
				['Introdução à Administração',  5,      8,         6,             22,           0,      8.2],
				['Estatística Aplicada', 2,      7,        8,             10,          3,      6],
				['Comunicação e Linguagem',  0,      15,       5,             0,           11,     6.2],
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
	
		/*'colors':['#006600','#00CC00','#FFCC00','#FF6600','#CC0000','#3366FF'],*/
	
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
				'width': '100%',
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
	 * @name relatorioCoordenador2
	 * @author Fabio Ba�a
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
		$item_avaliado = utf8_decode($curso);
		
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
	
		$semestre_escolhido = utf8_decode($semestre);
		$curso_escolhido = utf8_decode($curso);
	
		$rel_name = "Avaliador: ".$tipo_avaliacao;
		$rel_name .= "<br/>Questionário: ".$subtipo_avaliacao;
		$rel_name .= "<br/>Curso: ".utf8_encode($curso_escolhido);
	
		$_SESSION["s_rel_name"] = $rel_name;
	
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
	
		if($curso != "Todos"){
			//verificar qual o coordenador do curso
			//pra filtrar a avaliacao por curso
			//na tabela turma
			$coord_id = new Turma();
			$coord_id->curso = $curso_escolhido;
			$coord_id->group("coordenador_id");
			$coord_id->find(true);
			$coord_id = $coord_id->getCoordenadorId();
		}
		
		while( $questionario->fetch() ) {
	
			if($curso == "Todos"){
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
		}
	
		//debug
		/*foreach ($resposta as $resp){
		 print_r($resp);
		echo "<br />";
		}*/
		//print_r($resposta);
		//exit;
	
	
		// Create and populate the data table.
		/*var data = google.visualization.arrayToDataTable([
		 ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'Média'],
				['Introdução à Administração',  5,      8,         6,             22,           0,      8.2],
				['Estatística Aplicada', 2,      7,        8,             10,          3,      6],
				['Comunicação e Linguagem',  0,      15,       5,             0,           11,     6.2],
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
	
		/*'colors':['#006600','#00CC00','#FFCC00','#FF6600','#CC0000','#3366FF'],*/
	
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
				'width': '100%',
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
	
	
	function relatorioComentarios($tipo, $subtipo) {
		$comentarios = new Comentarios();
		$comentarios->tipoAvaliacao = $tipo;
		$comentarios->itemAvaliado = $subtipo;
		$comentarios->find();
		
		$rel_name = "Avaliador: ".$tipo;
		$rel_name .= "<br/>Questionário: ".$subtipo."(Comentários)";
		
		$_SESSION["s_rel_name"] = $rel_name;
		
		$html = "<div id='comentarios'>";
		while ($comentarios->fetch()) {
			$html .= "<div class='comentario'>";
			$html .= "<p>".$comentarios->getComentario()."</p>";
			$html .= "</div>";
		}
		
		$html .= "</div>";
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