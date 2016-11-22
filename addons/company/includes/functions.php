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
require_once($r .'/includes/resize_class.php'); 
//print_r($_POST);
//print_r($_SESSION);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function add_company_link(){
	if(is_logged_in()){
		echo "<div class='gainsboro'>
			<a href='".ADDONS_PATH."company?add_type=company'><h3> +Add Company Page </h3>
			Add Company profile where people can interact with your company or business.</div> </a><hr>";
		
		}
}

function add_company_projects(){
	if(addon_is_active('project_manager') && $_GET['action']=='show'){
		
	$company= get_company_page();
		if($_SESSION['username'] == $company['creator']){
		echo '<a href="'.ADDONS_PATH.'project_manager/?action=add&type=project&parent=company&tid='.$company['id'].'">
				<button class="pull-right btn-primary">+Add project</button>
			</a><br>';
		}
	}
}

function add_company_jobs(){
	if(addon_is_active('jobs') && is_logged_in() && $_GET['action']=='show'){
	$company= get_company_page();
		if($_SESSION['username'] == $company['creator']){	
		echo'<span class="col-md-12 pull-right"><a href="'.ADDONS_PATH.'jobs?action=add_job&parent=company&tid='.$company['id'].'"><button class="pull-right btn-primary">+Add Job</button></a></span>';
		}
	}
}

function add_company_team_member_link(){
	if( is_logged_in() && $_GET['action']=='show'){
	$company= get_company_page();
		if($_SESSION['username'] == $company['creator']){	
		echo'<span class=" pull-right"><a href="'.ADDONS_PATH.'company?action=add_company_team_member&parent=company&tid='.$company['id'].'"><button class="btn-primary">+Add Team member</button></a></span>';
		}
	}
}

function show_company_admin_links(){
	echo '<a href="'.ADDONS_PATH.'company?add_type=company">Edit Company Page </a>';
	}

function add_company_profile(){
	if($_POST['submit'] == 'Save company details' && is_logged_in() ){
	
		$company_name = trim(mysql_prep($_POST['company_name']));
		$company_url = trim(mysql_prep($_POST['company_url']));
		$about = trim(mysql_prep($_POST['about']));
		$logo = trim(mysql_prep($_POST['logo']));
		$background = trim(mysql_prep($_POST['background']));
		$creator = $_SESSION['username'];
		$path = ADDONS_PATH.'company?company_name='.$company_name;
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT IGNORE INTO company(`id`, `company_name`, `about`, `logo`, `background`, `creator`, `path`,`company_url`) 
		VALUES ('','{$company_name}','{$about}','','','{$creator}','{$path}','{$company_url}')") 
		or die('Problem saving company profile '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message('success','Company profile saved');
			link_to(ADDONS_PATH.'company','Go to companies page', 'text-center', 'link','');
			}
}		
	if($_GET['add_type'] == 'company'){
	go_back();
	
	echo '<div class="page_content edit-form ">
	
	<form method="post" action="'.$_SESSION['current_url'].'" class="padding-10">
	<h1> Add Company profile</h1>
	<input type="text" name="company_name" placeholder="Company name">
	<textarea name="about" placeholder="About Company"></textarea>
	<input type="text" name="company_url" placeholder="Website / external webpage link">
	<input type="text" name="logo" placeholder="LOGO "><br><em> Paste link to any picture online</em><br>
	<input type="text" name="background" placeholder="Background "><br><em> Paste link to any picture online</em><br>
	<input type="submit" name="submit" value="Save company details">
	</form>
	</div>';
	}
	
}

function edit_company_profile(){
	$company_name = trim(mysql_prep($_POST['company_name']));
	$company_url = trim(mysql_prep($_POST['company_url']));
	$about = trim(mysql_prep($_POST['about']));
	$logo = trim(mysql_prep($_POST['logo']));
	$background = trim(mysql_prep($_POST['background']));
	$id = mysql_prep($_GET['tid']);
	
	if($_GET['action'] == 'edit_company' && is_logged_in() ){
		if($_POST['submit'] == 'Edit company details' && is_logged_in() && $_POST['control'] == $_SESSION['control']){
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE company SET `company_name`='{$company_name}', `about`='{$about}', 
		`logo`='{$logo}', `background`='{$background}', `company_url`='{$company_url}' WHERE id='{$id}'") 
		or die('Problem updating company profile '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message('success','Company profile saved');
			link_to(ADDONS_PATH.'company','Companies','text-center','link','');
			//redirect_to($_SESSION['current_url']);
			} 
		}
		
	$result = get_company_page();
	$_SESSION['company_creator'] = $result['creator'];
	$_SESSION['company_id'] = $result['id'];
	$_SESSION['company_name'] = $result['company_name'];
	
	
	if($_POST['delete_company'] == 'delete company' && is_logged_in() ){
		delete_company_profile();
		}
	
	echo '<h1> Edit Company profile</h1>
	<form method="post" action="'.$_SESSION['current_url'].'">
	<input type="hidden" name="action" value="edit_company">
	<input type="hidden" name="control" value="'.$_SESSION['control'].'">
	<input type="text" name="company_name" value="'.$result['company_name'].'">
	<textarea name="about" placeholder="about this company" size="7">'.$result['about'].'</textarea><br>
	Company website/page :<input type="text" name="company_url" value="'.$result['company_url'].'" placeholder="Website / external webpage link">
	<br>Logo : <input type="text" name="logo" value="'.$result['logo'].'"><br>
	Background :<input type="text" name="background" value="'.$result['background'].'"><br><br>
	<input type="submit" name="submit" value="Edit company details">
	<input type="submit" name="delete_company" value="delete company">
	</form>';
	
	
	} 
}
	
function delete_company_profile(){
	
	if((is_logged_in() & $_SESSION['company_creator'] == $_SESSION['username']) || is_admin()){
		if(isset($_POST['delete_company'])){
		$id = $_SESSION['company_id'];
		$user = $_SESSION['username'];
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM company WHERE id='{$id}' AND creator='{$user}'");
		
			if($query){
				status_message('alert','Company profile deleted');
				}
			}
		}
	
	}
	
function change_company_logo() {
	if(isset($_GET['company_name']) && is_logged_in() && $_GET['action'] == 'edit_company' && (!isset($_POST['search_company']))){
		
	#$folder should end in a forward slash eg $folder = 'user/'
	global $r;
	
	if(isset($_GET['company_name'])){
		$name = trim(mysql_prep($_GET['company_name'])) .'_logo.jpg' ;
		}
	if(isset($_GET['tid'])){
		$id = mysql_prep($_GET['tid']);
		}

$submit =  $_POST['submit'];

$uploaddir = $r.'addons/company/files/logo/';
$uploadfile = $uploaddir . $name;
$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
$uploadfile = $m;

$path = ADDONS_PATH.'company/files/'. $name;
$smpath = ADDONS_PATH.'company/files/logo/'. $name;
$m = str_ireplace('/regions/','',$path);
$path = $m;
$rpath = $r.'addons/company/files/logo/'. $name;
$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
$rpath= $m;

	# ONSUBMIT
	if ($submit==='upload' && !empty($_FILES)){

   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
  
if(isset($_GET['tid'])){
	$parent_id = mysql_prep($_GET['tid']);
	}
	
   if($_FILES['logo']['size'] > 10000){
	   unlink($uploadfile);
   $move = move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile);

	if($move ==1){
		
		$small_path = resize_logo($pic=$uploadfile);
		$logo = $smpath ;
		} 
		
		} else {
			   $move = move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile);
				$resizeObj = new resize($uploadfile);

				// *** 3) Save image ('image-name', 'quality [int]')

				$resizeObj -> saveImage($uploadfile, 90);
				
			$logo = $smpath ; 
			$logo = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $logo) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));}
		
		//echo "<div class='message-notification'>File is valid, and was successfully uploaded.\n</div>";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `company` SET logo='{$logo}' WHERE id='{$id}'") 
		or die("Could not save company logo to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$_SESSION['last_upload'] = $name;
		
		//if($query) { echo "Succesfully saved to DB!";} testing
		//unset($_FILES['logo']);
		//redirect_to($_SESSION['current_url']);
	
}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing
	
	
# UPLOAD FORM
	echo '<h3> Upload logo and Background </h3><form action="http://'
	.$_SERVER["HTTP_HOST"] .$_SERVER["REQUEST_URI"] .'" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
    <b>Logo image (optional)</b>
	<input type="file" size="500" name="logo" value="">
	<br>
	<b>Background image(optional)</b>
	<input type="file" size="500" name="background" value="">
	<input type="submit" name="submit" value="upload">
	<br><em>For best results, a background picture 
	with height of 150px is recommended.</em>
	</form>';
	
	$company = get_company_page($id);
	echo '<b>Logo</b><br><img src="'.$company['logo'].'?nocache='.time().'" width="50" height="50"><p>';
	echo '<b>Background</b><br><img src="'.$company['background'].'?nocache='.time().'" width="50" height="50"><p>';
}
	
}
	
function change_company_background() {
	if(isset($_GET['company_name']) && $_GET['action'] == 'edit_company' && (!isset($_POST['search_company']))){
		
	#$folder should end in a forward slash eg $folder = 'user/'
	global $r;
	
	if(isset($_GET['company_name'])){
		$name = trim(mysql_prep($_GET['company_name'])) .'_background.jpg' ;
		}
	if(isset($_GET['tid'])){
		$id = mysql_prep($_GET['tid']);
		}

	if($r==='' && !url_contains('edit_')){
		$r = dirname(__FILE__);
		$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
		}
$submit =  $_POST['submit'];

$uploaddir = $r.'addons/company/files/background/';
$uploadfile = $uploaddir . $name;
$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
$uploadfile = $m;

$path = ADDONS_PATH.'company/files/'. $name;
$bgpath = ADDONS_PATH.'company/files/background/'. $name;
$m = str_ireplace('/regions/','',$path);
$path = $m;
$rpath = $r.'addons/company/files/background/'. $name;
$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
$rpath= $m;
	//print_r($_FILES);
	# ONSUBMIT
	if ($submit==='upload' && !empty($_FILES['background']['name'])){
		
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));


   $move = move_uploaded_file($_FILES['background']['tmp_name'], $uploadfile);

		if($move == 1){
			echo 'A nom ya!';
			$background = $bgpath ;
			$background = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $background) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			} 
		
	//echo "<div class='message-notification'>File is valid, and was successfully uploaded.\n</div>";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `company` SET background='{$background}' WHERE id='{$id}'") 
	or die("Could not save company background to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$_SESSION['last_upload'] = $name;
	
	//if($query) { echo "Succesfully saved to DB!";} testing
	unset($_FILES['background']);
	session_message('success','File uploaded successfully');
	redirect_to($_SESSION['current_url']);
}
	
}
}

function show_company_banner_and_logo(){
	
		if(isset($_GET['company_name']) && $_GET['action'] == 'show' && (!isset($_POST['search_company']))){
		$company = get_company_page();
		echo '<div class="company-background"><img class="center-block img-responsive" width="100%" height="150px" src="'.$company['background'].'?nocache='.time().'">';
		echo '<a href="'.$ADDONS_PATH.'?company_name='.$company['company_name'].'&action=show&tid='.$company['id'].'"><img class="company-logo" width="100px" height="100px" src="'.$company['logo'].'?nocache='.time().'"></a>
		<div class="company-name">'.$company['company_name'];
		if(is_admin() || (is_logged_in && $_SESSION['username'] == $company['creator'])){
		echo '<a href="'.ADDONS_PATH.'company?company_name='.$company['company_name'].'&action=edit_company&tid='.$company['id'].'" class="tiny-edit-text orangered padding-5"> &nbsp;edit</a>';
		}

		echo '</div>
		</div><p></p>';

		//echo $company['logo'];
		}

	}	
	
function resize_logo($pic='',$option='exact'){
	global $r;
	$width=70; 
	$height=70;
	$dest_folder= $r.'addons/company/files/logo/'. $_GET['company_name'].'_logo.jpg';
	$m = str_ireplace('regions/','',$dest_folder); // fixes a bugin upload_no_edit()
	$dest_folder = $m;
	$output = BASE_PATH.'addons/company/files/logo/';
	/**$folder is the folder name, eg thumbnail, medium etc
	 * $option is one of : exact, portrait, landscape, auto, crop
	 * */
	
	
	// USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 100);

return $output;
	
}

function get_company_discussions(){
	
	
	}

function add_company_team_member(){
	
	if($_POST['submit'] == 'Save team member' 
	&& ( is_admin() || $_SESSION['company_creator'] == $_SESSION['username'])
	&& $_SESSION['control'] == $_POST['control'] ){
		
		$company_id = mysql_prep($_GET['tid']);
		$team_member_name = trim(mysql_prep($_POST['team_member_name']));
		$team_member_title = trim(mysql_prep($_POST['team_member_title']));
		$team_member_job_description = trim(mysql_prep($_POST['team_member_job_description']));
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `company_team`(`id`, `company_id`, `team_member_name`, `team_member_title`, `team_member_job_description`) 
		VALUES ('0','{$company_id}','{$team_member_name}','{$team_member_title}','{$team_member_job_description}')") 
		or die ('error saving team member'.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message('success','Team member added successfully!');
			}
		}
	
	if($_GET['action'] == 'add_company_team_member'){
	
	echo '<h1> Add "<a href="'.ADDONS_PATH.'company?company_name='.$_SESSION['company_name'].'&action=show&tid='
	.$_GET['tid'].'">'.$_SESSION['company_name'].'</a>" team member</h1>
	<form method="post" action="'.$_SESSION['current_url'].'">
	<input type="hidden" name="control" value="'.$_SESSION['control'].'">
	<input type="text" name="team_member_name" placeholder="Team member username">
	<input type="text" name="team_member_title" placeholder="Job Title">
	<textarea name="team_member_job_description" placeholder="Job Description"></textarea>
	<input type="submit" name="submit" value="Save team member">
	<input type="submit" name="delete_team_member" value="delete team member">
	</form>';
	}
	
}

function delete_company_team_member(){
	
		if((is_logged_in() & $_SESSION['company_creator'] == $_SESSION['username']) || is_admin()){
		if($_POST['delete_team_member'] == 'delete team member'){
		$id = $_SESSION['company_id'];
		$team_member_name = trim(mysql_prep($_POST['team_member_name']));
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM company_team WHERE company_id='{$id}' AND team_member_name='{$user}'");		
			if($query){
				status_message('alert','Team member deleted');
				}
			}
		}
	}
	
function show_company_team(){
	$company_id = $_SESSION['company_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM company_team WHERE company_id='{$company_id}'") 
	or die("Error fetching company team " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$team =array();
	$num = mysqli_num_rows($query);
	
				
	if($_GET['action']=='show'){
		while($result = mysqli_fetch_array($query)){
		$user = $result['team_member_name'];
		$pic = show_user_pic($user,'circle-pic ',50);
		$team[] = $pic['thumbnail'];
				}
		echo '<div class="right-sidebar-region">';
		echo '<h2>Team members</h2>';
		add_company_team_member_link();
		
		if(empty($num)){
		echo '<em class="text-center">No members added yet!</em>';
		} else {
		echo '<div class="pull-right inline-block"><a href="'.ADDONS_PATH.'company?action=show-team-page&tid='.$company_id.'">See full team details</a></div><p>';
		}
		echo '<div class="inline-block">';
		foreach($team as $member){
			echo '<span class="inline padding-5">'.$member.'</span>';
			}
		echo '</div>';
		echo '</div>';
	}
	if($_GET['action']== 'show-team-page'){
		echo '<div class="main-content-region">';
		echo '<h2>'.$_SESSION['company_name'].' Team</h2>';
		echo '<a href="'.ADDONS_PATH.'company?company_name='.$_SESSION['company_name'].'&action=show&tid='.$company_id.'" class="center-block text-center">Return to '.$_SESSION['company_name'].'</a>';
			
		
		while($result = mysqli_fetch_array($query)){
		$user = $result['team_member_name'];
		$pic = show_user_pic($user,'circle-pic inline ',100);
		
		echo '<span class="col-md-5 col-xs-12 whitesmoke inline-block padding-10 margin-10">';
		echo '<span class="pull-left">'.$pic['picture'].'</span>';
		
		echo '<span class="text-center"><h3><a href="'.BASE_PATH.'user?user='.$user.'">'.ucfirst($result['team_member_name']).'</a>&nbsp:&nbsp;'.$result['team_member_title'].'</h3></span>';
		echo '<span class="padding-20 text-center">Job Description: '.$result['team_member_job_description'] .'</span>';
		
		echo '</span>';
				}
		
		echo '</div>';
		}
	
	}
	
function get_company_page(){
	
		$id = trim(mysql_prep($_GET['tid']));
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM company WHERE id='{$id}'") 
		or die('Could not fetch company details'. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		//$num = mysql_num_rows($query);echo 'i dey here' .$num;
		while($result=mysqli_fetch_array($query)){
			$output = [
			'id' => $result['id'],
			'company_name' => $result['company_name'],
			'about' => $result['about'],
			'logo' => $result['logo'],
			'background' => $result['background'],
			'creator' => $result['creator'],
			'company_url' => $result['company_url'],
			];
			
			}
		return $output;

	}
	
function get_company_projects(){
	$query = $_SERVER['QUERY_STRING'];
	$parent_id =mysql_prep($_GET['tid']);
	$company = mysql_prep($_GET['company_name']);
	
	if(($_GET['action']==='show') && (isset($_GET['company_name']))){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_project` WHERE parent='company' AND parent_id='{$parent_id}' ORDER BY `id` DESC LIMIT 30")
		or die ("! something is wrong with project lists" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$list = "<h3>{$company} Projects list</h3><hr><ol>";
    $num = mysqli_num_rows($query);
    
	echo '<p></p><hr>';	
	add_company_projects();
	while($result = mysqli_fetch_array($query)){
		// decorate based on status
		if($result['status'] === 'completed'){
			$pattern = 'pm-completed';
			} 
			elseif($result['status'] === 'started'){
			$pattern = 'pm-started';	
			}
			else {
			$pattern = 'pm-pending';	
				}
				
			$incomplete = has_incomplete_tasks($result['project_name']);
			if(!empty($incomplete['count'])){
				$count_num = "<em class='u-pull-right'><small>Has <strong>{$incomplete['count']}</strong> incomplete tasks</small></em>";
				}else {$count_num = "<em class='u-pull-right'><small>No pending tasks</small></em>";}
		$list = $list . "<li class='{$pattern}'><big> <a href='" .ADDONS_PATH 
		."project_manager/?action=show&project_name=" 
		.$result['project_name']  ."'>"
		.ucfirst($result['project_name']) 
		."</a></big>";
		if(is_author() || is_admin() || is_project_manager()){
				$list = $list .
				"&nbsp&nbsp {$count_num} </li><hr>";
				}
		
		} $list = $list . "</ol><br>";
		echo $list;
    if(empty($num)){
      echo '<em>There are no projects yet!';
      if(is_logged_in()){
       echo '<a href="'.ADDONS_PATH.'project_manager/?action=add&type=project">create one</a></em>';
       }
      }
	}
	
	
	}
	
function get_company_jobs(){
	if( isset($_GET['company_name']) && $_GET['action'] == 'show'){
	$query_string = $_SERVER['QUERY_STRING'];
	show_jobs_color_codes();
	$parent_id = mysql_prep($_GET['tid']);
	$company_name = mysql_prep($_GET['company_name']);
	
	echo '<div class="text-center"><strong>Color codes:</strong> <span class="code-pending">pending</span>'; 
		echo '<span class="code-started">started</span>'; 
		//echo '<span class="code-submitted">bid accepted</span>';
		echo '<span class="code-completed">completed</span>';
		//echo '<span class="code-disapproved">disapproved</span>';
		echo'  </div><br>';
	
	if(isset($_GET['company_name'])){
	$pager = pagerize();
	$limit = $_SESSION['pager_limit'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `jobs`WHERE parent='company' AND parent_id='{$parent_id}' ORDER BY `id` DESC {$limit}");
	
	echo '<div class="col-md-12 whitesmoke padding-10">
	<h3>'.$company_name.' Jobs</h3><hr><ul>';
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
	}
	
	
function is_company_owner(){
		if($_SESSION['company_creator'] = $company['creator']){
			return true;
			}
	}	

function get_company_updates(){
	delete_comment();
		if(isset($_GET['company_name']) && $_GET['action'] == 'show' && (!isset($_POST['search_company']))){
		//simply official comments (statements) by the company
		remove_file();
		
		echo '<div class="col-md-12">';
		add_comment($subject = 'company',
		$reply='Company Updates', 
		$placeholder="Post a new update",
		$button_text="Post this",
		$upload_allowed='true');
		echo '</div>';
		}
	}
	
function search_companies(){
	if(!isset($_GET['company_name']) && empty($_GET)){
	
	echo '<div class="aliceblue clear padding-20 margin-20">
	<form  method="post" action="'.$_SESSION['current_url'].'">
	<input type="text" name="company_name" value="" placeholder="Search companies">
	<input type="submit" name="search_company" value="search" class="submit">
	</form>
	</div>';
	if(is_logged_in()){
	echo '<a href="'.ADDONS_PATH.'company?add_type=company"><button>Add company</button></a>';
	}
		$company = trim(mysql_prep($_POST['company_name']));
		if(!empty($_POST['search_company'])){
			echo '<h2>Search results</h2>';
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM company WHERE company_name LIKE '%{$company}%' LIMIT 0, 10") 
		or die('Problem searching companies'. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else {
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM company LIMIT 0, 20") 
		or die('Problem searching companies'. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			echo '<br>';
		while($result = mysqli_fetch_array($query)){
			echo '<div class="whitesmoke inline-table padding-10 margin-10"><a href="'.ADDONS_PATH.'company/?company_name='.$result['company_name'].'&action=show&tid='.$result['id'].'"><img class="pull-left inline-block padding-5" src="'.$result['logo'].'" width="55" height="55" hspace="7"> &nbsp;'.$result['company_name'].'</a><br>
			'.substr($result['about'],0,250).'</div>';
			}
	
	}
	}
	
function show_my_companies(){
	if(!url_contains('/user')){
	$user = $_SESSION['username'];
	} else if(url_contains('/user/?user=') || url_contains('/user?user')){
		$user = trim(mysql_prep($_GET['user']));
		}
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM company WHERE creator='{$user}' order by id DESC LIMIT 0, 5") 
	or die('Problem selecting companies '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	while($result = mysqli_fetch_array($query)){
		echo '<a href="'.ADDONS_PATH.'company?company_name='.$result['company_name'].'&action=show&tid='.$result['id'].'">'.$result['company_name'].'</a> <hr>'; 
		}
	}

 // end of company functions file
 // in root/company/includes/functions.php
?>
