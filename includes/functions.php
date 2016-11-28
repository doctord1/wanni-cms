<?php 
ob_start();
require_once('constants.php');
require_once('session.php');
require_once('connect.php');
require_once('title.php');
require_once('crud_functions.php');
$_SESSION['LAST_ACTIVITY'] = time();
$_SESSION['base_path'] = BASE_PATH;
$_SESSION['temp_container']='';

if(isset($_POST['destination'])){
  $destination = $_POST['destination'];
	echo "<script> window.location.replace('{$destination}') </script>";
	exit;
}else if(isset($_GET['destination'])){
  $destination = $_GET['destination'];
	echo "<script> window.location.replace('{$destination}') </script>";
	exit;
}

function install_core(){

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `activity`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(611) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `actor` varchar(150) NOT NULL,
  `action` varchar(255) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `actor_path` varchar(255) NOT NULL,
  `subject_path` varchar(255) NOT NULL,
  `date` varchar(30) NOT NULL,
  `parent` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");	
	
	
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `addons`");
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `addons` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `addon_name` varchar(40) NOT NULL,
  `description` varchar(100) NOT NULL,
  `required_files` varchar(150) NOT NULL,
  `status` int(1) NOT NULL,
  `version` varchar(10) NOT NULL DEFAULT '0.01',
  `core` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `addon_name` (`addon_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `blocks`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_name` varchar(150) NOT NULL,
  `region` varchar(30) NOT NULL,
  `block_title` varchar(150) NOT NULL,
  `block_description` varchar(150) NOT NULL DEFAULT 'none',
  `position` int(3) NOT NULL,
  `content` longtext NOT NULL,
  `function_call` varchar(100) DEFAULT NULL,
  `parent_addon` varchar(50) DEFAULT NULL,
  `show_title` int(1) NOT NULL DEFAULT '1',
  `page_visibility` text NOT NULL,
  `role_visibility` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_name` (`block_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ") ;

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "

INSERT INTO `blocks` (`id`, `block_name`, `region`, `block_title`, `block_description`, `position`, `content`, `function_call`, `parent_addon`, `show_title`, `page_visibility`) VALUES
(1, 'front-slideshow', 'highlight', 'Front slideshow', 'none', 1, '', 'show_slideshow_block();', 'slideshow', 0, 'home'),
(2, 'New-users', 'right sidebar', 'New Users', 'Block showing new members', 1, '', 'show_new_users();', 'user', 1, 'home,'),
(3, 'user_login', 'none', 'User login', 'User Login block', 1, '', 'show_login_form();', 'user', 0, ''),
(4, 'Primary-menu', 'none', 'Primary menu', '', 1, '', 'if(addon_is_available(menus)){ get_top_menu_items();}', 'admin', 0, 'all,'),
(5, 'Front-promoted', 'right sidebar', 'Front promoted', 'Pages promoted in frontpage', 2, '', 'show_front_promoted_posts();', 'system(page)', 1, 'home'),
('', 'primary menu', 'none', 'Primary menu', 'System block showing primary (top) menu', 1, '1', 'get_top_menu_items();', 'system(menus)', 0, 'none')");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `blocks`(`id`, `block_name`, `region`, `block_title`, `block_description`, `position`, `content`, `function_call`, `parent_addon`, `show_title`, `page_visibility`) 
VALUES ('','activity','main content','activity','Block showing activity','1','','show_activity();','activity','0','home')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `blockable_functions`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `blockable_functions` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent` varchar(150) NOT NULL,
  `function` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='functions that can be called as blocks'");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `comments`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `path` varchar(150) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `parent_id` int(65) NOT NULL,
  `author` varchar(150) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `config`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `application_name` varchar(35) NOT NULL,
  `welcome_message` varchar(50) NOT NULL DEFAULT 'Welcome to Wanni CMS',
  `base_path` varchar(30) DEFAULT NULL,
  `admin_folder_name` varchar(30) NOT NULL DEFAULT 'admin',
  `sub_folder_name` varchar(50) NOT NULL,
  `default_functions` varchar(150) NOT NULL DEFAULT '/includes/functions.php',
  `stylesheet` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `config` (
`id`, `application_name`, `welcome_message`,
  `base_path`, `admin_folder_name`, `sub_folder_name`, 
  `default_functions`, `stylesheet`) VALUES
(2, 'default', 'Welcome to Wanni CMS', '', 'admin', 'sandbox2', '/includes/functions.php', 'geniusaid.css')");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `contest`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `contest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contest_name` varchar(100) NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `total_votes` int(6) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `start_date` varchar(50) NOT NULL,
  `end_date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `contest_entries`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
  CREATE TABLE IF NOT EXISTS `contest_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contest_id` int(11) NOT NULL,
  `contestant_name` varchar(150) NOT NULL,
  `contest_entry` text NOT NULL,
  `date` varchar(50) NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `files`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `large_path` tinytext NOT NULL,
  `medium_path` tinytext NOT NULL,
  `small_path` tinytext NOT NULL,
  `original_path` tinytext NOT NULL,
  `parent` varchar(150) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `follow`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `follow` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent` varchar(150) NOT NULL,
  `child_name` varchar(150) NOT NULL,
  `owner` varchar(150) NOT NULL,
  `path` varchar(255) NOT NULL,
  `follow_list_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `fundraiser`");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `fundraiser` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `fundraiser_name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `perks` text NOT NULL,
  `target_amount` int(6) NOT NULL,
  `amount_raised` int(6) NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `status` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`fundraiser_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") ;

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `fundraiser` (`id`, `fundraiser_name`, `reason`, `perks`, `target_amount`, `amount_raised`, `author`, `editor`, `status`, `created`, `last_updated`, `start_date`, `end_date`) VALUES
(1, 'test', 'zilch brand', 'zero incentive', 500, 100, 'test', '', 'pending', '2015-06-06T14:03:53+01:00', '2015-06-06T14:03:53+01:00', '0000-00-00', '0000-00-00')");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `fundraiser_donors` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `donor` varchar(150) NOT NULL,
  `amount` int(6) NOT NULL,
  `fundraiser_name` varchar(255) NOT NULL,
  `recipient` varchar(150) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `funds_manager`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `funds_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giver` varchar(50) NOT NULL,
  `reciever` varchar(50) NOT NULL,
  `amount` int(6) NOT NULL,
  `time` varchar(50) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `balance` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `installed` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `value` varchar(3) NOT NULL,
   UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `menus`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item_name` varchar(60) NOT NULL,
  `menu_type` varchar(20) NOT NULL,
  `position` int(2) NOT NULL DEFAULT '1',
  `visible` int(2) NOT NULL DEFAULT '1',
  `destination` varchar(260) NOT NULL,
  `parent` varchar(20) NOT NULL,
  `is_parent` varchar(3) NOT NULL,
  `is_child` varchar(3) NOT NULL,
  `parent_menu_id` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_item_name` (`menu_item_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `menus` (`id`, `menu_item_name`, `menu_type`, `position`, `visible`, `destination`, `parent`) VALUES
(1, 'home', 'primary', 1, 1, 'home', 'page'),
(2, 'sections', 'primary', 1, 1, 'sections', 'page')");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `menu_type`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `menu_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_type_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `menu_type` (`id`, `role_id`, `menu_type_name`) VALUES
('', 0, 'primary'),
('', 0, 'secondary'),
('', 0, 'user'),
('', 0, 'none')");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `messaging`");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `messaging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reciever` varchar(60) NOT NULL,
  `sender` varchar(60) NOT NULL,
  `subject` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `parent_id` varchar(60) NOT NULL,
  `unread_sender` varchar(3) NOT NULL,
  `unread_reciever` varchar(3) NOT NULL DEFAULT 'yes',
  `reply` varchar(3) NOT NULL,
  `participants` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `page`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(150) NOT NULL,
  `category` varchar(150) NOT NULL,
  `page_name` varchar(150) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `page_type` varchar(150) NOT NULL,
  `menu_type` varchar(150) NOT NULL DEFAULT 'none',
  `position` int(3) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `allow_comments` varchar(3) NOT NULL,
  `promote_on_homepage` varchar(3) NOT NULL,
  `destination` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `page_type`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `page_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_type` (`page_type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `page_type` (`id`, `page_type_name`) VALUES
(2, 'blog'),
(4, 'contest'),
(3, 'events'),
(1, 'page')");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_project`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `project_manager_project` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `project_manager` varchar(150) NOT NULL,
  `path` varchar(255) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `created` varchar(150) NOT NULL,
  `last_updated` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_name` (`project_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_suggestion`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `project_manager_suggestion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suggestion_name` varchar(150) NOT NULL,
  `project_name` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `editor` varchar(50) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `likes` int(6) NOT NULL DEFAULT '0',
  `dislikes` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `suggestion_name` (`suggestion_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_task`");
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `project_manager_task` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(150) NOT NULL,
  `parent` varchar(150) NOT NULL,
  `parent_type` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `project_manager` varchar(150) NOT NULL,
  `path` varchar(255) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `assigned_to` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  `priority` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_name` (`task_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `project_manager_task_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` varchar(150) NOT NULL,
  `submission_note` text NOT NULL,
  `disapproval_note` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `author` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_ticket`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `project_manager_ticket` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(150) NOT NULL,
  `parent` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `priority` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_name` (`ticket_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `rate`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `rate_type` varchar(50) NOT NULL,
  `rate_value` int(1) NOT NULL,
  `rater` varchar(150) NOT NULL,
  `ratee` varchar(250) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `rate_type`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `rate_type` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `rate_type_name` varchar(150) NOT NULL,
  `rate_value` int(4) NOT NULL,
  `rate_text` varchar(15) NOT NULL,
  `unrate_text` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `regions`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `regions` (
  `region_name` varchar(40) NOT NULL,
  `position` int(2) NOT NULL,
  PRIMARY KEY (`region_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `regions` (`region_name`, `position`) VALUES
('3_COLUMN_column_1', 3),
('3_COLUMN_column_2', 3),
('3_COLUMN_column_3', 3),
('ads', 0),
('footer', 6),
('header', 1),
('highlight', 2),
('left sidebar', 3),
('main content', 4),
('navigation', 3),
('none', 0),
('right sidebar', 5)");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `sections`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(150) NOT NULL,
  `position` int(3) NOT NULL,
  `description` text NOT NULL,
  `visible` int(1) NOT NULL,
  `parent_post_type` varchar(50) NOT NULL,
  `is_category` varchar(3) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `sections` (`id`, `section_name`, `position`, `description`, `visible`) VALUES
(1, 'blog', 1, 'Blog posts', 1),
(2, 'contest', 2, 'Vote and be voted for in contests and competitions where you can win cash prizes or rewards !', 1),
(3, 'notices', 7, 'Sitewide notices and important updates.', 1),
(4, 'none', 8, '', 0)");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS ``");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `blocks` 
(`id`, `block_name`, `region`, `block_title`, `block_description`, 
`position`, `content`, `function_call`, `parent_addon`, 
`show_title`, `page_visibility`) 
VALUES (NULL, 'front slideshow', 'highlight', 'Front slideshow', 
'none', '1', '', 'show_slideshow_block();', 'slideshow', '0', 'home')");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `themes`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `path` varchar(150) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `parent_id` int(65) NOT NULL,
  `author` varchar(150) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
DROP TABLE IF EXISTS `user`");

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` varchar(14) NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '2',
  `logged_in` varchar(3) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `site_funds_amount` int(11) NOT NULL DEFAULT '0',
  `role` varchar(50) NOT NULL DEFAULT 'authenticated',
  `picture` varchar(255) NOT NULL,
  `picture_thumbnail` varchar(255) NOT NULL,
  `secret_question` varchar(150) NOT NULL,
  `secret_answer` varchar(150) NOT NULL,
  `status` varchar(15) NOT NULL,
  `registration_code` int(13) NOT NULL,
  `bank_account_no` varchar(255) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ") ;

	if($q1){
		status_message('success','Wanni-CMS Core installed successfully!');
		}
	}

	
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
SELECT `value` FROM `installed`");
$result = mysqli_fetch_array($q1);

if($result['value'] ==='no'){
	install_core();
	$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `installed` SET `value`='1' WHERE `id`='0'");
	 $destination = 'config.php';
	 header("Location: $destination");exit;
	 #echo "<script> window.location.replace('{$destination}') </script>";

	} else if(empty($result)){
		$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE `installed`");
		$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
	CREATE TABLE IF NOT EXISTS `installed` (
	  `id` int(1) NOT NULL,
	  `value` varchar(3) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1");

	$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
	INSERT INTO `installed` (`id`, `value`) VALUES
	('', 'no') LIMIT 1");	
	 $destination = $_SERVER['PHP_SELF'];
	echo "<script> window.location.replace('{$destination}') </script>";
		}
		
function load_system(){
	$r = dirname(dirname(__FILE__)); #do not edit
	$r = $r .'/';
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `required_files` FROM `addons`");
	while($result = mysqli_fetch_array($q)){
		require_once($result['required_files']);
		//echo $result['required_files']."<br>";
		}
	
	}load_system();


function install_required(){
	
	$r = dirname(dirname(__FILE__)); #do not edit
	$r = $r .'/';
	#echo $r;
	$core = array('page','blocks','sections','funds_manager','menus','libraries',
					'scripts','styles','regions','uploads','admin','addons','config',
					'slideshow','user','includes','documentation');
	$list = array();
	
	$exception = array('libraries','scripts','styles','regions','addons','config','includes');
	
	#GET OPTIONAL ADDONS				
	if ($handle = opendir($r)) {
		while (false !== ($addon_folder = readdir($handle))) {
			if ($addon_folder != "." 
				&& $addon_folder != ".." 
				&& $addon_folder != ".git"
				&& $addon_folder != "admin"
				&& is_dir($addon_folder)) {
					if(in_array($addon_folder,$core)){
						include_once($r.$addon_folder."/details.php");
						if(!in_array($addon_folder,$exception)){
							$required = $r.$addon_folder."/includes/functions.php";
						
							$q = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`
							(`id`, `addon_name`, `description`, `required_files`, `status`, `version`, `core`) 
							VALUES ('','{$addon_folder}','{$my_addon_desc}','{$required}','1','{$version}','yes')");
						}
					}
			}
		}
				closedir($handle);
			}
	}



function status_message($class, $string){
	
	if (isset($_GET['class'])){
		
		$class = $_GET['class'];
	}
	if (isset($_GET['status_message'])){
		
		$string = $_GET['status_message'];
	}
	
	$alert = "<div class='alert'>";
	$success = "<div class='success'>";
	$error = "<div class='error'>";
	
	if ($class === "alert"){
	$message = $alert .$string ."</div>";
	
	} else if ($class === "success"){
	$message = $success .$string ."</div>";	
		
	} else if ($class === "error"){
	$message = $error .$string ."</div>";	
		
	} else { $message ="";}
	
	echo $message ;
	
}



// * # SHOW THE TOP BAR WITH LINKS TO HOMEPAGE AND ADMIN PAGE
function show_top_bar(){
	
	$bar = '<nav class="top-bar u-full-width" id="navbar">';
	//<a href="#"><span class="home-link" id="toggle-sidebar"><img src="'.BASE_PATH.'uploads/files/default_images/menu24.png"></span></a>
	$bar .='&nbsp;<a href="' .BASE_PATH . '" class ="home-link"><img src="'.BASE_PATH .'uploads/files/default_images/Home-32.png">&nbsp;</a>&nbsp;' ;
	
	
	if(is_admin()){
		$bar = $bar .'<div class="back-to-control"><a href="' .ADMIN_PATH .'"><img src="'.BASE_PATH .'uploads/files/default_images/Admin-Icon.png"></div>';
		}
	
	if(addon_is_active('money_service')){
	$bar = $bar .'&nbsp;&nbsp;<a href="' .ADDONS_PATH .'money_service/catalog"><img src="'.BASE_PATH .'uploads/files/default_images/Add-To-Cart-32.png"> </a>';	
		}
	
	if(isset($_SESSION['addon_home'])){
	$bar = $bar . ' &nbsp;'.$_SESSION['addon_home'];	
	}
	
	if(is_logged_in()){
	$bar .= "&nbsp;&nbsp;<a href='".BASE_PATH."page/add/'>".'<img src="'.BASE_PATH .'uploads/files/default_images/Add-New-32.png"></a>';	
	}
		
	if(isset($_SESSION['user_id'])){
	
	$bar .= 
	'&nbsp;&nbsp;<div class="greet"><a href="'.BASE_PATH .'user?user='.$_SESSION['username'].'">' .$_SESSION['username'] .
	'</a>&nbsp|<a href="' .BASE_PATH .'user/logout.php" class ="home-link">LOGOUT</a></div>' ;
	}
	
	$bar = $bar .'</nav>';
	echo $bar;
}

# Selecting styles
function select_style() {// Largely INCOMPLETE
  $query = mysqli_query($GLOBALS["___mysqli_ston"], "select stylesheet from config") or die("Could not select style!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  $result = mysqli_fetch_array($query);   
  //uncomment the next line to test if theme selection works!
  # echo $result['stylesheet'] ."Theme selection successful!!";
  
  $style = '<link href="'. BASE_PATH .'styles/'. strtolower(STYLESHEET) .'" rel="stylesheet">';
  
  return $style;
}


function addon_is_available($addon_name){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `addons` WHERE `addon_name`='{$addon_name}'");
	
	if($query){
		$num = mysqli_num_rows($query);
		
		if($num >= 1){
		return true;
		}
		}
}


function addon_is_active($addon_name){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `addons` WHERE `addon_name`='{$addon_name}'");

	if($query){
		$result=mysqli_fetch_array($query);
		if($result['status']==='1'){
		return $result;
	} else {
		return false;
		}
	}
}

function query_string_in_url(){
	$url = $_GET;
	if(!empty($url)){
		return true;
	} else {
		return false;
		}
}



function start_addons_page(){

	$page_title = set_page_title();
	
	start_page();
	
	if(addon_is_available('funds_manager')){
		//get_user_funds();
		}
	
	$toolbar = show_top_bar();
	echo $toolbar; 
	remove_file();
	
	}	

function start_addon_config_page(){
	
	echo '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title> <?php echo $page_title ?> </title>
<link href="'.STYLESHEET.'" rel="stylesheet">
</head>
<body>
 
<!-- HEADER REGION START -->
<section class="holder">
	<section class="top-bar"> <a href="' .BASE_PATH . '" class ="home-link">Home - ' .APPLICATION_NAME .'</a>
		<div class="back-to-control"><a href="'.ADMIN_PATH.'">GO TO ADMIN </a> 
		</div>
	</section>
</section>';
	}

function show_config_form_buttons(){
	echo 
'<form enctype="multipart/form-data" method="POST" action="'. $_SERVER["PHP_SELF"] . '">' .
'<input type="hidden" name="status" value="1">' .
'<input type="submit" name="activate" value="ACTIVATE" class="activate-button">';

echo 
'<input type="hidden" name="status" value="0">' .
'<input type="submit" name="deactivate" value="DEACTIVATE" class="deactivate-button">';

echo 
'<input type="submit" name="update" value="UPGRADE" class="pull-right deactivate-button">';

echo 
'<input type="submit" name="uninstall" value="UNINSTALL" class="uninstall-button"></form><hr></p>';

// CONFIG ENDS HERE

	}

function start_page(){

	$page_title = set_page_title(); 
	echo '
	<!DOCTYPE html>
	<html lang="en">
	<head>';
	
	echo '<meta charset="utf-8"/><meta name="description" content="">
	<title>' .$page_title . $_GET['page_name'] .'</title>
	
	<!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="shortcut icon" href="'.BASE_PATH.'uploads/files/default_images/favicon.ico?v=4f32ecc8f43d">
	<link href="'.BASE_PATH .'libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="'.BASE_PATH .'libraries/bxslider/jquery.bxslider.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="'.BASE_PATH.'libraries/dropit/dropit.css" type="text/css" />';
	echo '<link rel="stylesheet" href="'.BASE_PATH.'styles/nivo-slider/themes/default/default.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="'.BASE_PATH.'styles/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" href="'.BASE_PATH.'styles/sidebar_style.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" type="text/css" href="'.BASE_PATH.'libraries/slick/slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="'.BASE_PATH.'libraries/slick/slick/slick-theme.css"/>
				
	 
	';
	$stylesheet = select_style();
	echo $stylesheet;	
echo '<!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="'.BASE_PATH .'uploads/files/default_images/favicon.png">
  
  
  <!-- SCRIPTS
  ____________________________________________________ -->';
  


echo "</head>";
    
    
        
        
$is_mobile = check_user_agent('mobile');
	if($is_mobile || !is_logged_in() || !url_contains('page_name=home')){
	echo '<body class="">';
	}else if(!$is_mobile || (is_logged_in() && url_contains('page_name=home'))){
	echo '<body class="content-pushed">';
	}
	remove_file();
	
}



function is_home_page(){
$url =$_SESSION['current_url'];
if($url == $_SERVER['HTTP_HOST'] || $_SERVER['QUERY_STRING'] == 'page_name=home'){
	return true;
	} else { return false; } 
	
}


	
function is_admin(){
	if(isset($_SESSION['username'])){
		if($_SESSION['role']==='admin' || $_SESSION['role']==='manager' || $_SESSION['role']==='superadmin'){
			return true;
			} else {return false;}
	}
}

function is_author(){
	if($_SESSION['username'] === $_SESSION['author']){
		return true;
		} else { return false;}
}

function is_logged_in(){
	if(isset($_SESSION['username']) && $_SESSION['free_view_count'] < 2){
		return true;
		} else {
			return false;
			}
}	

function add_ckeditor(){	
	echo '<script type="text/javascript" src="'. BASE_PATH .'libraries/ckeditor/ckeditor.js"></script>
	<script>
                // Replace the <textarea id="content-area"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( "#content-area" );
            </script>';
}

function show_more_button($current_page = ''){
	
	$url = $_SESSION['current_url'];
	
	if(!empty($_GET['start_from']) && is_numeric($_GET['start_from'])){
		$number_holder = trim(mysql_prep($_GET['start_from']));
	} else { $number_holder = 0; }
	
	echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		'Show more <input type="number" class="small-input-box" name="show_more" size ="3" value="11">'.
		' Starting from <input type="number" name="start_from" value="'.$number_holder.'">
		<input type="submit" name="submit" value="show older pages" class="button-primary">'.
		"<input type='submit' name='clear_page_list_values' value='reset'>
		</form></div>";
	
}

function show_more_execute($parent,$number_holder=''){
	# Executes the action of show_more_button()
	
	if(isset($_POST["{$parent}_list_limit"])){
		$post_limit = $_POST["{$parent}_list_limit"];
	} else { $post_limit = 10;}
	if(isset($_POST["{$parent}_list_number_holder"])){	
		$step = $_POST["{$parent}_list_number_holder"];
	} else{ $step = 0; }
	
	if(isset($_POST["clear_{$parent}_list_values"])){
			unset($_POST);
			$number_holder = '';
			$post_limit = 10;
			$step = 0;
			}	
			
		$limit = "LIMIT ". $step .", ".$post_limit;
		$number_holder = $post_limit + $step;	
	
	$output = array('parent'=>$parent,'limit'=>$limit, 'number_holder'=>$number_holder);
	return $output;
	}

function add_nicedit_editor(){
	
echo '<script src="'.BASE_PATH.'libraries/nicedit/nicEdit.js" type="text/javascript"></script>
 ';

echo"
<script type='text/javascript'>

function addArea() { 
	area = new nicEditor({
		iconsPath : '".BASE_PATH."libraries/nicedit/nicEditorIcons.gif',
		buttonList : ['bold','italic','underline','strikeThrough','left','center','right','ol','ul','strikethrough',
		'image','upload','link','unlink','image']}).panelInstance('content-area');
		
		}
function removeArea() {
area.removeInstance('content-area');}
</script>";
	
}

function add_tinymce_editor(){
	//$is_mobile = check_user_agent('mobile');
	
	//#Local tinymce 
	////echo '<script type="text/javascript" src="'. BASE_PATH .'libraries/tinymce/js/tinymce/tinymce.min.js">';
	#'<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
//	echo'</script>
	//<script type="text/javascript">
//    selector: "textarea#content-area"
//	});
//	</script>';
	
}

function link_to($path='', $name='', $class='', $type='',$extra_get=''){
	//valid types are 'button' or 'link'
	if($type !== '' && $type === 'button'){
		$name = "<button class='{$class}'>{$name}</button>";
		$pre ='';
		$post='';
		}
	else if($type === '' || $type === 'link'){
		$name = $name;
		$pre = "<div class='{$class}'>";
		$post = "</div>";
		}
	if($path === 'self'){
		$path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] .$extra_get;
		}
	echo $pre."<a href='{$path}'>{$name}</a>".$post;
}


function do_header() {	
	show_logo(); # Displays logo
	
	if(is_logged_in()){
		show_welcome_message(); 
		$output = get_region_blocks('header');
		if($output !== ''){
			echo $output;
			}
	}
}

function do_highlight() {
	if(is_logged_in()){	
	$output = get_region_blocks('highlight');
	if($output !== ''){
		echo $output;
		}
	}
}


function do_three_column_region(){
	$column1 = get_region_blocks('3_column_column_1','three-column-title','three-column-even');
	$column2 = get_region_blocks('3_column_column_2','three-column-title','three-column-even');
	$column3 = get_region_blocks('3_column_column_3','three-column-title','three-column-even');

	$region = '';
	if($column1 !== ''){ $region = $region .'<div class="floating-container">'. $column1 .'</div>'; }
	if($column2 !== ''){ $region = $region .'<div class="floating-container">'. $column2 .'</div>';  }
	if($column3 !== ''){ $region = $region .'<div class="floating-container">'. $column3 .'</div>'; }
	
	if($region !== ''){ echo $region;}
	
	}

function do_main_content(){
	if(is_logged_in()){
		$output = get_region_blocks('main content');
		if($output !== ''){
			echo $output;
			}
	}
}

function do_ads(){
	$output = get_region_blocks('ads');
	if($output !== ''){
		echo $output ;
		}		
}

function do_left_sidebar(){
	if(is_logged_in()){
	$output = get_region_blocks('left sidebar');
	if($output !== ''){
		echo $output;
		}
	}
}


function do_right_sidebar(){
	if(is_logged_in()){
	$output = get_region_blocks('right sidebar');
	if($output !== ''){
		echo $output;
		}
	if($_GET['page_name'] !=='home' && !url_contains('user')){
		//show_activity('page');
		}
		echo '</div>';
	}
}


function do_top_right_sidebar(){
	
	$output = get_region_blocks('top right sidebar');
	if($output !== ''){
		echo $output;
		}
	if($_GET['page_name'] !=='home' && !url_contains('user')){
		
		}
		echo '</div>';
}

function redirect_to($destination){
	echo "<script> window.location.replace('{$destination}') </script>";
	echo "<noscript>";
	header('Location: '.$destination);
	echo"</noscript>";
	exit;
	}
	
function load_bootstrap(){
	echo '<script src="'.BASE_PATH .'libraries/bootstrap/js/bootstrap.min.js"></script>';
	}
	
function load_jquery(){
	echo '<script src="'.BASE_PATH .'libraries/jquery/jquery-1.11.2.min.js"></script>';
	
	}
	
function load_prettyPhoto(){
	echo '<script src="'.BASE_PATH .'libraries/prettyPhoto/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>';
	//echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>';
	}

function do_footer(){
	unset($_SESSION['status_message']);
	$output = get_region_blocks('footer');
	if($output !== ''){ echo $output; }
	add_nicedit_editor();
	
	if(addon_is_active('gallery')){
		style_prettyPhoto();
		}
	
	echo "<p></p><div class='credits clear'>
	<span class='inline-block padding-10'> <a href='".BASE_PATH."?page_name=terms-of-use'>Terms of use</a></span>
	<span class='inline-block padding-10'> <a href='".BASE_PATH."?page_name=faqs'>Faqs</a></span>"
	 .
	#'<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js" type="text/javascript"></script>' .
	'<script src="'.BASE_PATH .'libraries/jquery/jquery-1.11.2.min.js"></script>' .
	'<script src="'.BASE_PATH .'libraries/bxslider/plugins/jquery.fitvids.js"></script>' .
	'<script src="'.BASE_PATH .'libraries/bxslider/jquery.bxslider.min.js"></script>' .
	//'<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    //'<script src="' .BASE_PATH .'scripts/simpler-sidebar.min.js"></script>'.
	'<script src="'.BASE_PATH .'libraries/jquery/jquery.timeago.js"></script>' .  
	"" . 
	'<script type="text/javascript" src="' .BASE_PATH .'libraries/mosaic/js/mosaic.1.0.1.min.js"></script>
	<script type="text/javascript" src="' .BASE_PATH .'libraries/slick/slick/slick.min.js"></script>';
	
	if(url_contains('edit')){
		
		}
	//if(url_contains('gallery')){
		load_prettyPhoto();
		echo '<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    $("a[rel^=\'prettyPhoto\']").prettyPhoto({
		theme: "facebook", /* light_rounded / dark_rounded / light_square / dark_square / facebook */
		horizontal_padding: 20, /* The padding on each side of the picture */
		allow_resize: true, /* Resize the photos bigger than viewport. true/false */
		allow_expand: false, /* Allow the user to expand a resized image. true/false */
		default_width: 500,
		default_height: 344,
			
    });
  });
</script>' ;
		//}
		
	if(url_contains('messaging/?mid')){
		echo '<script type="text/javascript">
		var myVar;    
		var url = BasePath + "addons/messaging/new-pings.php?_=" + Math.random();
		function showNewMessages(){
			$("#new-messages").load(url +" #new-pings").fadeIn("slow");
			myVar = setTimeout(showNewMessages, 10000);
		}
		function stopFunction(){
			clearTimeout(myVar); // stop the timer
		}
		$(document).ready(function(){
			showNewMessages();

		});
		</script>';
		}
		
	//if(! url_contains('user/?user=')){
   echo "<script src='" .BASE_PATH ."scripts/script.js' type='text/javascript'></script>".
   '<script type="text/javascript" src="'. BASE_PATH .'libraries/dropit/dropit.js"></script>' ;
	//}
   echo '<script type="text/javascript" src="'.BASE_PATH .'libraries/nivo-slider/jquery.nivo.slider.pack.js"></script>' .
	'<script type="text/javascript" src="'. BASE_PATH .'scripts/modernizr.js"></script>'.
   '<script type="text/javascript"> '.
			
			"jQuery(function($){
				$('.bar').mosaic({
					animation	:	'slide'
				});
		    });
		    
		</script>
		
		".'<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-583a9f4c273aaaed"></script>  '.
    "<div class='text-center'>Powered by - Wanni CMS</div>" .
	   "</div>";
	
	
	load_bootstrap();

    //5. Close connection
	# do this at the end of the page
	if(isset($connection)) {
	((is_null($___mysqli_res = mysqli_close($connection))) ? false : $___mysqli_res);
		}	

}

function show_logo(){
	$logo = "<div class='logo'>" .'<a href="'. BASE_PATH.'"><img src="'.BASE_PATH.'uploads/files/default_images/logo.png"></a></div>';
	echo $logo;
	}
	
function show_welcome_message(){
	echo "<div class='welcome-message'>" .WELCOME_MESSAGE ." v.".SITE_VERSION."</div>";
	}

function show_application_name(){
	echo "<div class='application-name'>". APPLICATION_NAME ."</div>";
	}

function string_contains($haystack='', $needle='') {

	//check for needle in haystack
	 $lookup = strpos($haystack, $needle);

	 
	 //If string is found, set the value of found_context
	if($lookup !== false) {
		return true; 
	}
	
	//If not found, set UNSET the value of found_context
	else {return false; }
 }

//  Check and Set context via url
function url_contains($string) {

// Test for context via url

	//get the current url
	$url = $_SESSION['current_url'];
	
	//check for string in url
	 $lookup = strpos($url, $string);
	 
	 //If string is found, set the value of found_context
	if($lookup > 1) {
		return true;
	}
	
	//If not found, set UNSET the value of found_context
	else {return false; }
 }



function register_update($addon,$function,$version){
	if(addon_is_active($addon) || $addon == 'system'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `updates`(`id`, `type`, `function`, `version`, `status`) 
		VALUES ('0','{$addon},'{$function}','{$version}','')") 
		or die("Problems registering update ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query ){
			status_message('alert','Update registered');
			}
		}
	}


function update($addon=''){
	if($addon == ''){
		$query =mysqli_query($GLOBALS["___mysqli_ston"], "SELECT function FROM updates WHERE version > '{SITE_VERSION}'");
		
		while($result = mysqli_fetch_array($query)){
			eval($result['function']);
			}
		} else {
			if(addon_is_active($addon)){
				$query =mysqli_query($GLOBALS["___mysqli_ston"], "SELECT function FROM updates WHERE addon='{$addon}' AND version > '{SITE_VERSION}'");
		
				while($result = mysqli_fetch_array($query)){
					eval($result['function']);
					}
				}
			}
	}

function better_strip_tags( $str, $allowable_tags = '<a><p><br><em><strong><h1><h2><h3><ul><del><strikethrough><blockquote>', $strip_attrs = false, $preserve_comments = false, callable $callback = null ) {
 // Features:
//* allowable tags (as in strip_tags),
//* optional stripping attributes of the allowable tags,
//* optional comment preserving,
//* deleting broken and unclosed tags and comments,
//* optional callback function call for every piece processed allowing for flexible replacements.

  
  $allowable_tags = array_map( 'strtolower', array_filter( // lowercase
      preg_split( '/(?:>|^)\\s*(?:<|$)/', $allowable_tags, -1, PREG_SPLIT_NO_EMPTY ), // get tag names
      function( $tag ) { return preg_match( '/^[a-z][a-z0-9_]*$/i', $tag ); } // filter broken
  ) );
  $comments_and_stuff = preg_split( '/(<!--.*?(?:-->|$))/', $str, -1, PREG_SPLIT_DELIM_CAPTURE );
  foreach ( $comments_and_stuff as $i => $comment_or_stuff ) {
    if ( $i % 2 ) { // html comment
      if ( !( $preserve_comments && preg_match( '/<!--.*?-->/', $comment_or_stuff ) ) ) {
        $comments_and_stuff[$i] = '';
      }
    } else { // stuff between comments
      $tags_and_text = preg_split( "/(<(?:[^>\"']++|\"[^\"]*+(?:\"|$)|'[^']*+(?:'|$))*(?:>|$))/", $comment_or_stuff, -1, PREG_SPLIT_DELIM_CAPTURE );
      foreach ( $tags_and_text as $j => $tag_or_text ) {
        $is_broken = false;
        $is_allowable = true;
        $result = $tag_or_text;
        if ( $j % 2 ) { // tag
          if ( preg_match( "%^(</?)([a-z][a-z0-9_]*)\\b(?:[^>\"'/]++|/+?|\"[^\"]*\"|'[^']*')*?(/?>)%i", $tag_or_text, $matches ) ) {
            $tag = strtolower( $matches[2] );
            if ( in_array( $tag, $allowable_tags ) ) {
              if ( $strip_attrs ) {
                $opening = $matches[1];
                $closing = ( $opening === '</' ) ? '>' : $closing;
                $result = $opening . $tag . $closing;
              }
            } else {
              $is_allowable = false;
              $result = '';
            }
          } else {
            $is_broken = true;
            $result = '';
          }
        } else { // text
          $tag = false;
        }
        if ( !$is_broken && isset( $callback ) ) {
          // allow result modification
          call_user_func_array( $callback, array( &$result, $tag_or_text, $tag, $is_allowable ) );
        }
        $tags_and_text[$j] = $result;
      }
      $comments_and_stuff[$i] = implode( '', $tags_and_text );
    }
  }
  $str = implode( '', $comments_and_stuff );
  return $str;
}

function strip_non_alphanumeric( $string ) {
	return preg_replace( "/[^a-z0-9]/i", "", $string );
}



function parse_text_for_output($string){ // Should handle the formatting and output of text
		$string = urldecode(better_strip_tags($string));
		
		$pattern = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)(\.png|\.jpg|\.gif|\.jpeg)+/';
		//$ret = preg_replace(urldecode($pattern),'PIC_HERE',$string);
		//echo '<img src="'.$ret.'" width="45%"/>';
		
		if(preg_match_all($pattern,$string,$matches)){
			foreach($matches[0] as $match){	
			$match1 = str_ireplace('+','%2B',$match);
			//echo $match;
			$string = str_ireplace($match,"<a href='".$match1."' rel='prettyPhoto[gen_gal]'>".'<img class="inline-block thumbnail img-responsive" src="'.$match1.'" ></a>',$string);
			
			}
		}	
		
		$pattern = '/(?<!\S)@\w+(?!\S)/';
		// Explanation: This will match any word containing alphanumeric characters, starting with "@." 
		// It will not match words with "@" anywhere but the start of the word.
		if(preg_match_all($pattern,$string,$matches)){
			foreach($matches[0] as $match){
			$changed_match = str_ireplace($match, "<a href='".BASE_PATH."user/?user=".str_ireplace('@','',$match)."'>{$match}</a>", $match);
			$new_string = preg_replace("/{$match}/", $changed_match, $string);
			$new_string = str_ireplace('&amp;','&',$new_string);
			$string = $new_string;
			}
			
			
		//$content = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', ' <a href="http://$1" target="_blank">$1</a> ', $new_string."");
		//$content = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a target="_blank" href="http://$1">$1</a> ', $content."");
	
		}
		if(addon_is_active('hashtags')){
		$hashtag = '/(?<!\S)#\w+(?!\S)/';
		// Explanation: This will match any word containing alphanumeric characters, starting with "#." 
		// It will not match words with "#" anywhere but the start of the word.
		
		//print_r($matches[0]);
			if(preg_match_all($hashtag,$string,$matches)){
				foreach($matches[0] as $match){
				$changed_match = str_ireplace($match, "<a href='".ADDONS_PATH."hashtags?hashtag=".str_ireplace('#','',$match)."'>{$match}</a>", $match);
				$new_string = preg_replace("/{$match}/", $changed_match, $string);
				$new_string = str_ireplace('&amp;','&',$new_string);
				$string = $new_string;
				}
			}
		}
	$string = convertYoutube($string); 
	$string = str_ireplace('https://player.vimeo.com/video/','https://vimeo.com/',$string);

	$string = preg_replace_callback('#https://vimeo.com/\d*#', function($string) {  return convertVimeo($string[0]);
}, $string);

	if(string_contains($string,'www.') 
	|| string_contains($string,'http://') 
	|| string_contains($string,'https://')){
		$content =  preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank">$1</a> ', $string);
		$content = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a target="_blank" href="http://$1">$1</a> ', $content);
		$string = $content;
		}
	
		
			
	
	//$string = str_ireplace('<a href="<iframe width="','',$string);
	
	unset($matches);
	return $string;
	
}


function convertVimeo($string,$width='')
{
//extract the ID
if(preg_match(
        '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/',
        $string,
        $matches
    ))
    {

//the ID of the Vimeo URL: 71673549 
$id = $matches[2];  

//set a custom width and height
if($width !== ''){
		$height = $width - 105;
		} else {
			$width = 420;
			$height = 315;
			}    
$vid ='<div class=""><iframe width="'.$width.'" height="'.$height.'" src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><br>';
 
 return $vid;  
   }
    

}



function convertYoutube($string='', $width='') {
	if($width !== ''){
		$height = $width - 105;
		} else {
			$width = 420;
			$height = 315;
			}
	$output = preg_replace(
		"/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
		"<div class='responsive-video'><iframe width=\"{$width}\" height=\"{$height}\" src=\"http://www.youtube.com/embed/$2\" target='blank' allowfullscreen></iframe></div>",
		$string
	);
	return $output;
}


function time_elapsed($time) {
    $elapsed_time = $time - time();

    if ($elapsed_time < 1) {
        return '0 seconds';
    }

    $a = array(12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'min',
        1 => 'sec'
    );

    foreach ($a as $secs => $text) {
        $d = $elapsed_time / $secs;
        if ($d >= 1) {
            $r = round($d);
            return " - " . $r . ' ' . $text . ($r > 1 ? 's' : '') . ' left';
        }
    }
}


function get_session(){
echo "<script type='text/javascript'>
	var SessionUser = '{$_SESSION['username']}';
	";
	$basepath = str_ireplace('http://', '',$_SESSION['base_path']) ;
echo "var BasePath = '{$_SESSION['base_path']}';
</script>";
}
get_session();

function show_slider1(){
	
	echo '	<div class="cd-panel from-right">	
		<div class="cd-panel-container">
		<header class="cd-panel-header">
			<a href="#0" class="cd-panel-close"></a>
		</header>

			<div class="cd-panel-content">
			</div> <!-- cd-panel-content -->
			
		</div> <!-- cd-panel-container -->
	</div> <!-- cd-panel -->';
}
show_slider1();

function show_with_editor($string, $rows=''){
	if($rows===''){$rows = 5;}
	echo '<a class="add-nicedit" onclick="addArea();">[ Show Editor]</a> &nbsp&nbsp <a class="remove-nicedit"  onclick="removeArea();">[ Hide Editor ]</a>
<br><textarea name="content" id="content-area" rows="'.$rows.'">'  .urldecode($string) .'</textarea>' ;
	
	}
   
 function mysql_escape_gpc($dirty)
{
    if (ini_get('magic_quotes_gpc'))
    {
        return $dirty;
    }
    else
    {
        return ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $dirty) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    }
}  

 	
function no_configurable_settings(){
	status_message('alert','This addon has no configuration');
}

function show_addon_config_path(){ // to be used only on addon index pages
	if(is_admin()){
		echo "<p align='center'><a href='".$_SESSION['current_url']."config'><button>Configure addon</button></a></p>";
		}
	}
 
function check_user_agent ( $type = NULL ) {
	 
 # USER-AGENTS
	
 
	$user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
	if ( $type == 'bot' ) {
			// matches popular bots
			if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
					return true;
					// watchmouse|pingdom\.com are "uptime services"
			}
	} else if ( $type == 'mobile' ) {
			// matches popular mobile devices that have small screens and/or touch inputs
			// mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
			// detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
	if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
					// these are the most common
					return true;
				}
	}else if ( $type == 'opera mini' ) {
			// matches popular mobile devices that do not support ajax
			if ( preg_match ( "/opera mini/", $user_agent ) ) {
					// these are the most common
					return true;
				}
	} else if ( $type == 'browser' ) {
			// matches core browser types
			if ( preg_match ( "/mozilla\/|opera\/|safari\/", $user_agent ) ) {
					return true;
			}
	} else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
					// these are less common, and might not be worth checking
					return true;
			}
	
	return false;
	
	/** HOW TO USE
	 * 
	 * <?php $ismobile = check_user_agent('mobile'); 
	 * if($ismobile) {
	 * return 'yes';
	 * } else{
	 * return 'no';
	 * }
	 * ?>
	 **/
       
}
 
function session_message($class='', $string=''){
	
	$_SESSION['message_owner'] = $_SESSION['username'];
	
	$_SESSION['status_message'] ='';
	if (isset($_POST['class'])){
		
		$class = $_POST['class'];
	}
	if (isset($_POST['status_message'])){
		
		$string = $_POST['status_message'];
	}
	
	$alert = "<div class='alert'>";
	$success = "<div class='success'>";
	$error = "<div class='error'>";
	
	if ($class === "alert"){
	$message = $alert .$string ."</div>";
	
	} else if ($class === "success"){
	$message = $success .$string ."</div>";	
		
	} else if ($class === "error"){
	$message = $error .$string ."</div>";	
		
	} else { $message ="";}
	
	$_SESSION['status_message'] = "<section  class='container'>" .$message ."</section>";
	
	$output = $_SESSION['status_message'];
	
}


function show_session_message(){
	if(isset($_SESSION['status_message'])){
			$message = $_SESSION['status_message'];	
			echo $message;
			}
	$_SESSION['status_message']='';
	} 

 
 
 function get_addons_list(){
	 $addons_number = 0;
	 if($_SESSION['role']==='admin'){
		$result = mysqli_query($GLOBALS["___mysqli_ston"], "Select * FROM addons ") or die("Cannot get addons List!"). ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		echo "<div class='clear'><h1>Registered Addons</h1><hr>";
		while($row = mysqli_fetch_array($result)){
			$clean_name = str_ireplace('_',' ',$row['addon_name']);
			$addons_number++;
			if($row['core']==='yes'){
				echo "{$addons_number} &nbsp<a href='" .BASE_PATH .$row['addon_name'] ."'><big>" .
				ucfirst($clean_name) ."</big></a> | '" .$row['description'] ."' | " .
				"<a href='" .BASE_PATH .$row['addon_name'] ."/config'> Configure </a> <br><hr>";
			} else {
				echo "{$addons_number} &nbsp<a href='" .ADDONS_PATH .$row['addon_name'] ."'><big>" .
				ucfirst($clean_name) ."</big></a> | '" .$row['description'] ."' | " .
				"<a href='" .ADDONS_PATH .$row['addon_name'] ."/config'> Configure </a> <br><hr>";
				}
		} echo '</div>';
	} else { deny_access(); }
}
 
function mysql_prep($string){
	
	$value = htmlentities($string);
	$value = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$value = htmlspecialchars($string);
	$value = str_ireplace("'","&#39;",$value);
	return $value;
}

function activity_record(
	
	$parent_id = '',
	$actor='',
	$action='',
	$subject_name='',
	$actor_path='',
	$subject_path='',
	$date ='',
	$parent=''
	){
	if($date === ''){$date= date('c');}	
	if($parent ==='project' 
	|| $parent === 'task'
	|| $parent === 'suggestion'){
		$parent = 'project_manager';
		}
	if($parent_id ==''){ $parent_id = 0; }
	
		if(!is_admin()){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `activity`(`id`,`parent_id`,`actor`,`action`,`subject_name`,`actor_path`,`subject_path`,`date`,`parent`) 
		VALUES ('0','{$parent_id}','{$actor}','{$action}','{$subject_name}','{$actor_path}','{$subject_path}','{$date}','{$parent}')")
		or die("Failed to insert activity" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
	
	}

	
function show_activity($parent=''){
	
	
	//DELETES ACTIVITY
	if(isset($_GET['activity_delete']) && ($_SESSION['control'] == $_GET['control'])){
		$id = $_GET['activity_delete'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `activity` WHERE `id`='{$id}'") or die('Failed to delete activity' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
	
	if($parent ===''){
		
		if(isset($_GET['fundraiser_name'])){
			$parent = 'fundraiser';
		} else if(isset($_GET['contest_name'])){
			$parent = 'contest';
		} else if(isset($_GET['project_name'])){
			$parent = 'project';
		}
	}
	# activity_limit routing
	
	if(!empty($_POST['activity_parent'])){
		$_SESSION['activity_parent'] = $_POST['activity_parent'];
		}
		
	if(isset($_POST['activity_parent_name_holder'])){
		$_SESSION["activity_parent_name_holder"] = $_POST['activity_parent_name_holder'];
		}
		
	if(isset($_POST['activity_limit'])){
		$_SESSION["activity_limit"] = $_POST['activity_limit'] + $_POST['activity_number_holder'];
		}
	if(isset($_POST['activity_number_holder'])){
		$_SESSION['activity_number_holder'] = $_POST['activity_number_holder'];
		}
		
	if(isset($_POST['clear_activity_session_values'])){
		
		unset($_SESSION["activity_parent"]);
		unset($_SESSION['activity_number_holder']);
		unset($_SESSION["activity_limit"]);
		unset($_SESSION['activity_parent_name_holder']);
	}
		# SET QUERY VALUES
		
	if(isset($_SESSION["activity_parent"])){
	$parent = $_SESSION["activity_parent"];
	}
			
	if(isset($_SESSION["activity_limit"])){
	$limit = $_SESSION["activity_limit"];
	}
	
		if(isset($_SESSION['activity_number_holder'])){
		$_SESSION["activity_limit"] = $limit + $_SESSION['activity_number_holder'];
		$number_holder = $_SESSION["activity_limit"];
		$_POST['activity_limit']=''; 
		$condition2 = " LIMIT 0, {$_SESSION["activity_limit"]}";
		}
	
	 else { 
		 $limit = 6; 
		 $condition2 = " LIMIT 0, {$limit}";
		 }
		 
	if($parent!==''){$condition = " WHERE `parent`='{$parent}'";
	}else{$condition = "";} 
		# DO QUERY
	
	//get contacts of user
	if(is_logged_in()){
		$owner = $_SESSION['username'];
	}	
	
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `contact_name` FROM `contacts` WHERE `owner`='{$owner}' LIMIT 9") 
		or die("Error selecting contacts " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) ;
		
		
	if($parent =='user'){
		$user = user_being_viewed();
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `activity` WHERE `actor`='{$user}' ORDER BY `id` DESC {$condition2}") 
		or die("Failed to get user activity ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	} else {	
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT * FROM `activity`{$condition}  GROUP BY date, actor ORDER BY `id` DESC {$condition2}")
	 	or die("FAiled to fetch {$parent} activity!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	 	$count = mysqli_num_rows($query);
			if($count < 1){
			//	echo '<div class="main-content-region">';
				status_message('alert','No activity!');
				
			} else { echo '<br><h2 class="text-center">Recent Activity</h2><div class="center-block">'; }
			
	 	while($result = mysqli_fetch_array($query)){
			
			echo '<a href="'.$result['actor_path'].'">'. $result['actor'].'</a>'
					.$result['action'].' <a href="'.$result['subject_path'].'">'.str_ireplace('-',' ',urldecode($result['subject_name'])).'</a> '
					." <time class='timeago' datetime='".$result['date']."'>".$result['date']."</time>";
					
					if(is_admin()){
					 echo "<span class='tiny-edit-text'><a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&activity_delete=".$result['id']."&control=".$_SESSION['control']."'><em>delete</em></a></span>";
					}echo " <br><hr>";
			}
		
	
	echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='activity_limit' value='".$limit."'>
		<input type='hidden' name='activity_number_holder' value='".$number_holder."'>
		<input type='hidden' name='activity_parent' value='".$parent."'>
		<input type='submit' name='submit' value='show me more' class='button-primary'>
		<input type='submit' name='clear_activity_session_values' value='reset'>
		</form></div>";
		
		echo "</div>";
	
	}
	
function show_more($parent=''){
	
	
	//DELETES ACTIVITY
	if(isset($_GET["{$parent}_delete"]) && ($_SESSION['control'] == $_GET['control'])){
		$id = $_GET['activity_delete'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `{$parent}` WHERE `id`='{$id}'") or die('Failed to delete record' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}
	
	if($parent ===''){
		
		if(isset($_GET['fundraiser_name'])){
			$parent = 'fundraiser';
		} else if(isset($_GET['contest_name'])){
			$parent = 'contest';
		} else if(isset($_GET['project_name'])){
			$parent = 'project';
		}
	}
	# post_limit routing
	
	if(!empty($_POST["{$parent}_parent"])){
		$_SESSION['activity_parent'] = $_POST['activity_parent'];
		}
		
	if(isset($_POST["{$parent}_parent_name_holder"])){
		$_SESSION["{$parent}_parent_name_holder"] = $_POST["{$parent}_parent_name_holder"];
		}
		
	if(isset($_POST["{$parent}_limit"])){
		$_SESSION["{$parent}_limit"] = $_POST["{$parent}_limit"] + $_POST["{$parent}_number_holder"];
		}
	if(isset($_POST["{$parent}_number_holder"])){
		$_SESSION["{$parent}_number_holder"] = $_POST["{$parent}_number_holder"];
		}
		
	if(isset($_POST["clear_{$parent}_session_values"])){
		
		unset($_SESSION["{$parent}_parent"]);
		unset($_SESSION["{$parent}_number_holder"]);
		unset($_SESSION["{$parent}_limit"]);
		unset($_SESSION["{$parent}_parent_name_holder"]);
	}
		# SET QUERY VALUES
		
	if(isset($_SESSION["{$parent}_parent"])){
	$parent = $_SESSION["{$parent}_parent"];
	}
			
	if(isset($_SESSION["{$parent}_limit"])){
	$limit = $_SESSION["{$parent}_limit"];
	}
	
		if(isset($_SESSION["{$parent}_number_holder"])){
		$_SESSION["{$parent}_limit"] = $limit + $_SESSION["{$parent}_number_holder"];
		$number_holder = $_SESSION["activity_limit"];
		$_POST["{$parent}_limit"]=''; 
		$condition2 = ' LIMIT 0, {$_SESSION["{$parent}_limit"]}';
		}
	
	 else { 
		 $limit = 10; 
		 $condition2 = " LIMIT 0, {$limit}";
		 }
		 
	if($parent!==''){$condition = " WHERE `parent`='{$parent}'";
	}else{$condition = "";} 
		# DO QUERY
		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `{$parent}`{$condition} ORDER BY `id` DESC {$condition2}")
	 	or die("FAiled to1 fetch {$parent} !" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 	
	 	$count = mysqli_num_rows($query);
	 	if($count < 1){
			echo '<div class="main-content-region">';
			status_message('alert','No post!');
			
		}else{
	 	
	 	
	 	
	 	
	 	while($result = mysqli_fetch_array($query)){
			
			echo '<a href="'.$result["{$parent}_name"].'">'. $result['actor'].'</a>'
					.$result['action'].' <a href="'.$result['subject_path'].'">'.str_ireplace('-',' ',$result['subject_name']).'</a> '
					." <time class='timeago' datetime='".$result['date']."'>".$result['date']."</time>";
					
					if(is_admin()){
					 echo "<span class='tiny-edit-text'><a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&activity_delete=".$result['id']."&control=".$_SESSION['control']."'><em>delete</em></a></span>";
					}echo " <br><hr>";
			}
		}
	
	echo "<div class='show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='hidden' name='activity_limit' value='".$limit."'>
		<input type='hidden' name='activity_number_holder' value='".$number_holder."'>
		<input type='hidden' name='activity_parent' value='".$parent."'>
		<input type='submit' name='submit' value='show me more' class='button-primary'>
		<input type='submit' name='clear_activity_session_values' value='reset'>
		</form></div>";
		
		echo "</div>";
	
	
	
	}
	

function pagerize($start='',$show_more='10'){ 
	//to use $limit = $_SESSION['pager_limit'];
	
	if(isset($_POST['show_more'])){
	$_SESSION['pager_number_holder'] = mysql_prep($_POST['show_more']) + $_SESSION['pager_start'];
	$_SESSION['pager_show_more'] = mysql_prep($_POST['show_more']);
	$_SESSION['pager_calling_page'] = mysql_prep($_POST['pager_calling_page']);
	$_SESSION['pager_switch'] = 1;
	}
	
	if($start ==='' && isset($_SESSION['pager_number_holder'])){
		$_SESSION['pager_start'] = $_SESSION['pager_number_holder'];
		} else if(isset($_POST['show_more'])){
			$_SESSION['pager_start'] = mysql_prep($_POST['show_more']);
			} else {
				$_SESSION['pager_start'] = '0';
				}
		
	if(isset($_POST['show_more'])){
		$_SESSION['pager_show_more'] = mysql_prep($_POST['show_more']);
		} else {
			$_SESSION['pager_show_more'] = $show_more;
			}
		

	$_SESSION['pager_limit'] = "LIMIT {$_SESSION['pager_start']}, {$_SESSION['pager_show_more']}" ;
	// Reset session values
	if(isset($_POST['clear_pager_session_values']) || 
	($_SESSION['pager_calling_page'] != $_SESSION['current_url'] && $_SESSION['pager_switch'] == 1)){
		$_SESSION['pager_start'] = '0';
		$_SESSION['pager_show_more'] = '0';
		$_SESSION['pager_number_holder'] ='0';
		$_SESSION['pager_switch'] = 0;
		unset($_POST['show_more']);
		redirect_to($_SESSION['current_url']);
		}
	
	$output = "<div class='clear show-more'>
		<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>".
		"<input type='submit' name='submit' value='show me more' class='button-primary'>
		<input type='text' name='show_more' value='".$show_more."'>
		<input type='hidden' name='pager_calling_page' value='".$_SESSION['current_url']."'>
		<input type='submit' name='clear_pager_session_values' value='reset'>
		</form></div>";
		
	return $output;	
	
}


function go_back($location =''){
	
	if($location ===''){
	echo "<span class='clear-bottom text-center'><a href='" .$_SERVER['HTTP_REFERER'] ."'> Go BACK</a></span>";
	} else {
		echo "<span class='clear-bottom text-center'><a href='" .$location ."'> Go BACK</a></span>";
		}
	}


function deny_access(){
	 status_message("alert","You do not have Permission to access this area!");
	 
	}
	
function log_in_to_continue(){
	if(!is_logged_in()){
	 echo "<div class='main-content-region'><p align='center'><a href='".BASE_PATH."user?redirect_to=".$_SESSION['current_url']."'>Log in </a> 
	 or <a href='".BASE_PATH."user?action=register&redirect_to=".$_SESSION['current_url']."'>Signup </a> to continue .</p></div>";
		}
	}

function log_in_to_comment(){
	 echo "<p align='center'>You must <a href='".BASE_PATH."user?redirect_to=".$_SESSION['current_url']."'>Log in </a> 
	 or <a href='".BASE_PATH."user?action=register&redirect_to=".$_SESSION['current_url']."'>Signup </a> to comment .</p>";
	}

function are_you_lost(){
	
	if(empty($_SESSION['username'])){
		echo '<div class="container">
				<h2 align="center">Are you lost?</h2><br>
				<p align="center">  &nbsp	 		
				<a href="'.BASE_PATH.'"> Go Home </a>&nbsp|&nbsp
				<a href="'.BASE_PATH.'admin"> Go to admin area </a><br>
				<a href="'.ADDONS_PATH.'shop/cart.php"> Go to shopping cart </a>&nbsp or&nbsp
				<a href="'.ADDONS_PATH.'shop/catalog"> Check out the Catalog </a></p></div>';
				}
	}
	

function do_search($table='', $column=''){
	#print_r($_SESSION); // testing

		if(isset($_SESSION['search_table'])){
			$table = $_SESSION['search_table'];
			
			$valid = array('page','contest','fundraiser','jobs','user','project_manager_project','project_manager_task','project_manager_suggestion','project_manager_ticket','company');
			
			if(! in_array($table,$valid)){
				$table = 'page';
			}
		}
			
		if(!empty($_SESSION['do_search'])){
			
			if($_SESSION['search_term'] !== ''){
			$string = str_ireplace(' ','-',trim(mysql_prep($_SESSION['search_term'])));
			} else { $string = ''; }
			
			if($column === ''){
			$column = $table."_name";
			} else if(isset($_SESSION['search_column'])){
				$column = $_SESSION['search_column'];
				}
			
			$route ='';
			$route2  = '';

			if($string !==''){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `{$table}` WHERE `{$column}` LIKE '%{$string}' LIMIT 0, 10") or die
			("Search Failed !". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			$num =mysqli_num_rows($query);
			if($num < 1){
				$num =0;
				echo "<strong>{$num}</strong> results found ! ";
				echo "<br>No results were found for your search";
			} else {
				echo 'You searched for <span class="green-text"><em>'.$string.'</em></span><br>
			<strong>'.$num.'</strong> results found ! <hr><br>
			<h2> Results are :</h2><ol>';
				}
			
			while($result = mysqli_fetch_array($query)){
			
			//print_r($result);
			if(isset($result['user_name'])){
			$route = 'user/?' ;	
			$route2 = 'user';
			$route3 = '';
			} else if(isset($result['fundraiser_name'])){
			$route = 'addons/fundraiser/'.'?action=show&';
			$route2 = str_ireplace(' ','-',$column);
			$route3 = '&jid='.$result['id'];
			} else if(isset($result['project_name'])){
			$route = 'addons/project_manager/'.'?action=show&';
			$route2 = str_ireplace(' ','-',$column);
			$route3 = '';
			} else if(isset($result['contest_name'])){
			$route = '/addons/contest/?' ;	
			$route2 = 'contest_name';
			$route3 = '';
			} else if(isset($result['title'])){
			$route = '/addons/jobs/?' ;	
			$route2 = 'job_title';
			$route3 = '&jid='.$result['id'];
			} else if(isset($result['page_name'])){
				if($result['page_type'] === 'contest'){
				$route = 'addons/contest/?' ;	
				$route2 = 'contest_name';
				$route3 = '';
				} else if($result['page_type'] === 'event'){
				$route = '/addons/event/?' ;	
				$route2 = 'event_name';
				$route3 = '';
				} else {
					$route = '?page_name' ;
					$route2 = '';
					$route3 = '';
					}
			
		}
			
			echo '<li><a href="'.BASE_PATH.$route.$route2.'='.str_ireplace(' ','-',$result["{$column}"]) .$route3.'&tid='.$result['id'].'">'.ucfirst(str_ireplace('-',' ',$result["{$column}"]))."</a> <small><em>";
			if (isset($result['page_type'])){
			echo $result['page_type'];
			}
			echo "</em></small></li><hr>";
				} echo '<ol>'; 
			}
		
			$_SESSION['search_term'] ='';
			
		}
	}

	
function show_search_form($table='',$column=''){
	if(is_logged_in()){
		echo '<div class="search-region">
		<div class="search"><form method="post" action="'.BASE_PATH.'bouncer.php">';
		
	if(!empty($_GET['page_name']) || !empty($_SESSION ['route']['page_name'])){
	$table= 'page';
	} else if(!empty($_GET['fundraiser_name']) || !empty($_SESSION ['route']['fundraiser_name'])){
	$table = 'fundraiser';
	} else if(!empty($_GET['user']) || !empty($_SESSION ['route']['user'])){
	$table = 'user';
	} else if(!empty($_GET['section_name']) || !empty($_SESSION ['route']['section_name'])){
	$table = $_GET['section_name'];
	}
	echo '
	<input type="hidden" name="destination" value="'.BASE_PATH.'search.php">
	<input type="hidden" name="table" value="'.$table.'">
	<input type="hidden" name="column" value="'.$column.'">
	<input type="text" name="search_term" value="" placeholder="">
	<input type="submit" name="do_search" value="search" class="submit">
	</form></div></div>
	
	<div id="search-toggle">Search</div>';	
	
	}
}
	
function show_search_special_form($table='',$column=''){
	$destination ='http://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
		echo '<form method="post" action="'.BASE_PATH.'bouncer.php">
	<input type="hidden" name="destination" value="'.$destination.'">
	<input type="hidden" name="table" value="'.$table.'">
	<input type="hidden" name="search_column" value="'.$column.'">
	<input type="text" name="search_term" value="" placeholder="Your text here">
	<input type="submit" name="do_search" value="search" class="submit">
	</form>';
	
	}
	

function curl_get($url=''){	
	$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => sprintf($url, $name, $mobNo), 
));
$response = curl_exec($curl);
curl_close($curl);
return $response;

}

function _isCurl(){
	var_dump(curbl_version());
 return function_exists('curl_version');
}
 // Form functions
function form_start($method='post',$action=''){
	if($action ==''){
		$action = $_SESSION['current_url'];
		}
	echo '<form method="'.$method.'" action="'.$action.'">';
	}
	

function currency_filter($amount=''){
	
		echo SITE_CURRENCY ;
		echo number_format($amount,2,'.',',');
	}
	
function call_to_action_front(){

	//center_start();
	if(addon_is_active('fundraiser')){
	go_to_fundraiser(); 
	}
	if(addon_is_active('project_manager')){
	go_to_project_manager();
	}
	if(addon_is_active('funds_manager')){
	fund_account_link(); 
	}
	//center_stop();

}

function not_found_error(){
	if(isset($_GET['error'])){
		if($_GET['error'] === '404'){
			echo "<h2>Hmmn... thats strange, <br> I could not find the page you were looking for</h2>";
			}
		}
	}

#  ADMIN FOOTER

function do_admin_footer() {
	
  echo "" .
	"<script src='" .BASE_PATH ."libraries/jquery/jquery-1.11.2.min.js'></script>" .
	"<script src='" .BASE_PATH ."libraries/uikit/js/uikit.min.js'></script>" .
	"<script src='" .BASE_PATH ."libraries/nivo-slider/jquery.nivo.slider.pack.js'></script>".
	"<script type='text/javascript' src='" .ADMIN_PATH .'/' ."scripts/script.js'></script>" .
	"Wanni CMS" .
	"" .
	"</body>" . 
	"</html>";
    
# Close connection
# do this at the end of the page
if(isset($connection)) { ((is_null($___mysqli_res = mysqli_close($connection))) ? false : $___mysqli_res); }

}


function restrict_config_to_admin(){
	if((url_contains('config/index.php') || url_contains('config.php') || url_contains('/config')) && !is_admin()){
		go_back(); 
		echo '<br>';
		deny_access();
		die();
		}
	}
restrict_config_to_admin();


?>
