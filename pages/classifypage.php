<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    function awcbf_add_classifypage() { ?>

        <div class="wrap">
            
            <h1><?php _e("Classify");?></h1>

            <form class="settings">

                <h2>Step1. <?php _e('Execution Settings');?></h2>

                <?php
                    $option_tab = new awcbf_write_wptable();
                    $option_tab->add_row(
                        __('Number of clusters (groups)'),
                        '<input name="n_clusters" type="number" step="1" min="1" value="5" class="small-text">',
                        __('Enter the approximate number of article groups.')
                    );
                    $option_tab->add_row(
                        __('Articles to be analyzed'),
                        '
                            <p><label><input name="target" type="radio" value="all" checked=>'.__('All Articles').'</label></p>
                            <p><label><input name="target" type="radio" value="only_publish">'.__('Only "Publish"').'</label></p>
                        '
                    );
                    echo $option_tab->get_html();
                ?>

            </form>

            <form class="embedding">

                <h2>Step2. <?php _e("Get Title Vectors (Automatically)");?></h2>
                
                <p>
                    <?php _e("Convert article titles into a numeric vector for classification.");?><br>
                    <?php _e("This is all done automatically, so you don't need to worry about it.");?><br>
                </p>

                <p><input type="button" name="submit" id="submit" class="button button-primary" value="<?php _e('Run');?>"></p>

                <p><textarea name="status" class="large-text code" rows="2" readonly>Status: <?php _e("Waiting");?></textarea></p>

            </form>

            <form class="classify">

                <h2>Step3. <?php _e("Classify (Automatically)");?></h2>

                <p>
                    <?php _e('Classify the articles based on the generated vectors.');?><br>
                    <?php _e('When you are ready, click the "Run" button.');?>
                </p>


                <?php if(get_option( 'awcbf_ad', False )):?>
                <p class="chui"><?php _e("Since the cluster will be renewed, the ad settings will be reset at the same time.");?></p>
                <?php endif;?>

                <p><input type="button" name="submit" id="submit" class="button button-primary" value="<?php _e('Run');?>"></p>

                <p><textarea name="status" class="large-text code" rows="2" readonly>Status: <?php _e("Waiting");?></textarea></p>

            </form>

        </div>

        <script>
            (function($) {
                
                $('form.embedding input[type="button"]').click(function(){

                    $status_obj = $('form.embedding textarea[name="status"]');

                    $status_obj.val('<?php _e("Running...");?>\n<?php _e("This will take some time. Please wait a moment.");?>');

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
                        if(data === '') $status_obj.val('<?php _e("Done! Please proceed to Step 3.");?>');
                        else $status_obj.val(data);
                    })
                    .fail( function(){
                        $status_obj.val('<?php _e("Error.");?>');
                    } );

                });

                $('form.classify input[type="button"]').click(function(){

                    $status_obj = $('form.classify textarea[name="status"]');

                    $status_obj.val('<?php _e("Running...");?>\n<?php _e("This will take some time. Please wait a moment.");?>');

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
                        if(data === '') $status_obj.val('<?php _e("Done! Please proceed to the ad settings.");?>');
                        else $status_obj.val(data);
                    })
                    .fail( function(){
                        $status_obj.val('<?php _e("Error.");?>');
                    } );

                });

            })(jQuery);
	    </script>

    <?php }