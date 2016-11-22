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
include_once($r .'includes/functions.php'); #do not edit

//print_r($_SESSION);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function show_message_form($subject='', $reciever='', $reply =''){
	
	$re=''; # This is used to hold the prefix 'RE:' in case of a reply
	
	if (isset($_GET['subject'])){
	$subject = trim(mysql_prep($_GET['subject']));
	}
	
	
	if (isset($_GET['reply_to'])){
	$reciever = $_GET['reply_to'];
	$reply = 'yes';
	$re= "RE:";
	}
	
	if (isset($_GET['parent_id'])){
	$parent_id = mysql_prep($_GET['parent_id']);
	}
	
	if(!is_logged_in()){
	$sender = mysql_prep($_GET['reciever']);
	}
	
	if(isset($_GET['reciever'])){
	$reciever = mysql_prep($_GET['reciever']);
	}
	
	if(!is_logged_in()){
		$sender = "<input type='hidden' name='sender' value='Anonymous'>
		<input type='text' name='name' value='' placeholder='Your name'>
		<input type='email' name='email' value='' placeholder='Email address'>
		<input type='tel' name='phone' value=''placeholder='Phone number'> ";
		$reciever = 'feedback';
		} else {
			$sender = "<input type='hidden' name='sender' value='" .$_SESSION['username'] ."'><br>";
			}
	
	$form = "<section class='padding-20'><form action='".ADDONS_PATH."messaging/process.php' method='post' >";
	if(empty($_SESSION['username'])){
		$reciever = 'feedback';
		}
	$form .= "<input type='hidden' name='reciever' value='".$reciever ."'>
	{$sender}
	<input type='text' size='30' name='subject' value='" .$re.$subject ."' placeholder='Subject'><br>
	<textarea name='message' size=15 rows='4' placeholder='Message'></textarea><br>";
	
	if(!is_logged_in()){
		$form .= "<div class='gainsboro'>
		<strong>Phone, Garri, Plastic, Bottle</strong><br>
		Which of the above is a FOOD item?<br>
		<input type='text' size='30' name='spam_blocker' value='' placeholder='Type your answer here'><br>
		<em>(This is to help us know if you are a human visitor or a spam bot)</em>
		</div>";	
		}
	
	$form .= "
	<input type='hidden' name='reply' value='" .$reply ."'>
	<input type='submit' name='submit' value='submit' class='submit'>
	</form></section>";
	echo $form;
	}

function send_message($sender='',$reciever='',$message='',$parent_id=""){
	
	#print_r($_POST); Testing purposes
	$reply = 'no';
	
	$subject = '-';
	if(!is_logged_in() && !empty($_POST['spam_blocker'])){
		$spam_check = strtolower(trim(mysql_prep($_POST['spam_blocker'])));
		if($spam_check === 'garri'){
		$pass = true;	
			} else { $pass = false;}
	} else if(is_logged_in){
		$pass = true;
		}
	
	if(!is_logged_in() && $sender==''){
		$sender = "system";
	}else if(is_logged_in() && $sender==''){
		$sender = $_SESSION['username'];
		}
	
	 if($sender==='me'){
		$sender = $_SESSION['username'];
	} 
	
	if (isset($_POST['from'])){
	$sender = trim(mysql_prep($_POST['from']));
	}
	
	if(isset($_POST['reciever'])){
		$reciever = trim(mysql_prep($_POST['reciever']));
	}
	if($reciever === 'me'){
		$reciever = $_SESSION['username'];
	}

		
	if(isset($_POST['message'])){
		$message = trim(mysql_prep($_POST['message']));
		
		}
		
	if(isset($_POST['subject'])){
		$subject = trim(mysql_prep(strip_non_alphanumeric($_POST['subject'])));
		
		}	
	if(isset($_POST['sender'])){
		$sender = trim(mysql_prep($_POST['sender']));
		
		}	
		
	if(isset($_POST['parent_id'])){
		
		$parent_id =trim(mysql_prep($_POST['parent_id']));
		} else {
		$parent_id = substr($sender,0,28) .'.'. substr($reciever,0,28);
		$participants = explode('.',$parent_id);
		
		sort($participants);
		$person1= $participants[0];
		$person2= $participants[1];
		
		$parent_id = $participants[0].'.'.$participants[1];		
	}
		
	
	if($_POST['reply'] === 'yes'){
		$reply= trim(mysql_prep($_POST['reply']));
		
		}	
	
	$unread = 'yes';
	$participants = $sender .' , '.$reciever;
	$created = date('c');

	if($pass){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `messaging`(`id`, `reciever`, `sender`, `subject`, `content`, `parent_id`, `unread_sender`, `unread_reciever`, `reply`, `participants`,`created`) 
		VALUES ('0', '{$reciever}', '{$sender}', '{$subject}', '{$message}', '{$parent_id}', '{$unread}', '{$unread}', '{$reply}', '{$participants}','{$created}')") 
		or die("Failed to save message!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message("success","Done!");
			if(addon_is_active('sms')){
				$message = substr($content,0,110);
				$response = sms_notify($sender,$reciever,$message);
				if($response){	
					status_message('alert',$response.' an SMS notification has been sent!');
					}
			} else { status_message('alert','No sms sent - SMS addon not enabled!'); }
			go_back();
			}
	}
}

function send_message_link($user=''){
	$user = trim(mysql_prep($_GET['user']));
	$me = $_SESSION['username'];
	if(is_logged_in())
		if($_SESSION['username'] !== $user){
		
		echo "<span><a href='".ADDONS_PATH."messaging/?mid=&parent_id={$user}.{$me}&unread=no&older=yesreciever=" .$user ."'><button>Send Message</button></a></span>";
		//echo "<span><a href='".ADDONS_PATH."messaging'><button>Inbox</button></a></span>";
		
		} else {
			//echo "<div class='col-md-12'>";
			//echo "<a href='".ADDONS_PATH."messaging?send_message=yes&reciever=" .$user ."'><button><i class='glyphicon glyphicon-pencil'></i> Write a new message</button></a>";
			echo "<span><a href='".ADDONS_PATH."messaging'><button><i class='glyphicon glyphicon-envelope'></i> Inbox</button></a></span>";
			}
	
	
	}
	

function display_unread_messages(){
	$user = $_SESSION['username'];
	
			$list = "<section class='container'>
				<h2>New messages</h2>
				<table class='table'>";
					$message_count = 0;	
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `messaging` WHERE `unread_reciever`='yes' AND `reciever`='{$user}' GROUP BY `sender` ORDER BY `id` DESC") 
	or die("Failed to get messages!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		while ($message = mysqli_fetch_array($query)){
			//Get sender pic
			$pic = show_user_pic($message['sender'],$pic_class='img-circular');
			
			$parts = explode(" , ",$message['participants']);
			if(in_array($user,$parts)){
			
			if ($message['sender'] === $_SESSION['username']){$message['sender'] = 'me';}
			
				if($message['reply'] === 'no'){
				$list = $list. "<tbody><tr><td class='table-message-message'>{$message['sender']} > {$message['reciever']}<time class='timeago tiny-text u-pull-right green-text' datetime='".$message['created']."' title='".$message['created']."'></time><br> <a href='" .ADDONS_PATH ."messaging/?mid=" .$message['id'] ."&parent_id=".$message['parent_id']."&unread=no"."'>".
				substr($message['content'],0,28)  ."</a></td></tr></tbody>";
				} else{
					
				$list = $list. "<tbody><tr><td class='table-message-message'> {$message['sender']}<time class='timeago tiny-text u-pull-right green-text' datetime='".$message['created']."' title='".$message['created']."'></time><br><a href='" .ADDONS_PATH ."messaging/?mid=" .$message['parent_id'] ."&parent_id={$message['parent_id']}&unread=no"."'>".
				substr($message['content'],0,28) ."</a> <em> reply</em></td></tr></tbody>";
					} $message_count++;
			
			if(empty($message['parent_id'])){
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `messaging` SET `parent_id`='{$message['id']}' WHERE `id`='{$message['id']}'")
				or die("Failed to update Parent id! ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				}
			}
		}$count = $message_count;
				
		$list = $list ."</table><hr></section>";
		
		if(empty($count)){
			$list.= "<span class='text-center'><em>-No new messages-</em></span>";
			}
			
		$output = array('display'=>$list,'count'=>$count);	
		
		return $output;
	
}




function display_read_messages(){
	$query_string = $_SERVER['QUERY_STRING'];
	
	
	$user = $_SESSION['username'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `messaging` WHERE `reciever`='{$user}' GROUP BY `sender` ") 
	or die("Failed to get messages!") .mysql_error;
	
	if($query){
		$list = "<div class='container'><br>
				<h2>Older Messages</h2>
				<table class='table'>";
						
		while ($message = mysqli_fetch_array($query)){
			$parts = explode(" , ",$message['participants']);
			if(in_array($user,$parts)){
	
			sort($parts);
			$person1= $parts[0];
			$person2= $parts[1];
			if($user == $person1 && $person1 != $person2){
				$other_person = $person2;
				} else if($user == $person2 && $person1 != $person2){
					 $other_person = $person1 ;
					 }else{ $other_person = $user;}
				
				if(!empty($message['parent_id'])){
					$it = $message['parent_id'];
				} else {
					$it = $message['id'];
					}
				
		//Get sender pic
			$pic = show_user_pic($other_person,$pic_class='img-circular');
			
			$list = $list. "<tbody><tr><td class=''>{$message['sender']} > {$message['reciever']}<time class='timeago tiny-text u-pull-right green-text' datetime='".$message['created']."' title='".$message['created']."'></time><br> <a href='" .ADDONS_PATH ."messaging/?mid=" .$message['id'] ."&parent_id=".$message['parent_id']."&unread=no"."&older=yes"."'>".
				substr($message['content'],0,28) ."</a><span class='tiny-text u-pull-right'><a href='".ADDONS_PATH."messaging/?delete=".$message['id']."&control=".$_SESSION['control']."'><em>delete</em></a></span></td></tr>";
				}
			if(empty($message['parent_id'])){
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `messaging` SET `parent_id`='{$message['id']}' WHERE `id`='{$message['id']}'")
				or die("Failed to update Parent id! ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				}	
			}
			echo $list ."</tbody></table></div>";
		}
		
	
}


function display_sent_messages(){
	$query_string = $_SERVER['QUERY_STRING'];
	
	
	$user = $_SESSION['username'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `messaging` WHERE `sender`='{$user}' AND unread_reciever='yes' GROUP BY `reciever` ") 
	or die("Failed to get messages!") .mysql_error;
	
	if($query){
		$list = "<div class='container'><br>
				<h2>Sent and unread Messages</h2>
				<table class='table'>";
						
		while ($message = mysqli_fetch_array($query)){
			$parts = explode(" , ",$message['participants']);
			if(in_array($user,$parts)){
	
			sort($parts);
			$person1= $parts[0];
			$person2= $parts[1];
			if($user == $person1 && $person1 != $person2){
				$other_person = $person2;
				} else if($user == $person2 && $person1 != $person2){
					 $other_person = $person1 ;
					 }else{ $other_person = $user;}
				
				if(!empty($message['parent_id'])){
					$it = $message['parent_id'];
				} else {
					$it = $message['id'];
					}
				
		//Get sender pic
			$pic = show_user_pic($other_person,$pic_class='img-circular');
			
			$list = $list. "<tbody><tr><td class='table-message-older'>{$message['sender']} > {$message['reciever']}<time class='timeago tiny-text u-pull-right green-text' datetime='".$message['created']."' title='".$message['created']."'></time><br> <a href='" .ADDONS_PATH ."messaging/?mid=" .$message['id'] ."&parent_id=".$message['parent_id']."&unread=no"."&older=yes"."'>".
				substr($message['content'],0,28) ."</a><span class='tiny-text u-pull-right'><a href='".ADDONS_PATH."messaging/?delete=".$message['id']."&control=".$_SESSION['control']."'><em>delete</em></a></span></td></tr>";
				}
			if(empty($message['parent_id'])){
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `messaging` SET `parent_id`='{$message['id']}' WHERE `id`='{$message['id']}'")
				or die("Failed to update Parent id! ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				}	
			}
			echo $list ."</tbody></table></div>";
		}
		
	
}

function fetch_new_thread_messages($participants=''){
	$persons = $_SESSION['message_thread_participants'];
	if(empty($participants)){
		$participants = $_SESSION['participants'];
		}
		$id = max($_SESSION["message_{$participants}_shown"]);
		//echo 'id is '. $id;
		
	$user = $_SESSION['username'];
		//echo  "i reach here o!";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `messaging` WHERE `id` > '{$id}' AND `reciever`='{$user}' AND `parent_id`='{$participants}' ORDER BY `id` DESC {$limit}") 
		or die("Failed to get messages!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$num = mysqli_num_rows($query);
		
		//if(!empty($num)){
		//	echo ' '. $num .' messages fetched!';
		//	} else { echo 'nothing o';}
			echo "<div>
				<table class='table'><tbody>";
		while($message = mysqli_fetch_array($query)){
		$_SESSION['last_message_thread_id'] = $message['id'];
			//Get sender pic
			$pic = show_user_pic($message['sender'],$pic_class='img-circular');
			
			$parts = explode(" , ",$message['participants']);
			if(in_array($user,$parts)){
			
			if ($message['sender'] === $_SESSION['username']){$message['sender'] = 'me';}

				echo "<tr><td class='table-message-message'>{$message['sender']}<time class='timeago tiny-text u-pull-right green-text' datetime='".$message['created']."' title='".$message['created']."'></time><br> <a href='" .ADDONS_PATH ."messaging/?mid=" .$message['id'] ."&parent_id=".$message['parent_id']."&unread=no"."'>".
				$message['content'] ."</a></td></tr>";
				
			}
		
	} 
	
	$_SESSION['last_message_thread_id'] = $_SESSION['last_message_thread_id'] + $num;
		echo '</tbody></div>';
		//  END CHECK FOR NEW mESSAGES
	
}

function display_message_thread($parent_id=''){
	
	if(isset($_GET['parent_id'])){
		$parent_id = trim(mysql_prep($_GET['parent_id']));
	}
	$participants = explode('.',$parent_id);
	$displayed_participants = $participants;
	
	sort($participants);
	$person1= $participants[0];
	$person2= $participants[1];
	
	$user = $_SESSION['username'];
	
	
	$persons = $participants[0].'.'.$participants[1];
	$_SESSION['participants'] = $persons;
	
	if(($key= array_search($user,$participants)) !== false){
		unset($participants[$key]);
		}
	foreach($participants as $p){
		$reciever = $p;
		}

	
	
					// respond to reply 
			if($_POST['submit']==='Send message' && $_POST['message'] !==''){
			$reply = mysql_prep($_POST['message']);
						
			send_message($sender='me',$reciever=$reciever,$content="{$reply}",$parent_id="{$_POST['parent_id']}");
			$_POST='';
			redirect_to($_SESSION['current_url']);
			}
			
			// show response form
		echo "<div class='padding-10'><form method='post' action='http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}'>
		<input type='hidden' name-'parent_id' value='".$persons."'>
		<textarea name='message' placeholder='respond'></textarea>
		<input type='submit' name='submit' value='Send message'>
		</form></div>";	
			
			
	//Start requesting thread
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `messaging` WHERE `parent_id`='{$persons}' ORDER BY `id` DESC {$limit}") 
	or die("Failed to get messages!") .mysql_error;
	
		
				
		$thread = "<div id='new-messages'> </div>
		<section id='message_holder'>
				<table class=''>";
			$_SESSION["message_{$persons}_shown"] = array();
						
			while ($message = mysqli_fetch_array($query)){
			$_SESSION["message_{$persons}_shown"][] = $message['id'];
			
			//Get sender pic
			$pic = show_user_pic($message['sender'],$pic_class='img-circular');
			
			# CHECK TO MAKE SURE USER IS A PARTICIPANT
				
				$parts = explode(" , ",$message['participants']);
				
			if(in_array($user,$parts)){
				$_SESSION['message_thread_id'] = $message['id'];
				$participants = $message['sender'] .', '.$message['reciever'];
			if($message['unread_reciever'] === 'yes' && $message['reciever'] === $_SESSION['username']){
			$unread_status_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `messaging` SET `unread_reciever`='no' WHERE id='{$message['id']}'") 
		or die("Failed to update unread status") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			}

				
				if ($message['sender'] === $_SESSION['username']){
					$message['sender'] = 'me';
					$td_class='table-message-sent-messages';
				}else{
					$td_class='table-message-older';
				}
					$thread = $thread. "<tbody><tr><td class='{$td_class}'>".$message['sender']."<time class='timeago tiny-text u-pull-right green-text' datetime='".$message['created']."' title='".$message['created']."'></time><br>".parse_text_for_output($message['content']) 
					.'&nbsp;&nbsp;&nbsp;<span class="tiny-text u-pull-right"><a href="'.ADDONS_PATH .'messaging?delete='.$message['id'] .'&control='.$_SESSION['control'].'">delete</a></span>';
				
	} 
	
	}

			
			if($_SESSION['username'] === $message['sender']){
	$unread_status_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `messaging` SET `unread_sender`='no' WHERE id='{$id}'") 
	or die("Failed to update unread status") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	} else if($_SESSION['username'] === $message['reciever']){
		$unread_status_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `messaging` SET `unread_reciever`='no' WHERE id='{$id}'") 
		or die("Failed to update unread status") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
		
		
		
		echo "&nbsp&nbsp<strong>Participaints : </strong>" ;
		if($participants){
			foreach($displayed_participants as $person){
		  echo ' <a href="'.BASE_PATH.'user/?user='.$person.'">'.$person.'</a> , ';
		  }
		}
		echo $full_thread ."<br>" .$thread  . "</td></tr></tbody></table></div></section>";
		
		echo $pager;
		
		
	
}
	
function reply_message(){
	
		if (isset($_GET['reply_to'])){
		$reciever = $_GET['reply_to'];
		$subject = $_GET['subject'];
		show_message_form($subject);
	}
}

function delete_message(){
	if(is_logged_in()){ 
		if($_GET['control'] == $_SESSION['control']){
			$mid = $_GET['delete'];
			$delete_query=mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `messaging` where `id`='{$mid}' or `parent_id`={$mid}")  
			or die("Failed to delete message!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			
			if($delete_query){
				
				session_message('success', 'Message deleted!');
				redirect_to($_SESSION['prev_url']);
				}
		}
	}
}

function new_message_notification(){
	$unread = display_unread_messages();
	
	if(isset($_SESSION['username'])){
		if($unread['count'] > 0){
			status_message('alert', 'You have <a href="'. ADDONS_PATH .'messaging">'.$_SESSION['new_message_count'] .' new messages</a>');
			}
		}
}
	


 // end of messaging functions file
 // in root/messaging/includes/functions.php
?>
