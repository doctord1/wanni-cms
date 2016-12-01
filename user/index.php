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
'" class ="home-link"><img src="'.BASE_PATH .'uploads/files/default_images/User-Group-32.png"></a>';
start_page(); 
//print_r($_GET);
//print_r($_SESSION);
#						- Template ends -
#=======================================================================
# DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

# HEADER REGION START -->

?>
	
<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper dropdown"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>	
	</div>
	<?php show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>
  

<section class='container'>
<?php include_once('../regions/includes/left_region.php'); ?>
<!-- HIGHTLIGHT REGION -->
<?php include_once('../regions/includes/highlight_region.php'); 
if(! is_logged_in()){
	
			echo '<div class="row"><div class="col-md-12 col-xs-12 black margin-20 padding-10 large-text">';
			echo $_SESSION['banner'];
			echo '</div>
			</div>';
	}?>


<div class='main-content-region text-center'>
	
<?php show_session_message();

//print_r($_SERVER);

echo $_SESSION['status_message'];
$_SESSION['status_message'] = '';

	
login_register_switcher();
forgot_password();
if(isset($_GET['user'])){
	$user = trim(mysql_prep($_GET['user']));
	if(is_logged_in()){
		if(!isset($_GET['edit'])){
			echo '<div class="col-md-12">';
			login_successful();	
			echo '</div>';
			$user_details = get_user_details($user);
			// print_r($user_details);
			
		//	echo "Today is : <span class='glyphicon glyphicon-time'></span>&nbsp;" .date('d/m/y');
			
			$pic = show_user_pic($user); # intitializes the user picture
			echo '<div class="col-md-12 well well-lg"><p align="center"><h1>';
			echo ucfirst($user_details['user_name']) ;
			echo '\'s profile</h1></p>';
			show_user_edit_link(); echo ' &nbsp;&nbsp;| &nbsp;&nbsp;'; 
			show_user_delete_link();
			echo "<hr>"; 
			
			echo '<p align="center">';
			echo $pic['picture'];
			if(addon_is_active('messaging')){		 			
				send_message_link(); # Allow others to message the user being viewed.
				}
			echo '</p>' ;# Displays the user picture
			
			
			
			if(addon_is_active('referrals')){
				//echo'<div class="col-md-12 thumbnail">';
			$referrer = show_referrer(); 
			echo $referrer['referrer_link'];
			$referral = show_referral_id();
			echo  $referral['ref_id'] ;
			echo  $referral['ref_link'] ;
			echo '<br><br></div>';
			}
			
			echo'<div class="col-md-12 ">';	
			if(addon_is_active('contacts')){
				add_to_contacts();
				remove_from_contacts();
				
			}
				
			
			if(addon_is_active('top_up_pin')){
			//show_verification_status();
			}
		show_user_profile();
		//show_user_profile(); # Shows user profile fields including messaging and funds if available	
		echo '<br><br></div>';	
			
			echo'<div class="col-md-12 whitesmoke">';
			if(addon_is_active('gallery')){
			
			show_link_to_gallery();
			echo '<br></div>';
			}
			
			if(addon_is_active('fundraiser')){
			echo'<div class="col-md-12 thumbnail well"><br>';
			show_my_fundraisers();
			echo '<br><br></div>';
				}
			
			if(addon_is_active('contest')){
			echo'<div class="col-md-12">';
			show_my_current_contests();
			echo '<br></div>';
				}
			
			if(addon_is_active('project_manager')){
			echo'<div class="col-md-12"><br><br>';
			show_my_completed_tasks();
			echo '<br><br></div>';
			}
			
			list_users(); 
			
				
		} else { # IF EDIT USER IS REQUESTED
		edit_user();
		
		}			
		} 
	}
	echo "</div>";
	
	echo "<div class='main-content-region'>";
	user_search();
	echo "</div>";

//<!-- RIGhT REGION -->
echo '<div class="right-sidebar-region">';
	if(is_logged_in()){
	link_to(BASE_PATH.'user','&nbsp;<i class="glyphicon glyphicon-search"></i>&nbsp; Find someone',$class='center',$type='button');
	
	}
echo '</div>';
?>


<?php include_once('../regions/includes/right_region.php'); ?>
	

<?php 
echo '<div class="right-sidebar-region">';
//show_new_users();
if(addon_is_active('follow')){
user_follow_list('follow');	
}
online_users(pics);
masquerade_as();
echo '</div>';

if(!is_logged_in()){
echo '<div class="row">

<div class="col-md-12 col-xs-12 black padding-10 large-text">On GeniusAid, we do 3 things well.</div>
<div class="col-md-4 col-xs-12 padding-20 black-bar"> Get Financial backing (FROM US) for your project or business idea (Up to $3,000.00) No payback required. </div>
<div class="col-md-4 col-xs-12 padding-20 orange-bar"> Raise funds (FROM OTHERS) to support your plans, business ideas or community development projects.</div>
<div class="col-md-4 col-xs-12 padding-20 green-bar"> Help others achieve their goals, ideas or projects by completing small tasks for which you will be paid for.</div>
<div class="col-md-12 col-xs-12 padding-20">Whichever one you choose, GeniusAid has got you covered!</div>
</div>';


}	
?>	

<!-- </div></div> -->

</section>

<!-- FOOTER -->
<?php include_once('../regions/includes/footer_region.php');
//die(); // this stop incessant redirects
?>


</body></html>

