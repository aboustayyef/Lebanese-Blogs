<?php 
require_once('init.php');

/*$data = Posts::get_top_posts(52, 5, 'politics');


echo "<pre>";
	var_dump($data);
echo "</pre>";*/
test();

function test(){

if (Posts::hasClicked('192.168.1.101', 'http://www.ultgate.com/3898/apples-itunes-charged-me-2-99-for-a-free-application')) {
	echo 'YES';
	# code...
}else {
	echo 'NO';
}

}


/*$users->get('posts', array('post_url','=','http://www.thenational.ae/thenationalconversation/comment/assads-position-strengthens-in-lead-up-to-geneva-talks'));
echo "<pre>";
	print_r($users->results());
echo "</pre>";*/

/* To insert */

/*$users->insert('posts', array(
	'post_url'	=>	'http://test.testy',
	'post_title'	=>	'This is a test',
	'post_image'	=>	'http://test.testyimage/theimage.jpg',
	'post_excerpt'	=> ' Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet.',
	'blog_id'		=>	'myoung_ds',
	'post_timestamp'	=> '1486199860',
	'post_content'	=> 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam, eum, necessitatibus et in quasi quis consectetur perferendis modi adipisci quam dicta voluptates ratione perspiciatis minus fuga enim suscipit sequi odio culpa mollitia libero voluptas eos laudantium tenetur ipsum atque beatae magni facilis temporibus veritatis repudiandae qui ad neque! Dolore, aliquam, commodi, consectetur esse quis ratione minus fugit harum veniam delectus qui et molestiae possimus. Mollitia, blanditiis ipsum dignissimos excepturi aliquam natus odio non magni ullam dolor? Possimus nam ipsa a eius provident odio iure esse neque maiores ipsam. Vel, soluta id laborum ipsam dicta cupiditate nesciunt corrupti a assumenda delectus?',
	'post_image_height'	=> '300',
	'post_image_width'	=>	'600'
));

echo "<pre>";
	print_r($users->results());
	print_r($users->count());
echo "</pre>";

*/

/*$url = "http://smileyface80.wordpress.dcom/2013/03/03/%D8%A7%D9%84%D9%88%D8%B1%D8%AF-%D8%AC%D9%85%D9%8A%D9%84-%D8%A3%D9%85-%D9%83%D9%84%D8%AB%D9%88%D9%85/";

if (Posts::postExists($url)) {
	echo 'This post exists';
} else {
	echo 'This post Does Not Exists';
}
*/


/*$users->get('columnists', array('col_name','=','Michael Young'));
echo "<pre>";
	print_r($users->results());
echo "</pre>";*/

/*$blog_id = 'beirutspring';
echo Posts::get_blog_name($blog_id);*/


/*$user_id = '812400331';
$post_url = 'http://ginosblog.com/2013/11/19/amazing-graffiti-series-continues-in-ain-el-remmeneh/';

if (Posts::isSaved($user_id, $post_url)) {
	echo 'This post is saved';
}else{
	echo 'This post is not saved';
}*/

/*$data = Posts::get_saved_bloggers_posts('812400331', 0, 5);
echo "<pre>";
var_dump($data);
echo "</pre>";*/


// Test getIdFromFacebookId
/*
$id = Users::getIdFromFacebookId('2147483647');
echo "Facebook User Id '2147483647' corresponds to Lebanese Blogs ID $id ";
*/

// Test toggleFavorites
/*$user_facebook_id = '812400331';
$blog_id = 'blogbaladi';

Posts::toggleFavorite($user_facebook_id, $blog_id);*/

// Test for isFavorite method
/*$user_facebook_id = '812400331';
$blog_id = 'qifanabki';

if (Posts::isFavorite($user_facebook_id, $blog_id)) {
	echo $blog_id.' is a favorite of '.$user_facebook_id;
} else {
	echo 'Not a favorite';
}*/


/*$users = DB::getInstance();
$users->get('users', array('user_first_name','=','Mustapha'));
echo "<pre>";
	print_r($users->results());
echo "</pre>";

echo $users->results()[0]->user_id;*/

//Posts::get_favorite_bloggers_posts(1, 0, 20);




//$users = DB::getInstance();

/* To insert */

/*echo $users->insert('users', array(
	'user_first_name'	=>	'Mustapha',
	'user_last_name'	=>	'Hamoui',
	'user_email'		=>	'mustapha.hamoui@gmail.com'
	));*/

/* To Update:

echo $users->update('users',1, array(
	'user_first_name'	=>	'Mustapha',
	'user_last_name'	=>	'Hamoui',
	'user_email'		=>	'mustapha.hamoui@gmail.com',
	'user_visit_count'	=>	1
	));

*/

?>