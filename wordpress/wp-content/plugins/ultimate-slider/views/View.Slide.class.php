<?php

/**
 * Class to display a slide in the slider on the front end.
 *
 * @since 2.0.0
 */
class ewdusViewSlide extends ewdusView {

	// Set default content for a blank slide
	public $title 				= '';
	public $filtered_content 	= '';
	public $image_url 			= EWD_US_PLUGIN_URL . '/assets/img/Black_Background.png';
	public $buttons 			= array();

	/**
	 * Get the content (image, title, etc.) of the slide
	 *
	 * @since 2.0.0
	 */
	public function get_slide_content() {

		if ( empty( $this->ID ) ) { return; }

		$this->title 				= $this->post_title;
		$this->filtered_content 	= str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $this->post_content ) );
		$this->post_thumbnail_id 	= get_post_thumbnail_id( $this->ID );

		if ( $this->image_type == 'youtube_video' ) { $this->image_url = $this->get_youtube_image_url(); }
		else { $this->image_url = $this->post_thumbnail_id ? wp_get_attachment_url( $this->post_thumbnail_id ) : $this->image_url; }
	}

	/**
	 * Render the view
	 * @since 2.0.0
	 */
	public function render() {		
		global $ewd_us_controller;

		$this->set_slide_specific_variables();

		$this->get_slide_content();

		$this->prep_slide_for_display();

		// Add css classes to the slide
		$this->classes = $this->slide_classes();

		ob_start();
		
		if ( $this->image_type == 'youtube_video' ) { $template = $this->find_template( 'slide-video' ); }
		else { $template = $this->find_template( 'slide' ); }
		
		if ( $template ) {
			include( $template );
		}
		$output = ob_get_clean();

		return apply_filters( 'ewd_us_slide_output', $output, $this );
	}

	/**
	 * Set the order this slide is being displayed in for the slider
	 * @since 2.0.0
	 */
	public function set_slide_count( $slide_count ) {

		$this->slide_count = intval( $slide_count );
	}

	/**
	 * Apply max title and body chars, create watermark image if necessary
	 * @since 2.0.0
	 */
	public function prep_slide_for_display() {
		global $ewd_us_controller;

		$this->title = $this->max_title_chars ? substr( $this->title, 0, $this->max_title_chars ) : $this->title;
		$this->filtered_content = $this->max_body_chars ? substr( $this->filtered_content, 0, $this->max_body_chars ) : $this->filtered_content;

		if ( $ewd_us_controller->settings->get_setting( 'add-watermark' ) and $this->image_type != 'youtube_video' ) {
			
			$this->image_url = $this->get_watermarked_image();
		}
	}

	/**
	 * Return the lightbox attributes for the slide, if lightbox enabled
	 * @since 2.0.0
	 */
	public function print_lightbox_data() {
		global $ewd_us_controller;

		if ( ! $ewd_us_controller->settings->get_setting( 'lightbox' ) ) { return; }

		return 'data-ulbsource="' . esc_attr( $this->image_url ) . '" data-ulbtitle="' . esc_attr( $this->title ) . '" data-ulbdescription="' . esc_attr( $this->filtered_content ) . '"';
	}

	/**
	 * Return the code to diplay a YouTube video slide
	 * @since 2.0.0
	 */
	public function print_youtube_video_code() {
		global $ewd_us_controller;

		if ( $ewd_us_controller->settings->get_setting( 'youtube-autoplay-video' ) ) {
			$youtube_autoplay = '&autoplay=1';
		}
		else {
			$youtube_autoplay = '';
		}

		if ( ! $this->youtube_url ) { return; }

		if ( empty( $this->youtube_video_id ) ) { $this->set_youtube_video_id(); }

		return '<iframe class="ewd-us-video" width="640" height="360" src="https://www.youtube.com/embed/' . $this->youtube_video_id . '?rel=0&fs=1' . $youtube_autoplay . '&mute=1&controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
	}

	/**
	 * Return the link for a slide button
	 * @since 2.0.0
	 */
	public function get_button_link( $button ) {
		
		return ! $button['Post_ID'] ? $button['Custom_Link'] : get_permalink( $button['Post_ID'] );
	}

	/**
	 * Return the link target for a slide button
	 * @since 2.0.0
	 */
	public function get_button_link_target_text( $button ) {
		global $ewd_us_controller;

		if ( $ewd_us_controller->settings->get_setting( 'link-action' ) == 'same' ) { return; }
		elseif ( $ewd_us_controller->settings->get_setting( 'link-action' ) == 'smart' and strpos( $this->get_button_link( $button ), get_site_url() ) !== false ) { return; }
		
		return 'target="_blank"';
	}

	/**
	 * Return the URL for the preview image of a YouTube video
	 * @since 2.0.0
	 */
	public function get_youtube_image_url() {

		if ( ! $this->youtube_url ) { return; }

		if ( empty( $this->youtube_video_id ) ) { $this->set_youtube_video_id(); }

		return 'http://img.youtube.com/vi/' . $this->youtube_video_id . '/default.jpg';
	}

	/**
	 * Determine the ID of a YouTube video
	 * @since 2.0.0
	 */
	public function set_youtube_video_id() {

		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $this->youtube_url, $matches);

		$this->youtube_video_id = $matches[0];
	}

	/**
	 * Set the variables (max title chars, max body chars, etc.) specific to this slide
	 * @since 2.0.0
	 */
	public function set_slide_specific_variables() {

 		$this->max_title_chars 	= get_post_meta( $this->ID, 'EWD_US_Max_Title_Chars', true );
		$this->max_body_chars 	= get_post_meta( $this->ID, 'EWD_US_Max_Body_Chars', true );
		$this->image_type 		= get_post_meta( $this->ID, "EWD_US_Image_Type", true );
		$this->youtube_url 		= get_post_meta( $this->ID, "EWD_US_YouTube_URL", true );
		$this->buttons 			= get_post_meta( $this->ID, "EWD_US_Buttons", true ) ? get_post_meta( $this->ID, "EWD_US_Buttons", true ) : $this->buttons;
	}

	/**
	 * Create watermarked image if necessary and return watermarked image URL
	 * @since 2.0.0
	 */
	public function get_watermarked_image() {

		$upload_dir = wp_upload_dir();
		$plugin_upload_dir = $upload_dir['baseurl'] . "/ultimate-slider/";

		if ( ! file_exists($plugin_upload_dir . basename( $this->image_url ) ) ) {
			
			ewd_us_create_watermarked_image( $this->image_url );
		}

		$watermarked_image_url = $plugin_upload_dir . basename( $this->image_url );

		return $watermarked_image_url;
	}

	/**
	 * Get the initial slide css classes
	 * @since 2.0.0
	 */
	public function slide_classes( $classes = array() ) {
		global $ewd_us_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-us-slide',
			)
		);

		if ( $this->slide_count !== 0 ) { $classes[] = 'ewd-us-hidden'; }

		if ( $this->image_type == 'youtube_video' ) { $classes[] = 'ewd-us-video'; }

		if ( $ewd_us_controller->settings->get_setting( 'lightbox' ) ) { $classes[] = 'ewd-ulb-lightbox'; }

		return apply_filters( 'ewd_us_slide_classes', $classes, $this );
	}

}
