<?php
/*
Plugin Name: Ads With Clusters By FULFILLs
Plugin URI: https://fulfills.jp/
Description: Automatically generate groups of posts based on the titles of the posts, and place suitable ads for each group.
Author: FULFILLs
Author URI: https://fulfills.jp/
Domain Path: /languages/
Version: 1.0.0
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

// Include JS
include 'js/core.php';

// Include Common Parts
include 'php/common.php';

// Include AJAX
include 'php/get-embeddings.php';
include 'php/classify.php';

// Add Pages
include 'pages/aboutus.php';
include 'pages/classifypage.php';
include 'pages/adsettingpage.php';

// Add Shortcode(s)
include 'php/shortcodes.php';

function awcbf_add_pages() {
    global $AWCBF_MAIN_SLUG;
    add_menu_page( 'page_title', __('Ads With Clusters'), 'publish_posts', $AWCBF_MAIN_SLUG, 'awcbf_add_toppage', 'dashicons-analytics', 99);
    add_submenu_page( $AWCBF_MAIN_SLUG, __('About Us'), __('About Us'), 'publish_posts', $AWCBF_MAIN_SLUG, 'awcbf_add_toppage' );
    add_submenu_page( $AWCBF_MAIN_SLUG, __('Classify'), __('Classify'), 'publish_posts', "$AWCBF_MAIN_SLUG-classify", 'awcbf_add_classifypage' );
    add_submenu_page( $AWCBF_MAIN_SLUG, __('Ad Setting'), __('Ad Setting'), 'publish_posts', "$AWCBF_MAIN_SLUG-adsetting", 'awcbf_add_adsettingpage' );
}
add_action('admin_menu', 'awcbf_add_pages');