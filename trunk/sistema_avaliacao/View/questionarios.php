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
require_once '../system/application/models/dao/Laboratorio.php';
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
}


$laboratorios = new Laboratorio();
$laboratorios->find();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Questionários</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<link type="text/css"
	href="css/unicampo-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript">

		function escreveNome(){
			var name = $('#tipo').val() +"_"+ $('#subtipo'). val();
            $('#description').val(name); 
		}
		
    	function teste() {
    		   		
    		
            //aqui só executo se o option for diferente do 'Selecione'
            if($('#tipo').selectedTexts() != 'Selecione'){
               
                    //aqui removo todos <option>, necessario para evitar ficar option quando seleciona algum estado que nao tem cidade
                    $('#subtipo').removeOption(/./);
					
					
                    var tipoAluno = {
    						"Professor/Disciplina" : "Professor/Disciplina",
    						"Curso/Coordenador" : "Curso/Coordenador",
    						"Instituição" : "Instituição"
    						<?php 
    						foreach ($laboratorios as $lab){
    							echo ',"'.utf8_encode("Lab_".$lab->getNome()).'" : "'.utf8_encode("Lab_".$lab->getNome()).'"';
							}
    						?>
    						}
					
					var tipoProfessor = {
    						"Auto-avaliação-professor" : "Auto-avaliação",
    						"Coordenador" : "Coordenador",
    						"Instituição" : "Instituição"
    						<?php 
    	    				foreach ($laboratorios as $lab){
    	    					echo ',"'.utf8_encode("Lab_".$lab->getNome()).'" : "'.utf8_encode("Lab_".$lab->getNome()).'"';
    						}
    	    				?>
    						}
                    var tipoCoordenador = {
    						"Auto-avaliação-coordenador" : "Auto-avaliação",
    						"Docente" : "Docente",
    						"Instituição" : "Instituição"
    						}
                    var tipoFuncionario = {
    						"Instituição" : "Instituição"
    						}
					
                    if($('#tipo').selectedTexts() == 'Aluno'){
                    	$('#subtipo').addOption(tipoAluno, false);
                    }
                    if($('#tipo').selectedTexts() == 'Professor'){
                    	$('#subtipo').addOption(tipoProfessor, false);
                    }
                    if($('#tipo').selectedTexts() == 'Coordenador'){
                    	$('#subtipo').addOption(tipoCoordenador, false);
                    }
                    if($('#tipo').selectedTexts() == 'Funcionário'){
                    	$('#subtipo').addOption(tipoFuncionario, false);
                    }        

					escreveNome();                        
                                  
            }
 
    }
        
	
</script>
<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>
	
<?php if(($new == true) || $edit == true){	?>
<script type="text/javascript">
$(document).ready(function() {
	ativaBlackout();
	ativaPopup();
	verificaSize();
})
</script>
<?php }?>

</head>

<body style="background: #fafafa;">





<?php if(($new == true) || $edit == true){	?>

	<div id="overlay"></div>
	
	
	
	
	
	
<?php } ?>
<!-- 
	<div id="menu_usuario">
		<ul>
			<li><a href="http://www.faculdadeunicampo.edu.br/" target="_blank">Faculdade
					Unicampo</a></li>
			<li><a href="http://mail.faculdadeunicampo.edu.br/" target="_blank">E-mail
					Unicampo</a></li>
			<li id="username">Ol&aacute;, <?php //echo $usuario_logado->getNome();?> - <a
				href="../Controller/loginController.php?action=logout">Sair</a>
			</li>
			
		</ul>
	</div>
	-->	
<div id="wrapper" class="container">
<?php if(($new == true) || $edit == true){	?>
    <div id="box">
    	<div id="box_inside">
        <?php
		$descricao = "";
      	if($edit == true){
      	
      		if(isset($_SESSION["questionario"])){
        	//$questionario = new questionario;
        	$questionario = unserialize($_SESSION["questionario"]);
        	//debug
        	//print_r($questionario);
        	$id = $questionario->getId();
        	$descricao = utf8_encode($questionario->getDescricao());
        	$tipo = utf8_encode($questionario->getTipo());
        	$subtipo = utf8_encode($questionario->getSubtipo());
        	}
      	}
		?>
    		
    		<form action="../Controller/questionarioController.php?action=save" id="form-questionario" method="post">
        	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
        	            
            <label for="tipo">Avaliador:</label><br />
            <select name="tipo" id="tipo" onchange="teste()" >
            <?php 
            if($tipo != ""){
            	$selectedA = "";
            	$selectedP = "";
            	$selectedC = "";
            	$selectedF = "";
            	if($tipo == "Aluno"){
            		$selectedA = "selected='selected'";
            	}
            	if($tipo == "Professor"){
            		$selectedP = "selected='selected'";
            	}
            	if($tipo == "Coordenador"){
            		$selectedC = "selected='selected'";
            	}
            	if($tipo == "Funcionário"){
            		$selectedF = "selected='selected'";
            	}
            }
            if($subtipo != ""){
            	$selectedA = "";
            	$selectedP = "";
            	$selectedC = "";
            	$selectedF = "";
            	if($tipo == "Aluno"){
            		$selectedA = "selected='selected'";
            	}
            	if($tipo == "Professor"){
            		$selectedP = "selected='selected'";
            	}
            	if($tipo == "Coordenador"){
            		$selectedC = "selected='selected'";
            	}
            	if($tipo == "Funcionário"){
            		$selectedF = "selected='selected'";
            	}
            }
            ?>
            	<option value="-1">Selecione</option>
            	<option value="Aluno" <?php echo $selectedA?>>Aluno</option>
            	<option value="Professor" <?php echo $selectedP?>>Professor</option>
            	<option value="Coordenador" <?php echo $selectedC?>>Coordenador</option>
            	<option value="Funcionário" <?php echo $selectedF?>>Funcionário</option>
            </select>
            <br />
			<br />
			<br />
            
            <label for="subtipo">Avaliação:</label><br />
            <select name="subtipo" id="subtipo" onchange="escreveNome()">
            	
            	<?php
            	if($subtipo != ""){
            		echo "<option value='".$subtipo."'>".$subtipo."</option>";
            	}else{
            		echo "<option value='-1'>Selecione</option>";
            	}
            	?>
            	
            </select>
            <br />
			<br />
			<br />
			
            <label for="description">Nome do questionário:</label><br />
        	<input type="text" name="description" id="description" value="<?php //echo $nome_questionario; ?>"/><br /><br /><br />
                    
        	
        	<hr />
            <button class="botaoGoogleBlue float-right" type="submit" name="enviar" onclick="removePopup();document.getElementById('status').style.zIndex='0';">Salvar</button>
            
            <button class="botaoGoogleBlue float-right" type="reset" name="cancelar" onclick="removePopup();document.getElementById('status').style.zIndex='0';">Cancelar</button>        	        
            
            <div class="clear"></div>
            </form>
       	</div>
     </div>   
     <!--<div id="box">
    	<div id="box_inside">
    		<form action="adm_questionario.php" method="post">
        	<label for="textarea-question">Texto da questão:</label><br />
            <textarea id="textarea-question" name="textarea-question"></textarea>
        	
            <button class="btn-default float-right" type="submit" name="enviar" onclick="document.getElementById('box').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">Salvar</button>
            
            <button class="btn-default float-right" type="reset" name="cancelar" onclick="document.getElementById('box').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">Cancelar</button>        	        
            
            <div class="clear"></div>
            </form>
       	</div>
    </div>-->
<?php } ?>
	<?php include_once 'inc/header_inc.php';?>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>       
    
    <div class="white">
		<br />
        <a href="../Controller/questionarioController.php?action=new"  title="Novo Questionário" class="botao_right botaoGoogleBlue">Novo Questionário</a>
        <a href="gerenciar_avaliacoes.php"  title="Definir Questionários" class="botao_right botaoGoogleBlue">Definir Questionários</a>

        <h3>Questionários Cadastrados</h3>
        
       <div id="questionarios">
        	<table>
            	<tr>
                	<th>ID</th>
                    <th>NOME</th>
                    <th>AVALIADOR</th>
                    <th>AVALIAÇÃO</th>
                    <th>QUESTÕES</th>
                    <th>MODIFICADO EM</th>
                    <th colspan="2"></th>
                </tr>
                <?php
                                
                               
                	$lista = new Questionario();
                	$lista->order("id DESC");
                	
                	//parametros da paginacao
                	$totalRegistros = $lista->find();
                	$registrosPagina = 10;
                	$paginacao = $totalRegistros / $registrosPagina;
                	$paginacaoConvertida = ceil($paginacao);
                	
                	if(isset($_GET['pag'])){
                		$pagIndex = $_GET['pag'];
                		$indice = ($_GET['pag'] - 1) * 10;
                		                		
                		$lista = new Questionario();
                		$lista->order("id DESC");
                		$lista->limit($indice, $registrosPagina);
                		$lista->find();
                	}else{
                		$pagIndex = 1;
                		                		
                		$lista = new Questionario();
                		$lista->order("id DESC");
                		$lista->limit(0, $registrosPagina);
                		$lista->find();
                	}
                	
                	
					while( $lista->fetch()) {
						//pega o total de questoes
						
						$qhq = new QuestionarioHasQuestao();
						$qhq->setQuestionarioId($lista->getId());
						$totalQuestoes = $qhq->find();
						
						
						echo "<tr>";
						echo "<td style='width: 5%'>".$lista->getId()."</td>";
						echo "<td style='width: 40%'><a href='../Controller/questionarioController.php?action=details&id=".$lista->getId()."' class='link2'>".utf8_encode($lista->getDescricao())."</a></td>";
						echo "<td style='width: 10%'>".utf8_encode($lista->getTipo())."</td>";
						echo "<td style='width: 20%'>".utf8_encode($lista->getSubtipo())."</td>";
						echo "<td style='width: 10%'>".utf8_encode($totalQuestoes)."</td>";
						echo "<td style='width: 15%'>".datetime_to_ptbr($lista->getDataCreate())."</td>";

						echo "<td style='width: 5%'><a href='../Controller/questionarioController.php?action=details&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Detalhes do Questionário'>Detalhes</a></td>";
						
						if($lista->getAvaliado() == "Avaliado"){
							echo "<td style='width: 5%'>&nbsp</td>";
							echo "<td style='width: 5%'>&nbsp</td>";
						}else{							
							echo "<td style='width: 5%'><a href='../Controller/questionarioController.php?action=edit&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Editar Questionário'>Editar</a></td>";
							echo "<td style='width: 5%'><a href='../Controller/questionarioController.php?action=delete&id=".$lista->getId()."' class='botao_right botaoGoogleRed' title='Remover Questionário'>Excluir</a></td>";
						}
						
						echo "</tr>";
					}
                

		
				?>
               
            
            </table>
            <div id="paginacao">
<?php
 $lastPage = ceil($paginacao);
 
 $min;
 $max;
 $diferencaInicio;
 $diferencaFim;
 if($paginacaoConvertida > 10){
 	$paginadorQtd = 10;
 }else{
 	$paginadorQtd = $paginacaoConvertida;
 }
 
 if($pagIndex - 1 > 4){
	 $min = $pagIndex - 4;
	 $primeiraPagina = '<a href="?pag=1">Primeira Página</a>';
 }else{
	 $min = 1;
	 $diferencaInicio = 4 - ($pagIndex - 1);	 
	 $primeiraPagina = "";
 }
 if($lastPage - $pagIndex > 4){
	 $max = $pagIndex + 4 + $diferencaInicio;
	 $ultimaPagina = '<a href="?pag='.$lastPage.'">Última Página</a>';
 }else{
	 $max = $lastPage;
	 $diferencaFim = 4 - ($lastPage - $pagIndex);
	 
	 //obs
	 $min = $pagIndex - 4 - $diferencaFim;
	 $ultimaPagina = "";
 }
 
 echo $primeiraPagina;
 
 for($i=$min; $i <= $max; $i++){
	 $p = $pagIndex+$i;
 	if($i == $pagIndex){
 		echo "<span>$pagIndex</span>";
 	}else{
 		echo '<a href="?pag='.$i.'">'.$i.'</a>';
 	} 	
 }
 echo $ultimaPagina;
 ?>
 </div>
        
        </div>
       
       
        </div><!-- fecha div white -->
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
</html>
