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
$r = $r .'/addons/contest'; #do not edit
require_once($r .'/includes/functions.php'); #do not edit

#======================================================================
#						TEMPLATE ENDS
#======================================================================
//print_r($_SESSION);

#				 ADD YOUR CUSTOM ADDON CODE BELOW



# ADD PAGES
function add_contest() {
	
// show form
$form = '<div class="edit-form">
<form method="POST" action="'.ADDONS_PATH.'contest/process.php' .'">
<input type="hidden" name="action" value="insert" >
<input type="hidden" name="back_url" value ="'.$_SERVER['HTTP_REFERER'] .'" >
Title <input type="text" name="contest_name" class="menu-item-form" placeholder="contest name" required>
<br>Visible:(Yes) <input type="checkbox" name="visible" value="1" checked="checked" class="checked">
<br>Menu Type: <select name="menu" size="1">
<option value="primary">Primary menu</option>
<option value="secondary">Secondary Menu</option>
<option value="user">User menu</option>
<option value="none" selected="selected" >None</option>
</select>
<br>Position:(<em>Starting from 0, higher numbers will appear last</em>)<br><input type="text" name="position" value="1" size="3" maxlength="3">
<br>Contest Rules / Guide lines:<br><textarea name="description" id="content-area" size="8"></textarea>
<br>Registration Amount (Site Funds): <br><input type="text" name="reg_amount" value="" required>
<br>Reward: <input type="text" name="reward" value="" required>
<div id="dates">
<hr>Duration :
<select name="duration">
<option>1 day</option>
<option>3 days</option>
<option>1 week</option>
<option>1 month</option>
</select>
</div>';
$form = $form .'
<input type="submit" name="submitted" value="Add contest" class="submit">
</form></div>';

	if(isset($_SESSION['username'])){
	echo $form;	// End of Form  
	} else {deny_access();}
}

# LIST CONTESTS

function get_contest_lists($category='') {
	
	$query_string = $_SERVER['QUERY_STRING'];
	if(empty($query_string) || url_contains('?section_name=')){
		
		$variables = show_more_execute('contest');
		$parent = $variables['parent'];
		$limit = $variables['limit'];
		$number_holder = $variables['number_holder'];
		
		$pager = pagerize();
		$limit = $_SESSION['pager_limit'];
		
		if(empty($_SESSION['pager_limit'])){
			$limit = 'LIMIT 0, 25';
			}
		$condition='';	
		if($category != ''){
		$condition = " WHERE category='{$category}'";		
		}
		
	  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM  `contest` {$condition} ORDER BY `id` DESC 
	  {$limit}") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	  #echo "Data fetched succesfully"; //testing
	
	  echo "<table ><tbody>";
	  
	  
	  while($result = mysqli_fetch_array($query)){
	$pic = show_user_pic($user=$result['author'],$pic_class='img-rounded',$length='');
	 $leader = get_contest_leader($result['id']);
		 if(!empty($leader)){
			  $leading_contestant = '<br><span class="pull-right tiny-text green-text">Currently Leading :<a href="'.BASE_PATH.'user/?user='.$leader.'">'.$leader.'</a></span>';
		
			 }
			 
		if($result['contest_name'] !=='home'){
		 if(!empty($result['created'])){
	$last_update = "<div class='last-updated'>Created - <time class='timeago' datetime='".$result['created'] ."'</time></div>";
		}
	}
	
	
	$status = '<span class="green-text pull-right">'.$result['status'].'</span>';
	
	echo '<tr><td class="">'
	  . '<img src="'.BASE_PATH.'uploads/files/default_images/contest.png" width="70px">'
	  .'</td><td class="table-message-plain"><a href="' .ADDONS_PATH .'contest/?contest_name=' .$result['contest_name'] .'&tid='.$result['id'].'"><strong><big> ' 
	  . str_ireplace('-',' ',ucfirst($result['contest_name'])) ;
	
	echo '</big></strong></a>'.$status.$leading_contestant.'';
	  
	  if(is_admin() || $result['author'] == $_SESSION['username']){
		activate_contest($result['id'],$result['status']);
		$pagelist .= '<span class="tiny-edit-text"><a href="' 
	  . ADDONS_PATH ."contest/edit/?action=edit_contest&contest_name="
	  . $result['contest_name']
	  . '&cid='.$result['id'].'" >edit </a>'
	  . '&nbsp <a href="'
	  . ADDONS_PATH ."contest/process.php?" 
	  . 'action='
	  . 'delete_page&'
	  . 'contest_name='
	  . $result['contest_name']
	  . '&deleted='
	  . 'jfldjff7'
	  . '" '
	  . '>delete </a></span>';
	  $leading_contestant ='';
	  }
	  $type = '';
	  if(!url_contains('addons/contest/')){
		  $type = "<span class='grey-text'> <em>contest</em></span>";
		  }
	  
	  echo $type ."</td></tr>";
	  }
	  echo "</tbody></table>";
	  
	  //if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
	 
	 
	  
	 // echo $pager;
	  
		//} else {deny_access();}
  }
}


function show_new_contests(){
$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM contest where status='active' order by id DESC LIMIT 0, 3")
 or die('Problem with new contests ' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
$num = mysqli_num_rows($query);
if(is_home_page()){
	echo '<a class="u-pull-right" href="'.ADDONS_PATH.'contest">See all [Contests]</a><br>';
	}
while($result= mysqli_fetch_array($query)){
     echo "<div class='page-content'> ";
     echo "<div class='padding-5 pull-right text-center inline-block thumbnail'>";
     $post =str_ireplace(' ','-',$result['contest_name'])." contest";
		 show_thumbnail($subject=$post);
		 $leader = get_contest_leader($result['id']);
		 if(!empty($leader)){
			  $leading_contestant = '<br><span class="pull-right tiny-text green-text ">Leading :<br><a href="'.BASE_PATH.'user/?user='.$leader.'">'.$leader.'</a></span>';
		
			 }
		echo $leading_contestant;
		 echo "</div><a href='" .ADDONS_PATH ."contest/?contest_name=" .str_ireplace(' ','-',$result['contest_name']) ."&contest=yes&tid=".$result['id']."'>" 
     . str_ireplace('-',' ',ucfirst($result['contest_name'])) ."</a><br>" ;
		 upload_no_edit();
		 echo parse_text_for_output(urldecode($result['description'])) ;
		 echo '<br><strong>Reward :</strong><span class="green-text">'.parse_text_for_output(urldecode($result['reward'])) .'</span>';
		
		 
		  echo "</div>";
     $leading_contestant='';
  
	} if(empty($num)){ status_message('alert','No active contests at this time.'); }
}   
 
# EDIT PAGES  
function edit_contest(){

if(url_contains('edit_')){
$page = trim(mysql_prep($_GET['contest_name'])); 
#echo $block; // Testing
}	

if(isset($page)){
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from contest WHERE id='{$_SESSION['contest_id']}' LIMIT 1") 
	or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 $row = mysqli_fetch_array($q);
}

if($row['author'] == $_SESSION['username'] || is_admin()){
 if (isset($page)){
	 $target = $page;
	 $route = 'addons/contest/?contest_name=';
	 $end = 'contest';
	 
	 }

	 // now we show the contest edit form
	 echo "<strong> You are editing <em>&nbsp" . $target ." {$end} </em></strong>";
	 echo '<p align="center">Go to <a id="view-page" href="' .BASE_PATH .$route .$target .'"><strong><big> ' 
  . $target .' ' .$end
  . '</big></strong></a></p>';
	 	
  $menu_fetcher = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE `menu_item_name`='{$page}' LIMIT 1") or die("MENu item fetching failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$menu_item = mysqli_fetch_array($menu_fetcher);
	 	
$form = '<div class="edit-form">' .
'<form method="POST" action="../process.php">' .
'<input type="hidden" name="action" class="form" value ="update">' .
'<input type="hidden" name="menu_item" value="' .$menu_item['menu_item_name'] .'">' .
'<input type="hidden" name="menu_id" value="' .$menu_item['id'] .'">' .
'<input type="hidden" name="id"  value ="' .$row['id'] .'">' .
'Title:<br> <input type="text" name="contest_name" value ="' .$row['contest_name'] . '" >' .
'<br>Visible: <input type="checkbox" name="visible" value="1" checked="checked">(Yes)' .
'<br>' .
'Menu Type *: <select name="menu" size="1">'; 

// get and set the selected menu value
$menu_result = mysqli_query($GLOBALS["___mysqli_ston"], "Select menu_type_name from menu_type order by id") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

while($menu = mysqli_fetch_array($menu_result)) {
$form = $form .'<option value="' . $menu['menu_type_name'] .'" ';
if($menu['menu_type_name'] === $row['menu_type']) {
		 $form = $form . ' selected="selected" >'.ucfirst($menu['menu_type_name']) .'</option>'; 
		 }
		 else {
		 $form = $form . '>'.ucfirst($menu['menu_type_name']) .'</option>';	
		 	}
		 }
$form = $form .
'</select>' .
'<br>Description:<br><textarea name="description" id="content-area" rows="5">' .urldecode($row['description']) .'</textarea>' .
'<hr>Duration :
<select name="duration">
<option>1 day</option>
<option>3 days</option>
<option>1 week</option>
<option>1 month</option>
</select>';
$form = $form .'
	<br>Reward: <input type="text" name="reward" value="'. $row['reward'].'">
	<br>
<input type="submit" name="updated" value="Save contest" class="submit">' .
'<input type="submit" name="deleted" value="Delete">' .
'</form></div>';

echo $form;
}

 }



function install_contest_blocks(){ //Work in progress... 
	$child_switch_1 = "";
	// first check if already installed
	$check_status_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `block_name` FROM blocks WHERE parent_addon='contest'") 
	or die("Could not check if contest block_1 was installed") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	
	if($check_status_query){
		//if found, do not reinstall
		$child_switch_1 = "done";
		
		}else if($child_switch_1 !=="done"){
				
				$function_call = urlencode('<?php show_registered_contest_entries(); ?>');
			
				$child_switch_1_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `blocks`(`id`, `block_name`, `region`, `block_title`, `block_description`, `position`, `visible`, `content`, `function_call`, `parent_addon`, `show_title`, `page_visibility`) 
				VALUES ('','show registered contest entries', 'main content', 'Registered contest entries', 'Shows registered users of a particular contest on the contest page.', '2', '1', '', '{$function_call}', 'contest', '1')") 
				or die("Failed to install contest block 1") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				
				if($child_switch_1_query){ $child_switch_1 = "done"; }
			}
}


function register_for_contest(){
	
	if(isset($_SESSION['username'])&& empty($_GET['action']) && $_SESSION['contest_status']  != 'inactive'){
		if($_SESSION['contest_status'] != 'ended'){	
		$user = $_SESSION['username'];
		$contest_id = get_contest_id();
		$page = $_SESSION['contest_name'];
		unset($_SESSION["{$page}_{$user}_status"]);

		# CHECK IF USER HAS REGISTERED ALREADY
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `contest_entries` WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$user}'")
		 or die("Status query failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$result = mysqli_fetch_array($query);

		#CHECKING LOGIC
		if($result['contestant_name'] === $user){
			$_SESSION["{$page}_{$user}_status"] = '1'; 
			echo "<div class='clear'><br><em>You have registered for this contest! &nbsp</em>" ."<a href='" .ADDONS_PATH .'contest/register.php?' 
			."action=delete_reg'><button  class='error'>unregister </button></a><br></div>";
			
		}
		else{ 
				
			$user = $_SESSION['username'];
			$_SESSION["{$page}_{$user}_status"] = '0';
			echo "<br><a class='action-button-blue' href='" .ADDONS_PATH .'contest/register.php?user=' 
			.$user .'&contest_name=' .$page ."&tid={$contest_id}'> Register for this contest </a>" ;
							
		}
	} else { //contest has ended 
		}
	}
}
	
		
		
function show_contest_registration_form(){
	if(isset($_SESSION['username'])){
	$contest = trim(mysql_prep($_GET['contest_name']));
	$user_id = $_SESSION['user_id'];
	$registering_user = trim(mysql_prep($_GET['user']));
	
	
	if($_SESSION['site_funds_amount'] > 50){	
		show_contest_entry_form();
		  
		} else { echo "<br>";
		
		status_message("alert", "You do not have up to 50 site funds to register!<br>"); 
		
			echo '<br><a href ="' .ADDONS_PATH .'/contest/?contest_name=' .$contest .'"> Return to contest page</a>';
			 }
	}
}
	

function show_num_contestants(){
	if(!isset($_GET['action'])){
		$contest_id = $_SESSION['contest_id'];	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `contest_entries` WHERE `contest_id`='{$contest_id}'") 
		or die("Contest details  error" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		$count = mysqli_num_rows($query);
		
		echo "<h2><div class='red-text'>Contestants = </div><span class='total-contestants'>" .$count.'</span></h2><hr>';
	
	}
}
	

	
function check_user_contest_status(){
	if((isset($_SESSION['username']) && $_SESSION['contest_status'] == 'voting in progress') ||is_user_page()){
		
		
	if(isset($_POST['user_to_check'])){
		$contest_id = $_SESSION['contest_id'];
		$user = $_POST['user_to_check'];
		$contest_name = $_POST['contest_name'];
		$user_status_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `votes`, `contest_entry` FROM `contest_entries` WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$user}'") 
		or die("Failed to check user status! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$status_result = mysqli_fetch_array($user_status_query);
		
		$url1 = '<a href="'.ADDONS_PATH.'contest/?contest_name='.$contest_name.'">';
		$url2 = '</a>';
		echo '<h2>'.$url1.ucfirst($contest_name).$url2.'<em> <small>Contestant status check</small></em></h2>
		<div class="page_content"> Username = <span class="huge-text">' .$user .'</span> Number of votes = <span class="huge-text">'. $status_result['votes'] .'</span></div>';
		
		echo '<div class="page_content light-blue">'.$status_result['contest_entry'].' <span class="pull-right">
		<form action="'.ADDONS_PATH. '/contest/?reg_user='.$user.'&contest_name='.$contest_name.'&action=voting" method="post">
			<input type="submit" name="do_vote" value="Support me">
			</form></span></div>';
		
		}
		 
		if ((!empty($_GET['contest_name']) || !empty($_POST['contest_name'])) || is_user_page()){ 
		echo '<br><div class="page_content"><h2>Check user status in this contest</h2><form action="http://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']. '" method="post">
	<input type="text" name="user_to_check" value="" placeholder="Enter username ">
	<input type="hidden" name="contest_name" value="'.$_GET['contest_name'] .'">
	<input type="submit" name="submit" value="Check status" class="submit"></form>';

			}
	
	show_all_registered_contestants();	
	echo '</div>';
	}
}

function set_contest_duration(){
	echo '<select name="duration">
	<option>1 day</option>
	<option>3 days</option>
	<option>1 week</option>
	<option>1 month</option>
	</select>';
	}

function update_contest_status(){
	
	}

function show_contest_page(){
	unset($_SESSION['contest_winner']) ;
	$page = trim(mysql_prep($_GET['contest_name'])); 	
  if(!empty($_GET['contest_name']) ) {
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "select * from `contest` " .
	 'where contest_name="' .
	 $page .
	 '" ' .
	 " limit 1") or die("Failed to get selected contest" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	  
   $contest = mysqli_fetch_array($query);
   	$_SESSION['contest_id'] = $contest['id'];
	$_SESSION['author'] = $contest['author'];
	$_SESSION['contest_name'] = $page;
	$_SESSION['contest_revenue'] =  $contest['revenue'];
	$_SESSION['contest_duration'] =  $contest['duration'];
	$_SESSION['contest_end_date'] =  $contest['end_date'];
	$_SESSION['contest_status'] =  $contest['status'];
	$_SESSION['contest_reg_amount'] =  $contest['reg_amount'];
	$_SESSION['no comment_photos'] = 'true';
	
	 get_contest_vote_status();
	   
   	if($contest['contest_name'] !=='home'){
		if(!empty($contest['last_updated'])){
	$last_update = "<div class='last-updated'>Last updated -  <time class='timeago' datetime='".$contest['last_updated'] ."'</time></div>";
		}else if(!empty($contest['created'])){
	$last_update = "<div class='last-updated'>Created - <time class='timeago' datetime='".$contest['created'] ."'</time></div>";
		}
	}

   # GET PAGE IMAGES
	$is_mobile = check_user_agent('mobile');
	if($is_mobile){
		$size='medium';
	} else {
		$size='large';
		}
  $pics = get_linked_contest_media();	   

	   #SHOW CONTENT ONLY WHEN USER IS NOT PERFORMING AN ACTION e.g VOTING
	   if(!isset($_GET['action'])){ 
	 
	   # IF OWNER OR MANaGER, THEN SHOW EDIt link 
   if(isset($_SESSION['username'])){
		if($contest['author'] === $_SESSION['username'] || $_SESSION['role'] === 'manager' || $_SESSION['role'] === 'admin')  {

			 echo '  <section class="top-left-links">
						<ul>
							<li id="show_blocks_form_link" class="float-right-lists">
								<a href="'.ADDONS_PATH .'contest/edit/?action=edit_contest&contest_name='.$page.'&cid='.$contest['id'].'"> Edit this contest </a></li>			
							<li class="float-right-lists"><a href="'.BASE_PATH .'page/add?type=contest">Add a new Contest </a></li>
						</ul>
					</section>';
		   }
	}
	 
	 #show title
     echo "<div class='sweet_title'>" . str_ireplace('-',' ',ucfirst($page)) .$last_update."</br></div>";
     
     #show contest images

      if(!empty($pics)) { show_slideshow_block($pics); }
      
      #show content
     upload_no_edit();
     echo "<div class='page_content'>";
     
      if(!empty($contest['status'])){
			echo "<span class='pull-right padding-5 green-text inline-block'>status: ".$contest['status']."</span>";
			}
			$content = parse_text_for_output(($contest['description']));
		 echo $content ."<br><strong>
     Registration cost: </strong>".$contest['reg_amount']."  <div class='red-text margin-10 inline-block'><strong>Reward : </strong>". $contest['reward']."</div>
     <div class='margin-10 inline-block'><strong>Duration : </strong>". $contest['duration']."</div>
     </div>";
     show_contest_winner();
     show_contest_winning_entry_details();
     add_to_category();
     if(addon_is_active('featured_content')){
		show_feature_this_link();
		}
     echo "";
    
	show_user_follow_button($child_name=$page,$parent='contest'); 	
	follow($child_name=$page);
	unfollow($parent='contest',$child_name=$page);
	
		}
 
     
  }
  	
}



function show_contest_entry_form(){
	if(url_contains('contest/register')){
		if(isset($_SESSION['username']) ){
		$contest_id = get_contest_id();
		$contest_name = $_SESSION['contest_name'];
		$contest = '<a href="'.ADDONS_PATH.'contest/?contest_name='.$contest_name.'">'.$contest_name.'</a>';
		$reg_user = $_SESSION['username'];
		
		$status_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `contest_entries` WHERE `contestant_name`='{$reg_user}' AND `contest_id`='{$contest_id}'") 
		or die("contest entry status checking failed!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		$result = mysqli_fetch_array($status_query);
		
			if(empty($result['contest_entry'])){
				echo "<h2>You are registering for the {$contest} contest.</h2>";
				$comment = '<form action="./register.php" method="POST">
							<br>Tell everyone why you should be voted to win this contest<br>
							<input type="hidden" name="contest_id" value="'.$contest_id.'">
							<input type="hidden" name="contest" value="'. $_GET['contest_name'] .'">
							<textarea name="comments" rows="7" placeholder="Your comments here" required></textarea>
							<br><em>N.B - this may directly affect your chances of winning and CANNOT BE CHANGED once submitted</em><br>
							<input type="submit" name="submit" class="submit" value="Submit-entry">
							</form>';
				echo $comment;
				
			}
			
		} 
	}
}


function is_contest_entry_owner(){
	if($_SESSION['username'] == $_SESSION['contest_entry_owner']){
		return true;
		}
	}
	
function has_voted(){
	$user_id = $_SESSION['user_id'];
	$contest_entry_id = $_GET['contest_entry_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM contest_voters WHERE voter_uid='{$user_id}' AND contest_entry_id='{$contest_entry_id}'") 
	or die("Cannot check if user has voted " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$num = mysqli_num_rows($query);
	if(!empty($num)){
	return true;
	} else {
		return false;
		}
}

function get_contest_id(){
	
	if(isset($_GET['contest_name'])){
		$contest_name = trim(mysql_prep($_GET['contest_name']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `contest_name` FROM `contest` WHERE `contest_name`='{$contest_name}'") 
			or die ("Contest id selection failed! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			$result = mysqli_fetch_array($query);
			$contest_id = $result['id'];
			$contest_name = $result['contest_name'];
			$_SESSION['contest_id'] = $result['id'];
			$_SESSION['page_name'] = $contest_name;
			return $contest_id;
		}
}

function get_contest_vote_status(){
	// reverse calculate duration and set voting status based od
	// predefined rules
	if($_SESSION['contest_status'] != 'inactive'){
		$contest_id = $_SESSION['contest_id'];
		$duration = $_SESSION['contest_duration'];
		
	if(isset($_POST['duration'])){
		$duration = trim(mysql_prep($_POST['duration']));
	}
		
		$end_date = strtotime($_SESSION['contest_end_date']);
	
		$now = time();
		
		//$now = date('l jS F');
		$voting_allowed = false;
		// Get voting status
		$_SESSION['contest_vote_switch'] = 'off';
		$_SESSION['contest_status_checked'] = $_SESSION['contest_status'];
		if($end_date > $now){
			$voting_allowed = true; 
			} else { $voting_allowed = false;}
			
			if($voting_allowed == true && $_SESSION['contest_status'] != 'voting started'){
			$_SESSION['contest_status_checked'] = 'voting in progress';
			//
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE contest SET status='voting in progress' WHERE id='{$contest_id}'") 
			or die("Error setting contest vote status ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
			} else {
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE contest SET status='ended' WHERE id='{$contest_id}'") 
			or die("Error setting contest vote status ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			$_SESSION['contest_status_checked'] = 'ended';
			}
			
			if($_SESSION['contest_status'] !== $_SESSION['contest_status_checked']){redirect_to($_SESSION['current_url']);}
		}
}

function get_total_votes(){
	$contest_id = $_SESSION['contest_id'];
	$total_votes = 0;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT SUM(votes) FROM `contest_entries` WHERE `contest_id`='{$contest_id}'") 
	or die("ERROR fetching total votes!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$result = mysqli_fetch_array($query);
		$total_votes = $result['SUM(votes)'];
	if(mysqli_num_rows($query)>0){	
	return $total_votes;
	} else {
		$total_votes = 0;
		return $total_votes;}
}

function show_contest_revenue(){
	if(is_admin()){
		if(empty($_SESSION['contest_revenue'])){
			$revenue = 0;
			} else { $revenue = $_SESSION['contest_revenue'];}
		echo '<div class="col-md-12 btn btn-group" inline-block >
		<span class="padding-10 tan col-md-8 col-xs-8"><h4>Contest revenue &nbsp;</h4></span>
		<span class="padding-10 green white-text inline-block col-md-4 col-xs-4"><h4>'.$revenue.' &nbsp</h4></span></div>';
			
		}
	}
	
function activate_contest($contest_id='',$contest_status=''){
	if(isset($_POST['start_contest'])){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE contest SET status='voting in progress' WHERE id='{$contest_id}'") 
		or die("Failed to start contest " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			session_message('contest_activated');
			redirect_to($_SESSION['current_url']);
			}
		}
	if(is_admin() && $contest_status == 'inactive'){
		 echo "<span class='pull-left padding-5 inline-block'><a href='".$_SESSION['current_url']."'>
		 <form method='post' action='".$_SESSION['current_url']."'>
		 <input type='submit' name='start_contest' class='btn btn-primary btn-xs' value='Start'>
		 </form>";
		}
	}

function add_to_contest_revenue($amount=''){
	$contest_id = $_SESSION['contest_id'];
	if(isset($_POST['contest_id'])){
	$contest_id = $_POST['contest_id'];
	}
	//$total_votes = 0;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT revenue FROM `contest` WHERE `id`='{$contest_id}'") 
	or die("ERROR fetching contest revenue!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$result = mysqli_fetch_array($query);
	$revenue = $result['revenue'];
	$new_total = $revenue + $amount;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE contest SET revenue={$new_total} WHERE id={$contest_id}") 
	or die('Error updating contest revenue '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
}

function show_contest_stats(){
		if(is_admin()){
		$total_votes = get_total_votes();
		echo '<h2>Total votes :'.$total_votes.'</h2>';
		}
		show_contest_revenue();
	}

function set_total_votes(){
	$contest_id = $_SESSION['contest_id'];
	$total_votes = 0;
	$total_votes = get_total_votes();
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest` SET `total_votes`='{$total_votes}' WHERE `id`='{$contest_id}'") 
	or die("Failed to set total votes!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}

function get_linked_contest_media($contest_name='',$pic_size=''){
	if(isset($_GET['contest_name'])){
		$contest_name = trim(mysql_prep($_GET['contest_name'])) .' contest';
		}
	$destination_url = $_SESSION['current_url'];
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `files` WHERE `parent`='{$contest_name}' AND `destination_url`='{$destination_url}' ORDER BY `id` DESC ");
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

function show_registered_contest_entries(){
	if(!isset($_GET['action'])){
		# IF USERS HAVE REGISTERED, THEN SHOW A LIST OF TOP VOTED USERS
		# IF NO VOTED USERS, THE SHOW A LIST OF REGISTERED USERS 
		
		$contest_id = $_SESSION['contest_id'];
		if(isset($_GET['contest_name']) && $_SESSION['contest_status'] == 'voting in progress'){
			$contest_name = trim(mysql_prep($_GET['contest_name']));
			
			
		$total_votes = get_total_votes();

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `contest_entries` where `contest_id`='{$contest_id}' ORDER BY `votes` DESC") 
		or die("Could not get contest_entries" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$total_contestants = mysqli_num_rows($query);
		//echo 'Total contestants =' .$total;
		//continue here
				
			$output = "<div class='top-ten-votes'> <h3>  Top ten Registered contestants </h3><hr><ul>";
			
			$count = 1;
			while($result = mysqli_fetch_array($query)){
				if($count <= 10){
					$votee = $result['contestant_name'];
					$vote_num = $result['votes'];
					$percent = round(($vote_num / $total_votes) * 100,2);
							
					$output = $output .'<em>'. $vote_num .' Votes </em><br>'.'<span class="tiny-text">'.$percent. ' %</span>'.
					"<progress value='".$vote_num."' min='0' max='".$total_votes."'></progress> <li><a href='" .ADDONS_PATH ."contest/?reg_user=" .$result['contestant_name'] 
					."&contest_name=" .$contest_name ."&contest_id={$contest_id}&action=voting&contest_entry_id={$result['id']}'>" .$result['contestant_name'] ."</a> " ."</li><hr>" ;
					$count++ ;
				}
			} 
			$output = $output . "</ul></div>";
			
			echo $output;
		}	
	
	}
	
}


function get_contest_leader($contest_id=''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT contestant_name, votes FROM `contest_entries` WHERE `contest_id`='{$contest_id}' ORDER by votes DESC LIMIT 0,1") 
	or die("Could not get highest voted contestant" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		$output = array('name'=>"{$result['contestant_name']}", 'votes'=>"{$result['votes']}");
		return $result['contestant_name'];
	}

function show_contest_winner(){
		if($_SESSION['contest_status'] == 'ended'){
			$contest_id = $_SESSION['contest_id'];
		$winner = get_contest_leader($contest_id);
		$_SESSION['contest_winner'] = $winner;
		$winner = show_user_pic($user=$winner,$pic_class='thumbnail center-block',$length='');
		echo '<div class="success"><h2>Winner!!!</h2>'.$winner['picture'] .'</div>';	
		 
		}
	}
	

	
function show_contest_winning_entry_details(){
	if(isset($_SESSION['contest_winner'])){
		$winner = $_SESSION['contest_winner'];
		$contest_id = $_SESSION['contest_id'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `contest_entries` where `contest_id`='{$contest_id}' and contestant_name='{$winner}'") 
		or die("Could not get winning entry details" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		$_SESSION['contest_entry_id'] = $result['id'];
		
		$parent = 'GA-CTST-'.mysql_prep($_GET['contest_name']).'-'.mysql_prep($result['id']);
		$query2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `files` WHERE parent='{$parent}' AND contest_entry_id='{$result['id']}' ORDER BY `id` DESC {$sql_suffix}") 
		or die('Error fetching contest entry photos '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		echo '<div class="row">';
		while($result2 = mysqli_fetch_array($query2)){
			echo '<div class="col-md-6 col-xs-12">';
			 $file ='<span class="inline-block thumbnail">';
			 $file .= "<a href='".$result2['original_path']."' rel='prettyPhoto[".$winner.$contest_id."_gal]'>";
			 $file .='<img src="' .$result2['medium_path'] .'" alt="'.$images_array['name'].'" class=" img-responsive">';
			 $file .= '</a>';
			 if((is_file_owner($file_owner=$images_array['owner']) || is_admin())  && $for_slideshow != 'true'){
				$file .= '<a href="'.$_SESSION['current_url'].'&delete_pic='.$result2['id'].'" class="padding-5 tiny-text pull-right inline-block">delete</a>';
				}
			$file .='</span>';
			echo $file;
			echo '</div>';
			}
		 echo '</div>' ;
		echo "<div class='row'>
		<div class='padding-20 lavender'><h2 class='text-center'>".$result['votes']." votes</h2>"
		  . $result['contest_entry'] ."</div>";
		
			echo '</div> <br> ' ;
			}
			
		}


function show_all_registered_contestants(){
	$contest_id = get_contest_id();
	$contest_name = mysql_prep($_GET['contest_name']);
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, contestant_name, contest_id FROM contest_entries WHERE contest_id='{$contest_id}'");
	
	echo '<h2>All Registered contestants : </h2>';
	while($result =mysqli_fetch_array($query)){
		
		echo '<a href="'.ADDONS_PATH. 'contest/?reg_user='.$result['contestant_name'].'&contest_name='.$contest_name.'&contest_id='.$result['contest_id'].'&action=voting&contest_entry_id='.$result['id'].'">'
		.$result['contestant_name'] .', </a>';
		}
	}


function show_my_current_contests(){
	$viewing = user_being_viewed();
	$user = trim(mysql_prep($viewing));
	$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT contest_id FROM contest_entries WHERE contestant_name='{$user}' ORDER BY id DESC ")
	or die('Problem with new contests ' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	echo '<br><h2>My active contests</h2>';
	if(!empty($num)){
	echo '<a class="u-pull-right" href="'.ADDONS_PATH.'contest">See all [Contests]</a><br>';
	
		while($result=mysqli_fetch_array($query)){ 	
				$id  = $result['contest_id'];
				$query2=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM contest WHERE id='{$id}' AND status='voting in progress' order by id DESC LIMIT 0, 3")
				or die('Problem with new contests ' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

				$result2= mysqli_fetch_array($query2);
				if($result2['status'] == 'voting in progress'){
					echo "<div class='padding-5 margin-3 whitesmoke'>";
					$leader = get_contest_leader($result2['id']);
					if(!empty($leader)){
						 $leading_contestant = '<span class="pull-right tiny-text green-text ">Leading :<br><a href="'.BASE_PATH.'user/?user='.$leader.'">'.$leader.'</a></span>';
						 }
					echo $leading_contestant;
					echo "<a href='" .ADDONS_PATH ."contest/?contest_name=" .str_ireplace(' ','-',$result2['contest_name']) ."&contest=yes&tid=".$result2['id']."'>" 
					. str_ireplace('-',' ',ucfirst($result2['contest_name'])) ."</a><br>" ;
					echo parse_text_for_output(urldecode($result2['description'])) ;
					echo '<br><strong>Reward :</strong><span class="green-text">'.parse_text_for_output(urldecode($result2['reward'])) .'</span>';
					$leading_contestant='';
					echo '</div>';
				}
		   }
	} else {  echo '<em>--not currently in any contest--</em>';  }
	
}


function show_user_contest_entry_page(){
	$user_funds = get_user_funds();
	if(addon_is_active('reward')){
	$user_veto_amount = get_user_veto_power_status();
	}
	$vote_registered_user = trim(mysql_prep($_GET['reg_user'])); 
	$contest_id = mysql_prep($_GET['contest_id']);
	if(isset($_GET['contest_name'])){ $vote_contest = trim(mysql_prep($_GET['contest_name'])); }
			

	//if(isset($_SESSION['username'])){
		if(!empty($_GET['reg_user']) && empty($_POST['user_to_check'])){
			
			if($user_funds >= 5){	
			// do vote if voted
			
			# first get user picture
			$pic = show_user_pic($user = $vote_registered_user,'',$size=70);
			
			# Then get user's contest entry
			$get_entry_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `contest_entries` WHERE `contestant_name`='{$vote_registered_user}' AND contest_id='{$contest_id}'") 
			or die("Could not get entry query") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			
				if($get_entry_query){
				$row = mysqli_fetch_array($get_entry_query);
				$contest_entry = $row['contest_entry'];
				$total_votes = $row['votes'];
				$_SESSION['contest_entry_id'] = $row['id'];
				$_SESSION['contest_entry_owner'] = $row['contestant_name'];
				$_SESSION['no comment_photos'] = 'true';
				$_SESSION['contest_end_date'] = $row['end_date'];
				$destination_url = $row['destination_url'];
				$photos = get_linked_image('','half','',$comment_id=$result['id'],$has_zoom='true');
				
				}
				
				echo "id: GA-CTST-" .strtoupper($_GET['contest_name'])."-{$row['id']} <h2 align='center'><a href='".ADDONS_PATH."contest/?contest_name="
				.$vote_contest."'>".ucfirst(str_ireplace('-',' ',$vote_contest))."</a></h2> ";
				
				echo "<em class=''>Veto power mutiplies votes drastically 
				and gives the contestant a better chance of winning.</em>
				
				<table><thead><th> Contestant </th><th> Contest Entry </th></thead> <tr><td>" 
				."{$vote_registered_user} <br> {$pic['thumbnail']}</td> <td> ";
				
				$if_owner = is_contest_entry_owner;
				upload_no_edit($allow = $if_owner);
				
				if(!empty($photos)){
					echo '<div class="row">';
					foreach ($photos as $photo){
						echo '<div class="col-md-6 col-xs-12">'.$photo .'</div>' ;
						}
					echo '</div> <br> ' . $contest_entry ;
					}
				
				echo "<p></p>" .
				"<form action='http://".$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']."' method='post'>
				<input type='hidden' name='voter' value='".$_SESSION['username']."'>
				<input type='hidden' name='reciever' value='".$_SESSION['username']."'>
				<input type='hidden' name='voting_for' value='".$vote_registered_user."'>
				<input type='hidden' name='add_funds' value='yes'>
				<input type='hidden' name='action' value='vote-contest'>
				<input type='hidden' name='reason' value='Voted for ".$vote_registered_user." in ".$_GET['contest_name']." contest'>
				<input type='hidden' name='amount' value='-5'>
				<div class='btn btn-group'>
				<input type='submit' name='do_vote' value='Support me' class='btn btn-success'>";
				$user_has_veto = get_user_veto_power_status();
				
				if(!empty($user_has_veto) && $user_has_veto >= 10){
				echo "<input type='submit' name='do_vote_veto_10' value='Use 10 Veto' class='btn btn-primary '>";
				}
				if(!empty($user_has_veto) && $user_has_veto >= 20){
				echo "<input type='submit' name='do_vote_veto_20' value='Use 20 Veto' class='btn btn-primary'>";
				}
				if(!empty($user_has_veto)){
				echo "<input type='submit' name='do_vote_veto_all' value='Use All My Veto' class='btn btn-primary'>";
				}
				echo"</div>
				<br><span class='total-votes'>Total votes : {$total_votes}</span>
				</form>" .
				"</td></tr></table>";
				
				if(has_voted()){
					echo '<div class="col-md-12">';
					add_comment($subject = 'contest entry',
					$reply='Comments', 
					$placeholder="Comment on this",
					$button_text="Post this",
					$upload_allowed='false');
					echo '</div>';
					}
				
			} else { 
			status_message('error', 'You do not have sufficient funds to vote! <a href="'.BASE_PATH.'funds_manager/?action=load_top_up">Fund your account</a>');
			echo"<h2 align='center'><a href='".ADDONS_PATH."contest/?contest_name="
				.$vote_contest."'>Return to - ".str_ireplace('-',' ',$vote_contest)."</a></h2> ";
				 }// end user funds check
		} 
	//} else {deny_access();}
}
	

function contest_do_vote(
	
			$entity='user',
			$voter='',
			$votee='',
			$vote_value='1',
			$label='vote',
			$funds_value=5,
			$parent='contest'){
				
	//print_r($_POST);
	if(isset($_SESSION['username'])){	
		
		// if user clicked "Support me"
	if($_POST['do_vote'] === 'Support me'){
			
			$contest_id = $_SESSION['contest_id'];
			$page_name = $_SESSION['page_name'];
			if($_GET['action'] === 'voting'){
			$subject_name = trim(mysql_prep($_GET['contest_name']));
			} else if (isset($_GET['poll_name'])){
			$subject_name = trim(mysql_prep($_GET['poll_name']));	
			}
			$date = date('c');

			
	if(isset($_POST['voter'])){
		$voter = $_POST['voter'];
		} else {
			$voter = $_SESSION['username'];
			}
			
	if(isset($_POST['voting_for'])){
		$voting_for = $_POST['voting_for'];
	} else if(isset($_GET['voting_for'])){
		$voting_for = $_GET['voting_for'];
	} else if(isset($_GET['reg_user'])){
	$voting_for = trim(mysql_prep($_GET['reg_user']));
	}
	
	//Get current vote count
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `votes` FROM `contest_entries` WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$result = mysqli_fetch_array($query);
	$total_votes = $result['votes'];
	$new_total = $total_votes + 1;
	$_SESSION['user_votes'] = $new_total;
	
	# save the vote
	
		$vote_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest_entries` SET `votes`='{$new_total}'  
		WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
		or die("Could not save complete vote query" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($vote_query){
			add_to_contest_revenue($amount=5);
			echo "<div class='success'> You have just ".$label ."d ".$voting_for ."  -- thanks!</div>";
			
			# Save the Vote
			$voter_uid = $_SESSION['user_id'];
			$contest_entry_id = $_GET['contest_entry_id'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO contest_voters(id,voter_uid,contest_entry_id) 
			VALUES('0','{$voter_uid}','{$contest_entry_id}')") 
			or die('Error saving contest voter ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			# Update total votes
			$result = get_total_votes();

				$total_value = $result + 1;
				//echo $total_value; //testing purposes

			$update_total_votes_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest` SET `total_votes`='{$total_value}' WHERE `id`='{$contest_id}'") 
			or die("Failed to save new total votes" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			#  SUBTRACT FUNDS PER VOTE
			
			transfer_funds('subtract',$amount='',$giver,$reciever=$_SESSION['username'],$reason);
			$goodwill = calculate_goodwill();
			$goodwill_total = $goodwill + 5;
			set_user_goodwill($user=$_SESSION['username'],$goodwill= $goodwill_total);
			}
		}
		
		
		
		/** NOTe i chose to use this lenghty repititive code rather than a concise select list 
		* which sends the amount so that the voting form will be more intuitive and require
		* less clicks by the user. */
		
		
		
			// Else if user clicked "Use 10 Veto"
	if($_POST['do_vote_veto_10'] === 'Use 10 Veto'){
			
			$contest_id = $_SESSION['contest_id'];
			$page_name = $_SESSION['page_name'];
			if($_GET['action'] === 'voting'){
			$subject_name = trim(mysql_prep($_GET['contest_name']));
			} else if (isset($_GET['poll_name'])){
			$subject_name = trim(mysql_prep($_GET['poll_name']));	
			}
			$date = date('c');

			
	if(isset($_POST['voter'])){
		$voter = $_POST['voter'];
		} else {
			$voter = $_SESSION['username'];
			}
			
	if(isset($_POST['voting_for'])){
		$voting_for = $_POST['voting_for'];
	} else if(isset($_GET['voting_for'])){
		$voting_for = $_GET['voting_for'];
	} else if(isset($_GET['reg_user'])){
	$voting_for = trim(mysql_prep($_GET['reg_user']));
	}
	
	//Get user veto amount
	$voting_user_veto = get_user_veto_power_status();
	//Number of veto voting user is applying
	$num_veto_applied = 10;
	//check it user veto is sufficient for this action
	if($voting_user_veto < $num_veto_applied){
		session_message('error','You do not have up to 10 veto power');
		$_POST='';
		//die();
		redirect_to($_SESSION['prev_url']);
		}
	//converting Veto to votes
	$veto_vote_equivalent = 1 + (($num_veto_applied / 2) / 10);
	
	
	//Get current vote count
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `votes` FROM `contest_entries` WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$result = mysqli_fetch_array($query);
	$total_votes = $result['votes'];
	
	
	$new_total = $total_votes * $veto_vote_equivalent;
	$_SESSION['user_votes'] = $new_total;
	
	# save the vote
	
		$vote_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest_entries` SET `votes`='{$new_total}' 
		WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
		or die("Could not save complete vote query" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($vote_query){
			echo "<div class='success'> You have just ".$label ."d ".$voting_for ."  -- thanks!</div>";
			
			# Update total votes
			$result = get_total_votes();

				$total_value = $new_total;
				//echo $total_value; //testing purposes

			$update_total_votes_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest` SET `total_votes`='{$total_value}' WHERE `id`='{$contest_id}'") 
			or die("Failed to save new total votes" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			# UPDATE VETO AMOUNT
			update_veto_power('subtract',10,'');
			
			#  SUBTRACT FUNDS PER VOTE
			
			transfer_funds('subtract',$amount='',$giver,$reciever=$_SESSION['username'],$reason);
			$goodwill = calculate_goodwill();
			$goodwill_total = $goodwill + 5;
			set_user_goodwill($user=$_SESSION['username'],$goodwill= $goodwill_total);
			}
		}
		$_POST['do_vote']='';
		
		
		
		
		
		
		
		
			
	// Else if user clicked "Use 20 Veto"
	if($_POST['do_vote_veto_20'] === 'Use 20 Veto'){
			
			$contest_id = $_SESSION['contest_id'];
			$page_name = $_SESSION['page_name'];
			if($_GET['action'] === 'voting'){
			$subject_name = trim(mysql_prep($_GET['contest_name']));
			} else if (isset($_GET['poll_name'])){
			$subject_name = trim(mysql_prep($_GET['poll_name']));	
			}
			$date = date('c');

			
	if(isset($_POST['voter'])){
		$voter = $_POST['voter'];
		} else {
			$voter = $_SESSION['username'];
			}
			
	if(isset($_POST['voting_for'])){
		$voting_for = $_POST['voting_for'];
	} else if(isset($_GET['voting_for'])){
		$voting_for = $_GET['voting_for'];
	} else if(isset($_GET['reg_user'])){
	$voting_for = trim(mysql_prep($_GET['reg_user']));
	}
	
	//Get user veto amount
	$voting_user_veto = get_user_veto_power_status();
	//Number of veto voting user is applying
	$num_veto_applied = 20;
	//check it user veto is sufficient for this action
	if($voting_user_veto < $num_veto_applied){
		session_message('error','You do not have up to 20 veto power');
		$_POST='';
		//die();
		redirect_to($_SESSION['prev_url']);
		}
	//converting Veto to votes
	$veto_vote_equivalent = 1 + (($num_veto_applied / 2) / 10);
	
	
	//Get current vote count
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `votes` FROM `contest_entries` WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$result = mysqli_fetch_array($query);
	$total_votes = $result['votes'];
	
	
	$new_total = $total_votes * $veto_vote_equivalent;
	$_SESSION['user_votes'] = $new_total;
	
	# save the vote
	
		$vote_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest_entries` SET `votes`='{$new_total}' 
		WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
		or die("Could not save complete vote query" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($vote_query){
			echo "<div class='success'> You have just ".$label ."d ".$voting_for ."  -- thanks!</div>";
			
			# Update total votes
			$result = get_total_votes();

				$total_value = $new_total;
				//echo $total_value; //testing purposes

			$update_total_votes_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest` SET `total_votes`='{$total_value}' WHERE `id`='{$contest_id}'") 
			or die("Failed to save new total votes" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			# UPDATE VETO AMOUNT
			update_veto_power('subtract',20,'');
			
			#  SUBTRACT FUNDS PER VOTE
			
			transfer_funds('subtract',$amount='',$giver,$reciever=$_SESSION['username'],$reason);
			$goodwill = calculate_goodwill();
			$goodwill_total = $goodwill + 5;
			set_user_goodwill($user=$_SESSION['username'],$goodwill= $goodwill_total);
			}
		}
		$_POST['do_vote']='';
	
	
	
		
		
		
		
		
		
		
			
	// Else if user clicked "Use All My Veto"
	if($_POST['do_vote_veto_all'] === 'Use All My Veto'){
			
			$contest_id = $_SESSION['contest_id'];
			$page_name = $_SESSION['page_name'];
			if($_GET['action'] === 'voting'){
			$subject_name = trim(mysql_prep($_GET['contest_name']));
			} else if (isset($_GET['poll_name'])){
			$subject_name = trim(mysql_prep($_GET['poll_name']));	
			}
			$date = date('c');

			
	if(isset($_POST['voter'])){
		$voter = $_POST['voter'];
		} else {
			$voter = $_SESSION['username'];
			}
			
	if(isset($_POST['voting_for'])){
		$voting_for = $_POST['voting_for'];
	} else if(isset($_GET['voting_for'])){
		$voting_for = $_GET['voting_for'];
	} else if(isset($_GET['reg_user'])){
	$voting_for = trim(mysql_prep($_GET['reg_user']));
	}
	
	//Get user veto amount
	$voting_user_veto = get_user_veto_power_status();
	//Number of veto voting user is applying
	$num_veto_applied = $voting_user_veto;
	//check it user veto is sufficient for this action
	if(empty($voting_user_veto)){
		session_message('error','You do not have any veto power! <');
		redirect_to($_SESSION['prev_url']);
		}
	
	
	//converting Veto to votes
	$veto_vote_equivalent = 1 + (($num_veto_applied / 2) / 10);
	
	
	//Get current vote count
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `votes` FROM `contest_entries` WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$result = mysqli_fetch_array($query);
	$total_votes = $result['votes'];
	
	
	$new_total = $total_votes * $veto_vote_equivalent;
	$_SESSION['user_votes'] = $new_total;
	
	# save the vote
	
		$vote_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest_entries` SET `votes`='{$new_total}' 
		WHERE `contest_id`='{$contest_id}' AND `contestant_name`='{$voting_for}'") 
		or die("Could not save complete vote query" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($vote_query){
			echo "<div class='success'> You have just ".$label ."d ".$voting_for ."  -- thanks!</div>";
			
			# Update total votes
			$result = get_total_votes();

				$total_value = $new_total;
				//echo $total_value; //testing purposes

			$update_total_votes_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest` SET `total_votes`='{$total_value}' WHERE `id`='{$contest_id}'") 
			or die("Failed to save new total votes" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			
			# UPDATE VETO AMOUNT
			update_veto_power('subtract',$amount=$voting_user_veto,'');
			
			
			#  SUBTRACT FUNDS PER VOTE
			
			transfer_funds('subtract',$amount='',$giver,$reciever=$_SESSION['username'],$reason);
			$goodwill = calculate_goodwill();
			$goodwill_total = $goodwill + 5;
			set_user_goodwill($user=$_SESSION['username'],$goodwill= $goodwill_total);
			}
		}
		$_POST['do_vote']='';
	
		
	}

}


function process_contest_submission(){

# Add pages form processing
$id = $_POST['id']; 
$contest_name = str_ireplace(' ','-',trim(mysql_prep(strtolower($_POST['contest_name'])))) ;
if(isset($_POST['page_name'])){
$contest_name = str_ireplace(' ','-',trim(mysql_prep(strtolower($_POST['page_name'])))) ;	
	}
$action = mysql_prep($_POST['action']);
$updated = mysql_prep($_POST['updated']);
$submitted = trim(mysql_prep($_POST['submitted']));
$content1 = trim(mysql_prep($_POST['description']));
$content =trim(mysql_prep($_POST['content']));
$description = $content1;
$duration = trim(mysql_prep($_POST['duration']));
$reward = mysql_prep($_POST['reward']);
if(!empty($_POST['reg_amount'])){
$reg_amount = mysql_prep($_POST['reg_amount']);
} else {
	$reg_amount = '0';
	}
$position = htmlentities($_POST['position']);
$section_name = trim(mysql_prep($_POST['section']));
if(isset($_POST['category'])){
$category = trim(mysql_prep($_POST['category']));
}
$deleter = $_GET['action'];
$sent_delete = $_GET['deleted'];
$parent = $page_type;
$menu_type = $_POST['menu_type'];

$author = $_SESSION['username'];
$editor = $_SESSION['username'];
$back_url = $_POST['back_url'];
$created = date('c');
$last_updated = date('c');


$end_date = trim(mysql_prep($_POST['end_date']));
$add_page_type = $_POST['add_page_type'];
$delete_page_type = $_POST['delete_page_type'];
$page_type = $_POST['page_type'];
if(isset($_POST['allow_comments'])){
$allow_comments = 'yes';
} 
if(isset($_POST['promote'])){
$promote_on_homepage = 'yes';
} else { $promote_on_homepage = 'no'; }


$destination = BASE_PATH."addons/contest/?contest_name={$contest_name}&contest=yes";	

//calculate end date
		
		$now = date('l jS F');
		$next_day = date('l jS F', strtotime('+1 days'));
		$next_3_days = date('l jS F', strtotime('+3 days'));
		$one_week = date('l jS F', strtotime('+7 days'));
		$one_month = date('l jS F', strtotime('+4 weeks'));
		$duration = mysql_prep($_POST['duration']);

		if($duration == '1 day'){
			$end_date = $next_day;
		}elseif($duration == '3 days'){				
			$end_date = $next_3_days;
		}elseif($duration == '1 week'){
			$end_date = $one_week;
		}elseif($duration == '1 month'){
			$end_date = $one_month;
		}
	


if (isset($submitted) && $action ==='insert'){	
	if(isset($reward)){
		$contest_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `contest` (`id`, `contest_name`, `description`, `reward`, `author`, `editor`, `total_votes`, `created`, `last_updated`, `duration`, `reg_amount`, `end_date`,`status`,`revenue`,`category`) 
		VALUES ('0','{$contest_name}','{$description}','{$reward}','{$author}','{$editor}','0','{$created}','{$edited}','{$duration}','{$reg_amount}','{$end_date}','inactive','0','')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		
	 if($contest_query) {
		 	 
		  activity_record(
					$actor=$author,
					$action=" created the contest ",
					$subject_name = $contest_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH .'contest?contest_name=' .$contest_name,
					$date=$created,
					$parent='contest'
					);
					
		session_message('success', "Contest saved successfully!");
		
		
		}
	if($menu_type !== 'none' && !empty($menu_type)){
	$insert_menu = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `menus`(`id`, `menu_item_name`, `menu_type`, `position`, `visible`, `destination`, `parent`) 
	VALUES ('0','{$contest_name}', '{$menu_type}', '{$position}', '{$visible}', '{$destination}', '{$parent}')")	
	or die("FAiLEd to insert menu item!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}

	redirect_to($destination);
}	

# Edit form processing

if($updated ==='Save contest'){
		
		//echo $end_date; die();
		if(empty($description)){
			$description = '<em>empty description</em>';
			}
		 $update_contest_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest` SET `description`='{$description}',`reward`='{$reward}',`editor`='{$editor}',`last_updated`='{$last_updated}',`duration`='{$duration}',`start_date`='{$start_date}',`end_date`='{$end_date}' WHERE id='{$id}'") 
		 or die("Error Updating contest " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 
		 
	 if($update_contest_query) {
		 	 	 
		  activity_record(
					$actor=$author,
					$action=" updated the contest",
					$subject_name = $contest_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH .'contest?contest_name=' .$contest_name,
					$date=$created,
					$parent='contest'
					); 
		session_message('success', 'Contest saved successfully!');		
		
	 }
	 
	
		redirect_to($destination);
}

  // Now we check if delete is requested  
if(isset($deleter) && is_author() && $sent_delete ==='jfldjff7'){
	
	$del_contest_name= $_GET['contest_name'];
	$destination = ADDONS_PATH.'contest';
	#echo " id is " . $del_page_name . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from contest WHERE contest_name='{$del_contest_name}'") 
	or die('<div class="alert">Could not delete the specified contest!</div>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
} else if(isset($_POST['deleted'])){
		
	$del_contest_name= $contest_name;
	$destination = ADDONS_PATH.'contest';
	#echo " id is " . $del_contest_name . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from contest WHERE contest_name='{$del_contest_name}'") 
	or die('<div class="alert">Could not delete the specified contest!</div>') . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	
	
	
}
	
	if($delete_query) {
		session_message('success', "Contest '" .$del_contest_name ."' deleted successfully!!");
		
	}
	$delete_menu_query=mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$del_page_name}'") 
	or die("Menu item deletion failed1" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	redirect_to($destination);

}
 // end of contest functions file
 // in root/contest/includes/functions.php
?>
