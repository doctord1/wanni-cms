<?php

#=======================================================================
#                   FUNCTIONS TEMPLATE 
#=======================================================================
# THIS TEMPLATE CONTAINS CODE ALREADY WRITTEN CODE TO HELP YOU QUICKLY 
# AND EASILY  START WRITING ADDONS FOR WANNI CMS.
# 
#				DO NOT EDIT OR TAMPER 
#		[UNLESS YOU ABOLUTELY KNOW WHAT YOU ARE DOING]	 
# ---------------------------------------------------------------------
#					 TEMPLATE STARTS
#----------------------------------------------------------------------
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

# This gives you access too core functions and variables.
#  It can be optional if you want your addon to act independently. 
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
$me = dirname(__FILE__);
#echo $r ."<br>";
include_once($r .'includes/functions.php'); #do not edit


start_addons_page();

#======================================================================
#						TEMPLATE ENDS
#======================================================================

echo "<section class='container'>";

echo "<h2>Addons</h2>";
# MAIN CONTENT

if(is_admin()){
echo '<ul>
			<li class="float-right-lists">
				<a href="'.BASE_PATH .'config">Site SETTINGS </a> </li>
			<li class="float-right-lists">
				<a href="'.ADDONS_PATH .'">Install or configure Addons </a> </li>
		</ul>';
		}

	echo "<ol>";
	$core = array('page','blocks','sections','funds_manager','menus','libraries',
					'scripts','styles','regions','uploads','admin','addons','config',
					'slideshow','users','includes','documentation');
	$list = array();
	
	#GET OPTIONAL ADDONS				
	if ($handle = opendir($me)) {
		while (false !== ($addon_folder = readdir($handle))) {
			if ($addon_folder != "." 
				&& $addon_folder != ".." 
				&& $addon_folder != ".git"
				&& is_dir($addon_folder)) {
				
					if(!in_array($addon_folder,$core)){
					array_push($list,$addon_folder);
					include_once($r.$addon_folder."/details.php");
					$_SESSION["{$addon_folder}_desc"]  = $my_addon_desc; # Gets description from included file
					
					}
				}
				
			}
	}
				closedir($handle);
				# END GET FOLDERS

	#print_r($list); testing


	#START LISTING OPTIONAL ADDONS
	
	foreach ($list as $addon_name){
		
		
		$desc = $_SESSION["{$addon_name}_desc"];
		echo "<div class='addon-holder'><li> <a href='".ADDONS_PATH.$addon_name."'><big>". ucfirst(str_ireplace('_', ' ', $addon_name))."</big> </a><div>
		{$desc}</div><div class='float-right'>&nbsp&nbsp
		<a href='".ADDONS_PATH.$addon_name."/config'>configure </a>&nbsp&nbsp</div> ";
		$desc='';
		
		if(addon_is_active($addon_name)){
			echo "<div class='float-right'><div class='green-text'><em> Activated</em></div></div>";
			} else {
				echo "<div class='float-right'> <div class='red-text'><em> Deactivated</em></div></div>";
				}
								
		echo "<hr></li></div>";
		}

	
	
	echo "</ol>";

echo "</section>";
?>
