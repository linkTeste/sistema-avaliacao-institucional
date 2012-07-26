<?php
//obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/QuestionarioHasQuestao.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
require_once '../system/application/models/dao/Log.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/Turma.php';
require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

if(isset($_SESSION["s_aluno"])){
	$str = $_SESSION["s_aluno"];
	if($str instanceof Aluno){
		$aluno = $str;
	}else{
		$aluno = unserialize($_SESSION["s_aluno"]);
	}

	$ra = $aluno->getRa();
}





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Relatorios</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<?php include_once 'inc/theme_inc.php';?>
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery.selectboxes.js"></script>

</head>

<body style="background: #fafafa;">









<?php if(($new == true) || $edit == true){	?>
	<div id="blackout"></div>
	
	
	
	
	
	
	
		
	
	
	
<?php } ?>

<div id="wrapper" class="container">

	<?php include_once 'inc/header_inc.php';?> 
    <div id="content">
    <?php include_once 'inc/menu_aluno_inc.php';?>       
    
    <div class="white">
		<br />
        

        <h3>Relat&oacute;rios</h3>
        
<!--         <div id="questionarios"> -->

        		<div id="chart_div" style="width: 900px; height: 300px;"></div>
<!--       		<div id="chart1" style="width: 900px; height: 300px;"></div>
        		<div id="chartDisciplinasMelhoresNotas" style="width: 900px; height: 300px;"></div>
        		<div id="chartDisciplinasMelhoresNotasCombo" style="width: 900px; height: 300px;"></div>
        		<div id="chartDisciplinasPioresNotas" style="width: 900px; height: 300px;"></div>
        		<div id="chartCursosGeralNotas" style="width: 900px; height: 300px;"></div>
        		<div id="chartLabsGeralNotas" style="width: 900px; height: 300px;"></div> -->
        		<div id="dashboard">
      
		            <div id="control1"></div>
		            <div id="control2"></div>
		            <div id="control3"></div>
		          	<br />
		          	<br />         
		            <div id="chart1"></div>
		            <div id="chart2"></div>
		            <div id="chart3"></div>
          
    		    </div>
        	
        
<!--         </div> -->
        </div><!-- fecha div white -->
        
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
</body>
</html>
