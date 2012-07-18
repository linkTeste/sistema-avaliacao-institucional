<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/QuestionarioHasQuestao.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
require_once '../system/application/models/dao/Log.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/Turma.php';
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


// funcoes de relatorios
// log
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

//print_r($array_relatorio_log);

//pega as notas dos professores pra criar o grafico
function disciplinasGeralNotas() {

	//obs: fazer join com tabela turma pra poder agrupar os dados por turma/curso
	//fazer depois


	//pega as disciplinas avaliadas
	//$avaliacoes = array();
	$avaliacoes;
	//$turma = new Turma();
	$av = new Avaliacao();
	$av->alias("av");
	//$av->join($turma, 'INNER', 'turma', 'itemAvaliado', 'turmaIdTurma');
	$av->select("item_avaliado, sum(nota) as soma, count(nota) as qtdNotas, avg(nota) as average");

	// 	$av->setAvaliador("0100.01.10");
	// 	$av->setTipoAvaliacao("Aluno");
	// 	$av->setProcessoAvaliacaoId(1);
	// 	$av->setSubtipoAvaliacao("Professor/Disciplina");

	// 	$av->where("avaliador = '0100.01.10'");
	// 	$av->where("avaliador = '0003.01.10'");

	$av->where("subtipo_avaliacao = 'Professor/Disciplina'");
	$av->group("item_avaliado");

	//usar o order e o limit pra pegar as "melhores" e as "piores" notas
	$av->order("average DESC");
	//$av->limit(0,3);

	$av->find();
	// 	echo $qtd;
	// 	exit;
	while ($av->fetch()) {
		// 			$avaliacoes[]["itemAvaliado"] = $av->itemAvaliado;
		// 			//$avaliacoes[]["nomeDisciplina"] = discoveryDisciplineName($av->itemAvaliado);
		// 			$avaliacoes[]["somas"] = $av->soma;
		// 			$avaliacoes[]["qtd_notas"] = $av->qtdNotas;
			
		$avaliacoes[] = array("itemAvaliado" => $av->itemAvaliado,
			"nomeDisciplina" => discoveryInfoTurma($av->itemAvaliado, "nomeDisciplina"),
			"nomeProfessor" => discoveryInfoTurma($av->itemAvaliado, "nomeProfessor"),
			"somas" => $av->soma,
			"qtd_notas" => $av->qtdNotas,
			"avg" => $av->soma/$av->qtdNotas,
			"media" => $av->average);
	}
	//print_r($avaliacoes);

	return $avaliacoes;



}

/**
 * @name questoesNotas
 * @author Fabio Baía
 * @since 06/07/2012 13:25:27
 * insert a description here
 **/
function questoesNotas() {
	// 	use faculdadeunica05;
	// 	select id, questionario_has_questao_questionario_id, nota, processo_avaliacao_id, item_avaliado, avaliador, tipo_avaliacao, subtipo_avaliacao from avaliacao where tipo_avaliacao = 'Aluno' and subtipo_avaliacao = 'Professor/Disciplina'
	// 	and questionario_has_questao_questao_id = 121;

	$results;
	$av = new Avaliacao();
	$av->select("processo_avaliacao_id, questionario_has_questao_questionario_id,
	questionario_has_questao_questao_id, item_avaliado, nota, tipo_avaliacao, subtipo_avaliacao, avaliador");
	
	$av->where("subtipo_avaliacao = 'Professor/Disciplina'");

	$av->order("item_avaliado ASC");
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
		if($it == null || $it != $av->itemAvaliado){
			$it = $av->itemAvaliado;
			
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
		
		if($it == $av->itemAvaliado){
			
			$avaliadores = new Avaliacao();
			$avaliadores->setItemAvaliado($av->itemAvaliado);
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
						"itemAvaliado" => $av->itemAvaliado,
						"nota5" => $nota5,
						"nota4" => $nota4,
						"nota3" => $nota3,
						"nota2" => $nota2,
						"nota1" => $nota1,
						"media" => $media,
						/*"soma" => $soma,
						"qtd" => $qtd_questoes,
						"qtdAV" => $qtd_avaliadores,*/
						"tipo_avaliacao" => $av->tipoAvaliacao,
						"subtipo_avaliacao" => $av->subtipoAvaliacao);
		}
		
	}
	//debug
	//print_r($results);
	
	return  $results;
}

/**
* @name questoesNotas2
* @author Fabio Baía
* @since 09/07/2012 13:33:59
* insert a description here
**/
function questoesNotas2() {
	// 	use faculdadeunica05;
	// 	select id, questionario_has_questao_questionario_id, nota, processo_avaliacao_id, item_avaliado, avaliador, tipo_avaliacao, subtipo_avaliacao from avaliacao where tipo_avaliacao = 'Aluno' and subtipo_avaliacao = 'Professor/Disciplina'
	// 	and questionario_has_questao_questao_id = 121;
	
	
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
	//debug
	//print_r($results);

	return  $results;
}

function disciplinasMelhoresNotasCombo() {

	$avaliacoes;
	$av = new Avaliacao();
	$av->alias("av");
	$av->select("item_avaliado,questionario_has_questao_questao_id, nota, count(nota) as qtdNotas, avg(nota) as average");

	$av->where("subtipo_avaliacao = 'Professor/Disciplina'");
	// 	$av->group("item_avaliado, nota");
	$av->group("questionario_has_questao_questao_id, item_avaliado, nota");

	//usar o order e o limit pra pegar as "melhores" e as "piores" notas
	//$av->order("average DESC");
	//$av->limit(0,3);

	$av->find();
	// 	echo $qtd;
	// 	exit;
	$pos = 0;
	$posAtual = 0;
	$star5 = 0;
	$star4 = 0;
	$star3 = 0;
	$star2 = 0;
	$star1 = 0;
	while ($av->fetch()) {

		if($pos == 0){
			$item_de_vez = $av->itemAvaliado;
			$posAtual = $pos;
		}

		if($item_de_vez == $av->itemAvaliado){
			$somaGeral = ($star5*5 + $star4*4 + $star3*3 + $star2*2 + $star1*1);
			$qtdGeral = ($star5 + $star4 + $star3 + $star2 + $star1);
				
			switch ($av->nota) {
				case 5:
					$star5 = $av->qtdNotas;
					break;
				case 4:
					$star4 = $av->qtdNotas;
					break;
				case 3:
					$star3 = $av->qtdNotas;
					break;
				case 2:
					$star2 = $av->qtdNotas;
					break;
				case 1:
					$star1 = $av->qtdNotas;
					break;
			}
				
			$avaliacoes[$posAtual] = array("itemAvaliado" => $av->itemAvaliado,
													"nomeDisciplina" => discoveryInfoTurma($av->itemAvaliado, "nomeDisciplina"),
													"nomeProfessor" => discoveryInfoTurma($av->itemAvaliado, "nomeProfessor"),
													"nota5" => $star5,
													"nota4" => $star4,
													"nota3" => $star3,
													"nota2" => $star2,
													"nota1" => $star1,
													"media" => $somaGeral/10);
		}else{
			$item_de_vez = $av->itemAvaliado;
				
			$somaGeral = ($star5*5 + $star4*4 + $star3*3 + $star2*2 + $star1*1);
			$qtdGeral = ($star5 + $star4 + $star3 + $star2 + $star1);

			switch ($av->nota) {
				case 5:
					$star5 = $av->qtdNotas;
					break;
				case 4:
					$star4 = $av->qtdNotas;
					break;
				case 3:
					$star3 = $av->qtdNotas;
					break;
				case 2:
					$star2 = $av->qtdNotas;
					break;
				case 1:
					$star1 = $av->qtdNotas;
					break;
			}

			$avaliacoes[$posAtual] = array("itemAvaliado" => $av->itemAvaliado,
																		"nomeDisciplina" => discoveryInfoTurma($av->itemAvaliado, "nomeDisciplina"),
																		"nomeProfessor" => discoveryInfoTurma($av->itemAvaliado, "nomeProfessor"),
																		"nota5" => $star5,
																		"nota4" => $star4,
																		"nota3" => $star3,
																		"nota2" => $star2,
																		"nota1" => $star1,
																		"media" => $somaGeral/5);
				
			$posAtual++;
		}
		
		$pos++;

	}

	return $avaliacoes;

}

function disciplinasPioresNotas() {

	$avaliacoes;
	$av = new Avaliacao();
	$av->alias("av");
	$av->select("item_avaliado, sum(nota) as soma, count(nota) as qtdNotas, avg(nota) as average");

	$av->where("subtipo_avaliacao = 'Professor/Disciplina'");
	$av->group("item_avaliado");

	//usar o order e o limit pra pegar as "melhores" e as "piores" notas
	$av->order("average ASC");
	$av->limit(0,3);

	$av->find();
	// 	echo $qtd;
	// 	exit;
	while ($av->fetch()) {
		$avaliacoes[] = array("itemAvaliado" => $av->itemAvaliado,
			"nomeDisciplina" => discoveryInfoTurma($av->itemAvaliado, "nomeDisciplina"),
			"nomeProfessor" => discoveryInfoTurma($av->itemAvaliado, "nomeProfessor"),
			"somas" => $av->soma,
			"qtd_notas" => $av->qtdNotas,
			"avg" => $av->soma/$av->qtdNotas,
			"media" => $av->average);
	}

	return $avaliacoes;
}

function cursosGeralNotas() {

	$avaliacoes;
	$av = new Avaliacao();
	$av->alias("av");
	//$av->select("item_avaliado, sum(nota) as soma, count(nota) as qtdNotas, avg(nota) as average");
	$av->select("item_avaliado, avg(nota) as average");

	$av->where("subtipo_avaliacao = 'Curso/Coordenador'");
	$av->group("item_avaliado");

	//usar o order e o limit pra pegar as "melhores" e as "piores" notas
	$av->order("average DESC");

	$av->find();
	// 	echo $qtd;
	// 	exit;
	while ($av->fetch()) {
		$avaliacoes[] = array("itemAvaliado" => $av->itemAvaliado,
			"nomeCurso" => discoveryInfoTurma($av->itemAvaliado, "nomeCurso"),
			"nomeCoordenador" => discoveryInfoTurma($av->itemAvaliado, "nomeCoordenador"),
			"media" => $av->average);
	}

	return $avaliacoes;
}

function labsGeralNotas() {

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

	return $avaliacoes;
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


//   	  $arr = questoesNotas();
//   	  echo sizeof($arr);

// 			questoesNotas2();
//   	  exit;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Relatorios</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.load('visualization', '1.1', {packages:['controls']});
      google.setOnLoadCallback(drawChart);

      //cores para os charts
      // #920300 padrao unicampo
      // #00CD00 verde google
      // #CD0000 vermelho google
      
      function drawTeste(){
    	// Create and populate the data table.
    	  var data = google.visualization.arrayToDataTable([
    	                                              	  
    	  <?php
//     	  var data = google.visualization.arrayToDataTable([
//           ['Month', '5 estrelas', '4 estrelas', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
//           ['2004/05',  165,      938,         522,             998,           450,      614.6],
//           ['2005/06',  135,      1120,        599,             1268,          288,      682],
//           ['2006/07',  157,      1167,        587,             807,           397,      623],
//           ['2007/08',  139,      1110,        615,             968,           215,      609.4],
//           ['2008/09',  136,      691,         629,             1026,          366,      569.6]
//         ]);    	  
    	  
    	  $arr1 = disciplinasMelhoresNotasCombo(); 
    	    	  	$i = 0; 
    	    	  	echo "[";
    	    		for($i; $i <count($arr1); $i++){
    	    			$d = utf8_encode($arr1[$i]["nomeDisciplina"]);
    	    			
    	    			 
    	    			//se for a primeira linha...
    	    			if($i == 0){
    	    				echo "'".$d."'";
    	    			}else{
    	    				echo ", '".$d."'";
    	    			}  	
    	    		}
    	    		
    	    		echo "],[";
    	    		$i = 0;
    	    		for($i; $i <count($arr1); $i++){
    	    			$m = escalaDecimal($arr1[$i]["media"]);
    	    		
    	    			//se for a primeira linha...
    	    			if($i == 0){
    	    				echo $m;
    	    			}else{
    	    				echo ", ".$m;
    	    			}
    	    		}
    	    		echo "]";    	    		
    	    		
    	    		?> 
    	    		  
    	    		]); 	    		

    	  // Create and draw the visualization.
    	  //graficos possíveis pra esses dados
    	  //Area - Line - Bar - Column
    	  chart = new google.visualization.ColumnChart(document.getElementById('chart1'));
		  chart.draw(data,
    	           {title:"Disciplinas - Geral",
    	            height:300,
    	            /*colors: ['#920300'],*/
    	            hAxis: {title: "Disciplina"},
    	            vAxis:{title: 'Nota',
        	               maxValue: 10,
        	               minValue: 0
        	               /*gridlines:{count:10}*/
 	               		},
 	               	pointSize: 5,
 	                allowHtml: true
    	           }
    	      );
      }

      function drawDisciplinasMelhoresNotas(){
        	// Create and populate the data table.
        	  var data = new google.visualization.DataTable();
              data.addColumn('string', 'Disciplina');
              data.addColumn('number', 'Media');
              //data.addColumn({type:'string',role:'tooltip'});
        	  <?php
        	  $arr1 = disciplinasPioresNotas();
        	    	  	echo 'data.addRows('.count($arr1).');';
        	    	  	$i = 0; 
        	    		for($i; $i <count($arr1); $i++){
        	    			$d = utf8_encode($arr1[$i]["nomeDisciplina"]);
        	    			$m = escalaDecimal($arr1[$i]["media"]);
        	    			
//         	    			$htmlTooltip = utf8_encode($arr1[$i]["nomeProfessor"]).'\n';
//         	    			$htmlTooltip .= utf8_encode($arr1[$i]["nomeDisciplina"]).'\n';
//         	    			$htmlTooltip .= "Nota: ".escalaDecimal($arr1[$i]["media"]);
        	    			
        	    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
        	    			echo 'data.setValue('.$i.', 1, '.$m.');';   
//         	    			echo 'data.setValue('.$i.', 2, \''.$htmlTooltip.'\');';       	    			
        	    					
        	    		}
        	    		?>    	    		

        	  // Create and draw the visualization.
        	  //graficos possíveis pra esses dados
        	  //Area - Line - Bar - Column
        	  chart = new google.visualization.ColumnChart(document.getElementById('chartDisciplinasMelhoresNotas'));
    		  chart.draw(data,
        	           {title:"Disciplinas - Melhores Notas",
        	            height:300,
        	            colors: ['#920300'],
        	            hAxis: {title: "Disciplina"},
        	            vAxis:{title: 'Nota',
            	               maxValue: 10,
            	               minValue: 0
            	               /*gridlines:{count:10}*/
     	               		},
     	               	pointSize: 5,
     	                allowHtml: true
        	           }
        	      );
          }
   

	  function drawDashBoard(){
		// Create and populate the data table.
       /*var data = google.visualization.arrayToDataTable([
          ['Disciplina', '5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela', 'Média'],
          ['Introdução à Administração',  5,      8,         6,             22,           0,      8.2],
          ['Estatística Aplicada', 2,      7,        8,             10,          3,      6],
          ['Comunicação e Linguagem',  0,      15,       5,             0,           11,     6.2],
          ['Filosofia',  3,      5,       2,             1,           8,     3.8]
       ]);*/

      	
      	var data = new google.visualization.DataTable();
        data.addColumn('string', 'Disciplina');
        data.addColumn('number', '5 estrelas');
        data.addColumn('number', '4 estrelas');
        data.addColumn('number', '3 estrelas');
        data.addColumn('number', '2 estrelas');
        data.addColumn('number', '1 estrela');
        data.addColumn('number', 'Média');
        

        <?php
//   	    $arr1 = disciplinasMelhoresNotasCombo();
            $arr1 = questoesNotas2();
  	    	  	echo 'data.addRows('.sizeof($arr1).');';
  	    	  	$i = 0; 
  	    		for($i; $i <sizeof($arr1); $i++){
//   	    			$d = utf8_encode($arr1[$i]["nomeDisciplina"]);
  	    			$d = utf8_encode($arr1[$i]["itemAvaliado"]);
  	    			$m = escalaDecimal($arr1[$i]["media"]);
  	    			
  	    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
  	    			echo 'data.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
  	    			echo 'data.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
  	    			echo 'data.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
  	    			echo 'data.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
  	    			echo 'data.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
  	    			echo 'data.setValue('.$i.', 6, '.$m.');';    	    			
  	    					
  	    		}
  	    		
  	    		?> 
       
        	   

      	    		
      	    		var barChart = new google.visualization.ChartWrapper({
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

      	    		// Define a slider control for the Age column.
      	    		  var slider = new google.visualization.ControlWrapper({
      	    		    'controlType': 'NumberRangeFilter',
      	    		    'containerId': 'control1',
      	    		    'options': {
      	    		      'filterColumnLabel': 'Média',
      	    		    'ui': {'labelStacking': 'vertical'}
      	    		    }
      	    		  });

      	    		  // Define a category picker control for the Gender column
      	    		  var categoryPicker = new google.visualization.ControlWrapper({
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
      	    		  
      	    		// Define a table
      	    		  var table = new google.visualization.ChartWrapper({
      	    		    'chartType': 'Table',
      	    		    'containerId': 'chart2',
      	    		    'options': {
      	    		      'width': '900px',
      	    		      'allowHtml': true
      	    		    }
      	    		  });

      	    		var formatter = new google.visualization.ColorFormat();
      	    	  formatter.addRange(0, 5, '#CC0000', null);
      	    	  formatter.addRange(5, 10, '#006600', null);
      	    	  formatter.format(data, 6); // Apply formatter to second column
      	    	  
      	    		// Create a dashboard
      	    		  new google.visualization.Dashboard(document.getElementById('dashboard')).
      	    		      // Establish bindings, declaring the both the slider and the category
      	    		      // picker will drive both charts.
      	    		      bind([slider, categoryPicker], [barChart, table]).
      	    		      // Draw the entire dashboard.
      	    		      draw(data);
      	    		
			
      }

      
      function drawDisciplinasMelhoresNotasCombo(){
      	// Create and populate the data table.
      	  var data = new google.visualization.DataTable();
            data.addColumn('string', 'Disciplina');
            data.addColumn('number', '5 estrelas');
            data.addColumn('number', '4 estrelas');
            data.addColumn('number', '3 estrelas');
            data.addColumn('number', '2 estrelas');
            data.addColumn('number', '1 estrela');
            data.addColumn('number', 'Media');
            //data.addColumn({type:'string',role:'tooltip'});
      	  <?php
      	  $arr1 = disciplinasMelhoresNotasCombo();
      	     	  	//echo 'data.addRows('.count($arr1).');';
      	    	  	echo 'data.addRows(6);';
      	    	  	$i = 0; 
      	    		for($i; $i <6; $i++){
      	    			$d = utf8_encode($arr1[$i]["nomeDisciplina"]);
      	    			$m = escalaDecimal($arr1[$i]["media"]);
      	    			
      	    			$nota = $arr1[$i]["nota"];
      	    			
//       	    			$htmlTooltip = utf8_encode($arr1[$i]["nomeProfessor"]).'\n';
//       	    			$htmlTooltip .= utf8_encode($arr1[$i]["nomeDisciplina"]).'\n';
//       	    			$htmlTooltip .= "Nota: ".escalaDecimal($arr1[$i]["media"]);
      	    			
      	    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
      	    			echo 'data.setValue('.$i.', 1, '.$arr1[$i]["nota5"].');';
      	    			echo 'data.setValue('.$i.', 2, '.$arr1[$i]["nota4"].');';
      	    			echo 'data.setValue('.$i.', 3, '.$arr1[$i]["nota3"].');';
      	    			echo 'data.setValue('.$i.', 4, '.$arr1[$i]["nota2"].');';
      	    			echo 'data.setValue('.$i.', 5, '.$arr1[$i]["nota1"].');';
      	    			echo 'data.setValue('.$i.', 6, '.$m.');';   
//       	    			echo 'data.setValue('.$i.', 2, \''.$htmlTooltip.'\');';       	    			
      	    					
      	    		}
      	    		?>    	    		

      	  // Create and draw the visualization.
      	  //graficos possíveis pra esses dados
      	  //Area - Line - Bar - Column
      	  chart = new google.visualization.ColumnChart(document.getElementById('chartDisciplinasMelhoresNotasCombo'));
  		  chart.draw(data,
      	           {title:"Disciplinas - Melhores Notas - Combo",
      	            height:300,
      	            /*colors: ['#920300'],*/
      	            /*colors: ['#8B1A1A','#8B2323','#CD3333','#EE3B3B','#FF4040','#228B22'],*/
      	            hAxis: {title: "Disciplina"},
      	            vAxis:{title: 'Nota',
          	               maxValue: 10,
          	               minValue: 0
          	               /*gridlines:{count:10}*/
   	               		},
   	               	seriesType: "bars",
   	          		series: {5: {type: "line"}},
   	               	pointSize: 5,
   	                allowHtml: true  
      	           }	             	
      	           
      	      );
        }

      
      function drawDisciplinasPioresNotas(){
      	// Create and populate the data table.
      	  var data = new google.visualization.DataTable();
            data.addColumn('string', 'Disciplina');
            data.addColumn('number', 'Media');
            //data.addColumn({type:'string',role:'tooltip'});
      	  <?php
      	  $arr1 = disciplinasPioresNotas();
      	    	  	echo 'data.addRows('.count($arr1).');';
      	    	  	$i = 0; 
      	    		for($i; $i <count($arr1); $i++){
      	    			$d = utf8_encode($arr1[$i]["nomeDisciplina"]);
      	    			$m = escalaDecimal($arr1[$i]["media"]);
      	    			
//       	    			$htmlTooltip = utf8_encode($arr1[$i]["nomeProfessor"]).'\n';
//       	    			$htmlTooltip .= utf8_encode($arr1[$i]["nomeDisciplina"]).'\n';
//       	    			$htmlTooltip .= "Nota: ".escalaDecimal($arr1[$i]["media"]);
      	    			
      	    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
      	    			echo 'data.setValue('.$i.', 1, '.$m.');';   
//       	    			echo 'data.setValue('.$i.', 2, \''.$htmlTooltip.'\');';       	    			
      	    					
      	    		}
      	    		?>    	    		

      	  // Create and draw the visualization.
      	  //graficos possíveis pra esses dados
      	  //Area - Line - Bar - Column
      	  chart = new google.visualization.ColumnChart(document.getElementById('chartDisciplinasPioresNotas'));
  		  chart.draw(data,
      	           {title:"Disciplinas - Piores Notas",
      	            height:300,
      	            colors: ['#920300'],
      	            hAxis: {title: "Disciplina"},
      	            vAxis:{title: 'Nota',
      	            	   maxValue: 10,
          	               minValue: 0
          	               /*gridlines:{count:10}*/
   	               		},
   	               	pointSize: 5,
   	                allowHtml: true
      	           }
      	      );
        }

      function drawCursosGeralNotas(){
        	// Create and populate the data table.
        	  var data = new google.visualization.DataTable();
              data.addColumn('string', 'Curso');
              data.addColumn('number', 'Media');
              //data.addColumn({type:'string',role:'tooltip'});
        	  <?php
        	  $arr1 = cursosGeralNotas();
        	    	  	echo 'data.addRows('.count($arr1).');';
        	    	  	$i = 0; 
        	    		for($i; $i <count($arr1); $i++){
        	    			$d = utf8_encode($arr1[$i]["nomeCurso"]);
        	    			$m = escalaDecimal($arr1[$i]["media"]);
        	    			
//         	    			$htmlTooltip = utf8_encode($arr1[$i]["nomeProfessor"]).'\n';
//         	    			$htmlTooltip .= utf8_encode($arr1[$i]["nomeDisciplina"]).'\n';
//         	    			$htmlTooltip .= "Nota: ".escalaDecimal($arr1[$i]["media"]);
        	    			
        	    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
        	    			echo 'data.setValue('.$i.', 1, '.$m.');';   
//         	    			echo 'data.setValue('.$i.', 2, \''.$htmlTooltip.'\');';       	    			
        	    					
        	    		}
        	    		?>    	    		

        	  // Create and draw the visualization.
        	  //graficos possíveis pra esses dados
        	  //Area - Line - Bar - Column
        	  chart = new google.visualization.ColumnChart(document.getElementById('chartCursosGeralNotas'));
    		  chart.draw(data,
        	           {title:"Cursos - Geral",
        	            height:300,
        	            colors: ['#920300'],
        	            hAxis: {title: "Curso"},
        	            vAxis:{title: 'Nota',
        	            	   maxValue: 10,
            	               minValue: 0
            	               /*gridlines:{count:10}*/
     	               		},
     	               	pointSize: 5,
     	                allowHtml: true
        	           }
        	      );
          }

      function drawLabsGeralNotas(){
      	// Create and populate the data table.
      	  var data = new google.visualization.DataTable();
            data.addColumn('string', 'Laboratório');
            data.addColumn('number', 'Media');
            //data.addColumn({type:'string',role:'tooltip'});
      	  <?php
      	  $arr1 = labsGeralNotas();
      	    	  	echo 'data.addRows('.count($arr1).');';
      	    	  	$i = 0; 
      	    		for($i; $i <count($arr1); $i++){
      	    			$d = utf8_encode($arr1[$i]["nomeLaboratorio"]);
      	    			$m = escalaDecimal($arr1[$i]["media"]);
      	    			
//       	    			$htmlTooltip = utf8_encode($arr1[$i]["nomeProfessor"]).'\n';
//       	    			$htmlTooltip .= utf8_encode($arr1[$i]["nomeDisciplina"]).'\n';
//       	    			$htmlTooltip .= "Nota: ".escalaDecimal($arr1[$i]["media"]);
      	    			
      	    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
      	    			echo 'data.setValue('.$i.', 1, '.$m.');';   
//       	    			echo 'data.setValue('.$i.', 2, \''.$htmlTooltip.'\');';       	    			
      	    					
      	    		}
      	    		?>    	    		

      	  // Create and draw the visualization.
      	  //graficos possíveis pra esses dados
      	  //Area - Line - Bar - Column
      	  chart = new google.visualization.ColumnChart(document.getElementById('chartLabsGeralNotas'));
  		  chart.draw(data,
      	           {title:"Laboratorios - Geral",
      	            height:300,
      	            colors: ['#920300'],
      	            hAxis: {title: "Laboratório"},
      	            vAxis:{title: 'Nota',
      	            	   maxValue: 10,
          	               minValue: 0
          	               /*gridlines:{count:10}*/
   	               		},
   	               	pointSize: 5,
   	                allowHtml: true
      	           }
      	      );
        }
      
      function drawChart() {

    	  var data = new google.visualization.DataTable();
          data.addColumn('string', 'Dia');
          data.addColumn('number', 'Acessos');
    	  <?php
    	  	echo 'data.addRows('.count($array_relatorio_log).');';
    	  	$i = 0; 
    		for($i; $i <count($array_relatorio_log); $i++){
    			$d = date_to_ptbr($array_relatorio_log[$i]["dia"]);
    			$t = $array_relatorio_log[$i]["qtd"];
    			
    			echo 'data.setValue('.$i.', 0, \''.$d.'\');';
    			echo 'data.setValue('.$i.', 1, '.$t.');';       	
    					
    		}
    		?>
        

        var options = {
          title: 'Quantidade de Acessos',
          hAxis:{title:'Dias'},
          vAxis:{title:'Acessos'},
          height: 300,
          pointSize: 5
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
        //drawProfessor();
        //drawDisciplinasMelhoresNotas();
        
        //drawDisciplinasMelhoresNotasCombo();
        //drawDisciplinasPioresNotas();
        //drawCursosGeralNotas();
        //drawLabsGeralNotas();
        
        drawDashBoard();
      }
      
      
    </script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.js"></script>

</head>

<body style="background: #fafafa;">









<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>
	
	
	
	
	
	
	
		
	
	
	
<?php } ?>

<div id="wrapper" class="container">

	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>       
    
    <div class="white">
		<br />
        

        <h3>Relat&oacute;rios</h3>
        
<!--         <div id="questionarios"> -->

        		<div id="chart_div" style="width: 900px; height: 300px;"></div>
<!--       		<div id="chart1" style="width: 900px; height: 300px;"></div>
        		<div id="chartDisciplinasMelhoresNotas" style="width: 900px; height: 300px;"></div>
        		<div id="chartDisciplinasMelhoresNotasCombo" style="width: 900px; height: 300px;"></div>
        		<div id="chartDisciplinasPioresNotas" style="width: 900px; height: 300px;"></div>
        		<div id="chartCursosGeralNotas" style="width: 900px; height: 300px;"></div>
        		<div id="chartLabsGeralNotas" style="width: 900px; height: 300px;"></div> -->
        		<div id="dashboard">
      
		            <div id="control1"></div>
		            <div id="control2"></div>
		            <div id="control3"></div>
		          	<br />
		          	<br />         
		            <div id="chart1"></div>
		            <div id="chart2"></div>
		            <div id="chart3"></div>
          
    		    </div>
        	
        
<!--         </div> -->
        </div><!-- fecha div white -->
        
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
</html>
