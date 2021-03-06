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
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
include_once($r .'includes/functions.php'); #do not edit
#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW



# OUTPUT HEADER (OPTIONAL)
 start_page();
 
function fundraiser_settings(){
	
	echo '<form action="'.$_SESSION['current_url'].'" method="post">
	<input type="text" name="fundraiser_duration" placeholder="Duration">
	<input type="submit" name="settings" value="Save settings"> 
	</form>';
	
}

# ADD fundraiserS
function add_fundraiser() {
	//calculate end date
$now = date('l jS F');
$one_month = date('l jS F', strtotime('+4 weeks'));
			
$minimum_balance = 200;
$current_user_balance = get_user_funds();

echo 'Minimum required amount :<b>' . $minimum_balance .'</b><br>';
echo 'Your current balance :<span class="green-text">' . $current_user_balance .'</span><br>';

$form = '<div class="edit-form page_content">
	<form method="POST" action="process.php" class="padding-10">
	<input type="hidden" name="action" value ="insert" >
	<input type="hidden" name="author" value ="'.$_SESSION['username'].'" >
	<input type="hidden" name="back_url" value ="'.$_SERVER['HTTP_REFERER'] .'" >
	Fundraiser name <input type="text" name="fundraiser_name" class="menu-item-form" placeholder="fundraiser name" >
	<br>Active:(Yes) <input type="checkbox" name="visible" value="1" checked="checked" class="checked">

	<br><br>Position:(<em>Starting from 0, higher numbers will appear last</em>)<br><input type="text" name="position" value="1" size="3" maxlength="3">
	<br>Reason:<br><textarea name="reason" id="content-area" size="8"></textarea>
	<br>Perks (if any):<br><textarea name="perks" id="content-area" size="8"></textarea>
	<br> Target Amount : <br>NGN <input type="number" name="target_amount" maxlength="" value="">
	<br> Start date: (Campaign will end exactly 45 days after today '; $form .= $date; $form.=')<br>
	<input type="hidden" name="start_date" value="'.$now.'" > <br>
	<input type="hidden" name="end_date" value="'.$one_month.'" > <br>
	Make project :<input type="checkbox" name="make_project" value="yes" class="checked">(Select to automatically create a project page for this fundraiser)<br>
	<input type="submit" name="submitted" value="Add fundraiser" class="submit">

	</form></div>';// End of Form

	if(is_logged_in() && ($minimum_balance < $current_user_balance) ){
	// show form
	echo $form;	  
		
	} else {
		status_message('alert','You do not have sufficient funds to do this.');
		}
	

}
function is_fundraiser_owner(){
	if($_SESSION['username'] == $_SESSION['author']){
		return true;
		} else {
			return false;
			}
	}

function add_perk(){ // Benefit to donors
	
	if(isset($_POST['save_perk'])){
		$fundraiser_id = $_SESSION['fundraiser_id'];
		$donation_amount = 0 + mysql_prep($_POST['amount']);
		$amount_available = 0 + mysql_prep($_POST['amount_available']);
		$reward = trim(mysql_prep($_POST['reward']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO fundraiser_perks(`id`,`fundraiser_id`,`donation_amount`,`reward`,`amount_available`,`amount_claimed`) 
		VALUES('0','{$fundraiser_id}','{$donation_amount}','{$reward}','{$amount_available}','0')") 
		or die('Failed to save perk '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			session_message('success','Perk saved!');
			redirect_to($_SESSION['current_url']);
			}
		}
	if(is_fundraiser_owner()){	
	echo '<div class="title">Add perks</div><form method="post" action="'.$_SESSION['current_url'].'">
	<input type="number" name="amount" placeholder="donation amount" class="form-control">
	<input type="number" name="amount_available" placeholder="No of slots available" class="form-control">
	<textarea name="reward" placeholder="reward" class="form-control"></textarea>
	<input type="submit" name="save_perk" value="Add perks" class="btn btn-primary">
	</form>';
	}	
}

function claim_fundraiser_perk(){
	
	if($_POST['claim_perk'] == 'Claim this'){
		$balance = get_user_funds();
		donate($reciever="{$_SESSION['author']}");
		
		}
	}
	

function show_fundraiser_perks(){
	$fundraiser_id = mysql_prep($_SESSION['fundraiser_id']);
	$reciever = $_SESSION['author'];
	if(is_fundraiser_owner()){
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM fundraiser_perks where fundraiser_id='{$fundraiser_id}' order by id desc")
	 or die('Could not select perks '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 
	 
	 while($result = mysqli_fetch_array($query)){
		echo '<div class="page-content">';
		echo '<div class="title clear">'.'$'.$result['donation_amount'].'<hr></div>
		 <div class="">'.
		 $result['reward'];
		 if(is_logged_in()){
		 '<form method="post" action="process.php">
		 <input type="hidden" name="amount" value="'.$result['donation_amount'].'">'.
		 "<input type='hidden' name='action' value='donate'>
		<input type='hidden' name='current_amount' value='".$current_amount."'>
		<input type='hidden' name='user_balance' value='".$balance."'>
		<input type='hidden' name='reciever' value='".$reciever."'>
		<input type='hidden' name='fundraiser_name' value='".$_GET['fundraiser_name']."'>
		<input type='hidden' name='giver' value='".$_SESSION['username']."'>
		<input type='hidden' name='add_funds' value='yes'>
		<input type='hidden' name='reason' value='support ".$_GET['fundraiser_name']." fundraiser'>".
		 "<input type='submit' name='submit' value='Claim this' class='btn btn-primary'>";
		 
		 echo '</form>'; 
		 }
		// echo '</div>';
		 if(is_logged_in()){
		 '<form method="post" action="process.php">
		 <input type="hidden" name="amount" value="'.$result['donation_amount'].'">'.
		 "<input type='hidden' name='action' value='donate'>
		<input type='hidden' name='current_amount' value='".$current_amount."'>
		<input type='hidden' name='user_balance' value='".$balance."'>
		<input type='hidden' name='reciever' value='".$reciever."'>
		<input type='hidden' name='fundraiser_name' value='".$_GET['fundraiser_name']."'>
		<input type='hidden' name='giver' value='".$_SESSION['username']."'>
		<input type='hidden' name='add_funds' value='yes'>
		<input type='hidden' name='reason' value='support ".$_GET['fundraiser_name']." fundraiser'>".
		 "<input type='submit' name='submit' value='Claim this' class='btn btn-primary'>";
		 
		 echo '</form>'; 
		 //
		 if(is_fundraiser_owner()){
		
		 echo "<span class='tiny-text'> <a href='".ADDONS_PATH."fundraiser/?delete_perk=".$result['id']."'> delete </a></span>";
		 }
		 }
		 echo '</div>';
		 echo '</div>';
	}
	}
	
		add_perk();
}


function delete_fundraiser_perk(){
	
	if(isset($_GET['delete_perk']) && is_fundraiser_owner()){
		
		$id = mysql_prep($_GET['delete_perk']);
		
		$query = mysqli_query($GLOBALS['___mysqli_ston'],"DELETE FROM fundraiser_perks WHERE id='{$id}' LIMIT 1") or die('Error deleting perk '.mysqli_error());
		if($query){
			//~ echo 'i am here';
			session_message('alert', 'Perk deleted');
			redirect_to($_SESSION['prev_url']);
			}
		}
	}


# LIST fundraiserS

function show_fundraiser_lists($category='',$status='') {
	if($category != '' && $status == ''){
		$condition = "WHERE category='{$category}' and status='active'";
		}
	else if($category != '' && $status != ''){
		$condition = " WHERE category='{$category}' and status='{$status}'";		
	}
	
	else if($category == '' && $status != ''){
		$condition = " WHERE status='{$status}'";		
	}
	
	else{
		$condition = "";		
	}
	
	echo '<h2>'.$status.' fundraisers</h2>';
	
	if(url_contains('page_name=home')){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser` {$condition} ORDER BY `id` DESC 
  LIMIT 0 , 3") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else {
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser` {$condition} ORDER BY `id` DESC 
  LIMIT 0 , 30") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  }
  #echo "Data fetched succesfully"; //testing
  $fundraiserlist = '';
  $fundraiserlist = $fundraiserlist . "<table class=''>";
  
  
  while($result = mysqli_fetch_array($query)){
	  $pic = show_user_pic($user=$result['author'],$pic_class='img-rounded',$length='');
	  
	$time = strtotime($result['end_date']);
	$remaining = time_elapsed($time);
	
  	$fundraiserlist = $fundraiserlist 
	 . '<tr><td class="gainsboro">'.
	$pic['thumbnail']
  .'</td><td><a href="' .ADDONS_PATH .'fundraiser/?action=show&fid=' .$result['id'] .'"> ' 
  . ucfirst(str_ireplace('-',' ',$result['fundraiser_name']))
  . '</a> <span class="timeago">'.$remaining.'</span><br>';
  
  
  # Print goal bar
	$fundraiserlist .= 
	'<span class="green-text pull-right tiny-text">Raised: N'.number_format($result['amount_raised']).'.00</span><progress class="progress-mini pull-right" value="'.$result['amount_raised'].'" min="0" max="'.$result['target_amount']. '"></progress>'.
	'<span class="target-text">Target:<span class="target-amount"> NGN'.number_format($result['target_amount']).'.00</span></span>';
	
	if(is_admin() || $result['author'] == $_SESSION['username']){
   $fundraiserlist .=  
   '<br><span class="tiny-edit-text"> 
   <a href="'
  . ADDONS_PATH ."/fundraiser/edit/?" 
  . 'action='
  . 'edit&'
  . 'fid='
  . $result['id']
  . '" '
  . '>edit </a> &nbsp; <a href="'
  . ADDONS_PATH ."fundraiser/process.php?" 
  . 'action='
  . 'delete&'
  . 'fid='
  . $result['id']
  . '&deleted='
  . 'jfldjff7'
  . '" '
  . '>delete </a></span>';
  }
	$fundraiserlist .= "<span class='grey-text'> <em>{$result['status']}</em></span> </td>";
		}

  
  $fundraiserlist .= "</tr></table>";
  
    echo $fundraiserlist;
    

  }
   


function show_my_fundraisers() {
	
		$viewing = user_being_viewed();		
		$user = trim(mysql_prep($viewing));

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser` WHERE author='{$user}' ORDER BY `id` DESC 
  LIMIT 0 , 2") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$num = mysqli_num_rows($query);
		
	if(!empty($num)){	 
	//echo "Data fetched succesfully"; //testing
  $fundraiserlist = '';
  $fundraiserlist = $fundraiserlist . "<table class=''>";
  
  
  while($result = mysqli_fetch_array($query)){
	  $pic = show_user_pic($user=$result['author'],$pic_class='img-rounded',$length='');
	  
	  $time = strtotime($result['end_date']);
	  $remaining = time_elapsed($time);
	  
  	$fundraiserlist = $fundraiserlist 
	 . '<tr><td class="gainsboro">'.
	$pic['thumbnail']
  .'</td><td><a href="' .ADDONS_PATH .'fundraiser/?action=show&fid=' .$result['id'] .'"> ' 
  . ucfirst(str_ireplace('-',' ',$result['fundraiser_name']));
  
  if($_SESSION['status']  == 'active'){
  $fundraiserlist .= '</a><span class="timeago">'.$remaining.'</span><br>';
  }
  
  
  # Print goal bar
	$fundraiserlist .= 
	'<span class="green-text pull-right tiny-text">Raised: N'.number_format($result['amount_raised']).'.00</span><progress class="progress-mini pull-right" value="'.$result['amount_raised'].'" min="0" max="'.$result['target_amount']. '"></progress>'.
	'<span class="target-text">Target:<span class="target-amount"> NGN'.number_format($result['target_amount']).'.00</span></span>';
	
	if(is_admin() || $result['author'] == $_SESSION['username']){
   $fundraiserlist .=  
   '<br><span class="tiny-edit-text"> 
   <a href="'
  . ADDONS_PATH ."/fundraiser/edit/?" 
  . 'action='
  . 'edit&'
  . 'fundraiser_name='
  . $result['fundraiser_name']
  . '&tid='
  . $result['id']
  . '" '
  . '>edit </a> &nbsp; <a href="'
  . ADDONS_PATH ."fundraiser/process.php?" 
  . 'action='
  . 'delete&'
  . 'fundraiser_name='
  . $result['fundraiser_name']
  . '&deleted='
  . 'jfldjff7'
  . '" '
  . '>delete </a></span>';
  }
	$fundraiserlist .= "<span class='grey-text'> <em>fundraiser</em></span></td>";
		}

  
  $fundraiserlist .= "</tr></table>";
  
    echo '<br><h2>My active fundraisers</h2>'.$fundraiserlist;
	} // end if not empty $num
	else { echo '<br><h2>--No active fundraisers--</h2>';
		if($viewing == $_SESSION['username']){
		echo '<a href="'.ADDONS_PATH.'fundraiser?action=add-fundraiser"><em> Create a fundraiser </em></a>';
		}
	}

  }

 
# EDIT fundraiserS  
function edit_fundraiser(){
$now = date('l jS F');
$one_month = date('l jS F', strtotime('+4 weeks'));

if(url_contains('edit')){
$fundraiser = mysql_prep($_GET['fid']); 
#echo $block; // Testing
}	

if(isset($fundraiser)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from fundraiser WHERE id='{$fundraiser}' LIMIT 1") 
	or die("Failed to get selected fundraiser" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 $result = mysqli_fetch_array($query);
}

if(($result['author'] == $_SESSION['username'] || is_admin()) && $result['status'] == 'pending'){	

	
	//Check if update is requested and show edit form
	 if(url_contains('edit')){
	 	
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "select * from fundraiser " .
	 'where id="' .
	 $fundraiser .
	 '" ' .
	 " limit 1") or die("Failed to get selected fundraiser" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 $result = mysqli_fetch_array($query);
	 
	 #DETECT and switch between sections and fundraisers
	 
	 if(isset($fundraiser)){
	 $target = $fundraiser;
	 $fundraiser_name = mysql_prep($_SESSION['fundraiser_name']);
	 //$route = '&fundraiser_name=';
	 $end = 'fundraiser';
	 
	 
	 
	 // now we show the fundraiser edit form
	 echo "<strong> You are editing <em>&nbsp" . $target ." {$end} </em></strong>";
	 echo '<p align="center">Go to <a id="view-fundraiser" href="' .ADDONS_PATH .'fundraiser?action=show&fid='.$target .'"><strong><big> ' 
  . $fundraiser_name .' ' .$end
  . '</big></strong></a></p>';
	 	

$form = '<div class="edit-form">
<form method="POST" action="../process.php">
<input type="hidden" name="action" value ="update" >
<input type="hidden" name="author" value ="'.$_SESSION['username'].'" >
<input type="hidden" name="back_url" value ="'.$_SERVER['HTTP_REFERER'] .'" >
Fundraiser name <input type="text" name="fundraiser_name" class="menu-item-form" value="'.$result['fundraiser_name'].'" >
<br>Activate:(Yes) <input type="checkbox" name="visible" value="yes" checked="checked" class="checked">

<br><br>Position:(<em>Starting from 0, higher numbers will appear last</em>)<br><input type="hidden" name="position" value="'.$result['position'].'" size="3" maxlength="3">
<br>Reason:<br><textarea name="reason" id="content-area" size="8">'.$result['reason'].'</textarea>
<br> Target Amount : <br>NGN <input type="hidden" name="target_amount" maxlength="" value="'.$result['target_amount'].'">'.$result['target_amount'].'
<br> Start date: (Campaign will end exactly 4 weeks after this sdate)<br>
<input type="text" name="start_date" value="'.$now.'"> <br>
<input type="hidden" name="end_date" value="'.$one_month.'"> <br>
<input type="submit" name="updated" value="Save changes" class="submit">
</form></div>';

echo $form;
	}

	}
} else {
	status_message('alert','You cannot edit a fundraiser once it is Active.<br>
	You may only add or remove perks.');
	
	 echo '<p align="center"><a id="view-fundraiser" href="' .ADDONS_PATH .'fundraiser?action=show&fid='
	 .$_SESSION['fundraiser_id'] .'"><strong><big>&laquo; Return to fundraiser</big></strong></a></p>';
	
	}

}
 
function get_linked_fundraiser_media($subject_name='',$pic_size=''){
	$destination_url = $_SESSION['current_url'];
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE `parent`='{$subject_name}' OR `destination_url`='{$destination_url}' ORDER BY `id` DESC ");
	 while($images_array= mysqli_fetch_array($query)) { 
		if($pic_size ==='large'){ 
			$image_sized = $images_array['large_path'];
		} else if($pic_size==='small'){ 
			$image_sized = $images_array['small_path'];
		} else if($pic_size=== 'medium'){ 
			$image_sized = $images_array['medium_path'];
		} else if($pic_size==='original'){ 
			$image_sized = $images_array['original_path'];
		} else { 
			$image_sized = $images_array['medium_path'];
		}
		
	$output[] = '<img src="' .$image_sized .'" alt="image" class="img-responsive">';
	//echo $image_sized['large']; //testing
	} return $output;
}

function get_fundraiser_grid(){
	$pager = pagerize(6);
	$limit = $_SESSION['pager_limit'];
	 
 $query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from fundraiser where status='active' order by id DESC LIMIT 20")
 or die("Failed to get fundraisers!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 

//if condition passes then show grid

	$is_mobile = check_user_agent('mobile');
			
	 
	echo "<h2 align='center'>Active Fundraisers</h2>";
	while($result= mysqli_fetch_array($query)) {
		

	$title = substr($result['fundraiser_name'],0,28);
	$name = $result['fundraiser_name'] .' fundraiser';
	$desc = substr($result['reason'],0,60);
	$image_name = $result['fundraiser_name']." fundraiser";
	
	$percent_raised = $result['amount_raised']/$result['target_amount'] * 100;
	
	$time = strtotime($result['end_date']);
	$remaining = time_elapsed($time);
	//~ echo $percent_raised;

		$pic = get_linked_fundraiser_media($subject_name = $name,$pic_size='medium');
		echo " ".
				"<div class='fundraiser-block'>"
				."<div class='grid'>
					<div class='grid_titles'>
						<a href='" .ADDONS_PATH ."fundraiser/?action=show&fid={$result['id']}'>" . ucfirst($title) ."...
					</div>
				</div>"
				.$pic[0] 
				."<div class='fundraiser-grid-description'>"
					
				. $desc 
				.'...</a></div>'
				.'<div class="fundraiser-goal"><span class="target-text">Target:<span class="target-amount"> NGN'.number_format($result['target_amount']).'.00</span></span>
				<progress align="center" value="'.$result['amount_raised'].'" min="0" max="'.$result['target_amount']. '"></progress>
				<span class="target-text">Raised: <span class="target-amount"> NGN'.$result['amount_raised'].'.00</span></span>'
			.'<span class="red-text">&uarr;</span><span class="red-text smaller">'.$percent_raised.'%</span> </a> <span class="timeago smaller">'.$remaining.'</span></div>'
			."</div>";
	
	
	
	
	}
	echo $pager;
} 



function donate($fundraiser_owner='',$current_amount=''){
	//show voguepay donate button
	$merchant_id = '13302-13767';
	$merchant_demo = 'demo';
	$username = $_SESSION['username'];
	$fundraiser = $_SESSION['fundraiser_name'];
	$fundraiser_id = $_SESSION['fundraiser_id'];

echo "<div class='row padding-20'><div class='col-md-12 col-xs-12'></div>";		
echo "<div class='toggle-interswitch whitesmoke col-md-offset-1 col-md-5 col-xs-12 padding-10 margin-3'>support via interswitch</div>";
echo "<div class='toggle-funds tan col-md-5 col-xs-12 padding-10 margin-3'>support via site funds</div>";
echo "</div>";

if($fundraiser_owner != $_SESSION['username']){
echo "<form class='inline-block aliceblue padding-10 margin-10 interswitch-pay' method='POST' action='https://voguepay.com/pay/'>

<input type='hidden' name='v_merchant_id' value='".$merchant_id."' />";

if(is_logged_in()){
	echo "<input type='hidden' name='merchant_ref' value='".$fundraiser_id."' />";
	} else {
		echo "<input type='hidden' name='merchant_ref' value='Anonymous' />";
		}
echo "<input type='hidden' name='notify_url' value='".BASE_PATH."funds_manager/process.php' />
<input type='hidden' name='success_url' value='".BASE_PATH."funds_manager/success.php' />
<input type='hidden' name='memo' value='Support {$fundraiser}' />".
'Choose Currency<br />
<select name="cur" >
<option value="NGN">NGN - Nigerian Naira</option>
<option value="USD">USD - US Dollar</option>
</select>
<input type="hidden" name="v_merchant_id" value="13302-13767" />'.
"<input type='hidden' name='notify_url' value='".ADDONS_PATH."funds_manager/process.php' />
<input type='hidden' name='success_url' value='".ADDONS_PATH."funds_manager/success.php' />
<input type='number' name='total' value='' placeholder='Amount to Give'/><br>
Your details (so we can thank you) <br><input type='text' name='email' value='' placeholder='Email, name or Phone' /><br>".
	
'<input type="image" src="http://voguepay.com/images/buttons/donate_blue.png" alt="PAY" />'.
"</form>";
}
	// donate with site funds
	$balance = get_user_funds();
	if(isset($_SESSION['username'])){
		if($fundraiser_owner != $_SESSION['username']){
			echo "<form class='aliceblue padding-10 margin-10 site-funds-pay' method='post' action='".BASE_PATH."funds_manager/success.php'>
			<strong>via site funds</strong>
			<br><span class='donate'><span class='green-text' align='center'>Support this campaign! - You have <span class='red-text'> N".$balance .".00</span><br></span>
			NGN <input type='number' name='amount' value='' placeholder='amount to give' required> 
			<input type='hidden' name='intent' value='support'>
			<input type='hidden' name='add_funds' value='subtract'>
			<input type='hidden' name='channel' value='site funds'>
			<input type='hidden' name='memo' value='donate with site funds'>
			<input type='hidden' name='current_amount' value='".$current_amount."'>
			<input type='hidden' name='user_balance' value='".$balance."'>
			<input type='hidden' name='reciever' value='".$_SESSION['username']."'>
			<input type='hidden' name='fundraiser_author' value='".$fundraiser_author."'>
			<input type='hidden' name='fundraiser_name' value='".$_SESSION['fundraiser_name']."'>
			<input type='hidden' name='target' value='".$_SESSION['fundraiser_id']."'>
			<input type='hidden' name='target_type' value='fundraiser'>
			<input type='hidden' name='giver' value='".$_SESSION['username']."'>
			<input type='hidden' name='reason' value='support [".$_SESSION['fundraiser_name']."] fundraiser'></span>
			<input type='submit' name='submit' value='Donate' class='button-primary'> </form>";
			} else {
				echo '<p>';
				status_message('alert','You cannot donate to your own fundraiser!');
				}
	} else { echo '<div class=" site-funds-pay">';
		log_in_to_continue();
		echo'</div>';}

}


function activate_fundraiser($fundraiser_id='',$fundraiser_status=''){
	$one_month = date('l jS F', strtotime('+4 weeks'));
	$date =  date('c');
 	if(isset($_GET['fid'])){
		$fundraiser_id=mysql_prep($_GET['fid']);
		}
		
	if(isset($_POST['start_fundraiser'])){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE fundraiser SET status='active', end_date='{$one_month}', timeago_stamp='{$date}' WHERE id='{$fundraiser_id}'") 
		or die("Failed to start contest " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			session_message('Fundraiser_activated');
			redirect_to($_SESSION['current_url']);
			}
		}
		
	if($fundraiser_status == '' && isset($_GET['fid'])){
		$fundraiser_status = $_SESSION['status'];
		}
	
	if(is_fundraiser_owner() && $fundraiser_status == 'pending'){
		//~ echo 'here am i';
		 echo "<span class='col-md-12 col-xs-12'><a href='".$_SESSION['current_url']."'>
		 <form method='post' action='".$_SESSION['current_url']."'>
		 <input type='submit' name='start_fundraiser' class='btn btn-primary btn-md' value='Activate Fundraiser'>
		 <br><em>Fundraiser will end exactly 30 days from activated date (today)</em>
		 </form></span>";
		}
		
		
	}



function show_fundraiser(){
	
	if(!empty($_GET['action']) && !empty($_GET['fid'])){
		$fundraiser_id = mysql_prep($_GET['fid']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser` WHERE `id`='{$fundraiser_id}' LIMIT 1") 
		or die('ERROR SELECTING FUNDRAISER ');
		
		$result = mysqli_fetch_array($query);
		
		$time = strtotime($result['end_date']);
		//echo $time;
		
		$remaining = time_elapsed($time);
		if($remaining == '0 seconds' && $result['status'] != 'finished'){
			$query = mysqli_query($GLOBALS["___mysqli_ston"],"Update fundraiser set status='finished' where id='{$fundraiser_id}' LIMIT 1") 
			or die('Failed to update fundraiser status');
			redirect_to($_SESSION['current_url']);
			}
		$name = $result['fundraiser_name'] .' fundraiser';
		$percent_raised = $result['amount_raised']/$result['target_amount'] * 100;
		
		$_SESSION['fundraiser_name'] = $result['fundraiser_name'];
		$_SESSION['fundraiser_id'] = $result['id'];
		$_SESSION['status'] = $result['status'];
		$_SESSION['author'] = $result['author'];
		$_SESSION['end_date'] = $result['end_date'];
		$_SESSION['time_remaining'] = '<time class="timeago" datetime="'.$result['timeago_stamp'].'">'.$result['timeago_stamp'].'</time>';
		
		echo "<section class='main-content-region'>";
		
		show_session_message();
		
		   # IF OWNER OR MANaGER, THEN SHOW EDIt link 
   if(isset($_SESSION['username'])){
	   
			echo '<section class="top-left-links">
						<ul>';
		if($result['author'] === $_SESSION['username'] || $_SESSION['role'] === 'manager' || $_SESSION['role'] === 'admin')  {


							echo '<li id="show_blocks_form_link" class="float-right-lists">
								<a href="'.ADDONS_PATH .'fundraiser/edit/?action=edit&fid='. $result['id'].'"> Edit fundraiser </a></li>	';		
						}

			echo '<li id="add_page_form_link" class="float-right-lists">
			<a href="'.ADDONS_PATH .'fundraiser?action=add-fundraiser">Add Fundraiser </a></li>';
		
			echo '</ul></section>';
			
		   
	}	# End edit link
	
	if($result['fundraiser_name'] !=='home'){
		if(!empty($result['last_updated'])){
	$last_update = "<div class='last-updated'>Last updated - <time class='timeago' datetime='".$result['last_updated']."'>".$result['last_updated'] ."</time></div>";
		}else if(!empty($result['created'])){
	$last_update = "<div class='last-updated'>Created - <time class='timeago' datetime='".$result['created']."'>".$result['created'] ."</time></div>";
		}
	}
	
	# Ready pictures
					 # GET PAGE IMAGES
			$is_mobile = check_user_agent('mobile');
			if($is_mobile){
				$size='medium';
			} else {
				$size='large';
				}
			
			$pics = get_linked_fundraiser_media($subject_name=$name,$pic_size=$size);
			
			
			echo "<div class='sweet_title'>".ucfirst(str_ireplace('-',' ',$result['fundraiser_name'])).$last_update."</div>";
		upload_no_edit();
			#Show page images    
		 if(isset($pics)) { 
			 show_slideshow_block($pics);
			 }
			 
		if($_SESSION['status'] == 'active'){
		    claim_fundraiser_perk();  
				
			# Print goal bar
			echo '<div class="fundraiser-goal-alone"><span class="target-text">Target:<span class="target-amount"> NGN'.number_format($result['target_amount']).'.00</span></span>
			<progress align="center" value="'.$result['amount_raised'].'" min="0" max="'.$result['target_amount']. '"></progress>
			<span class="target-text">Raised: <span class="target-amount"> NGN'.$result['amount_raised'].'.00</span></span>
			<span class="green-text">&uarr;</span><span class="red-text">'.$percent_raised.'%</span>';
			echo "</div>";
			
			donate($reciever=$result['author'], $current_amount=$result['amount_raised']);
			
			}
			
			
			if($_SESSION['status'] == 'pending'){
			status_message('alert','this fundraiser is not yet active');	
			}
			if($_SESSION['status'] == 'finished'){
			status_message('alert', 'This fundraiser has ended!');
			}
			
			activate_fundraiser();
				
				
	# Print content	
			echo "<div class='page-content'><h2> Reason :</h2>". parse_text_for_output($result['reason']) ."";
				if($_SESSION['status']  == 'active'){
				"<h2>End Date:</h2> <span class='end-date'>  ".$result['end_date']."</span>".$_SESSION['time_remaining'];
				echo '<span class="timeago">'.$remaining.'</span>';
				}
			echo  '<p class="padding-10 category"><strong class="">Category - <a href="'.BASE_PATH.'?section_name='.$result['category'].'&is_category=yes">'.$result['category'].'</a></strong></p>' .
				"</div>";
				

			add_to_category();
			if(addon_is_active('featured_content')){
				show_feature_this_link();
				}	
			show_user_follow_button($child_name=$fundraiser,$parent='fundraiser'); 	
			follow($child_name=$fundraiser);
			unfollow($parent='fundraiser',$child_name=$fundraiser);
			
			echo "</section>";
			
		# Right sidebar
		echo "<aside class='right-sidebar-region'>";
		
		#Show author pic
			
			$author = show_user_pic($user=$result['author']);
			
			echo "<strong>Author : </strong><a href='".BASE_PATH.'user?user='.$result['author']."'>".$result['author']."</a></strong><br>";
			echo $author['thumbnail'];
			
		echo "<h2>Target Amount<hr><div class='fundraiser-status'> NGN ".number_format($result['target_amount']).".00</div></h2>".
			"<h2>Status<hr></h2><div class='fundraiser-status'> ".$result['status']."</div>".
			"<h2>Amount raised<hr><div class='amount-raised-big'> NGN ".number_format($result['amount_raised']).".00</div></h2>";
			
			
		echo "<h2>Perks</h2><div class='perks'> </div>";
		
		#Show donors button
		echo "<br><form method='post' action='./donors.php'>".
		"<input type='hidden' name='fundraiser_name' value='".$result['fundraiser_name']."'>".
		"<input type='hidden' name='author' value='".$result['author']."'>".
		"<input type='submit' name='submit' class='btn btn-primary' value='See donors list'>".
		"</form>".
		
		
		show_fundraiser_perks();
		
		echo '<a href="'.ADDONS_PATH .'fundraiser">
		<div class="whitesmoke padding-10 clear">Other fundraisers </div>
		</a>';
		
		echo "</aside>";
		
		}
}

function show_fundraiser_donors_list(){
	
	if(isset($_POST['author'])){
		$author = $_POST['author'];
		} 
	if(isset($_POST['donor'])){
		$donor = $_POST['donor'];
		}
	$fundraiser_id = $_SESSION['fundraiser_id'];
	$author = $_SESSION['author'];

 #Show the fundraiser title
 echo "<div align='center'><strong><a href='".ADDONS_PATH. 
 "fundraiser/?action=show&fundraiser_name=".$_POST['fundraiser_name'] .
 "&fid=".$_SESSION['fundraiser_id']."'>".ucfirst(str_ireplace('-',' ',($_POST['fundraiser_name'])))."</a></strong></div>";
	
		#Search donors
	
	echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>".
		"<input type='hidden' name='fundraiser_name' value='".$fundraiser_name."'>".
		"<input type='hidden' name='author' value='".$author."'>".
		"<input type='text' name='donor' value='' placeholder='Type in a username'>".
		"&nbsp&nbsp<input type='submit' name='submit' value='Search donors by name'>".
		"</form> <hr>";
	
	
	
	if($_POST['submit']==='See donors list'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `payment_transactions` WHERE `target`='{$fundraiser_id}' AND `target_type`='fundraiser' ORDER BY `id` DESC") 
		or die ("Donor selection failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$total = mysqli_num_rows($query);
		echo "<h2>Total of {$total} persons have contributed to the Fundraiser :". ucfirst($_SESSION['fundraiser_name'])."</h2><br>";
	
	} else if($_POST['submit']==='Search donors by name'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `payment_transactions` WHERE `target`='{$fundraiser_id}' AND `donor`='{$donor}' ORDER BY `id` DESC") 
		or die ("Donor selection failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$total = mysqli_num_rows($query);
		
		echo "<h2>{$donor} has contributed {$total} times</h2>";
	}

	
	
	while ($result = mysqli_fetch_array($query)){
		$user=$result['donor'];
		
		echo "<strong>Donor : </strong><a href='".BASE_PATH.'user?user='.$result['donor']."'>".$result['donor']."</a></strong>".
		"<br><strong>Amount:</strong> <span class='green-text'> NGN " .$result['amount']. ".00</span>&nbsp&nbsp<strong>Date : </strong>".$result['date']."<br><hr>";
		}
	

	}

 
function go_to_fundraiser(){
	echo '<a href="'.BASE_PATH.'?section_name=fundraiser"><aside class="call-to-action">Fundraisers</aside></a>';
	}
?>
