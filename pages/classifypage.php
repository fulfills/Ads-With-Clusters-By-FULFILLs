<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    // トップページ
    function awcbf_add_classifypage() { ?>

        <div class="wrap">
            
            <h1>Classify</h1>

            <form class="settings">

                <h2>Step1. Execution Settings</h2>

                <?php
                    $option_tab = new awcbf_write_wptable();
                    $option_tab->add_row(
                        'クラスタ数',
                        '<input name="n_clusters" type="number" step="1" min="1" value="5" class="small-text">',
                        'おおよその数を入力してください。'
                    );
                    $option_tab->add_row(
                        '分析対象記事', 
                        '
                            <p><label><input name="target" type="radio" value="all" checked=>すべての記事</label></p>
                            <p><label><input name="target" type="radio" value="only_publish">公開済み記事のみ</label></p>
                        '
                    );
                    echo $option_tab->get_html();
                ?>

            </form>

            <form class="embedding">

                <h2>Step2. Get Title Vectors (Automatically)</h2>
                
                <p>
                    記事タイトルを自動分類のために、ベクトルに変換します。すべて自動的に行われますので、心配はいりません。<br>
                    準備ができたら、「実行する」ボタンを押してください。
                </p>

                <p><input type="button" name="submit" id="submit" class="button button-primary" value="実行する"></p>

                <p><textarea name="status" class="large-text code" rows="2" readonly>Status: Waiting</textarea></p>

            </form>

            <form class="classify">

                <h2>Step3. Classify (Automatically)</h2>

                <p>
                    生成したベクトルをもとに、記事を分類します。<br>
                    準備ができたら、「実行する」ボタンを押してください。
                </p>


                <?php if(get_option( 'awcbf_ad', False )):?>
                <p class="chui">2回目以降の場合、クラスタが新しくなるため、広告設定がリセットされます。</p>
                <?php endif;?>

                <p><input type="button" name="submit" id="submit" class="button button-primary" value="実行する"></p>

                <p><textarea name="status" class="large-text code" rows="2" readonly>Status: Waiting</textarea></p>

            </form>

        </div>

        <script>
            (function($) {
                
                $('form.embedding input[type="button"]').click(function(){

                    $status_obj = $('form.embedding textarea[name="status"]');

                    $status_obj.val('実行中（時間がかかります。しばらくお待ち下さい。）');

                    $.ajax({
                    dataType: "text",
                    url: '<?php echo admin_url('admin-ajax.php', __FILE__); ?>',
                    data: {
                        'action': 'awcbf_embeddings_ajax',
                        'secure': '<?php echo wp_create_nonce('awcbf_embeddings_ajax') ?>',
                        'n_clusters': $('form.settings input[name="n_clusters"]').val(),
                        'target': $('form.settings input[name="target"]:checked').val(),
                    }
                    })
                    .done( (data) => {
                        console.log(data);
                        if(data === '') $status_obj.val('完了! Step3に進んでください。');
                        else $status_obj.val(data);
                    })
                    .fail( function(){
                        $status_obj.val('失敗しました。');
                    } );

                });

                $('form.classify input[type="button"]').click(function(){

                    $status_obj = $('form.classify textarea[name="status"]');

                    $status_obj.val('実行中（時間がかかります。しばらくお待ち下さい。）');

                    $.ajax({
                    dataType: "text",
                    url: '<?php echo admin_url('admin-ajax.php', __FILE__); ?>',
                    data: {
                        'action': 'awcbf_classify_ajax',
                        'secure': '<?php echo wp_create_nonce('awcbf_classify_ajax') ?>',
                        'n_clusters': $('form.settings input[name="n_clusters"]').val(),
                        'target': $('form.settings input[name="target"]:checked').val(),
                    }
                    })
                    .done( (data) => {
                        console.log(data);
                        if(data === '') $status_obj.val('完了! Step3に進んでください。');
                        else $status_obj.val(data);
                    })
                    .fail( function(){
                        $status_obj.val('失敗しました。');
                    } );

                });

            })(jQuery);
	    </script>

    <?php }