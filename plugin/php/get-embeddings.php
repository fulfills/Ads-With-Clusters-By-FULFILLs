<?php
    function awcbf_embeddings_ajax() {

        check_ajax_referer('awcbf_embeddings_ajax','secure');
		header('Content-type: text/plain; charset= UTF-8');

        // GET WP-POST(s)
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'post',
            'post_status'      => ((awcbf_sanitize_for_array($_REQUEST['target']) === 'only_publish') ? 'publish' : 'all'),
            'suppress_filters' => true 
        );
        $posts = get_posts( $args );

        // GENERATE TITLE-DICT {ID: POST_TITLE}
        $TITLEs = json_encode(array_column($posts, 'post_title', 'ID'));

        // WebAPI URL
        $url = 'https://corpus.fulfills.jp';

        // CHANGE TITLE-DICT to CONTEXT on POST-REQUEST
        $options = array(
				'http' => array(
					'method'=> 'POST',
					'header'=> 'Content-type: application/json; charset=UTF-8',
					'content' => $TITLEs
				)
			);
		$context = stream_context_create($options);
		
        // POST-REQUEST & DECODE RESPONSE TO JSON
        $OUTPUT = file_get_contents($url, FALSE, $context);
        $OUTPUT = json_decode($OUTPUT, 1);

        // ERROR CHECK
        if(!$OUTPUT['flag']) {
            echo esc_html($OUTPUT['message']);
            wp_die();
        }
		
        // SAVE
        update_option( 'awcbf_embeddings', $OUTPUT['vectors'] );

        wp_die();

    }
    add_action( 'wp_ajax_awcbf_embeddings_ajax', 'awcbf_embeddings_ajax' );
    add_action( 'wp_ajax_nopriv_awcbf_embeddings_ajax','awcbf_embeddings_ajax' );