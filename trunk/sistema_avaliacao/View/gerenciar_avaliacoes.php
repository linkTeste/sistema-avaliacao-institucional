<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Questionario.php';
require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

if(isset($_GET['status'])){
	$status = $_GET['status'];
	if($status == "sucesso"){
		$msgAvaliacao = "AvaliaÃ§Ã£o Realizada com sucesso!";
	}
}


//teste com os dados do form
$arrayTurmas = array();
$arrayQuest = array();

$arrayTurmasQuest = array();
$size;
if(isset($_POST['turmas'])){
	
	
	
	foreach($_POST['turmas'] as $keyTurma)
	{
		$arrayTurmas[] = $keyTurma;
	}
	
	$size = count($arrayTurmas);
	
	foreach($_POST['quest'] as $keyQuest)
	{
		$arrayQuest[] = $keyQuest;
	}
	
	for ($i = 0; $i < $size; $i++) {
		$arrayTurmasQuest[$arrayTurmas[$i]] = $arrayQuest[$i];
		$turma = new Turma();
		$turma->get($arrayTurmas[$i]);
		$turma->setQuestionarioId($arrayQuest[$i]);
		$turma->update();
	}
	
	
	
	//print_r($arrayTurmasQuest);
	
}



//pegar dados ficticios de aluno
$aluno = new Aluno();
// $aluno->get("0003.01.10"); //Ilson Gomes Psicologia
// $aluno->get("0011.03.10"); //Dirnei de Fátima Serviço Social
$aluno->get("0245.03.11"); //Camila Larissa

echo "Aluno: ".$aluno->getNome();
echo "<br />";
echo "RA: ".$aluno->getRa();

//periodo letivo atual pra limitar a listagem de turmas
$periodo_atual = "2/2011";
$curso_escolhido = "Psicologia";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de AvaliaÃ§Ã£o Institucional - PÃ¡gina Inicial</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jqtransformplugin/jqtransform.css" />
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jqtransformplugin/jquery.jqtransform.js"></script>
<script type="text/javascript">
	/*$(document).ready(function(){

    $('#gerenciar_avaliacoes').jqTransform();
    
	});
	*/
</script>	

</head>

<body>


<?php if(isset($_GET['status'])){	?>
	<div id="blackout"></div>
	
	
	
	
<?php } ?>
	
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
    <br />
    	<h3>Turmas do periodo letivo atual(2/2011)</h3>
    	<p>Para cada turma escolha o questionario que sera usado.</p>
    	

<!-- 		<select class="select_right" name="cursos"> -->
<!--     		<option value="1">Psicologia</option> -->
<!--     		<option value="2">Enfermagem</option>   -->
<!--     		<option value="3">Serviço Social</option>   -->
<!--     		<option value="4">Gestão Comercial</option>   -->
<!--     		<option value="5">Gestão de Cooperativas</option>	 -->
<!--     	</select> -->
    	
    	<form action="" id="gerenciar_avaliacoes" method="post">
    	<?php
    	
    	
    	$turma = new Turma();
    	$turma->where("periodo_letivo = '".$periodo_atual."'");
    	//$turma->where("curso = '".$curso_escolhido."'");
    	$turma->find();
    	
    	while( $turma->fetch() ) {
    		//pega o id do professor
    		$id_professor = $turma->getProfessorId();
    		
    		//pega o professor
    		$professor = new Professor();
    		$professor->get($id_professor);
    		
    		?>
    		<div id="avaliacao_box">
    		<div class="div700">
    		<div class="photo">
    		<img src="<?php echo pegaImagem($professor->getId()); ?>" alt="Foto do Professor" />
    		</div>
    		<div class="description">
    		<h4><span>Disciplina: </span><?php echo $turma->getIdTurma()." - ".utf8_encode($turma->getNomeDisciplina()); ?></h4>
    		<h4><span>Professor: </span><?php echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    		</div>
    		</div>
    		<input type="hidden" name="turmas[]" value="<?php echo $turma->getIdTurma(); ?>"></input>
    		<div id="select" class="botaoGoogleGrey">
    		<select name="quest[]">
    			<option value="0">Selecione</option>
    			<?php 
    			
    			
    			$lista_questionarios = new Questionario(); 
    			$lista_questionarios->instrumentoId = 1;		//assim lista só os questionarios das turmas(instrumento 1)
    			$lista_questionarios->find();    			 
    			while( $lista_questionarios->fetch() ) {
    				$quesId = $turma->getQuestionarioId();
    				echo "quesID: ".$quesId;
    				if($quesId == $lista_questionarios->getId()){
    					$selected = "selected=\"selected\"";
    				}
    				else{
    					$selected = "";
    				}
    			?>
    			
    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo $lista_questionarios->getDescricao();?></option>
    			<?php
    			}
    			?>
    			
    		</select>
    		</div>
    		</div>
    		<?php 
    		
//     		echo "<tr>";
//     		echo "<td>".$aluno->id_turma."</td>";
//     		echo "<td>".utf8_encode($aluno->nome_disciplina)."</td>";
//     		echo "</tr>";
//     		echo "<br />";
    	}
    	
    	?>
        
        <input type="submit" value="Salvar" name="enviar" />
        </form>
        
        
        <br />
       
                
        
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;<?php echo date("Y");?> - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
</body>
</html>
