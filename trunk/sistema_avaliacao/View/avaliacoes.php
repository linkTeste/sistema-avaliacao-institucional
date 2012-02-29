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
		$msgAvaliacao = "Avalia√ß√£o Realizada com sucesso!";
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
	
	//È importante guardar o ra pois a cada nova consulta sql precisaremos de um 'novo' aluno
	//e para obter o 'novo' aluno precisamos do ra dele
	$ra = $aluno->getRa();
}



if(isset($_SESSION["s_periodo"])){
	$periodo_atual = $_SESSION["s_periodo"];
	echo "periodo: ".$periodo_atual;
}else{
	header("Location: index.php");
}

// //pegar dados ficticios de aluno

// $ra = "0003.01.10"; //Ilson Gomes Psicologia
// // $ra = "0011.03.10"; //Dirnei de F·tima ServiÁo Social
// $ra = "0245.03.11"; //Camila Larissa

// $aluno = new Aluno();
// $aluno->get($ra);


// echo "Aluno: ".$aluno->getNome();
// echo "<br />";
// echo "RA: ".$aluno->getRa();

// //pega dados do processo de avaliacao
// $processo = new ProcessoAvaliacao();
// $processo->get(1);

// //periodo letivo atual pra limitar a listagem de turmas
// $periodo_atual = "2/2011";

// // $aluno = unserialize($_SESSION["aluno"]);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avalia√ß√£o Institucional - P√°gina Inicial</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

</head>

<body>


<?php if(isset($_GET['status'])){	?>
	<div id="blackout"></div>
	
	
	
	
<?php } ?>
<div id="menu_usuario">
		<ul>
			<li><a href="http://www.faculdadeunicampo.edu.br/" target="_blank">Faculdade
					Unicampo</a></li>
			<li><a href="http://mail.faculdadeunicampo.edu.br/" target="_blank">E-mail
					Unicampo</a></li>
			<li id="username">Ol&aacute;, <?php echo $aluno->getNome();?> - <a
				href="../Controller/loginController.php?action=logout">Sair</a>
			</li>
			
		</ul>
	</div>
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
    <div id="menu">
				<ul>
					<li><a href="index.php" title="P&aacute;gina Inicial"
						class="botao_left botaoGoogleGrey">P&aacute;gina Inicial</a></li>
					<li><a href="avaliacoes.php" title="Avalia&ccedil;&otilde;es"
						class="botao_left botaoGoogleGrey">Avalia&ccedil;&otilde;es</a></li>
					<li><a href="#" title="Relat&oacute;rios"
						class="botao_left botaoGoogleGrey">Relat&oacute;rios</a></li>
					
				</ul>
			</div>      
    
    <br />
    	<h3>Avalia√ß√µes Pendentes</h3>
    	
    	<!-- avaliacao do curso -->
<!--     	<div id="avaliacao_box"> -->
<!--     	<div class="div1"> -->
<!--     	            	<div class="photo"> -->
<!--     	<img src="css/images/avatar/foto_psicologia.jpg" alt="Curso de Psicologia" /> -->
<!--     	            	</div> -->
<!--     	<div class="description"> -->
<!--     	<h4><span>Curso:</span> Psicologia</h4> -->
<!--     	<h4><span>Coordenador:</span> Pedro Paulo Rodrigues Cardoso de Melo</h4> -->
<!--     	</div> -->
<!--     	</div> -->

<!--     	<a href="" class="botao_right btn_avaliacao botaoGoogleBlue">Avaliar</a> -->

<!--     	        </div> -->
    	        
    	<?php
    	    	
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
    	
    	
//     	if($aluno->fetch() == ""){
    	
//     		//È necessario pegar dados do aluno NOVAMENTE
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
    		<h4><span>Disciplina: </span><?php echo $aluno->id_turma." - ".utf8_encode($aluno->nome_disciplina); ?></h4>
    		<h4><span>Professor: </span><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    		</div>
    		</div>

    		<a href="../Controller/avaliacaoController.php?action=avaliar&turma=<?php echo $aluno->id_turma ?>"  title="Avaliar o professor" class="botao_right btn_avaliacao botaoGoogleBlue">Avaliar</a>

    		</div>
    		<?php 
    
    	}
    	if($aluno->fetch() == ""){
    		echo "Nenhuma avalia&ccedil;&atilde;o pendente";
    	}
    	
    	
    	?>
        <br />
        <br />
        <h3>Avalia√ß√µes Realizadas</h3>
        <?php
        
        //È necessario pegar dados do aluno NOVAMENTE
    	$aluno2 = new Aluno();
		$aluno2->get($ra);
			
    	$aluno2->alias('a');
    	
    	$t = new Turma();
    	$av2 = new Avaliacao();
    	// une as classes
    	$aluno2->join($t,'INNER','t');
    	$aluno2->join($av2, 'INNER', 'av2');
    	
    	// seleciona os dados desejados
    	$aluno2->select("t.idTurma, t.nomeDisciplina, t.professorId, av2.dataAvaliacao");
    	
    	$aluno2->where("t.periodoLetivo = '".$periodo_atual."' and t.idTurma = av2.turmaIdTurma");
    	
    	//agrupamos para n„o listar as avaliacoes de cada questao
    	$aluno2->groupBy("av2.turmaIdTurma");
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
    		<h4><span>Disciplina: </span><?php echo $aluno2->id_turma." - ".utf8_encode($aluno2->nome_disciplina); ?></h4>
    		<h4><span>Professor: </span><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    		</div>
    		</div>
    		<div class="div2">
            	<img class="ok" src="css/images/img-ok.png" /><br />
            	<h4>Avaliado em:</h4>
            	<h4><?php echo $aluno2->data_avaliacao?></h4>
            </div>
    		
    		</div>
    		<?php 
    
    	}
    	if($aluno2->fetch() == ""){
    		echo "Nenhuma avalia&ccedil;&atilde;o foi realizada ainda";
    	}
    	
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
