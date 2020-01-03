<!-- partial:partials/_sidebar.html -->
<nav class="sidebar site-sidebar sidebar-offcanvas" id="sidebar" data-simplebar>
  <ul class="nav">
    <?php
    $datas = require('config/menu.php');
    ?>
    <?php foreach($datas as $menu_label => $menu_data) : ?>
  	<li class="nav-item">
  		<a href="#" class="nav-link"><?= $menu_label ?></a>
  		<ul class="menu-content">
        <?php foreach($menu_data as $sublabel => $value) : ?>
  			<li>
  				<a href="#<?=$value?>" data-value="<?=$value?>" class="menu-item doc-ajax-load"><?=$sublabel?></a>
  			</li>
        <?php endforeach;?>
  		</ul>
  	</li>
    <?php endforeach;?>
  </ul>
</nav>
<!-- partial -->