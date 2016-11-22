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

function show_hashtags_page_title(){
	
	if(empty($_GET)){
		echo '<h1>Hashtags</h1>';
	} else if(isset($_GET['hashtag'])){
		echo '<h1>#'.$_GET['hashtag'].'</h1>';
		}
		
}

function get_hashtag_details(){
	if(isset($_GET['hashtag']) && is_logged_in()){
	
	unset($_SESSION['hashtag']);	
	unset($_SESSION['hashtag_status']);	
	unset($_SESSION['hashtag_participants']);	
	unset($_SESSION['hashtag_creator']);	
	
	$hashtag = trim(mysql_prep($_GET['hashtag']));
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM hashtags where hashtag='{$hashtag}'") 
	or die('Unable to get hashtag details'.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	
	if(!empty($num)){
	$result = mysqli_fetch_array($query);
	$_SESSION['hashtag_id'] = $result['id'];
	$_SESSION['hashtag'] = $result['hashtag'];
	$_SESSION['hashtag_status'] = $result['accessibility'];
	$_SESSION['hashtag_participants'] = $result['participants'];
	$_SESSION['hashtag_creator'] = $result['creator'];
	
	return true;
	} else { 
		//status_message('alert','');
		return false;}
	}
	//print_r($_SESSION);
}

function show_participants(){
	if(is_logged_in() && is_hashtag_participant() && $_SESSION['hashtag_status'] !='open'){
	$hashtag_id = $_SESSION['hashtag_id'];
	echo '<h3>Participants</h3>';
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT username from hashtag_participants where hashtag_id='{$hashtag_id}'") 
	or die('Failed to get hashtag participants '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_array($query)){
		echo '<a href="'.BASE_PATH.'user?user='.$result['username'].'">'.$result['username'].'</a>, ';
		}
	}
}


function is_hashtag_participant($hashtag=''){
	//print_r($_SESSION);
	if(isset($_GET['hashtag'])){
		$hashtag = trim(mysql_prep($_GET['hashtag']));
		}
	if(empty($hashtag)){
	$hashtag = $_SESSION['hashtag'];
	$hashtag_id = $_SESSION['hashtag_id'];
	} else {
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from hashtags where hashtag='{$hashtag}'") 
		or die('Problem getting hashtag id '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		$hashtag_id = $result['id'];
		}
	$user = $_SESSION['username'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from hashtag_participants where hashtag_id='{$hashtag_id}' and username='{$user}'") 
	or die('Could not select hashtag participant '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	//echo $num;
	if($_SESSION['hashtag_status'] == 'open'){
		return true;
	}else if($_SESSION['hashtag_status'] != 'blocked' && (!empty($num)) || is_admin()){
	return true;
	}else { 
		return false; 
		}
	
	}


function get_hashtag_posts(){
	
	if(isset($_GET['hashtag']) ){
		if(is_hashtag_participant()){
	$hashtag = trim(mysql_prep($_GET['hashtag']));
	//$id = trim(mysql_prep($_GET['tid']));
	
	$show_more_pager = pagerize($start='',$show_more='15');
	$limit = $_SESSION['pager_limit'];
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM hashtagged_posts WHERE hashtag='{$hashtag}' ORDER BY id DESC {$limit}") 
	or die('There was a problem fetching hashtag posts ' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$num = mysqli_num_rows($query);
	if(empty($num)){
		status_message('alert', 'No posts here.  Be the FIRST!');
		}
	echo '<section class=""><table class=""><tbody>';
	
		 # GET PAGE IMAGES
      $is_mobile = check_user_agent('mobile');
      if($is_mobile){
      $size='medium';
      } else {
      $size='large';
      }
	
	while($result = mysqli_fetch_array($query)){
		$id = $result['parent_id'];
		$query2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM page where id={$id}");
		$result2 = mysqli_fetch_array($query2);
			
			//$output = '<a href="'.$result2['destination'].'">'.str_ireplace('-',' ',ucfirst(urldecode($sel_page['page_name']))) .'</a><br>';
			
			$pic = show_user_pic($user=$result2['author'] ,$pic_class='img-rounded');
			$output =  "<tr><td class='darkturquoise'>
			{$pic['thumbnail']}</td><td class='table-message-plain'>";
			//$title = "<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>".$result['page_name'] .'</a><br>';
			
			$page = $result2['page_name'];
			$timeago = "<div class='last-updated'> <time class='timeago' datetime='".$result2['last_updated'] ."'>".$result2['last_updated'] ."</time></div>";
			
			$pics = get_linked_image($subject_id=$result2['id'],$pic_size='large');
			
			$content = substr(urldecode($result2['content']),0,350);
			$output .= $timeago . "<a href='" .BASE_PATH ."?page_name=" .$result2['page_name'] ."&tid=".$result2['id']."'>";
			$output2 = "";
			$comments_num = get_num_comments($result2['id']);
			$output2 .= $comments_num ."</a>" ;
			$output2 .= "<a href='" .BASE_PATH ."?page_name=" .$result2['page_name'] ."&tid=".$result2['id']."'>&nbsp; &raquo; Continue reading </a><br>";
			
			
			$content2 = parse_text_for_output($content);
			if($content2){
				$output3 = $output ."<a href='" .BASE_PATH ."?page_name=" .$result2['page_name'] ."&tid=".$result2['id']."'>".$pics[0]."</a>".'<br>'.$content2 .'<br>' .$output2. "<br> </td></tr>" ;
			}
			echo $output3;
		
		
	}echo '</tbody></table></section>';
		if(!empty($num)){ echo $show_more_pager; }
	}
	else {
		status_message('alert',"This is a private Hashtag Group and you do not have access to it. 
		If you are interested in JOINING, you may contact <a class='green-text' href='".BASE_PATH."user?user={$_SESSION['hashtag_creator']}'> {$_SESSION['hashtag_creator']} </a> to add you.");
		}
	}

}
	

function add_hashtag($hashtag="",$path='',$show_form='no',$post_type=""){
	if(is_logged_in()){
	$creator = $_SESSION['username'];
	
	
	#GET POST VALUES
	if(isset($_POST['hashtag'])){
		$hashtag = trim(mysql_prep($_POST['hashtag']));
		}
	if(isset($_POST['accessibility'])){
		$accessibility = trim(mysql_prep($_POST['accessibility']));
		}
	
	if(isset($_POST['path'])){
		$path = '';
		}
	if(isset($_POST['id'])){
		$id = trim(mysql_prep($_POST['id']));
		}
		
	
		
	$hashtag = str_ireplace('#','',$hashtag);
	
	//echo 'before the save <br>';
	#SAVE HASHTAG IF NOT EXISTS	
	if(!empty($hashtag)){	
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT IGNORE INTO hashtags (`id`, `hashtag`, `accessibility`, `creator`) 
	VALUES ('0','{$hashtag}','open','{$_SESSION['username']}')");
		}
	
	#SAVE TAGGED POST	
	if($query){
		if(!empty($hashtag) && !isset($_POST['submitted'])){
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM page WHERE destination='{$path}' LIMIT 1");
		$result=mysqli_fetch_array($query);
		
		if(isset($id)){
		$parent_id = $id;	
		} else { $parent_id = $result['id']; }
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `hashtagged_posts`(`id`, `hashtag`, `path`, `parent_id` ,`post_type`, `creator`) 
		VALUES ('0','{$hashtag}','{$path}','{$parent_id}','{$post_type}','{$creator}')") 
		or die('Error inserting hashtag ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
		}
		
	#SHOW FORM
	if($show_form =='yes'){
		$query_string = $_SERVER['QUERY_STRING'];
		if(empty($query_string)){
			echo '<h2>Add Hashtag</h2><form method="post" action="'.$_SESSION['current_url'].'">
			<input type="text" name="hashtag" placeholder="Hashtag or Group name">
			<br>Access : <select name="accessibility">
			<option value="open">Open</option>
			<option value="private">Private / invite only</option>
			</select>
			<input type="submit" name="save_hashtag" value="Add Tag/Group">
			</form>';
			}
		}
	
	}
	
}
	
function is_hashtag_owner(){
	if(is_logged_in() && $_SESSION['username'] == $_SESSION['hashtag_creator']){
		return true;
		} else { return false; }
	}

function administer_hashtag(){
	$user = mysql_prep($_SESSION['username']);
	if($_GET['action'] == 'exit_private_hashtag'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM hashtag_participants where username='{$user}'") 
		or die('Exit hashtag failed '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			redirect_to($_SESSION['prev_url']);
			}
		}
	if(get_hashtag_details()){
		echo '<h3>Created by : '.$_SESSION['hashtag_creator'] .'</h3>';
	
		
	//SHOW ACCESSIBILITY STATUS
	if($_SESSION['hashtag_status'] == 'blocked'){
		echo status_message('error','BLOCKED!');
	} elseif($_SESSION['hashtag_status'] == 'open'){
		echo status_message('success',strtoupper($_SESSION['hashtag_status']));
		
	} elseif($_SESSION['hashtag_status'] == 'private'){
		echo status_message('alert',strtoupper($_SESSION['hashtag_status']).' GROUP');
	}
	
	
	//BLOCK TAG
	if(is_admin()){
		if(isset($_GET['hashtag']) && $_POST['hashtag_action']  == 'Block'){
		$id = mysql_prep($_SESSION['hashtag_id']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE hashtags set accessibility='blocked' WHERE id='{$id}'") 
		or die("Block hashtag failed ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			status_message('error','Hashtag BLOCKED!');
			//redirect_to($_SESSION['current_url']);
			}
		}
	}
	
	if(is_logged_in() && (is_hashtag_owner() || is_admin())){	
	//MAKE PRIVATE
	if((is_admin() || $_SESSION['username'] == $_SESSION['hashtag_creator']) && $_POST['hashtag_action']  == 'Private'){
		if(isset($_GET['hashtag'])){
			$id = mysql_prep($_SESSION['hashtag_id']);
			} 
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE hashtags set accessibility='private' WHERE id='{$id}'") 
		or die("Block hashtag failed ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){redirect_to($_SESSION['current_url']);}
		}
	//MAKE PUBLIC
	if((is_admin() || $_SESSION['username'] == $_SESSION['hashtag_creator']) && $_POST['hashtag_action']  == 'Open'){
		if(isset($_GET['hashtag'])){
			$id = mysql_prep($_SESSION['hashtag_id']);
			} 
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE hashtags set accessibility='open' WHERE id='{$id}'") 
		or die("Block hashtag failed ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){redirect_to($_SESSION['current_url']);}
		}
	//DELETE TAG
	if((is_admin() || $_SESSION['username'] == $_SESSION['hashtag_creator']) && $_POST['hashtag_action']  == 'Delete HASHTAG'){
		//echo 'deleting';
		$hashtag = trim(mysql_prep($_GET['hashtag']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from hashtagged_posts where hashtag='{$hashtag}'") 
		or die('Trying to delete hashtag '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$num = mysqli_num_rows($query);
		if($num >= 1){
			session_message('error','This hashtag still contains some posts and so cannot be deleted. Delete all posts first then try again.');
			redirect_to($_SESSION['current_url']);
			} else {
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from hashtags where hashtag='{$hashtag}' LIMIT 1") 
				or die("Error deleting ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				if($query){
					session_message('alert','#'.$hashtag .' deleted !');
					redirect_to(BASE_PATH.'?page_name=talk');
					}
				}
		}
	
	//SHOW HASHTAG ADMINISTRATION MENU
	echo '
	<form action="'.$_SERVER['current_url'].'" method="post">
	<div class="btn-group">
	<input type="submit" class="btn btn-md btn-success" name="hashtag_action" value="Open">
	<input type="submit" class="btn btn-md btn-warning" name="hashtag_action" value="Private">
	<input type="submit" class="btn btn-md btn-danger" name="hashtag_action" value="Block">
	</div><br><br>';
	if(is_admin()){
	echo '<input type="submit" class="btn btn-sm btn-danger col-md-12" name="hashtag_action" value="Delete HASHTAG">';
	}
	echo '</form>';
		}
		
	//Exit Hashtag group
	if(is_hashtag_participant() && $_SESSION['hashtag_status'] == 'private' && is_logged_in() && !is_hashtag_owner() && !is_admin()){
	echo '<p align="center"><a href="'.$_SESSION['current_url'].'&action=exit_private_hashtag">Exit this Hashtag Group</a></p>';
		}
	
	}
}
	
	
function add_hashtag_participants(){
	if(is_logged_in()){
	if(isset($_GET['hashtag']) && (is_hashtag_owner() || is_admin())){
		$hashtag = trim(mysqli_query($GLOBALS["___mysqli_ston"], $_GET['hashtag']));
		$hashtag_id = $_SESSION['hashtag_id'];
		
		if(isset($_POST['save_participants'])){
			//print_r($_SESSION);print_r($_POST);
			$post_participants = trim(mysql_prep($_POST['participants']));
			$participants= explode(',',$post_participants);
			$hashtag = $_SESSION['hashtag'];
			
			foreach($participants as $participant){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO hashtag_participants(`id`,`username`,`hashtag_id`) 
			VALUES('0','{$participant}','{$hashtag_id}')") 
			or die('Failed to add hashtag participants. '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			if($query){
				session_message('success','Participants added!');
				//redirect_to($_SESSION['current_url']);
				}
			}
	echo '<h2>Add participants</h2><form method="post" action="'.$_SESSION['current_url'].'">
		<input type="hidden" name="hashtag" value="'.$hashtag.'">
		<textarea name="participants" placeholder="Usernames seperated by comma"></textarea>
		<input type="submit" name="save_participants" value="Add Participants">
		</form>';
	
		}
	}
}

function delete_hashtag_participants(){
	if(is_hashtag_owner() || is_admin() && isset($_GET['hashtag'])){
	echo '<br><p align="center"><a href="'.$_SESSION['current_url'].'&del_participants=true#remove_participants"> Remove participant</a></p>';
	}
	
	if(isset($_GET['hashtag']) && (is_hashtag_owner() || is_admin()) && $_GET['del_participants'] == 'true'){
		$hashtag = trim(mysqli_query($GLOBALS["___mysqli_ston"], $_GET['hashtag']));
		$hashtag_id = $_SESSION['hashtag_id'];
		
		if(isset($_POST['del_participants'])){
			//print_r($_SESSION);print_r($_POST);
			$post_participants = trim(mysql_prep($_POST['participants']));
			$participants= explode(',',$post_participants);
			$hashtag = $_SESSION['hashtag'];
			
			foreach($participants as $participant){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM hashtag_participants WHERE hashtag_id='{$hashtag_id}' AND username='{$participant}'") 
			or die('Failed to delete one  or more participants. '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			if($query){
				session_message('success','Participants removed!');
				redirect_to($_SESSION['prev_url']);
				}
			}
	echo '<div id="remove_participant"><h2>Remove participants</h2><form method="post" action="'.$_SESSION['current_url'].'">
		<input type="hidden" name="hashtag" value="'.$hashtag.'">
		<textarea name="participants" placeholder="Usernames seperated by comma"></textarea>
		<input type="submit" name="del_participants" value="Remove Participants">
		</form></div>';
	
		
	}
}

function process_hashtags($string, $path=''){
	//echo $path;die();
	$hashtag = '/(?<!\S)#\w+(?!\S)/';
	if(preg_match_all($hashtag,$string,$matches)){
			foreach($matches[0] as $match){
			add_hashtag($match,$path);
			}
		} 
	
	}
	
function search_hashtags(){
	if(is_logged_in()){
		if($_POST['submit'] == 'Find Hashtag'){
			$hashtag = trim(mysql_prep($_POST['query_hashtag']));
			$condition = "WHERE hashtag LIKE '%{$hashtag}%' ";
			$title = '<h2>Search results</h2>';
			} else { 
				$condition = ''; 
				$title = '<h2>Latest Tags</h2>';
				}
			echo '<p><h2>Search Hashtags</h2></p><form method="post" action="'.ADDONS_PATH.'hashtags/index.php">
			<input type="search" name="query_hashtag" placeholder="find tag containing">
			<input type="submit" name="submit" class="btn btn-primary" value="Find Hashtag">
			<input type="submit" name="reset" class="btn btn-default" value="reset">
			</form><br>';
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, hashtag FROM hashtags {$condition} ORDER BY id DESC LIMIT 0,50") 
		or die("Problem searching hashtag ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		echo "<p>{$title}";
		while($result = mysqli_fetch_array($query)){
			echo '<a href="'.ADDONS_PATH.'hashtags/?hashtag='.$result['hashtag'].'">#'.$result['hashtag'].',</a> ';
			}
			echo '<hr>';
		}
	}

 // end of hashtags functions file
 // in root/hashtags/includes/functions.php
?>
