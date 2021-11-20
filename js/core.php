<?php 
    function awcbf_add_admin_scripts() {
        wp_enqueue_script('jquery');
    }
    add_action('admin_enqueue_scripts', 'awcbf_add_admin_scripts');