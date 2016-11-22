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
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

# ADD BLOCK
function add_block() {
		
// show form
$form = '<div class="edit-form page_content padding-20">
<form method="post" action="../process.php" class="padding-20">
<input type="hidden" name="action" size="20" class="" value ="insert">
Name :<input type="text" name="block_name" size="20" placeholder="block unique name"><br>
Title (optional) :<input type="text" name="block_title" size="20" class="menu-item-form" placeholder="block title">
<br>Start collapsed :<input type="checkbox" name="collapsible" value="true" checked>
<br>Description :<input type="text" name="description" size="20" class="">

<br>Show Title: 
<select name="show_title">
<option value="1" selected="selected">Yes</option>
<option value="0">No</option>
</select>
<br>Position:(<em>Starting from 0, higher numbers will appear last</em>)<br><input type="number" name="position" value="1" size="3" maxlength="3">
<br>Content: <a class="add-nicedit" onclick="addArea();">[ Show Editor]</a> &nbsp&nbsp <a class="remove-nicedit"  onclick="removeArea();">[ Hide Editor ]</a>
<br><textarea name="content" id="content-area" rows="4" class="ckeditor"></textarea>
<br>Region : <select name="region">';

// Populate section values from database

$result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from regions order by position asc") or die("Failed to select regions!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

while($regions= mysqli_fetch_array($result)) {
	# GET ALL EXCEPT REGIONS
	if($regions['region_name'] !=='ads'){
		$form = $form . "<option value=" . $regions['region_name'] .">".strtoupper($regions['region_name']) ."</option>";
	}
}
$form .= '</select>
<br>Show on these pages: <textarea name="page_visibility" rows="4" placeholder="Enter one page name per line ending with a comma">all</textarea>
<em>Valid values include "all", "home", or full paths like "http://example_site.com/here" where example_site.com is your domain name. </em><br> 
<br>Visible to role (Enter roles seperated by a coma) : <textarea name="role_visibility" rows="4" placeholder="Enter one page name per line ending with a coma">' .$row['role_visibility']. '</textarea>';

$is_admin = is_admin();
if ($is_admin){ $form = $form .
	'<br>Call back function: <br><input type="text" name="function_call" value="" placeholder="call back function">';
	} 
 $form = $form .'<br><input type="submit" name="submitted" value="Add block" class="submit"></form></div>';

echo $form;	// End of Form  	

}



# LIST BLOCKS

function list_blocks() {

if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
	
	if(isset($_POST['block_list_limit'])){
		$post_limit = $_POST['block_list_limit'];
	} else { $post_limit = 10;}
	if(isset($_POST['block_list_number_holder'])){	
		$step = $_POST['block_list_number_holder'];
	} else{ $step = 0; }
	
	if(isset($_POST['clear_block_list_values'])){
		unset($_POST);
		$number_holder = '';
		$post_limit = 10;
		$step = 0;
	}	
			
		$limit = "LIMIT ". $step .", ".$post_limit;
		$number_holder = $post_limit + $step;
			
  $query= mysqli_query($GLOBALS["___mysqli_ston"], "Select * from `blocks` order by `id` DESC {$limit}") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  #echo "Data fetched succesfully";
  $count = mysqli_num_rows($query);
  
  	$blocklist = "<h1> Blocks list</h1>";
  	$blocklist = $blocklist ."<table class='table'><thead><th>&nbspTitle </th></thead>";

	if($count < 1){status_message('alert', 'No more results here!');}
	
  while($row = mysqli_fetch_array($query)){
  	$blocklist = $blocklist 
 
  . '<tr><td class"table-message-plain"><a href="' 
  . BASE_PATH ."blocks/edit?" 
  . 'action='
  . 'edit_block&'
  . 'block_name='
  . $row['block_name']
  . '&ref_page='.$_SESSION['current_url']
  . '" '
  . '>'.$row['block_title'].'</a>'
  . ' &nbsp&nbsp <em>[ block_name: ' .$row['block_name'] .']</em>'
  . '&nbsp &nbsp'
  . '&nbsp<a href="'
  . BASE_PATH . "blocks/process.php?"
  . 'action='
  . 'delete_block&'
  . 'block_name='
  . $row['block_name']
  .'&deleted='
  . 'jfldjff7'
  . '" '
  . '>delete </a>&nbsp </td>'

  . "</tr>";
  }
  $blocklist = $blocklist . "</table><br>";
  
  echo $blocklist;
  
   echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='block_list_limit' value='10'>
		<input type='hidden' name='block_list_number_holder' value='".$number_holder."'>
		<input type='submit' name='submit' value='show older blocks' class='button-primary'>
		<input type='submit' name='clear_block_list_values' value='reset'>
		</form></div></div>";
		
	} else { deny_access(); }

  }

# EDIT BLOCKS

function edit_block(){

if(url_contains('edit_block')){
$block = $_GET['block_name'];
}	

if(isset($block)){
	$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from blocks WHERE block_name='{$block}' LIMIT 1") 
	or die("Failed to get selected Block" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 
	$row = mysqli_fetch_array($result);
}
	 
	// now we show the Block edit form
	echo "<h3> Edit " . strtoupper($row['block_name']) ." Block </h3>";
	 	
$form = '<div class="edit-form page-content ">' .
'<form method="POST" action="../process.php" class="padding-20">' .
'<input type="hidden" name="id" class="" value ="' .$row['id'] .'" >' .
'<input type="hidden" name="block_name" class="" value ="' .$row['block_name'] .'" >' .
'<input type="hidden" name="ref_page" class="" value="' .$_GET['ref_page'].'" >' .
'<input type="hidden" name="action" class="" value="update">' .
'<br>Title (optional):<input type="text" name="block_title" class="" value ="' .$row['block_title'] . '" >' .
'<br>Start Collapsed :<input type="checkbox" name="collapsible" value="true" >'.
'<br>Description :<input type="text" name="description" value="' .$row['block_description'] .'">' .
'<br>Show title? :<select name="show_title">
<option  value="1"';

# Set the saved show_title value
if ($row['show_title']==='1'){$form = $form. ' selected="selected">Yes</option>
	<option value="0">No</option></select>';}
else {$form = $form. '>Yes</option><option value="0" label="No" selected="selected">No</option></select>';}
 $form .=
'<br>Position: <input type="text" name="position" value="' . $row['position'] .
'" size="3" maxlength="3">(<em>Starting from 0, higher numbers will appear last</em>)' .
'<br>Content: <a class="add-nicedit" onclick="addArea();">[ Show Editor]</a> &nbsp&nbsp <a class="remove-nicedit"  onclick="removeArea();">[ Hide Editor ]</a>
<br><textarea name="content" id="content-area" rows="4" height="250">' .urldecode($row['content']) .'</textarea>' .
'<br><br>Change placement (Region) : <br><select name="region" size="1">' ;

// get the regions
$regions_result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from regions order by position asc") or die("Failed to select regions!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

while($regions= mysqli_fetch_array($regions_result)) {
	# GET ALL EXCEPT ADS
	if($regions['region_name'] !== 'ads'){
		$form = $form . "<option value='" . 
		$regions['region_name'] . 
		 "'";
		
		if ($row['region'] === $regions['region_name']){
			$form = $form ." selected='selected'>".strtoupper($regions['region_name']) ."</option>";}
			else { $form = $form. ">".strtoupper($regions['region_name']) ."</option>"; }
		}
	
	}

$form = $form . '</select>
<br><br>Page visibility (Enter one page name per line ending with a coma) : <textarea name="page_visibility" rows="4" placeholder="Enter one page name per line ending with a coma">' .$row['page_visibility']. '</textarea>
<br><br>Visible to role (Enter roles seperated by a coma) : <textarea name="role_visibility" rows="4" placeholder="Enter one page name per line ending with a coma">' .$row['role_visibility']. '</textarea>
<br><br><input type="submit" name="updated" value="Save Block" class="submit">' .
'</form></div>';

echo $form;

}

function delete_block($block_name = ''){
	
	    
  // Now we check if delete is requested  
if (isset($_GET['deleted'])) {
	//echo " id is " . $block_name . ' and delete button was pressed';
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from blocks WHERE block_name='{$block_name}' LIMIT 1");
	if($delete_query) {
		status_message('alert',$block_name ." Block deleted !");
	}
	elseif((isset($sent_deleted))){
		$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from blocks WHERE block_name='{$block_name}' LIMIT 1");
	if($delete_query) {
		status_message('alert',$block_name ." Block deleted!");
	}
}
	else {
   die(' Could not delete the specified Block!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
   
   }
} 
		//Programatically deleting from addon uninstallation
		$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from blocks WHERE block_name='{$block_name}' LIMIT 1") 
		or die("Could not delete {$block_name}" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if(($delete_query)) {
	status_message('alert',$block_name ." Block deleted!");		
	}
}


function show_blockable_functions(){ // functions available to be called as blocks
	echo '<h2>Fuctions available to call as blocks</h2>';
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `blocks` WHERE `parent_addon` LIKE '%system%'") 
	or die("Failed to get callable functionsfor blocks" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result=mysqli_fetch_array($query)){
		echo $result['function_call'].'<span class="tiny-text">'.$result['parent'].'</span><br>';
	}
}
	
function insert_blockable_function($parent,$function){
	// Parent should be addon name
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `blockable_functions`(`id`,`parent`,`function`) 
	VALUES ('0','{$parent}','{$function}')") or die("Failed to Save Blockable function!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
}


function block_create($block_name,$description,$function_call,$parent_addon){
	
	$content = trim(mysql_prep($content));
	$parent = 'system('.$parent_addon.')';
	$block_title = ucfirst(str_replace('_',' ',$block_name));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `blocks` WHERE `block_name`='{$block_name}'");
	$query= mysqli_query($GLOBALS["___mysqli_ston"], "INSERT IGNORE INTO `blocks`(
	`id`,
	`block_name`,
	`region`,
	`block_title`,
	`block_description`,
	`position`,
	`content`,
	`function_call`,
	`parent_addon`,
	`show_title`,
	`page_visibility`, 
	`role_visibility`) 
	VALUES ('0','{$block_name}','none','{$block_title}','{$description}','1','{$content}','{$function_call}','{$parent}','0','none','authenticated')") 
	or die("Block creation failed!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	status_message('success', "Successfully installed '{$block_title} Block!'");
}

function block_exists($block_name){
	// check if block exists before taking action
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `block_name` FROM `block` WHERE `block_name`='{$block_name}'");
	$num = mysqli_num_rows($query);
	if($num === 1){
		return true;
		}
	
	}

function enable_system_blocks($addon_name){
	
	
	
}
	
function disable_system_blocks($addon_name){
	
	
	
} 
	

function get_region_blocks($string,$title_class='',$content_class='',$page=''){
	$region = $string;
	
	#TRY TO SET PAGE VALUE FOR CONTROLLING PAGE VISIBILTY OF BLOCKS
	if(isset($_GET['page_name'])){
	$page = trim(mysql_prep($_GET['page_name']));
	
	} else if(isset($_GET['contest_name'])){
	$page = trim(mysql_prep($_GET['page_name']));
	
	}else if(isset($_GET['section_name'])){
	$page = trim(mysql_prep($_GET['section_name']));
	
	}else if(isset($_GET['fundraiser_name'])){
	$page = trim(mysql_prep($_GET['fundraiser_name']));
	
	}else if(url_contains('/user/?user=') || url_contains('/user?user=')
	|| url_contains('/user')){
	$page = 'user';
	
	}else {$page =  'all';}
	
	$url = $_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING'];
		
	#SET TITLE CLASS
	if($title_class===''){
	$title_class='sweet_title';
	}
	
	// Select and print blocks
$query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "SELECT * from blocks " .
	 'WHERE region="' .$region . '" ' .
	 'AND `region`!="none" ' .
	 'ORDER BY position ASC') or die("No blocks in this region!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$output = '';
	while($blocks= mysqli_fetch_array($query)) {	
		$block_id='block_'.$blocks['id'];
		if(!empty($blocks['content']) || !empty($blocks['function_call'])){
		//GET ROLE VISIBILITY
		$roles = explode(",",$blocks['role_visibility']);
				
		if((in_array($_SESSION['role'],$roles))
		 || $blocks['role_visibility'] == 'all'){
		$can_see_block = true;
	} else { $can_see_block = false; }
		
		if($can_see_block){
		$defined_pages = explode(",",$blocks['page_visibility']);
		
		#check if $page from url is one of $defined_pages from db
		
		if(in_array($page,$defined_pages) 
		|| in_array('all',$defined_pages) 
		){
		
		#SHOW ADMINS
		
				//show hidden edit link	
				if(is_admin()){
					$this_page= $_SESSION['current_url'];
					if($this_page == BASE_PATH.'?page_name=home'){
						$this_page = str_ireplace('?page_name=home','',$this_page);
						}
					echo '<div class="tiny-edit-text">
					<a href="' 
					  . BASE_PATH ."blocks/edit?" 
					  . 'action='
					  . 'edit_block&'
					  . 'block_name='
					  . $blocks['block_name']
					  . '&ref_page=' . $this_page
					  . '"> edit '.$blocks['block_title'].'</a>
					  </div>'; 
				}
		
		
						# Check show_title setting and show title if yes
						
				if ($blocks['show_title'] ==='1'){
					if($blocks['collapsed'] == 'true'){
						$collapse_class = '';
						} else { $collapse_class = 'in';}
					echo "<div onclick='none()' class='{$title_class}' data-toggle='collapse' data-target='#{$block_id}'> <span id='toggle-collapse'>[+]</span>"; 
					echo ucfirst($blocks['block_title']) ; 
					echo "</div>" ;
					
					echo "<div id='".$block_id."' class='collapse {$collapse_class}' >";
					if($blocks['content']!=='') {
					echo '<div>'.urldecode($blocks['content']);
					echo "</div>";
					}
					
						
					if(!empty($blocks['function_call'])){
							echo eval($blocks['function_call']);
						}
					echo "</div>";
				} 
				# If no, show only content or call function
					
				else if ($blocks['show_title'] ==='0'){
						if($blocks['region'] !=='highlight'){
							if($blocks['content'] !==''){
								echo "<div class='{$content_class}'>". urldecode($blocks['content']) ;
								echo "</div>";
							}
						} 
					 
					if(!empty($blocks['function_call'])){
						echo '<div>'.eval($blocks['function_call']).'</div>';
					} 
					 
				}
			}
		} // end if($can see block)
		}//end if (!empty)
		}// end while
	
}
 
 // end of block functions file
 // in root/blocks/includes/functions.php
?>
