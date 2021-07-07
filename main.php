<?php
require_once("./twitteroauth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;
require_once('./public_html/okinotori/wp-load.php');

$consumerKey       = "";
$consumerSecret    = "";
$accessToken       = "";
$accessTokenSecret = "";
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$lastarticle = get_last_article('post');
$tweet = $lastarticle->post_title." ".esc_url(get_permalink($lastarticle->ID));
$result = $twitter->post("statuses/update", array('status' => $tweet));
write_log($tweet, $twitter->getLastHttpCode() == 200);

/*最新記事取得関数*/
function get_last_article($post_type) { 
  $args = array(
    'posts_per_page' => 1, 
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => $post_type,
    );
  $my_posts = get_posts( $args );
  wp_reset_postdata();
  return $my_posts[0];
}

/*ログ出力関数*/
function write_log($tweet, $isSuccess){ 
  date_default_timezone_set('Asia/Tokyo');
  $now = date("Y/m/d H:i:s");
  $msg = $isSuccess ? $tweet : "tweet failed";
  $log = $now."\t".$msg."\n";
  file_put_contents("./twitter-log.txt", $log, FILE_APPEND);
}
?>