<?php 
$menu_caps = true;


$page = $_SESSION["s_active_page"];

if($page == "indexCoordenador.php"){
	$index_active = "class='home_link active'";
	$avaliacoes_active = "";
	$relatorios_active = "";
	$pendentes_active = "";
}
if($page == "avaliacoesCoordenador.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "class='active'";
	$relatorios_active = "";
	$pendentes_active = "";
}
if($page == "avaliacaoCoordenador.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "class='active'";
	$relatorios_active = "";
	$pendentes_active = "";
}
if($page == "alunospendentes.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "";
	$relatorios_active = "";
	$pendentes_active = "class='active'";
}


if($menu_caps){
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexCoordenador.php");?>" title="P&aacute;gina Inicial" <?php echo $index_active;?>><?php echo strtoupper(strtr("Página Inicial" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesCoordenador.php");?>" title="Avalia&ccedil;&otilde;es" <?php echo $avaliacoes_active;?>><?php echo strtoupper(strtr("Avaliações" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("alunospendentes.php");?>" title="Alunos Pendentes" <?php echo $pendentes_active;?>><?php echo strtoupper(strtr("Alunos Pendentes" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}else{
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexCoordenador.php");?>" title="P&aacute;gina Inicial" <?php echo $index_active;?>>P&aacute;gina Inicial</a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesCoordenador.php");?>" title="Avalia&ccedil;&otilde;es" <?php echo $avaliacoes_active;?>>Avalia&ccedil;&otilde;es</a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("alunospendentes.php");?>" title="Alunos Pendentes" <?php echo $pendentes_active;?>>Alunos Pendentes</a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}
?>