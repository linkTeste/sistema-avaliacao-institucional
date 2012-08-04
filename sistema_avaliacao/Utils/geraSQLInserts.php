<?php
// ini_set('memory_limit', '-1'); //nao funcionou

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




//define se � pra mostrar as mensagens de debug ou n�o
$debug = true;
//ordem
//professor
//coordenador
//turma
//aluno


// importaTudo();
// importaAlunos();
// importaProfessores();
// importaCoordenadores();
// importaFuncionarios();
// importaTurmas();
importaTurmas2();



//importa��o dos dados
function importaTudo(){
	importaAlunos();
	importaProfessores();
	importaProfessores();
	importaCoordenadores();
	importaTurmas();
	importaTurmas2();
}

//importa alunos
function importaAlunos(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	global $DB;

	//$sql = "select * from ca_cadastro where sit_acad is not null";
// 1 - matriculado
// 2 - matrícula trancada
// 3 - matrícula cancelada
// 4 - Transferido
// 5 - Formado
// 6 - Pré-matriculado
// 7 - Matrícula não renovada
// 8 - Abandono de curso

	$sql = "select * from ca_cadastro where sit_acad = 1 or sit_acad = 7";
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

		//escreve a sql de insert
		$sqlInsert = "INSERT INTO `".$DB."`.`aluno` (`ra`,`nome`,`login`,`senha`,`email`,`curso`,`sit_academica`)
			VALUES ('".$ra."', '".$nome."', '".$login."', '".$senha."', '".$email."', '".$curso."', ".$sit_acad.");";

		//escreve a sql de update

		$sqlUpdate = "UPDATE `".$DB."`.`aluno`
SET
`ra` = 	'".$ra."',
`nome` = '".$nome."',
`login` = '".$login."',
`senha` = '".$senha."',
`email` = '".$email."',
`curso` = '".$curso."',
`sit_academica` = ".$sit_acad."
WHERE `ra` = '".$ra."';
		";

		// 		$aluno = new Aluno();
		// 		$aluno->setRa($ra);
		// 		$aluno->setNome($nome);
		// 		$aluno->setCurso($curso);
		// 		$aluno->setLogin($login);
		// 		$aluno->setSenha($senha);
		// 		$aluno->setEmail($email);
		// 		$aluno->setSitAcademica($sit_acad);


		$select = "select ra from aluno where ra = '".$ra."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			//$aluno->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$aluno->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";
		}
		else {
			// 			$aluno->insert();
			// 			if($debug){
			// 				echo "	INSERT >> ".$aluno->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlInsert;
			echo "<br />";
		}

	}
}

//importa professores
function importaProfessores(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	global $DB;

	//$sql = "select * from ca_turmas";
	$sql = "select * from ca_turmas where turma != '1F' group by professor";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$nome = $dados["professor"];
		$id_professor = $dados["id_professor"];
		$id_coordenador = $dados["id_coordenador"];
		$isCoordenador = 0;

		$professor = new Professor();
		$professor->setId($id_professor);
		$professor->setNome($nome);
		if($id_professor == $id_coordenador){
			//$professor->setIscoordenador(true);
			$isCoordenador = 1;
		}else{
			//$professor->setIscoordenador(false);
			$isCoordenador = 0;
		}



		$select = "select id from professor where id = '".$id_professor."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		$sqlInsert = "INSERT INTO `faculdadeunica05`.`professor`
					(`id`,`nome`,`isCoordenador`)
					VALUES
					(".$id_professor.", '".$nome."', ".$isCoordenador."
											);";
		$sqlUpdate = "UPDATE `faculdadeunica05`.`professor`
SET
`id` = ".$id_professor.",
`nome` = '".$nome."',
`isCoordenador` = ".$isCoordenador."
WHERE `id` = ".$id_professor.";
		";

		if (mysql_num_rows($resultado) > 0 ) {
			// 			$professor->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$professor->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";
		}
		else {
			// 			$professor->insert();
			// 			if($debug){
			// 				echo "	INSERT >> ".$professor->getNome();
			// 				echo "<br />";
			// 			}
				
			echo $sqlInsert;
			echo "<br />";

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

		// 		$professor = new Professor();
		// 		$professor->setId($id_professor);
		// 		$professor->setNome($nome);
		// 		$professor->setLogin($login);
		// 		$professor->setSenha($senha);

		$sqlInsert = "INSERT INTO `faculdadeunica05`.`professor`
		(`id`,
		`login`,
		`senha`,
		`email`)
		VALUES
		(
		".$id_professor.",
		'".$login."',
		'".$senha."'
		);
			";
		$sqlUpdate = "UPDATE `faculdadeunica05`.`professor`
SET
`id` = ".$id_professor.",
`login` = '".$login."',
`senha` = '".$senha."'
WHERE `id` = ".$id_professor.";
		";


		$select = "select id from professor where id = '".$id_professor."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
			// 			$professor->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$professor->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";

		}
		else {
			//n�o vai inserir pq o objetivo � so atualizar os dados faltantes com base nos registros ja inseridos
			//$professor->insert();
			// 			echo $sqlInsert;
			// 			echo "<br />";
		}

	}

}

//importa professores
/**
 * @name importarFuncionarios
 * @author Fabio Baía
 * @since 14/06/2012 10:12:57
 * importa os funcionarios
 **/
function importaFuncionarios() {
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	global $DB;

	//$sql = "select * from ca_turmas";
	$sql = "select * from ca_professor where categoria = 2";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$nome = $dados["professor"];
		$id_professor = $dados["id_professor"];

		$login = $dados["login"];
		$senha = $dados["senha"];

		$select = "select id from funcionario where id = '".$id_professor."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		$sqlInsert = "INSERT INTO `faculdadeunica05`.`funcionario`
					(`id`,`nome`,`login`,`senha`)
					VALUES
					(".$id_professor.", '".$nome."', '".$login."', '".$senha."'
											);";
		$sqlUpdate = "UPDATE `faculdadeunica05`.`funcionario`
SET
`id` = ".$id_professor.",
`nome` = '".$nome."',
`login` = '".$login."',
`senha` = '".$senha."'
WHERE `id` = ".$id_professor.";
		";

		if (mysql_num_rows($resultado) > 0 ) {
			// 			$professor->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$professor->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";
		}
		else {
			// 			$professor->insert();
			// 			if($debug){
			// 				echo "	INSERT >> ".$professor->getNome();
			// 				echo "<br />";
			// 			}

			echo $sqlInsert;
			echo "<br />";

		}

	}


}

//importa coordenadores
/**
 * @name importaCoordenadores
 * @author Fabio Ba�a
 * @since 05/03/2012 13:57:50
 * importa os coordenadorwes do sistema academico
 **/
function importaCoordenadores() {
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	global $DB;

	$sql = "select * from ca_professor where nivel = 2";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$nome = $dados["professor"];
		$id_professor = $dados["id_professor"];
		$login = $dados["login"];
		$senha = $dados["senha"];

		// 		$professor = new Professor();
		// 		$professor->setId($id_professor);
		// 		$professor->setNome($nome);
		// 		$professor->setLogin($login);
		// 		$professor->setSenha($senha);
		// 		$professor->setIscoordenador(true);


		$sqlInsert = "INSERT INTO `faculdadeunica05`.`professor`
				(`id`,
				`nome`,
				`login`,
				`senha`,
				`isCoordenador`)
				VALUES
				(
				".$id_professor.",
				'".$nome."',
				'".$login."',
				'".$senha."',
				1
				);
					";
		$sqlUpdate = "UPDATE `faculdadeunica05`.`professor`
		SET
		`id` = ".$id_professor.",
		`nome` = '".$nome."',
		`login` = '".$login."',
		`senha` = '".$senha."',
		`isCoordenador` = 1
		WHERE `id` = ".$id_professor.";
				";

		$select = "select id from professor where id = '".$id_professor."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
				
			// 			$professor->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$professor->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";
		}
		else {
			// 			$professor->insert();
			echo $sqlInsert;
			echo "<br />";
		}

	}
}


//importa turmas
function importaTurmas(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	global $DB;

	$sql = "select * from ca_turmas where periodo_letivo = '1/2012' AND turma != '1F'";
	$query  = mysql_query($sql,$conexaoAcademico) or die(mysql_error());
	//Percorre os campos da tabela
	$i = 0;
	while ($dados = mysql_fetch_assoc($query)) {
		$id_turma = $dados["id_turma"];
		$nome_disciplina = $dados["nome_disciplina"];
		$periodo_letivo = $dados["periodo_letivo"];
		$curso = $dados["curso"];
		$turma_ca = $dados["turma"];
		$id_professor = $dados["id_professor"];
		$id_coordenador = $dados["id_coordenador"];

		// 		$turma = new Turma();
		// 		$turma->setIdTurma($id_turma);
		// 		$turma->setNomeDisciplina($nome_disciplina);
		// 		$turma->setPeriodoLetivo($periodo_letivo);
		// 		$turma->setCurso($curso);
		// 		$turma->setTurma($turma_ca);
		// 		$turma->setProfessorId($id_professor);
		// 		$turma->setCoordenadorId($id_coordenador);

		//escreve a sql de insert
		$sqlInsert = "INSERT INTO `".$DB."`.`turma` (`id_turma`,`nome_disciplina`,`periodo_letivo`,`curso`,`turma`,`professor_id`,`coordenador_id`)
					VALUES ('".$id_turma."', '".$nome_disciplina."', '".$periodo_letivo."', '".$curso."', '".$turma_ca."', '".$id_professor."', ".$id_coordenador.");";
			
		//escreve a sql de update
		$sqlUpdate = "UPDATE `".$DB."`.`turma`
		SET
		`id_turma` = 	'".$id_turma."',
		`nome_disciplina` = '".$nome_disciplina."',
		`periodo_letivo` = '".$periodo_letivo."',
		`curso` = '".$curso."',
		`professor_id` = '".$id_professor."',
		`coordenador_id` = '".$id_coordenador."',
		`turma` = '".$turma_ca."'
		WHERE `id_turma` = '".$id_turma."';
				";


		$select = "select id_turma from turma where id_turma = '".$id_turma."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
				
			// 			$turma->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$turma->getIdTurma()." - ".$turma->getNomeDisciplina();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";
		}
		else {
				
			// 			$turma->insert();
			// 			if($debug){
			// 				echo "	INSERT >> ".$turma->getIdTurma()." - ".$turma->getNomeDisciplina();
			// 				echo "<br />";
			// 			}
			echo $sqlInsert;
			echo "<br />";
		}

	}

}

function importaTurmas2(){
	global $conexao;
	global $conexaoAcademico;
	global $debug;
	global $DB;

	// 	$qtdRegistros = 500;

	// 	$limitInicio = 500;
	// 	$limitFim = $limitInicio+$qtdRegistros;

	// 	for ($i = 0; $i < 30; $i++) {
	// 		$limitInicio = ($i*1)*$qtdRegistros;
	// 		$limitFim = ($limitFim*1)+$qtdRegistros;
	// 		//importaTurmas2();
	// 		echo "inicio: ".$limitInicio;
	// 		echo "fim: ".$limitFim;
	// 		echo "<br />";



	//a sql abaixo pega s� os registros q tem registros equivalentes na tabela de turmas
	//$sql = "select d.cod_turma, d.ra from ca_turmas t, ca_diario d where t.id_turma = d.cod_turma";

	//o mesmo q a linha de cima mas de forma otimizada
	// 	 $sql = "SELECT TOP 4000 d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC";

	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma and t.periodo_letivo = '2/2011' ORDER BY d.cod_turma DESC LIMIT ".$limitInicio.", ".$limitFim;
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT ".$limitInicio.", ".$limitFim;

	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma and t.periodo_letivo = '1/2012' ORDER BY d.cod_turma DESC LIMIT 0, 500";
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC";
	//      $sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 500, 1000";
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 800, 900";
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 900, 1000";
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 1000, 2000";
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 2000, 3000";
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma ORDER BY d.cod_turma DESC LIMIT 3000, 4000";

	//pega s� a serie da turma
	//$sql = "SELECT d.cod_turma, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma GROUP BY d.cod_turma ORDER BY d.cod_turma";

	//pega por curso
	//$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d INNER JOIN ca_turmas t ON t.id_turma = d.cod_turma and t.periodo_letivo = '1/2012' and t.id_curso = ".$curso." ORDER BY d.cod_turma";
	//$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d, ca_turmas t WHERE t.id_turma = d.cod_turma and t.periodo_letivo = '1/2012' and t.id_curso = ".$curso." ORDER BY d.cod_turma";
	
	
	
	//obs: coloquei a restricao (situacao != 3 e situacao != 4) pq acho q são alunos desistentes, verificar depois
	//$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d, ca_turmas t WHERE t.id_turma = d.cod_turma and t.periodo_letivo = '1/2012' and t.turma != '1F' and d.situacao != 3 and situacao != 4 ORDER BY d.cod_turma";
	
	//o sql abaixo pega so as turmas dos alunos ok
	$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d, ca_turmas t, ca_cadastro c WHERE c.ra = d.ra and (c.sit_acad = 1 or c.sit_acad = 7) and t.id_turma = d.cod_turma and t.periodo_letivo = '1/2012' and t.turma != '1F' and d.situacao != 3 and situacao != 4 ORDER BY d.cod_turma";
	$query = mysql_query($sql,$conexaoAcademico) or die(mysql_error());

	$total = mysql_num_rows($query);

	$qtdReg = 100;
	$divisao = $total / $qtdReg;
	$ceil = ceil($divisao);
	// 	echo "divisao" .$divisao;
	// 	echo "<br />";
	// 	echo "sobra" .$sobra;
	// 	echo "<br />";
	// 	echo "arredondamento" .$ceil;
	// 	echo "<br />";
	$valor = 0;

	$array_series = array();

	//esse for serve para pegar os dados um pouco de cada vez pra n�o pesar os inserts
	// 	for($i = 0; $i<$ceil; $i++){
	// 		//echo "limit ".$valor.", ".$qtdReg;
	// 		//echo "<br />";
	// 		$valor += $qtdReg;
	// 		$sql = "SELECT d.cod_turma, d.ra, d.serie FROM ca_diario d, ca_turmas t WHERE t.id_turma = d.cod_turma and t.periodo_letivo = '1/2012' ORDER BY d.cod_turma LIMIT ".$valor.", ".$qtdReg;
	// 		$query = mysql_query($sql,$conexaoAcademico) or die(mysql_error());

	while ($dados = mysql_fetch_assoc($query)) {
		$id_turma = $dados["cod_turma"];
		$ra_aluno = $dados["ra"];
		$serie_turma = $dados["serie"];
		//echo $id_turma;
		//echo "<br />";

		$array_series[] = array("cod_turma" => $dados["cod_turma"],
							"ra" => $dados["ra"],
							"serie" => $dados["serie"]);

		//escreve a sql de insert
		$sqlInsert = "INSERT INTO `".$DB."`.`turma` (`id_turma`,`serie`)
							VALUES ('".$id_turma."', '".$serie_turma."');";
		$sqlInsert2 = "INSERT INTO `".$DB."`.`turma_has_aluno` (`turma_id_turma`,`aluno_ra`)
									VALUES ('".$id_turma."', '".$ra_aluno."');";


		//escreve a sql de update
		$sqlUpdate = "UPDATE `".$DB."`.`turma`
				SET
				`id_turma` = 	'".$id_turma."',
				`serie` = '".$serie_turma."'
				WHERE `id_turma` = '".$id_turma."';
						";
		$sqlUpdate2 = "UPDATE `".$DB."`.`turma_has_aluno`
						SET
						`turma_id_turma` = 	'".$id_turma."',
						`aluno_ra` = '".$ra_aluno."'
						WHERE `turma_id_turma` = '".$id_turma."' AND `aluno_ra` = '".$ra_aluno."';
								";


		// 		//faz update
		// 		$aluno->save();

		// 	$select = "select id_turma from turma where id_turma = '".$id_turma."'";
		$select = "select turma_id_turma from turma_has_aluno where turma_id_turma = '".$id_turma."'";
		$resultado = mysql_query($select, $conexao) or die(mysql_error());

		if (mysql_num_rows($resultado) > 0 ) {
				
			// 			$aluno->update();
			// 			if($debug){
			// 				echo "UPDATE >> ".$aluno->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlUpdate;
			echo "<br />";
			echo $sqlUpdate2;
			echo "<br />";

		}
		else {
			//
			// 			$aluno->insert();
			// 			if($debug){
			// 				echo "	INSERT >> ".$aluno->getNome();
			// 				echo "<br />";
			// 			}
			echo $sqlInsert2;
			echo "<br />";


		}



		//$i++;

	}//fecha while


	//} //fecha for


	//print_r($array_series);

	//exit;

}




/**
 * @name criaAvaliacoes
 * @author Fabio Ba�a
 * @since 03/02/2012 16:12:57
 * cria as avaliacoes com base nos dados dos alunos
 **/
function criaAvaliacoes($param) {
	//$periodo_atual = "2/1011";


}
