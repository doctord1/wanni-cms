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
//print_r($_SESSION);

#				 ADD YOUR CUSTOM ADDON CODE BELOW



# OUTPUT HEADER (OPTIONAL)
echo '<style>
.stButton .stFb, .stButton .stTwbutton, .stButton .stMainServices {
    background-image: url(/images/facebook_counter.png);
    background-repeat: no-repeat;
    display: inline-block;
    white-space: nowrap;
    font-family: Verdana,Helvetica,sans-serif;
    font-size: 11px;
    height: 24px;
    padding-top: 3px;
    padding-bottom: 3px;
    line-height: 16px;
    width: auto;
    position: relative;
}
</style>';

# ADD PAGES
function add_page() {

$page_type = trim(mysql_prep($_GET['type']));

if(isset($_GET['page_type']) ){
	$page_type = trim(mysql_prep($_GET['page_type']));
	}
if(isset($_GET['type']) ){
	$page_type = trim(mysql_prep($_GET['type']));
	}
if(isset($_GET['parent_id'])){
	$parent_id = mysql_prep($_GET['parent_id']);
	}
if(isset($_GET['section_name'])){
	$section_name = trim(mysql_prep($_GET['section_name']));
	}
if(isset($_GET['category'])){
	$category = trim(mysql_prep($_GET['category']));
	}
		

// show form
$form = '<div class="edit-form page_content ">
<form method="POST" action="'.BASE_PATH.'page/process.php" class="padding-10 ">
<input type="hidden" name="action" value ="insert" >
<input type="hidden" name="back_url" value ="'.$_SERVER['HTTP_REFERER'] .'" >'.
"<input type='hidden' name='redirect_to' value='{$_SESSION['prev_url']}'>".
		
'Title <input type="text" name="page_name" class="menu-item-form" placeholder="page name" required>
<hr>Visible:(Yes) <input type="checkbox" name="visible" value="1" checked="checked" class="checked"><hr>';
if(is_admin()){
$form = $form .'Menu Type: <select name="menu_type">
<option value="primary">Primary menu</option>
<option value="secondary">Secondary menu</option>
<option value="user">User menu</option>
<option value="none" selected="selected">None</option>
</select><hr>';
}
$form = $form .
'Position:
<input type="text" name="position" value="1" size="3" maxlength="3">
<br>(<em>Starting from 0, higher numbers will appear last</em>)
';


$form = $form.'<input type="hidden"  name="id" value="'.$_GET['id'].'">';
$form = $form.'<input type="hidden" id="'.$page_type.'" name="page_type" value="'.$page_type.'">';
$form = $form.'<input type="hidden"  name="parent_id" value="'.$parent_id.'">';
$form = $form.'<input type="hidden" id="'.$section_name.'" name="section" value="'.$section_name.'">';
$form = $form.'<input type="hidden" id="'.$category.'" name="category" value="'.$category.'">';
$form = $form .'<hr>Content:'.
'<a class="add-nicedit" onclick="addArea();">[ Show Editor]</a> &nbsp&nbsp <a class="remove-nicedit"  onclick="removeArea();">[ Hide Editor ]</a>'.
'<br><textarea name="content" id="content-area" size="8" data-uk-htmleditor="{markdown:true}"></textarea><br>
<em>to disableslideshow and show images in list, add "{show_images_in_lists} to the content"</em>';
if(url_contains('page/add/?type=contest')){
$form .= '<br>Reward: <input type="text" name="reward" value="">';
$form .= '
<hr>Duration :
<select name="duration">
<option>1 day</option>
<option>3 days</option>
<option>1 week</option>
<option>1 month</option>
</select>';
}


$form .= '<hr>Allow comments? :<input type="checkbox" name="allow_coments" value="yes"><em> tick for yes, leave empty for no</em>
<hr>Promote on homepage? :<input type="checkbox" name="promote" value="yes"><em> tick for yes, leave empty for no</em>
<hr><input type="submit" name="submitted" value="Add Page" class="submit">
</form></div>';

	if(isset($_SESSION['username'])){
	echo $form;	// End of Form  
	} else {deny_access();}
	
	
}




# LIST PAGES

function get_page_lists() {
	#print_r($_POST);
	
if(is_admin()){
	
	$show_more_pager = pagerize();
		
	$limit = $_SESSION['pager_limit'];

  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` ORDER BY `id` DESC {$limit}") 
  or die('Could not get data:' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $count = mysqli_num_rows($query);

  $pagelist = '';
  $pagelist = $pagelist . "<table class='table pages-list'><thead><th>&nbsp Page title</th><th>&nbsp Edit</th><th>&nbsp Delete</th></thead>";
  
  if($count < 1){status_message('alert', 'No more results here!');}
  
  while($row = mysqli_fetch_array($query)){
  	$pagelist = $pagelist 
  
  . '<tr><td class="spreadout">'
  . '&nbsp Title :&nbsp'
  .'<a href="' .BASE_PATH .'?page_name=' .$row['page_name'] .'&tid='.$row['id'].'"> ' 
  . ucfirst(urldecode(str_ireplace('-',' ',$row['page_name'])))
  . '</a>'
  .'</td><td>&nbsp &nbsp<a href="' 
  . BASE_PATH ."page/edit?" 
  . 'action='
  . 'edit_page&'
  . 'page_name='
  . $row['page_name']
  . '&tid='.$row['id'].'" '
  . '>edit </a>'
  . "</td>"
  . '<td>'
  . '&nbsp <a href="'
  . BASE_PATH ."page/process.php?" 
  . 'action='
  . 'delete_page&'
  . 'page_name='
  . $row['page_name'].'&tid='.$row['id']
  . '&deleted='
  . 'jfldjff7'
  . '" '
  . '>delete </a>'
  . "</td></tr>";
  }
  $pagelist = $pagelist . "</table>";
  
 
  echo $pagelist;
  
  echo $show_more_pager;
	
	} else {deny_access();}
 }
 

function add_new_what(){
	$context = query_string_in_url();
	if(empty($context)){
	
		$holder = '';
		
		if(is_admin()){
		echo "<div class='row'>
			<a href='".BASE_PATH."page/add/?type=page'><div class='whitesmoke col-md-5 col-xs-12'><h3> Page  </h3>
			Add regular site content that have menus like About us page etc.</div></a>";
			
		echo "
			<a href='".ADDONS_PATH."notifications'><div class=' col-md-5 col-xs-12'><h3> Notice </h3>
			Add site Notices. </div></a>";
		}
		
		if(is_logged_in()){
		$output = addon_is_active('fundraiser');
		if($output){
			echo "  
			<a href='".ADDONS_PATH."fundraiser?action=add-fundraiser'><div class='whitesmoke col-md-5 col-xs-12'><h3> Fundraiser </h3>
			 {$output['description']}.</div></a>";
			}
		//echo "
		//	<a href='".BASE_PATH."page/add/?type=blog'><div class='gainsboro col-md-5'><h3> Blog post </h3>
		//	Add regular site content that have menus like About us page etc.</div> </a>";
		
		
		echo "
			<a href='".ADDONS_PATH."company/?add_type=company'><div class='col-md-5 col-xs-12'><h3> Company </h3>
			Add Company profile where people can interact with your company or business.</div> </a>";
		
		echo "<a href='".ADDONS_PATH."ads'><div class='whitesmoke col-md-5 col-xs-12'><h3> +Create New Ads </h3>
			Advertise something. </div></a>";
		}
	if(is_admin()){
		echo "
			<a href='".BASE_PATH."page/add/?type=contest'><div class=' col-md-5 col-xs-12'><h3> Contest </h3>
			Contests are competitions that can be voted on and people can win prizes or cash</div>";
		}
		echo '</div></div>';
	}
}
       
# EDIT PAGES  

function edit_pages(){
	
//$page_type_query = mysql_query("SELECT `page_type_name` FROM `page_type`");
$path = $_SESSION['prev_url'];

if(url_contains('edit_') || ($row['author'] == $_SESSION['username'] || is_admin())){
$page = trim(urlencode(mysql_prep($_GET['page_name']))); 
$id = mysql_prep($_GET['tid']); 
#echo $block; // Testing
			 	
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "select * from page " .
	 'where id="' .
	 $id .
	 '" ' .
	 " limit 1") or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 $row = mysqli_fetch_array($query);
	 
	 #DETECT and switch between sections and page
	 
	 if(isset($_GET['page_name'])){
	 $target = trim(mysql_prep($_GET['page_name']));
	 $route = '?page_name=';
	 } else if (isset($_GET['section_name'])){
	 $target = trim(mysql_prep($_GET['section_name']));
	 $route = '?section_name=';
	 $end = 'section';
	 
	 } $id = $row['id'];
	 // get destination for saving
	 // $destination =  BASE_PATH .$route .$target ;
	 
	 // now we show the page edit form
	 echo "<span> You are editing "
	.'<a id="view-page" href="' .BASE_PATH .$route .urlencode($target) .'&tid='.$row['id'].'"><strong><big> ' 
  . $target .' [' .$end
  . ']</big></strong></a></span>';
	 	
  $menu_fetcher = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `menus` WHERE `menu_item_name`='{$page}' LIMIT 1") or die("MENu item fetching failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$menu_item = mysqli_fetch_array($menu_fetcher);
	 	
$form = '<div class="edit-form">' .
'<form method="POST" action="../process.php">'.
'<input type="hidden" name="action" class="form" value ="update">' .
'<input type="hidden" name="menu_item" value="' .$menu_item['menu_item_name'] .'">' .
'<input type="hidden" name="menu_id" value="' .$menu_item['id'] .'">' .
'<input type="hidden" name="path" value="' .$path .'">' .
'<input type="hidden" name="id"  value ="' .$id.'">' ;
if($_SESSION["{$page}_has_comments"] === 'yes'){
	$form = $form.'<em>** You cannot change page name because this page already has comments **</em>' .
	'<input type="hidden" name="page_name" value ="' .$row['page_name'] . '" >' ;
	} else {
$form = $form.'Title: <input type="text" name="page_name" value ="' .$row['page_name'] . '" ><hr>' ;
	}
//$form .= '<input type="hidden" name="destination" value="'.$destination.'">';
$form .= 'Visible: <input type="checkbox" name="visible" value="1" checked="checked"> (Yes)<hr>' .
'Menu Type *: <select name="menu_type" size="1">'; 

// get and set the selected menu value
$menu_result = mysqli_query($GLOBALS["___mysqli_ston"], "Select menu_type_name from menu_type order by id") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

while($menu = mysqli_fetch_array($menu_result)) {
	$form = $form . '<option value="' . $menu['menu_type_name'].'"';
if($menu['menu_type_name'] === $row['menu_type']) {
		 $form = $form . ' selected="selected" ';
		 } 
		 $form = $form .'">'. ucfirst($menu['menu_type_name']) . '</option>'; 
		 }
$form = $form .
'</select><hr>
Position: <input type="text" name="position" value="' . $row['position'] .
'" size="3" maxlength="3">(<em>Starting from 0, higher numbers will appear last</em>) ' ;



$form = $form.'<hr><input type="hidden" name="page_type"value="'.$row['page_type'].'" <br>Content: <a class="add-nicedit" onclick="addArea();">[ Show Editor]</a> &nbsp&nbsp <a class="remove-nicedit"  onclick="removeArea();">[ Hide Editor ]</a>
<br><textarea name="content" id="content-area" rows="5" data-uk-htmleditor="{markdown:true}">' .urldecode($row['content']) .'</textarea>
<br><em>to disableslideshow and show images in list, add "{show_images_in_lists} to the content"</em>' ;
	

$category_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from sections WHERE `is_category`='yes' order by position asc") or die("Failed to select sections!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$form = $form .'<br>Category : <select name="category" size="1">';
while($category= mysqli_fetch_array($category_query)) {
	$form = $form . "<option value='" . 
	$category['section_name'] . "'"; 
	 if($category['section_name'] == $_GET['category']) {
		 $form = $form . ' selected="selected" >'.strtoupper($category['section_name']) . '</option>'; 
		 }
		 else {
	$form = $form . '>'.strtoupper($category['section_name']) .'</option>';
	}
}
$form = $form .'</select>';

if($row['allow_comments'] === 'yes'){
	$comments = 'checked="checked"';
	} else { $comments = ''; }
if($row['promote_on_homepage'] === 'yes'){
	$promote = 'checked="checked"';
	} else { $promote = ''; }
$form = $form .'<br>

Allow comments ?:<input type="checkbox" name="allow_comments" value="yes" '.$comments.'><em>tick for yes, leave empty for no</em>
<br>Promote on homepage? :<input type="checkbox" name="promote" value="yes" '.$promote.'><em> tick for yes, leave empty for no</em>
<br><input type="submit" name="updated" value="Save page" class="submit">' .
'<input type="submit" name="deleted" value="Delete">' .
'</form></div>';

echo $form;


	}
 }


function add_page_type(){// deprecated
	//echo "<form method='post' action='./process.php'>
	//<input type='text' name='page_type' placeholder='page type name' value=''>
	//<input type='submit' value='Add page type' name='add_page_type' class='button-primary'>
	//<input type='submit' value='Delete page type' name='delete_page_type' class='button delete'>
	//</form> ";
	}
	

function delete_page_type(){
	if(isset($_GET['del_page_type']) && is_admin()){
		$id = mysql_prep($_GET['del_page_type']);
		$page_type = mysql_prep($_GET['page_type_name']);
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM page WHERE page_type='{$page_type}'");
		$num = mysqli_num_rows($query);
		
		//echo 'Num = ' . $num ; testing
		if(empty($num)){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM page_type WHERE id='{$id}'");
			if($query){
				session_message('success','Page type deleted !');
				redirect_to($_SESSION['prev_url']);
				}
			} else { session_message('alert',"You cannot delete [{$page_type}] page type, First delete all posts of this page type.");
				redirect_to($_SESSION['prev_url']); }
		}
	
	}
	
function list_page_types(){ // deprecated
	//echo "<h1>Page Types</h1>";
	//$query = mysql_query("SELECT * FROM page_type");
	//while($result = mysql_fetch_array($query)){
	//	echo $result['page_type_name'] ."<span class='tiny-text pull-right'><a href='".$_SESSION['current_url'] ."?del_page_type={$result['id']}&page_type_name={$result['page_type_name']}'>delete</a></span><hr>";
	//	}
	
	}	
	

function my_posts(){
	$more = $_GET['show_more_my_posts']; 
	if(is_logged_in() && is_user_page()){
	$user = trim(mysql_prep($_GET['user']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `page_name`, `page_type`, `destination` 
	FROM `page` 
	WHERE `author`='{$user}' LIMIT 0, {$more}");
	echo '<div class="padding-20">';
	while($result = mysqli_fetch_array($query)){
		echo '<a href="' .$result['destination'] .'">'.urldecode($result['page_name']) 
		.'</a><hr><div class="small"><em>'.$result['page_type'].'</em></div>';
		}	
		echo '</div>';	
	}
}


function get_page_types(){ //deprecated
	//echo "<em>Current page types are: </em> ";
	//$query = mysql_query("SELECT page_type_name FROM page_type");
	//while($result= mysql_fetch_array($query)){
		//echo ""."[".$result['page_type_name']."] ";
	//	}
	}

function add_child_page(){
	if($_SESSION['page_type'] =='page' || $_SESSION['page_type'] =='discussion' && is_author()){
	echo "<div class='padding-10 clear whitesmoke pull-right'><a href='".BASE_PATH.'page/add/?type=page&parent_id='.$_SESSION['page_id'].'&section_name='
	.$_SESSION['section_name'].'&category='.$_SESSION['category']."'>Add child page</a></div>";
	}
} 


function is_child_page(){
	if(!empty($_SESSION['parent_id']) || !empty($_GET['parent_id'])){
		return true;
		} 
}

function get_next_page(){
	if(isset($_GET['next'])){
	$id = $_GET['next'];
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `page_name` from page WHERE id='{$id}'");
	$num = mysqli_num_rows($query);
	
	$result = mysqli_fetch_array($query);
	$next_id = $result['id'] + 1;
	
		if(!empty($num)){
		echo '<div class="clear pull-right padding-10 light-blue margin-10 inline-block">NEXT : <a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'&next='.$next_id.'">'.str_ireplace('-',' ',urldecode($result['page_name'])).'</a></div>';
		}		
	}
}

function get_parent_page(){
	
	$parent_id = $_SESSION['parent_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`page_name` FROM page WHERE id='{$parent_id}'")or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_array($query)){
	
	echo '<div class="pull-right whitesmoke padding-10 inline-block margin-10"><strong>Parent :</strong><a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'">'.urldecode(str_ireplace('-',' ',$result['page_name'])).'</a></div>';
	}
}

function list_child_pages(){
	$id = $_SESSION['page_id'];
	if(!empty($_GET['tid'])){
		$id = mysql_prep($_GET['tid']);
		
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`page_name` FROM page WHERE parent_id='{$id}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if(!empty($num)){
		echo '<strong class="padding-10 inline-block">Child pages</strong><br>';
		while($result = mysqli_fetch_array($query)){	
			//if($result['parent_id']){	
			$next_id = $result['id'] + 1;
			echo '<li><a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'&next='.$next_id.'">'.ucfirst(str_ireplace('-',' ',$result['page_name'])).'</a></li>';
			//}
			}
		}
	}
}
	
function add_comment($subject = '',$reply='',$placeholder='',$button_text='',$upload_allowed=''){
remove_file();
if($subject == ''){
	$parent_type ='page';
	} else { $parent_type = $subject; }
$path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(isset($_GET['tid'])){
	$parent_id = mysql_prep($_GET['tid']);
	} else {
$parent_id = $_SESSION['page_id'];
}
$created = date('c');

//$reply = 'Join the Discussion';
#print_r($_POST);
	if(isset($_POST['add_comment'])){
		
		$subject_name = trim(mysql_prep($_POST['page_name']));
		$content = trim(mysql_prep($_POST['content']));
		$author = $_SESSION['username'];		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `comments`(`id`,`path`,`parent_type`,`parent_id`,`author`,`content`,`created`) VALUES 
		('0', '{$path}','{$parent_type}', '{$parent_id}', '{$author}', '{$content}','{$created}')")
		 or die ("Error inserting comments" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		 
		 if($query){ 
			 $q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` FROM comments WHERE `content`='{$content}' AND `created`='{$created}' LIMIT 0,1");
			 $result=mysqli_fetch_array($q);
			 
			 echo '<div class="success"> Saved Successfully!</div>'; }
		 
		 
	}
	
	if(isset($_GET['reply_to'])){
			$parent_id = trim(mysql_prep($_GET['parent_id']));
			$reply = "Reply to Comment # ".$_GET['reply_to'];
			} else if(empty($reply)){
			 $parent_id = '';
				$reply = 'Comments and Responses';}
	if($placeholder == ''){
		$placeholder = 'say something about this';
		}
	if($button_text == ''){
		$button_text = 'Add comment';
		}
	
	echo "<h2 align='center'>{$reply}</h2>";
	if(is_logged_in()){
		edit_comment();
			echo '<form method="post" action="'.$_SESSION['current_url'].'" class="whitesmoke padding-20" enctype="multipart/form-data">
			<input type="hidden" name="page_name" value="'.$page .'" placeholder="">
			<input type="hidden" name="parent_id" value="'.$parent_id.'">
			<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
			<!-- Name of input element determines name in $_FILES array -->';
			//~ if($upload_allowed =='true'){
			//~ echo '<input type="file" size="500" name="image_field" value="" placeholder="choose picture">';
			//~ }
			echo' <textarea name="content" size="5" placeholder="'.$placeholder.'"></textarea>
			<input type="submit" name="add_comment" value="'.$button_text.'" class="button-primary">
			</form>	';
	}	
		
	# SHOW MORE
	if(isset($_POST['comment_list_limit'])){
		$comment_limit = $_POST['comment_list_limit'];
	} else { $comment_limit = 10;}
	if(isset($_POST['comment_list_number_holder'])){	
		$step = $_POST['comment_list_number_holder'];
	} else{ $step = 0; }
	
	if(isset($_POST['clear_comment_list_values'])){
			unset($_POST);
			$number_holder = '';
			$comment_limit = 10;
			$step = 0;
			}	
			
		$limit = "LIMIT ". $step .", ".$comment_limit;
		$number_holder = $comment_limit + $step;
	
		echo '<table><tbody>';	
	// consider deprecating	
		if(isset($_GET['tid'])){
	$parent_id = mysql_prep($_GET['tid']);
	} else {
	$parent_id = $_SESSION['page_id'];
	}
	// ....
	
	//Fetch associated Photos

	
	$path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `comments` WHERE `parent_type`='{$parent_type}' AND `parent_id`='{$parent_id}' ORDER BY `id` DESC {$limit}")
	 or die("comment list error" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$count = mysqli_num_rows($query);
	while($result = mysqli_fetch_array($query)){
	//$photos = get_linked_image('','half','',$comment_id=$result['id'],$has_zoom='true');
		
			
	#Show Comments	
		echo "<tr><td class='table-message-plain'>";

		echo '<a href="'.BASE_PATH.'user/?user='.$result['author'] .'">'.$result['author'] .'</a>';
		echo "<div class='last-updated pull-right'> <time class='timeago' datetime='".$result['created'] ."'>".$result['created'] ."</time></div><br>";
		
		//~ if(!empty($photos)){
			//~ 
			//~ foreach ($photos as $photo){
				//~ echo $photo ;
				//~ }
			//~ 
			//~ }
		
		echo parse_text_for_output($result['content']);
		if((($result['author'] == $_SESSION['username']) || is_author() || is_admin())&&is_logged_in()){
		echo ' <span class="pull-right tiny-edit-text inline-block"> <a href="'.$_SERVER['REQUEST_URI']."&author=".$result['author'] . "&edit_comment=".$result['id'].'"> edit </a> &nbsp;&nbsp';
		echo '  <a href="'.$_SERVER['REQUEST_URI']."&author=".$result['author'] . "&delete_comment=".$result['id'].'"> delete </a></span><br>';
		}
		echo "</td></tr>";
		}
		echo '</tbody></table>';
		
	if($count < 1){status_message('alert', 'There are no responses to show!');}
	else { $_SESSION["{$page}_has_comments"] = 'yes';}
  		
	
		echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='comment_list_limit' value='10'>
		<input type='hidden' name='comment_list_number_holder' value='".$number_holder."'>
		<input type='submit' name='submit' value='show older' class='button-primary'>
		<input type='submit' name='clear_comment_list_values' value='reset'>
		</form></div>";
		
}


function edit_comment($comment_upload_allowed=''){
		if(isset($_GET['edit_comment'])){
		
			$ref_url = $_SESSION['prev_url'];
			$comment_id = mysql_prep($_GET['edit_comment']);	
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `content`, `parent_id` FROM comments WHERE id='{$comment_id}'") 
			or die("Problem editing comment " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			$result = mysqli_fetch_array($query);
			$content = $result['content'];
			$parent_id = $result['parent_id'];	
			
			if(isset($_POST['edit_comment'])){
				$ref_url = $_POST['ref_url'];
				$content = trim(mysql_prep($_POST['content']));

				$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE comments SET `content`='{$content}' WHERE id='{$comment_id}'") 
				or die("Error editing comment ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				
				if($query){
				session_message('success', 'Edited and saved!');
				upload_image('','','',$comment_id=$result['id']);	
				redirect_to($ref_url);
				}
			}
			
			echo '<h2>Editing </h2><form method="post" action="'.$_SESSION['current_url'].'" class="whitesmoke padding-20" enctype="multipart/form-data">
			<input type="hidden" name="parent_id" value="'.$parent_id.'">
			<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
			<!-- Name of input element determines name in $_FILES array -->';
			//if($comment_upload_allowed === 'true'){
			//echo '<input type="file" size="500" name="image_field" value="" placeholder="choose picture">';
			//}
			echo' <textarea name="content" size="5">'.$content.'</textarea>
			<input type="submit" name="edit_comment" value="Save" class="button-primary">
			</form>	';
			
			}
		
		
		
		
		
		
		
		
		
		
		
		
		// Modal 
		
		echo '<div class="modal fade" id="editModal" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;</button>
								<h2 class="modal-title" id="myModalLabel">Edit comment</h2>
								</div>
							<div class="modal-body">
							';
							
						
							
							
							echo '</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Submit changes</button>
							</div>
						</div><!-- /.modal-content -->
					</div>
				</div>';
		
	}

function get_num_comments($page_id=''){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) FROM comments WHERE parent_id='{$page_id}'") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_array($query)){
		
		if($result['COUNT(*)'] != 0){
		return $result['COUNT(*)'] .' comments';
		}
	}
}

function delete_comment(){
	$comment_author = $_GET['author'];
	$id = trim(mysql_prep($_GET['delete_comment']));
	if(isset($_GET['delete_comment']) && (is_author() || ($comment_author === $_SESSION['username'])) ){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `comments` WHERE `id`={$id}") 
		or die("Failed to delete comment " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query) {
			session_message("Comment deleted!");
			}
		redirect_to($_SESSION['prev_url']);
		}
	}

function show_front_promoted_posts(){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE `promote_on_homepage`='yes'") 
	or die("Failed to get Front promoted posts" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if($num<1){ echo "There are no promoted posts yet!";}
	
	while($result = mysqli_fetch_array($query)){
	echo "<a href='{$result['destination']}'>".str_ireplace('-',' ',$result['page_name'])."</a><br><hr>";
	}
}
 function remove_from_promoted_posts($page){
	 
	 }

function get_promoted_posts($string='', $limit='') {
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "SELECT * from page WHERE promoted_on_homepage ='yes' ORDER BY id DESC' LIMIT 10") or die("Failed to get promoted posts" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	echo "<div class='sweet_title'>Promoted Posts</br></div>";
     
   while($result= mysqli_fetch_array($query)){
   if ($section==='contest'){
     echo "<li><a href='" .ADDONS_PATH ."contest/?contest_name=" .$result['page_name'] ."&contest=yes'>" 
     . ucfirst($result['page_name']) ."</a><p>" ;
     if($_GET['page_name'] !=='home'){
     echo strip_tags(urldecode($result['content'])) ;} echo "</p></li>";
     } else if(isset($_GET['section_name'])) {
		 echo "<li><h3><a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."'>" 
		. ucfirst($result['page_name']) ."</a></h3> &nbsp &nbsp" ;
		echo urldecode(substr($result['content'],0,160))."..." ;
		echo "</li>";
     } else {
		  echo "<li><a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."'>" 
     . ucfirst($result['page_name']) ."</a>" ;
     if($_GET['page_name'] !=='home'){
     echo urldecode(substr($result['content'],0,160))."..." ;} echo "</li>";
		 }
  }
  	  echo "</ul></div>";
  	  
  	  echo $result['section_name'];
  	// show sections
  	
  	if($_GET['page_name'] ==='sections'){
  	get_grid_sections();  
	}
  
   	
}

function show_contributors(){
	
}

function get_page_content($page='home') {
	delete_comment();
	if(!isset($_GET['section_name'])){
	
	$_SESSION['page_context'] = 'post-';
	}
	$edit_page = url_contains('edit-');	
	
	if(!empty($_GET['page_name']) && !empty($_GET['tid'])){  
	$id = mysql_prep($_GET['tid']);
	  
	$query = mysqli_query($GLOBALS["___mysqli_ston"], 
	"select * from page " .
	'where id="' .
	$id .
	'" ' .
	" limit 1") or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	  } else if(isset($_GET['page_name']) && empty($_GET['tid'])){
		  
    $page = trim(urlencode(mysql_prep($_GET['page_name']))); 
    
    $query = mysqli_query($GLOBALS["___mysqli_ston"], 
    "select `id`, `page_name` from page " .
    'where page_name="' .
    $page .
    '" ' .
    " limit 1") or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    $num = mysqli_num_rows($query);
		if(!empty($num)){
		$temp = mysqli_fetch_array($query);
			if($temp['page_name'] != 'home'){
			$correct_path = BASE_PATH.'?page_name='.$temp['page_name'].'&tid='.$temp['id'];
			redirect_to($correct_path);
			}
		}
    }
    $num = mysqli_num_rows($query);
    
    if(!empty($num)){

					$result= mysqli_fetch_array($query);
					$_SESSION['id'] = $result['id'];
					$_SESSION['page_id'] = $result['id'];
					$_SESSION['author'] = $result['author'];
					$_SESSION['page_name'] = $result['page_name'];
					$_SESSION['page_type'] = $result['page_type'];
					$_SESSION['section_name'] = $result['section_name'];
					$_SESSION['category'] = $result['category'];
					$_SESSION['parent_id'] = $result['parent_id'];
					$_SESSION['fetched_page'] = true;

					if($_GET['page_name'] !== 'home' && $_GET['page_name'] !== 'contact'){ # DO NOT SHOW ALL THESE ON HOMEPAGE OR CONTACT PAGE
					  echo '  <div class="top-left-links"><ul>';

					  # IF OWNER OR MANaGER, THEN SHOW EDIt link 
					  if(isset($_SESSION['username'])){
					  
						if(($result['author'] === $_SESSION['username']) || ($_SESSION['role'] === 'manager' || $_SESSION['role'] === 'admin'))  {

						  echo '<li id="show_blocks_form_link" class="float-right-lists">
						  <a href="'.BASE_PATH .'page/edit/?action=edit_page&page_name='. $_SESSION['page_name'].'&tid='.$result['id'].'&section_name='
					.$_SESSION['section_name'].'&category='.$_SESSION['category'].'"> Edit page </a></li>';
						}
						if(!empty($_SESSION['role'])){
						echo'<li align="right" class="float-right-lists">
						<a href="'.BASE_PATH .'page/add"> +Add new</a></li>';
						}			
						echo '</ul>
						</div>';
					  }
					$share_buttons ='<div class="block">
										<!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="addthis_inline_share_toolbox"></div>
										</div>';
						

					  # GET PAGE IMAGES
					  $is_mobile = check_user_agent('mobile');
					  if($is_mobile){
					  $size='medium';
					  } else {
					  $size='large';
					  }
					  $pics = get_linked_image($subject='',$pic_size=$size,$limit='','',$has_zoom='true',$for_slideshow='true');


					  if($result['page_name'] !=='home'){
						if(!empty($result['last_updated'])){
						  $last_update = "<div class='last-updated'>Last Updated -  <time class='timeago' datetime='".$result['last_updated'] ."'>".$result['last_updated'] ."</time></div>";
						}else if(!empty($result['created'])){
						  $last_update = "<div class='last-updated'>Created -  <time class='timeago' datetime='".$result['created'] ."'>".$result['created'] ."</time></div>";
						}
					  }

					  // Echo the Page title


					  echo "<div class='sweet_title'>" . str_ireplace('-',' ',ucfirst(urldecode($result['page_name']))) .$last_update."</br></div>";
					  if(($_GET['page_name'] !== 'login') && (!$edit_page)  && ($_GET['page_name'] !== 'sections')){
						
						
					
						 echo "";
						 //show picture upload form for authors
						if(!url_contains('contact')){
						
						upload_no_edit();
						
						} //end picture upload form
						
						//$author_picture .= get_author_picture(); 

						 get_parent_page();
						 
						 echo "<table><tr>";
						 echo "<td class='whitesmoke'>";$user = show_user_pic($user=$result['author'] ,$pic_class='img-rounded');
						 echo $user['thumbnail'];
						 //get_author_picture(); 
						 echo"</td>";
						 
						#Show page images 
						echo "<td class='table-message-plain'>"; 
						//echo $share_buttons;  
						if(!empty($pics)){  
							
							$switch = strpos($result['content'],'show_images_in_lists');
							if($switch > 1){
								//echo "yes i am inlists ";
								
								show_images_in_list($images=$pics);

								} else { 
							
							  show_slideshow_block($pics);
							}
							
						} 
						
						
						#SHOW CONTENT ONLY WHEN USER IS NOT PERFORMING AN ACTION e.g VOTING
						if(! isset($_GET['action'])){ 	
							$content1 = $result['content'];
							$content = str_ireplace("{show_images_in_lists}",'',$content1);
							$content = str_ireplace("\{ \}",' ',$content);
							
						$content = parse_text_for_output($content);
						}
					echo $content;
					echo $share_buttons;
					
					
						
					//~ if(!url_contains('page_name=home') 
					//~ && !url_contains('page_name=contact') 
					//~ && !empty($result['category']) && $result['page_type'] == ''){
					//~ echo '<p class="padding-10 category"><strong class="">Category - <a href="'.BASE_PATH.'?section_name='.$result['category'].'&is_category=yes">'.$result['category'].'</a></strong></p>' ;
					//~ }
					if(addon_is_active('featured_content')){
						show_feature_this_link();
						}
					if($result['page_type'] !== 'page'){									
								if(addon_is_active('follow')){
								show_user_follow_button($child_name=$page,$parent='page'); 	
								follow($child_name=$page);
								unfollow($parent='page',$child_name=$page);
								}
							}
					//Show attachments
					show_linked_attachments();		
						
					//Show child pages
						echo '<div align="right" class="padding-10">';
							if(!is_child_page()){
							add_child_page(); 
							} // else {  echo '<em>Is a chid page</em>'; }
							get_next_page();
						echo '</div>';

					  }   
					}  list_child_pages();
						
				 
		} //end $num

			  else if(($result['page_name'] == 'sections') && (!$edit_page)) {
				// show sections
				get_grid_sections(); 

			  }  

			  else if((isset($_GET['section_name'])) && (!$edit_page)) {
				// show sections    
				
				get_section_content();  
				get_category_content(); //deprecating in favour of hashtags
				
				
			  } else {
					  status_message('alert','<h1><span class="glyphicon glyphicon-alert text-center padding-10"></span></h1>There must be a mistake, this page does not exist!');
						  if(isset($_GET['page_name'])){
						  $page_name_raw = trim(mysql_prep($_GET['page_name']));
						  $page_name = substr($page_name_raw,0,5);
						 //echo $page_name;
						  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM page WHERE page_name LIKE '%{$page_name}%' ORDER BY id DESC LIMIT 0, 10")or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
						  echo '<h3>Try These pages &raquo;</h3><ol>';
						  while($result = mysqli_fetch_array($query)){
							 
							  echo '<li><a href="' .BASE_PATH .'?page_name=' .$result['page_name'] .'&tid='.$result['id'].'"> ' 
							  . ucfirst(urldecode(str_ireplace('-',' ',$result['page_name'])))
							  . '</a></li><hr>';
							}
							echo '</ol>';
						}
					  
					  
					  go_back();
					  
					  }
					  
			echo "</td></tr></table>";
			 
			//if($_SESSION['fetched_page'] == true){// deprecating in favour of hashtags
			//add_to_category();	  
			//}
			
			
				
				
			  if($result['allow_comments'] === 'yes'){
				add_comment();
			echo '</div>';	
				if(!is_logged_in()){
				 log_in_to_comment(); 
				}
				
			  

			}
	unset($_SESSION['fetched_page']);
	
}


function get_author_picture(){
	if(!empty($_SESSION['author'])){
		$author = get_user_details($_SESSION['author']);
		
		if(empty($author['picture_thumbnail'])){
		$author['picture_thumbnail'] = default_pic_fallback('',$size = 'small');
		}
		
		//echo $author['picture'];// Testing purposes
		$output = array();
		$_SESSION['author_picture'] = '<a href="'.BASE_PATH .'user/?user='.$author['user_name'] .'">'.
	'<img class="thumbnail" src="'.$author['picture_thumbnail'].'">'.substr($author['user_name'],0,5).'...</a>';
		}
	echo $_SESSION['author_picture'];
	}
	
	
function my_authored_posts(){
$user = $_SESSION['username'];
$get_post_type = trim(mysql_prep($_GET['post_type']));
$show_more_pager = pagerize();

if(empty($_SESSION['pager_limit'])){
$limit = $_SESSION['pager_limit'];
}else {$limit = 'LIMIT 0, 10';}

if($get_post_type == 'page' || $post_type == 'blog' || $post_type == 'notice' || 'contest'){
$post_type = 'page';
$table = 'page';
} else {
$post_type = 'page';
$table= 'page';
}
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`{$post_type}_name`, `page_type` FROM `{$table}` WHERE `author`='{$user}' ORDER BY `id` DESC {$limit}") 
or die("Failed to Get my authored posts ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


echo '<div class="padding-10">';

while($result = mysqli_fetch_array($query)){
	echo "<a href='".BASE_PATH."?{$post_type}_name={$result['page_name']}&tid={$result['id']}'>".urldecode(str_ireplace('-',' ',$result['page_name'])) ."</a><hr>";
	}
	echo '</div>';
	echo $show_more_pager;
}



function start_a_discussion(){
if(is_logged_in()){
	//upload_image();
	if(isset($_GET['hashtag'])){
	$quicktag = '#'.$_GET['hashtag'];
	$category = '#'.trim(mysql_prep($_GET['hashtag']));
	}
	
	process_post_submission();
	$page_name= trim(mysql_prep($_GET['page_name']));
	$form = '<form class="gainsboro padding-10" action="'.$_SERVER['current_url'].'" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="insert">
	<input type="hidden" name="category" value="'.$category.'">
	<input type="hidden" name="page_name" value="'.$page_name.'">
	<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    <!-- Name of input element determines name in $_FILES array -->
	<input id="image_field" type="file" size="500" name="image_field" value="" placeholder="choose picture">
	<input id="image_title" type="text" size="500" name="image_title" value="" placeholder="Title / Caption">
	<input type="hidden" name="page_type" value="discussion">
	<textarea name="content" placeholder="Talk about this">'.$quicktag.'</textarea>
	<input type="submit" name="submit_discussion" value="Say it">
	</form>
	
	Quick links: 
	<span class="padding-5"><a href="'.ADDONS_PATH.'hashtags/?hashtag=complaints">#complaints</a> | </span>
	<span class="padding-5"><a href="'.ADDONS_PATH.'hashtags/?hashtag=suggestion">#suggestion</a> | </span>
	<span class="padding-5"><a href="'.ADDONS_PATH.'fundraiser">fundraisers</a> | </span>
	<span class="padding-5"><a href="'.ADDONS_PATH.'contest">contests</a> | </span>
	<span class="padding-5"><a href="'.ADDONS_PATH.'jobs">jobs(paid tasks)</a> | </span>
	<span class="padding-5"><a href="'.ADDONS_PATH.'company">companies</a></span>';
	
	echo $form;
	
	} else {log_in_to_continue();}
}

function get_notices(){
	$this_month = date('m');
	$today = date('d');
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `page_name`, `created` FROM page WHERE section_name='notices' AND visible=1 ORDER BY id DESC LIMIT 0,1") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($result = mysqli_fetch_array($query)){
		$post_date_month = substr( $result['created'],5,2);
		$post_date_day = substr( $result['created'],8,2);
		
		//echo $this_month .'<br>';
		//echo $today .'<br>';
		//echo $post_date_month .'<br>';
		//echo $post_date_day .'<br>';
		
		
		if(($this_month > $post_date_month) || ($this_month == $post_date_month)){
		$diff = $today - $post_date_day;
			if($diff < 5){
			$info = '! Notice : <a href="'.BASE_PATH.'?page_name='.$result['page_name'].'">'.str_ireplace('-',' ',$result['page_name']).'</a>';
			echo status_message('alert',$info);
			}
		
		}
	}
}

function get_discussion_content(){
		//if(is_logged_in()){
		$show_more_pager = pagerize($start='',$show_more='5');
		$limit = $_SESSION['pager_limit'];
		
		$category = trim(mysql_prep($_GET['section_name']));
		
		if($category){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE WHERE page_type='discussion' AND `category`='{$category}' AND `page_name`!='home' ORDER BY `id` DESC {$limit}")
		or die ("Failed to get category content ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else if(url_contains('page_name=home') || url_contains('page_name=talk')){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE page_type='discussion' ORDER BY `id` DESC {$limit}")
		or die ("Failed to get discussions ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else {
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` ORDER BY `id` DESC {$limit}")
		or die ("Failed to get discussions ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		echo '<section class=""><table class="table"><tbody>';
		
		 # GET PAGE IMAGES
      $is_mobile = check_user_agent('mobile');
      if($is_mobile){
      $size='medium';
      } else {
      $size='large';
      }
     
		
		while($result = mysqli_fetch_array($query)){
			
			if(string_contains($result['category'],'#')){
			$hashtag = $result['category'];
			//echo $hashtag .'sure-';  testing
			}else {$hashtag ='';}
			if(!empty($hashtag) && is_hashtag_participant()){
				$can_see = true;
				//echo 'can see :'.$result['content'].': '; testing
			} else if(empty($hashtag)){
				$can_see2 = true;
				//echo 'can see2 :'.$result['content'].': '; testing
			} 
			if($can_see || $can_see2){
			$user = show_user_pic($user=$result['author'] ,$pic_class='img-rounded');
			$page = mysql_prep($result['page_name']);
			$pics = get_linked_image($subject_id = $result['id'],$pic_size='half',$limit='4');
			
			$title = "<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>".'&nbsp;&raquo;Continue reading'.'</a>';
			
				$output=  "<tr><td class='gainsboro'>
			{$user['thumbnail']}</td><td class='table-message-plain'>" ;
			
			$output .= "<div class='last-updated pull-right'> <time class='timeago' datetime='".$result['last_updated'] ."'>".$result['last_updated'] ."</time></div>";
			
			$content = str_ireplace('{show_images_in_lists}','',substr(urldecode($result['content']),0,350));
			$output2 = "<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>";
		$output2 .= "";
		$comments_num = get_num_comments($result['id']);
		$output2 .= $comments_num ."</a>" ;
		
		
		$content2 = parse_text_for_output($content);
		if(string_contains($content2,'youtube.com/embed') 
		|| string_contains($content2,'youtu.be/') 
		|| string_contains($content2,'youtube.com/watch?') 
		|| string_contains($content2,'vimeo.com/')
	){
		$content2 .= " <span class='badge padding-10'>video</span>";
		}
		if($content2){
			echo  $output ."<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>";
			foreach($pics as $pic){
				echo $pic;
				}
			echo "</a>".'<br>'.$content2 .'<br>' .$output2 . $title ;
		}
		echo " </td></tr>" ;
		} // if is hashtag participant
	
	}echo '</tbody></table></section>';
		echo $show_more_pager;
	//}
}


function get_page_id($page_name){
	if(!empty($page_name)){
		$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$page_name}' ORDER BY id DESC LIMIT 0, 1") 
		or die("Cannot get page id".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		return $result['id'];
		}
		
	}


function process_post_submission(){
# Add pages form processing
$id = mysql_prep($_POST['id']); 
if(!empty($_POST['parent_id'])){
	$parent_id  = mysql_prep($_POST['parent_id']);
	}
$page_name = str_ireplace(' ','-',trim(mysql_prep(strtolower(($_POST['page_name']))))) ;

$page_type = trim(mysql_prep(strtolower($_POST['page_type']))) ;
$parent_id = trim(mysql_prep($_POST['parent_id'])) ;
if(empty($parent_id)){
	$parent_id  = 0;
	}

//
//print_r($_FILES); 


if(empty($_POST['visible'])) {
$visible = 0;}
else{
$visible = 1;
}


$action = htmlentities($_POST['action']);
$updated = htmlentities($_POST['updated']);
$submitted = trim(mysql_prep($_POST['submitted']));
$content1 = urlencode($_POST['content']);
$content = trim(mysql_prep($content1));

$position = htmlentities($_POST['position']);
$section_name = trim(mysql_prep($_POST['section']));
if(isset($_POST['category'])){
$category = trim(mysql_prep($_POST['category']));
}
$deleter = $_GET['action'];
$sent_delete = $_GET['deleted'];
$parent = $page_type;
$menu_type = $_POST['menu_type'];
$redirect_to = trim(mysql_prep($_POST['redirect_to']));

$author = $_SESSION['username'];
$editor = $_SESSION['username'];
$back_url = $_POST['back_url'];
$created = date('c');
$last_updated = date('c');
$image_title = trim(mysql_prep($_POST['image_title']));

$start_date = trim(mysql_prep($_POST['start_date']));
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



//process discussions

if(isset($_POST['submit_discussion'])){
$page_type = 'discussion';
$page_name1 = str_ireplace('+','-',substr($content,0,50) ."...");
$page_name = str_ireplace('#','',$page_name1);
$allow_comments = 'yes';

}

if(isset($page_type) && $page_type !== 'page'
&& $page_type !== 'blog' && $page_type !== 'notice' && $page_type !== 'discussion'){
	$destination = BASE_PATH."addons/{$page_type}/?{$page_type}_name={$page_name}";
	}
	
	if($page_type === 'blog'
	|| $page_type === 'notice' 
	|| $page_type === 'discussion'
	|| $page_type === 'page'
	|| empty($page_type)){
	$destination = BASE_PATH."?page_name={$page_name}&tid={$id}";
	}

	if($page_type === 'contest'){
	$destination = BASE_PATH."addons/{$page_type}/?{$page_type}_name={$page_name}&contest=yes";	
	}




if (isset($submitted) && $action ==='insert'){
	
	
//process photos
	global $r;

	if($r==='' && !url_contains('edit_')){
		$r = dirname(__FILE__);
		$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
		}
$submit =  $_POST['submit'];

$uploaddir = $r.'/uploads/files/';
$uploadfile = $uploaddir .$folder.'/'. basename($_FILES['image_field']['name']);

$m = str_ireplace('/regions/','',$uploadfile); // fixes a bugin upload_no_edit()
$uploadfile = $m;
//echo $uploadfile;
$path = BASE_PATH.'uploads/files/'.$folder.'/'. basename($_FILES['image_field']['name']);
$m = str_ireplace('/regions/','',$path);
$path = $m;
$rpath = $r.'/uploads/files/'.$folder.'/'. basename($_FILES['image_field']['name']);
$m = str_ireplace('/regions/','',$rpath); // fixes a bugin upload_no_edit()
$rpath= $m;

	# ONSUBMIT
	
		//print_r($_POST); die();
		
   $type = $_FILES['image_field']['type'];
   $name = basename($_FILES['image_field']['name']);
   
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
   
   

if(isset($_GET['tid'])){
	$tid = mysql_prep($_GET['tid']);
	$parent_id = $tid;
	} 

	if(isset($_FILES['image_field'])){
		if(!empty($image_title)){
			$parent = $image_title;
	} else {
	$parent = "pic".$name .date('d/m/Y') .time(); 
	}
	$move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
    }
	if($move ==1){
		
		$page_name = $parent;
		$parent2 = str_ireplace('#','%23',$parent);
		$small_path = resize_pic_small($pic=$rpath);
		$small_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $small_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$medium_path = resize_pic_medium($pic=$rpath);
		$medium_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $medium_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		
		$large_path = resize_pic_large($pic=$rpath);
		$large_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $large_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

		$created = date('c');
		$author = $_SESSION['username'];
		$destination_url = BASE_PATH."?page_name={$parent}&tid={$parent_id}";

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page`(`id`, `section_name`, `category`, `page_name`, `parent_id`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) 
		VALUES ('0', 'none', '{$category}', '{$parent}', 0, 'discussion', 'none', 0, '1', '{$content}', '{$created}', '{$created}', '{$author}', 0, 'yes', 'no', '{$destination_url}')")
		or die ("Page insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				 
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$parent}' LIMIT 0,1");
		$result = mysqli_fetch_array($query);
		$parent_id = $result['id'];
		if($query){ 
		$destination_url = BASE_PATH."?page_name={$page_name}&tid={$parent_id}";
		}

		$file_parent = $page_name . ' page';
	
		$comment_id = '0';
		$contest_entry_id = '0';
		$owner = $_SESSION['username'];
		//echo "<div class='message-notification'>File is valid, and was successfully uploaded.\n</div>";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `files`(`id`, `name`, `large_path`, `medium_path`, `small_path`, `original_path`, `parent`, `parent_id`, `type`, `destination_url`, `comment_id`, `contest_entry_id`, `owner`) 
		VALUES ('0', '{$name}', '{$large_path}', '{$medium_path}', '{$small_path}', '{$path}', '{$file_parent}', '{$parent_id}', '{$type}','{$destination_url}','{$comment_id}','{$contest_entry_id}','{$owner}')") 
		or die("Could not save image to DB!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$_SESSION['last_upload'] = $name;

		//echo $file_parent.'<br> '.$destination_url; die();
		redirect_to($destination_url);
		}
		
 
 else if($move != 1){
	if($_POST['page_type'] == 'discussion' && empty($duration)){
		//print_r($_POST); echo "iam here "; die();

		$created = date('c');
		$author = $_SESSION['username'];
		$destination_url1 = BASE_PATH."?page_name={$parent}&tid={$parent_id}";

		$page_name1 = str_ireplace('+','-',substr($content,0,50) ."...");
		$page_name = str_ireplace('#','',$page_name1);
		if(!empty($image_field)){
			$page_name = $image_field;
			}
		$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page`(`id`, `section_name`, `category`, `page_name`, `parent_id`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) 
		VALUES ('0', '{$section_name}', '{$category}', '{$page_name}', '{$parent_id}', '{$page_type}', '{$menu_type}', '0', '{$visible}', '{$content}', '{$created}', '{$last_updated}', '{$author}', '{$editor}', '{$allow_comments}', '{$promote_on_homepage}', '{$destination_url1}')")
		 or die ("Discussion insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		  
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$page_name}' AND destination='{$destination_url1}' LIMIT 0,1");
		$result = mysqli_fetch_array($query);
		$id = $result['id'];
		$destination_url = BASE_PATH."?page_name={$parent}&tid={$id}";

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE page SET destination='{$destination_url}' WHERE id='{$id}'") 
		or die("Failed to update destination url " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$talk_url = BASE_PATH."?page_name=talk";
		
		}
	}

		if($query ) {

		#process hashtags
		if(addon_is_active('hashtags')){
		$string = trim(mysql_prep($_POST['content']));
		process_hashtags($string,$path=$destination_url);
		}	
		  activity_record(
					$parent_id = $id,
					$actor=$author,
					$action=" created the {$page_type}",
					$subject_name = $page_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= BASE_PATH .'?page_name=' .$page_name,
					$date=$created,
					$parent='page'
					);
			
			if($menu_type !== 'none' && !empty($menu_type)){
			echo $page_name;
			//~ echo $_POST['menu_item_name'];
			//~ print_r($_POST);
			 //~ die();
			menu_item_create($name=$page_name,$type=$menu_type,$destination,$parent='page');
		 
			}	
					
			session_message('success', "{$page_type} saved successfully!");

			redirect_to($_SESSION['current_url']);
			}
		
	
	
	// IF POST IS A PAGE
	
	if($_POST['page_type'] == 'page' && empty($duration)){
		//print_r($_POST); echo "iam here "; die();

		$created = date('c');
		$author = $_SESSION['username'];
		$destination_url1 = BASE_PATH."?page_name={$parent}&tid={$parent_id}";

		$page_name = str_ireplace(' ','-',trim(mysql_prep(strtolower(($_POST['page_name']))))) ;

		$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page`(`id`, `section_name`, `category`, `page_name`, `parent_id`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) 
		VALUES ('0', '{$section_name}', '{$category}', '{$page_name}', '{$parent_id}', '{$page_type}', '{$menu_type}', '0', '{$visible}', '{$content}', '{$created}', '{$last_updated}', '{$author}', '{$editor}', '{$allow_comments}', '{$promote_on_homepage}', '{$destination_url1}')")
		 or die ("Discussion insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		  
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id from page WHERE page_name='{$page_name}' AND destination='{$destination_url1}' LIMIT 0,1");
		$result = mysqli_fetch_array($query);
		$id = $result['id'];
		$destination_url = BASE_PATH."?page_name={$parent}&tid={$id}";

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE page SET destination='{$destination_url}' WHERE id='{$id}'") 
		or die("Failed to update destination url " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query ) {

		#process hashtags
		if(addon_is_active('hashtags')){
		$string = trim(mysql_prep($_POST['content']));
		process_hashtags($string,$path=$destination_url);
		}	
		  activity_record(
					$parent_id = $id,
					$actor=$author,
					$action=" created the {$page_type}",
					$subject_name = $page_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= BASE_PATH .'?page_name=' .$page_name,
					$date=$created,
					$parent='page'
					);
			
			if($menu_type !== 'none' && !empty($menu_type)){
				menu_item_create($name=$page_name,$type=$menu_type,$destination,$parent='page');
		 
			}			
			session_message('success', "{$page_type} saved successfully!");

			redirect_to($destination_url);
			}
	
	}
	
}
	

# Edit form processing

if($updated ==='Save page'){
   
    $id = mysql_prep($_POST['id']);
    $page_type = mysql_prep($_POST['page_type']);
		
			$q = "UPDATE page SET section_name='{$section_name}' ,category='{$category}' ,page_name='{$page_name}',";

				$q = $q ." page_type='{$page_type}',";
			
			$q = $q ."  menu_type='{$menu_type}', 
			position=0, visible='{$visible}', content='{$content}', 
			last_updated='{$last_updated}', editor='{$editor}', 
			allow_comments='{$allow_comments}', promote_on_homepage='{$promote_on_homepage}', 
			destination='{$destination}' WHERE id='{$id}'"; 
			
			$update_query = mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("Post UPDATE failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
	 if($update_query) {
		 
			#process hashtags
			if(addon_is_active('hashtags')){
				$string = trim(mysql_prep($_POST['content']));
				process_hashtags($string,$path=$destination);
				}
	 	 	 
		  activity_record(
					$parent_id = $result['id'],
					$actor=$author,
					$action=" updated the {$page_type}",
					$subject_name = $page_name,
					$actor_path = BASE_PATH.'user/?user='.$author,
					$subject_path= BASE_PATH .'?page_name='. $page_name,
					$date=$created,
					$parent='page'
					);
		
		session_message('success', 'Page saved successfully! <br> Return to - <a href="'.BASE_PATH.'?page_name='.$page_name.'&tid='.$id.'">'.str_ireplace('-',' ',$page_name).'</a>');		
	 }	
	 $update_menu = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `menus` SET `menu_item_name`='{$page_name}',`menu_type`='{$menu_type}',`position`='{$position}',`visible`='{$visible}',`destination`='{$destination}',`parent`='{$parent}' WHERE `menu_item_name`='{$page_name}'") 
	 or die("FAiled to UPDATE menu" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 if(!$update_menu){
		menu_item_create($name=$page_name,$type=$menu_type,$destination,$parent='page');
		 }
	  
		redirect_to($destination);
}
#echo "deleter = " .$deleter ."<br> And sent_delete = " .$sent_delete ;   //testing

  // Now we check if delete is requested  
if(isset($deleter) && $sent_delete ==='jfldjff7'){
	if(is_admin()){
	$destination = BASE_PATH.'page';
	} else { $destination = BASE_PATH; }
	
	$del_page_name= $_GET['page_name'];
	$id = $_GET['tid'];
	//echo " id is " . $id . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from page WHERE id='{$id}'") 
	or die('Could not delete the specified page! '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	session_message('success', $del_page_name ." deleted successfully!!");
	
	$delete_menu_query=mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$del_page_name}'") 
	or die("Menu item deletion failed1" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	redirect_to($destination);
	
} else if(isset($_POST['deleted'])){
		
	$del_page_name= $page_name;
	if(is_admin()){
	$destination = BASE_PATH.'page';
	} else { $destination = BASE_PATH; }
	$id = $_POST['id'];
	//echo " id is " . $id . ' and delete button was pressed'; // testing
	$delete_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE from page WHERE id='{$id}'") 
	or die('<div class="alert">Could not delete the specified page!</div>'. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) ;
	
	session_message('success', $del_page_name ." deleted successfully!!");
	
	$delete_menu_query=mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$del_page_name}'") 
	or die("Menu item deletion failed1" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	redirect_to($destination);
}
	


if($add_page_type){
	$clear = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `page_type` WHERE `page_type_name`='{$page_type}'");
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `page_type`(`id`,`page_type_name`) VALUES('0','{$page_type}')")
	or die("Page type  Insert failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($q){
		session_message('success','Page type ADDED successfully');
		}
		redirect_to($_SESSION['prev_url']);
	}
	
if($delete_page_type){
	$clear = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `page_type` WHERE `page_type_name`='{$page_type}'") 
	or die("FAiled to delete page type " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($clear){
		session_message('success','Page type DELETED successfully');
		}
		redirect_to($_SESSION['prev_url']);
}
}

?>
