<?php
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

start_addons_page();

########################################################################
?>



<section class="container">

<?php 
if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){

echo '<section class="clear">
		<ul>
			<li id="add_page_form_link" class="float-right-lists">
				<a href="'.BASE_PATH .'blocks/config">Config </a> </li>
			<li id="add_page_form_link" class="float-right-lists">
				<a href="'.BASE_PATH .'blocks/add">Add blocks </a></li>
		</ul>
	</section>';

echo "<div class='main-content-region'>";
list_blocks(); 
echo "</div>";

echo "<div class='right-sidebar-region'>".
"<h2>System blocks</h2>"; 
echo "</div>";

} else { deny_access();}
 ?>
</section>

</body>
</html>
