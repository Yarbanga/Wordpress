<?php
$background_image = get_theme_support( 'custom-header', 'default-image' );

if ( has_header_image() ) {
  $background_image = get_header_image();
}
?>
<div class="consultup-breadcrumb-section" style='background: url("<?php echo esc_url( $background_image ); ?>" ) repeat scroll center 0 #143745;'>
  <div class="overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12">
			<div class="consultup-breadcrumb-title">
            <?php if( class_exists( 'WooCommerce' ) && is_shop() ) { ?>
            <h1>
            <?php woocommerce_page_title(); ?>
            </h1>
            <?php    
          	} else { ?>
            <h1><?php the_title(); ?></h1>
           <?php } ?>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>