<?php ob_start();
#======================================================================
#					- Template starts -
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/title.php'); # 
require_once($r .'includes/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
<title>  <?php $page_title = set_page_title();
 echo $page_title; ?> </title>

<?php $r= BASE_PATH; 
$stylesheet = '<link href="' .$r .'styles/style.css" rel="stylesheet">';
echo $stylesheet;
start_page();
show_top_bar();

#					- Template ends -
#======================================================================
?>
<!-- DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->	

<br>
<section class="container">

	<h1> ADD SECTION </h1>
			<!-- BACK LINK -->
			<ul>
				<li id="add_page_form_link" class="float-right-lists">
					<?php echo'<a href="../">BACK TO SECTIONS </a>' ;?></li>
			</ul>
<div class='main-content-region'>			
	<?php delete_section(); add_section(); ?> 
</div>



	<div class='right-sidebar-region'>
	<?php  upload_image($folder='',$name='Section image'); // upload form ?>
	</div>

</section>

<section class="documentation"> <br><h1> Documentation</h1>
<hr><br>
</section>
