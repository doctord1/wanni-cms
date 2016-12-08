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

#						- Template ends -
#=======================================================================
?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->
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
 
 

<section class="container padding-20">
 <h1><?php echo ucfirst($my_addon_name)."s"; ?></h1>

<?php 
$destination = BASE_PATH."?section_name=fundraiser";

delete_fundraiser_perk();
$s = $_SERVER['QUERY_STRING'];
if(empty($s)){
	//redirect_to($destination);
	}
$back_url = $_SERVER['HTTP_REFERER'];

show_fundraiser();

//echo '<div class="main-content-region">';

//~ show_fundraiser_perks();
//~ echo '<div class="whitesmoke padding-10 inline-block margin-10">
			//~ <a href="'.ADDONS_PATH .'fundraiser?action=list-fundraisers">List Fundraisers </a></div>';


//~ if (isset($_SESSION['username'])){
//~ 
			//~ echo '<div class="clear whitesmoke padding-10 inline-block margin-10">
			//~ <a href="'.ADDONS_PATH .'fundraiser?action=add-fundraiser">Add Fundraiser </a></div>';
		//~ }
		//show_post_activity('fundraiser'); // causes error in display of slideshow

//echo '</div>';	


$query_string = $_SERVER['QUERY_STRING'];
 if($_GET['action'] ==="show" && $_GET['grid']==="yes"){
	get_fundraiser_grid();
	
	} else if($query_string === ''){
		get_fundraiser_grid();
		}

			
					
echo "<div class='main-content-region'>";


			
if (isset($_SESSION['username'])){			
	if($_GET['action'] ==="add-fundraiser"){
	add_fundraiser();
	
	}	
}
	
	if(!isset($_GET['action']) && !isset($_GET['tid'])){
	show_fundraiser_lists('','pending');
	show_fundraiser_lists('','finished');
	}
	



	
echo "</div>";	//end main content region


$is_admin = is_admin();
if($is_admin){

}

?>



</section>
<!-- FOOTER -->
<?php include_once('../../regions/includes/footer_region.php');?>


</body></html>
