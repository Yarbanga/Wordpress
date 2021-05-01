<?php

/**
 * Class to replace the main WooCommerce product image with a slider
 * that contains all of the product images
 */

if ( !defined( 'ABSPATH' ) )
	exit;

class ewdusWooCommerceIntegration {


	public function __construct() {
		
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'remove_wc_thumbnails' ) );
		add_filter( 'woocommerce_single_product_image_html', array( $this, 'replace_woocommerce_image' ), 10, 2 );
	}

	/**
	 * Replace main WooCommerce image with a slider containing all product images
	 * @since 2.0.0
	 */
	public function replace_woocommerce_image( $html, $product_id ) {

		$product = wc_get_product( $product_id );
	
		$post_ids = get_post_thumbnail_id( $product_id );
		$attachment_ids = $product->get_gallery_attachment_ids();
	
		if ( $attachment_ids ) {

			foreach ( $attachment_ids as $attachment_id ) {
				$post_ids .= "," . $attachment_id;
			}

			return do_shortcode( '[ultimate-slider post__in_string="' . $post_ids . '"]' );
		}
			
		return $html;
	}

	/**
	 * Remove the product image thumbnails if displaying the slider
	 * @since 2.0.0
	 */
	public function remove_wc_thumbnails() {

		return null;
	}
}