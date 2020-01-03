<!-- partial:partials/_sidebar.html -->
<nav class="sidebar site-sidebar sidebar-offcanvas" id="sidebar" data-simplebar>
  <ul class="nav">
    <?php
    $datas = require('config/menu.php');
    ?>
    <?php foreach($datas as $menu_label => $menu_data) : ?>
  	<li class="nav-item">
      <?php
      if(is_array($menu_data)){
        $first_url = isset(array_values($menu_data)[0]) ? array_values($menu_data)[0] : '#';
      }
      else{
        $first_url = $menu_data;        
      }
      ?>
  		<a href="#<?=$first_url?>" data-value="<?=$first_url?>" class="nav-link doc-ajax-load"><?= $menu_label ?></a>
      <?php if(is_array($menu_data)): ?>
  		<ul class="menu-content">
        <?php foreach($menu_data as $sublabel => $value) : ?>
  			<li>
  				<a href="#<?=$value?>" data-value="<?=$value?>" class="menu-item doc-ajax-load"><?=$sublabel?></a>
  			</li>
        <?php endforeach;?>
  		</ul>
      <?php endif;?>
  	</li>
    <?php endforeach;?>
  </ul>
</nav>
<!-- partial -->