<?php
/*
Plugin Name: Memory Usage Bar 
Plugin URI: https://wordpress.org/plugins/memory-usage-bar/
Github: https://github.com/ayangyuan/Wordpress-Plugin-Memory-Usage-Bar.git 
Description: Show the current memory usage in the admin header.
Version: 1.0.0 
Author: Yuan Yang
Author URI: https://84361749.com
Text Domain: memory-usage-bar

*/

if ( is_admin() ) {	
    add_filter( 'admin_bar_menu', 'add_header' ,990);
    function add_header($admin_bar) {
        $admin_bar->add_menu(array(
             'id'=>'memory_usage_bar',
             'title'=>memory_usage_bar(),
        ));
        $admin_bar->add_menu(array(
             'parent'=>'memory_usage_bar',
             'id'=>'memory_usage_server_limit',
             'title'=>"Server Limit: ".(int) ini_get('memory_limit')."MB",
        ));
        $admin_bar->add_menu(array(
             'parent'=>'memory_usage_bar',
             'id'=>'memory_usage_wp_limit',
             'title'=>memory_usage_wp_limit(),
        ));
        $admin_bar->add_menu(array(
             'parent'=>'memory_usage_bar',
             'id'=>'memory_usage_ip',
             'title'=>memory_usage_ip(),
        ));
        $admin_bar->add_menu(array(
             'parent'=>'memory_usage_bar',
             'id'=>'memory_usage_hostname',
             'title'=>'Hostname: '.gethostname(),
        ));
    }

    function memory_usage_bar() {
        $usage = function_exists('memory_get_peak_usage') ? round(memory_get_peak_usage(TRUE) / 1024 / 1024, 2) : 0;                                            
        $limit = (int) ini_get('memory_limit') ;
        if ( !empty($usage) && !empty($limit) ) 
             $percent = round ($usage / $limit * 100, 0);
        $content = 'Memory:' . $usage .'MB(' . $percent . '%)';
        return $content;

    }
    function memory_usage_wp_limit(){
        $unit  = substr( WP_MEMORY_LIMIT, -1 );
        $limit = substr( WP_MEMORY_LIMIT, 0, -1 );
        $limit = (int)$limit;  
        switch ( strtoupper( $unit ) ) { case 'P' : $limit*= 1024; case 'T' : $limit*= 1024; case 'G' : $limit*= 1024; case 'M' : $limit*= 1024; case 'K' : $limit*= 1024; }
        $memory = size_format( $limit );
        $content = "WP Limit: $memory";
        return $content;
    }
    function memory_usage_ip(){
         $server_ip_address = (!empty($_SERVER[ 'SERVER_ADDR' ]) ? $_SERVER[ 'SERVER_ADDR' ] : "");
         if ($server_ip_address == "") 
              $server_ip_address = (!empty($_SERVER[ 'LOCAL_ADDR' ]) ? $_SERVER[ 'LOCAL_ADDR' ] : "");
         $content = ' IP:  ' . $server_ip_address . '';
         return $content;
    }
}
