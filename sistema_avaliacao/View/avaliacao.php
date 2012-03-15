<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Professor.php';

require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

if(isset($_SESSION["s_questionario"])){
	$questionario = unserialize($_SESSION["s_questionario"]);
	$questionario_id = $questionario->getId();
}

//verifica o tipo e subtipo
if(isset($_SESSION["tipo"])){
	$tipo = $_SESSION["tipo"];
	if(isset($_SESSION["subtipo"])){
		$subtipo = $_SESSION["subtipo"];
		
	}

}

echo "tipo: ".$tipo;
echo "<br />";
echo "subtipo: ".$subtipo;

if($subtipo == "Professor/Disciplina"){
	$id_professor;
	if(isset($_SESSION["s_turma"])){
		$turma = unserialize($_SESSION["s_turma"]);
		$id_professor = $turma->getProfessorId();
	}	
}

if($subtipo == "Curso/Coordenador"){
	$id_professor;
	if(isset($_SESSION["s_curso"])){
		$curso = $_SESSION["s_curso"];
	}
	if(isset($_SESSION["s_coordenador"])){
		$coordenador = unserialize($_SESSION["s_coordenador"]);
		//$id_coordenador = $coordenador->getId();
	}
}



// if(isset($_SESSION["aluno"])){
// 	$aluno = unserialize($_SESSION["aluno"]);
// }
if(isset($_SESSION["s_aluno"])){
	$str = $_SESSION["s_aluno"];
	if($str instanceof Aluno){
		$aluno = $str;
	}else{
		$aluno = unserialize($_SESSION["s_aluno"]);
	}
}

$professor = new Professor();
$professor->get($id_professor);



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
<link href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.raty.js"></script>
</head>

<body>
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
    	
        <div id="avaliacao_current">
           	<div class="div1"> 
            	<h2>Você está avaliando agora...</h2>          	
            	<div class="photo">
                	<img src="<?php echo pegaImagem($professor->getId()); ?>" alt="Foto do Professor" />
            	</div>
            	<?php
            	if($tipo == "Aluno" && $subtipo == "Professor/Disciplina" ){
            	?>
            	<div class="description">                	
            		<h4><span>Professor: </span><?php echo utf8_encode($professor->getNome()); ?></h4>
            		<h4><span>Disciplina: </span><?php echo utf8_encode($turma->getNomeDisciplina()); ?></h4>                
            	</div>
            	<?php 
				}
            	if($tipo == "Aluno" && $subtipo == "Curso/Coordenador" ){
            	?>
            	<div class="description">                	
            		<h4><span>Curso: </span><?php echo $curso; ?></h4>
            		<h4><span>Coordenador: </span><?php echo "tste".$coordenador->getNome(); ?></h4>                
            	</div>
            	<?php 
				}
				if($tipo == "Aluno" && $subtipo == "Instituição" ){
            	?>
            	<div class="description">
<!--             	<h4><span>Curso: </span></h4> -->
            	<h4>Instituição</h4>                
            	</div>
            	<?php 
				}
            	?>
            </div>
        </div>
        
        <form name="form" method="post" action="../Controller/avaliacaoController.php" onsubmit="return verifica()">
        	<input  type="hidden" name="action" value="saveInDatabase"/>
        	<input  type="hidden" name="questionario_id" value="<?php echo $questionario_id;?>"/>
        	<input  type="hidden" name="tipo" value="<?php echo $tipo;?>"/>
        	<input  type="hidden" name="subtipo" value="<?php echo $subtipo;?>"/>
        	
        <div id="escala_conceitos">
        	<h3>Questões</h3>

        	
         <?php 
    
    	
    	// muda o alias
    	$questionario->alias('q');
    	// telefone
    	$q = new Questao();
    	// une as classes
    	$questionario->join($q,'INNER','qu');
    	// seleciona os dados desejados
    	$questionario->select("qu.id, qu.texto, qu.topico, qu.opcional");
    	// recupera os registros
    	$questionario->find();
    	
    	while( $questionario->fetch() ) {

    		if($questionario->opcional != "opcional"){
    	?> 
            <script type="text/javascript">
            $(document).ready(function() {
            	  adicionaObrigatoria(<?php echo $questionario->id ?>);
            	});
    		
    		</script>
    		<?php }?>
    		
            <div class="questao <?php echo $questionario->opcional ?>" id="<?php echo $questionario->id ?>">
            	<?php if($questionario->opcional == "opcional"){ ?>
    		
                <div class="texto_questao"><h4><?php echo utf8_encode($questionario->texto)?><span class="span_opcional">Questão Opcional</span></h4></div>
                
                <?php }else{ ?>
                
                <div class="texto_questao"><h4><?php echo utf8_encode($questionario->texto)?></h4></div>
                
                <?php }?>
                
                <div class="star_questao">                
                	<!--<div class="group"></div>
                	<div id="hint"></div>-->
                </div>
                
            </div>
            
            <?php

				}			
			?>
			
            <label for="obs" class="obs">Sugestões e/ou reclamações</label><br />
            <textarea name="obs" class="obs"></textarea>          
                      
            
        </div>
        
        
        <br />
        
        <!-- <span class="btn-comecar-avaliacao"><a href="../Controller/avaliacaoController.php?action=saveInDatabase&questionario_id=<?php //echo $questionario_id?>">Salvar Avaliação</a></span> -->
<!--         <span class="btn-comecar-avaliacao"> -->
        <input type="submit" value="Salvar" name="enviar" class="botaoGoogleBlue" style="/*margin-left: 440px;*/margin-left: 47%;" />
        <br />
<!--         </span> -->
        
        </form>          
        
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;<?php echo date("Y");?> - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>

<script type="text/javascript">  
                	$('.questao').raty({
						path:       'css/images/',				
						starOff:    'star-unmarked.png',
  						starOn:     'star-marked.png',
						iconRange: [
									{ range: 1, on: 'star-marked1.png', off: 'star-unmarked1.png' },
						            { range: 2, on: 'star-marked2.png', off: 'star-unmarked2.png' },
						            { range: 3, on: 'star-marked3.png', off: 'star-unmarked3.png' },
						            { range: 4, on: 'star-marked4.png', off: 'star-unmarked4.png' },
						            { range: 5, on: 'star-marked5.png', off: 'star-unmarked5.png' }
						          ],
						hintList:  ['Questão não atendida', 'Questão atendida em até 25% das vezes', 'Questão atendida em até 50% das vezes', 'Questão atendida em até 75% das vezes', 'Questão atendida em até 100% das vezes'],
  						size:       35,
						click: function(score, evt) {
							var question_id = $(this).attr('id');
							$.ajax({
						           type: 'POST',
						           url: '../Controller/avaliacaoController.php',
						           data: {'action': 'saveScore',
							           'question_id': question_id, 
						        	   'score':score},
						          success: function(data){
							          adiciona(data);
							          //alert('Your rating was saved'+data); 
							          }         
						         });
						         
						         //alert('ID: ' + $(this).attr('id') + '\nscore: ' + score + '\nevent: ' + evt);						
						},
						width: 1202
						/*width: 948
						/*,
						target:     '.target',
						targetKeep: true,
						targetType: 'number'*/
					});
				</script>
<script type="text/javascript"> 
var array_questoes = new Array();
//var array_obrigatorias = [ "1", "2", "3"];
var array_obrigatorias = new Array();

function adiciona(data){
	//adiciona ao array de respondias
	array_questoes.push(data);	
	//alert(array_questoes);
}

function adicionaObrigatoria(data){
	//adiciona ao array de obrigatorias
	array_obrigatorias.push(data);	
	//alert(array_questoes);
}
function verifica(){
	var isInArray = 1;
	//verificar se as perguntas no array de obrigatorias estao no array de respondidas
	
	$.each(array_obrigatorias, function(index, value){
		//alert(index + ': ' + value); 
		
		//concatenar o valor com "" para tranforma-lo em string
		var x = jQuery.inArray(""+value, array_questoes);
		if(x == -1){
			isInArray = x;
			
		}
		//alert(x);	
	});
	
	if(isInArray == -1){
		alert("Responda todas as questões obrigatorias");
		//alert(array_obrigatorias);
		//alert(array_questoes);
		return false;
	}else{
		return true;
	}
	
}

</script>
<div id="teste"></div>
</body>
<?php 

$_SESSION["turma"] = serialize($turma);
$_SESSION["aluno"] = serialize($aluno);

?>
</html>