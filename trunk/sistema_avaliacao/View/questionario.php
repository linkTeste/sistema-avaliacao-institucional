﻿<?php

//obs: os requires devem vir antes da sessao
require '../Model/Bean/questionario.class.php';
require '../Model/DAO/questionarioDAO.class.php';
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
}


$questionarioDAO = new questionarioDAO();
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
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

</head>

<body>
<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>
	
<?php } ?>
	
<div id="wrapper" class="container">
<?php if(($new == true) || $edit == true){	?>
    <div id="box">
    	<div id="box_inside">
        <?php
		$descricao = "";
      	if($edit == true){
      	
      		if(isset($_SESSION["questionario"])){
        	//$questionario = new questionario;
        	$questionario = $_SESSION["questionario"];
        	//debug
        	//print_r($questionario);
        	$id = $questionario->getId();
        	$descricao = $questionario->getDescricao();
        }
      	}
		?>
    		<form action="../Controller/questionarioController.php?action=save" id="form-questionario" method="post">
        	<label for="input-name">Nome do questionário:</label><br />
        	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="text" name="description" value="<?php echo $descricao; ?>"/><br /><br /><br />
            <label for="instrumento">Instrumento:</label><br />
            <select name="instrumento">
            	<option value="1">Instrumento 1 - Auno avalia professor</option>
                <option value="2">Instrumento 2 - Aluno avalia curso</option>
                <option value="3">Instrumento 3 - Funcionário avalia Instituição</option>
                <option value="4">Instrumento 4 - Professor avalia ...</option>
                <option value="5">Instrumento 5 - Coordenador avalia ...</option>
            </select><br /><br />
            
        	
            <button class="btn-default float-right" type="submit" name="enviar" onclick="document.getElementById('box').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">Salvar</button>
            
            <button class="btn-default float-right" type="reset" name="cancelar" onclick="document.getElementById('box').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">Cancelar</button>        	        
            
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
	<div id="header"></div>
    <div id="content">
    <br />
    	
        <span class="btn-novo-grande"><a href="../Controller/questionarioController.php?action=new"  title="Novo Questionário">Novo Questionário</a></span>
        <h3>Questionários Cadastrados</h3>
        
        <div id="questionarios">
        	<table>
            	<tr>
                	<th>Id</th>
                    <th>Nome</th>
                    <th>Inst.</th>
                    <th>Criado em</th>
                    <th>Opções</th>
                </tr>
                <?php
					$result = $questionarioDAO->listAll();
					foreach ($result as $registro) {
						echo "<tr>";
                		echo "<td>".$registro["id"]."</td>";
						echo "<td>".$registro["descricao"]."</td>";
						echo "<td>".$registro["instrumento_id"]."</td>";
						echo "<td>".datetime_to_ptbr($registro["data_criacao"])."</td>";
						//echo "<td>".$registro["data_criacao"]."</td>";
						echo "<td><a href='../Controller/questionarioController.php?action=edit&id=".$registro["id"]."'>Editar</a></td>";
						echo "<td><a href='../Controller/questionarioController.php?action=delete&id=".$registro["id"]."'>Excluir</a></td>";
						echo "</tr>";
						
						//print_r($registro);
					}
		
				?>
                <!--<tr>
                	<td>1</td>
                    <td>Questionario de Filosofia</td>
                    <td>15/02/2011</td>
                    <td>editar | excluir</td>
                </tr>
                <tr>
                	<td>1</td>
                    <td>Questionario de Filosofia</td>
                    <td>15/02/2011</td>
                    <td>editar | excluir</td>
                </tr>
                <tr>
                	<td>1</td>
                    <td>Questionario de Filosofia</td>
                    <td>15/02/2011</td>
                    <td>editar | excluir</td>
                </tr>-->
            
            </table>
        
        </div>
        
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;2011 - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
</body>
</html>