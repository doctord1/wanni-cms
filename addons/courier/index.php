<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/courier'; #do not edit
require_once($r .'/includes/functions.php'); #do not edit

start_addons_page();  
#						- Template ends -
#=======================================================================

?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->


<section class="container">
 <h1>Courier Delivery</h1>
</section>

<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'courier">Settings </a>' ;?></li>
			<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'courier/add-package.php"> Add package </a>' ;?></li>
	</ul>
</section>

<?php 



# CUSTOM CODE HERE 
if (!isset($_SESSION['username'])){

echo '<section class="container">
	<ul>
			<li class="big-button" class="float-right-lists">
			<a href="'.BASE_PATH .'courier/tracking.php"> Tracking </a></li>
	</ul>
</section>';

} else {
	
if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
	echo '<section class="container">
		<ul>
			<li  class="big-button">
				<a href="'.BASE_PATH .'courier">Settings </a></li>
				<li id="add_page_form_link" class="big-button">
				<a href="'.BASE_PATH .'courier/add-package.php"> Add package </a></li>
				<li class="big-button">
				<a href="'.BASE_PATH .'courier/tracking.php"> Tracking </a></li>
		</ul>
	</section>';	
	list_packages();
	}
}
?>



<section class='container'>
<?php 

 ?>
</section>


</body>
</html>
