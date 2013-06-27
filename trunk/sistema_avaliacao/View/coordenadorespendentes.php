<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/TurmaHasLaboratorio.php';
require_once '../system/application/models/dao/Laboratorio.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';

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

if(isset($_SESSION["s_periodo"])){
	$periodo_atual = $_SESSION["s_periodo"];
	// 	echo "periodo: ".$periodo_atual;
}else{
	header("Location: index.php");
}

if(isset($_SESSION["s_periodo"])){
	$cursos_coordenados = $_SESSION["s_cursos_coordenados"];
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
<link
	href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth'
	rel='stylesheet' type='text/css' />

<link type="text/css"
	href="css/unicampo-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/info_usuario.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {
	packages:["corechart"]});
	google.load('visualization', '1.1', {
		packages:['controls']});
		//google.setOnLoadCallback(drawChart);


</script>
<?php include_once 'inc/analytics_inc.php';?>
</head>

<body style="background: #fafafa;">


<?php if(isset($_GET['status'])){	?>
	<div id="blackout"></div>

	
<?php } ?>

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
	<?php include_once 'inc/header_inc.php';?>
    <div id="content">
    <?php include_once 'inc/menu_coord_inc.php';?>      
    
    <div class="white">
    <br />
    	<h3>Coordenadores com Avaliações Pendentes</h3>
    	<?php 
    	
    	$qtd_coordenadores;
    	$qtd_coordenadores_avaliaram = 0;
    	
    	if(isset($_POST["semestre-selecionado"]) && $_POST["semestre-selecionado"] != ""){
    		//filtro por semestre
    		$ss = $_POST["semestre-selecionado"];    		
    		$semestre_escolhido = utf8_decode($ss."º SEMESTRE");
    		$where_semestre = "and turma.serie = '".$semestre_escolhido."'";
    		$where_semestre2 = "and t.serie = '".$semestre_escolhido."'";
    		
    	}
    	else{
//     		$semestre_escolhido = "Todos";
    		$where_semestre = "";
    	}
    	
    	if(isset($_POST["curso-selecionado"]) && $_POST["curso-selecionado"] != ""){
    		//filtro por semestre
    		$cs = $_POST["curso-selecionado"];
    		$curso_escolhido = $cs;
    		$where_curso = "and turma.curso='".utf8_decode($curso_escolhido)."'";  		
    	}
    	else{
    		//pega o primeiro curso como sendo o default
    		$where_curso = "and turma.curso='".$cursos_coordenados[0]."'";
    	}
    	
    	if(isset($_POST["turma-selecionada"]) && $_POST["turma-selecionada"] != ""){
    		$ts = $_POST["turma-selecionada"];
    		$turma_escolhida = $ts;
    		$where_turma = "and turma.turma='".utf8_decode($turma_escolhida)."'";
    		$where_turma2 = "and t.turma = '".$turma_escolhida."'";
    	}
    	else{
    		//pega o primeiro curso como sendo o default
    		$where_turma = "";
    	}
    	
    	?>
    	
    	<?php
    	$qtd_pendente = 0;
    	$qtd_avaliada = 0;    	

    	$coordenador = new Professor();
    	$coordenador->alias('professor');
    	
    	$turma = new Turma();
    	$coordenador->join($turma, 'INNER', 'turma');
    	
    	$coordenador->select("professor.nome, turma.periodoLetivo, professor.id");

    	
    	$coordenador->where("turma.periodoLetivo = '".$periodo_atual."' AND coordenador.id != '".$usuario_logado->getId()."'".
    		$where_curso." ".$where_turma." ".$where_semestre);
    	    	

    	$coordenador->group("coordenador.id");
    	$coordenador->order("professor.nome");
    	$qtd_coordenadores = $coordenador->find();   	
    	
    	
     	echo "TOTAL DE COORDENADORES ATIVOS DO PERIODO ATUAL: ".$qtd_coordenadores;
     	//echo "<br />";

    	?>
    	
    	
    	
    	<script>
    	$(function() {
    		    		
    		$( "#accordion" ).accordion({
    			active: false,     //inicia com o accordion fechado
    			collapsible: true, //fecha o accordion qdo clicado
    			autoHeight: false, //ajusta o accordion ao conteudo
    			navigation: true
    		});
    	});  	
    	</script>
    		
    	<div id="accordion">

    	<?php
    	  	
    	
    	while( $professor->fetch() ) {
    		$listaAvaliacoes = array();
    		
    		$id_coordenador = $coordenador->id;
    		
    		//verificar qtos avaliacoes o coordenador tem
    		$query = "SELECT * FROM turma WHERE coordenador_id = '".$id_coordenador."'";
    		$result = mysql_query($query);
    		//$total = mysql_num_rows($result) + 2;// 2 = avaliacao institucional + auto-avaliacao 
    		
    		$listaAvaliacoes[0] = "Auto-avaliação-professor";
    		$listaAvaliacoes[1] = "Instituição";
    		while($dados = mysql_fetch_object($result)){
    			$listaAvaliacoes[] = "Coordenador-".$dados->coordenador_id;
    		}
    		
    		//verifica quais labs o professor precisa avaliar
    		$turmasDoProfessor_array[] = array();
    		$turmasProfessor = new Turma();
    		$turmasProfessor->periodoLetivo = $periodo_atual;
    		$turmasProfessor->where("professor_id = ".$id_professor);
    		$turmasProfessor->groupBy("nomeDisciplina");
    		$qtd = $turmasProfessor->find();
    		//echo "total de turmas encontradas: ".$qtd;
    		while ($turmasProfessor->fetch()) {
    			$turmasDoProfessor_array[] = $turmasProfessor->idTurma;
    		}
    		//print_r($turmasDoProfessor_array);
    		$labs = new TurmaHasLaboratorio();
    		$labs->find();
    		
    		//$laboratorios = array();    		
    		while ($labs->fetch()) {
    			if(in_array($labs->turmaIdTurma, $turmasDoProfessor_array)){
    				$lab_name = new Laboratorio();
    				$lab_name->get($labs->laboratorioId);
    		
    				//$laboratorios[] = array("id" => $labs->laboratorioId, "nome" => $lab_name->getNome(),
    						//"usado" => "sim", "avaliado" => "não");
    				
    				//verifica se o lab ja n�o esta na lista
    				if(!in_array(utf8_encode( "Lab_".$lab_name->getNome() ), $listaAvaliacoes)){
						$listaAvaliacoes[] = utf8_encode( "Lab_".$lab_name->getNome() );
					}
					
    			}
    		}
    		
    		$total = sizeof($listaAvaliacoes);
    		//
    		
    		
    		//debug
    		//echo "Array -->>";
    		//print_r($listaAvaliacoes);
    		    		
    		$query = "SELECT * FROM avaliacao WHERE avaliador = '".$id_professor."' AND tipo_avaliacao != 'Coordenador' GROUP BY item_avaliado ORDER BY subtipo_avaliacao";
    		$result = mysql_query($query);
    		$totalA = mysql_num_rows($result);    		
    		
  		
    		$qtd_pendente_professor = 0;
    		
    		$acuracia = 0.7;
    		
    		if($total == $totalA){
    			 //incrementa a qtd de professores q avaliaram tudo
    			 $qtd_professores_avaliaram++;
    		}else{
				//incrementa tbm qdo a percentagem de avaliacao for de 70%
				if( $totalA >= ( $total * $acuracia ) ){
					$qtd_professores_avaliaram++;
				}


    			echo "<h4><a href='#'>".utf8_encode($professor->nome)."<span style='float: right;'>Avaliou ".$totalA." de ".$total."</span></a></h4>";
    			$qtd_pendente_professor = $total - $totalA;
			}
    		?>
    		
    		
    		<?php 
//     		    		
    		if($total == $totalA){
    			$qtd_avaliada++;
    		}else{
    			
    		?>
    		
    		<div>
    		<table>
    		<caption>Avaliações Pendentes: <?php echo $qtd_pendente_professor;?></caption>
    	<tr>
    	<th>AVALIA&Ccedil;&Otilde;ES</th>
    	</tr>
    		
    		<?php 
    		
    		}
    		
    		while( $dados = mysql_fetch_object($result) ) {
    			
				if( (in_array($dados->subtipo_avaliacao, $listaAvaliacoes)) || (in_array($dados->subtipo_avaliacao."-".$dados->item_avaliado, $listaAvaliacoes)) ||in_array("Lab_".$dados->subtipo_avaliacao, $listaAvaliacoes)  ){
    				//remove item do array
    				$key;
    				if(in_array($dados->subtipo_avaliacao, $listaAvaliacoes)){
						$key = array_search($dados->subtipo_avaliacao, $listaAvaliacoes);						
					}
					if(in_array($dados->subtipo_avaliacao."-".$dados->item_avaliado, $listaAvaliacoes)){
						$key = array_search($dados->subtipo_avaliacao."-".$dados->item_avaliado, $listaAvaliacoes);						
					}
					if(in_array("Lab_".$dados->subtipo_avaliacao, $listaAvaliacoes)){
						$key = array_search("Lab_".$dados->subtipo_avaliacao, $listaAvaliacoes);
					}
					unset($listaAvaliacoes[$key]);
    				
    				//incrementa total de pendentes
    				$qtd_avaliada++;
    			}else{							
    				
    			}  			   			
    			
    		}
    		
    		//
    		foreach($listaAvaliacoes as $av){
    			echo "<tr>";
    			echo "<td style='width: 10%;'>".$av."</td>";
    			echo "</tr>";
    			//incrementa total de pendentes
    			$qtd_pendente++;
    			$qtd_pendente_professor++;
    		}
    		
    		if($total == $totalA){
    		
    		}else{
    		?>
    		</table>
    		</div>
    		    		
    		<?php 
    		}
    		  		

    		
    	}
    	?>
    	    		
    	    		</div>
    	    		</div><!-- fecha div white -->
    	    		<?php
       	
    	
    	//echo "TOTAL DE AVALIA&Ccedil;&Otilde;ES PENDENTES: ".$qtd_pendente;
    	/*echo "<br />";
    	echo "TOTAL DE AVALIA&Ccedil;&Otilde;ES CONCLUIDAS: ".$qtd_avaliada;
     	echo "<br />";
     	echo "TOTAL DE PROFESSORES QUE NÃO CONCLUIRAM A AVALIAÇÃO: ".($qtd_professores - $qtd_professores_avaliaram);
     	echo "<br />";
     	echo "TOTAL DE PROFESSORES QUE CONCLUIRAM A AVALIAÇÃO: ".$qtd_professores_avaliaram;  	
    	*/
    	
    	?>
        
        <br />
        
        <script type="text/javascript">
      
      function drawChart(){
        	// Create and populate the data table.
        	  var data = new google.visualization.DataTable();
              data.addColumn('string', 'alunos');
              data.addColumn('number', 'Professores que concluíram a avaliação');
              data.addColumn('number', 'Professores pendentes');

              <?php
              //$qtd_alunos_avaliaram = 75;
              //$qtd_alunos = 155;
              ?>
              var data = google.visualization.arrayToDataTable([                                                         
                                                                ['Classificação', 'Qtd'],
                                                                ['Professores que concluíram a avaliação',     <?php echo $qtd_professores_avaliaram;?>],
                                                                ['Professores pendentes',      <?php echo $qtd_professores - $qtd_professores_avaliaram?>]
                                                              ]);

      var options = {
              title: 'Professores que concluíram a avaliação X Professores pendentes',
              is3D: true
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);     
      
      }

      $(function() {
    	  drawChart();
      });
      
      
    </script>
        <div id="chart_div" style="width: '100%'; height: 500px;"></div>
                
        
    </div>
    <?php include_once 'inc/footer_inc.php';?>
</div>
<?php 

//$_SESSION["aluno"] = serialize($aluno);
// $_SESSION["processo"] = serialize($processo);
?>
</body>
</html>
