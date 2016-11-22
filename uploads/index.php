<?php
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
 start_addons_page(); $status = "active";
 
#						- Template ends -
#=======================================================================
?>

<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<section class='container'>
	
<h1> UPLOADS </h1>

<!-- TOP RIGHT LINKS --> 

<div class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'uploads/config">Configure Uploads </a>' ;?></li>
	</ul>
</div>

	
<?php 
if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
# CUSTOM CODE HERE 

echo"<div class='main-content-region'>";
show_files_listing();
echo "</div>";

echo"<div class='right-sidebar-region'>";
remove_file();
upload_image(); PHP_EOL;
echo"</div>";

} else { deny_access();}
?>

</section>
</body>
</html>
