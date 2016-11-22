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

//print_r($_SERVER);
//print_r($_SESSION);
#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW


function add_project(){

	if(isset($_SESSION['username'])){	
	if($_GET['action']==='add' && $_GET['type']==='project'){
		$project_name = strtolower(trim(mysql_prep($_POST['project_name'])));
		$content = trim(mysql_prep($_POST['content']));
		$parent = trim(mysql_prep($_POST['parent']));
		$parent_id = trim(mysql_prep($_POST['parent_id']));
		$path = ADDONS_PATH."project_manager/?action=show&project_name={$project_name}";
		$author = $_SESSION['username'];
		$editor = '';
		$created = date('c');
		$last_update ='';
		$status = 'pending';

		if($_POST['submit'] ==='Add project') {
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `project_manager_project`
			(`id`, `project_name`, `content`, `author`, `project_manager`, `path`, `editor`, `created`, `last_updated`, `status`, `parent`, `parent_id`) 
			VALUES ('0','{$project_name}', '{$content}', '{$author}', '{$author}', '{$path}', '{$editor}','{$created}','{$last_updated}','{$status}','{$parent}','{$parent_id}')") 
			or die ("Error inserting project ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			if($query){ 
				activity_record(
					$parent_id = $result['id'],
					$actor=$author,
					$action=' created the project ',
					$subject_name = $project_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH.'project_manager/?action=show&project_name='.$project_name,
					$date = $created,
					$parent='project_manager'
					);
				
				session_message("success", "project saved successfully!"); 
				redirect_to($_SESSION['prev_url']);
			}
		}

		if(isset($_GET['parent'])){
			$parent = $_GET['parent'];
			}
		if(isset($_GET['tid'])){
			$parent_id = $_GET['tid'];
			}
		echo '<h2 align="center">Add Project</h2><hr><form method="post" action="'.$_SESSION['current_url'].'">
		Name :<br><input type="text" name="project_name" value="" placeholder="Project name"><br>
		<input type="hidden" name="parent" value="'.$parent.'"> 
		<input type="hidden" name="parent_id" value="'.$parent_id.'"> 
		Description: <br><textarea name="content" size="5"> What is this project about ?</textarea>
		<input type="submit" name="submit" value="Add project" class="button-primary">
		</form>	';
		}
	}
}

function make_task($string=''){ // makes a project out of a post or other content type
	
	if($string === ''){
		$string = 'Make Task';
		}
	$task_name = strtolower(trim(mysql_prep($_GET['job_title'])));
	$parent = '';
	$parent_type = 'jobs';
	$content = mysql_prep($_SESSION['current_url']);
	$path = $_SESSION['current_url'];
	$author = $_SESSION['username'];
	$editor = '';
	$created = date('c');
	$last_update ='';
	$status = 'started';
	$reward = $_SESSION['job_reward'];
	
	echo '<form action='.$_SESSION['current_url'].' method="post">
	<button type="submit" name="make_project" value="yes">'.$string.'</button>
	</form>';
	
	if($_POST['make_task'] ==='yes') {
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `project_manager_task`(`id`, `task_name`, `parent`, `parent_type`, `content`, `author`, `project_manager`, `path`, `editor`, `created`, `last_updated`, `assigned_to`, `status`, `priority`, `reward`) 
			VALUES ('','{$task_name}', '{$parent}', '{$parent_type}' '{$content}', '{$author}', '{$author}', '{$path}', '{$editor}','{$created}','{$last_updated}','{$author}','{$status}','{$priority}','{$reward}')") 
			or die ("Error inserting task ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			if($query){ 
				activity_record(
					$actor=$author,
					$action=' created the task ',
					$subject_name = $project_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH.'project_manager/?action=show&task_name='.$project_name,
					$date = $created,
					$parent='jobs'
					);
				
				status_message("success", "Task saved successfully!"); 
			}
		}
	
	}

function edit_project(){
		
if( is_admin || is_author() || is_project_manager()){
	
		if(isset($_POST['project_name'])){
			$project_name = strtolower(trim(mysql_prep($_POST['project_name'])));
			}
		if(isset($_POST['content'])){
			$content = trim(mysql_prep($_POST['content']));
			}
		$updated = date('c');
		$path = $path = ADDONS_PATH."project_manager/?action=show&project_name={$project_name}"; 
		
		if($_POST['submit'] === 'Save project'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_project` SET `project_name`='{$project_name}', 
		`content`='{$content}',`path`='{$path}', `last_updated`='{$updated}' WHERE `id`='{$_SESSION['project_id']}'") 
		or die('Edit project failed! '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		if($query){
			session_message('success', 'Task edited successfully');
			$destination = $_SESSION['prev_url'];
	echo "<script> window.location.replace('{$destination}') </script>";
			}
		
		if($_GET['action'] === 'edit' && $_GET['project_name'] === $_SESSION['project_name']){
		echo '<h2 align="center">Editing Project : <em><a href="'.ADDONS_PATH.'project_manager/?action=show&project_name='.$_SESSION['project_name'].'">'.$_SESSION['project_name'].'</a></em></h2>
		<hr><form method="post" action="'.$_SESSION['current_url'].'">
		Name :<br><input type="text" name="project_name" value="'.$_SESSION['project_name'].'" placeholder="Project name"><br>
		Description: <br><textarea name="content" size="5"> '.$_SESSION['project_content'].'</textarea>
		<input type="submit" name="submit" value="Save project" class="button-primary">
		</form>	';
		}
		
		
	}
}

function show_edit_project_link(){
	if(is_logged_in() && is_author() && $_SESSION['project_status'] === 'pending'){
		if($_GET['action'] !== 'edit'){
			echo '<a class="u-pull-right" href="'.ADDONS_PATH.'project_manager/?action=edit&project_name='.$_SESSION['project_name'].'">Edit project&nbsp</a>';
		}
	}
}

function show_projects_list(){
	$query = $_SERVER['QUERY_STRING'];
	
	if(($_GET['action']==='show' && $_GET['type']==='project') || empty($query)){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_project` ORDER BY `id` DESC LIMIT 30")
		or die ("! something is wrong with project lists" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$list = "<h2>Projects list</h2><hr><ol>";
    $num = mysqli_num_rows($query);
    
		
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

function show_project_page(){
		
	if($_GET['action']==='show' && !empty($_GET['project_name'])){
			
		$project_name = trim(mysql_prep($_GET['project_name']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_project` WHERE `project_name`= '{$project_name}'");
		
		$result = mysqli_fetch_array($query);
		$_SESSION["project_manager"] = $result['project_manager'];
		$_SESSION['project_status'] = $result['status'];
		$_SESSION['project_author'] = $result['author'];
		$_SESSION['id'] = $result['id'];
		$_SESSION['project_id'] = $result['id'];
		$_SESSION['project_name'] = $result['project_name'];
		$_SESSION['project_content'] = $result['content'];
		$_SESSION['project_url'] = ADDONS_PATH.'project_manager/?action=show&project_name='.$result['project_name'];
		
		if($result['parent'] == 'company'){
		$query2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT company_name, path FROM company WHERE id='{$result['parent_id']}'");
		$result2 = mysqli_fetch_array($query2);
		}
		echo '<h2 align="center">Showing project for <a href="'.$result2['path'].'&action=show&tid='.$result['parent_id'].'">'.$result2['company_name'].'</a></h2><div class="sweet_title">'.ucfirst($result['project_name']).'<div class="u-pull-right">'.$result['status'].'&nbsp&nbsp</div></div>';
		
		show_start_project_button();
		$incomplete = has_incomplete_tasks();
		if(!$incomplete['bool']){
		show_mark_as_complete_project_link();
		}
		
		echo '<div class="page_content">';
		$output = parse_text_for_output(urldecode($result['content']));
		echo $output;
		
		echo '<br><br><hr><strong>Project Manager : <a href="'.BASE_PATH.'user/?user='.$result['project_manager'].'">'.$result['project_manager'].'</a></strong>';
		
		echo '</div>';  
		show_edit_project_link();
		
		if(addon_is_active('follow')){
			show_user_follow_button($child_name=$project_name,$parent='project');
			follow($child_name=$project_name);
			unfollow($parent='project',$child_name=$project_name);
		}
		show_status();
		
		if(is_project_manager()){ 
			if($_SESSION['project_status'] !== 'pending' 
			&& $_SESSION['project_status'] !== 'completed'
			&& $_SESSION['project_status'] !== 'disapproved' 
			&& $_SESSION['project_status'] !== 'submitted'){
				echo '<div align="center"><a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&project_name='.$project_name.'">
					<div>Add a new task to this project </div>
				</a></div>';
			}
		}
		
		
	}
}

function add_task(){
	//print_r($_POST);
	if(isset($_SESSION['username'])){
		
		if($_GET['action']==='add' && (!empty($_GET['task_name']) || !empty($_GET['project_name']))){
			$task_name = trim(mysql_prep($_POST['task_name']));
			$project_name = trim(mysql_prep($_GET['project_name']));
			
			if (isset($_GET['project_name'])){
			$parent = trim(mysql_prep($_GET['project_name']));	
			$route = '?project_name=';	
			$parent_type = 'project';
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `project_manager` FROM `project_manager_project` WHERE `project_name`='{$project_name}'") 
			or die("Error selecting project_manager" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			$pm = mysqli_fetch_array($query);
			
			
		} else if (isset($_GET['task_name'])){
			$parent = trim(mysql_prep($_GET['task_name']));	
			$route = '?task_name=';
			$parent_type = 'task';
		}
			$content = trim(mysql_prep($_POST['content']));
			$reward = trim(mysql_prep($_POST['reward']));
			$author = $_SESSION['username'];
			$project_manager = $pm['project_manager'];
			$_get_task_name = trim(mysql_prep($_POST['task_name']));
			$path = ADDONS_PATH.'project_manager/?action=show&task_name='.$_get_task_name;
			$editor = '';
			$created = date('c');
			$last_update ='';
			$assigned_to = strtolower(trim(mysql_prep($_POST['assigned_to'])));
			$status = 'pending';
			$priority = 'medium';
			
		if(!empty($_GET['parent']) && !empty($_GET['parent_type'])){
			$parent = trim(mysql_prep($_GET['parent']));
			$parent_type = trim(mysql_prep($_GET['parent_type']));
			}
		if($_GET['parent_type'] == 'jobs'){
			$path = ADDONS_PATH.'jobs/?job_title='.$parent;
			}

			if($_POST['submit'] ==='Add task') {
				$_get_task_name = trim(mysql_prep($_GET['task_name']));
				$_get_project_name = trim(mysql_prep($_GET['project_name']));
				
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `project_manager_task`
				(`id`, `task_name`, `parent`, `parent_type`, `content`, `author`, `project_manager`, `path`, `editor`, `created`, `last_updated`, `assigned_to`, `status`, `priority`, `reward`) 
				VALUES ('','{$task_name}','{$parent}','{$parent_type}','{$content}','{$author}','{$project_manager}','{$path}','{$editor}','{$created}','{$last_updated}','{$assigned_to}','{$status}','{$priority}','{$reward}')") 
				or die ("Error inserting task ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				
				
				if($query){ 
					activity_record(
					$actor=$author,
					$action=' created the task ',
					$subject_name = $task_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= ADDONS_PATH.'project_manager/?action=show&task_name='.$task_name,
					$date = $created,
					$parent='project_manager'
					);
					
			
				
					status_message("success", "Task saved successfully!");
					if(isset($_GET['project_name'])){ echo '<h2 align="center">return to <a href="'.ADDONS_PATH.'project_manager/?action=show'.'&project_name='.$_get_project_name.'">'.$_get_project_name.'</a></h2>';}
					else if(isset($_GET['task_name'])){echo '<h2 align="center">return to <a href="'.ADDONS_PATH.'project_manager/?action=show'.'&task_name='.$_get_task_name.'">'.$_get_task_name.'</a></h2>';}
				}
			}

			if(isset($_GET['task_name'])){
				echo '<br><hr><h2> Add a Sub task to this Task : <a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$_get_task_name.'">'.$_get_task_name.'</a></h2>';
			} else if(isset($_GET['project_name'])){
				echo '<br><hr><h2> Adding a task to project : <em><a href="'.ADDONS_PATH.'project_manager/?action=show&project_name='.$_GET['project_name'].'">'.$_GET['project_name'].'</a></em></h2>';
				}
			
			echo '<hr><div class="gainsboro"><form method="post" action="'.$_SESSION['current_url'].'">
			<input type="text" name="task_name" value="" placeholder="Task name"> 
			Prioriy: 
			<select name="priority">
			<option>highest</option>
			<option>medium</option>
			<option>low</option>
			</select>
			<input type="hidden" name="project_name" value="'.$_GET['project_name'].'" placeholder="Project name"><br>
			Description: <br><textarea name="content" size="5"> What is this task about ?</textarea>
			<span>Assigned to : 
			<input type="text" name="assigned_to" value="" placeholder="username of assignee"></span>
			Reward : <input type="text" name="reward" value="" placeholder="Reward for completing this task"><br>
			</span><input type="submit" name="submit" value="Add task" class="button-primary">
			</form>	</div>';
			}
	}

}


function edit_task(){
	$path = $_SESSION['prev_url'];
	if( is_admin || is_author() || is_project_manager()){ 
		if(isset($_POST['task_name'])){
			$task_name = trim(mysql_prep($_POST['task_name']));
			}
		if(isset($_POST['content'])){
			$content = trim(mysql_prep($_POST['content']));
			}
			
		if(isset($_POST['assigned_to'])){
			$assigned_to = strtolower(trim(mysql_prep($_POST['assigned_to'])));
			}
		$updated = date('c');
		
		if($_POST['submit'] === 'Save task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `task_name`='{$task_name}', `path`='{$path}', 
		`content`='{$content}', `last_updated`='{$updated}',`assigned_to`='{$assigned_to}' WHERE `id`='{$_SESSION['task_id']}'") 
		or die('Edit task failed! '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		if($query){
			session_message('success', 'Task edited successfully');
			$destination = ADDONS_PATH.$_SESSION['page_context'].'/?action=show&task_name='.$_GET['task_name'];
			redirect_to($destination);
		//echo "<a class='text-center' href='". $_SESSION['prev_url']."'>Add another task</a>";
			
			}
		
		if($_GET['action'] === 'edit' && $_GET['task_name'] === $_SESSION['task_name']){
		echo '<h2 align="center">Editing Task :<em> <a href="'.$_SESSION['task_url'].'">'.$_SESSION['task_name'].'</a></em></h2><hr>
		<form method="post" action="'.$_SESSION['current_url'].'">
		Name :<br><input type="text" name="task_name" value="'.$_SESSION['task_name'].'" placeholder="Project name"><br>
		<input type="hidden" name="task_name" value="'.$_SESSION['task_id'].'" placeholder="Project name"><br>
		Description: <br><textarea name="content" size="5"> '.$_SESSION['task_content'].'</textarea><br>
		<input type="text" name="assigned_to" value="'.$_SESSION['task_assignee'].'" placeholder="assigned to"><br>
		<input type="submit" name="submit" value="Save task" class="button-primary">
		</form>	';
		}
		
		
	}
}

function show_edit_task_link(){
	if($_GET['action'] !== 'edit' && (is_author() || is_admin())){
		echo '<a class="u-pull-right" href="'.ADDONS_PATH.'project_manager/?action=edit&task_name='.$_SESSION['page_name'].'">Edit task&nbsp</a>';
		}
	};
	



function show_task_list($priority='',$status=''){
	if($_GET['action'] !== 'add'){ // used to prevent sub tasks from showing when adding new tasks
		echo '<div id="task-list">';
		
		$project_name = trim(mysql_prep($_GET['project_name']));
		
		if($_GET['action']==='show' || isset($_GET['jid']) || isset($_SESSION['job_id'])){
			if (isset($_GET['project_name'])){
				$parent = trim(mysql_prep($_GET['project_name']));
				$host = "project";
				$title ='Task list for this ';
			} else if (isset($_GET['task_name'])){
			
				$parent = trim(mysql_prep($_GET['task_name']));
				$host = 'task';
				$title = 'Sub tasks for this ';
			} else if (isset($_GET['job_title'])){
			
				$parent = trim(mysql_prep($_GET['job_title']));
				$host = 'Job';
				$title = 'Tasks for this ';
			} else { $parent = ''; 
				
			}
			$condition1 = " `parent`='{$parent}'";
			
			if($priority !==''){
				$condition2 = " AND `priority`='{$priority}'";
				}else { $condition2 = "";}
			if($priority !==''){
				$condition3 = " AND `status`='{$status}'";
				}else { $condition3 = "";}
				
			if(isset($parent)){	
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task`
				WHERE {$condition1} {$condition2} {$condition3} ORDER BY `id` DESC")
				or die ("! something is wrong with task lists" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				$num = mysqli_num_rows($query);
				if( $num > 0){
				$list = "<h3>{$title} {$host}</h3><ol>";
								
				$_SESSION['task_list_num'] = mysqli_num_rows($query);

				while($result = mysqli_fetch_array($query)){
				// check for status and decorate display
				if($result['status'] === 'completed'){
				$pattern = 'pm-completed';
				} 
				elseif($result['status'] === 'started'){
				$pattern = 'pm-started';	
				}
				elseif($result['status'] === 'submitted'){
				$pattern = 'pm-submitted';	
				}
				elseif($result['status'] === 'disapproved'){
				$pattern = 'pm-disapproved';
				}
				else {
				$pattern = 'pm-pending';	
					}
					$incomplete = has_incomplete_tasks($result['task_name']);
				if($incomplete['count'] > 0){
					$count_num = "<em class='u-pull-right'><small>Has <strong>{$incomplete['count']}</strong> incomplete tasks</small></em>";
					}
					$_SESSION['task_parent'] = $result['parent'];
					if($_GET['job_title'] !== $result['task_name'] || $_SESSION['task_parent'] !== $result['task_name'] ){
					$list = $list . "<li class='{$pattern}'><big> <a href='" .ADDONS_PATH ."project_manager/?action=show&task_name=" .$result['task_name']  ."'>".ucfirst($result['task_name']) ."</a></big>&nbsp{$count_num}</li><hr>";
					$count_num = '';
					}
				} $list = $list . "</ol>";
				echo $list;
				} else {$list = '';}
			
			}
		
		}
		echo '</div>';
	}
}


function show_task_page(){
	
	if($_GET['action']==='show' && !empty($_GET['task_name'])){
		$task_name = trim(mysql_prep($_GET['task_name']));
		$task_id = trim(mysql_prep($_GET['tid']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task` WHERE `task_name`= '{$task_name}'");
		
		$result = mysqli_fetch_array($query);	
		$_SESSION['task_assignee'] = $result['assigned_to'];
		$_SESSION['task_author'] = $result['author'];
		$_SESSION['task_status'] = $result['status'];
		$_SESSION['task_id'] = $result['id'];
		$_SESSION['task_name'] = $result['task_name'];
		$_SESSION['task_content'] = $result['content'];
		$_SESSION['task_parent'] = $result['parent'];
		$_SESSION['task_parent_type'] = $result['parent_type'];
		$_SESSION['task_reward'] = $result['reward'];
		$_SESSION['task_url'] = ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'&tid='.$result['id'];
		
		if($result['parent_type'] === 'project'){ 
			$route1 = 'project_manager/';
			$route2 = '?action=show&project_name=';
		} else if($result['parent_type'] === 'task') {
			$route1 = 'project_manager/';
			$route2 = '?action=show&task_name=';
		} else if($result['parent_type'] === 'jobs') {
			$route1 = 'jobs/';
			$route2 = '?job_title=';
		}
		
		echo '<span class="u-pull-right whitesmoke"><strong>Parent : '.'<a href="'.ADDONS_PATH.$route1.$route2.
		$result['parent'].'">'.$result['parent'].'</a></strong></span>';
		echo '<h2 align="center">Showing Task </h2><div class="sweet_title">'.ucfirst($result['task_name']).'</div>';
		
		show_start_task_link();
		show_mark_as_complete_task_link();
		
		show_submit_task_link();
		
		echo '<div class="page_content">';
		$output = parse_text_for_output(urldecode($result['content']));
		
		
		if($result['status']==='completed'){
			
		echo '<div class="completed" id="strikethrough">'. $output .'</div>';
		} else { echo $output; }
		
		echo '<br><b>Reward : </b>'.$result['reward'];
		echo '<br><br><hr><strong>Assigned to <a href="'.BASE_PATH.'user/?user='.$result['assigned_to'].'">'.$result['assigned_to'].'</a></strong>';
		
		echo '<br></div>';
		
		
		show_user_follow_button($child_name=$task_name,$parent='task'); 	
		follow($child_name=$task_name);
		unfollow($parent='task',$child_name=$task_name);
		show_status();
		
		if($_result['status'] !== 'pending' 
			&& $_result['status'] !== 'completed' 
			&& $_result['status'] !== 'submitted' 
      && is_project_manager()){
    
      echo '<br><div align="center"><a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&task_name='.$task_name.'&tid='.$task_id.'">
      <div>Add a sub task to this task </div>
      </a></div><br>';
    }
    
    show_task_submission_notes();
    
	}
}

function show_task_submission_notes(){
  if(($_SESSION['task_status'] === 'submitted' 
  || $_SESSION['task_status'] === 'disapproved' 
  || $_SESSION['task_status'] === 'completed')
  && is_project_manager()){
      
    $page = $_SESSION['task_name'];
    $user = $_SESSION['username'];

    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task_submissions` WHERE `parent`='{$page}' 
    AND `author`='{$user}'") or die('FAiled to get submission notes!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    echo '<br><div class="page-content"><h3>Submission notes</h3><ul>';
    while($result = mysqli_fetch_array($query)){
      $submission_id = $result['id'];
      //set class based on status
      if($result['status'] === 'disapproved'){
        $class = 'pm-disapproved';
      } else if($result['status'] === 'submitted'){
        $class = 'whitesmoke';
      } else if($result['status'] === 'completed'){
        $class = 'pm-completed';
      }
      
      echo "<li class='{$class} round-border'>{$result['submission_note']}";
       if($result['status'] === 'submitted'){
          echo "<span class='u-pull-right'>";
          show_mark_approved_link($submission_id);
          show_mark_disapproved_link($submission_id);
          echo '</span>';
        } 
      echo "<br>{$result['created']} </li>";
      show_disapprove_with_reason_form($submission_id);
    } echo "</ul></div>";
  }

}

function project_manager_menu(){
	
//if(isset($_SESSION['username'])){
		//$s = $_SERVER['QUERY_STRING'];
	//if($s ===''){	
	echo '<a href="'.ADDONS_PATH.'project_manager/?action=show&type=project"><div>View Projects</div></a><br>';	
	//} else {
		
	//}
}




function go_to_project_manager(){
	
	echo '<a href="'.ADDONS_PATH.'project_manager"><aside class="call-to-action">Projects</aside></a>';
	
}

function show_add_task_button(){

	if(is_logged_in() && is_project_manager()){
	
	$project_name = trim(mysq_prep($_GET['project_name']));
	
	echo '<div align="center"><a href="'.ADDONS_PATH.'project_manager/?action=add&type=task&project_name='.$project_name.'">
			<div>Add a new task to this project </div>
		</a></div><br>';
	}
}
	
	
function search_projects(){
	
		$s = $_SERVER['QUERY_STRING'];
	
	if($s ===''){
		
	echo '<div align="center" class="whitesmoke padding-10">
	<em>Click on a project to add tasks or suggestions</em>';
	
	echo '<h2>Search Projects</h2>';
	show_search_special_form($table='project_manager_project',$column='project_name');	
	echo '</div>';
	echo '<div class="center">';
	do_search($table='project_manager_project',$column='project_manager_project');
	echo '</div>';

	
	}
	
	if(false === $_GET['QUERY_STRING']){
		show_activity($parent='project_manager');
	}	
	
}
	
function show_add_project_link(){
	
	if(isset($_SESSION['username'])){
	echo '<a href="'.ADDONS_PATH.'project_manager/?action=add&type=project">
			<div>Add a new project</div>
		</a><br>';
	}
}

function show_start_project_button(){
	$project = $_SESSION['task_name'];
	
	if($_GET['status'] === 'start_project' && is_project_manager()){ 	
		if(!empty($_GET['project_name']) && empty($_GET['task_name'])){
			
			//if is a project page
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_project` SET `status`='started' 
			WHERE `project_name`='{$project}' AND `status`='pending'") or die('failed to start project' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			redirect_to($_SESSION['prev_url']);
		}
	} 
	
		// SHow Button
	if(!empty($_GET['project_name']) && empty($_GET['task_name']) && is_project_manager()){
		
		// if viewer is owner or manager then show 
		if( $_SESSION['project_status'] !== 'completed' && $_SESSION['project_status'] !=='started'){
			echo '<a href="'.$_SESSION['current_url'].'&status=start_project">
				<span class="u-pull-right"><button>Start project</button></span>
			</a>';
		}
	
	} 
}



function is_assignee(){
	if($_SESSION['task_assignee'] === $_SESSION['username']){
		return true;
		} else { return false; }
	
}


function is_project_manager(){
	if(!empty($_SESSION['project_manager']) && $_SESSION['project_manager'] === $_SESSION['username']){
		return true;
		} else { return false; }
	
}

function is_a_project($title=''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from project_manager_project where project_name='{$title}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if(!empty($num)){
		return true;
		}
	}

function is_a_task($title=''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from project_manager_task where task_name='{$title}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if(!empty($num)){
		return true;
		}
	}

function show_mark_as_complete_project_link(){
		if(is_logged_in() && is_project_manager() && ($_SESSION['status'] !== 'completed' && $_SESSION['status'] !== 'pending')){
		if($_GET['status'] === 'complete_project'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_project` SET `status`='completed' WHERE `id`='{$_SESSION['project_id']}' ") 
		or die('cannot mark project as complete!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
	echo '<a href="'.$_SESSION['current_url'].'&status=complete_project">
	<span class="u-pull-right"><button>Mark as Completed</button></span>
	</a>';
	}
}
	
function show_mark_as_complete_task_link(){

	if(is_logged_in() && is_project_manager() && $_SESSION['status'] === 'submitted'){
		
		if($_GET['status'] === 'complete_task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot mark task as complete!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&status=complete_task">
		<span class="u-pull-right"><button>Mark as Completed</button></span>
		</a>';
		
	}
}

function show_cancel_task_link(){

	if(is_logged_in() && is_project_manager() && $_SESSION['task_status'] == 'pending'){
		
		if($_GET['status'] === 'complete_task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot mark task as complete!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
		echo '<a href="'.$_SESSION['current_url'].'&status=complete_task">
		<span class="u-pull-right"><button>Mark as Completed</button></span>
		</a>';
		
	}
}

function project_instructions(){

	echo '<div class="padding-10"><h3>Instructions</h3>
	Projects can only be deleted by admins, so contact an admin to have your project removed.
	</div>';
}




function show_submit_task_link(){
	
	if(is_assignee()){
    if($_SESSION['task_status'] === 'started' || $_SESSION['task_status'] === 'disapproved'){

      if($_GET['status'] === 'submit_task'){
        echo '<h2>Submit task with details</h2><form method="post" action="'.$_SESSION['current_url'].'">
        <textarea name="notes" placeholder="Any notes or details that concern this submission"></textarea>
        <input type="hidden" name="parent" value="'.$_SESSION['task_name'].'">
        <input type="hidden" name="status" value="submitted">
        <input type="submit" name="submit" value="Submit task"> 
        </form>';
        
        if($_POST['submit'] === 'Submit task' && $_POST['status'] === 'submitted'){
          $submission_note = $_POST['notes'];
          $parent = $_POST['parent'];
          $status = 'submitted';
          $author = $_SESSION['username'];
          $date = date('d/m/Y');
          $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='submitted' WHERE `id`='{$_SESSION['task_id']}' ") 
          or die('cannot submit task! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
          $query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `project_manager_task_submissions`(`id`,`parent`,`submission_note`,`disapproval_note`,`status`,`author`,`created`) 
          VALUES ('','{$parent}','{$submission_note}','{$disapproval_note}','{$status}','{$author}','{$date}')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
          redirect_to($_SESSION['prev_url']);
        }
      } 
      else {
        // Show submit task button
        echo '<a href="'.$_SESSION['current_url'].'&status=submit_task">
        <span class="u-pull-right"><button> Submit task </button></span>
        </a>';
      }

    }
	
		if($_SESSION['status'] === 'disapproved'){
			echo '<a href="'.$_SESSION['current_url'].'&status=submit_task">
			<span class="u-pull-right"><button>re-Submit task </button></span>
			</a>';
		}

	}

}

function show_start_task_link(){
	if(is_assignee() && $_SESSION['task_status'] === 'pending'){
		if($_GET['status'] === 'start_task'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='started' WHERE `id`='{$_SESSION['task_id']}' ") 
		or die('cannot start task! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		redirect_to($_SESSION['prev_url']);
		}
	
	echo '<a href="'.$_SESSION['current_url'].'&status=start_task">
	<span class="u-pull-right"><button> Start task </button></span>
	</a>';
		
	}
}

function show_mark_approved_link($submission_id){
  if(is_logged_in() && is_project_manager() && $_SESSION['task_status'] === 'submitted'){
    
    if($_GET['status'] === 'approve_task'){
        
        $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='completed' WHERE `id`='{$_SESSION['task_id']}' ") 
        or die('cannot submit task! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task_submissions` SET `status`='completed' WHERE `id`='{$submission_id}'") 
        or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        redirect_to($_SESSION['prev_url']);
    }
    
    //if($_GET['status'] !== 'disapprove_task'){
      echo '<a href="'.$_SESSION['current_url'].'&status=approve_task&submission_id='.$submission_id.'">'.
      '<span class="u-pull-right"><button> Approve </button></span>
      </a>';
    //}
    
  }
  
}


function show_mark_disapproved_link($submission_id){	
	if(is_logged_in() && is_project_manager() && $_SESSION['project_status'] === 'submitted'){
		
		if($_GET['status'] === 'disapprove_task' && $_GET['submission_id'] === $submission_id){
	
      if($_POST['submit'] === 'Disapprove'){
        $reason = $_POST['reason'];
        $parent = $_POST['parent'];
        $author = $_SESSION['username'];
        $date = date('d/m/Y');
        
        $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task` SET `status`='disapproved' WHERE `id`='{$_SESSION['task_id']}' ") 
        or die('cannot submit task! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        $query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `project_manager_task_submissions` SET `disapproval_note`='{$reason}', `status`='disapproved' WHERE `id`='{$submission_id}'") 
        or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        redirect_to($_SESSION['prev_url']);
      }

    }
    if($_GET['status'] !== 'disapprove_task'){
      echo '<a href="'.$_SESSION['current_url'].'&status=disapprove_task&submission_id='.$submission_id.'">'.
      '<span class="u-pull-right"><button> Disapprove (with reason) </button></span>
      </a>';
    }
    
  }
  
}

function show_disapprove_with_reason_form($submission_id){
  
  if($_GET['status'] === 'disapprove_task' 
  && $_GET['submission_id'] === $submission_id
  && $_SESSION['task_status'] !== 'disapproved'){
    echo '<form method="post" action="'.$_SESSION['current_url'].'">
    <input type="hidden" name="status" value="disapproved">
    <textarea name="reason" placeholder="reason for disapproval"></textarea>
    <input type="submit" name="submit" value="Disapprove"> 
    </form>';
  }
  
}


function show_status(){
  
	if($_SESSION['task_status'] === 'pending' || $_SESSION['project_status'] === 'pending'){
	$status = 'pending';
	$pattern = 'pm-pending';
	} else if($_SESSION['task_status'] === 'started' || $_SESSION['project_status'] === 'started'){
	$status = 'started';
	$pattern = 'pm-started';
	} else if($_SESSION['task_status'] === 'completed' || $_SESSION['project_status'] === 'completed'){
	$status = 'completed';
	$pattern = 'pm-completed';
	} else if($_SESSION['task_status'] === 'submitted' || $_SESSION['project_status'] === 'submitted'){
	$status = 'submitted';
	$pattern = 'pm-submitted';
	} else if($_SESSION['task_status'] === 'disapproved' || $_SESSION['project_status'] === 'disapproved'){
	$status = 'disapproved';
	$pattern = 'pm-disapproved';
	}
	
	echo "<div class='{$pattern} text-center'>{$status} </div>";
}

function has_incomplete_tasks($project_name=''){
	
	$values = array('started','submitted','disapproved','pending');
	$table = 'project_manager_task';
	if($project_name === ''){
	$project = $_SESSION['project_name'];	
	} else { $project = $project_name; }
	$count = 0;
	
	foreach($values as $value){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` FROM `{$table}` WHERE `parent`='{$project}' 
	AND `status`='{$value}'") or die("Nothing selected!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$num = mysqli_num_rows($query);	
	$count = $count + $num;
	} 	

	if ($count > 0){
		//echo "has {$count} incomplete tasks!";
		$bool = true;
	} else { //echo 'does not have incomplete tasks';
		 $bool = false;
	}
		$returns = array('count'=>$count, 'bool'=>$bool);
		return $returns;
}


function my_assigned_tasks(){
	$user = $_SESSION['username'];
	
	if(is_logged_in()){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task` WHERE `assigned_to`='{$user}' ORDER BY `id` ASC");
		$num = mysqli_num_rows($query);

		echo "<div>";
		while($result = mysqli_fetch_array($query)){
		if($result['status'] === 'pending'){
		  echo '<li class="pm-pending left-pad-one"><a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'">'.$result['task_name'].'</a></li>';
		} else if($result['status'] === 'submitted'){
		  echo '<li class="pm-submitted left-pad-one"><a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'">'.$result['task_name'].'</a></li>';
		} else if($result['status'] === 'disapproved'){
		  echo '<li class="pm-disapproved left-pad-one"><a href="'.ADDONS_PATH.'project_manager/?action=show&task_name='.$result['task_name'].'">'.$result['task_name'].'</a></li>';
		} 
			
			}
		
		echo "</ul>";
		echo "</div>";
		if(empty($num)){
		  echo '<em>You have no assigned tasks at this time.</em>';  
		}
    }
}

function show_my_completed_tasks(){

	if(is_user_page()) { // if page viewed is a user page
	 $user = $_SESSION['user_being_viewed'];
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `project_manager_task` WHERE `assigned_to`='{$user}' 
		AND `status`='completed' ORDER BY `id` DESC LIMIT 20") 
		or die("Failed to get completed tasks!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$num = mysqli_num_rows($query);
		
		echo '<hr><h2>My Completed Project Tasks</h2>';
		if(empty($num)){
			echo "<em>No completed tasks!</em>";
		} else if($query){
			while($result = mysqli_fetch_array($query)){
			echo '<a href="'.$result['path'].'">'.$result['task_name'].'</a>, &nbsp ';	
			}
		
		} 
		echo '<br><hr>';
	}
}

function style_project_manager(){
	echo '<style  type="text/css">
	.pm-completed{ background-color: lightgreen; }
	.pm-started{ background-color: lightblue; }
	.pm-pending{ background-color: lightgrey; }
	.pm-submitted{ background-color: lightgoldenrodyellow; }
	.pm-disapproved{ background-color: lightcoral;}
			
	.pm-completed,
	.pm-started,
	.pm-submitted,
	.pm-disapproved,
	.pm-pending{padding: 5px; clear: both; margin: 5px;}
	</style>';
	
}
	
function pm_color_codes(){
	
	echo '<style  type="text/css">
	.code-completed{ background-color: lightgreen;}
	.code-started{ background-color: lightblue; }
	.code-pending{ background-color: lightgrey; }
	.code-submitted{ background-color: lightgoldenrodyellow; }
	.code-disapproved{ background-color: lightcoral;}
	
	.code-completed,
	.code-started,
	.code-submitted,
	.code-disapproved,
	.code-pending{padding: 5px; width: 10px; height: 5px;}
	</style>';
	
	echo '<div class="text-center"><strong>Color codes:</strong> <span class="code-pending">pending  + </span> 
			 <span class="code-started">started  + </span> 
			 <span class="code-submitted">submitted  + </span> 
			 <span class="code-completed">completed  + </span>
			 <span class="code-disapproved">disapproved  + </span>  </div><br>';
	}
//print_r($_SESSION);
 // end of project_manager functions file
 // in root/project_manager/includes/functions.php
