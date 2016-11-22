<?php 
#=====================================================================
#					- Template starts -
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/title.php'); 
require_once($r .'includes/functions.php');

start_page();
show_top_bar();

#					- Template ends -
#=====================================================================

?>
<section class="container">
<div class="main-content-region">
<!-- BACK LINK -->
			
				<?php 
				if(isset($_GET['section_name'])){ 
					echo'<a class="float-right-lists" href="'.BASE_PATH. '?page_name=sections">BACK TO SECTIONS </a>' ;
					} else if(isset($_GET['page_name'])){
						echo'<a class="float-right-lists" href="'.BASE_PATH. 'page">Page list </a>' ;
						}?>
		



	<!-- PAGE RENDERING STARTS -->	

	<?php remove_file(); edit_contest(); ?>
</div>

<div class='right-sidebar-region'><?php upload_image(); ?></div>

</section>
		
	<section class="documentation"> <h1> Documentation</h1>
	<hr>
	</section
	
</div>
<?php do_footer(); ?>
