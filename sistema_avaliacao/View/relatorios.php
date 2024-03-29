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
// $lista = new Log();
// $lista->alias("l");

// $prof = new Professor();
// $u = new Usuario();
// $a = new Aluno();

// $lista->join($prof, 'LEFT', 'prof', "usuario", "id");
// $lista->join($u, 'LEFT', 'u', "usuario", "id");
// $lista->join($a, 'LEFT', 'a', "usuario", "ra");

// $lista->select("l.id, l.usuario, l.hora, l.ip, l.tipoUsuario,
//                 	prof.nome as prof_nome, prof.id as prof_id,
//                 	u.id as u_id, u.nome as u_nome,
//                 	a.ra as a_id, a.nome as a_nome, count(*) as total, DATE(hora) as dia");
// $lista->where("l.usuario != '1'");
// $lista->order("dia ASC");
// $lista->groupBy("dia");
// $lista->find();

// $array_relatorio_log = array();
// $it = 0;
// while( $lista->fetch()) {
// 	$array_relatorio_log[$it]["dia"] = $lista->dia;
// 	$array_relatorio_log[$it]["qtd"] = $lista->total;
// 	$it++;
// }

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
/*function questoesNotas() {
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
/*"soma" => $soma,//
"qtd" => $qtd_questoes,//
"qtdAV" => $qtd_avaliadores,//
"tipo_avaliacao" => $av->tipoAvaliacao,
"subtipo_avaliacao" => $av->subtipoAvaliacao);
}

}
//debug
//print_r($results);

return  $results;
}*/

/**
 * @name questoesNotas2
 * @author Fabio Baía
 * @since 09/07/2012 13:33:59
 * insert a description here
 **/
/*function questoesNotas2() {
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
 /*"soma" => $soma,//
 "qtd" => $qtd_questoes,//
 "qtdAV" => $qtd_avaliadores,//
 "tipo_avaliacao" => $av->tipoAvaliacao,
 "subtipo_avaliacao" => $av->subtipoAvaliacao);
 }

 }
 //debug
 //print_r($results);

 return  $results;
 }
 */

/*function disciplinasMelhoresNotasCombo() {

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
*/

/*
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
 */

/*
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

 */

/*
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
 */




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Relatorios</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style>
					        	ul.vertical {
	margin: 0;
	padding: 0;
	list-style: none;
	width: 240px; /* Width of Menu Items */
	border-bottom: 1px solid #ccc;
	}
	
ul.vertical li {
	position: relative;
	}
	
ul.vertical li ul {
	position: absolute;
	left: 149px; /* Set 1px less than menu width */
	top: 0;
	display: none;
	list-style: none;
	width: 200px;
	}

/* Styles for Menu Items */
ul.vertical li a {
	display: block;
	text-decoration: none;
	color: #777;
	background: #fff; /* IE6 Bug */
	padding: 5px;
	border: 1px solid #ccc; /* IE6 Bug */
	border-bottom: 0;
	}
	
ul.vertical li a:hover {
	text-decoration: none;
	color: #333;
	background: #f3f3f3;
	}
	
/* Holly Hack. IE Requirement \*/
* html ul.vertical li { float: left; height: 1%; }
* html ul.vertical li a { height: 1%; }
/* End */

ul.vertical li:hover ul, ul.vertical li.over ul { display: block; } /* The magic */

#rel_menus{
	background: #FAFAFA;
	padding: 10px;
	height: 157px;
	margin: 10px;
}

.box_op{
	border: 1px solid #f3f3f3;
	background: #fff;
	padding: 10px;
	width: auto;
	float: left;
	height: 135px;
	overflow-y: scroll;
}

.box_op h4{
	margin: 0;
	padding: 5px;
	text-align: center;
	color: #777;
}

.box_op a{
	text-decoration: none;
	padding: 4px;
	display: block;
}

.box_op a.chart{
	text-decoration: none;
	background: url(css/images/chart.png) no-repeat;
	padding-left: 25px;
}

.goog-menuitem-content {
	text-align: left;
}
/* efeito hover nos filtros*/
/*passe o mouse*/
/*#mouse {
background: #fff;
text-align: center;
width: auto;
padding: 10px;
color: red;
}
#rel_menus {
width: 100%;
position: absolute;
background: #FAFAFA;
padding: 10px;
margin: 10px;
}
#rel_menus:hover {
height: auto;
z-index: 500;
}
.box_op {
border: 1px solid #f3f3f3;
background: #fff;
padding: 10px;
float: left;
}

.box_op:hover {
height: auto;
z-index: 500;
position: relative;
}
*/

/*filtro modelo 2*/
#rel_menus {
width: 92%;
/*height: auto;*/
height: 65px;
background: #FAFAFA;
padding: 10px;
margin: 10px;
position: absolute;
}
.box_op {
border: 1px solid #f3f3f3;
background: #fff;
padding: 10px;
width: auto;
float: left;
height: 20px;
overflow-y: hidden;
}
.box_op:hover {
height: auto;
z-index: 500;
position: relative;
box-shadow: 1px 1px 2px #888888;
}

.box_op h4.ident1{
	text-align: left;
	padding-left: 20px;
	border-top: 1px solid #ddd;
}

.box_op h4.ident2{
	text-align: left;
	padding-left: 40px;
}
					        	
					        	</style>
<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.load('visualization', '1.1', {packages:['controls']});
      google.setOnLoadCallback(drawChart);

      var qtd = 0;
      function drawChart(){
          //exibe div carregando
          //$('#dashboard').hide();
    	for(var i = 0; i < qtd; i++){
    		var funcao = "drawChart"+i;
    		window[funcao]();// isso executa a fun��o
    	}

    	escondeBlackout();   	
    	
      }

      function escondeBlackout(){
    	//esconde div carregando
  		$('#blackout').hide();
      }

      function drawConteiners(param){
          $('#dashboard').append('<div id="dashboard_'+param+'" class="dash"><div id="dashinfo_'+param+'" class="dashinfo"></div><div id="control1_'+param+'" class="control"></div><div id="control2_'+param+'" class="control"></div><br /><br /><div id="chart1_'+param+'" class="chart1"></div><div id="chart2_'+param+'" class="chart2"></div><div id="comments_'+param+'" class="comments"></div></div>');
      }

      function drawInfo(param, info){
          var div = "#dashinfo_"+param;
          //alert(param);
          $(div).html(info);                    
      }

      function drawComments(param, comments){
          var div = "#comments_"+param;
          //alert(param);
          if(comments == ""){
        	  //$(div).hide();
        	  $(div).html("<h3>N&atilde;o h&aacute; Coment&aacute;rios</h3>");
          }else{
        	  $(div).html("<h3>Coment&aacute;rios</h3>"+comments);
          }
                              
      }

      function imprimirDashboards(tipo){
    	
    	  $("div.dash").each(function(){
						
			//impressao completa tabelas+graficos
			if(tipo == "full"){
				$('head').append('<style media="print">div.dash{page-break-after: always;}div.control{display: none;}div.chart1{display: block;}div.chart2{display: block;}div.comments{display: block;}</style>');
			}
			//impressao de tabelas
			if(tipo == "table"){
				$('head').append('<style media="print">div.dash{page-break-after: always;}div.control{display: none;}div.chart1{display: none;}div.chart2{display: block;}div.comments{display: none;}</style>');
			}
			//impressao de graficos
			if(tipo == "chart"){
				$('head').append('<style media="print">div.dash{page-break-after: always;}div.control{display: none;}div.chart1{display: block;}div.chart2{display: none;}div.comments{display: none;}</style>');
			}
			//impressao de comentarios
			if(tipo == "comment"){
				$('head').append('<style media="print">div.dash{page-break-after: always;}div.control{display: none;}div.chart1{display: none;}div.chart2{display: none;}div.comments{display: block;}</style>');
			}
			
    	  });

	      window.print();
      }
      

      //cores para os charts
      // #920300 padrao unicampo
      // #00CD00 verde google
      // #CD0000 vermelho google
      
      <?php 
      if(isset($_SESSION["s_active_chart"]) && $_SESSION["s_active_chart"] != ""){
      	
      	$chart = $_SESSION["s_active_chart"];
      	echo $chart;
      	
      }
      ?>
      
       
      
    </script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.js"></script>
<script type="text/javascript">
/*function botaoGerarGrafico(op){
	if(op == "mostrar"){
		$("button#gerar_grafico").show();
		alert('mostrando');
	}else{
		$("button#gerar_grafico").css('display','none');
		alert('escondendo');
	}
	
}

botaoGerarGrafico("esconder");
*/
    
var box; //guarda a box q vai ser atualizada

var req_2;
function loadXMLDoc_2(url){
 	req_2 = null;

	if (window.XMLHttpRequest) {
 		req_2 = new XMLHttpRequest();
		req_2.onreadystatechange = processReqChange_2; 
                		
		req_2.open("GET", url, true);
 		req_2.send(null);

	} else if (window.ActiveXObject) {
		try {
				req_2 = new ActiveXObject("Msxml2.XMLHTTP.4.0");
			} catch(e) {
		try {
				req_2 = new ActiveXObject("Msxml2.XMLHTTP.3.0");
			} catch(e) {
		try {
				req_2 = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
		try {
				req_2 = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
		req_2 = false;
					}
					}
					}
					}

	if (req_2) {
 		
		req_2.onreadystatechange = processReqChange_2();
		req_2.open("GET", url, true);
 		req_2.send();
	}
	}
}

function drawOnBox(){
	var boxToDraw = "box_opt"+box;

	for(var i = 1; i <= 5; i++){
		
		if(i>box){
			var boxToDel = "box_opt"+i;
			document.getElementById(boxToDel).innerHTML = "";
		}
	}
	document.getElementById(boxToDraw).innerHTML = req_2.responseText;
	
}

function processReqChange_2(){
	
	if (req_2.readyState == 4) {
		if (req_2.status == 200) {
			drawOnBox();
			//document.getElementById("box_opt").innerHTML = req_2.responseText;
		} else {
			alert("Houve um problema ao obter os dados:\n" + req_2.statusText);
		}
	}

	if (req_2.readyState == 2) {
		if (req_2.status == 200) {
			var boxToDraw = "box_opt"+box;
			document.getElementById(boxToDraw).innerHTML = "Carregando...";
		} else {
			alert("Houve um problema ao obter os dados:\n" + req_2.statusText);
		}
	}
}


function loadOptions(filtro, param, obj){
	//alert(param);
	//loadXMLDoc_2("../Controller/relatorioController.php?action=load&avaliador="+param);
	loadXMLDoc_2("../Controller/relatorioController.php?action=load&filtro="+filtro+"&param="+param);
	box = filtro+1;
	//marcaOpcao(filtro, param);
	marcaOpcaoCheckbox(obj);
	
	//preencheHiddenFields(filtro, param);
	//verifica se tem grafico e chama a funcao abaixo
}

function loadOptionsMultiple(filtro, param, obj){
	//pega todos os checkboxes marcados

	var param_cursos = [];
	var param_semestres = [];
	
		$("input[name='curso[]']").each(function( index ) {
			  if($(this).is(':checked')){
				//alert("checado "+$(this).val());
				param_cursos.push($(this).val());
			  }else{
				//alert("n�o checado");
			  }
			});
	
		$("input[name='semestre[]']").each(function( index ) {
			  if($(this).is(':checked')){
				//alert("checado "+$(this).val());
				param_semestres.push($(this).val());
			  }else{
				//alert("n�o checado");
			  }
			});
	
	//alert(param_cursos);
	loadXMLDoc_2("../Controller/relatorioController.php?action=load&filtro="+filtro+"&param_cursos="+param_cursos+"&param_semestres="+param_semestres);
	
	
	box = filtro+1;
	//marcaOpcao(filtro, param);
	marcaOpcaoCheckbox(obj);

}


//sem marcar opcao(usando radio button)
function loadOptions2(filtro, param){
	loadXMLDoc_2("../Controller/relatorioController.php?action=load&filtro="+filtro+"&param="+param);
	//box = filtro+1;
	//marcaOpcao(filtro, param);
}



function marcaOpcao(filtro, param){

	var op = "op"+filtro;
	var n = param.replace(" ", "_");
	var id = op+"_"+n;

	//primeiro limpa as demais opcoes
	//$(".box_op > a").css("color", "#777");
	
	var x = "#box_opt"+filtro+" > .box_op > a";
	$(x).css("color", "#06C");
	$(x).css("background", "#fff");

	//$(id).css("background", "#fafafa");
	document.getElementById(id).style.color = "#fff";
	document.getElementById(id).style.background = "#333";

	//alert("testaaaaaaaaaaando>>> "+id);
}

function checkAll(box){
	//var name = $(obj).attr("name");
	//alert(box);
	
	if($("#"+box+" .checkAll").is(':checked')){
		$("#"+box+" input").attr("checked",true);
	}else{
		$("#"+box+" input").attr("checked",false);
	}
	
	
}

function marcaOpcaoCheckbox(obj){

	//var op = "op"+filtro;
	//var n = param.replace(" ", "_");
	//var id = op+"_"+n;
	
	var type = $(obj).attr("type");

	var x = $(obj).parent().get(0);
	
	if(type == "radio"){
		//pega o nome do elemento
		var name = $(obj).attr("name");
		//alert(name);
		if($(obj).is(':checked')){
			//alert("marcado");
			
			//se for radio button deve desmarcar os outros antes
			$("input[name="+name+"]").attr("checked",false);
			$("input[name="+name+"]").parent().css("color", "#06C");
			$("input[name="+name+"]").parent().css("background", "#fff");

			//marca o atual
			$(obj).attr('checked', true);		
			//$(x).css("color", "#fff");
			//$(x).css("background", "#333");
		}else{
			//alert("desmarcado");
			//$(x).css("color", "#06C");
			//$(x).css("background", "#fff");
		}
	}
	if(type == "checkbox"){
		if($(obj).is(':checked')){
			//alert("marcado");
			
			//$(x).css("color", "#fff");
			//$(x).css("background", "#333");
		}else{
			//alert("desmarcado");
			//$(x).css("color", "#06C");
			//$(x).css("background", "#fff");
		}
	}
	
	
	
	
}

//fazer funcao pra carregar chart com ajax aqui


</script>
</head>

<body style="background: #fafafa;">
	
	<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>


	<?php } ?>

	<div id="wrapper" class="container">
		<?php include_once 'inc/header_inc.php';?>
		<div id="content">
			<?php include_once 'inc/menu_admin_inc.php';?>

			<div class="white">
				<div class="remove_at_print">
				
				  <?php 
				  //mostra o botao de imprimir so qdo tem graficos
			      if(isset($_SESSION["s_active_chart"]) && $_SESSION["s_active_chart"] != ""){
			      ?>	
			      	<br />
					<button class="botaoGoogleGrey" onclick="imprimirDashboards('full');">Imprimir</button>
					<button class="botaoGoogleGrey" onclick="imprimirDashboards('chart');">Imprimir Gr&aacute;ficos</button>
					<button class="botaoGoogleGrey" onclick="imprimirDashboards('table');">Imprimir Tabelas</button>
					<button class="botaoGoogleGrey" onclick="imprimirDashboards('comment');">Imprimir Coment&aacute;rios</button>
					<br />
			      <?php	
			      }
			      ?>
	
					<h3>Relat&oacute;rios</h3>
				</div>
				
			<div id="rel_menus">
			<div style="font-weight: bold;font-size: 16px;color: #777;">Filtros</div>
			<?php
				$ip = "189.26.80.175";
				//echo $_SERVER['REMOTE_ADDR'];
				if($usuario_logado->getId() == 1 && $_SERVER['REMOTE_ADDR'] == $ip){

			?>
				
				
				
				
			<?php
				}else{
			?>
				<form name="loadFilters" action="../Controller/relatorioController.php" method="post">
				<div id="box_opt1">
										
					<div class="box_op">
						<h4>Avaliador:</h4>
						<br />
					
						<!--
						<a href="#" id="op1_Aluno" onclick="loadOptions(1, 'Aluno');">Aluno</a>						
						<a href="#" id="op1_Professor" onclick="loadOptions(1, 'Professor');">Professor</a>
						<a href="#" id="op1_Coordenador" onclick="loadOptions(1, 'Coordenador');">Coordenador</a>
						<a href="#" id="op1_Funcionário" onclick="loadOptions(1, 'Funcionário');">Funcionário</a>
						-->
						
						<label><input type="radio" value="" name="avaliador" id="op1_Aluno" onclick="loadOptions(1, 'Aluno', this);" />Aluno</label>
						<label><input type="radio" value="" name="avaliador" id="op1_Professor" onclick="loadOptions(1, 'Professor', this);" />Professor</label>
						<label><input type="radio" value="" name="avaliador" id="op1_Coordenador" onclick="loadOptions(1, 'Coordenador', this);" />Coordenador</label>
						<label><input type="radio" value="" name="avaliador" id="op1_Funcionário" onclick="loadOptions(1, 'Funcionário', this);" />Funcionário</label>
					</div>			
					
					
				</div>
				
				<div id="box_opt2">
					
				</div>
				<div id="box_opt3">
					
				</div>
				<div id="box_opt4">
					
				</div>
				<div id="box_opt5">
					
				</div>
				
				<button type="submit" class="botaoGoogleBlue" value="gerar_grafico" id="gerar_grafico">Gerar Gr&aacute;fico</button>
				</form>
				<?php
				}
				?>
				
				</div><!-- rel menus -->
				<br />
				
				<br /><br />
				<div class="remove_at_print">
					<br style="clear: both" />
					
					<br />
					<br />
					<br />
					<a href="../Controller/relatorioController.php?relatorio_id=12&tipo=Aluno">Participa&ccedil;&atilde;o dos Alunos</a><br />
					
					<a href="../Controller/relatorioController.php?relatorio_id=13&tipo=Professor">Participa&ccedil;&atilde;o dos Professores</a><br />
					
					<a href="../Controller/relatorioController.php?relatorio_id=13&tipo=Coordenador">Participa&ccedil;&atilde;o dos Coordenadores</a><br />
					
					<a href="../Controller/relatorioController.php?relatorio_id=13&tipo=Funcionário">Participa&ccedil;&atilde;o dos Funcion&aacute;rios</a>
					
					<div id="chart_div"></div>
					
				</div>
				
				
				<div id="dashboard">
					
					</div>

					</div><!-- fecha div white -->

					</div>
    <?php include_once 'inc/footer_inc.php';?>
			
			</div>
<script type="text/javascript">
      //google.load("visualization", "1", {packages:["corechart"]});
      //google.load('visualization', '1.1', {packages:['controls']});
      //google.setOnLoadCallback(drawChart);

      //cores para os charts
      // #920300 padrao unicampo
      // #00CD00 verde google
      // #CD0000 vermelho google
      
      <?php 
      //if(isset($_SESSION["s_active_chart"]) && $_SESSION["s_active_chart"] != ""){
      	
      	//$chart = $_SESSION["s_active_chart"];
      	//echo $chart;
      	
      //}
      ?>
      
</script>
</body>
</html>
