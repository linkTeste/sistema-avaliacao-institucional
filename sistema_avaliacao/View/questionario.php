<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Página Inicial</title>
<link href="blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="scrollbar.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth' rel='stylesheet' type='text/css' />

</head>

<body>
<?php if(($add == true) || $edit == true){	?>
<div id="blackout"></div>
<?php } ?>
	
<div id="wrapper" class="container">
<?php if(($add == true) || $edit == true){	?>
    <div id="box">
    	<div id="box_inside">
        <?php
		$questionario = $crud->dbSelect("questionario", "id", $id); 
		$quest_nome;
		$quest_inst;
		
		foreach ($questionario as $registro) {
			$quest_nome = $registro["descricao"];
			$quest_inst = $registro["instrumento_id"];
		}
		?>
    		<form action="questionarioController?action=new" id="form-questionario" method="post">
        	<label for="input-name">Nome do questionário:</label><br />
            <input type="text" name="input-name" value="<?php echo $quest_nome ?>"/><br /><br /><br />
            <label for="select-instrumento">Instrumento:</label><br />
            <select name="select-instrumento">
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
    	
        <span class="btn-novo-grande"><a href="adm_questionario.php?action=add"  title="Novo Questionário">Novo Questionário</a></span>
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
					$result = $crud->dbSelect("questionario");
		
					foreach ($result as $registro) {
						echo "<tr>";
                		echo "<td>".$registro["id"]."</td>";
						echo "<td>".$registro["descricao"]."</td>";
						echo "<td>".$registro["instrumento_id"]."</td>";
						echo "<td>".datetime_to_ptbr($registro["data_criacao"])."</td>";
						echo "<td><a href='adm_questionario.php?action=edit&id=".$registro["id"]."'>Editar</a></td>";
						echo "<td><a href='adm_questionario.php?action=delete&id=".$registro["id"]."'>Excluir</a></td>";
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