<?php 
#=====================================================================
#                 - Template starts -

#   		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit

require_once($r .'includes/functions.php'); #do not edit;
require_once($r .'includes/title.php');
start_page();
show_top_bar();



#                   - Template ends -
#======================================================================

?>

<!-- BACK LINK -->
<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="../">BACK TO PAGES </a>' ;?></li>
	</ul>
	
</section>

<br>

<section class='container'><?php remove_file();
 edit_pages(); 
 ?>
</section>

<section class='right-sidebar-region'>
<?php upload_image(); ?>
</section>
