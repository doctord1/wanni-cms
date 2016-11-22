<?php 
#=====================================================================
#					- Template starts -
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/title.php'); 
require_once($r .'includes/functions.php');

start_addons_page();


#					- Template ends -
#=====================================================================

?>
<section class="container">
<div class="main-content-region">
<!-- BACK LINK -->
<ul>
			<li id="add_page_form_link" class="float-right-lists">
				<?php echo'<a href="../"> Back to Blocks </a>' ;?></li>
		</ul>



	<!-- PAGE RENDERING STARTS -->	
	<br>

	<?php remove_file(); edit_block(); ?>
</div>

<div class='right-sidebar-region'><?php upload_image(); ?></div>

</section>
		
	<section class="documentation"> <h1> Documentation</h1>
	<hr>
	</section
	
<?php# add_tinymce_editor(); ?>	
</div>



<?php add_tinymce_editor(); ?>
