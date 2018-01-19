<?php
/**
 * Memory Usage Bar 
 *
 * Memory Usage Bar is a powerful plugin for WordPress that allows you to display current memory usage on top of the admin header.  It so useful to monitor the total memory useage for a single visit.
 *
 * @package   Memory Usage Bar
 * @author    Mr.ING <ayangyuan@gmail.com>
 * @license   GPL-2.0+
 * @link      https://squaredaway.studio/wordpress-plugin-memory-usage-bar/
 * @copyright 1999-2018 
 *
 * @wordpress-plugin
 * Plugin Name: Memory Usage Bar
 * Plugin URI:  https://wordpress.org/plugins/memory-usage-bar/
 * GitHub URI:  https://github.com/ayangyuan/Wordpress-Plugin-Memory-Usage-Bar
 * Author URI:  https://squaredaway.studio/wordpress-plugin-memory-usage-bar/
 * Author:      Mr.ING 
 * Version:     1.0.2
 * Text Domain: memory-usage-bar
 * Domain Path: /res/lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Description: Display the current memory usage bar on admin header.
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

/** Add links to the plugin action row. */
function mr_ing_mub_plugin_row_meta( $links, $file ) {
  if ( plugin_basename( __FILE__ ) === $file ) {
    $new_links = array(
    'support'    => '<a href = "http://wordpress.org/support/plugin/memory-usage-bar">' . __( 'Support' ) . '</a>',
    'donate'     => '<a href = "https://squaredaway.studio/donate/">' . __( 'Donate') . '</a>',
    'contribute' => '<a href = "https://github.com/ayangyuan/Wordpress-Plugin-Memory-Usage-Bar">' . __( 'Contribute' ) . '</a>',
     );
     $links = array_merge( $links, $new_links );
   }
   return $links;
}
add_filter( 'plugin_row_meta', 'mr_ing_mub_plugin_row_meta', 10, 2 );

}
