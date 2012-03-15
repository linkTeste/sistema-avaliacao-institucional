<?php

///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
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
	if($_SESSION["action"] == "details"){
		$details = true;
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

if(isset($_SESSION["s_questionario"])){
	$str = $_SESSION["s_questionario"];
	if($str instanceof Questionario){
		$questionario = $str;
	}else{
		$questionario = unserialize($_SESSION["s_questionario"]);
	}
	
	$questionario_id = $questionario->getId();
	$questionario_avaliado = $questionario->getAvaliado();
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Questionário</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css"
	href="css/jquery.autocomplete.css" />

<link href="css/smoothness/jquery-ui-1.8.17.custom.css" rel="stylesheet"
	type="text/css" />

<script type="text/javascript"
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
<script type="text/javascript"
	src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>

<script type="text/javascript">
$().ready(function() {
		
		$("#texto").autocomplete({
			source: "../Utils/searchQuestions.php"
		});
	});
	</script>
	
	<style>
	#sortable { list-style-type: none; margin: 5px; padding: 0; width: 100%; }
	tr.highlight { height: 100px;}
	/*#sortable tr:hover {cursor:move;}*/
	</style>
	<script>
	$(function() {
		$( "#sortable" ).sortable({
			placeholder: "highlight",
			items: "tr",
			cursor: "move",
			axis: 'y',
			containment: 'parent',
			tolerance: 'pointer',
			handle: 'span',
			update: function() {
				var idDrag = this.id;
				//alert("id: "+idDrag);

				if(idDrag == "sortable"){						
					var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
					//alert("ordem: "+order); 
					$.post("../Controller/questaoController.php", order, function(theResponse){
						$("#teste").html(theResponse);
					});
				}
				},								  
			stop: function( e, ui ) {
				//salvaCookie();
			}
		});
		$( "#sortable" ).disableSelection();
	});
	</script>

</head>

<body>


<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>
	
	
	
	
<?php } ?>
<div id="menu_usuario">
		<ul>
			<li><a href="http://www.faculdadeunicampo.edu.br/" target="_blank">Faculdade
					Unicampo</a></li>
			<li><a href="http://mail.faculdadeunicampo.edu.br/" target="_blank">E-mail
					Unicampo</a></li>
			<li id="username">Ol&aacute;, <?php echo $usuario_logado->getNome();?> - <a
				href="../Controller/loginController.php?action=logout">Sair</a>
			</li>
			
		</ul>
	</div>
	
	<div id="teste"></div>
	
<div id="wrapper" class="container">
<?php if(($new == true) || $edit == true){	?>
    <div id="box">
    	<div id="box_inside">
        <?php
		$descricao = "";
      	if($edit == true){
      	
//       		if(isset($_SESSION["questionario"])){
//         	//$questionario = new questionario;
//         	$questionario = $_SESSION["questionario"];
//         	//debug
//         	//print_r($questionario);
//         	$id = $questionario->getId();
//         	$descricao = $questionario->getDescricao();
//         }
      	}
		?>
		
    		<form action="../Controller/questaoController.php?action=save" id="form-questionario" method="post">
        	<input type="hidden" name="questionario_id" value="<?php echo $questionario->getId(); ?>"/>
            <label for="texto">Texto:</label><br />
            
            <textarea rows="" cols="" name="texto" id="texto"></textarea>
            
            <label for="checkbox-opcional">
            <input name="checkbox-opcional" id="checkbox-opcional" value="1" type="checkbox" title="Marque para tornar essa questão opcional" /> Questão opcional
            </label>
			<br /><br />
            
                    
        	
        	<hr />
            <button class="botaoGoogleBlue float-right" type="submit" name="enviar" onclick="document.getElementById('box').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">Salvar</button>
            
            <button class="botaoGoogleBlue float-right" type="reset" name="cancelar" onclick="document.getElementById('box').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">Cancelar</button>        	        
            
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
	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <div id="menu">
    <ul>
    <?php 
    	foreach ($usuario_logado_permissoes as $value) {
    		$permissao = new Permissao();
    		$permissao->get($value);
    ?>
    <li><a href="<?php echo $permissao->getLink();?>"  title="<?php echo $permissao->getNome();?>" class="botao_left botaoGoogleGrey"><?php echo $permissao->getNome();?></a></li>
    <?php		
    	}    
    ?>	
    </ul>    
    </div>       
    
    <br />
        
    <h2>Questionario <?php echo $questionario->getDescricao(); ?></h2> 
    <br />
    <?php 
    	if($questionario_avaliado == "Avaliado"){
    		
    	}
    	else{
    	?>
    			
    	<a href="../Controller/questaoController.php?action=new&questionario_id=<?php echo $questionario->getId()?>"  title="Nova Questão" class="botao_right botaoGoogleBlue">Nova Questão</a>
    	
        <?php } ?>    	
        

        <h3>Questões Cadastradas</h3>
        
                
        <div id="questionarios">
        	<table>
        	<thead>
            	<tr>
<!--             		<th>&nbsp;</th> -->
                	<th>ID</th>
                	<th>QUEST&Atilde;O</th>
                	<th>MODIFICADO EM</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody id="sortable">
                
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
    		echo "<tr id=recordsArray_".$questionario->id.">";
    		//echo "<td style='width: 5%'>".$questionario->id."</td>";
    		echo "<td style='width: 5%'><span>:: </span>".$questionario->id."</td>";
    		
//     		if($questionario->opcional == "opcional"){
//     			echo "<td><img src='css/images/opcional.png' alt='Opcional'/></td>";
//     		}else{
//     			echo "<td>&nbsp</td>";
//     		}
    		
    		if($questionario->opcional == "opcional"){
    			echo "<td style='width: 70%'>".utf8_encode($questionario->texto)."<span class='span_opcional'>Questão Opcional</span></td>";
    		}else{
    			echo "<td style='width: 70%'>".utf8_encode($questionario->texto)."</td>";
    		}
    		
    		echo "<td style='width: 20%'>".datetime_to_ptbr($questionario->dataCreate)."</td>";
    		
    		if($questionario_avaliado == "Avaliado"){
    			echo "<td style='width: 10%'>&nbsp</td>";
    		}
    		else{
    			echo "<td style='width: 10%'><a href='../Controller/questaoController.php?action=delete&id=".$questionario->id."&questionario_id=".$questionario_id."' class='botao_right botaoGoogleGrey' title='Remover do questionário'>Excluir</a></td>";
    		}
    		echo "</tr>";
    		
    	}
    	
    
    ?>
           </tbody>    
            
            </table>
        
        </div>
        
    </div>
    
    <div id="footer">
        <hr />
    	<p>&copy;<?php echo date("Y");?> - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
</body>
</html>
