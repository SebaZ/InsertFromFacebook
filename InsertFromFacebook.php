<?php //

/*
Plugin Name: InsertFromFacebook
Plugin URI: 
Description: Wordpress plugin which allow you add blog posts from Facebook profile and page walls.
Version: 1.0.0
Author: SebaZ
Author URI: 
License: GPLv3
*/

/* 
Copyright (C) 2016 SebaZ
*/

add_action('admin_menu', 'iff_setup_menu');

function iff_setup_menu(){
    add_menu_page('Insert From Facebook', 'Insert From Facebook', 'manage_options', 'iff-plugin', 'iff_settings_page' );
    add_submenu_page('iff-plugin', 'Ustawienia Insert From Facebook', 'Ustawienia', 'manage_options', 'iff-plugin', 'iff_settings_page');
    
    $fbids_menu_params = get_option('fbids_menu_params');
    // if there is NO submenu params for FB feed 
    if($fbids_menu_params === false) {
        $fbids_menu_params = iff_updateMenuParamsFromFbids(); // rebuild it
    }
    // add menu for each FB ID
    foreach ($fbids_menu_params as $fbid => $fbname) {
        add_submenu_page('iff-plugin', "$fbname Insert From Facebook", $fbname, 'manage_options', $fbid.'--iff-plugin', 'iff_posts_list_page');
    }
}
 
function iff_settings_page(){
    if (isset($_POST['fbids'])) {
        $fb = explode("\n", trim(stripslashes(esc_textarea($_POST['fbids']))));
        foreach ($fb as $k => $v) {
            $fb[$k] = trim($v); //cleanup ids
        }
        $value = implode("\n", $fb);
        update_option('fbids', $value);
        $r = iff_updateMenuParamsFromFbids();
    }
    $value = get_option('fbids', $default = "205748262962024\n184965671567736\n475370040463\n271502918243\n449763581844923");
    
    $fbids_menu_params = get_option('fbids_menu_params');
    $fbnames = null;
    foreach($fbids_menu_params as $key => $v) {
        $fbnames .= $v."\n"; 
    }
  
    include('include/options.php');
}

function iff_posts_list_page(){
    $page = $_GET['page'];
    $fbid = str_replace('--iff-plugin', '', $page);
    $msg = '';
    $category = get_term_by('name', 'Facebook', 'category');
    
    if (isset($_POST['dodaj_post']) && $category !== false) {
        $post = array();
        $post['post_status']   = 'publish';
        $post['post_type']     = 'post';
        $post['post_title']    = wp_trim_words($_POST['fb_post_message'], $num_words = 8, $more = null);
        $post['post_content']  = $_POST['fb_post_message'];
        $post['post_category'] = array($category->term_id);
        //$post['post_date'] = date('c',strtotime('2010-04-08 13:46:43'));
        $post['post_date'] = $_POST['fb_post_date'];

        // Create Post
        $post_id = wp_insert_post( $post );        
        $image = media_sideload_image($_POST['fb_photo_url'], $post_id, $desc);        
        $media = get_attached_media('image', $post_id);
        foreach ($media as $media_id => $media_object) {
            set_post_thumbnail($post_id, $media_id);
        }
        
        add_post_meta($post_id, 'fb_post_id', $_POST['fb_post_id']);
        add_post_meta($post_id, 'fb_post_url', $_POST['fb_post_url']);
        add_post_meta($post_id, 'fb_attachment_id', $_POST['fb_attachment_id']);
        add_post_meta($post_id, 'fb_attachment_type', $_POST['fb_attachment_type']);
        add_post_meta($post_id, 'fb_attachment_url', $_POST['fb_attachment_url']);
        
        $msg = 'Wpis dodany!';
        unset($post);
    }     
    
    $posts = iff_getdataFromFID($fbid);
    $page_name = $posts['data'][0]['from']['name'];
    $posts = $posts['data'];
    
    include('include/posts_list.php');
}

//Get JSON object of feed data
function iff_getdataFromFID($fbid, $post_limit = 50) {
    // tokens from Custom Facebook Feed plugin code http://smashballoon.com/custom-facebook-feed
    $access_token_array = array(
        '772762049525257|UksMy-gYmk78WNHVEsimaf8uar4',
        '1611234219197161|PenH1iYmf3CShpuWiLMrP6_0mro',
        '842457575860455|MA2WQAK6MO22mYlD1vAfQmY-jNQ',
        '1598576770461963|t3KRNHf1490G8qEopdGoUiMPJ7I',
        '1774415812787078|3yGpMpgbH-Nte9YHCfVIQ59RIt8',
        '762305090538008|KmVsImjHmaJIPTpII9HyOif3yD0',
        '1741187749472232|b1ZfgQ2OSQZzsQN1lqLn4vjrQV4',
        '1748310315388907|AMSWRHgAoChtXepfsWU0OxKfVbQ',
        '1721409114785415|4dIAXp4_utfqkAJS-9X4OXB6GR4',
        '1609030662756868|nCKsZPN4cI-GsIJsi0DESGGtSgw',
        '1590773627916704|EbgBWG45AVQZdNrwsAnTl_-CW_A',
        '227652200958404|AzHtmm3B080elswwLKJrRCKYpGg',
        '1176842909001332|YIQehZhGPWxqkvmiFn4Klt1PA4U',
        '217933725249790|h4YSEYX71EO_2el93hiT47uyf5g',
        '823681761071502|0oAyJYz-MO-jgr8rI3ftrEcBRiQ'
    );

    $access_token = $access_token_array[rand(0, 14)];
    $graph_query = 'feed';
    
    $url = "https://graph.facebook.com/$fbid/$graph_query?fields=id,from,message,link,full_picture,attachments,source,type,created_time&access_token=$access_token&limit=$post_limit&locale=pl_PL";

    //Auto detect request method
    if(is_callable('curl_init')){
        $ch = curl_init();
        // Use global proxy settings
        if (defined('WP_PROXY_HOST')) {
          curl_setopt($ch, CURLOPT_PROXY, WP_PROXY_HOST);
        }
        if (defined('WP_PROXY_PORT')) {
          curl_setopt($ch, CURLOPT_PROXYPORT, WP_PROXY_PORT);
        }
        if (defined('WP_PROXY_USERNAME')){
          $auth = WP_PROXY_USERNAME;
          if (defined('WP_PROXY_PASSWORD')){
            $auth .= ':' . WP_PROXY_PASSWORD;
          }
          curl_setopt($ch, CURLOPT_PROXYUSERPWD, $auth);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        $feedData = curl_exec($ch);
        curl_close($ch);
    } elseif ( (ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) && in_array('https', stream_get_wrappers()) ) {
        echo 'file_get_contents <br />';
        $feedData = @file_get_contents($url);
    } else {
        $request = new WP_Http;
        $response = $request->request($url, array('timeout' => 60, 'sslverify' => false));
        if( is_wp_error( $response ) ) {
            $feedData = null;
        } else {
            $feedData = wp_remote_retrieve_body($response);
        }
    }
    
    return json_decode($feedData, true);
}

function iff_updateMenuParamsFromFbids() {
    $fbids_menu_params = false;
        
    // get take facebook IDs
    $fbids = get_option('fbids');
    if($fbids !== false) {
        $fbids = explode("\n", $fbids);
        foreach ($fbids as $key => $fbid) {
            $post_data = iff_getdataFromFID($fbid, 1);
            // make params array for submenu
            $fbids_menu_params[$fbid] = $post_data['data'][0]['from']['name'];
        }
    }
    if($fbids_menu_params != false) {
        update_option('fbids_menu_params', $fbids_menu_params);
    }
    return $fbids_menu_params;
}
