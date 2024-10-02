<?php
$params = $dataObj::params();
$type = sanitize_text_field($_GET['type']);
$id = '';
if(!isset($type)) {
	$type = "button";
}
if (!empty($_GET['readMoreId'])) {
	$id = (int)$_GET['readMoreId'];
}
?>
<?php if(!empty($_GET['saved'])) : ?>
	<div id="default-message" class="updated notice notice-success is-dismissible">
		<p>Read more updated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	</div>
<?php endif; ?>
<div class="ycf-bootstrap-wrapper">
<form method="POST" action="<?php echo admin_url();?>admin-post.php?action=arm_save_data">
	<?php
		if(function_exists('wp_nonce_field')) {
			wp_nonce_field('read_more_save');
		}
	?>
<input type="hidden" name="read-more-type" value="<?php echo esc_attr($type); ?>">
<input type="hidden" name="read-more-id" value="<?php echo esc_attr($id); ?>">
<div class="expm-wrapper">
	<div class="titles-wrapper">
		<h2 class="expander-page-title">Change settings</h2>
		<div class="button-wrapper">
			<p class="submit">
				<?php if(YRM_PKG == 1): ?>
					<input type="button" class="expm-update-to-pro" value="Upgrade to PRO version" onclick="window.open('<?php echo YRM_PRO_URL; ?>');">
				<?php endif;?>
				<input type="submit" class="button-primary" value="<?php echo 'Save Changes'; ?>">
			</p>
		</div>
	</div>
	<div class="clear"></div>
	<div class="row">
		<div class="col-xs-12">
			<input type="text" name="expm-title" class="form-control input-md" placeholder="Read more title" value="<?php echo esc_attr($typeObj->getOptionValue('expm-title')); ?>">
		</div>
	</div>
	<div class="options-wrapper">
		<div class="panel panel-default">
			<div class="panel-heading">General options</div>
			<div class="panel-body">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Button width', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="form-control input-md expm-options-margin expm-btn-width" name="button-width" value="<?php echo esc_attr($typeObj->getOptionValue('button-width'));?>"><br>
					</div>
					<div class="col-md-2 expm-option-info">(in pixels)</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Button height', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="form-control input-md expm-options-margin expm-btn-height" name="button-height" value="<?php echo esc_attr($typeObj->getOptionValue('button-height'));?>"><br>
					</div>
					<div class="col-md-2 expm-option-info">(in pixels)</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Font size', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type='text' class="form-control input-md expm-option-font-size" name="font-size" value="<?php echo esc_attr($typeObj->getOptionValue('font-size'))?>"><br>
					</div>
					<div class="col-md-2 expm-option-info">(in pixels)</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Set animation speed', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
					<input type="text" class="form-control input-md  expm-options-margin" name="animation-duration" value="<?php echo esc_attr($typeObj->getOptionValue('animation-duration'))?>">
					</div>
					<div class="col-md-2 expm-option-info"></div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="show-less-scroll-top"><?php _e('After "Show Less" scroll to initial possition', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<label class="yrm-switch">
							<input type="checkbox" id="show-less-scroll-top" name="show-less-scroll-top" class="yrm-accordion-checkbox" <?php echo $typeObj->getOptionValue('show-less-scroll-top') ? 'checked': ''; ?>>
							<span class="yrm-slider yrm-round"></span>
						</label>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default yrm-pro-options-wrapper">
			<div class="panel-heading"><?php _e('Advanced options', YRM_TEXT_DOMAIN);?></div>
			<div class="panel-body">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Background Color', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="input-md background-color" name="btn-background-color" value="<?php echo esc_attr($typeObj->getOptionValue('btn-background-color')); ?>"><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Text Color', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="input-md btn-text-color" name="btn-text-color" value="<?php echo esc_attr($typeObj->getOptionValue('btn-text-color'))?>"><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Font Family', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<?php echo $functions::createSelectBox($params['googleFonts'],"expander-font-family", esc_attr($typeObj->getOptionValue('expander-font-family')));?><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Border radius', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="form-control input-md btn-border-radius" name="btn-border-radius" value="<?php echo esc_attr($typeObj->getOptionValue('btn-border-radius'))?>"><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Horizontal alignment', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<?php echo $functions::createSelectBox($params['horizontalAlign'],"horizontal", esc_attr($typeObj->getOptionValue('horizontal')));?><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Vertical alignment', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<?php echo $functions::createSelectBox($params['vertical'],"vertical", esc_attr($typeObj->getOptionValue('vertical')));?><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="arm-show-on-selected-devices"><?php _e('Show On Selected Devices', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<label class="yrm-switch">
							<input type="checkbox" id="arm-show-on-selected-devices" name="arm-show-on-selected-devices" class="yrm-accordion-checkbox" <?php echo $typeObj->getOptionValue('arm-show-on-selected-devices')  ? 'checked': ''; ?>>
							<span class="yrm-slider yrm-round"></span>
						</label>
					</div>
				</div>
				<div class="yrm-accordion-content yrm-hide-content">
					<div class="row row-static-margin-bottom">
						<div class="col-xs-5">
							<label class="control-label" for="show-on-selected-devices"><?php _e('Select device(s)', YRM_TEXT_DOMAIN);?>:</label>
						</div>
						<div class="col-xs-4">
							<?php echo $functions::yrmSelectBox($params['devices'], $typeObj->getOptionValue('arm-selected-devices'), array('name'=>"arm-selected-devices[]", 'multiple'=>'multiple', 'class'=>'yrm-select2'));?>
						</div>
					</div>
					<div class="row row-static-margin-bottom">
						<div class="col-xs-5">
							<label class="control-label" for="arm-hide-content"><?php _e('hide content if not matched devices', YRM_TEXT_DOMAIN);?>:</label>
						</div>
						<div class="col-xs-4">
							<label class="yrm-switch">
								<input type="checkbox" id="arm-hide-content" name="arm-hide-content" class="yrm-accordion-checkbox" <?php echo $typeObj->getOptionValue('arm-hide-content')  ? 'checked': ''; ?>>
								<span class="yrm-slider yrm-round"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Hover effect', YRM_TEXT_DOMAIN);?>:</label>
					</div>
					<div class="col-xs-4">
						<label class="yrm-switch">
							<input type="checkbox" id="shover-effect" name="hover-effect" class="yrm-accordion-checkbox" <?php echo $typeObj->getOptionValue('hover-effect')  ? 'checked': ''; ?>>
							<span class="yrm-slider yrm-round"></span>
						</label>
					</div>
				</div>
				<div class="yrm-accordion-content yrm-hide-content">
					<div class="row">
						<div class="col-xs-5">
							<label class="control-label" for="textinput"><?php _e('button color', YRM_TEXT_DOMAIN);?>:</label>
						</div>
						<div class="col-xs-5">
							<input type="text" class="input-md btn-hover-color" name="btn-hover-text-color" value="<?php echo esc_attr($typeObj->getOptionValue('btn-hover-text-color'))?>" >
						</div>
					</div>
					<div class="row">
						<div class="col-xs-5">
							<label class="control-label" for="textinput"><?php _e('button bg color', YRM_TEXT_DOMAIN);?>:</label>
						</div>
						<div class="col-xs-5">
							<input type="text" class="input-md btn-hover-color" name="btn-hover-bg-color" value="<?php echo esc_attr($typeObj->getOptionValue('btn-hover-bg-color'))?>" >
						</div>
					</div>
				</div>

			</div>
			<?php if(YRM_PKG == 1) :?>
				<div class="yrm-pro-options" onclick="window.open('<?php echo YRM_PRO_URL; ?>');">

				</div>
			<?php endif;?>
		</div>
	</div>
	<div class="right-side">
		<div class="panel panel-default">
			<div class="panel-heading">Live preview</div>
			<div class="panel-body">
				<?php require_once(YRM_VIEWS."livePreview/buttonPreview.php");?>
			</div>
		</div>
		<?php $shortCode = '[read_more id="'.$id.'" more="Read more" less="Read less"]Read more hidden text[/read_more]'; ?>
		<?php if($id != 0): ?>
			<input type="text" id="expm-shortcode-info-div" class="widefat" readonly="readonly" value='<?php echo esc_attr($shortCode); ?>'>
		<?php endif; ?>
	</div>
</div>
</form>
</div>