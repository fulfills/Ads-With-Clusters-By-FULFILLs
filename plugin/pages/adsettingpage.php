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
            
            <h1><?php _e("Ad Settings");?></h1>

            <h2>1. <?php _e("Installation Position");?></h2>

            <p>
                <?php _e("The Ads will be displayed where you place the following shortcode.");?><br>
                <?php _e("It can be placed with a shortcode widget or directly in the theme file.");?>
            </p>
            
            <p><input type="text" value="[awcbf-ad]" class="regular-text ltr" readonly></p>
            <p>
                <a href="<?php echo admin_url('widgets.php');?>" class="button button-primary">-> <?php _e("Widgets");?></a>
                <a href="<?php echo admin_url('theme-editor.php');?>" class="button button-primary">-> <?php _e("Theme Editor");?></a>
            </p>

            <h2>2. <?php _e("Ad Tags");?></h2>

            <?php if($rep):?>

                <form method="POST">
                    <?php
                        $ad_tab = new awcbf_write_wptable();
                        foreach($rep as $index => $list) {
                            $str = '';
                            $str .= '<p>■ '.__('Representative Words').'<br>*'.__('These shows what kind of cluster this is.').'</p>';
                            $str .= '<textarea rows="2" class="large-text code" readonly>';
                            foreach($list as $l) $str .= $l.' ';
                            $str .= '</textarea>';
                            $str .= '<p>■ '.__('HTML TAG').'</p>';
                            $str .= '<textarea name="adtag['.$index.']" rows="7" class="large-text code">'.esc_html(stripslashes($options['adtag'][$index])).'</textarea>';
                            $ad_tab->add_row('Cluster '.$index, $str);
                        }
                        echo $ad_tab->get_html();
                    ?>
                    <p class="submit"><input type="submit" class="button button-primary" value="<?php _e('Save Changes');?>"></p>
                </form>

            <?php else:?>

                <p>
                    記事のクラスタ分類がまだ行われていません。<br>
                    「Classify」ページより、クラスタリングを実行してください。    
                </p>

            <?php endif;?>

    <?php }