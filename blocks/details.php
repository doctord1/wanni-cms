<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------


$my_addon_name = "blocks"; 

$my_addon_desc = "Blocks allow you to place content in  different areas of the page eg header, footer, left etc"; // Short clear description of what addon does.

$version = "1.01.dev"; 

$config_path = BASE_PATH ."blocks/config";


#------------------------------------------------------

# dev for development version
# stable for stable or fully working version
# alpha or beta in between



$details = array(
'name' =>$my_addon_name ,
'desc' => $my_addon_desc,
'version' => $version, 
'config_path' => $config_path);

?>
