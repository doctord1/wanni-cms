<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/money_service'; #do not edit
require_once($r .'/includes/functions.php'); #do not edit
?>

<?php  start_addons_page();  
#						- Template ends -
#=======================================================================

?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->


<section class="container">
 <?php if(is_admin()){ echo ' <h1><a href="../money_service">Money service </a>admin</h1>';}
 if(!is_logged_in() && empty($_GET)){ redirect_to(ADDONS_PATH."money_service/catalog"); }
 ?>
 


<div class='margin-10'>
<?php 
# CUSTOM CODE HERE 
show_session_message(); // prints out latest session message

// GET USER pERmIsSIOn
if(isset($_SESSION['username'])){
	
	if(is_admin()){ 
		
		# Show admin links if user has permission
		echo '<div class="top-left-links u-full-width">
				<ul>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service">Settings </a></li>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service/config">Configure </a></li>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service/catalog">Catalog </a></li>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service/cart.php">Basket </a></li>
				</ul>
			</div><br>';
			
			} else {  # SHOW LINKS FOR NON ADMIN USER	
		echo '<div class="top-left-links u-full-width">
				<ul>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service/catalog">Catalog </a></li>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service/cart.php">Basket </a></li>
				</ul>
			</div><br><hr>'; 
			
			
			}
			
			
		
		 if(!empty($_GET['show']) && $_GET['show']==='money_service'){
			 
			if(isset($_GET['money_service_code'])){

				
				$money_service = show_money_service();
				
				#SHOW money_service title and pictures
				echo "<div class='main-content-region'>"
				.$money_service['title'];
				upload_no_edit();
				
				foreach($money_service['picture'] as $picture){
				echo '<div class="center-block img-responsive">' .$picture .'</div>';
				}
				echo "</div>";
				
				echo '<aside class="right-sidebar-region"><div class="money_service-description">'.$money_service['description'].'</div>'
					.'<div class="money_service-price">NGN '.$money_service['price'] .'</div>'
						.'<div class="money_service-stock">Available : '.$money_service['stock'].'</div>'
					 .$money_service['add to cart button'];
					 
						echo '</aside>';
				
				}else{  show_money_services_list('',1);  }
			
		}else if(!empty($_GET['show']) && $_GET['show']==='money_service_type'){
			
			show_money_service_type_list();
				
		}else if(!empty($_GET['show']) && $_GET['show']==='pending_approval'){
			
			show_pending_money_services_queue();
			
		}else if(isset($_GET['add_new']) && $_GET['add_new']==='money_service_type'){
			
			add_new_money_service_type();
			
		}else if(!empty($_GET['action']) && $_GET['action']==='edit_money_service_type'){ //edit money_service type
			edit_money_service_type();
			
		}else if(!empty($_GET['action']) && $_GET['action']==='delete_money_service_type'){ //delete money_service type
			delete_money_service_type();	
			
		}else{
				
			if(!isset($_GET['show']) && empty($_GET)){
				show_money_service_admin_links();
				//change_money_service_details();
				}
				
			if(isset($_GET['add_new']) && $_GET['add_new']==='money_service'){
				
				add_new_money_service();
				}
				
			if(!empty($_GET['action']) && $_GET['action']==='edit_money_service'){ //edit money_service
				edit_money_service();
				echo "<aside class='right-sidebar-region'>";
				echo upload_image() ;
				echo "</aside>";
				}
			if(!empty($_GET['show']) && $_GET['show']==='order_form'){
					
				show_order_form();
				}
					
	}
} else if(!empty($_GET['show']) && $_GET['show']==='money_service'){
			 
			if(isset($_GET['money_service_code'])){

				
				$money_service = show_money_service();
				
				#SHOW money_service title and pictures
				echo "<div class='main-content-region'>"
				.$money_service['title'];
				echo eval($money_service['pic_upload']);
				
				foreach($money_service['picture'] as $picture){
				echo '<div class="linked-image">' .$picture .'</div>';
				}
				echo "</div>";
				
				echo '<aside class="right-sidebar-region"><div class="money_service-description">'.$money_service['description'].'</div>'
					.'<div class="money_service-price">NGN '.$money_service['price'] .'</div>'
						.'<div class="money_service-stock">Available : '.$money_service['stock'].'</div>'
					 .$money_service['add to cart button'];
					 
						echo '</aside>'; 
				} 
		} else { deny_access(); }



?>
</div>
</section>


<?php do_footer();?>

