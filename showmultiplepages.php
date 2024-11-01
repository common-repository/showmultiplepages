<?php
/*
Plugin Name: ShowMultiplePages
Plugin URI: http://www.ma.xnowack.de/
Description: Show multiple pages on a page. This plugin displays more than one page on a page.
Version: 0.1
Author: Max Nowack
Author URI: http://www.ma.xnowack.de/
*/

function mn_showMultiplePages()
{
	global $post;
	$pages = mn_getPages($post->post_content);
	
	if(count($pages)>0)
	{
		query_posts(array('post_type' => 'page','post__in' => $pages,'orderby' => 'menu_order'));
		echo "<!--";
		var_dump($wp_query);
		echo "-->";
	}
}

function mn_getPages($content)
{
	$content = str_replace("&lt;","<",$content);
	$content = str_replace("&gt;",">",$content);

	preg_match_all("@<!--pageID=([0-9]+)-->@is",$content,$pageIDs);
	preg_match_all("@<!--pagename=(.*)-->@Uis",$content,$pagenames);
	
	foreach($pagenames[1] as $name)
	{
		$pageIDs[1][] = mn_getIDbyPageName($name);
	}
	
	return $pageIDs[1];
}

function mn_getIDbyPageName($pagename)
{
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$pagename."'");
	return $page_name_id;
}

add_action('wp_head','mn_showMultiplePages');

?>