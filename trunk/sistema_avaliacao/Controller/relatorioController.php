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
		$relatorio = relatorioDisciplina();
		$_SESSION["s_active_chart"] = $relatorio;
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
	          height: 300,
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
      	    		      'colors':['#006600','#00CC00','#FFCC00','#FF6600','#CC0000','#3366FF']
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