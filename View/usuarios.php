<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

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
}else{
	header("Location: login.php");
}


if(isset($_SESSION["s_usuario_logado_permissoes"])){
	$usuario_logado_permissoes = $_SESSION["s_usuario_logado_permissoes"];
}

//fazer isso no index do admin
//pega dados do processo de avaliacao
// $processo = new ProcessoAvaliacao();
// $processo->get(1);
// $processo_avaliado = $processo->getAvaliado();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Usu&aacute;rios</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
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
		$descricao = "";
      	//if($edit == true){
      	
      		if(isset($_SESSION["s_usuario"])){
        	//$questionario = new questionario;
        	$usuario = unserialize($_SESSION["s_usuario"]);
        	//debug
        	//print_r($questionario);
        	
        	//$usuario = new Usuario();
        	
        	$id = $usuario->getId();
        	$nome = $usuario->getNome();
        	$login = $usuario->getLogin();
        	$email = $usuario->getEmail();        	        	
        	
        	}
        	if(isset($_SESSION["s_permissoes"])){
        		$permissoes_atuais = $_SESSION["s_permissoes"];
        		//print_r($permissoes_atuais);
        	}
        	
      	//}
		?>
    		<form action="../Controller/usuarioController.php?action=save" id="form-questionario" method="post">
        	<input type="hidden" name="id" value="<?php echo $id;?>"/>
        	
        	<label for="nome">Nome:</label><br />
        	<input type="text" name="nome" value="<?php echo utf8_encode($nome);?>"/><br /><br /><br />
            
            <label for="login">Login:</label><br />
			<input type="text" name="login" value="<?php echo $login;?>"/><br /><br /><br />
            
            <label for="email">Email:</label><br />
            <input type="text" name="email" value="<?php echo $email;?>"/><br /><br /><br />
            
            <fieldset id="user_permissoes">
            <?php 
            $permissoes = new Permissao();
            $permissoes->find();
            
            while( $permissoes->fetch()) {
            	$checked = "";
            	foreach ($permissoes_atuais as $value) {
            		if($value == $permissoes->id){
            			$checked = "checked='checked'";
            		}
            	}

            	?>
            	<label>
            	<input type="checkbox" value="<?php echo $permissoes->id;?>" name="permissoes[]" <?php echo $checked;?>/><?php echo utf8_encode($permissoes->nome);?>
            	</label><br />
            	
            <?php 	
            }
            ?>
            
            </fieldset>
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
		<?php include_once 'inc/header_inc.php';?>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>       
    
    <div class="white">
    <br />

        <a href="../Controller/usuarioController.php?action=new"  title="Novo Usu&aacute;rio" class="botao_right botaoGoogleBlue">Novo Usu&aacute;rio</a>

		<h3>Usu&aacute;rios Cadastrados</h3>
        
        <div id="questionarios">
        	<table>
            	<tr>
                	<th>ID</th>
                    <th>NOME</th>
                    <th>LOGIN</th>
                    <th>EMAIL</th>
                    <th>MODIFICADO EM</th>
                    <th colspan="2"></th>
                </tr>
                <?php
                	$lista = new Usuario();
                	$lista->id != 1;
                	$lista->find();
					while( $lista->fetch()) {
						if($lista->getLogin() != "admin"){
							echo "<tr>";
							echo "<td style='width: 5%'>".$lista->getId()."</td>";
							echo "<td style='width: 40%'>".utf8_encode($lista->getNome())."</td>";
							echo "<td style='width: 10%'>".utf8_encode($lista->getLogin())."</td>";
							echo "<td style='width: 20%'>".$lista->getEmail()."</td>";
							echo "<td style='width: 15%'>".datetime_to_ptbr($lista->getDataCriacao())."</td>";
							echo "<td style='width: 5%'><a href='../Controller/usuarioController.php?action=edit&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Editar Usuário'>Editar</a></td>";
								if($processo_avaliado == "Avaliado"){
									echo "<td style='width: 5%'>&nbsp</td>";
								}
								else{
									echo "<td style='width: 5%'><a href='../Controller/usuarioController.php?action=delete&id=".$lista->getId()."' class='botao_right botaoGoogleRed' title='Remover Usuário'>Excluir</a></td>";
								}
							echo "</tr>";
						}else{
							
						}
						
// 						if($lista->getLogin() != "admin"){
// 							echo "<td style='width: 5%'><a href='../Controller/usuarioController.php?action=edit&id=".$lista->getId()."' class='botao_right botaoGoogleGrey' title='Editar Usuário'>Editar</a></td>";
// 							if($processo_avaliado == "Avaliado"){
// 								echo "<td style='width: 5%'>&nbsp</td>";
// 							}
// 							else{
// 								echo "<td style='width: 5%'><a href='../Controller/usuarioController.php?action=delete&id=".$lista->getId()."' class='botao_right botaoGoogleRed' title='Remover Usuário'>Excluir</a></td>";
// 							}
// 						}
// 						else{
// 							echo "<td style='width: 5%'>&nbsp</td>";
// 							echo "<td style='width: 5%'>&nbsp</td>";
// 						}					
						
						
						
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
//$_SESSION["periodo"] = "2/2011";

?>
</html>
