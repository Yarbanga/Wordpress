<?php foreach ( $this->slides as $slide_counter => $slide ) { ?>
	<div class="ewd-us-slider-control-thumbnail" data-slidenumber="<?php echo $slide_counter; ?>">
		<img src="<?php echo $slide->image_url; ?>" class="ewd-us-slider-control-thumbnail-img" />
	</div>
<?php } ?>