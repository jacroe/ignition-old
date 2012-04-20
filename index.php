<?php
/* BEGIN SETTINGS */
error_reporting(0);
$ign_title = "An Ignition Blog";
$ign_subtitle = "A single-file, flat-file blogging system";
$ign_template = "default";
$ign_timezone = "America/Chicago";
$ign_filesys = 1;
/* END SETTINGS */

/* BEGIN FRONTEND */
if ($_GET['p'] == 'feed')
{
	header('Content-type: application/rss+xml');
	$url = str_replace('?p=feed', '', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	$i = 0;
	
	echo('<?xml version="1.0" encoding="UTF-8"?>');
?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="http://<?php echo $url; ?>?p=feed" rel="self" type="application/rss+xml" />
	<title><?php echo $ign_title; ?></title>
	<link>http://<?php echo $url; ?></link>
	<description><?php echo $ign_subtitle; ?></description>
<?php
	foreach (array_reverse(glob('posts/*.txt')) as $post)
	{
		$post = str_replace('posts/', '', $post);
		$post = str_replace('.txt', '', $post);
		echo("<item>
<title>".ign_get_post($post, 'title')."</title>
<link>http://".$url."?p=".$post."</link>
<guid>http://".$url."?p=".$post."</guid>
<pubDate>".ign_get_post($post, 'pubdate')."</pubDate>
<description><![CDATA[".ign_get_post($post, 'post')."]]></description>
</item>
");
		$i++;
		if ($i == 5) break;
	}
?>
</channel>
</rss>

<?php
}
elseif ($_GET['p'])
{
	$post = $_GET['p'];
	if (file_exists('posts/'.$post.'.txt'))
	{
		ign_header_post($post);
		echo ign_get_post($post, 'post')."</section>";
	}
	else ign_404($ign_title);
	
	ign_footer();
}
else
{
	ign_header_main($ign_title, $ign_subtitle);
	foreach (array_reverse(glob('posts/*.txt')) as $post) {
		$post = str_replace('posts/', '', $post);
		$post = str_replace('.txt', '', $post);
		if (ign_get_post($post, 'type') == 'post') {
			echo '<section class="post">'."\n";
			echo '<h1><a href="?p='.$post.'">'.ign_get_post($post, 'date').' - '.ign_get_post($post, 'title').'</a> </h1>'."\n";
			echo '<p>'.ign_get_post($post, 'excerpt').'</p>'."\n";
			echo '</section>'."\n\n";}
		if (ign_get_post($post, 'type') == 'aside') {
			echo '<section class="aside">'."\n";
			echo '<p><strong><a href="?p='.$post.'">'.ign_get_post($post, 'title').'</a></strong> - '.ign_get_post($post, 'excerpt');
			echo '</p>'."\n</section>\n\n"; }
	}
	ign_footer();
}

/* END FRONTEND */


/*
BEGIN TEMPLATE
Author: Jacob Roeland
Last update: 12/25/2011 03:28:14 AM 
*/
function ign_header_main($title, $subtitle) {
?>
<!DOCTYPE html> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title><?php echo $title; ?></title> 
<link rel="stylesheet" href="./files/css.css" /> 
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="./?p=feed" /> 
<!--[if lt IE 9]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
</head> 
<body>
<nav>
Home
</nav>
<header>
<p class="hi">Hi. This is</p>
<h1><?php echo $title;?></h1> 
<p class="hi"><?php echo $subtitle; ?></p> 
</header>
<article>

<?php
}


function ign_header_post($post) {
?>
<!DOCTYPE html> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title><?php echo ign_get_post($post, 'title'); ?></title> 
<link rel="stylesheet" href="./files/css.css" /> 
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="./?p=feed" /> 
<!--[if lt IE 9]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
</head> 
<body>
<nav>
<a href=?>Home</a> > <?php echo ign_get_post($post, 'title'); ?>
</nav>
<header>
<p class="hi">Hi. You're reading</p>
<h1><?php echo ign_get_post($post, 'title'); ?></h1>
<p class="hi"><?php echo ign_subtitle(); ?></p>
</header>
<article>
<h1>This post was written by <?php echo ign_get_post($post, 'author'); ?> on <?php echo ign_get_post($post, 'date'); ?> in <?php echo ign_get_post($post, 'location'); ?></h1>
<section>
<?php
}

function ign_footer() {
?>
</article>
<footer>
<p class="hi">I like to make things.<br />
Sometimes I write in haiku.<br />
But not now; that's just silly.</p>
</footer>
</body> 
</html> 
<?php 
}


function ign_subtitle() {
	$lines = "I hope you're not disappointed; I know I was.
This is just some random crap I threw together at the last minute.
I chose this topic in the same way I make every decision: with darts and a wall of ideas.";
	$lines = explode("\n", $lines);
	$chosen = $lines[ mt_rand(0, count($lines) - 1) ];
	return $chosen;
}
function ign_404($title) {
?>
<!DOCTYPE html> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title><?php echo $title; ?> - Not Found</title> 
<link rel="stylesheet" href="./files/css.css" /> 
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="./?p=feed" /> 
<!--[if lt IE 9]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
</head> 
<body>
<nav>
<a href=./>Home</a> > 404
</nav>
<header>
<p class="hi">Well... um... you see</p>
<h1>404 - Not Found</h1> 
<p class="hi">This is awkward.</p> 
</header>
<article>
<h1>BURMA!!!</h1>
<p>Sorry, I panicked. Anyways, it appears that you were looking for something that doesn't exist. I know you may think you're perfect and everything but everyone make mistakes. One of the following most likely happened:</p>
<ul>
<li><strong>The page in question was deleted.</strong> This could be by accident or on purpose for a variety of reasons (stupidity, embarrassment, etc). This was not your fault.</li>
<li><strong>The URL is misspelled/wrong.</strong> Another possibilty is that the content you want does exist but the URL is wrong. If you manually entered it, double check to make sure it's all correct.</li>
<li><strong>UFOs.</strong> You never know...</li>
</ul>
<p>It's possible you can find what you're looking for by clicking the "Home" link at the very top and try to find it that way. Don't give up, Mitch. Please try again. A message of hope from your friends at Yoplait.</p>
</article>
<?php
}
/*
END TEMPLATE
*/

/*
BEGIN PLUGIN FILESYS
Display called upon files and output content or something like that
Author: Jacob Roeland
Last update: 12/25/2011 03:17:52 AM 
*/
function filesys($matches) {

$file = $matches[1];
$filename = basename($file);
$filename = explode('.', $filename);
$type = strtolower($filename[count($filename)-1]);
global $uri;

// mp3 files
// http://www.1pixelout.net/code/audio-player-wordpress-plugin/
if ($type == "mp3") {
return('
<object type="application/x-shockwave-flash" data="./files/mp3player.swf" id="audioplayer1" height="24" width="290">
<param name="movie" value="./files/mp3player.swf">
<param name="FlashVars" value="playerID=1&amp;soundFile='.$file.'">
<param name="quality" value="high">
<param name="menu" value="false">
<param name="wmode" value="transparent">
</object>'); }


// Video files (flv, mp4, mpg?)
// http://www.longtailvideo.com/players/jw-flv-player/
if ($type == "flv"  || $type == "mp4" || $type == "mpg") {
return('
<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="500" height="400">
<param name="movie" value="./files/jwplayer.swf" />
<param name="allowfullscreen" value="true" />
<param name="allowscriptaccess" value="always" />
<param name="flashvars" value="file='.$file.'" />
<object type="application/x-shockwave-flash" data="./files/jwplayer.swf" width="500" height="400">
<param name="movie" value="./files/jwplayer.swf" />
<param name="allowfullscreen" value="true" />
<param name="allowscriptaccess" value="always" />
<param name="flashvars" value="file='.$file.'" />
</object>
</object>
'); 
}

// Image files (jpg, png, gif)
if ($type == "jpg" || $type == "png" || $type == "gif") {
return('
<img src="http://thumbthumbthumb.appspot.com/t?image_url='.$file.'&width=200" alt="Image" class="floatRight" />');
}

// PDF Documents
if ($type == "pdf") {
//return('<iframe src="http://docs.google.com/viewer?url='.$file.'&embedded=true" style="width:800px; height:700px;" frameborder="0"></iframe>');
return '(View <img src=./files/pdf.png />PDF in <a href="http://docs.google.com/viewer?url='.$file.'" target=_blank>Google Docs</a>)';
}
}

/*
END PLUGIN FILESYS
*/ 

/*
BEGIN BASE IGNITION
EDIT NOTHING BELOW THIS LINE
*/
date_default_timezone_set($ign_timezone);

if (get_magic_quotes_gpc()) {
  if (!empty($_GET))    remove_magic_quotes($_GET);
  if (!empty($_POST))   remove_magic_quotes($_POST);
  if (!empty($_COOKIE)) remove_magic_quotes($_COOKIE);
  if (!empty($_REQUEST)) remove_magic_quotes($_REQUEST);
  if (!empty($_SESSION)) remove_magic_quotes($_SESSION);
  ini_set('magic_quotes_gpc', 0);
}
set_magic_quotes_runtime(0);
 
function remove_magic_quotes(&$array) {
  foreach (array_keys($array) as $key) {
    if (is_array($array[$key])) {
      remove_magic_quotes($array[$key]);
    }else {
      $array[$key] = stripslashes($array[$key]);
    }
  } 
}

function ign_get_post($post, $type) {
	global $ign_filesys;
	$raw = file_get_contents('posts/'.$post.'.txt');
	$data = explode('|', $raw);
	if ($type == 'title')
		return $data[0];
	if ($type == 'author')
		return $data[1];
	if ($type == 'date')
		return ($data[2].' '.$data[3].', '.$data[4]);
	if ($type == 'location')
		return $data[5];
	if ($type == 'type')
		return $data[6];
	if ($type == 'excerpt')
		return $data[7];
	if ($type == 'post') 
	{
		if ($ign_filesys)
		{
			$post = preg_replace_callback ("/\{\{([a-zA-Z_0-9\.\-\/\:]+)\}\}/", filesys, $data[8]);
			return $post;
		}
		else
		{
			return $data[8];
		}
	}
	if ($type == 'pubdate')
		return $data[9];
}
function ign_write_post($data, $filename) {
	if (!ign_writable_posts()) 
		return false;
	else {
		file_put_contents('posts/'.$filename.'.txt', $data);
		return true;
	}
}
function ign_writable_posts() {
	if (!is_writable('posts/')) return false; else return true;
}
/* 
END BASE IGNITION
*/
?>
