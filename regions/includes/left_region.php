<?php
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



function show_left_sidebar(){
	
	$is_mobile = check_user_agent('mobile');
	if($is_mobile || !is_logged_in()){
	echo '<section id="sidebar" class="hidden">';
	} else if(!$is_mobile && is_logged_in() && !url_contains('page_name=home') ){
	echo '<section id="sidebar" class="hidden">';
	} else if(!$is_mobile && is_logged_in() && ($_SESSION['current_url'] == BASE_PATH .'?page_name=home')){
	echo '<section id="sidebar" class="content-pushed">';
	}
	echo '<div class=" main-sidebar main-sidebar-left">';
		echo '<div id="close-sidebar"><p align="center"><br> - Close x </p></div>';
	if(!is_user_page()){
	$pic = show_user_pic($user=$_SESSION['username'],$pic_class='circle-pic',$length='150px');
	echo '<div class="padding-20 center-block">'.$pic['picture'].'</div>';
	}
	do_left_sidebar();
	
	if(is_logged_in()){
	link_to(BASE_PATH.'user','&nbsp;<i class="glyphicon glyphicon-search"></i>&nbsp; Find someone',$class='btn btn-sm btn-default margin-10',$type='button');
	}
	show_sidebar_settings_menu();
		
	echo '</div>';
	if(!is_logged_in()){
		echo '<div class="extra-content-1">';

		echo '</div>';	
		}
		echo '<div class="extra-content-2">
		
		</div>';
  
echo '</section>';	
}
show_left_sidebar();
# THis view enables easy any individual styling of this region 

?>


