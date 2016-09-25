<?php

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
}
 
function iff_settings_page(){
    add_submenu_page('iff-plugin', 'Uok', 'awienia', 'manage_options', 'iff-pdfgin', 'iff_settings_page');
    echo "<h1>Insert From Facebook!</h1>";
}

