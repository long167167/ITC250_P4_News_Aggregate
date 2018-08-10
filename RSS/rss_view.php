<?php
/**
 * RSS_view.php is a page to demonstrate the proof of concept of the 
 * initial RSS FEED objects.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package RSS FEED
 * @author Long Ding
 * @version 2.12 2018/08/08
 * @link http://han0919.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Question.php
 * @see Answer.php
 * @see Response.php
 * @see Choice.php
 */ 

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include '../RSS/rssfeed/Category.php';
spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects
$config->metaRobots = 'no index, no follow';#never index survey pages

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "RSS/index.php");
}

$myCategory = new Category($myID); //MY_category extends survey class so methods can be added

if($myCategory->isValid)
{
	$config->titleTag = "'" . $myCategory->CategoryName . "' RSS!";
}else{
	$config->titleTag = smartTitle(); //use constant 
}
#END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3><?=$myCategory->CategoryName;?></h3>

<?php

if($myCategory->isValid)
{ #check to see if we have a valid SurveyID
	echo '<p>' . $myCategory->CategoryDescription . '</p>';
	echo $myCategory->showSubcategory();
}else{
	echo "Sorry, no such RSS!";	
}

get_footer(); #defaults to theme footer or footer_inc.php


