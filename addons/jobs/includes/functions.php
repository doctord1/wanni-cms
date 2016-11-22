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

function show_jobs_navigation(){
	$query_string = $_SERVER['QUERY_STRING'];
	if(empty($query_string)){
	echo'<span>
	<a href="'.ADDONS_PATH.'jobs?action=add_job"><button>+Add Job</button></a></span>';
	} else{ link_to(ADDONS_PATH.'jobs','Back to Jobs Page');}
}

function add_job(){
	$user = $_SESSION['username'];
	$parent = $_GET['parent'];
	$parent_id = $_GET['tid'];
	$form = "<form method='post' action='process.php' class=' padding-20 edit-form'>
	<h2>Add new job</h2>
	Description : <br><textarea name='description'></textarea>
	<br><input type='text' name='reward' placeholder='Reward'>
	<input type='hidden' name='parent' value='{$parent}'>
	<input type='hidden' name='parent_id' value='{$parent_id}'>
	<input type='text' name='location' placeholder='Location(optional)'>
	<br>Requirement : <br><textarea name='requirements'></textarea>
	<br><input type='text' name='deadline' placeholder='Deadline for this job (date)'>
	<input type='hidden' name='owner' value='{$user}'>
	
	<input type='submit' name='submit' value='Add job'>
	</form>";
	echo $form;
}

function edit_job(){
	$user = $_SESSION['username'];
	$form = "<form method='post' action='process.php' class='padding-20'>
	<h2>Edit job</h2>
	Description : <br><textarea name='description'></textarea>
	<br><input type='text' name='reward' placeholder='Reward'>
	<input type='text' name='location' placeholder='Location(optional)'>
	<br>Requirement : <br><textarea name='requirement'></textarea>
	<input type='hidden' name='owner' value='{$user}'>
	<input type='submit' name='submit' value='Add job'>
	</form>";
	echo $form;
}

function delete_job(){
	
}

function show_jobs_color_codes(){
	if(empty($_SERVER['QUERY_STRING']) || $_GET['page_name']=='home'){
	echo '<div class="text-center"><strong>Color codes:</strong> <span class="code-pending">pending</span>'; 
		echo '<span class="code-started">started</span>'; 
		//echo '<span class="code-submitted">bid accepted</span>';
		echo '<span class="code-completed">completed</span>';
		//echo '<span class="code-disapproved">disapproved</span>';
		echo'  </div><br>';
	}
}

function get_job_lists(){
	$query_string = $_SERVER['QUERY_STRING'];
	show_jobs_color_codes();
	
	if(is_home_page()){
	echo '<a class="u-pull-right" href="'.ADDONS_PATH.'jobs">See all [Jobs]</a><br>';
	}
	
	if(empty($_SERVER['QUERY_STRING']) && !isset($_GET['action']) ||(!isset($_GET['action']) && $_GET['page_name']=='home')){
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `jobs` ORDER BY `id` DESC {$limit}");
	
	echo '<div class="padding-20 whitesmoke">
	<h2>Job lists</h2><hr><ul>';
	while($result = mysqli_fetch_array($query)){
		echo '<div class="lavender padding-10">';
		if($result['status'] == 'started'){
			$color_code = '<span class="code-started"></span>';
		} else if($result['status'] == 'satisfied'){
			$color_code = '<span class="code-completed"></span>';
		} else {
			$color_code = '<span class="code-pending"></span>';
		}
		echo $color_code .'&nbsp <a href="'.ADDONS_PATH.'jobs/?job_title='.$result['title'].'&jid='.$result['id'].'"> '.ucfirst(str_ireplace('-',' ',$result['title'])) .'</a> '
		.'<span class="u-pull-right badge">Reward : '.$result['reward'].'</span></div><hr>';
		
		}
		
	echo $pager;
	echo '</ul></div><p></p>';
	//echo '</div>';//end row
		}
}
	
	

function search_jobs(){
	
	$s = $_SERVER['QUERY_STRING'];
	
	if($s ===''){
		
	echo '<div align="center" class="padding-10">';
	
	echo '<h2>Search Jobs</h2>';
	show_search_special_form($table='jobs',$column='title');	
	echo '</div>';
	echo '<div class="center">';
	do_search($table='jobs',$column='title');
	echo '</div>';

	
	}	
	
	}


function get_job_content(){
	
	if(isset($_GET['job_title'])){
		
	$id = trim(mysql_prep($_GET['jid']));
	$title = trim(mysql_prep($_GET['job_title']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `jobs` WHERE `id`='{$id}' or title='{$title}'") 
	or die('Error fetching job content ' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	while($result=mysqli_fetch_array($query)){
		$_SESSION['job_reward'] = $result['reward'];
		$_SESSION['job_id'] = $result['id'];
		
		echo '<div class="sweet_title">'.ucfirst(str_ireplace('-',' ',$result['title'])).'</div>';
		
		if($result['status'] == 'satisfied'){
			status_message('success', 'This job has been completed, or the owner has been satisfied!');
			echo '<br>';
		}
	    
	    
	    // show button to mark as completed
	    
			$id = trim(mysql_prep($_GET['jid']));
			if($result['status'] == 'started' 
			&& !empty($_GET['job_title']) 
			&& $_GET['action'] != 'mark-satisfied'
			&& $result['owner'] == $_SESSION['username']){
				echo "<a href='".$_SESSION['current_url'].'&action=mark-satisfied'."'><button>Mark as Satisfied / Completed</button></a>";
			}
		
		
		echo '<div class="u-pull-right">'.mark_job_as_satisfied() .'</div>';
		echo '<div class="page_content"><strong>Job Description : </strong>'.$result['description'];
		echo'</div>'; // end page-content
		echo '<div class="reward"><strong>Reward : </strong>'.$result['reward'].'</div>';
		echo '<div class="whitesmoke"><strong>Requirements : </strong>'.$result['requirements'].'</div><p></p>';
		$_SESSION['job_status'] = $result['status'];
		$_SESSION['author'] = $result['owner'];
		
		if(addon_is_active('project_manager') && is_logged_in()){
			if(($_SESSION['username'] == $result['assigned_to'] || $_SESSION['username'] == $result['owner']) && $result['status'] != 'satisfied' && !is_a_project($result['title'])){
			make_task('Convert to task (if required) and start assigning tasks');
			}	
		
		//Show project and tasks
		
			 if(is_a_task($result['title']) && $_SESSION['job_status'] !== 'satisfied' &&  $_SESSION['username'] == $result['assigned_to']){
			
			echo '<div class="col-md-8 beige text-center padding-10"> This job has Tasks: <a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['title'].'">'.$result['title'].'</a></div>';
			echo '<div class="col-md-4 "> <a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&task_name='.$result['title'].'&parent='.$result['title'].'&parent_type=jobs"><button class=" margin-10">Add a new task </button></a></div>';
			}
		}
			
		echo '<div class="clear" id="task-list"><br>';
	
		 pm_color_codes();
		 
		show_task_list();
		}
		echo '</div>';
	}    
	
	
}


function accept_bid(){
	
	if(is_author() && !empty($_GET['accept_bid'])){
	$id = trim(mysql_prep($_GET['accept_bid']));
	$bid_owner = trim(mysql_prep($_GET['bid_owner']));
	$jid = trim(mysql_prep($_GET['jid']));
		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `jobs` SET `status`='started',`assigned_to`='{$bid_owner}' WHERE `id`='{$jid}'") 
	or die("Cannot Accept bid " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `jobs_bids` SET `status`='accepted' WHERE `id`='{$id}'") 
	or die("Cannot Accept bid " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$_SESSION['job_status'] = 'accepted';
	$_SESSION['accepted_bid'] = $id;
	
	redirect_to($_SESSION['prev_url']);
	}
}

function mark_job_as_satisfied(){

	if(is_author() && $_GET['action'] == 'mark-satisfied' && $_SESSION['job_status'] != 'satisfied'){

	$jobs_bid = $_SESSION['accepted_bid'];
	
	echo '<div class="padding-20 edit-form"><form method="post" action="'.$_SESSION['current_url'].'">
	Rate : <a href="'.BASE_PATH.'user?user='.$_SESSION['accepted_bid_owner'].'">'.$_SESSION['accepted_bid_owner'].'</a><br>
	Any comments about this job? how satisfied are you ? and how easy was it to work with '.$_SESSION['accepted_bid_owner'].
	'<br>Rating<select name="rating">
	<option selected>Excellent</option>
	<option>Satisfactory</option>
	<option>Poor</option>
	<option>Had some problems</option>
	</select>
	<input type="hidden" name="bid_id" value="'.$id.'">
	<textarea name="job_owner_feedback"></textarea>
	<input type="submit" name="submit" value="Rate user">
	</form></div>';

	if($_POST['submit'] == 'Rate user'){
	
	
	$job_owner_feedback = trim(mysql_prep($_POST['job_owner_feedback']));
	$rating = trim(mysql_prep($_POST['rating']));
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `jobs_bids` SET `job_owner_feedback`='{$job_owner_feedback}', `rating`='{$rating}' WHERE `id`='{$jobs_bid}'") 
	or die("Cannot save jobs bids rating! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `jobs` SET `status`='satisfied' WHERE `id`='{$id}'") 
	or die("Cannot Mark job as satisfied! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
		session_message('success','We are happy that you are now satisfied!');
		}
	
	$_SESSION['job_status'] = 'satisfied';
	redirect_to($_SESSION['prev_url']);
	}
	
	}	
}

function submit_job_report(){

	if((($_SESSION['accepted_bid_owner'] == $_SESSION['username'] || $_SESSION['author'] == $_SESSION['username']) && is_logged_in())
	&& $_GET['action'] != 'mark-satisfied' 
	&& $_SESSION['job_status'] != 'satisfied'
	&& !empty($_GET['job_title'])){

	$jobs_bid = $_SESSION['accepted_bid'];
	
	if($_SESSION['author'] == $_SESSION['username']){
	$button_value = 'Respond or leave a note';
	$help_message = 'Leave a note for : <a href="'.BASE_PATH.'user?user='.$_SESSION['accepted_bid_owner'].'">'.$_SESSION['accepted_bid_owner'].'</a><br>';
	} else if($_SESSION['accepted_bid_owner']== $_SESSION['username']){
		$help_message = 'Report to  : <a href="'.BASE_PATH.'user?user='.$_SESSION['author'].'">'.$_SESSION['author'].'</a><br>
		Situation report ';
		$button_value = 'Submit report';
		}
	echo '<h2>Notes and Reports</h2>';
	echo '<div class="padding-20 edit-form"><form method="post" action="'.$_SESSION['current_url'].'">'.
	$help_message.
	'<textarea name="job_report"></textarea>
	<input type="submit" name="submit" value="'.$button_value.'">
	</form></div>';

		if($_POST['submit'] == 'Submit report' || $_POST['submit'] == 'Respond or leave a note'){
			
			$content = trim(mysql_prep($_POST['job_report']));
			$job_id = trim(mysql_prep($_GET['jid']));
			$author = $_SESSION['username'];
			$date = date('c');
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO jobs_reports(`id`, `job_id`, `author`, `content`, `created`) 
			VALUES ('','{$job_id}','{$author}','{$content}','{$date}')")
			or die("Cannot save jobs report" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
		}
		
		
		
	}
	// GET Reports
		if(is_admin() || $_SESSION['accepted_bid_owner']== $_SESSION['username'] || $_SESSION['author'] == $_SESSION['username']){
			$job_id = trim(mysql_prep($_GET['jid']));
			
			
			echo '<table><tbody>';
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jobs_reports WHERE job_id='{$job_id}' ORDER BY id DESC") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			while($result = mysqli_fetch_array($query)){
				$pic = show_user_pic($result['author'],'img-circular');
				echo '<tr><td class="table-message-sender">'.$pic['thumbnail'].'</td><td class="table-message-plain">'.
				"<time class='timeago tiny-text u-pull-right green-text' datetime='".$result['created']."' title='".$result['created']."'></time><br>".$result['content'].'</td></tr>';
			}
			echo '</tbody></table><br>';
		}
}


function has_bidded(){
	$user = $_SESSION['username'];
	if(isset($_GET['jid'])){
	$jid = trim(mysql_prep($_GET['jid']));
	} else if(isset($_SESSION['job_id'])){
		$jid = trim(mysql_prep($_SESSION['job_id']));
		}
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`owner` FROM `jobs_bids` WHERE `job_id`='{$jid}' AND `owner`='{$user}'") 
	or die('Failed to check if user has placed bid! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	//$result = mysql_fetch_array($query);
	$_SESSION['has_bidded'] = true;
	
	$result = mysqli_num_rows($query);
	if(!empty($result)){
		return true;
		} else {
		return false;	
			}	
}

function place_bid(){
	if(!has_bidded() && !empty($_GET['job_title']) 
	&& $_SESSION['job_status'] != 'accepted' 
	&& !is_author()
	&& is_logged_in()){
	$user = $_SESSION['username'];
	$jid = trim(mysql_prep($_GET['jid']));
	echo '<form method="post" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" class="gainsboro padding-20">
	<h2 align="center">Apply / Bid for this job</h2>
		<input type="hidden" name="owner" value="'.$user.'">
		<input type="hidden" name="job_id" value="'.$jid.'">
		My Bid : (<em>Tell the job poster why you should get this job and what you will do to make it happen</em>)<textarea name="bid"></textarea>
		<br><input type="text" value="" name="expected_delivery_time" placeholder="Expected delivery time">
		<input type="text" value="" name="location" placeholder="Present location">
		<input type="submit" value="Place bid" name="submit">
		</form>';
	} else{
		if(!is_logged_in()){
		log_in_to_continue();
		status_message('alert','You may not bid, unless you are logged in');
		}
		}
}

// cv functions

function add_area_of_specialization(){
	form_start();
	form_text($name='area_of_specialization',$placeholder='Area of specialization');
	form_end($name='submit', $value='Submit');
	}

function add_qualification_attained(){
	form_start();
	form_text($name='qualification',$placeholder='Qualification');
	form_end($name='submit', $value='Submit');
	}

function add_money_service(){
	form_start();
	form_number($name='amount',$placeholder='Amount');
	form_text($name='money_service',$placeholder='Money service');
	
	
	form_end($name='submit', $value='Submit');
	}


function process_job_submission(){
	$id = trim(mysql_prep($_POST['id']));
	$parent = trim(mysql_prep($_POST['parent']));
	$parent_id = trim(mysql_prep($_POST['parent_id']));
	$description = trim(mysql_prep($_POST['description']));
	$owner = trim(mysql_prep($_POST['owner']));
	$reward = trim(mysql_prep($_POST['reward']));
	$requirements = trim(mysql_prep($_POST['requirements']));
	$location = trim(mysql_prep($_POST['location']));
	$deadline = trim(mysql_prep($_POST['deadline']));
	$title  = strtolower(str_ireplace(' ','-',substr($description,0,28)));
	$title .= '...';
	
	
	if($_POST['submit'] == 'Add job'){
	
	//echo '<br>Title is '.$title;// testing
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `jobs`(`id`, `title`, `description`, `owner`, `reward`, `requirements`, `location`, `deadline`, `status`, `assigned_to`, `owner_feedback`,`parent`,`parent_id`) 
	VALUES ('0','{$title}','{$description}','{$owner}','{$reward}','{$requirements}','{$location}','{$deadline}','','','','{$parent}','{$parent_id}')") 
	or die('Error saving new Job! '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
		$jid = ((is_null($___mysqli_res = mysqli_insert_id($query))) ? false : $___mysqli_res);
		$destination_url = ADDONS_PATH.'jobs/?job_title='.$title.'&jid='.$jid;
		#process hashtags
		if(addon_is_active('hashtags')){
		$string = trim(mysql_prep($description));
		process_hashtags($string,$path=$destination_url);	
		}
		session_message('success','Job saved successfully!');
		}
	redirect_to(ADDONS_PATH.'jobs');
	
	} else if($_POST['submit'] == 'Update Job'){
		
		
		}
}

function process_bids_submission(){
	
	if($_POST['submit'] == 'Place bid'){
		
		$jid = trim(mysql_prep($_POST['job_id']));
		$bid = trim(mysql_prep($_POST['bid']));
		$owner = trim(mysql_prep($_POST['owner']));
		$requirements = trim(mysql_prep($_POST['requirements']));
		$location = trim(mysql_prep($_POST['location']));
		$expected_delivery_time = trim(mysql_prep($_POST['expected_delivery_time']));
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `jobs_bids`(`id`, `job_id`, `bid`, `owner`, `requirements`, `expected_delivery_time`, `status`, `job_owner_feedback`)
		 VALUES ('0','{$jid}','{$bid}','{$owner}','{$requirements}','{$expected_delivery_time}','','')") 
		 or die('Failed to save bid! '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 
		 if($query){
			 status_message('success','Bid placed successfully!');
			 }
	}
}

function show_bids(){
	
	if(!empty($_GET['job_title'])){
	withraw_bid();
	
	$jid = trim(mysql_prep($_GET['jid']));
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `jobs_bids` WHERE `job_id`='{$jid}' ORDER BY id ASC {$limit}") 
	or die('Failed to Show bids! '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	echo "<h3>Bids</h3><ul>";
	while($result = mysqli_fetch_array($query)){
		
		if(!empty($result['job_owner_feedback']) && !empty($result['rating'])){
			$_SESSION['job_status'] = 'satisfied';
			}
		
		$user_pic = show_user_pic("{$result['owner']}",'img-circular');

		if(($_SESSION['job_status'] == 'started' || $_SESSION['job_status'] == 'satisfied') && $result['status'] == 'accepted'){
			$bg_color = 'light-green'; 
			$status = "<h3 align='center'>Accepted</h3>";
			$_SESSION['accepted_bid'] = $result['id'];
			$_SESSION['accepted_bid_owner'] = $result['owner'];
		
			} else { 
				$bg_color = 'lavender';
				$status = '';
				
				}
		if(empty($_SESSION['job_status']) && $_SESSION['username'] == $_SESSION['author'] && is_logged_in()){
			$accept_bid = '<a href="'.ADDONS_PATH.'jobs?jid='.$jid .'&accept_bid='.$result['id'].'&bid_owner='.$result['owner'].'"> <button> Accept bid </button> </a> -';
		$show_cv_link = ' <a href="'.ADDONS_PATH.'jobs/cv/?user='.$result['owner'].'"><span class="badge"> CV </span> </a>';
		$accepted_mesage = '';
		} else {
			$accept_bid = '';
			$show_cv_link = ' <a href="'.ADDONS_PATH.'jobs/cv/?user='.$result['owner'].'"><span class="badge"> CV </span> </a>';	
			}
			
		echo '<div class="page-content '.$bg_color.'"><li>';
				
		
		echo '<span class="u-pull-right">'.$user_pic['thumbnail'];
		if($_SESSION['username'] == $result['owner'] && $_SESSION['job_status'] != 'started' 
		&& $_SESSION['job_status'] != 'satisfied'
		&& is_logged_in()){
		echo "<div class='left-margin tiny-edit-text badge'><a href='".$_SESSION['current_url']."&action=withdraw_bid&bid_id=".$result['id'].
		"&control=".$_SESSION['control']."'>Withraw bid</a></div>";	
		}
		echo '</span><strong>Bid #'.$result['id'].' : </strong>'.$result['bid'].'<br>';
		//.'<br><a href="'.BASE_PATH.'user/?user='.$result['owner'].'" class="tiny-text">'.$result['owner'].'</a>'.'';
		
		echo $accept_bid;
		echo $show_cv_link;
		echo $status;
		
		echo '</li></div>';
		}
	
	echo"</ul>{$pager}";
	}
	
	}
	
function withraw_bid(){
	
	if(isset($_GET['bid_id']) && $_GET['action'] == 'withdraw_bid'){
		//1. Get the logged in user , the job_id and the bid_id
	
	$user = $_SESSION['username']; //logged in user
	
	$job_id  = trim(mysql_prep($_GET['jid'])); //Job id
	
	$bid_id = trim(mysql_prep($_GET['bid_id'])); // bid id
	
	//2. Delete from jobs_bids where owner is (logged in user) and job id is ($jid)
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `jobs_bids` WHERE `id`='{$bid_id}' AND `owner`='{$user}' AND `job_id`='{$job_id}'") 
	or die('Failed to withdraw bid '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	//3. Show success or failure message.
	
	if($query){
		session_message('success','Bid withdrawn successfully!'); //defined in root/includes/functions.php
	}
		redirect_to($_SESSION['prev_url']);
		
		}
}

function show_jobs_and_bidding_instructions(){
	echo "<h2>Jobs Manual</h2><ol>
	<li>Job poster's (Authors of job postings) can not bid on their own jobs.</li>
	<li>Once a bid is placed, it MAY or MAY NOT be accepted by the job poster(author).</li>
	<li>Bids may not be edited. a bid can only be withdrawn (deleted) and a new bid can then be placed.</li>
	<li>Once a bid is accepted, no other bidding can take place on that job, and bids can no longer be withdrawn.</li>
	<li>Once a job has started, that job can no longer be edited.</li>
	<li> A job is automatically marked as started once a bid has been accepted</li>
	<li> Job owners and bid winners may communicate using Notes and reports </li>
	</ol>
	";
}

function process_cv_submission(){
	
}
 // end of jobs functions file
 // in root/jobs/includes/functions.php
?>
