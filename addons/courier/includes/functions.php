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
require_once($r .'includes/functions.php'); #do not edit



#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW
?>

<?php 

function track(){
	

	$track = "<h2>Tracking</h2><form action='". $_SERVER['PHP_SELF'] ."' method='post'>
	<input type='text' name='tracking_no' placeholder='Enter tracking number'>
	<input type='submit' name='submit' value='submit' class='submit'>
	</form>";
	echo $track;
		
		
	$submitted = $_POST['submit'];
	
	if(isset($_POST['tracking_no'])){
	$tracking_no = htmlentities($_POST['tracking_no']);
		}
	$track_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM courier WHERE tracking_no='{$tracking_no}'") 
	or die("No package found!!");
		
		while($result=mysqli_fetch_array($track_query)){
			if($result['tracking_no'] === $_POST['tracking_no']){
				
			echo "<div class='message-notification' target='_BLANK'>  1 Package found! !</div>";
			echo "<div class='result'><table class='table'>
			<th>Sent by</th><th>From</th><th>Destination</th><th>Description</th><th>status</th>
				<tr><td>" .$result['sender_name'] ."</td><td>".$result['sender_location'] 
				."</td><td>".$result['reciever_location']. "</td><td>" . $result['description'] 
				."</td><td>". $result['status'] ."</td></tr>";
			$package = $package . "</table></div>";
				
				echo $package;
				
				} else {echo "You have entered a WRONG tracking number!";}
		
		}
		
	
		
	}


function add_package() {
	


$id = '';
$package_name = htmlentities($_POST['package_name']);
$sender_name = htmlentities($_POST['sender_name']);
$reciever_name = htmlentities($_POST['reciever_name']);
$sender_location = htmlentities($_POST['sender_location']);
$submitted = htmlentities($_POST['submitted']);
$reciever_location = htmlentities($_POST['reciever_location']);
$description = htmlspecialchars($_POST['description']);
$description = mysql_prep($_POST['description']);
$status = htmlentities($_POST['status']);
$tracking_no = htmlentities($_POST['tracking_number']);



if (isset($_POST['submitted'])){
		
		$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `courier`(`id`, `package_name`, `sender_name`, `reciever_name`, `sender_location`, `reciever_location`, `description`, `status`, `tracking_no`) 
		VALUES ('{$id}', '{$package_name}', '{$sender_name}', '{$reciever_name}', '{$sender_location}', '{$reciever_location}', '{$description}', '{$status}', '{$tracking_no}')")
		 or die ("Database insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	 if($insert_query) {
		echo "<div class='message-notification'> Package saved !</div>";
		}		
}

#SHOW FORM
$form = '<h1>Add Package</h1><div class="add-form">
<form method="POST" action="'.$_SERVER['PHP_SELF']. '"><br>
Package name : <br><input type="text" name="package_name" size="20" maxlength="20" value ="" placeholder="Package name"><br>
Description:<br><textarea name="description" rows="7"></textarea><br>
Sender: <br><input type="text" name="sender_name" size="20" maxlength="20" class="menu-item-form" placeholder="Sender name" ><br>
Receiver: <br><input type="text" name="reciever_name" size="20" maxlength="20" class="menu-item-form" placeholder="Reciever name" ><br>
Sender location: <br><input type="text" name="sender_location" size="20" maxlength="20" class="menu-item-form" placeholder="city,country" ><br>
Reciever location: <br><input type="text" name="reciever_location" size="20" maxlength="20" class="menu-item-form" placeholder="city, country" ><br>
Sender: <br><input type="text" name="sender_name" size="20" maxlength="20" class="menu-item-form" placeholder="Sender name" ><br>

Status: <br><input type="text" name="status" size="10" maxlength="20" class="menu-item-form" placeholder="status" ><br>
Tracking number: <br><input type="text" name="tracking_number" size="10" maxlength="10" class="menu-item-form" placeholder="Tracking number" ><br>
<br><input type="submit" name="submitted" value="Add Shipment" class="submit">
</form></div>';

echo $form;

}


function edit_package(){

$package_name = trim(mysql_prep($_GET['package_name']));

# FETCH SAVED DATA FOR EDIT FORM
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM  `courier` WHERE `package_name`='{$package_name}' ") 
or die('Could not get data:' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	

$row = mysqli_fetch_array($query);

#SHOW FORM
$form = '<h1>Edit Package</h1><div class="add-form">
<form method="POST" action="'.$_SERVER['PHP_SELF']. '"><br>
<input type="hidden" name="id" value="' .$row['id'].'">
Package name : <br><input type="text" name="package_name" size="20" maxlength="20" value ="'.$row['package_name'] .'"><br>
Description:<br><textarea name="description" rows="7">'.$row['description'].'</textarea><br>
Sender: <br><input type="text" name="sender_name" size="20" maxlength="20" class="menu-item-form" value="'.$row['sender_name'] .'"><br>
Receiver: <br><input type="text" name="reciever_name" size="20" maxlength="20" class="menu-item-form" value="'.$row['reciever_name'] .'" ><br>
Sender location: <br><input type="text" name="sender_location" size="20" maxlength="20" class="menu-item-form" value="'.$row['sender_name'] .'" ><br>
Reciever location: <br><input type="text" name="reciever_location" size="20" maxlength="20" class="menu-item-form" value="'.$row['reciever_location'] .'" ><br>
Status: <br><input type="text" name="status" size="10" maxlength="20" class="menu-item-form" value="'.$row['status'] .'" ><br>
Tracking number: <br><input type="text" name="tracking_number" size="10" maxlength="10" class="menu-item-form" value="'.$row['tracking_no'] .'" ><br>
<br><input type="submit" name="submitted" value="Save" class="submit">
</form></div>';



echo $form;	
	
}


 function list_packages(){
	 	 	
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM  `courier` ORDER BY  `id` DESC 
  LIMIT 0 , 30") or die('Could not get data lo:' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  #echo "Data fetched succesfully"; //testing
  $package_list = "<h2> Packages List</h2>";
  
  
  while($row = mysqli_fetch_array($query)){
  	$package_list = $package_list 
  . "<table class=''>"
  . '<tr><td colspan="3" rowspan="6" width="auto" height="100%"'
  . ' align="left" valign="bottom">Title :&nbsp'
  .'<strong> ' 
  . $row['package_name'] 
  . '</strong>'
  .'&nbsp &nbsp<a href="' 
  . ADDONS_PATH ."courier/process.php?" 
  . 'action='
  . 'edit_package&'
  . 'package_name='
  . $row['package_name']
  . '" '
  . '>edit </a>'
  . "</td>"
  . '<td colspan="3" rowspan="6" width="auto" height="100%"'
  . ' align="left" valign="bottom">'
  . '&nbsp| &nbsp<a href="'
  . ADDONS_PATH ."courier/process.php?" 
  . 'action='
  . 'delete_package&'
  . 'package_name='
  . $row['package_name']
  . '&deleted='
  . 'true'
  . '" '
  . '>delete </a>'
  . "</td><hr></tr></table>";
 }
 echo $package_list;
}

function delete_package(){
	
	$package_name = $_GET['package_name'];
	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `courier` WHERE `package_name`='{$package_name}'") 
					or die("DELETE package failed!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
					
		if($query){
			
			status_message("message-notification", "Package deleted");
			}
}



 // end of courier functions file
 // in root/courier/includes/functions.php
?>
