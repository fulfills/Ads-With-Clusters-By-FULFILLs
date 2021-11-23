<?php

    function awcbf_kmeans($vectors, $k) {

        // TOOLS

        function distance($a, $b) {
            if(count($a) != count($b)) throw new Exception('次元数が一致しません。');
            $dis = 0;
            foreach($a as $i => $val) {
                $dis += ($a[$i] - $b[$i])**2;
            }   
            return $dis;
        }

        function mean_vectors($vectors) {
            $dim = count($vectors[0]);
            $length = count($vectors);
            $output = array_fill(0, $dim, 0);
            for($d = 0; $d < $dim; ++$d) {
                for($i = 0; $i < $length; ++$i) {
                    $output[$d] += $vectors[$i][$d];
                }
                $output[$d] /= $length;
            }
            return $output;
        }

        // INIT

        $cluster_index = array_fill(0, $k, 0);

        // RANDOM CENTER

        $centers = array_rand($vectors, $k);
        foreach($centers as $key => $val) $centers[$key] = $vectors[$val];

        while(True) {

            // RE-INDEX
                
            $prev_cluster_index = $cluster_index;
            foreach($vectors as $i => $vector) {
                $dis = [];
                foreach($centers as $center) {
                    array_push($dis, distance($vector, $center));
                }
                $cluster_index[$i] = array_keys($dis, min($dis))[0];
            }

            // UPDATE CLUSTER CENTER
            $vectors_withClusters = array_fill(0, $k, []);
            foreach($cluster_index as $i => $index) {
                array_push($vectors_withClusters[$index], $vectors[$i]);
            }

            foreach($vectors_withClusters as $index => $vs) {
                $centers[$index] = mean_vectors($vs);
            }

            if($cluster_index === $prev_cluster_index) break;

        }

        return $cluster_index;

    }

    function awcbf_classify_ajax() {

        check_ajax_referer('awcbf_classify_ajax','secure');
		header('Content-type: text/plain; charset= UTF-8');

        // 広告設定削除（2回目以降のクラスタリングのため）
        delete_option( 'awcbf_ad' );

        // 復元
        $data = get_option( 'awcbf_embeddings', False );
        if(!$data) echo 'Error! Step2を先に実行してください。';

        // K-Means
        $vectors = array_column($data, 1);
        $k = awcbf_sanitize_for_array($_REQUEST['n_clusters']);

        // Check
        if($k <= 1) {
            echo 'Error! クラスタ数は2以上を指定してください。';
            wp_die();
        }
        elseif($k > count($vectors)) {
            echo 'Error! クラスタ数は記事数を超えないでください。';
            wp_die();
        }
        
        $indices = awcbf_kmeans($vectors, $k);

        // 保存
        $id_index = [];
        foreach(array_keys($data) as $i => $id) {
            $id_index[$id] = $indices[$i];
            update_post_meta($id, 'awcbf_class_num', $indices[$i]);
        }

        // 代表後抽出
        $representation_list = [];
        for($index = 0; $index < $k; ++$index) {
            $labels = array_keys($id_index, $index);
            $words = [];
            foreach($labels as $l) {
                $words = array_merge($words, explode(' ', $data[$l][0]));
            }
            $words_count = array_count_values($words);
            arsort($words_count);
            $representation_list[$index] = array_slice(array_keys($words_count), 0, 20);
        }

        update_option( 'awcbf_representation', $representation_list );

        wp_die();
        
    }
    add_action( 'wp_ajax_awcbf_classify_ajax', 'awcbf_classify_ajax' );
    add_action( 'wp_ajax_nopriv_awcbf_classify_ajax','awcbf_classify_ajax' );
