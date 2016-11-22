<?php  ob_start();
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

start_page();  
#						- Template ends -
#=======================================================================
//print_r($_SESSION);
 
 #DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->


?>	
<style>
.company-background{
	display: block;
	width: 100%;
	height: 150px;
	overflow: hidden;
	background-color: darkslateblue;
	z-index: 20;
	}
.company-logo{
	position: absolute;
	top: 30px;
	left: 30px;
	width: 100px;
	height: 100px;
	border: 10px solid white;
	z-index: 1000;
	}
.company-name{
	position: absolute;
    top: 50px;
    left: 30%;
    font-size: 40px;
    font-weight: bold;
    text-align: center;
    z-index: 1000;
    text-shadow: -2px 1px 4px #000;
    color: white;
    margin-bottom: 20px;
	}
.company-about{
	display: block;
	position: relative;
	}
</style>



<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>	
	</div>
	<?php show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>
 
 
 
<?php
echo '<div class="container padding-10">';
echo '<div class="main-content-region">';
search_companies();
add_company_profile();	
edit_comment($upload_allowed='true');
echo '</div>';

$company = get_company_page();
	$_SESSION['company_creator'] = $company['creator'];
	$_SESSION['company_id'] = $company['id'];
	$_SESSION['company_name'] = $company['company_name'];
	$_SESSION['company_url'] = $company['company_url'];

if(!isset($_GET['edit_comment'])){
	if(!empty($company)){
		show_company_banner_and_logo();
		echo '<div class="main-content-region">';
		show_session_message();
		add_company_profile(); 
		if( !isset($_POST['search_company']) && !empty($company['about']) && $_GET['action']=='show'){
		echo '<div class="company-about page-content lavender padding-10"><b>About : </b>'.$company['about'].'
		<br><b>Link : </b><a href="http://'.$company['company_url'].'">'.$company['company_url'].'</a></div><p></p>';
		}
		
		if((is_admin() || (is_logged_in() && $_SESSION['username'] == $company['creator'])) && $_GET['action'] == 'edit_company'){
				echo '<a href="'.ADDONS_PATH.'company?company_name='.$company['company_name'].'&action=show&tid='.$company['id'].'" class="pull-right">Return to '.$company['company_name'].'</a>';
				}
				
				edit_company_profile();
				get_company_updates();
				
				add_company_jobs();
				
				add_company_team_member();
				get_company_jobs();
				echo '</div>';
		} else {
				if(!empty($_GET['company_name'])){
					
				echo '<div class="main-content-region">';
				status_message('error', 'No such company here!');
				link_to(ADDONS_PATH.'company','See All Companies','','text','');
				}
				echo '</div>';
			}
	
	}
		echo '<div class="main-content-region">'; log_in_to_continue(); echo '</div>';
//

echo '<div class="right-sidebar-region">';
			if(!empty($_GET['company_name'])){
				link_to(ADDONS_PATH.'company','<b>See All Companies</b>','padding-10','button','');
			}

		//
change_company_logo();
change_company_background();
get_company_projects();

echo '</div>';

show_company_team(); // already has region information, so not placed in region




echo "</div></div>".//end container
"<p></p><div class='footer-region'>";
do_footer();

echo "</div>";
?>

</body>
</html>
