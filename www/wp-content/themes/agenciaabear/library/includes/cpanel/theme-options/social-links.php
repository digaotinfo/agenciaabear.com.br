<div id="tab-social-links" class="kopa-content-box tab-content tab-content-1">    

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Social Links Target', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Social Links Target', kopa_get_domain()); ?></span>            
            <?php
            $kopa_social_link_targets = array(
                '_self'  => __('Opens the linked document in the same frame as it was clicked', kopa_get_domain()),
                '_blank' => __('Opens the linked document in a new window or tab', kopa_get_domain()),
            );
            $kopa_social_link_target_name = "kopa_theme_options_social_link_target";
            foreach ($kopa_social_link_targets as $value => $label) {
                $kopa_target_id = $kopa_social_link_target_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_target_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_target_id; ?>" name="<?php echo $kopa_social_link_target_name; ?>" <?php echo ($value == get_option($kopa_social_link_target_name, '_self')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
    </div>

    <?php 
    /**
     * Social Links
     */
    ?> 
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Social Links', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <!-- RSS -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('RSS URL', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Display the RSS feed button with the default RSS feed or enter a custom feed below. <br><code>Enter <b>"HIDE"</b> if you want to hide it</code>', kopa_get_domain()); ?></p>    
            <input type="text" value="<?php echo get_option('kopa_theme_options_social_links_rss_url'); ?>" id="kopa_theme_options_social_links_rss_url" name="kopa_theme_options_social_links_rss_url">                                                     
        </div><!--kopa-element-box-->

        <!-- FACEBOOK -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Facebook_URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_facebook_url'); ?>" id="kopa_theme_options_social_links_facebook_url" name="kopa_theme_options_social_links_facebook_url">
        </div>

        <!-- TWITTER -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Twitter URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_twitter_url'); ?>" id="kopa_theme_options_social_links_twitter_url" name="kopa_theme_options_social_links_twitter_url">
        </div>

        <!-- Pinterest -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Pinterest URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_pinterest_url'); ?>" id="kopa_theme_options_social_links_pinterest_url" name="kopa_theme_options_social_links_pinterest_url">
        </div>

        <!-- Instagram -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Instagram URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_instagram_url'); ?>" id="kopa_theme_options_social_links_instagram_url" name="kopa_theme_options_social_links_instagram_url">
        </div>

        <!-- Google plus -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Google Plus URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_gplus_url'); ?>" id="kopa_theme_options_social_links_gplus_url" name="kopa_theme_options_social_links_gplus_url">
        </div>

        <!-- Youtube -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Youtube URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_youtube_url'); ?>" id="kopa_theme_options_social_links_youtube_url" name="kopa_theme_options_social_links_youtube_url">
        </div>

        <!-- Linkedin -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Linkedin URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_linkedin_url'); ?>" id="kopa_theme_options_social_links_linkedin_url" name="kopa_theme_options_social_links_linkedin_url">
        </div>
    </div>
</div>
