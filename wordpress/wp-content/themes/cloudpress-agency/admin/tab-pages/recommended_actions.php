<?php 
	$cloudpress_agency_actions = $this->recommended_actions;
	$cloudpress_agency_actions_todo = get_option( 'cloudpress_agency_recommended_actions', false );
?>
<div id="recommended_actions" class="cloudpress-agency-tab-pane panel-close">
<div class="action-list">
    <div class="row">
	<?php if($cloudpress_agency_actions): foreach ($cloudpress_agency_actions as $key => $cloudpress_agency_val): ?>
	<div class="col-md-6">
	<div class="action" id="<?php echo esc_attr($cloudpress_agency_val['id']); ?>">
		<div class="action-watch">
		<?php if(!$cloudpress_agency_val['is_done']): ?>
			<?php if(!isset($cloudpress_agency_actions_todo[$cloudpress_agency_val['id']]) || !$cloudpress_agency_actions_todo[$cloudpress_agency_val['id']]): ?>
				<span class="dashicons dashicons-visibility"></span>
			<?php else: ?>
				<span class="dashicons dashicons-hidden"></span>
			<?php endif; ?>
		<?php else: ?>
			<span class="dashicons dashicons-yes"></span>
		<?php endif; ?>
		</div>
		<div class="action-inner">
			<h3 class="action-title"><?php echo esc_html($cloudpress_agency_val['title']); ?></h3>
			<div class="action-desc"><?php echo esc_html($cloudpress_agency_val['desc']); ?></div>
			<?php echo wp_kses_post($cloudpress_agency_val['link']); ?>
		</div>
	</div>
	</div>
	<?php endforeach; endif; ?>
</div>
</div>
    </div>