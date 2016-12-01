<?php ob_start();
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

# 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

//print_r($_POST);
//print_r($_SESSION);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function add_draw(){
	if($_POST['submit'] == 'Add Draw'){
		
	$category = mysql_prep($_POST['category']);
	$duration = mysql_prep($_POST['duration']);
	$instructions = mysql_prep($_POST['instructions']);
	
		
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"INSERT INTO `draws`(`id`, `category`, `duration`, `instructions`, `end_date`, `status`, `last_winner`) 
	VALUES ('0','{$category}','{$duration}','{$instructions}','{$end_date}','active','')") 
	or die('failed to add draw '. mysqli_error());
	
	if($q){
		session_message('success','Draw saved!');
		unset($_POST['submit']);
		redirect_to($_SESSION['current_url']);
		}
	}
	
	echo '<h3>Add draw</h3><form method="post" action="'.$_SESSION['current_url'].'">
	Category: <br><input type="text" name="category" class="form-control" placeholder="20,50,100 etc">
	Duration: <br><select name="duration">
	<option>Daily</option>
	<option>Weekly</option>
	</select>
	<br>Instructions: <br><textarea name="instructions" class="form-control"></textarea>
	<input type="submit" name="submit" value="Add Draw" class="btn btn-primary btn-md"> 
	</form>';
	}
	
function delete_draw($id){
	$stats = get_draw_statistics($id);

	if(isset($_GET['del_draw']) && is_admin() && $id != ''){
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"DELETE FROM draws WHERE id='{$id}' LIMIT 1" ) 
	or die('Failed to delete draw '.mysqli_error($GLOBALS['___mysqli_ston']));
	
	if($q){
		session_message('alert','Draw deleted');
		redirect_to($_SESSION['prev_url']);
		}
	}
		
	echo '<span class="tiny-text pull-right"><a href="'.ADDONS_PATH.'draws/?del_draw='.$id.'">delete</a></span>';
}

function winner_exists_today($category,$date){
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"SELECT user_name FROM draw_winners WHERE category='{$category}' and date='{$date}'") 
	or die('Could not check if winner exists '.mysqli_error($GLOBALS['___mysqli_ston']));
	$num = mysqli_num_rows($q);
	echo $num;
	if($num < 1){
		return false;
		} 
		else {
		$result = mysqli_fetch_array($q);
		$winner ='<a href="'.BASE_PATH.'user/?user='.$result['user_name'].'">'.$result['user_name'].'</a>';	
		return $winner;
		}
}


function select_draw_winner($draw_id,$category=''){
	$time = getdate();
	$today = date('l jS F');
	//print_r($time);
	$participants = array();
	$stats = get_draw_statistics($draw_id,$category);
	
	if($stats['participants'] >= 1){
			//~ echo $stats['participants'];
	if($time['hours'] >= '20' && $time['hours'] < '24'){
		
			if(false == winner_exists_today($category,$today)){
			while($result = mysqli_fetch_array($q)){
			$participants[] = $result['user_name'];
			
			$num_participants = count($participants) - 1;
			$selected = mt_rand(0,$num_participants);
			$winner = $participants[$selected];
			//~ echo $winner;
			$amount_won = ($num_participants + 1) * $category;
			$due_to_winner = $amount_won - ((1/10)*$amount_won);
			
			//record winner
			$q = mysqli_query($GLOBALS['___mysqli_ston'],"INSERT INTO `draw_winners`(`id`, `user_name`, `category`, `total_amount`, `date`) 
			VALUES ('0','{$winner}','{$category}','{$amount_won}','{$today}')") 
			or die('Failed to record winner '. mysqli_error($GLOBALS['___mysqli_ston']));
			
			//pay winner;
			transfer_funds($action='add',$amount=$due_to_winner,$giver='system',$reciever=$winner,$reason='90% of Draw winnings',$auto_switch='true');
			echo '<div class="clear green-text pull-right"><h3> Winner!! > ';
			
			//~ $q = mysqli_query($GLOBALS['___mysqli_ston'],"DELETE FROM draw_participants WHERE draw_id='{$draw_id}'") 
			//~ or die('Could refresh participants db '.mysqli_error($GLOBALS['___mysqli_ston']));
		//~ 
			
			echo '<a href="'.BASE_PATH.'user/?user='.$winner.'">'.$winner.'</a></h3></div>';
			}
		
		
		// clear tables for new day
		$q = mysqli_query($GLOBALS['___mysqli_ston'],"DELETE FROM draw_participants WHERE draw_id='{$draw_id}'") 
			or die('Could refresh participants db '.mysqli_error($GLOBALS['___mysqli_ston']));
		
		
		}else { 
		$winner = winner_exists_today($category,$today);
		
		echo '<div class="clear green-text pull-right"><h3> Winner!! '. $winner.'</h3></div>';
		}
	} 
	
	
}

	
}


function enter_draw($draw_id,$category=''){
	$today = date('l jS F');
	$time = getdate();
	if(isset($_POST['enter_draw'])){
	$draw_id = mysql_prep($_POST['draw_id']);
	$category = mysql_prep($_POST['category']);
	$user =$_SESSION['username'];
	
	//checkif draw is active
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"SELECT status FROM draws WHERE id='{$draw_id}' ") 
	or die("Error checking participant status ".mysqli_error($GLOBALS['___mysqli_ston']));
	if($result['status'] == 'closed'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE draws SET status='active', end_date='{$today}'") 
		or die("Problem updating draw " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	// echo 'draw id is'.$draw_id;
	// check if user is already participating
	
	if(!is_draw_participant($draw_id)){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `draw_participants`(`id`, `draw_id`, `user_name`) 
		VALUES ('0','{$draw_id}','{$user}')") 
		or die("Problem entering draw " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		}
	if($query){
		transfer_funds('subtract',"{$category}",'system',"{$user}","Entered N{$category} draw",$auto_switch='true');
		session_message('alert','You are now participating in N'.$category.' draw');
		redirect_to($_SESSION['current_url']);
		}
		}
		
	if($time['hours'] < '20' && !is_draw_participant($draw_id)){
		echo '<form method="post" action="'.$_SESSION['current_url'].'">'.
		"<input type='hidden' name='draw_id' value='{$draw_id}'>
		<input type='hidden' name='category' value='{$category}'>
		<button name='enter_draw' class='btn btn-primary btn-md' onclick='this.form.submit();'>Enter draw</button>
		<em>will cost you {$category} site funds</em>";
			 
		echo'</form>';
	}
	
}


function is_draw_participant($draw_id){
	$user = $_SESSION['username'];
	
	// check if user is already participating
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"SELECT count(*) as entered FROM draw_participants WHERE draw_id='{$draw_id}' and user_name='{$user}'") 
	or die('Could not tell if you are participating '.mysqli_error($GLOBALS['___mysqli_ston']));
	
	$result = mysqli_fetch_array($q);
	if($result['entered'] > 0){
		return true;
		} else {
			return false;
			}
	}


function show_draws($status=''){
	$today = date('l jS F');
	$time = getdate();
	
	if($status != ''){
		$condition = " WHERE status='{$status}'";
	}else {$condition = '';} 
	
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"SELECT * FROM draws {$condition}") 
	or die('Error fetching draws'.mysqli_error($GLOBALS['___mysqli_ston']));
	while($result = mysqli_fetch_array($q)){
		
		echo '<div class="col-md-10 col-xs-10 padding-20 margin-20 whitesmoke"> <span class="pull-right">'.$today
		.' | '.($time['hours'] ).':'.$time['minutes'].'</span>
		<br> Category : <strong>N'.$result['category'].'</strong><hr>';
		if($result['duration'] == 'Daily'){
			$duration = 'Ends every day at 20.00 server time ';
		}elseif($result['duration'] == 'Weekly'){
			$duration = 'Ends on '.$result['end_date'].' by 8.00pm';
			}
		echo ''.$duration.'<hr>
		<strong>Instructions </strong>:'.parse_text_for_output($result['instructions']) .'<hr><br>';
		
		$funds = get_user_funds();
		if($funds >= $result['category']){
			enter_draw($result['id'],$result['category']);
			}
		$stats = get_draw_statistics($result['id'],$result['category']);
		echo '<span class="pull-right">
		<em>'.$stats['message'].'</em>
		<br><span class="red-text">Money pot :</span><span class="green-text">
		<strong>N'.$stats['money_pot'].' </strong></span>';
		echo'</span>';
		
		select_draw_winner($result['id'],$result['category']);
		if($stats['participants'] < 1){
		delete_draw($result['id']);
		}
		$share_buttons ='<div class="block">
						<!-- Go to www.addthis.com/dashboard to customize your tools --> 
						<div class="addthis_inline_share_toolbox"></div>
						</div>';
		echo $share_buttons.'</div>';
		}
	}


function get_draw_statistics($draw_id,$category=''){
	$user = $_SESSION['username'];
	$stats = array();
	
	$stats['entered'] = is_draw_participant($draw_id);
	//echo $stats['entered'];
	
	$q = mysqli_query($GLOBALS['___mysqli_ston'],"SELECT count(*) as participants FROM draw_participants WHERE draw_id='{$draw_id}'") 
	or die('Could not get draw participants '.mysqli_error($GLOBALS['___mysqli_ston']));
	
	$result = mysqli_fetch_array($q);
	$stats['participants'] = $result['participants'] ;
	if($stats['entered'] == true){
	$stats['message'] = '<strong>Participating : </strong><span class="red-text">You</span> and '.($result['participants']-1). ' others';
	} else {
		$stats['message'] = '';
		}
	$stats['money_pot'] = $result['participants'] * $category;
	//print_r($stats);
	return $stats;
	}


 // end of draws functions file
 // in root/addons/draws/includes/functions.php
?>
