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
			<p class="float-right-lists">
				<?php 
				if(isset($_GET['fundraiser_name'])){ 
					echo'<a href="'.BASE_PATH. '?section_name=fundraiser">BACK TO FUNDRAISERS </a>' ;
					} ?></p>
		



	<!-- PAGE RENDERING STARTS -->	
	<br>

	<?php remove_file(); edit_fundraiser(); ?>
</div>


<div class='right-sidebar-region'><?php upload_image();?></div>

</section>
		
	<section class="documentation"> <h1> Documentation</h1>
	<hr>
	</section
	
<?php# add_tinymce_editor(); ?>	
</div>
