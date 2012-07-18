<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/MensagemSistema.php';


require '../Utils/functions.php';

session_start();

if(isset($_GET['url'])){
	$url = $_GET['url'];
	$action = "../Controller/loginController.php?action=logar&url=".$url;
}
else{
	$action = "../Controller/loginController.php?action=logar";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Avaliação Institucional - Login</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="js/jqtransformplugin/jqtransform.css" type="text/css" media="all" /> -->
<script type="text/javascript" src="scripts/jquery-1.4.3.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="scripts/jquery.validate.js"></script>
<script type="text/javascript" src="scripts/additional-methods.js"></script>
<script type="text/javascript" src="http://updateyourbrowser.net/asn.js"> </script>


<!--[if lte IE 6]> 
<script src="scripts/DD_belatedPNG_0.0.8a.js" type="text/javascript"></script> 
<script type="text/javascript"> 
DD_belatedPNG.fix('#faqSection, #footer_middle, img'); 
</script> 
<![endif]--> 
<script type="text/javascript">
$().ready(function() {
	
	$("#inscricao").validate({
		errorClass: "warning",
		rules: {
			usuario: {
				required: true
			},
			senha: {
				required: true
			},
			
		},
		messages: {
			usuario: "Por favor informe seu usuário",
			senha: "Por favor informe sua senha"			
		}
	});
	
});
</script>

</head>

<body>
	<div id="wrapper" class="container">
    	<div id="header">
        	<div id="header_logo"></div>
        </div>
        <div id="content_login">
<!--         	<img src="logo-login.png" alt="Portal do Aluno - Acesso restrito" /> -->
			
            <?php
			if(isset($_GET["msg_e"])){
				$msg_code = $_GET["msg_e"];
				$msg = new MensagemSistema();
				$msg->get("codigo", $msg_code);
			?>
			<script type="text/javascript" language="javascript">  
	$().ready(function() {
      $("#form_login").effect("shake", { times:4 }, 100);
  });
  </script>
			
            <div id="msg_error">
            	<h3><?php echo utf8_encode($msg->getTexto()); ?></h3>           
            </div>            
            <?php
			}
			?>
            
            <?php
			if(isset($_GET["msg"])){
				$msg_code = $_GET["msg"];
				$msg = new MensagemSistema();
				$msg->get("codigo", $msg_code);
				
			?>
            <div id="msg">
            	<h3><?php echo utf8_encode($msg->getTexto()); ?></h3>            
            </div>            
            <?php
			}
			?>
        	<form id="form_login" class="jqtransform" action="<?php echo $action ?>" method="post">
            	
            	<div class="rowElem">               
                	<label for="login">Usu&aacute;rio</label>
                	<input type="text" id="login" name="usuario" />
                </div>
                <div class="rowElem">
                	<label for="senha">Senha</label>
                	<input type="password" id="senha" name="senha" />
                </div>
                <br />
                <div class="rowElem button_bar">
                	<input type="submit" value="Enviar" name="enviar"/>
                	<input type="reset" value="Cancelar" name="cancelar"/>
                </div>                            
                           
            </form>
            <a href="recuperar_senha.php" id="recupera_senha" title="Clique aqui para recuperar sua senha">Perdeu a senha?</a>
        </div>
        <div id="footer" class="span-24">
        	<div id="footer_endereco" class="span-12">
        		
        	</div>
        <div id="footer_realizacao" class="span-10 last">
        
        </div>
        </div>
    
    </div>

</body>
</html>
