<?php 
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page();
#					- Template Ends -
#=======================================================================


	if(!empty($_GET['delete_gallery_image'])){
		$id = trim(mysql_prep($_GET['delete_gallery_image']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `gallery` WHERE `gallery`.`id`={$id}") 
		or die("Select path failed " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$result = mysqli_fetch_array($query);
		
		if(($result['owner'] === $_SESSION['username']) || is_admin()){
		$path = $result['path'];

		unlink($path);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `gallery` WHERE `id`='{$id}'") 
		or die("DElete pic failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		session_message('alert', 'picture deleted successfully!');
		redirect_to($_SERVER['HTTP_REFERER']);
		}
	
	}


?>



