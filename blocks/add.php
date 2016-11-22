
<?php
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit

require_once($r .'includes/functions.php'); #do not edit 
require_once($r .'includes/title.php');
show_top_bar();?>

<section class="header">
<h1> ADD BLOCKS </h1>
</section>
<!-- TOP RIGHT LINKS --> 
<section class="">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'blocks/add">Add blocks </a>' ;?></li>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'blocks/config">Blocks Config </a>' ;?></li>	
		<li id="show_blocks_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'blocks">BACK</a>' ;?></li>
	</ul>
</section>

<section class='container'><?php 
remove_file();
 add_block(); 
 ?></section>
 
</body>
</html>
