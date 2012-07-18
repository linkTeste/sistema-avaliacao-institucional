<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
require_once '../system/application/models/dao/Log.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Funcionario.php';
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
}else{
	header("Location: login.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Acessos</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
	
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "..Utils/server_processing.php"
				} );
			} );
		</script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.js"></script>

</head>

<body style="background: #fafafa;">





<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>	
	
	
	
<?php } ?>

<div id="wrapper" class="container">

	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>       
    
    <div class="white">
		<br />
        

        <h3>&Uacute;ltimos Acessos</h3>
        
        <div id="questionarios">
        
        	<table>
            	<tr>
<!--                 	<th>ID</th> -->
                    <th>FOTO</th>
                    <th>NOME</th>
                    <th>N&Iacute;VEL</th>
                    <th>IP</th>
                    <th>HOST</th>
                    <th>&Uacute;LTIMO ACESSO EM</th>
                </tr>
                <?php
                	$lista = new Log();
                	$lista->alias("l");
                	
                	$prof = new Professor();
                	$u = new Usuario();
                	$a = new Aluno();
                	$f = new Funcionario();
                	
                	$lista->join($prof, 'LEFT', 'prof', "usuario", "id");
                	$lista->join($u, 'LEFT', 'u', "usuario", "id");
                	$lista->join($a, 'LEFT', 'a', "usuario", "ra");
                	$lista->join($f, 'LEFT', 'f', "usuario", "id");
                	
                	$lista->select("l.id, l.usuario, l.hora, l.ip, l.tipoUsuario, 
                	prof.nome as prof_nome, prof.id as prof_id, 
                	u.id as u_id, u.nome as u_nome,
                	a.ra as a_id, a.nome as a_nome,
                	f.id as f_id, f.nome as f_nome");
                	$lista->where("l.usuario != '1'");
                	$lista->order("id DESC");
                	$lista->find();
					while( $lista->fetch()) {
						
						$host = gethostbyaddr($lista->ip); 
						echo "<tr>";
// 						echo "<td style='width: 5%'>".$lista->getId()."</td>";

						if($lista->tipoUsuario == "Admin"){
							echo "<td style='width: 5%'><img src='".pegaImagem($lista->u_id)."' alt='Foto do Usuario' width='32' height='32'/></td>";
							echo "<td style='width: 40%'>".utf8_encode(stringToUpper($lista->u_nome))."</td>";
						}else if($lista->tipoUsuario == "Aluno"){
						 	echo "<td style='width: 5%'><img src='".pegaImagem($lista->a_id)."' alt='Foto do Usuario' width='32' height='32'/></td>";
						 	echo "<td style='width: 40%'>".utf8_encode($lista->a_nome)."</td>";
						}else if($lista->tipoUsuario == "Funcionario"){
						 	echo "<td style='width: 5%'><img src='".pegaImagem($lista->f_id)."' alt='Foto do Usuario' width='32' height='32'/></td>";
						 	echo "<td style='width: 40%'>".utf8_encode($lista->f_nome)."</td>";
						}else{
							echo "<td style='width: 5%'><img src='".pegaImagem($lista->prof_id)."' alt='Foto do Usuario' width='32' height='32'/></td>";
							echo "<td style='width: 40%'>".utf8_encode($lista->prof_nome)."</td>";
						}		
									
						echo "<td style='width: 10%'>".utf8_encode($lista->tipoUsuario)."</td>";
						echo "<td style='width: 10%'>".utf8_encode($lista->ip)."</td>";
						echo "<td style='width: 10%'>".utf8_encode($host)."</td>";
						
						echo "<td style='width: 15%'><img src='".isDayOrNight($lista->hora)."' width='32' height='32'/>&nbsp;&nbsp;&nbsp;".datetime_to_ptbr($lista->hora)."</td>";	
						echo "</tr>";
					}

		
				?>
               
            
            </table>
        
        </div>
        </div><!-- fecha div white -->
        
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
</html>
