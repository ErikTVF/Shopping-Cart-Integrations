<div class="wrap">
<h2>Tapfiliate</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('tapfiliate'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Tap Account ID:</th>
<td><input type="text" name="tap_account_id" value="<?php echo get_option('tap_account_id'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row">Conversion/Thank You page:</th>
<td>
	<select name="thank_you_page">
		<?php
			foreach (get_pages() as $page) {
				$field = "<option value='{$page->post_name}'";
				$field .= (get_option('thank_you_page') === $page->post_name) ? " selected" : null;
				$field .= ">{$page->post_title}</option>";
				echo $field;
			}
		?>
	</select>
</td>
</tr>
<tr valign="top">
<th scope="row">Transaction Id Query Parameter:</th>
<td>
	<input type="text" name="query_parameter_transaction_id" value="<?php echo get_option('query_parameter_transaction_id'); ?>" />
</td>
</tr>

<tr valign="top">
<th scope="row">Transaction Amount Query Parameter:</th>
<td>
	<input type="text" name="query_parameter_transaction_amount" value="<?php echo get_option('query_parameter_transaction_amount'); ?>" />
</td>
</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
