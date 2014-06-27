<?php 

// This defines the way the articles parser reads websites

$daily_star_old = array(
	'method'			=> '2', // this method for sites where the articles excerpts and titles are not grouped semanitcally
	'articles_wrapper'	=>	'#ctl00_ContentPlaceHolder1_tblDetails',
	'title'				=>	'td.AuthorArticleTitle',
	'link'				=>	'td.AuthorArticleTitle a',
	'link_prefix'		=>	'http://dailystar.com.lb', //for relative urls
	'timestamp'			=>	'td.textdate',
	'excerpt'			=>	'td.CategoryMainDetails',
	'article_body'		=>	'#ctl00_ContentPlaceHolder1_spanDetails', // The container where we can espect to find the image
	'img_prefix'		=>	'', // for relative image urls
	'content_body'		=>  '#ctl00_ContentPlaceHolder1_spanDetails',//This could be different from the article body to prevent storing comments in content
);

$daily_star = array(
	'method'			=> '1', // this method for sites where the articles excerpts and titles are not grouped semanitcally
	'articles_wrapper'	=>	'ul.more-news li',
	'title'				=>	array('h4',0),
	'link'				=>	array('a',0),
	'link_prefix'		=>	'http://dailystar.com.lb', //for relative urls
	'timestamp'			=>	array('.value-datetime',0,0,13),
	'excerpt'			=>	array('p',0),
	'article_body'		=>	'', // The container where we can espect to find the image
	'img_prefix'		=>	'http://dailystar.com.lb', // for relative image urls
	'content_body'		=>  '#divDetails',//This could be different from the article body to prevent storing comments in content
);

$now_lebanon = array(
	'method'			=>	'1', // this method is for semantically grouped titles and excerpts
	'articles_wrapper'	=>	'div.author_profile_listing_content',
	'title'				=>	array('h2.author_profile_listing_title',0),
	'link'				=>	array('a',0),
	'link_prefix'		=>	'', //for relative urls
	'timestamp'			=>	array('p.author_profile_listing_date',0),
	'excerpt'			=>	array('p.author_profile_listing_txt',0),
	'article_body'		=>	'div.article_main_section', // The container where we can espect to find the image
	'img_prefix'		=>	'http://now.mmedia.me', // for relative image urls
	'content_body'		=>  'div.article_main_section', //This could be different from the article body to prevent storing comments in content
);

$the_national = array(
	'method'			=>	'1',
	'articles_wrapper'	=>	'.artical-box-without-image',
	'title'				=>	array('h4',0),
	'link'				=>	array('a',0),
	'link_prefix'		=>	'http://www.thenational.ae', //for relative urls
	'timestamp'			=>	array('p span', 0),
	'excerpt'			=>	array('p',1),
	'article_body'		=>	'.article-body-page', // The container where we can espect to find the image
	'img_prefix'		=>	'', // for relative image urls
	'content_body'		=>  '.article-body-page',//This could be different from the article body to prevent storing comments in content
);

$al_akhbar = array(
	'method'			=>	'1',
	'articles_wrapper'	=>	'.views-row',
	'title'				=>	array('.views-field-title span.field-content',0),
	'link'				=>	array('a',0),
	'link_prefix'		=>	'http://english.al-akhbar.com/', //for relative urls
	'timestamp'			=>	array('.date-display-single', 0),
	'excerpt'			=>	array('.views-field-teaser span.field-content',0),
	'article_body'		=>	'.node-type-story', // The container where we can espect to find the image
	'img_prefix'		=>	'', // for relative image urls
	'content_body'		=> 	'.content-wrap', //This could be different from the article body to prevent storing comments in content
);

$beirutcityguide = array(
	'method'			=>	'1',
	'articles_wrapper'	=>	'.list-rows .post',
	'title'				=>	array('h3',0),
	'link'				=>	array('a',0),
	'link_prefix'		=>	'http://beirut.com', //for relative urls
	'timestamp'			=>	array('.cnt li',1, 3, 12), // the last item is to shop off the "on " in "on jan 22, 2014"
	'excerpt'			=>	array('.cnt p',0),
	'article_body'		=>	'.profile', // The container where we can espect to find the image
	'img_prefix'		=>	'', // for relative image urls
	'content_body'		=> 	'.profile', //This could be different from the article body to prevent storing comments in content
);


$lorientlejour = array(
	'method'			=>	'1',
	'articles_wrapper'	=>	'.articletext',
	'title'				=>	array('h2',0),
	'link'				=>	array('a',0),
	'link_prefix'		=>	'http://www.lorientlejour.com/', //for relative urls
	'timestamp'			=>	array('p.date', 0), // the last item is to shop off the "on " in "on jan 22, 2014"
	'excerpt'			=>	array('',0),
	'article_body'		=>	'article', // The container where we can espect to find the image
	'img_prefix'		=>	'', // for relative image urls
	'content_body'		=> 	'article', //This could be different from the article body to prevent storing comments in content
);

?>