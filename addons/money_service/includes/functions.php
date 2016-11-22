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

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW
 

function add_new_money_service(){
	go_back();
	echo ""
	."<div class='main-content-region'>"
	."<h2>Add new Money service</h2><div class='edit-form'>"
	."<em>Add Money service first then add money_service photos later (optional)</em><br>"
	."<form action='./process.php' method='post'>"
	."<input type='hidden' name='add_new_money_service' value='yes'>"
	."<strong>For </strong>:<input type='number' size='5' name='price' maxlength='5' value='' placeholder='This amount in Naira eg (500)'> NGN, "
	."<strong>I will: </strong><input type='text' name='money_service_code' placeholder='Do this, or give you this' value=''><br>"
	."Description :<br><textarea name='description' placeholder='Full details'></textarea><br>"
	."Number of orders i can handle: (Quantity available) :<br><input type='text' size='3' name='stock' maxlength='3' value='' placeholder='stock'><br>"
	."<input type='hidden' name='seller' value='".$_SESSION['username']."'>"
	//."<input type='hidden' name='destination' value='".$_SESSION['prev_url']."'>"
	."Currency (Defaults to naira NGN) :<br><input type='text' size='3' name='currency' maxlength='3' value='NGN' placeholder='Currency'><br>"
	."<input type='submit' name='submit' value='submit' class='submit'>"
	."</form></div></div>";
	
	
	
	}


function edit_money_service(){
	
	go_back();
	$get_id = trim(mysql_prep($_GET['id']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service` WHERE `money_service_id`='{$get_id}'");
	
	while($result = mysqli_fetch_array($query)){
	
	echo "<div class='main-content-region'>"
	."<h2>Edit Money service</h2>"
	."<div class='edit-form'>"
	."<em>Add Money service first then add Money service photos later (optional)</em><br>"
	."<form action='process.php' method='post'>"
	."<input type='hidden' name='money_service_id' placeholder='money_service code' value='".$result['money_service_id']."'>"
	."<input type='hidden' name='edit_money_service' value='yes'>"
	."<input type='text' name='money_service_code' placeholder='money_service code' value='".$result['money_service_code']."'>"
	."<br>Description :<br><textarea name='description' placeholder='description'>".$result['description']."</textarea><br>"
	."Price :<br><input type='number' size='3' name='price' maxlength='5' value='".$result['price']."' placeholder='price'><br>"
	."Stock (Quantity available) :<br><input type='text' size='3' name='stock' maxlength='3' value='".$result['stock']."' placeholder='stock'><br>"
	."Seller :<br><input type='text' name='seller' value='".$result['seller']."' placeholder='Seller name'><br>"
	."Currency (Defaults to naira NGN) :<br><input type='text' size='3' name='currency' maxlength='3' value='".$result['currency']."' placeholder='Currency'><br>"
	."<input type='submit' name='save_edit' value='Submit' class='submit'>"
	."<input type='submit' name='delete_item' value='delete'>"
	."</form></div></div>";
	
	}
}


function add_new_money_service_type(){

	echo "<h2>Add Money service Type</h2>"
	."<div class='edit-form'>"
	."<form action='process.php' method='post'>"
	."Money service Type Name: <br><input type='text' name='money_service_type_name' placeholder='money_service type name' value=''><br>"
	."Description :<br><textarea name='description' placeholder='description' value='' rows='3'></textarea><br>"
	."<input type='hidden' name='add_new_money_service_type' value='yes'>"
	."<input type='submit' name='submit' value='Submit' class='submit'>"
	."</form></div>";
	
	}
	

function edit_money_service_type(){
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	
	$get_id = trim(mysql_prep($_GET['id']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service_type` WHERE `id`='{$get_id}'");
	
	while($result = mysqli_fetch_array($query)){
	
	echo "<h2>Edit Money service Type ".$result['money_service_type_name']."</h2>"
	."<div class='edit-form'>"
	."<form action='process.php' method='post'>"
	."money_service Type Name: <br><input type='text' name='money_service_type_name' placeholder='money_service type name' value='".$result['money_service_type_name']."'><br>"
	."Description :<br><textarea name='description' placeholder='description' rows='3'> ".$result['money_service_type_description']."</textarea><br>"
	."<input type='hidden' name='edit_money_service_type' value='yes'>"
	."<input type='submit' name='submit' value='Submit' class='submit'>"
	."</form></div>";
	
	}
	
}
	
	
function show_money_service_catalog($type='',$start='',$step=''){
	
	#money_service FILTER FORM 
	echo "<h1>Money Service Categories</h1>";
	echo '<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
	<select name="money_service_type">';
	
	$money_service_type_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `money_service_type_name` FROM `money_service_type`");
		while($money_service_type = mysqli_fetch_array($money_service_type_query)){
			echo '<option value="'.$money_service_type['money_service_type_name']. '" label="' .$money_service_type['money_service_type_name'].'" ' ;
			
			if($_POST['money_service_type'] === $money_service_type['money_service_type_name']){
			echo 'selected="selected"';
			}
			echo ">". $money_service_type['money_service_type_name']. '</option>';
		}
	echo '</select>
	<input type="submit" name="filter" value="Filter results">
	</form>';
	
	
	if(isset($_POST['money_service_type'])){
		$type = trim(mysql_prep($_POST['money_service_type']));
		$condition = "AND `money_service_type`='{$type}'";
	}elseif(isset($_GET['money_service_type'])){
		$type = trim(mysql_prep($_POST['money_service_type']));
		$condition = "AND `money_service_type`='{$type}'";
	 } else {
		 $type = "<h1>All Money services</h1> ";
		 $condition = '';
		 }
	
	#PAGER
	//$pager = show_more_execute($parent='',$number_holder='');
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service` WHERE `status`='approved'{$condition} LIMIT 20") 
	or die("ERROR in catalog query" . mysql_query());
	$holder = array();	
	$rows = mysqli_num_rows($query);
	
	echo "<h1>" .ucfirst($type) ."</h1>";
	
			
	if(isset($_SESSION['username'])){ 
		
		$total_pending = total_pending_money_services();
		echo "<div align='center'><a href='".ADDONS_PATH.'money_service/?add_new=money_service'."'>:: Add new money service </a> &nbsp "
		."<a href='".ADDONS_PATH .'money_service/?show=pending_approval' ."'>:: money services waiting to be approved ({$total_pending})</a></div>";
		}

	
	while ($result = mysqli_fetch_array($query)){
	
	
	$title = substr($result['money_service_code'],0,28);
	$description = $result['description'];
	$desc = substr($description,0,60);
	$image_name = $result['money_service_code'] ." money_service";
	
		$pic = get_linked_image($subject=$image_name);
		
		echo "<div class='mosaic-block'>"
				."<div class=''>
					<div >
						<a href='" .ADDONS_PATH ."money_service?show=money_service&money_service_code=".$result['money_service_code']
						."'> NGN " .$result['price'] 
					."</a></div>
				</div>";
				foreach($pic as $picture){
				echo '<div class="img-responsive">'."<a href='" .ADDONS_PATH ."money_service?show=money_service&money_service_code=".$result['money_service_code']."'> "
					. $picture ."</a>".'</div>';
			}
				
			echo "</div>";
		
		}	

}
		

function show_money_service($item='') {
	

	if(isset($_GET['money_service_code'])) {

		$item = trim(mysql_prep($_GET['money_service_code'])); 
		$parent = $item . " money_service";	  

		$query = mysqli_query($GLOBALS["___mysqli_ston"], 
		"SELECT * FROM `money_service` " .
		'WHERE money_service_code="' .
		$item .
		'" ' .
		" limit 1") or die("Failed to get selected item" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


		$result= mysqli_fetch_array($query);



		# IF OWNER OR MANaGER, THEN SHOW EDIt link 
		if(is_admin()){

			echo '  <section class="top-left-links">
				
			</section>';
			}
		


		 # GET PAGE IMAGES
      $is_mobile = check_user_agent('mobile');
      if($is_mobile){
      $size='medium';
      } else {
      $size='large';
      }
		$picture = get_linked_image($subject=$parent,$pic_size=$size,'');
	
		// Echo the Page 
		if(is_author() || is_admin()){
		$title = '<ul>
					<li class="float-right-lists">
						<a href="'.ADDONS_PATH .'money_service?action=edit_money_service&id='.$result['money_service_id'].'&money_service_code='. $result['money_service_code'].'"> Edit page </a></li>			
				</ul>';
				
		} else { $title = ''; }
				$title .= "<div class='sweet_title'>" . ucfirst($item) ."</br></div>";
		
	}
	
	$description = "<h2>Details</h2><div class='money_service-description'>" .urldecode($result['description']) ."</div>" ;
	
		# OUTPUT PAYMENT LINK IF PAYMENT IS ACCEPTED ON THIS PAGE     
		 $output = $output."</div>";

	
	#SHOW PRICE 
    $price = $result['price'];
    $stock = $result['stock'];
    $seller = $result['seller'];
     
    #SHOW ADD TO CART LINK ONLY IF STOCK IS AVALABLE OR GREATER THAN ZERO
    
    if($stock >=1){
    $add_to_cart_button = '<form action="'.ADDONS_PATH.'money_service/cart.php" method="post">
							
							<input type="hidden" name="money_service_code" value="'.$_GET['money_service_code'].'">
							<input type="hidden" name="price" value="'.$result['price'].'"><br>
							<input type="hidden" name="seller" value="'.$result['seller'].'"><br>
							Quantity: <input type="number" name="quantity" maxlenght="4" size=3 value="1" >
							<input type="submit" name="submit" class="add-to-cart" value="'.'Add to cart">
							</form>';
    $add_to_cart_link = '<a href="'.ADDONS_PATH.'money_service/cart?money_service_code='.$_GET['money_service_code'].'">Add to cart</a>';
    } else {
		
	 $add_to_cart_button = "<br><em>Sorry!. This (money_service or service) is not available or is out of stock!</em>";
	 $add_to_cart_link =   "<br><em>Sorrry!. This (money_service or service) is not available or is out of stock!</em>";
	 
	
	}

    
	$output = array('title'=>$title, 'picture'=>$picture, 'price'=>$price, 'description'=>$description, 'stock'=>$stock, 'seller'=>$seller,
	 'add to cart button'=>$add_to_cart_button, 'add to cart link'=>$add_to_cart_link, 'pic_upload'=>$pic_upload);	
	return $output;
}

function total_pending_money_services(){
	$user = $_SESSION['username'];
	if(is_admin()){
			$where_condition = "WHERE `status`='pending'";
			} else {
				$where_condition = "WHERE `status`='pending' AND `author`='{$user}'";
				}
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service` ".$where_condition);
	$total_pending = mysqli_num_rows($query);
	$_SESSION['total_pending_money_services'] = $total_pending;
	return $total_pending;
	}

function show_money_service_admin_links(){
	$is_admin = is_admin();
	$user = $_SESSION['username'];

	$total_pending = total_pending_money_services();
	echo 
	"<div class='u-full-width'><ul><br>";
		if(isset($_SESSION['username'])){ 
		echo "<li><a href='".ADDONS_PATH.'money_service/?add_new=money_service'."'>Add new Money service </a></li>"
		."<li><a href='".ADDONS_PATH .'money_service/?show=pending_approval' ."'>Money services waiting to be approved  (<em>Total:{$total_pending}</em>)</a> </li>";
		}
		if($is_admin){ echo
		"<li><a href='".ADDONS_PATH .'money_service/?show=money_service' ."'>List Money services</a></li>"
		."<li><a href='".ADDONS_PATH .'money_service/?show=money_service_type' ."'>List Money service TYPES </a></li>"
		."<li><a href='".ADDONS_PATH .'money_service/orders.php?show=orders' ."'>Orders List</a></li>"
		."<br>"
		
	."</ul></div>";
		}
	
}


function show_money_services_list($approval=''){
	
	$user = $_SESSION['username'];
		
	#Title prefix will control titles on related pages
	$title_prefix = ucfirst($approval);
	
	# Show money_services of a particular approval state or show all if none is set
		
	if(isset($_POST['money_service_list_limit'])){
		$post_limit = $_POST['money_service_list_limit'];
	} else { $post_limit = 10;}
	if(isset($_POST['money_service_list_number_holder'])){	
		$step = $_POST['money_service_list_number_holder'];
	} else{ $step = 0; }
	
	if(isset($_POST['clear_money_service_list_values'])){
			unset($_POST);
			$number_holder = '';
			$post_limit = 10;
			$step = 0;
			}	
			
		$limit = "LIMIT ". $step .", ".$post_limit;
		$number_holder = $post_limit + $step;
	if($approval==='pending'){
		if(!$is_admin){
			$where_condition = "WHERE `status`='pending'";
			} else {
				$where_condition = "WHERE `status`='pending' AND `author`='{$user}'";
				}
		
		
	} else if($approval==='approved'){
		$where_condition = "WHERE `status`='approved'";
		
	} else if($approval==='rejected'){
		$where_condition = "WHERE `status`='rejected'";
	
	} else if($approval===''){
		$where_condition = "WHERE `status`='approved'";
	
	}else{ 
		$where_condition = "";
	}
	
	# Query DB based on approval settings
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service` " .$where_condition." ORDER BY `money_service_id` desc {$limit}") or die 
	("Error selecting money_services". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$count = mysqli_num_rows($query);
	# START DISPLAY
	
	echo "<h1>{$title_prefix} Money services</h1>";
	
	if(empty($title_prefix)){ // only show when displaying all money_services
	# Show add new money_service button
	echo "<a href='". ADDONS_PATH.'money_service/?add_new=money_service'."'><h3>Add new Money service</h3></a>";
	}
	# Start table
	echo "<br><table class='table'>"
	."<thead><th> &nbspmoney_service Id</th>";
	
	if(empty($title_prefix)){ echo"<th>Price</th><th>Stock</th></thead>";
		
	}else{ echo "<th>Details</th><th>Status</th></thead>"; }
	
	if($count < 1){status_message('alert', 'No results here!');}
	
	while($result = mysqli_fetch_array($query)){
		echo "<tr>" 
				 ."<td> &nbsp <a href='".ADDONS_PATH .'money_service/?show=money_service&money_service_code='.$result['money_service_code'] ."'>".$result['money_service_code'] ."</a>"
				 ."<br><span class='tiny-text'><em><a href='" .ADDONS_PATH."money_service/?action=edit_money_service&id=".$result['money_service_id'] ."&money_service_code=".$result['money_service_code'] ."'>edit |</a>"
				 ."<a href='" .ADDONS_PATH."money_service/process.php?action=delete&id=".$result['money_service_id'] ."'> delete</a></em></span></td>";
				 
				 
				 if(empty($title_prefix)){ echo			 
					"<td>".$result['price'].".00 <br><span class='tiny-text'>". $result['currency'] .'</span>'."</td>"
					."<td> ".$result['stock']." items </td>";
				 
				 } else { 
					 #This should be an approval queue so
					 #Show options to approve or reject money_service
					 
					  echo
					  "<td>" 
					."<strong>Description:</strong><br>".$result['description']
					."<hr><strong>Price : </strong><br>".$result['price'].".00 <span class='tiny-text'>". $result['currency'] .'</span>'
					."</td>"
					."<td> ".ucfirst($result['status']);
					
					if(is_admin()){
					echo " <span class='tiny-text'><br>"
					."<a href='" .ADDONS_PATH."money_service/process.php?action=approve_money_service&id=".$result['money_service_id'] ."'><button class='button-primary'> Approve </button> &nbsp</a>"
					."<a href='" .ADDONS_PATH."money_service/process.php?action=reject_money_service&id=".$result['money_service_id'] ."'><button class='button'> Reject </button></a>"
					."</span>";}
					
					echo "</td>"; 
					
						}
			echo "</tr>"; 
			
						
	}
	echo "</table><br>"; 
	
	#Show pager links	
	echo '<p align="center">';

 echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='money_service_list_limit' value='10'>
		<input type='hidden' name='money_service_list_number_holder' value='".$number_holder."'>
		<input type='submit' name='submit' value='show older money_services' class='button-primary'>
		<input type='submit' name='clear_money_service_list_values' value='reset'>
		</form></div></div>";
		
}


function add_to_cart(){

	if(isset($_SESSION['username'])){ // if user is logged in
	
		$buyer = $_SESSION['username'];
		$money_service_code = trim(mysql_prep($_POST['money_service_code']));
		$price = trim(mysql_prep($_POST['price']));
		$quantity = trim(mysql_prep($_POST['quantity']));
		$submit = trim(mysql_prep($_POST['submit']));
		$seller = trim(mysql_prep($_POST['seller']));
		
		if($submit ==='Add to cart'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `money_service_basket`(`id`, `money_service_code`, `buyer`, `seller`, `quantity`, `price`) 
		VALUES('', '{$money_service_code}', '{$buyer}', '{$seller}', '{$quantity}', '{$price}')") or die("Failed to Add to cart!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		if($query){
			session_message("success", "money_service added to cart!");
		
			}
		} 
		else {
			 status_message("You have to Log in first!");
			 }
	
}
	

function change_money_service_details(){ 
	
	if(is_admin()){
		$money_service_name = trim(mysql_prep($_POST['money_service_name']));
		$money_service_phone = trim(mysql_prep($_POST['money_service_phone']));
		$money_service_email = trim(mysql_prep($_POST['money_service_email']));
		$cart_name = trim(mysql_prep($_POST['cart_name']));
		$money_service_address = trim(mysql_prep($_POST['money_service_address']));
	
		echo '<h1>money_service settings</h1>
			
			<form method="post" action="'.$_SERVER['PHP_SELF'].'">
			<input type="text" name="money_service_name" placeholder="money_service name" value="">
			<input type="tel" name="money_service_phone" placeholder="money_service phone" value="">
			<input type="email" name="money_service_email" placeholder="money_service email" value="">
			<input type="text" name="cart_name" placeholder="cart name" value="">
			<textarea name="money_service_address" placeholder="money_service address"></textarea>
			<input type="submit" name="submit" value="Change cart name" class="button-primary">
			</form>';

	if($_POST['submit'] ==="Change cart name"){
		
		$update_cart_name_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `money_service` SET `money_service_name`='{$money_service_name}', `money_service_phone`='{$money_service_phone}', `money_service_email`='{$money_service_email}', `money_service_address`='{$money_service_address}', `cart_name`='{$cart_name}' WHERE `id`='0'") 
		or die("Failed to Update cart name!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 }
			
			if($update_cart_name_query){
				status_message("success", "Cart Updated!!");
				
				}					
	}
}


###### ORDERs ##########

function show_order_form(){
	if(isset($_POST['money_service_code'])){
		
		$id = $_POST['id'];
		$buyer = $_SESSION['username'];
		$money_service = trim(mysql_prep($_POST['money_service_code']));	
		$quantity = trim(mysql_prep($_POST['quantity']));
		$price = trim(mysql_prep($_POST['price'])) * $quantity;
		
		#CHECK THE BUYER BALANCE
		$balance_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `site_funds_amount` FROM `user` WHERE `user_name`='{$buyer}'") or die("Failed to get Buyer details" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$balance_result = mysqli_fetch_array($balance_query);
		
		$balance = $balance_result['site_funds_amount'];
		
		echo "<em>Your account balance is</em><strong> NGN: {$balance}</strong>";
		# Show order form
		echo '<form action="'.ADDONS_PATH.'money_service/checkout.php" method="post">
		<hr>
		You are Ordering <span class="money_service-stock"> <big>'.$quantity.'</big></span> &nbsp<strong><big> '.$money_service .'</big></strong><br>
		For the price of <span class="money_service-price"> NGN '.$price .'.00 .</span>
		<br><hr> <h3 align="center"><em>Continue if you agree, or exit if you dont.</em></h3>
		<input type="hidden" name="id" value="'.$id .'">
		<input type="hidden" name="quantity" value="'.$quantity .'">
		<input type="hidden" name="money_service_code" value="'.$money_service .'">
		<input type="hidden" name="buyer" value="'.$buyer .'">
		<input type="hidden" name="price" value="'.$price .'">
		<input type="hidden" name="balance" value="'.$balance .'">
		Order details:<br><textarea name="order_details" rows="5">Any detail you want us to note</textarea>
		<input type="submit" name="submit" value="Place order" class="submit">
		</form>';
	} 
}


function checkout(){
	
	# SHOW ANY MESSAGES
	show_session_message();
	
	#VARIABLES
	$id = $_POST['id'];	
	$buyer = $_SESSION['username'];
	$seller = $_POST['seller'];
	$money_service_code = $_POST['money_service_code'];
	$quantity = $_POST['quantity'];
	$price = $_POST['price'] * $quantity;
	$order_details = $_POST['order_details'];
	$balance = $_SESSION['site_funds_amount'];
	$redirect_url = ADDONS_PATH.'money_service/catalog';
	
	
	
	if(isset($_POST['submit'])){
		if($_POST['submit']==='Place order'){
			if($balance >= $price){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `money_service_orders`
			(`order_id`, `buyer`, `seller`, `quantity`, `price`, `order_details`, `status`) 
			VALUES ('', '{$buyer}', '{$seller}', '{$quantity}', '{$price}', '{$order_details}', 'completed')");
			
			#REDUCE BALANCE by PRICE
			$balance = $balance - $price;

			#UPDATE BALANCE
			$balance_update = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`='{$balance}' WHERE `user_name`='{$buyer}'")
			 or die("Balance update failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				
			#UPDATE BASKET
			$cart_update_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `basket` WHERE `id`='{$id}'");
			
			#UPDATE STOCK
			$select_stock_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `stock` FROM `money_service` WHERE `money_service_code`='{$money_service_code}' LIMIT 1");
			$stock_query_result = mysqli_fetch_array($select_stock_query);
			$former_stock = $stock_query_result['stock'];
			$new_stock = $former_stock - $quantity;
			
			#echo "New stock is ".$new_stock; //testing
			
			$stock_update = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `money_service` SET `stock`='{$new_stock}' WHERE `money_service_code`='{$money_service_code}'");
			} else { session_message("alert","You do not have enough funds!");
				redirect_to(ADDONS_PATH.'money_service/cart.php');
				
					}
		}
	} 
	if($balance_update){
		$_POST ='';
		status_message("success", "Transaction Successful!");
		echo 'Your balance is now <strong>NGN :'.$balance .'.00</strong> 
		<p align="center"><a href="'.ADDONS_PATH.'money_service/cart.php"> Return to Basket </a></p>';
					} 
	if(!isset($_SESSION['username'])){
						
				are_you_lost();		
						}
}


function show_basket(){
	show_session_message();
	
	#SHOW CATALOG LINK
	echo '<section class="top-left-links">
			<ul>
				<li class="float-right-lists">
					<a href="'.ADDONS_PATH .'money_service/catalog">Catalog </a></li>
			</ul>
		</section>';
	
	if(isset($_SESSION['username'])){
		if($_SESSION['role'] ==='authenticated' || $_SESSION['role'] ==='manager' || $_SESSION['role'] ==='admin'){
			#VARIABLES
			$user = $_SESSION['username'];
			$total=0;
			$remove_id = trim(mysql_prep($_GET['remove_item']));
			
			# REMOVE ITEM IF REQUESTED
			if(isset($_GET['remove_item'])){
			$remove_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `money_service_basket` WHERE `id`='{$remove_id}'");
			
			if($remove_query){ #ALERT USER THAT ITEM HAS BEEN REMOVED
				status_message('alert', 'item removed from basket!');
				}
			}	
			$select_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service_basket` WHERE `buyer`='{$user}'");
			
			#SHOW CART
			echo '  <h1 align="center">Basket</h1>
					<table class="table">
						<thead><th> Money service code </th><th> Price </th></thead>';
			while($result = mysqli_fetch_array($select_query)){
				$money_service = $result['money_service_code'];
				$pic =  get_linked_image($money_service, $limit=1) ;
				echo '<tr><td>'.$result['money_service_code']. ' <span class="money_service-stock">Quantity :<big>'.$result['quantity'].'</big></span><br><span class="cart-image">'.$pic['0'] .
							'</span><br><a href="'.ADDONS_PATH.'money_service/cart.php?remove_item='.$result['id'].'"> <em>remove</em></a></td>
							
							<td><span class="money_service-price"> NGN '.$result['price'].
							
							#SHOW THE CHECKOUT BUTTON
							
								'</span><br><form method="post" action="'.ADDONS_PATH.'money_service/checkout.php'.'">
								<input type="hidden" name="id" value="'.$result['id'].'">
								<input type="hidden" name="money_service_code" value="'.$result['money_service_code'].'">
								<input type="hidden" name="quantity" value="'.$result['quantity'].'">
								<input type="hidden" name="price" value="'.$result['price'].'">
								<input type="hidden" name="buyer" value="'.$result['buyer'].'">
								<input type="submit" name="pay" value="Pay Now" class="add-to-cart">
								</form>
								</td></tr>';
					
				} echo '</table>';
			if(mysqli_num_rows($select_query) < 1){
				echo "<br><p align='center'><em>You do not have any items in your Basket!</em></p>";
				}
			} else {deny_access();}
		} else { log_in_to_continue();}
	}


function show_money_service_type_list(){
	go_back();		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service_type` ORDER BY `id`") or die("Failed to fetch Money services list" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	echo "<h1>Money service types</h1>";
	# Show add new money_service type
	echo "<a href='". ADDONS_PATH.'money_service/?add_new=money_service_type'."'><h3>Add new Money service type</h3></a>";
	
	# Start table
	echo "<br><table class='table'>"
	."<thead><th> &nbspId</th><th>money_service Type Name</th><th>Description</th></thead>";
	
	while($result = mysqli_fetch_array($query)){
		echo "<tr>" 
				."<td> &nbsp".$result['id'] ."</td>"
				 ."<td> &nbsp <a href='".ADDONS_PATH .'money_service/?money_service_type_name='.$result['money_service_type_name'] ."'>".$result['money_service_type_name'] ."</a>"
				 ."<br><span class='tiny-text'><em><a href='" .ADDONS_PATH."money_service/?action=edit_money_service_type&id=".$result['id'] ."'>edit |</a>"
				 ."<a href='" .ADDONS_PATH."money_service/?action=delete_money_service_type&id=".$result['id'] ."'> delete</a></em></span></td>"
				 ."<td> ".$result['money_service_type_description'] ."</td>"
			."</tr>";
	}
	echo "</table><br>";
	
	# Repeat add new money_service button
	#echo "<a href='". ADDONS_PATH.'money_service/?add_new=money_service_type'."'><h3>Add new money_service type</h3></a><br>";
	
	
	}
	
function delete_money_service_type(){
	
	if($_GET['action'] == 'delete_money_service_type' && !empty($_GET['id'])){
		$id = trim(mysql_prep($_GET['id']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `money_service_type` WHERE `id`='{$id}'") 
		or die("Failed to delete the money_service type" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			session_message('alert','Money service type deleted successfully');
			}
		redirect_to(ADDONS_PATH.'money_service');
		}
}

function show_pending_money_services_queue(){
	
	show_money_services_list('pending');
	
	}	

function show_recent_approved_money_services(){

	show_money_services_list('approved');
	}

function show_recent_rejected_money_services(){

	show_money_services_list('approved');	
	}


function show_orders_list(){
	
	echo "<br><form method='post' action='".$_SERVER['PHP_SELF']."'>
		Get Orders made to this seller:<br>
		<input type='text' name='seller' placeholder='seller' value=''>
		<input type='submit' name='submit' value='Get orders' class='button-primary'>
		</form>";
	
	
	
	$num = 0;
	$admin = is_admin();
	
	if($admin){
	
			if(isset($_POST['seller'])){
				
				$seller = trim(mysql_prep($_POST['seller'])); 
				$condition = " WHERE `seller`='{$seller}'";
				} else {
					
					 $condition=''; }
				
				} 
	if(!empty($_SESSION['username']) && !$admin){
		$seller = $_SESSION['username'];
		$condition = " WHERE `seller`='{$seller}'";
		}
		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service_orders` ".$condition." ORDER BY order_id")
	 or die("Order selection failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 #START OUTPUT
	 echo "<div class='container'>";
	 
	 while($result = mysqli_fetch_array($query)){
		 $num++;
		 echo $num . '. <big>'. $result['money_service_code']."</big><br><strong> Buyer : </strong>". $result["buyer"]." "
		 ."<span class='money_service-stock'>Quantity : <big>".$result['quantity'] ."</big></span> "
		 ."Total price : <strong>NGN ".$result['price'].".00 </strong><br>".
		"<strong>Order details :<br></strong><em>". $result['order_details']."</em><hr>";
		 }
	echo "</div>";


	
}

function show_user_money_services($user=''){
	if(!empty($_GET['user'])){
	$user = trim(mysql_prep($_GET['user']));
	}elseif(empty($_GET['user']) && $user == ''){
	$user = $_SESSION['username'];
	}
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `money_service` WHERE `author`='{$user}' ORDER BY money_service_id DESC {$limit}") 
	or die('Cannot select User Money service' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	echo "<ul>";
	while($result = mysqli_fetch_array($query)){
		
		echo "<li><strong><a href='".ADDONS_PATH."money_service?show=money_service&money_service='>For </strong>".$result['price'].' NGN, <strong></strong>'.$result['description'].'</a></li><hr>';
		}
	echo "</ul>";

	echo '<a href="'.ADDONS_PATH.'money_service/?add_new=money_service"> + Add new money service</a>';
	}
	
 // end of money_service functions file
 // in root/addons/money_service/includes/functions.php
?>
