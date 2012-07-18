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

$descricao = "";
 
if(isset($_SESSION["s_processo"])){
	$processo = unserialize($_SESSION["s_processo"]);
	//debug
	//print_r($questionario);
	$id = $processo->getId();
	$descricao = $processo->getDescricao();
	
	if($edit == true){
		$inicio = datetime_to_ptbr($processo->getInicio());
		$fim = datetime_to_ptbr($processo->getFim());
	}else{
		$inicio = "";
		$fim = "";
	}	
	
	
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
<link type="text/css"
	href="css/unicampo-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.checkbox.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script>
	$(function() {
		
		//adiciona o timepicker
		$("#input-inicio").datetimepicker({
			monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
			timeText: 'Hora',
			hourText: 'Hora',
			minuteText: 'Minuto',
			currentText: 'Agora',
			closeText: 'Pronto',
			dateFormat: 'dd/mm/yy',
			timeFormat: 'hh:mm:ss'}
			);
		$("#input-fim").datetimepicker({
			monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
			timeText: 'Hora',
			hourText: 'Hora',
			minuteText: 'Minuto',
			currentText: 'Agora',
			closeText: 'Pronto',
			dateFormat: 'dd/mm/yy',
			timeFormat: 'hh:mm:ss'}
			);

		$('input:radio').checkbox();
	});
	</script>
	
	<?php if(($new == true) || $edit == true){	?>
<script type="text/javascript">
$(document).ready(function() {
	ativaBlackout();
	ativaPopup();
	verificaSize();
});
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
		
		?>
    		<form action="../Controller/processoController.php?action=save" id="form-questionario" method="post">
        	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
        	
        	<label for="descricao">Descri&ccedil;&atilde;o</label><br />
        	<input type="text" name="descricao" value="<?php echo $descricao;?>"/><br /><br /><br />
            
            <label for="input-inicio">Data inicial:</label><br />
			<input type="text" name="input-inicio" id="input-inicio" value="<?php echo $inicio;?>" readonly="readonly"/><br /><br /><br />
            
            <label for="input-fim">Data final:</label><br />
            <input type="text" name="input-fim" id="input-fim" value="<?php echo $fim;?>" readonly="readonly"/><br /><br /><br />
            <br /><br />
            
                    
        	
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
	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>      
    
    <div class="white">
    <br />

        <a href="../Controller/processoController.php?action=new"  title="Novo Processo de Avalia&ccedil;&atilde;o" class="botao_right botaoGoogleBlue">Novo Processo</a>

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
                	$lista->order("id DESC");
                	$lista->find();
					while( $lista->fetch()) {
						echo "<tr>";
						echo "<td style='width: 5%'>".$lista->getId()."</td>";
						echo "<td style='width: 40%'>".$lista->getDescricao()."</td>";
						echo "<td style='width: 15%'>".datetime_to_ptbr($lista->getInicio())."</td>";
						echo "<td style='width: 15%'>".datetime_to_ptbr($lista->getFim())."</td>";
						echo "<td style='width: 15%'>".datetime_to_ptbr($lista->getDataCriacao())."</td>";
						echo "<td style='width: 5%'><a href='../Controller/processoController.php?action=edit&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Editar Processo de Avalia&ccedil;&atilde;o'>Editar</a></td>";
						
						if($lista->getAvaliado() == "Avaliado"){
							echo "<td style='width: 5%'>&nbsp</td>";
						}else{
							echo "<td style='width: 5%'><a href='../Controller/processoController.php?action=delete&id=".$lista->getId()."' class='botao_right botaoGoogleRed' title='Remover Processo de Avalia&ccedil;&atilde;o'>Excluir</a></td>";
						}
						
// 						if($processo_avaliado == "Avaliado"){
//     						echo "<td style='width: 10%'>&nbsp</td>";    						
//     					}
//     					else{
//     						echo "<td style='width: 10%'><a href='../Controller/processoController.php?action=delete&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Remover Processo de Avalia&ccedil;&atilde;o'>Excluir</a></td>";
//     					}
						
						//echo "<td style='width: 5%'><a class='botao_right botaoGoogleGrey'><input name='radio.1' value='".$lista->getId()."' type='radio'>Off</a></td>";
						if($lista->getAtivo() == "Ativo"){
							echo "<td style='width: 5%'><a href='../Controller/processoController.php?action=ativar&id=".$lista->getId()."' class='botao_right botaoGoogleBlue' title='Desativar'>On</a></td>";
						}else{
							echo "<td style='width: 5%'><a href='../Controller/processoController.php?action=ativar&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Ativar'>Off</a></td>";
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
        </div><!-- fecha div white -->
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
<?php 
//fazer isso na home admin
//$_SESSION["aluno"] = serialize($aluno);
//$_SESSION["processo"] = serialize($processo);
$_SESSION["periodo"] = "2/2011";

?>
</html>
