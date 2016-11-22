<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------


$my_addon_name = "user"; 

$my_addon_desc = "Handles User account management"; // Short clear description of what addon does.

$version = "1.01.dev"; 

$config_path = BASE_PATH ."user/config";


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
