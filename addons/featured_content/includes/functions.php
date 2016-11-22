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

function add_featured_content(){
	if($_POST['save'] == "Save Featured Content"){

		$title = trim(mysql_prep($_POST['title']));
		$content = trim(mysql_prep($_POST['content']));
		$caption = trim(mysql_prep($_POST['caption']));
		$link = trim(mysql_prep($_POST['link']));
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO featured_content(`id`,`title`,`caption`,`content`,`link`) 
		VALUES('0','{$title}','{$caption}','{$content}','{$link}')") or die("Failed to save featuered content ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			session_message('success', 'Featured content saved !');
			redirect_to(ADDONS_PATH.'featured_content');
			}
		}
	
	echo '<h1>Add Featured content</h1><form method="post" action="'.$_SESSION['current_url'].'">
	<input type="text" name="title" class="form-control" placeholder="title">
	<textarea name="content" class="form-control" placeholder="content"></textarea>
	<input type="text" name="caption" class="form-control" placeholder="caption">
	<input type="text" name="link" class="form-control" placeholder="link">
	<input type="submit" name="save" class="btn btn-primary btn-md" value="Save Featured Content">
	</form>';
	}

function show_featured_content(){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM featured_content order by id DESC limit 0,7") 
	or die("Problem selecting featured content ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if(!isset($_GET['edit_feature'])){
		echo ' <ul class="bxslider">';
		while($result = mysqli_fetch_array($query)){
			
			$output = parse_text_for_output($result['content']);
			echo '<li class="center-block"><a class="pull-right" href="'.$result['link'].'">'.str_ireplace('-',' ',ucfirst(urldecode($result['title']))).'</a><br>'. $output.'</li>';
		
		}
		echo '</ul>';
	}
}


function list_featured_content(){
	// EDIT FEATURE
	//Save edit to db
	if($_POST['save'] == "Save Edit"){
		$title = trim(mysql_prep($_POST['title']));
		$content = trim(mysql_prep($_POST['content']));
		$caption = trim(mysql_prep($_POST['caption']));
		$link = trim(mysql_prep($_POST['link']));
		$id =mysql_prep($_GET['edit_feature']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE featured_content SET title='{$title}', content='{$content}', caption='{$caption}', link='{$link}' WHERE id='{$id}'") 
		or die("Error saving feature ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			session_message('success','Edit Saved!');
			redirect_to(ADDONS_PATH.'featured_content');
			}
		}
	//Show edit form
	if(isset($_GET['edit_feature'])){
		$id = mysql_prep($_GET['edit_feature']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM featured_content where id='{$id}'") or die('Error selecting feature '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		While($result=mysqli_fetch_array($query)){
			echo '<div class="whitesmoke padding-20"><h1>Editing '.$result['title'].'</h1><form method="post" action="'.$_SESSION['current_url'].'">
			<input type="text" name="title" class="form-control" placeholder="title" value="'.$result['title'].'">
			<textarea name="content" class="form-control" placeholder="content" >'.$result['content'].'</textarea>
			<input type="text" name="caption" class="form-control" placeholder="caption" value="'.$result['caption'].'">
			<input type="text" name="link" class="form-control" placeholder="link" value="'.$result['link'].'">
			<input type="submit" name="save" class="btn btn-primary btn-md" value="Save Edit">
	
			</form></div>'; 
			}
		}
		
	// DELETE FEATURE
	// Delete from db
	if(isset($_GET['delete_feature'])){
		$id = mysql_prep($_GET['delete_feature']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM featured_content where id='{$id}'") 
		or die('Error deleting feature '. mysql_query());
		session_message('alert','Feature deleted!');
		redirect_to(ADDONS_PATH.'featured_content');
		}
	
	if(is_logged_in()){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM featured_content order by id DESC limit 0,20") 
		or die("Problem selecting featured content ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		echo '<h1>Administer featured content</h1><ul>';
		while($result = mysqli_fetch_array($query)){
			echo '<li><a href="'.$result['link'].'">'.ucfirst(str_ireplace('-',' ',urldecode($result['title']).'</a> &nbsp;&nbsp;'));
				if(is_admin()){
				echo '<span class="tiny-text"><a href="'.$_SESSION['current_url'].'?edit_feature='.$result['id'].'">edit</a> | </span>
				<span class="tiny-text"><a href="'.$_SESSION['current_url'].'?delete_feature='.$result['id'].'">delete</a>  </span>';
				echo '</li>';
				}
			}
		echo '</ul>';
	}
}

function show_feature_this_link(){
	
	
	if(isset($_GET['page_name'])){
		$target = '&target=page';
		$type = 'page';
		$title = '&title=page_name';
		$content = '&content=content';
		$target_name = $_GET['page_name'];
		
	}else if(isset($_GET['fundraiser_name'])){
		$target = '&target=fundraiser';
		$type = 'fundraiser';
		$title = '&title=fundraiser_name';
		$content = '&content=description';
		$target_name = $_GET['fundraiser_name'];
		
	}else if(isset($_GET['contest_name'])){
		$target = '&target=contest';
		$type = 'contest';
		$title = '&title=contest_name';
		$content = '&content=description';
		$target_name = $_GET['contest_name'];
		
	}
	if(isset($target)){
		$target_name = urlencode($target_name);
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from featured_content where title='{$target_name}'") 
		or die('Failed to check if content is featuring '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if(empty($num)){
		if(is_admin()){
		echo '<a href="'.$_SESSION['current_url'].$target.$title.$content.'&add_feature='.$_GET['tid'].'"> &nbsp;<i class="pull-right whitesmoke padding-10 margin-10">feature this</i></a>';
		} else { echo '<i class="pull-right whitesmoke padding-10 margin-10">not featured</i>'; }
	}
	else{ 
		echo '<i class="pull-right whitesmoke padding-10 margin-10 green-text">currently featuring</i>'; 
		}
	}
	
	if(isset($_GET['target']) && isset($_GET['add_feature'])){
		
		$table = trim(mysql_prep($_GET['target']));
		$id = trim(mysql_prep($_GET['add_feature']));
		$title =trim(mysql_prep($_GET['title']));
		$content =trim(mysql_prep($_GET['content']));
		$link ='&link='.$_SESSION['prev_url'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT {$title},{$content} from {$table} where id='{$id}'") 
		or die('Failed to select content for featuring '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$result = mysqli_fetch_array($query);
		
		$title = $result["{$title}"];
		$content = $result["{$content}"];
		$link = $_SESSION['prev_url'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO featured_content(`id`,`title`,`caption`,`content`,`link`) 
		VALUES('0','{$title}','{$caption}','{$content}','{$link}')") or die("Failed to save featuered content ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			session_message('success', 'Featured content saved !');
			redirect_to($_SESSION['prev_url']);
			}
		}
	
	
	}
	
 // end of featured_content functions file
 // in root/addons/featured_content/includes/functions.php
?>
