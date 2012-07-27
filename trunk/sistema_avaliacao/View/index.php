<?php

///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';

require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}


if(isset($_SESSION["s_aluno"]) && $_SESSION["s_aluno"] != "" ){
	$str = $_SESSION["s_aluno"];
	if($str instanceof Aluno){
		$aluno = $str;		
	}else{
		$aluno = unserialize($_SESSION["s_aluno"]);		
	}	
}else{
	//sessao expirou, redireciona pro login
	header("Location: login.php");
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
<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.raty.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>

<link rel="stylesheet" href="js/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="js/nivo-slider/themes/theme2/default.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/nivo-slider/jquery.nivo.slider.js"></script>


<?php 
$tutorial = true;
if($tutorial == true){	?>
<script type="text/javascript">
$(window).load(function() {
	$('#slider').nivoSlider({
		effect: 'fade', // Specify sets like: 'random,fold,fade,sliceDown'
		slices: 15, // For slice animations
		boxCols: 8, // For box animations
		boxRows: 4, // For box animations
		animSpeed: 600, // Slide transition speed
		pauseTime: 3000, // How long each slide will show
		startSlide: 0, // Set starting Slide (0 index)
		directionNav: true, // Next & Prev navigation
		directionNavHide: false, // Only show on hover
		controlNav: false, // 1,2,3... navigation
		controlNavThumbs: false, // Use thumbnails for Control Nav
		pauseOnHover: true, // Stop animation while hovering
		manualAdvance: false, // Force manual transitions
		prevText: 'Anterior', // Prev directionNav text
		nextText: 'Proximo', // Next directionNav text
		randomStart: false, // Start on a random slide
		beforeChange: function(){
		}, // Triggers before a slide transition
		afterChange: function(){
		}, // Triggers after a slide transition
		slideshowEnd: function(){
		}, // Triggers after all slides have been shown
		lastSlide: function(){
			//$('#slider').data('nivoslider').stop();
			removePopup();
			//document.getElementById("#slider").style.display = 'none';
		}, // Triggers when last slide is shown
		afterLoad: function(){
		} // Triggers when slider has loaded
	});
});
</script>

<script type="text/javascript">
$(document).ready(function() {
	ativaBlackout();
	ativaPopup();
	verificaSize();
	disableButtonRight();
});
</script>
<?php }?>
<?php include_once 'inc/ie_bugfixes_inc.php';?>

</head>

<body style="background: #fafafa;">
<div id="overlay"></div>
	<!-- 
	<div id="menu_usuario">
		<ul>
			<li><a href="http://www.faculdadeunicampo.edu.br/" target="_blank">Faculdade
					Unicampo</a></li>
			<li><a href="http://mail.faculdadeunicampo.edu.br/" target="_blank">E-mail
					Unicampo</a></li>
			<li id="username">Ol&aacute;, <?php //echo $aluno->getNome();?> - <a
				href="../Controller/loginController.php?action=logout">Sair</a>
			</li>
			
		</ul>
	</div>
	 -->
	<div id="wrapper" class="container">
<?php 
if($tutorial == true){	?>	
	<div id="box">
	<div id="box_inside">
	<h2>TUTORIAL</h2>
		<div class="slider-wrapper theme-default">
            <div id="slider" class="nivoSlider">
                <img src="css/images/tut1.png" alt="" />
                <img src="css/images/tut2.png" alt="" />
                <img src="css/images/tut3.png" alt="" />
                <img src="css/images/tut5.png" alt="" />
            </div>            
        </div>
	</div>
	</div>
	<?php }
	$tutorial = false;
	?>
	
		<?php include_once 'inc/header_inc.php';?>
		<div id="content">
			<?php include_once 'inc/menu_aluno_inc.php';?>

			<div id="apresentacao">
<!-- 				<p>Caro aluno,</p> -->
<!-- 				<p>Solicitamos sua participação para auxiliar na avaliação de -->
<!-- 					desempenho dos docentes por entender-se que ela é indispensável -->
<!-- 					para a melhoria contínua das atividades desenvolvidas em sala de -->
<!-- 					aula.</p> -->
<!-- 				<p>Para tanto, é necessário que sua opinião não se baseie em -->
<!-- 					impressões precipitadas ou ditadas pela emoção. Procure avaliar -->
<!-- 					o professor nos quesitos propostos, baseando sua resposta no que é -->
<!-- 					mais constante no comportamento do professor.</p> -->
				
				<p>Prezado aluno,</p>	
				<p>Solicitamos sua colaboração para a melhoria contínua das atividades desenvolvidas na Instituição.</p>
				<p>Para tanto, pedimos para que leia e responda com atenção as questões.</p>
			</div>
			<div id="escala_conceitos_home">
				<h3>Escala de Conceitos</h3>
				<div id="item_escala">
					<div id="texto_escala">
						Quando a questão <span>não for atendida</span>
					</div>
					<div class="star_escala">
						<ul>
							<li class="star-marked1 png_bg" title="Questão não atendida"></li>
							<li class="star-unmarked2 png_bg"
								title="Questão atendida em até 25% das vezes"></li>
							<li class="star-unmarked3 png_bg"
								title="Questão atendida em até 50% das vezes"></li>
							<li class="star-unmarked4 png_bg"
								title="Questão atendida em até 75% das vezes"></li>
							<li class="star-unmarked5 png_bg"
								title="Questão atendida em até 100% das vezes"></li>
						</ul>
					</div>
				</div>
				<div id="item_escala">
					<div id="texto_escala">
						Quando a questão <span>for atendida em até 25% das vezes</span>
					</div>
					<div class="star_escala">
						<ul>
							<li class="star-marked1 png_bg" title="Questão não atendida"></li>
							<li class="star-marked2 png_bg"
								title="Questão atendida em até 25% das vezes"></li>
							<li class="star-unmarked3 png_bg"
								title="Questão atendida em até 50% das vezes"></li>
							<li class="star-unmarked4 png_bg"
								title="Questão atendida em até 75% das vezes"></li>
							<li class="star-unmarked5 png_bg"
								title="Questão atendida em até 100% das vezes"></li>
						</ul>
					</div>
				</div>
				<div id="item_escala">
					<div id="texto_escala">
						Quando a questão <span>for atendida em até 50% das vezes</span>
					</div>
					<div class="star_escala">
						<ul>
							<li class="star-marked1 png_bg" title="Questão não atendida"></li>
							<li class="star-marked2 png_bg"
								title="Questão atendida em até 25% das vezes"></li>
							<li class="star-marked3 png_bg"
								title="Questão atendida em até 50% das vezes"></li>
							<li class="star-unmarked4 png_bg"
								title="Questão atendida em até 75% das vezes"></li>
							<li class="star-unmarked5 png_bg"
								title="Questão atendida em até 100% das vezes"></li>
						</ul>
					</div>
				</div>
				<div id="item_escala">
					<div id="texto_escala">
						Quando a questão <span>for atendida em até 75% das vezes</span>
					</div>
					<div class="star_escala">
						<ul>
							<li class="star-marked1 png_bg" title="Questão não atendida"></li>
							<li class="star-marked2 png_bg"
								title="Questão atendida em até 25% das vezes"></li>
							<li class="star-marked3 png_bg"
								title="Questão atendida em até 50% das vezes"></li>
							<li class="star-marked4 png_bg"
								title="Questão atendida em até 75% das vezes"></li>
							<li class="star-unmarked5 png_bg"
								title="Questão atendida em até 100% das vezes"></li>
						</ul>
					</div>
				</div>
				<div id="item_escala">
					<div id="texto_escala">
						Quando a questão <span>for atendida em até 100% das vezes</span>
					</div>
					<div class="star_escala">
						<ul>
							<li class="star-marked1 png_bg" title="Questão não atendida"></li>
							<li class="star-marked2 png_bg"
								title="Questão atendida em até 25% das vezes"></li>
							<li class="star-marked3 png_bg"
								title="Questão atendida em até 50% das vezes"></li>
							<li class="star-marked4 png_bg"
								title="Questão atendida em até 75% das vezes"></li>
							<li class="star-marked5 png_bg"
								title="Questão atendida em até 100% das vezes"></li>
						</ul>
					</div>
				</div>
			</div>

			<br />

			<!--<a href="avaliacoes.php" class="btn-comecar-avaliacao" title="Começar Avaliação"></a>-->
			<a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoes.php");?>" class="botao botaoGoogleBlue">Começar
				Avaliação</a>
			<div class="clear"></div>
			<br />
		</div>
		<?php include_once 'inc/footer_inc.php';?>
	</div>
</body>




<?php 

// $_SESSION["aluno"] = serialize($aluno);
// $_SESSION["processo"] = serialize($processo);
// $_SESSION["periodo"] = "2/2011";

?>
</html>
