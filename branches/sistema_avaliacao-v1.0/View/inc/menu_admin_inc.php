<div id="menu">
<ul>
<li><a href="home.php"  title="Página Inicial" class="home_link">PÁGINA INICIAL</a></li>
<?php

$activePage = $_SESSION["s_active_page"];

$class = "";

foreach ($usuario_logado_permissoes as $value) {
	$permissao = new Permissao();
	$permissao->get($value);
	
	if($activePage == $permissao->getLink()){
		$class = "active";
	}else{
		$class = "";
	}
	?>
    <li><a href="../Controller/pageController.php?pg=<?php echo codifica($permissao->getLink());?>" class="<?php echo $class;?>" title="<?php echo utf8_encode($permissao->getNome());?>"><?php echo strtoupper(strtr(utf8_encode($permissao->getNome()) ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
    <?php		
    	}    
    ?>	
    </ul>
    <?php include_once 'inc/info_usuario_inc.php';?>  
    </div>