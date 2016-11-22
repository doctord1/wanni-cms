<?php 
#======================================================================
#					- Template starts -
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit

require_once($r .'includes/title.php'); # 
require_once($r .'includes/functions.php');
start_page(); //show_top_bar();

#					- Template ends -
#=======================================================================
?>

<!-- DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->	
<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>	
	</div>
	<?php show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>

<!-- MAIN START -->
<section class="container padding-20">

<?php 
	//print_r($_SESSION);

	echo "<div class='main-content-region'>";
?>

<h3 align="center"> Add New <?php echo $_GET['type']; ?></h3>


<!-- BACK LINK -->


<?php add_new_what();
	if($_GET['type'] === 'page' 
	|| $_GET['type'] === 'blog'
	|| $_GET['type'] === 'notice'){
		
		remove_file();
		add_page(); 
		
		} else if($_GET['type'] == 'contest'){
		add_contest();
		}
	?>
	</div>
	<div class="right-sidebar-region"><?php upload_image(); ?></div>
</section>


<?php
// echo '<script type="text/javascript" src="'. BASE_PATH .'page/scripts/script.js"></script>';

do_footer();
?>
