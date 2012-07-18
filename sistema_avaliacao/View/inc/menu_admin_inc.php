<div id="menu">
<ul>
<li><a href="home.php"  title="Página Inicial" class="home_link">PÁGINA INICIAL</a></li>
<?php
foreach ($usuario_logado_permissoes as $value) {
	$permissao = new Permissao();
	$permissao->get($value);
	?>
    <li><a href="<?php echo $permissao->getLink();?>"  title="<?php echo utf8_encode($permissao->getNome());?>"><?php echo strtoupper(strtr(utf8_encode($permissao->getNome()) ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
    <?php		
    	}    
    ?>	
    </ul>
    <?php include_once 'inc/info_usuario_inc.php';?>  
    </div>