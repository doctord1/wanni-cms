<?php
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

#					- Template ends -
#======================================================================
 include_once('../add.php'); 
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
 edit_contests(); 
 ?>
</section>

<section class='right-sidebar-region'>
<?php upload_image(); ?>
</section>
