<?php
    function awcbf_add_shortcode() {
        if(is_single()) {
            $post_id = get_the_ID();
            $cluster_index = get_post_meta( $post_id, 'awcbf_class_num', True );
            if( $cluster_index !== '' ) {   // クラスタ割当判定
                $options = get_option( 'awcbf_ad', [] );
                if( $ad = $options['adtag'][$cluster_index] ) {
                    return '<div class="awcbf-ad">'.stripslashes($ad).'</div>';
                }
            }
        }
        return '';
    }
    add_shortcode( 'awcbf-ad', 'awcbf_add_shortcode' );
    