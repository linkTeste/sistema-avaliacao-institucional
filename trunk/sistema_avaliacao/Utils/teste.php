<?php
//conexao ao banco academico pra atualizar os dados
$hostAcademico="mysql01-farm26.kinghost.net";
$userAcademico="faculdadeunica";
$passAcademico="unicampobd";
$DBAcademico="faculdadeunica";

$conexaoAcademico = mysql_connect($hostAcademico,$userAcademico,$passAcademico, true) or die (mysql_error("impossivel se conectar no sistema academico"));
$bancoAcademico = mysql_select_db($DBAcademico, $conexaoAcademico);

$host="mysql01-farm26.kinghost.net";
$user="faculdadeunica05";
$pass="avaliacaounicampo159";
$DB="faculdadeunica05";

$conexao = mysql_connect($host,$user,$pass, true) or die (mysql_error("impossivel se conectar no sistema de avaliacao"));
$banco = mysql_select_db($DB, $conexao);

/////////////////////////////////////////////


require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Instrumento.php';
require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/Turma.php';

//as duas linhas abaixo não funcionam. pesquisar o motivo(se der tempo :))
//Lumine::import('system/application/models/dao/Usuario.php');
// Lumine_Util::import('system/application/models/dao/Usuario.php');

//testando inserção de usuario

// $usuario = new Usuario();

// $usuario->nome = "Fabio Baía";
// $usuario->login = "fabio";
// $usuario->senha = "baia";
// $usuario->save();

//inserindo instrumentos

// $instrumento = new Instrumento();
// $instrumento->setNome("Instrumento 1");
// $instrumento->setDescricao("Instrumento que mede a avaliacao do professor realizada pelo aluno");
// $instrumento->save();


//testando insercao de questionario

// $questionario = new Questionario();
// $questionario->setDescricao("Questionario de Psicologia");
// $questionario->setInstrumentoId(1);
// $questionario->setDataCreate(date("Y-m-d"));
// $questionario->save();


//insercao de questoes

// $questao = new Questao();
// $questao->setTexto("What is your name");
// $questao->setTopico("Ingles");

// //pega o questionario com id 1
// $questionario = new Questionario();
// $questionario->get("id", 1);
// //pega o array de questionarios insere a questao e devolve o array
// $array_quest = $questao->getQuestionarios();
// array_push($array_quest, $questionario);

// $questao->setQuestionarios($array_quest);
// $questao->save();


//insere processo avaliacao

// $processo = new ProcessoAvaliacao();
// $processo->setDataCriacao(date("Y-m-d"));
// $processo->setInicio("2012/06/30");
// $processo->setFim("2012/07/30");
// $processo->save();


// insere usuarios
// $usuario = new Usuario();
// $usuario->setNome("Fabio Cezar Baía");
// $usuario->setLogin("fabio");
// $usuario->setSenha("baia");
// $usuario->setEmail("baiacfabio@gmail.com");
// $usuario->save();

//echo "id".$usuario->getId();
//cria um professor
// $professor = new Professor();
// $professor->setId(457);
// $professor->setNome("Fulano de Tal");
// $professor->setLogin("fulano");
// $professor->setSenha("fulano");
// $professor->setEmail("fulano@gmail.com");
// $professor->setIscoordenador(true);

// //usar o insert pq o id não é auto-incremento
// $professor->insert();



//insere um aluno
// $aluno = new Aluno();
// $aluno->setId(152);
// $aluno->setNome("Michelle");
// $aluno->setLogin("michelle");
// $aluno->setSenha("michelle");
// $aluno->setEmail("michelle@gmail.com");
// $aluno->setRa("0001.10.06");
// $aluno->setSitAcademica(1);
// $aluno->setCurso("Psicologia");
// $aluno->insert();


//insere um registro de avaliacao
// $avaliacao = new Avaliacao();
// $avaliacao->setProcessoAvaliacaoId(1);
// $avaliacao->setAlunoId(152);
// $avaliacao->setQuestionarioHasQuestaoQuestionarioId(1);
// $avaliacao->setQuestionarioHasQuestaoQuestaoId(9);
// $avaliacao->setNota(5);
// $avaliacao->setDataAvaliacao(date("Y-m-d"));
// //usar insert pq essa tabela não tem id
// $avaliacao->save();


//define se é pra mostrar as mensagens de debug ou não
$debug = true;
importaTudo();
// importaAlunos();
// importaProfessores();
// importaTurmas();
// importaTurmas2();



//importação dos dados
function importaTudo(){
	importaAlunos();
	importaProfessores();
	importaTurmas();
	importaTurmas2();
}

//importa alunos
function importaAlunos(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;

	$sql = "select * from ca_cadastro";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$ra = $dados["ra"];
		$nome = $dados["nome"];
		$curso = $dados["curso"];
		$login = $dados["usuario"];
		$senha = $dados["senha"];
		$email = $dados["email"];
		$sit_acad = $dados["sit_acad"];

		$aluno = new Aluno();
		$aluno->setRa($ra);
		$aluno->setNome($nome);
		$aluno->setCurso($curso);
		$aluno->setLogin($login);
		$aluno->setSenha($senha);
		$aluno->setEmail($email);
		$aluno->setSitAcademica($sit_acad);


		$select = "select ra from aluno where ra = '".$ra."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			//ja existe um cadastrado
			//atualiza
			//echo "update";
			$aluno->update();
			if($debug){
				echo "UPDATE >> ".$aluno->getNome();
				echo "<br />";
			}
		}
		else {
			//echo "insere";
			//ainda nao foi cadastrado
			//insere
			$aluno->insert();
			if($debug){
				echo "	INSERT >> ".$aluno->getNome();
				echo "<br />";
			}
		}

	}
}

//importa professores
function importaProfessores(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;

	$sql = "select * from ca_turmas";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$nome = $dados["professor"];
		$id_professor = $dados["id_professor"];
		$id_coordenador = $dados["id_coordenador"];

		$professor = new Professor();
		$professor->setId($id_professor);
		$professor->setNome($nome);
		if($id_professor == $id_coordenador){
			$professor->setIscoordenador(true);
		}else{
			$professor->setIscoordenador(false);
		}



		$select = "select id from professor where id = '".$id_professor."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			//ja existe um cadastrado
			//atualiza
			//echo "update";
			$professor->update();
			if($debug){
				echo "UPDATE >> ".$professor->getNome();
				echo "<br />";
			}
		}
		else {
			//echo "insere";
			//ainda nao foi cadastrado
			//insere
			$professor->insert();
			if($debug){
				echo "	INSERT >> ".$professor->getNome();
				echo "<br />";
			}
		}

	}

	$sql = "select * from ca_professor";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$nome = $dados["professor"];
		$id_professor = $dados["id_professor"];
		$login = $dados["login"];
		$senha = $dados["senha"];

		$professor = new Professor();
		$professor->setId($id_professor);
		$professor->setNome($nome);
		$professor->setLogin($login);
		$professor->setSenha($senha);


		$select = "select id from professor where id = '".$id_professor."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			//ja existe um cadastrado
			//atualiza
			//echo "update";
			$professor->update();
			if($debug){
				echo "UPDATE >> ".$professor->getNome();
				echo "<br />";
			}
		}
		else {
			//não vai inserir pq o objetivo é so atualizar os dados faltantes com base nos registros ja inseridos
			//$professor->insert();
		}

	}

}

//importa turmas
function importaTurmas(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;

	$sql = "select * from ca_turmas";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$id_turma = $dados["id_turma"];
		$nome_disciplina = $dados["nome_disciplina"];
		$periodo_letivo = $dados["periodo_letivo"];
		$curso = $dados["curso"];
		$id_professor = $dados["id_professor"];
		$id_coordenador = $dados["id_coordenador"];

		$turma = new Turma();
		$turma->setIdTurma($id_turma);
		$turma->setNomeDisciplina($nome_disciplina);
		$turma->setPeriodoLetivo($periodo_letivo);
		$turma->setCurso($curso);
		$turma->setProfessorId($id_professor);
		$turma->setCoordenadorId($id_coordenador);


		$select = "select id_turma from turma where id_turma = '".$id_turma."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			//ja existe um cadastrado
			//atualiza
			//echo "update";
			$turma->update();
			if($debug){
				echo "UPDATE >> ".$turma->getIdTurma()." - ".$turma->getNomeDisciplina();
				echo "<br />";
			}
		}
		else {
			//echo "insere";
			//ainda nao foi cadastrado
			//insere
			$turma->insert();
			if($debug){
				echo "	INSERT >> ".$turma->getIdTurma()." - ".$turma->getNomeDisciplina();
				echo "<br />";
			}
		}

	}

}

function importaTurmas2(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	
	$qtdRegistros = 500;

// 	$limitInicio = 0;
// 	$limitFim = $qtdRegistros;

// 	for ($i = 0; $i < 30; $i++) {
// 		$limitInicio = ($i*1)*$qtdRegistros;
// 		$limitFim = ($limitFim*1)+$qtdRegistros;
// 		//importaTurmas2();
// 		echo "inicio: ".$limitInicio;
// 		echo "fim: ".$limitFim;
// 		echo "<br />";

	
	
	//a sql abaixo pega só os registros q tem registros equivalentes na tabela de turmas
	//$sql = "select d.cod_turma, d.ra from ca_turmas t, ca_diario d where t.id_turma = d.cod_turma";

	//o mesmo q a linha de cima mas de forma otimizada
	//  $sql = "SELECT TOP 4000 d.cod_turma, d.ra FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC";
    
// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma and t.periodo_letivo = '2/2011' ORDER BY d.cod_turma DESC LIMIT ".$limitInicio.", ".$limitFim;
		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma GROUP BY d.cod_turma ORDER BY d.cod_turma DESC";
//      $sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 500, 800";
// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 800, 900";
// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 900, 1000";
// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 1000, 2000";
// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 2000, 3000";
// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 3000, 4000";
	$query = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	//$i = 0;


	while ($dados = mysql_fetch_assoc($query)) {
		$id_turma = $dados["cod_turma"];
		$ra_aluno = $dados["ra"];
		$serie_turma = $dados["serie"];
		echo $id_turma;
		echo "<br />";

		$turma = new Turma();
		$turma->get($id_turma);
		
		//adiciona a serie na turma e atualiza
		$turma->setSerie($serie_turma);
		$turma->save();
		$aluno = new Aluno();
		$aluno->get($ra_aluno);

		$turmas = array();
		$turmas = $aluno->getTurmas();
		array_push($turmas, $turma);
		$aluno->setTurmas($turmas);


		// 	$select = "select id_turma from turma where id_turma = '".$id_turma."'";
		$select = "select ra from aluno where ra = '".$ra_aluno."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			//ja existe um cadastrado
			//atualiza
			//echo "update";
			//$turma->update();
			$aluno->update();
			if($debug){
				echo "UPDATE >> ".$aluno->getNome();
				echo "<br />";
			}

		}
		else {
			//echo "insere";
			//ainda nao foi cadastrado
			//insere
			//$turma->insert();
			$aluno->insert();
			if($debug){
				echo "	INSERT >> ".$aluno->getNome();
				echo "<br />";
			}


		}



		//$i++;

	}
	
// 	} //fecha for
}

/**
* @name criaAvaliacoes
* @author Fabio Baía
* @since 03/02/2012 16:12:57
* cria as avaliacoes com base nos dados dos alunos
**/
function criaAvaliacoes($param) {
	$periodo_atual = "2/1011";
	
		
}
