<?php
    function awcbf_embeddings_ajax() {

        check_ajax_referer('awcbf_embeddings_ajax','secure');
		header('Content-type: text/plain; charset= UTF-8');

        // switch()
        if(awcbf_sanitize_for_array($_REQUEST['target']) === 'all') {

            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'post',
                'post_status'      => 'all',
                'suppress_filters' => true 
            );

        }
        elseif(awcbf_sanitize_for_array($_REQUEST['target']) === 'only_publish') {

            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'suppress_filters' => true 
            );

        }

        $posts_array = get_posts( $args );
        $TITLEs = array_column($posts_array, 'post_title', 'ID');

        $url = 'https://corpus.fulfills.jp';
        $data = [
            'data' => $TITLEs
        ];
        $context = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
                'content' => http_build_query($data)
            )
        );
        $html = file_get_contents($url, false, stream_context_create($context));
        $output = json_decode($html, 1);

        // ERROR CHECK
        if(!$output['flag']) {
            echo esc_html($output['message']);
            wp_die();
        }

        // SAVE
        update_option( 'awcbf_embeddings', $output['vectors'] );

        wp_die();

    }
    add_action( 'wp_ajax_awcbf_embeddings_ajax', 'awcbf_embeddings_ajax' );
    add_action( 'wp_ajax_nopriv_awcbf_embeddings_ajax','awcbf_embeddings_ajax' );
