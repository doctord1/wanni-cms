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
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW


function add_section($is_category = ''){ 
	
	if( is_admin()){
	// if $is_category is equal to yes, 
	// the section created will be a category.
	
	$add_section = $_POST['add_section'];
	$section_name = trim(mysql_prep($_POST['section_name']));
	$position = trim(mysql_prep($_POST['position']));
	$description = trim(mysql_prep($_POST['description']));
	$visible = trim(mysql_prep($_POST['visible']));
	$parent_post_type = strtolower(trim(mysql_prep($_POST['parent_post_type'])));
	$is_category = strtolower($_POST['is_category']);
	
	
	if($_POST['add_section'] ==='Add section'){
	$add_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `sections`(`id`, `section_name`, `position`, `description`, `visible`, `parent_post_type`, `is_category`) 
	VALUES ('0', '{$section_name}', '{$position}', '{$description}', '{$visible}', '{$parent_post_type}', '{$is_category}')") or die ("Section creation failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	
	if($add_query){
		session_message('success', 'Section successfully created!');
		$destination = BASE_PATH ."sections";
		header("Location: $destination"); exit;
		
		}
		
	$add_section_form = '<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
					<br>Section Name: <br><input type="text" name="section_name" placeholder="Type in Section name here">
					<br>position: <br><input type="number" name="position" value="1" size="2" maxlenght="2">
					<br>Description: <br><input type="text" name="description" placeholder="Description here">
					<br>Will this be a category? 
					<br><select name="is_category">
					<option value="no">No</option>
					<option value="yes">Yes</option>
					</select>
					<br>If this is a category, what is the Parent Post Type?
					<br><input type="text" name="parent_post_type" placeholder="eg fundraiser, contest, page etc">
					<br>Visible:(1=yes, 0=no) <br><input type="number" name="visible" placeholder="Should this section be visible?">
					<br><input type="submit" value="Add section" name="add_section" class="submit">
					</form><br>';
	echo $add_section_form;
	
	} else { 
		session_message('error','You have been redirected because you are not authorized to go there!');
		redirect_to(BASE_PATH.'?page_name=home');
		}
}
	
	
function section_create($section_name='',$description='',$path=''){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `sections`(`id`, `section_name`, `position`, `description`, `visible`, `parent_post_type`, `is_category`) 
	VALUES ('0','{$section_name}','1','{$description}','1','','no')")
	 or die('Problem with section create' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 if($query){
		status_message('success', "{$section_name} section created");
		}
	}

function section_delete($section_name=''){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM company WHERE section_name='{$section_name}'"); 
	if($query){
		status_message('alert', "{$section_name} section removed");
		}
	}
	
function list_sections(){
	if (! isset($_GET['section_name'])){
		
	# Do not show on actual menu section
	
		$list_sections = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sections` WHERE `is_category`!='yes'") or die("Menu selection failed") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		if($_SESSION['role'] ==='admin' || $_SESSION['role'] ==='manager'){
			
			$list = "<div class='col-md-6 padding-20'><h2>Sections</h2> <ol>";
			while($row = mysqli_fetch_array($list_sections)){
				if($row['is_category'] === 'yes'){
					$type = '<em>Category</em>';
				} else { 
					$type = '<em>Section</em>';
				}
				
				$list = $list . "<li><big> <a href='" .BASE_PATH ."sections?action=edit-&section_name=" .$row['section_name']  ."'>".ucfirst($row['section_name']) ." {$type} </a></big>" ;
				$list = $list . "&nbsp &nbsp| &nbsp &nbsp<a href='".BASE_PATH ."sections?delete=".$row['id'] ."'".'><em>Delete</em></a></li><hr>';
				
				} $list = $list . "</ol></div><br>";
				echo $list;
					}
	 
	}
}

	
function delete_section(){
	
#check for permission
if($_SESSION['role'] ==='admin' || $_SESSION['role'] ==='manager'){
	
	#then check url value
	if(isset($_GET['delete'])){
		
		$id = $_GET['delete'];
		$delete_section = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `sections` WHERE `id`='{$id}'") 
		or die("Section deletion failed") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		if($delete_section){
			
			session_message("alert", "Section deleted!");
			}
		}	
	}
}


function edit_section(){
		if($_GET['action'] === 'edit-'){
			
			$section = $_GET['section_name'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM sections WHERE `section_name`='{$section}'") 
			or die("failed to get section details" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			while($result = mysqli_fetch_array($query)){
			
			echo "<div class='main-content-region'><h1>Edit " .$result['section_name']. " Section</h1>
				<form action='./process.php' method='post'>
				<input type='hidden' name='id_holder' value='".$result['id'] ."'
				Section name: <br><input type='text' name='section_name' value='" .$result['section_name'] ."'><br>
				Position : (<em>higher numbers come last</em>)<br><input type='text' name='position' value='".$result['position'] ."'><br>
				Visible :(<em>1= yes / 0 = no</em>)<br><input type='text' name='visible' value='".$result['visible'] ."' maxlength='3'><br>
				Description :<br><textarea name='description'>".$result['description'] ."</textarea><br>
				Will this be a category? 
					<br><input type='text' name='is_category' value='{$result["is_category"]}'>
					<br>If this is a category, what is the Parent Post Type?
					<br><input type='text' name='parent_post_type' value='{$result["parent_post_type"]}'>
					
				<br><input type='submit' name='edit_section' class='submit' value='Save'></form></div>";
				;
				
				}
			
				echo "<div class='right-sidebar-region'>".
		 remove_file();
		 upload_image()."</div>"; // upload form 
						
		}
	}
	

  
# Show the sections GRID 

function get_grid_sections() {
	
	if(! isset($_GET['section_name'])){ // show only when no section is selected
	 	 
	 $sections_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from sections where visible=1 order by position asc")
	 or die("Failed to get sections!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 

   	//if condition passes then show grid
   	 echo "<section class='gainsboro'>"; 
   	while($section= mysqli_fetch_array($sections_query)) {
  
  $title = substr($section['section_name'],0,28);
  $name = $section['section_name'];
  $path = $section['path'];
  $image_name = $name. " section";
  $desc = substr($section['description'],0,60);
  $is_mobile = check_user_agent('mobile');
	
		//	$pic = get_linked_image($subject=$name .' section',$pic_size='small',$limit=1);
		//print_r($pic);
		// show only sections that are not categories
		if($section['is_category'] !== 'yes'){
			echo "<span class='inline-block padding-10 whitesmoke margin-10'>" ;
			
			if(!empty($path)){
				echo '<a href="'.$path.'">'.ucfirst($name).'</a></span>';
			} else {
				if($name==='shop'){
					echo "&nbsp;&nbsp;<a href='" .ADDONS_PATH ."shop/catalog'>" . ucfirst($title) ."</a></span>";
				}else{
					echo "&nbsp;&nbsp;<a href='" .BASE_PATH ."?section_name={$name}'>". ucfirst($title) ."</a></span>";
					}
			}
		} 
	}
		
		}echo "</section>";
}
    
 
function section_highlights(){
	 
	 echo '
		 <div class="col-md-4 col-xs-12 blue padding-10">
		 <a href="'.BASE_PATH.'/?section_name=news"><div class="text-center highlight">News</div></a>';
		 get_latest_section_content_in_lists('news',$pic_shown='true');
		 echo '</div>';
	 
	 echo '
	 <div class="col-md-4 col-xs-12 green padding-10">
	 <a href="'.BASE_PATH.'/?section_name=music"><div class="text-center highlight">Music</div></a>';
	 get_latest_section_content_in_lists('music','false');
	 echo '</div>';
	 
	 echo '
	 <div class="col-md-4 col-xs-12 red  padding-10">
	 <a href="'.BASE_PATH.'/?section_name=videos"><div class="text-center highlight">Videos</div></a>';
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM page WHERE section_name='videos' LIMIT 1") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 while ($result = mysqli_fetch_array($query)){
	  echo "<div class='text-center'>" ;
		 
		$content = substr(urldecode($result['content']),0,160);
		echo convertYoutube(parse_text_for_output(str_ireplace("show_images_in_lists",' ',$content)),$width = 180);
		echo "</a>" ;
		echo "</div>";
		}
	 get_latest_section_content_in_lists('videos','false');
	 echo '</div>
	 ';
	 }
 


function get_section_description(){
	$section_name = trim(mysql_prep($_GET['section_name']));
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `description` FROM `sections` WHERE `section_name`='${section_name}' LIMIT 1");
$result = mysqli_fetch_array($query);
echo "<p align='center'><em> ".$result['description']."</em></p>";
	
	}

# Show content is sections

function get_section_content($string='', $limit='') {
		
  if(isset($_GET['section_name'])) {
	  $section = trim(mysql_prep($_GET['section_name'])); 
  }	else if($string !== ''){
  	$section = $string;
  }
  
  if($limit !==''){
	  $limit = " LIMIT {$limit}";
	  } else { $limit = " LIMIT 20"; }
  
	 $result = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "SELECT * from page " .
	 'WHERE section_name="' .
	 $section .
	 '" ORDER BY id DESC' .
	 $limit) or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	   # IF OWNER OR MANaGER, THEN SHOW EDIt link 
   if(isset($_SESSION['username']) && $style !== 'block'){
	   
	   #If section is fundraiser, show add fundraiser link
	   if($_GET['section_name']==='fundraiser'){
		   
		echo '<li id="add_page_form_link" class="float-right-lists">
		<a href="'.ADDONS_PATH .'fundraiser?action=add-fundraiser">Add Fundraiser </a></li>';
		   }
		   
		if($sel_page['author'] === $_SESSION['username'] || $_SESSION['role'] === 'manager' || $_SESSION['role'] === 'admin')  {
	 
	 	 
	 if(isset($_GET['page_name'])){
		 $edit_route = $_GET['page_name'];
		 } else if (isset($_GET['section_name'])){
		 $edit_route = $_GET['section_name'];
		}
			 echo ' <ul> 
						<li align="right" class="float-right-lists">
					<a href="'.BASE_PATH .'sections/?action=edit-&section_name='. $section.'"> Edit Section </a></li>';			
		   }
		  if(!empty($_SESSION['role']) && $_SESSION['role'] !== 'anonymous'){
			  	echo '<li id="show_blocks_form_link" class="float-right-lists">
					<a href="'.BASE_PATH .'page/add"> Add a new page </a></li>';
			  }
			  echo '</ul>';
	}
	 
	 // Echo the Page title
	 if($section === 'front'){
		
	 } else {
		 
		 if($_GET['is_category'] === 'yes'){
			 $title = "".ucfirst($section) ." ";
			// $teaser = urldecode(substr($sel_page['content'],0,160))."..." ;
			 } echo "</li>";
		 
		if($_GET['is_category'] !== 'yes') {
			$title = "Posts in ".ucfirst($section) ." ";
			}
		echo "<div class='sweet_title'>" .$title ."</br></div>";
     }
     
     $pic = get_linked_image($subject=$name.' section',$pic_size='small',$limit=1);
     get_section_description();
     
   while($sel_page= mysqli_fetch_array($result)){

   if ($section =='contest' && addon_is_active('contest')){
	   get_contest_lists();
     	
	 
     }
     
      else if(isset($_GET['section_name'])) {
		 echo "<div class='margin-10 gainsboro'><h3><a href='" .BASE_PATH ."?page_name=" .$sel_page['page_name'] ."'>" 
		. str_ireplace('-',' ',ucfirst($sel_page['page_name'])) ."</a></h3>" ;
		$content = substr(urldecode($sel_page['content']),0,160);
		echo parse_text_for_output($content);
		echo "</div>";		
     }
	
  }
  	  
  	  echo $sel_page['section_name'];
  	// show sections
  	
  	if($_GET['page_name'] ==='sections'){
  	get_grid_sections();  
	} 	
}



function get_services_section(){
	if(($_GET['action'] == 'get_services' && (!isset($_GET['page_name']))) || isset($_GET['page_name'])){

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE `section_name`='Services' ORDER BY `id` DESC") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	
	while($result = mysqli_fetch_array($query)){
		echo '<div class="transparent-white margin-10 padding-10"><a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'"><h2>'.ucfirst(str_ireplace('-',' ',$result['page_name'])).'</h2></a></div></li>';
		}
		
	}
	
}


function get_latest_section_content_in_lists($section ='',$pic_shown=''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], 
	 "SELECT * from page " .
	 'WHERE section_name="' .
	 $section .
	 '" ORDER BY id DESC' .
	 $limit) or die("Failed to get selected page" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 
	 
	 echo '<ul>';
	 while ($result = mysqli_fetch_array($query)){
		 echo '<li><a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'">';
			 echo str_ireplace('-',' ',ucfirst($result['page_name'])).'</a></li>';
			 
		 if($pic_shown == 'true'){
			echo '<a href="'.BASE_PATH.'?page_name='.$result['page_name'].'&tid='.$result['id'].'">'; 
			$pic = get_linked_image($subject=$result['id'],$pic_size='medium',$limit='1','','','');
			//print_r($pic);
		 echo $pic['0'] .'</a>';
		 $pic_shown = 'false';
		// echo '<li>'. str_ireplace('-',' ',ucfirst($result['page_name'])).'</a><br>';
		 } 
		 
		 if($section == 'videos'){
			 $content = urldecode(substr($result['content'],0,160)) .'';
			echo convertYoutube(parse_text_for_output(str_ireplace("show_images_in_lists",' ',$content)),$width = 250);
		
			 } else { echo urldecode(substr($result['content'],0, 250)) .'...</li>';}
		 
	} echo '</ul>';
	
}



function get_category_content(){

		$show_more_pager = pagerize();
		$limit = $_SESSION['pager_limit'];
			
		if(!empty($_GET['section_name']) && $_GET['is_category']==='yes'){
		$category = trim(mysql_prep($_GET['section_name']));
		
		//Get FUNDRAISERS
		if(addon_is_active('fundraiser')){
			get_fundraiser_lists($category);
			}
		//Get contests
		if(addon_is_active('contest')){
			get_contest_lists($category);
			}
		
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `page` WHERE `category`='{$category}' ORDER BY `id` DESC {$limit}")
		or die ("Failed to get category content ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
	echo '<section class=""><table class=""><tbody>';
			 # GET PAGE IMAGES
      $is_mobile = check_user_agent('mobile');
      if($is_mobile){
      $size='medium';
      } else {
      $size='large';
      }
     
		
		
		while($result = mysqli_fetch_array($query)){
			$pic = show_user_pic($user=$result['author'] ,$pic_class='img-rounded');
			$output=  "<tr><td class=''>
			{$pic['thumbnail']}</td><td class='table-message-plain'>
			<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>".str_ireplace('-', ' ',urldecode(ucfirst($result['page_name']))) .'</a><br>';
			//$title = "<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>".$result['page_name'] .'</a><br>';
			
			$page = mysql_prep($result['page_name']);
			$pics = get_linked_image($subject_id = $result['id'],$pic_size='large',$limit='1');
			$content = substr(urldecode($result['content']),0,350);
			 $output2 = "<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>";
		$output2 .= "";
		$comments_num = get_num_comments($result['id']);
		$output2 .= $comments_num ."</a>" ;
		
		
		$content2 = parse_text_for_output($content);
		if($content2){
			echo  $output ."<a href='" .BASE_PATH ."?page_name=" .$result['page_name'] ."&tid=".$result['id']."'>".$pics[0]."</a>".'<br>'.$content2 .'<br>' .$output2; ; 
		echo" <span class='grey-text'> <em>".$result['page_type']."</em></span></td></tr>" ;
		}
		
		
			}echo '</tbody></table></section>';
		} echo $show_more_pager;
}



function add_to_category(){
	if((is_author() || is_admin()) 
	&& !url_contains('section_name') 
	&& !url_contains('page_name=sections')
	&& !url_contains('page_name=home')
	&& !url_contains('page_name=contact')
	&& !url_contains('search')){
	
		if(!empty($_GET['page_name'])){
			$subject = trim(mysql_prep($_GET['page_name']));
			$target = 'page';
		} else if(!empty($_GET['fundraiser_name'])){
			$subject = trim(mysql_prep($_GET['fundraiser_name']));
			$target = 'fundraiser';
		} else if(!empty($_GET['contest_name'])){
			$subject = trim(mysql_prep($_GET['contest_name']));
			$target = 'contest';
		}
		
		if($_POST['submit'] == 'categorize'){
			$category = $_POST['category'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `{$target}` SET `category`='{$category}' WHERE `{$target}_name`='{$subject}'")
			or die("Categorization failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($query){ session_message('success',"Added to {$category} category"); 
				redirect_to($_SESSION['current_url']);
				}
			
			}

			// Show form
		echo "
		<div class='text-center beige categorize-pullout margin-10'  style='cursor: pointer'>Change category</div>
		<div class='text-center categorize-close' style='cursor: pointer; background-color: whitesmoke; padding: 5px;' >Close x</div>
		<div class='margin-10 gainsboro categorize-holder'>
		<h3>If you are the author of this post, you may set its category below</h3>
		<form action='".'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."' method='post'>
		<select name='category'>";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `section_name` FROM `sections` WHERE `is_category`='yes'")
		or die("Category selection failed " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		while($result = mysqli_fetch_array($query)){
			echo "<option>{$result['section_name']}</option>";
			}
		echo "'</select>
		<input type='submit' name='submit' value='categorize'> 
		</form>
		</div>";
		
	}
}


function get_categories(){

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT section_name FROM sections WHERE is_category='yes'");
	$output = array();
	while($result = mysqli_fetch_assoc($query)){
		$output[] = $result['section_name'];
		}
	return $output;
	}

	
function show_all_categories(){
	
		if(url_contains('addons/contest/')){
		echo '<h2>Contest categories</h2>';
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sections` WHERE `is_category`='yes' AND `parent_post_type`='contest'") 
		or die("Could not fetch contest categories " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else
	if(url_contains('addons/fundraiser/')){
		echo '<h2>Fundraiser categories</h2>';
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sections` WHERE `is_category`='yes' AND `parent_post_type`='fundraiser'") 
		or die("Could not fetch fundraiser categories " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else {
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sections` WHERE `is_category`='yes'") or die("Menu selection failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
		
		$list = "<div class=''> ";
	while($result = mysqli_fetch_array($query)){
		if($result['is_category'] === 'yes'){
			//$type = 'category';
			$url_suffix = '&is_category=yes';
		} else if($result['is_category'] !== 'yes') { 
			$type ='section';
			$url_suffix = '&is_category=no';		
		}
			$list = $list . "<span> <a href='" .BASE_PATH ."?section_name=" .urlencode($result['section_name'])  .$url_suffix ."'>".ucfirst($result['section_name']) ." </a> </span>&nbsp;" ;
	} 
	echo $list ."</div>";	
}


function list_categories(){
	
	//if(url_contains('?page_name=')){
	//	$query = mysql_query("SELECT * FROM `sections` WHERE `is_category`='yes' AND `parent_post_type`='fundraiser'") 
	//	or die("Could not fetch contest categories " . mysql_error());
	//	}
		
	if (! isset($_GET['section_name'])){
		
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sections` WHERE `is_category`='yes'") or die("Menu selection failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($_SESSION['role'] ==='admin' || $_SESSION['role'] ==='manager'){
			
			$list = "<div class='col-md-6 padding-10'>
			<h2>Categories</h2> <ol>";
			while($result = mysqli_fetch_array($query)){
				if($result['is_category'] === 'yes'){
					$type = '<em>Category</em>';
				
				$list = $list . "<li><big> <a href='" .BASE_PATH ."sections?action=edit-&section_name=" .$result['section_name']  ."'>".ucfirst($result['section_name']) ." {$type} </a></big>" ;
				$list = $list . "&nbsp &nbsp| &nbsp &nbsp<a href='".BASE_PATH ."sections?delete=".$result['id'] ."'".'><em>Delete</em></a></li><hr>';
				
				} 
				
			}$list = $list . "</ol></div><br>";
			echo $list;
		}
	}
}


 // end of sections functions file
 // in root/sections/includes/functions.php
?>
