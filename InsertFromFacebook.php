<?php

/*
Plugin Name: InsertFromFacebook
Plugin URI: 
Description: 
Version: 1.0.0
Author: SebaZ
Author URI: 
License: GPLv2
*/

/* 
Copyright (C) 2016 SebaZ
*/

add_action('admin_menu', 'iff_setup_menu');
 
function iff_setup_menu(){
        add_menu_page( 'Insert From Facebook', 'Insert From Facebook', 'manage_options', 'iff-plugin', 'iff_init' );
}
 
function iff_init(){
        echo "<h1>Insert From Facebook!</h1>";
}
