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
	<br> Start date: (Campaign will end exactly 45 days after this start date on '; $form .= $date; $form.=')<br>
	<input type="text" id="datepicker" name="start_date" value="'.date('d/m/Y').'" > <br>
	<input type="hidden" id="datepicker" name="end_date" value="'.date('d-m-Y', strtotime("+45 days")).'" > <br>
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
	if($_SESSION['username'] == $_SESSION['author'] && url_contains('fundraiser_name=')){
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
			status_message('success','Perk saved!');
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
	$balance = get_user_funds();
	$reciever = $_SESSION['author'];
	if($reciever != $_SESSION['username']){
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM fundraiser_perks where fundraiser_id='{$fundraiser_id}' order by id desc")
	 or die('Could not select perks '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 
	 
	 while($result = mysqli_fetch_array($query)){
		echo '<div class="page-content">';
		echo '<div class="title clear">'.'$'.$result['donation_amount'].'<hr></div>
		 <div class="padding-10">'.
		 $result['reward'].
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
		 "<input type='submit' name='submit' value='Claim this' class='btn btn-primary'>".
		 '</form></div>'; 
		 echo '</div>';
	}
	}
	
		add_perk();
}

# LIST fundraiserS

function get_fundraiser_lists($category='') {
	
	if($category != ''){
		$condition = " WHERE category='{$category}'";		
	}
	
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
  	$fundraiserlist = $fundraiserlist 
	 . '<tr><td class="gainsboro">'.
	$pic['thumbnail']
  .'</td><td><a href="' .ADDONS_PATH .'fundraiser/?action=show&fundraiser_name=' .$result['fundraiser_name'] .'"> ' 
  . ucfirst(str_ireplace('-',' ',$result['fundraiser_name']))
  . '</a><br>';
  
  
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
  	$fundraiserlist = $fundraiserlist 
	 . '<tr><td class="gainsboro">'.
	$pic['thumbnail']
  .'</td><td><a href="' .ADDONS_PATH .'fundraiser/?action=show&fundraiser_name=' .$result['fundraiser_name'] .'"> ' 
  . ucfirst(str_ireplace('-',' ',$result['fundraiser_name']))
  . '</a><br>';
  
  
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

if(url_contains('edit')){
$fundraiser = $_GET['fundraiser_name']; 
#echo $block; // Testing
}	

if(isset($fundraiser)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from fundraiser WHERE fundraiser_name='{$fundraiser}' LIMIT 1") 
	or die("Failed to get selected fundraiser" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 $result = mysqli_fetch_array($query);
}

if($result['author'] == $_SESSION['username'] || is_admin()){	

	
	//Check if update is requested and show edit form
	 if(url_contains('edit')){
	 	
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "select * from fundraiser " .
	 'where fundraiser_name="' .
	 $fundraiser .
	 '" ' .
	 " limit 1") or die("Failed to get selected fundraiser" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 $result = mysqli_fetch_array($query);
	 
	 #DETECT and switch between sections and fundraisers
	 
	 if(isset($_GET['fundraiser_name'])){
	 $target = trim(mysql_prep($_GET['fundraiser_name']));
	 $route = '&fundraiser_name=';
	 $end = 'fundraiser';
	 
	 
	 
	 // now we show the fundraiser edit form
	 echo "<strong> You are editing <em>&nbsp" . $target ." {$end} </em></strong>";
	 echo '<p align="center">Go to <a id="view-fundraiser" href="' .ADDONS_PATH .'fundraiser?action=show'.$route .$target .'"><strong><big> ' 
  . $target .' ' .$end
  . '</big></strong></a></p>';
	 	

$form = '<div class="edit-form">
<form method="POST" action="../process.php">
<input type="hidden" name="action" value ="update" >
<input type="hidden" name="author" value ="'.$_SESSION['username'].'" >
<input type="hidden" name="back_url" value ="'.$_SERVER['HTTP_REFERER'] .'" >
Fundraiser name <input type="text" name="fundraiser_name" class="menu-item-form" value="'.$result['fundraiser_name'].'" >
<br>Active:(Yes) <input type="checkbox" name="visible" value="'.$result['active'].'" checked="checked" class="checked">

<br><br>Position:(<em>Starting from 0, higher numbers will appear last</em>)<br><input type="hidden" name="position" value="'.$result['position'].'" size="3" maxlength="3">
<br>Reason:<br><textarea name="reason" id="content-area" size="8">'.$result['reason'].'</textarea>
<br>Perks (if any):<br><textarea name="perks" id="content-area" size="8">'.$result['perks'].'</textarea>
<br> Target Amount : <br>NGN <input type="hidden" name="target_amount" maxlength="" value="'.$result['target_amount'].'">'.$result['target_amount'].'
<br> Start date: (Campaign will end exactly 45 days after this start date)<br>
<input type="disabled" name="start_date" value="'.$result['start_date'].'"> <br>
<input type="hidden" name="start_date" value="'.$result['end_date'].'"> <br>
<input type="submit" name="updated" value="Save changes" class="submit">
</form></div>';

echo $form;
	}

	}
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
	 
 $query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from fundraiser order by id DESC")
 or die("Failed to get fundraisers!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 

//if condition passes then show grid

	$is_mobile = check_user_agent('mobile');
			
	 
	//echo "<h2 align='center'>Fundraisers</h2>";
	while($result= mysqli_fetch_array($query)) {
		

	$title = substr($result['fundraiser_name'],0,28);
	$name = $result['fundraiser_name'] .' fundraiser';
	$desc = substr($result['reason'],0,60);
	$image_name = $result['fundraiser_name']." fundraiser";

		$pic = get_linked_fundraiser_media($subject_name = $name,$pic_size='medium');
		echo " ".
				"<div class='fundraiser-block'>"
				."<div class='grid'>
					<div class='grid_titles'>
						<a href='" .ADDONS_PATH ."fundraiser/?action=show&fundraiser_name={$result['fundraiser_name']}'>" . ucfirst($title) ."...
					</div>
				</div>"
				.$pic[0] 
				."<div class='fundraiser-grid-description'>"
					
				. $desc 
				.'...</a></div>'
				.'<div class="fundraiser-goal"><span class="target-text">Target:<span class="target-amount"> NGN'.number_format($result['target_amount']).'.00</span></span>
				<progress align="center" value="'.$result['amount_raised'].'" min="0" max="'.$result['target_amount']. '"></progress>
				<span class="target-text">Raised: <span class="target-amount"> NGN'.$result['amount_raised'].'.00</span></span></div>'
			
			."</div>";
	
	
	
	
	}
	echo $pager;
} 



function donate($reciever='',$current_amount=''){
	$balance = get_user_funds();
	if($reciever != $_SESSION['username']){
	echo '';
	echo "<form method='post' action='./process.php'>
	<br><span class='donate'><span class='green-text' align='center'>Support this campaign! - You have <span class='red-text'> N".$balance .".00</span><br></span>
	NGN <input type='number' name='amount' value='' placeholder='amount to give' required> 
	<input type='hidden' name='action' value='donate'>
	<input type='hidden' name='current_amount' value='".$current_amount."'>
	<input type='hidden' name='user_balance' value='".$balance."'>
	<input type='hidden' name='reciever' value='".$reciever."'>
	<input type='hidden' name='fundraiser_name' value='".$_GET['fundraiser_name']."'>
	<input type='hidden' name='giver' value='".$_SESSION['username']."'>
	<input type='hidden' name='add_funds' value='yes'>
	<input type='hidden' name='reason' value='support ".$_GET['fundraiser_name']." fundraiser'></span>
	<input type='submit' name='submit' value='Donate' class='button-primary'> </form>";
	} else {
		echo '<p>';
		status_message('alert','You cannot donate to your own fundraiser!');
		}
	
}


function show_fundraiser(){
	
	if(!empty($_GET['action']) && !empty($_GET['fundraiser_name'])){
		$fundraiser = trim(mysql_prep($_GET['fundraiser_name']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser` WHERE `fundraiser_name`='{$fundraiser}' LIMIT 1") 
		or die('ERROR SELECTING FUNDRAISER '.mysql_query());
		
		$result = mysqli_fetch_array($query);
		$name = $result['fundraiser_name'] .' fundraiser';
		
		$_SESSION['fundraiser_id'] = $result['id'];
		$_SESSION['status'] = $result['status'];
		$_SESSION['author'] = $result['author'];
		
		echo "<section class='main-content-region'>";
		
		
		   # IF OWNER OR MANaGER, THEN SHOW EDIt link 
   if(isset($_SESSION['username'])){
	   
			echo '<section class="top-left-links">
						<ul>';
		if($result['author'] === $_SESSION['username'] || $_SESSION['role'] === 'manager' || $_SESSION['role'] === 'admin')  {


							echo '<li id="show_blocks_form_link" class="float-right-lists">
								<a href="'.ADDONS_PATH .'fundraiser/edit/?action=edit&fundraiser_name='. $result['fundraiser_name'].'"> Edit fundraiser </a></li>	';		
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
		    claim_fundraiser_perk();  
				
	# Print goal bar
			echo '<div class="fundraiser-goal-alone"><span class="target-text">Target:<span class="target-amount"> NGN'.number_format($result['target_amount']).'.00</span></span>
			<progress align="center" value="'.$result['amount_raised'].'" min="0" max="'.$result['target_amount']. '"></progress>
			<span class="target-text">Raised: <span class="target-amount"> NGN'.$result['amount_raised'].'.00</span></span>';
		if(isset($_SESSION['username'])){
			donate($reciever=$result['author'], $current_amount=$result['amount_raised']);
		}
			echo "</div>";
			
			
	# Print content	
			echo "<div class='page-content'><h2> Reason :</h2>". parse_text_for_output($result['reason']) ."".
				"<h2>Duration :</h2>From <span class='start-date'>".$result['start_date']."</span> to <span class='end-date'>  ".$result['end_date']."</span>";
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
			
		echo "<h2>Fundraiser status :</h2><div class='fundraiser-status'> ".$result['status']."</div>".
			"<h2>Amount raised :</h2><div class='amount-raised-big'> NGN ".number_format($result['amount_raised']).".00</div>";
			
			
		echo "<h2>Perks: </h2><div class='perks'> </div>";
		
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

function show_fundraiser_donors_list($fundraiser_name, $author){
	if(isset($_POST['author'])){
		$author = $_POST['author'];
		} 
	if(isset($_POST['donor'])){
		$donor = $_POST['donor'];
		}

 #Show the fundraiser title
 echo "<div align='center'><strong><a href='".ADDONS_PATH. 
 "fundraiser/?action=show&fundraiser_name=".$_POST['fundraiser_name'] .
 "'>".ucfirst(str_ireplace('-',' ',($_POST['fundraiser_name'])))."</a></strong></div>";
	
		#Search donors
	
	echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>".
		"<input type='hidden' name='fundraiser_name' value='".$fundraiser_name."'>".
		"<input type='hidden' name='author' value='".$author."'>".
		"<input type='text' name='donor' value='' placeholder='Type in a username'>".
		"&nbsp&nbsp<input type='submit' name='submit' value='Search donors by name'>".
		"</form> <hr>";
	
	
	
	if($_POST['submit']==='See donors list'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser_donors` WHERE `fundraiser_name`='{$fundraiser_name}' AND `recipient`='{$author}' ORDER BY `id` DESC") 
		or die ("Donor selection failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$total = mysqli_num_rows($query);
		echo "<h2>Total of {$total} persons have contributed to the Fundraiser :". ucfirst($fundraiser_name)."</h2><br>";
	
	} else if($_POST['submit']==='Search donors by name'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `fundraiser_donors` WHERE `fundraiser_name`='{$fundraiser_name}' AND `donor`='{$donor}' ORDER BY `id` DESC") 
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
