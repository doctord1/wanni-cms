<?php 
#======================================================================
#					- Template starts -
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/title.php'); # 
require_once($r .'includes/functions.php');
start_page();
show_top_bar();

#					- Template ends -
#======================================================================

?>
</head>
<body>

<!-- DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->	

<section class="container">
<h2> ADD BLOCKS </h2>

<!-- TOP RIGHT LINKS --> 

	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'blocks/add">Add blocks </a>' ;?></li>
		<li id="show_blocks_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'blocks">BACK</a>' ;?></li>
	</ul>
<?php 
remove_file();
 add_block(); 
 ?></section>

<section class="documentation"> <h1> Documentation</h1>
<hr>
</section>



<?php add_tinymce_editor(); ?>
