<div class="wrap">
    <a href="https://widgetwhats.com/" style="text-decoration:none;" target="_blank"><h1>WidgetWA <span class="dashicons dashicons-external"></span> - Integration</h1></a>
    <p>Engage customers to start a whatsapp chat with you right from your site. Create your first widget or edit existing widget by clicking button below.</p>
    <form method="post" action="options.php"> 
        <?php @settings_fields( 'widgetwhats-group' ); ?>

		<table class="form-table">  
			<tr valign="top">
				<th scope="row"><label>Widget Setting</label></th>
				<td>
					<?php if ( get_option( 'widgetwhatsID' ) == '' ) { ?>
					<a href="https://widgetwhats.com/" class="button create-widget" target="_blank">Create New Widget</a>
					<?php } else { ?>
					<a href="https://panel.widgetwhats.com/setting/edit.php?post_type=widget" class="button" target="_blank">Open Widget Setting</a>
					<?php } ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="widgetwhatsID">Widget ID</label></th>
				<td><input type="number" name="widgetwhatsID" id="widgetwhatsID" value="<?php echo htmlspecialchars( get_option( 'widgetwhatsID' ) ); ?>" />
					<div style="font-size:85%;margin-top:5px;">You can find Widget ID on data-id attribute inside <a href="https://panel.widgetwhats.com/setting/edit.php?post_type=widget" target="_blank">embed code</a>.</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show widget on</th>
				<td>
					<fieldset>
						<label for="widgetwhatsCheckAll">
							<input type="checkbox" name="widgetwhatsCheckAll" id="widgetwhatsCheckAll" onClick="toggle(this);" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckAll' ), false ); ?>" />
							All
						</label>
						<br />
						<label for="widgetwhatsCheckHomepage">
							<input type="checkbox" name="widgetwhatsCheckHomepage" class="checkAll" id="widgetwhatsCheckHomepage" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckHomepage' ), false ); ?>" />
							Home Page
						</label>
						<br />
						<label for="widgetwhatsCheckFrontpage">
							<input type="checkbox" name="widgetwhatsCheckFrontpage" class="checkAll" id="widgetwhatsCheckFrontpage" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckFrontpage' ), false ); ?>" />
							Blog Index
						</label>
						<br />
						<label for="widgetwhatsCheckPosts">
							<input type="checkbox" name="widgetwhatsCheckPosts" class="checkAll" id="widgetwhatsCheckPosts" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckPosts' ), false ); ?>" />
							Blog Posts
						</label>
						<br />
						<label for="widgetwhatsCheckPages">
							<input type="checkbox" name="widgetwhatsCheckPages" class="checkAll" id="widgetwhatsCheckPages" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckPages' ), false ); ?>" />
							Pages
						</label>
						<br />
						<?php if ( class_exists( 'WooCommerce' ) ) { ?>
						<label for="widgetwhatsCheckProducts">
							<input type="checkbox" name="widgetwhatsCheckProducts" class="checkAll" id="widgetwhatsCheckProducts" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckProducts' ), false ); ?>" />
							Products
						</label>
						<br />
						<?php } ?>
						<label for="widgetwhatsCheckArchive">
							<input type="checkbox" name="widgetwhatsCheckArchive" class="checkAll" id="widgetwhatsCheckArchive" value="1" <?php echo checked( 1, get_option( 'widgetwhatsCheckArchive' ), false ); ?>" />
							Archive
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="widgetwhatsInclude">Show on specific post or page</label></th>
				<td><input type="text" name="widgetwhatsInclude" id="widgetwhatsInclude" value="<?php echo htmlspecialchars( get_option( 'widgetwhatsInclude' ) ); ?>" size="64" />
					<div style="font-size:85%;margin-top:5px;">You can add multiple ID's. Separate with comma (,) for each ID.</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="widgetwhatsExclude">Hide on specific post or page</label></th>
				<td><input type="text" name="widgetwhatsExclude" id="widgetwhatsExclude" value="<?php echo htmlspecialchars( get_option( 'widgetwhatsExclude' ) ); ?>" size="64" />
					<div style="font-size:85%;margin-top:5px;">You can add multiple ID's. Separate with comma (,) for each ID.</div>
				</td>
			</tr>
		</table>

		<?php @submit_button(); ?>
	</form>
</div>

<script>
var selectedIds = [];
function toggle(source) {
	checkboxes = document.querySelectorAll('.checkAll');
	for ( var i in checkboxes)
		checkboxes[i].checked = source.checked;
}
function addSelects() {
	var ids = document.querySelectorAll('.checkAll');
	for ( var i = 0; i < ids.length; i++) {
		if (ids[i].checked == true) {
			selectedIds.push(ids[i].value);
		}
	}
}
</script>
