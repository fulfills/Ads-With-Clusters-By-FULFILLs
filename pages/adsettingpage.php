<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    

    // トップページ
    function awcbf_add_adsettingpage() { 

        if($options = $_POST) {
            update_option( 'awcbf_ad', $options );
        }

        $rep = get_option( 'awcbf_representation', False );
        $options = get_option( 'awcbf_ad', [] );
        
    ?>

        <div class="wrap">
            
            <h1>Ad Settings</h1>

            <h2>1. 広告設置</h2>

            <p>広告は以下のショートコードを、ショートコードウィジェット、またはテーマファイルに直接設置することで、記事に最適な広告を表示できます。</p>
            <p><input type="text" value="[awcbf-ad]" class="regular-text ltr" readonly></p>
            <p>
                <a href="<?php echo admin_url('widgets.php');?>" class="button button-primary">-> ウィジェット</a>
                <a href="<?php echo admin_url('theme-editor.php');?>" class="button button-primary">-> テーマエディター</a>
            </p>

            <h2>2. 広告タグ</h2>

            <?php if($rep):?>

                <form method="POST">
                    <?php
                        $ad_tab = new awcbf_write_wptable();
                        foreach($rep as $index => $list) {
                            $str = '';
                            $str .= '<p>■ 代表語 （どのような記事クラスタかどうかを表しています。）</p>';
                            $str .= '<textarea rows="2" class="large-text code" readonly>';
                            foreach($list as $l) $str .= $l.' ';
                            $str .= '</textarea>';
                            $str .= '<p>■ HTML TAG</p>';
                            $str .= '<textarea name="adtag['.$index.']" rows="7" class="large-text code">'.esc_html(stripslashes($options['adtag'][$index])).'</textarea>';
                            $ad_tab->add_row('Cluster '.$index, $str);
                        }
                        echo $ad_tab->get_html();
                    ?>
                    <p class="submit"><input type="submit" class="button button-primary" value="変更を保存"></p>
                </form>

            <?php else:?>

                <p>
                    記事のクラスタ分類がまだ行われていません。<br>
                    「Classify」ページより、クラスタリングを実行してください。    
                </p>

            <?php endif;?>

    <?php }