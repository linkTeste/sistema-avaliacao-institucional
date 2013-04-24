<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/QuestionarioUsado.php';
require_once '../system/application/models/dao/Permissao.php';
require_once '../system/application/models/dao/Laboratorio.php';
require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

if(isset($_GET['status'])){
	$status = $_GET['status'];
	if($status == "sucesso"){
		$msgAvaliacao = "Avaliação Realizada com sucesso!";
	}
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
<title>Sistema de Avaliação Institucional - Página Inicial</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css"
	href="js/jqtransformplugin/jqtransform.css" />
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
<link type="text/css"
	href="css/unicampo-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<!-- <script type="text/javascript" src="js/jqtransformplugin/jquery.jqtransform.js"></script> -->
<script type="text/javascript">
	/*$(document).ready(function(){

    $('#gerenciar_avaliacoes').jqTransform();
    
	});
	*/
</script>
<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

</head>

<body style="background: #fafafa;">




<?php if(isset($_GET['status'])){	?>
	<div id="blackout"></div>
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
<?php if(isset($_GET['status'])){	?>
    <div id="status">
    	<div id="status_inside">
    		<h2><?php echo $msgAvaliacao?></h2>
        	<span class="btn-default">
        		<a href='javascript:;' onclick="document.getElementById('status').style.display='none';document.getElementById('blackout').style.display='none';document.getElementById('status').style.zIndex='0';">OK</a>
        	</span>
       	</div>
    </div>
<?php } ?>
	<div id="header">
		<div id="header_logo"></div>
	</div>
    <div id="content">
    <?php include_once 'inc/menu_admin_inc.php';?>      
    
    <div class="white">
    	<h3>Definir Question&aacute;rios</h3>
    	<p>Para cada tipo de usu&aacute;rio escolha o question&aacute;rio que ser&aacute; usado.</p>
    	
    	<form action="../Controller/questionarioController.php?action=definirQuestionario" id="gerenciar_avaliacoes" method="post">
    	<input type="hidden" name="id" value="<?php //echo $id; ?>"/>
    	<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Aluno</a></li>
				<li><a href="#tabs-2">Professor</a></li>
				<li><a href="#tabs-3">Coordenador</a></li>
				<li><a href="#tabs-4">Funcion&aacute;rio</a></li>
			</ul>
	
			<div id="tabs-1">
			
				<div id="avaliacao_box">
					<label>Disciplinas e Professores:</label>
					<div id="select" class="botaoGoogleGrey">
					<input type="hidden" name="tipo[]" value="Aluno"/>
					<input type="hidden" name="subtipo[]" value="Professor/Disciplina"/>
					<select name="quest[]">
					<?php 
	    			$lista_questionarios = new Questionario(); 
	    			$lista_questionarios->tipo = "Aluno";
	    			$lista_questionarios->subtipo ="Professor/Disciplina";
	    			$lista_questionarios->find();    			 
	    			while( $lista_questionarios->fetch() ) {
	    				//descobrir uma forma de marcar a opcao salva como selected
	    			?>
	    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
	    			
	    			<?php
	    			}
	    			?>    		
					</select>
					</div>
				</div>
				
				<div id="avaliacao_box">
					<label>Curso e Coordena&ccedil;&atilde;o:</label>
					<div id="select" class="botaoGoogleGrey">
					<input type="hidden" name="tipo[]" value="Aluno"/>
					<input type="hidden" name="subtipo[]" value="Curso/Coordenador"/>
					<select name="quest[]">
					<?php 
	    			$lista_questionarios = new Questionario(); 
	    			$lista_questionarios->tipo = "Aluno";
	    			$lista_questionarios->subtipo ="Curso/Coordenador";
	    			$lista_questionarios->find();    			 
	    			while( $lista_questionarios->fetch() ) {
	    				//descobrir uma forma de marcar a opcao salva como selected
	    			?>
	    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
	    			
	    			<?php
	    			}
	    			?>    		
					</select>
					</div>
				</div>
				
				<div id="avaliacao_box">
					<label>Institui&ccedil;&atilde;o:</label>
					<div id="select" class="botaoGoogleGrey">
					<input type="hidden" name="tipo[]" value="Aluno"/>
					<input type="hidden" name="subtipo[]" value="Instituição"/>
					<select name="quest[]">
					<?php 
	    			$lista_questionarios = new Questionario(); 
	    			$lista_questionarios->tipo = "Aluno";
	    			$lista_questionarios->subtipo = utf8_decode("Instituição");
	    			$lista_questionarios->find();    			 
	    			while( $lista_questionarios->fetch() ) {
	    				//descobrir uma forma de marcar a opcao salva como selected
	    			?>
	    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
	    			
	    			<?php
	    			}
	    			?>    		
					</select>
					</div>
				</div>
				
				<?php 
				$laboratorios = new Laboratorio();
				$laboratorios->find();
				while ($laboratorios->fetch()) {
					
				?>
				<div id="avaliacao_box">
					<label>Laboratório de <?php echo utf8_encode($laboratorios->getNome());?>:</label>
					<div id="select" class="botaoGoogleGrey">
					<input type="hidden" name="tipo[]" value="Aluno"/>
					<input type="hidden" name="subtipo[]" value="<?php echo utf8_encode("Lab_".$laboratorios->getNome());?>"/>
					<select name="quest[]">
					<?php 
	    			$lista_questionarios = new Questionario(); 
	    			$lista_questionarios->tipo = "Aluno";
	    			$lista_questionarios->subtipo = utf8_decode("Lab_").$laboratorios->getNome();
	    			$lista_questionarios->find();    			 
	    			while( $lista_questionarios->fetch() ) {
	    				//descobrir uma forma de marcar a opcao salva como selected
	    			?>
	    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
	    			
	    			<?php
	    			}
	    			?>    		
					</select>
					</div>
				</div>
				<?php }//fecha WHILE?>
				
			</div>
		
		<div id="tabs-2">
		
			<div id="avaliacao_box">
				<label>Auto-avalia&ccedil;&atilde;o:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Professor"/>
				<input type="hidden" name="subtipo[]" value="Auto-avaliação-professor"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios2 = new Questionario(); 
    			$lista_questionarios2->tipo = "Professor";
    			$lista_questionarios2->subtipo = utf8_decode("Auto-avaliação-professor");
    			$lista_questionarios2->find();    			 
    			while( $lista_questionarios2->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios2->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios2->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>
			
			<div id="avaliacao_box">
				<label>Coordenador:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Professor"/>
				<input type="hidden" name="subtipo[]" value="Coordenador"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios2 = new Questionario(); 
    			$lista_questionarios2->tipo = "Professor";
    			$lista_questionarios2->subtipo ="Coordenador";
    			$lista_questionarios2->find();    			 
    			while( $lista_questionarios2->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios2->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios2->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>
			
			<div id="avaliacao_box">
				<label>Institui&ccedil;&atilde;o:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Professor"/>
				<input type="hidden" name="subtipo[]" value="Instituição"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios2 = new Questionario(); 
    			$lista_questionarios2->tipo = "Professor";
    			$lista_questionarios2->subtipo = utf8_decode("Instituição");
    			$lista_questionarios2->find();    			 
    			while( $lista_questionarios2->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios2->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios2->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>
			
			<?php 
				$laboratorios = new Laboratorio();
				$laboratorios->find();
				while ($laboratorios->fetch()) {
					
				?>
				<div id="avaliacao_box">
					<label>Laboratório de <?php echo utf8_encode($laboratorios->getNome());?>:</label>
					<div id="select" class="botaoGoogleGrey">
					<input type="hidden" name="tipo[]" value="Professor"/>
					<input type="hidden" name="subtipo[]" value="<?php echo utf8_encode("Lab_".$laboratorios->getNome());?>"/>
					<select name="quest[]">
					<?php 
	    			$lista_questionarios = new Questionario(); 
	    			$lista_questionarios->tipo = "Professor";
	    			$lista_questionarios->subtipo = utf8_decode("Lab_").$laboratorios->getNome();
	    			$lista_questionarios->find();    			 
	    			while( $lista_questionarios->fetch() ) {
	    				//descobrir uma forma de marcar a opcao salva como selected
	    			?>
	    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
	    			
	    			<?php
	    			}
	    			?>    		
					</select>
					</div>
				</div>
				<?php }//fecha WHILE?>
		</div>
		
		<div id="tabs-3">
		
			<div id="avaliacao_box">
				<label>Auto-avalia&ccedil;&atilde;o:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Coordenador"/>
				<input type="hidden" name="subtipo[]" value="Auto-avaliação-coordenador"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios = new Questionario(); 
    			$lista_questionarios->tipo = "Coordenador";
    			$lista_questionarios->subtipo = utf8_decode("Auto-avaliação-coordenador");
    			$lista_questionarios->find();    			 
    			while( $lista_questionarios->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>
			
			<div id="avaliacao_box">
				<label>Docentes:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Coordenador"/>
				<input type="hidden" name="subtipo[]" value="Docente"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios = new Questionario(); 
    			$lista_questionarios->tipo = "Coordenador";
    			$lista_questionarios->subtipo ="Docente";
    			$lista_questionarios->find();    			 
    			while( $lista_questionarios->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>
			
			<div id="avaliacao_box">
				<label>Institui&ccedil;&atilde;o:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Coordenador"/>
				<input type="hidden" name="subtipo[]" value="Instituição"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios = new Questionario(); 
    			$lista_questionarios->tipo = "Coordenador";
    			$lista_questionarios->subtipo = utf8_decode("Instituição");
    			$lista_questionarios->find();    			 
    			while( $lista_questionarios->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>
			
			
		
	</div>
	<div id="tabs-4">
		
			<div id="avaliacao_box">
				<label>Institui&ccedil;&atilde;o:</label>
				<div id="select" class="botaoGoogleGrey">
				<input type="hidden" name="tipo[]" value="Funcionário"/>
				<input type="hidden" name="subtipo[]" value="Instituição"/>
				<select name="quest[]">
				<?php 
    			$lista_questionarios = new Questionario(); 
    			$lista_questionarios->tipo = utf8_decode("Funcionário");
    			$lista_questionarios->subtipo = utf8_decode("Instituição");
    			$lista_questionarios->find();    			 
    			while( $lista_questionarios->fetch() ) {
    				//descobrir uma forma de marcar a opcao salva como selected
    			?>
    			<option value="<?php echo $lista_questionarios->getId(); ?>" <?php echo $selected;?>><?php echo utf8_encode($lista_questionarios->getDescricao());?></option>
    			
    			<?php
    			}
    			?>    		
				</select>
				</div>
			</div>			
		
	</div>
</div>

<br />
<input type="submit" value="Salvar" name="enviar" class="botaoGoogleBlue" style="/*margin-left: 440px;*/margin-left: 47%;" />
<br />
</form>	
</div><!--  fecha div white -->

    	
<!--     	<form action="" id="gerenciar_avaliacoes" method="post"> -->
    	<?php
    	
    	
    	$turma = new Turma();
    	$turma->where("periodo_letivo = '".$periodo_atual."'");
    	//$turma->where("curso = '".$curso_escolhido."'");
    	$turma->find();
    	
    	while( $turma->fetch() ) {
    		//pega o id do professor
    		$id_professor = $turma->getProfessorId();
    		
    		//pega o professor
    		$professor = new Professor();
    		$professor->get($id_professor);
    		
    		?>
    		<!-- 
    		<div id="avaliacao_box">
    		<div class="div700">
    		<div class="photo">
    		<img src="<?php //echo pegaImagem($professor->getId()); ?>" alt="Foto do Professor" />
    		</div>
    		<div class="description">
    		<h4><span>Disciplina: </span><?php //echo $turma->getIdTurma()." - ".utf8_encode($turma->getNomeDisciplina()); ?></h4>
    		<h4><span>Professor: </span><?php //echo strtoupper(utf8_encode($professor->getNome())); ?></h4>
    		</div>
    		</div>
    		<input type="hidden" name="turmas[]" value="<?php //echo $turma->getIdTurma(); ?>"></input>
    		<div id="select" class="botaoGoogleGrey">
    		<select name="quest[]">
    			<option value="0">Selecione</option>
    			
    	   -->
    			<?php 
    			
    			
    			$lista_questionarios = new Questionario(); 
    			$lista_questionarios->instrumentoId = 1;		//assim lista s� os questionarios das turmas(instrumento 1)
    			$lista_questionarios->find();    			 
    			while( $lista_questionarios->fetch() ) {
    				$quesId = $turma->getQuestionarioId();
    				//echo "quesID: ".$quesId;
    				if($quesId == $lista_questionarios->getId()){
    					$selected = "selected=\"selected\"";
    				}
    				else{
    					$selected = "";
    				}
    			?>
    			<!-- 
    			<option value="<?php //echo $lista_questionarios->getId(); ?>" <?php //echo $selected;?>><?php //echo $lista_questionarios->getDescricao();?></option>
    			 -->
    			<?php
    			}
    			?>
    		<!-- 	
    		</select>
    		</div>
    		</div>
    		
    		 -->
    		<?php 

    	}
    	
    	?>
                
        
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
</html>
