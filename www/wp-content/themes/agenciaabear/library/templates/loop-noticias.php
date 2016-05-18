<?php
/* 
Template Name: Calendário
*/
?>

<div id="page-<?php the_ID(); ?>" <?php post_class( 'elements-box' ); ?>>
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
                  echo "<p class='txt-corrido'>Não há posts.</p>";
                endif;?>
            

        
<!-- pagination -->
<?php get_template_part('library/templates/template', 'pagination'); ?>