<?php
/*
Plugin Name: Ads With Clusters By FULFILLs
Plugin URI: https://fulfills.jp/
Description: This is Test.
Author: FULFILLs
Author URI: https://fulfills.jp/
Domain Path: /languages/
Version: 1.1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$AWCBF_MAIN_SLUG = 'ads-with-clusters-by-fulfills';

function awcbf_sanitize_for_array($obj, $is_textarea = 0) {
    if(is_array($obj)) {
        foreach($obj as $key => $val) $obj[$key] = awcbf_sanitize_for_array($val, $is_textarea);
        return $obj;
    }
    if($is_textarea) return sanitize_textarea_field($obj);
    return sanitize_text_field($obj);
}

load_plugin_textdomain('ads-with-clusters-by-fulfills');

function awcbf_add_pages() {
    global $AWCBF_MAIN_SLUG;
    add_menu_page( 'page_title', __('Ads with Clusters'), 'publish_posts', $AWCBF_MAIN_SLUG, 'awcbf_add_toppage', 'dashicons-email-alt', 8);
    add_submenu_page( $AWCBF_MAIN_SLUG, __('About Us'), __('About Us'), 'publish_posts', $AWCBF_MAIN_SLUG, 'awcbf_add_toppage' );
}
add_action('admin_menu', 'awcbf_add_pages');