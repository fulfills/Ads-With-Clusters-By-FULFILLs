<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    // トップページ
    function awcbf_add_toppage() {
        echo '
            <div class="wrap">
                <h1>Ads With Clusters </h1>
                <h2>■'.__('About this plug-in').'</h2>
                <p>
                    '.__('This plugin is developed by FULFILLs, an organization of volunteers.').'<br>
                    <a href="https://fulfills.jp">https://fulfills.jp</a>
                </p>
                <p>
                    '.__('We take all possible precautions against defects, but we are not responsible for any damages caused by this plug-in.').'
                </p>
                <h2>■'.__('Bug Reports and Improvements').'</h2>
                <p>
                    '.__('Opinions from actual users contribute greatly to the development of this plug-in.').'<br>
                    '.__('If you have any suggestions or comments, please report them below.').'<br>
                </p>
                <p><a class="button button-primary" href="https://fulfills.jp/contact" target="_blank">FULFILLs SUPPORT</a></p>
                <p>'.__('We appreciate your help.').'</p>
            </div>
        ';
    }