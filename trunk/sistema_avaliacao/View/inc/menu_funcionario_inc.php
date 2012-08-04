<?php 
$menu_caps = true;

$page = $_SESSION["s_active_page"];

if($page == "indexFuncionario.php"){
	$index_active = "class='home_link active'";
	$avaliacoes_active = "";
	$relatorios_active = "";
}
if($page == "avaliacoesFuncionario.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "class='active'";
	$relatorios_active = "";
}
if($page == "avaliacaoFuncionario.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "class='active'";
	$relatorios_active = "";
}

if($menu_caps){
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexFuncionario.php");?>" title="P&aacute;gina Inicial" <?php echo $index_active;?>><?php echo strtoupper(strtr("Página Inicial" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesFuncionario.php");?>" title="Avalia&ccedil;&otilde;es" <?php echo $avaliacoes_active;?>><?php echo strtoupper(strtr("Avaliações" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}else{
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexFuncionario.php");?>" title="P&aacute;gina Inicial" <?php echo $index_active;?>>P&aacute;gina Inicial</a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesFuncionario.php");?>" title="Avalia&ccedil;&otilde;es" <?php echo $avaliacoes_active;?>>Avalia&ccedil;&otilde;es</a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}
?>