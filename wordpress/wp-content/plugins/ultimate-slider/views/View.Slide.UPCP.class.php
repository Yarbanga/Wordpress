<?php

/**
 * Class to display a slide in the slider on the front end.
 *
 * @since 2.0.0
 */
class ewdusViewSlideUPCP extends ewdusViewSlide {

	/**
	 * Get the content (image, title, etc.) of the slide
	 *
	 * @since 2.0.0
	 */
	public function get_slide_content() {

		if ( empty( $this->ID ) ) { return; }

		if ( ! class_exists( 'UPCP_Product' ) ) { return; }

		$upcp_product_id = get_post_meta( $this->ID, "EWD_US_UPCP_Product_ID", true );

		// If this is a slide that has been assigned a product, load the product, otherwise load as normal
		$product = $upcp_product_id ? new UPCP_Product( array( 'ID' => $upcp_product_id ) ) : new UPCP_Product( array( 'ID' => $this->ID ) );

		$this->title 			= $product->Get_Product_Name();
		$this->filtered_content = $product->Get_Field_Value( 'Item_Description' );
		$this->image_url 		= $product->Get_Field_Value( 'Item_Photo_URL' );
	}

	/**
	 * Get the initial slide css classes
	 * @since 2.0.0
	 */
	public function slide_classes( $classes = array() ) {
		
		$parent_classes = parent::slide_classes();

		$classes = array_merge(
			$classes,
			$parent_classes,
			array(
				'upcp_slide'
			)
		);

		return apply_filters( 'us_slide_classes', $classes, $this );
	}

}
