<?php ob_start();
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit

if(!isset($_SESSION['username'])){
  $destination = '../user';
	header("Location: $destination");
	exit;
}
$_SESSION['addon_home'] ='';
start_page();
show_top_bar();
#######################################################################

login_successful();  // Informs of login success 
	  
?>

<section class="container padding-10">

<h1><p align="center">Admin Area</p></h1>

<!-- TOP RIGHT LINKS --> 
	
<?php if(is_admin()){
echo '<ul>
			<li class="float-right-lists">
				<a href="'.BASE_PATH .'config">Site SETTINGS </a> </li>
			<li class="float-right-lists">
				<a href="'.BASE_PATH .'addons">Install or configure Addons </a> </li>
		</ul>'
	;}
?>


<!-- PAGE CONTENT -->
	


<?php get_addons_list(); ?> 

</section>


<!-- FOOTER -->
<section class="footer-region"><?php do_footer();?>
</section>

</body>
</html>
