<?php
//subcategory_view.php
//abstract the data from the URL xml

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include '../RSS/rssfeed/Category.php';
spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects
$config->metaRobots = 'no index, no follow';#never index survey pages
$isValid= FALSE;
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "RSS/index.php");
}

get_header();


$sql = sprintf("select SubcategoryRSS from subcategories where SubcategoryID =%d",$myID);
$result = mysqli_query(\IDB::conn(),$sql) or die(trigger_error(mysqli_error(\IDB::conn()), E_USER_ERROR));
if (mysqli_num_rows($result) > 0)
		{#Must be a valid Category!
			$isValid = TRUE;
			while ($row = mysqli_fetch_assoc($result))
			{#dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			     $file = dbOut($row['SubcategoryRSS']);
			    
			}
		}
		@mysqli_free_result($result); #free resources
		
    

$xml = simplexml_load_file($file);
/*
echo '<pre>';
var_dump($xml);
echo '</pre>';
echo '</pre>';
*/


/*
echo '<pre>';
var_dump($zep);
echo '</pre>';
*/
$zep =$xml->xPath('channel/item');
echo '<h3><font color="red">' . $xml->channel->title . '</font></h3>';
echo '<div><a href ="' .$xml->channel->link . '"><img src = "' . $xml->channel->image->url . '"alt = "subcate pic" height="80px"></a></div>';
echo '<p><a href="' . $xml->channel->link . '">' . $xml->channel->title  . '</a></p>';
foreach($zep as $album)
{
    echo '<table bgcolor="#00FF00"> <tr><td><a href ="' . $album->link . '"><h4><font color="#DA6248"><em>' . $album->title . '</em></font><h4></a></td></tr>
                <tr><td>' . $album->description . '</td></tr>
                <tr><td><font color=#32A499>' . $album->pubDate . '</font></td></tr>
        
    
    
    
    
    
    
    </table><br>';
    //echo '<p>' . $album->description . '</p>';
    
}


get_footer();
?>



































?>