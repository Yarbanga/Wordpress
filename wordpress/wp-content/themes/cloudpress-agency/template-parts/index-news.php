<?php 
$cloudpress_agency_home_news_title=get_theme_mod('home_news_section_title',__('Vitae Lacinia','cloudpress-agency'));
$cloudpress_agency_home_news_subtitle=get_theme_mod('home_news_section_discription',__('Cras Vitae Placerat','cloudpress-agency'));
$cloudpress_agency_home_blog_enable=get_theme_mod('latest_news_section_enable','on');
if($cloudpress_agency_home_blog_enable=='on'):?>
<section class="section-module blog" id="blog">
    <div class="container">
        <?php if(!empty($cloudpress_agency_home_news_title) || !empty($cloudpress_agency_home_news_subtitle)):?>
          <div class="row">
             <div class="col-md-12">
                <div class="section-header">
                   <?php if(!empty($cloudpress_agency_home_news_title)):?>
                   <h5 class="section-subtitle"><?php echo esc_html($cloudpress_agency_home_news_title);?></h5>
                   <?php endif; if(!empty($cloudpress_agency_home_news_subtitle)):?>
                   <h2 class="section-title"><?php echo esc_html($cloudpress_agency_home_news_subtitle);?></h2>
                   <?php endif;?>    
                </div>
             </div>
          </div>
        <?php endif;?>
        <div class="row  blog list-view">
            <?php
            $cloudpress_agency_args = array( 'post_type' => 'post','post__not_in'=>get_option("sticky_posts"),'posts_per_page' => 3);
            query_posts( $cloudpress_agency_args );
            if(query_posts( $cloudpress_agency_args ))
            {
                while(have_posts()):the_post();
                { ?>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <article class="post media">  
                            <?php if(has_post_thumbnail()){ ?>                          
                            <figure class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a>
                            </figure>   
                            <?php } ?>
                            <div class="media-body post-content"> 
                             <?php if(get_theme_mod('blog_meta_section_enable',true)==true):?>  
                                <div class="entry-meta">
                                    <span class="entry-date"><a href="<?php echo esc_url( home_url('/') ); ?>/<?php echo esc_html(date( 'Y/m' , strtotime( get_the_date() )) ); ?>"><time><?php echo esc_html(get_the_date());?></time></a></span>
                                    <?php $cloudpress_agency_cat_list = get_the_category_list();
                                    if(!empty($cloudpress_agency_cat_list)) { ?>
                                    <span class="cat-links"><?php the_category(' '); ?></span>
                                    <?php }
                                    if(has_tag()):?>
                                    <span class="tag-links"><?php the_tags('',' ');?></span>
                                    <?php endif;?>
                                </div>   
                                <?php endif;?>                                               
                                <header class="entry-header">
                                    <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4>
                                </header>   
                                
                                <div class="entry-content">
                                    <?php the_excerpt();?>
                                    <p><a href="<?php the_permalink(); ?>" class="more-link btn-ex-small btn-animate dark"><?php echo esc_html__('READ MORE','cloudpress-agency');?></a>
                                    </p>
                                </div>

                                <?php if(get_theme_mod('blog_meta_section_enable',true)==true):?>   
                                <hr>                                
                                <div class="item-meta">
                                    <div class="pull-left v-center">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="avatar"><?php echo get_avatar(get_the_author_meta('ID'));?></a>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php esc_html_e('By','cloudpress-agency');?> <?php echo esc_html(get_the_author());?></a>
                                    </div>      
                                </div>
                                <?php endif;?>  
                            </div>              
                        </article>
                    </div>  
                <?php
                }endwhile;
            } ?>
        </div>
    </div>
</section>
<?php endif;?>