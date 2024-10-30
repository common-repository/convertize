<?php 
/**
 * Prevent Direct Access
 */
defined('ABSPATH') or die("Restricted access!");

?>

<div class="wrap">
    <h2>Connect Convertize to your website</h2>
    
    <hr/>
    
    <div class="metabox-holder">
        <form name="dofollow" action="options.php" method="post" style="max-width: 960px">
            <?php settings_errors(); ?>
            <?php settings_fields('insert-convertize-pixel'); ?>
            <?php do_settings_sections('insert-convertize-pixel'); ?>

            <p for="insert-header" class="main-text">
                Paste your <strong>unique tracking pixel</strong> below
                to connect Convertize to your website. <br>
                With one click of a button this will add Convertize
                to every page of your WordPress site. Easy!
            </p>

            <textarea
                id="insert_header"
                class="insert_header"
                name="convp_insert_header"
                style="width: 100%; font-family: monospace;padding: 0.75em; margin-bottom: 1em"
            ><?php echo esc_html(get_option('convp_insert_header')); ?></textarea>

            <div>
                <input 
                    class="button button-primary"
                    type="submit" 
                    name="Submit" value="Save pixel"
                />
            </div>
        </form>
    </div>
</div>