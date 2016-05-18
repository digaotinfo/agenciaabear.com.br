<?php 
if ( is_home() ) {
    get_template_part( 'library/templates/template', 'blog-slider' );
}
?>

<ul class="entry-list isotop-item clearfix">
    
    <h3 class="widget-title menor"></h3>

            <li class="element">
            <?php
            wp_reset_query();
            query_posts("posts_per_page=1&cat=26");
            if(have_posts()) : while(have_posts() ) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content foto">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            
                        </header>
                        <?php the_field('linha-fina'); ?>
                        
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
                <?php endwhile;
                else:
                  echo "";
                endif;?>
            </li>
            
            <li class="element">
            <?php
            wp_reset_query();
            query_posts("posts_per_page=1&cat=27");
            if(have_posts()) : while(have_posts() ) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content">
                        <header>
	                        <?php 
	                        	$target = "";
	                        	$url = get_field('link_compartilhamento');
	                        	if( empty( $url ) ){
	                        		$url = get_permalink();
	                        	}
	                        	if( get_field('nova_aba') == true ){
	                        		$target = "target='_blank'";
	                        	}
	                        ?>
                            <h4 class="entry-title"><a href="<?php echo $url; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h4>
                            
                        </header>
                        <?php the_field('linha-fina'); ?>
                        
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
                <?php endwhile;
                else:
                  echo "";
                endif;?>
            </li>
            
            <li class="element">
            <?php
            wp_reset_query();
            query_posts("posts_per_page=1&cat=28");
            if(have_posts()) : while(have_posts() ) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content">
                        <header>
                        	<?php 
                        		$target = "";
	                        	$url = get_field('link_compartilhamento');
	                        	if( empty( $url ) ){
	                        		$url = get_permalink();
	                        	}
	                        	if( get_field('nova_aba') == true ){
	                        		$target = "target='_blank'";
	                        	}
	                        ?>
                            <h4 class="entry-title"><a href="<?php echo $url; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h4>
                            
                        </header>
                        <?php the_field('linha-fina'); ?>
                        
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
                <?php endwhile;
                else:
                  echo "";
                endif;?>
            </li>
            
            <li class="element">
            <?php
            wp_reset_query();
            query_posts("posts_per_page=1&cat=29");
            if(have_posts()) : while(have_posts() ) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                        
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content">
                        <header>
                        	<?php 
	                        	$target = "";
	                        	$url = get_field('link_compartilhamento');
	                        	if( empty( $url ) ){
	                        		$url = get_permalink();
	                        	}
	                        	if( get_field('nova_aba') == true ){
		                        	$target = "target='_blank'";
	                        	}
	                        ?>
                            <h4 class="entry-title"><a href="<?php echo $url; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h4>
                            
                        </header>
                        <?php the_field('linha-fina'); ?>
                        
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
                <?php endwhile;
                else:
                  echo "";
                endif;?>
            </li>

        
</ul>



