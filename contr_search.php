<?php 

require_once('init.php');

$term = $_POST['term'];
$scope = $_POST['scope'];

switch ($scope) 
{
	case 'blognames': // searching for blognames
		$results = Lb_Search::searchBlogNames($term);
		$howmany = count($results);
		if ($howmany>0) //there are results
		{ 
			foreach ($results as $result) 
			{
        Render::drawFeaturedBlogger($result->blog_id);
			}
		} 
	break;
	
	case 'blogtitles':
		$results = Lb_Search::searchBlogTitles($term);
		$howmany = count($results);
		if ($howmany>1) //there are results
		{ 
			Render::drawCards($results);
		} 
		
	break;	

	case 'blogcontents':
		$results = Lb_Search::searchBlogContents($term);
		$howmany = count($results);
		if ($howmany>1) //there are results
		{ 
			Render::drawCards($results);
		} 
		
	break;	

	default:
		# code...
		break;
}

?>