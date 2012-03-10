<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/ProcessoAvaliacao.php';
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
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

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
<div id="wrapper" class="container">
<?php if(($new == true) || $edit == true){	?>
    <div id="box">
    	<div id="box_inside">
        <?php
		$descricao = "";
      	//if($edit == true){
      	
      		if(isset($_SESSION["processo"])){
        	//$questionario = new questionario;
        	$processo = unserialize($_SESSION["processo"]);
        	//debug
        	//print_r($questionario);
        	$id = $processo->getId();
        	$descricao = $processo->getDescricao();
        	$inicio = $processo->getInicio();
        	$fim = $processo->getFim();
        	
        	
        	}
      	//}
		?>
    		<form action="../Controller/processoController.php?action=save" id="form-questionario" method="post">
        	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
        	
        	<label for="descricao">Descri&ccedil;&atilde;o</label><br />
        	<input type="text" name="descricao" value="<?php echo $descricao;?>"/><br /><br /><br />
            
            <label for="input-inicio">Data inicial:</label><br />
			<input type="text" name="input-inicio" value="<?php echo $inicio;?>"/><br /><br /><br />
            
            <label for="input-fim">Data final:</label><br />
            <input type="text" name="input-fim" value="<?php echo $fim;?>"/><br /><br /><br />
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

        <a href="../Controller/processoController.php?action=new"  title="Novo Processo de Avalia&ccedil;&atilde;o" class="botao_right botaoGoogleBlue">Novo Processo</a>

		<br />
		<br />
        <h3>Processos de Avalia&ccedil;&atilde;o Cadastrados</h3>
        
        <div id="questionarios">
        	<table>
            	<tr>
                	<th>ID</th>
                    <th>NOME</th>
                    <th>DATA INICIAL</th>
                    <th>DATA FINAL</th>
                    <th>MODIFICADO EM</th>
                    <th colspan="2"></th>
                </tr>
                <?php
                	$lista = new ProcessoAvaliacao();
                	$lista->find();
					while( $lista->fetch()) {
						echo "<tr>";
						echo "<td>".$lista->getId()."</td>";
						echo "<td>".$lista->getDescricao()."</td>";
						echo "<td>".date_to_ptbr($lista->getInicio())."</td>";
						echo "<td>".date_to_ptbr($lista->getFim())."</td>";
						echo "<td>".datetime_to_ptbr($lista->getDataCriacao())."</td>";
						echo "<td style='width: 10%'><a href='../Controller/processoController.php?action=edit&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Editar Processo de Avalia&ccedil;&atilde;o'>Editar</a></td>";
						
						if($processo_avaliado == "Avaliado"){
    						echo "<td style='width: 10%'>&nbsp</td>";    						
    					}
    					else{
    						echo "<td style='width: 10%'><a href='../Controller/processoController.php?action=delete&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Remover Processo de Avalia&ccedil;&atilde;o'>Excluir</a></td>";
    					}
    					echo "</tr>";
					}
                
					
// 					foreach ($result as $registro) {
// 						echo "<tr>";
//                 		echo "<td>".$registro["id"]."</td>";
// 						echo "<td><a href='../Controller/questionarioController.php?action=details&id=".$registro["id"]."'>".$registro["descricao"]."</a></td>";
// 						echo "<td>".$registro["instrumento_id"]."</td>";
// 						echo "<td>".datetime_to_ptbr($registro["data_criacao"])."</td>";
// 						//echo "<td>".$registro["data_criacao"]."</td>";
// 						echo "<td><a href='../Controller/questionarioController.php?action=edit&id=".$registro["id"]."'>Editar</a></td>";
// 						echo "<td><a href='../Controller/questionarioController.php?action=delete&id=".$registro["id"]."'>Excluir</a></td>";
// 						echo "</tr>";
						
// 						//print_r($registro);
// 					}
		
				?>
               
            
            </table>
        
        </div>
        
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;<?php echo date("Y");?> - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
</body>
<?php 
//fazer isso na home admin
//$_SESSION["aluno"] = serialize($aluno);
//$_SESSION["processo"] = serialize($processo);
$_SESSION["periodo"] = "2/2011";

?>
</html>
