<?php

class ReadMoreAdminHelper
{
	public static function getReadMoreMainTableCreationDate() {
		global $wpdb;
		
		$query = $wpdb->prepare('SELECT table_name,create_time FROM information_schema.tables WHERE table_schema="%s" AND  table_name="%s"', DB_NAME, $wpdb->prefix.'expm_maker');
		$results = $wpdb->get_results($query, ARRAY_A);
		
		if(empty($results)) {
			return 0;
		}
		
		$createTime = $results[0]['create_time'];
		$createTime = strtotime($createTime);
		update_option('YRMInstallDate', $createTime);
		$diff = time()-$createTime;
		$days  = floor($diff/(60*60*24));
		
		return $days;
	}
	
	public static function shouldOpenReviewNoticeForDays() {
		$shouldOpen = true;
		$dontShowAgain = get_option('YRMCloseReviewNotice');
		$periodNextTime = get_option('YRMOpenNextTime');
		
		if($dontShowAgain) {
			return false;
		}
		
		/*When period next time does not exits it means the user is old*/
		if(!$periodNextTime) {
			$usageDays = self::getReadMoreMainTableCreationDate();
			update_option('YRMUsageDays', $usageDays);
			
			/*When very old user*/
			if($usageDays > YRM_REVIEW_NOTICE_PERIOD && !$dontShowAgain) {
				return $shouldOpen;
			}
			
			$remainingDays = YRM_REVIEW_NOTICE_PERIOD - $usageDays;
			
			$timeDate = new DateTime('now');
			$timeDate->modify('+'.$remainingDays.' day');
			
			$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
			update_option('YRMOpenNextTime', $timeNow);
			
			return false;
		}
		
		$currentData = new DateTime('now');
		$timeNow = $currentData->format('Y-m-d H:i:s');
		$timeNow = strtotime($timeNow);
		
		if($periodNextTime > $timeNow) {
			return false;
		}
		
		return $shouldOpen;
	}
	
	public static function getMaxOpenDaysMessage()
	{
		$getUsageDays = self::getUsageDays();
		$firstHeader = '<h1 class="yrm-review-h1"><strong class="yrm-review-strong">Wow!</strong> You’ve been using " Read more " on your site for '.$getUsageDays.' days</h1>';
		$content = self::getMaxOepnContent($firstHeader, 'days');
		
		$content .= self::showReviewBlockJs();
		
		return $content;
	}
	
	public static function showReviewBlockJs()
	{
		ob_start();
		?>
		<script type="text/javascript">
			jQuery('.yrm-already-did-review').each(function () {
				jQuery(this).on('click', function () {
					var ajaxNonce = jQuery(this).attr('data-ajaxnonce');

					var data = {
						action: 'yrm_dont_show_review',
						ajaxNonce: ajaxNonce
					};
					jQuery.post(ajaxurl, data, function(response,d) {
						
						if(jQuery('.yrm-review-block').length) {
							jQuery('.yrm-review-block').remove();
						}
					});
				});
			});

			jQuery('.yrm-show-period').on('click', function () {
				var ajaxNonce = jQuery(this).attr('data-ajaxnonce');
				var messageType = jQuery(this).attr('data-message-type');

				var data = {
					action: 'yrm_change_review_show_period',
					messageType: messageType,
					ajaxNonce: ajaxNonce
				};
				jQuery.post(ajaxurl, data, function(response,d) {
					if(jQuery('.yrm-review-block').length) {
						jQuery('.yrm-review-block').remove();
					}
				});
			});
		</script>
		<?php
		$content = ob_get_clean();
		
		return $content;
	}
	
	public static function getMaxOepnContent($firstHeader, $type) {
		$ajaxNonce = wp_create_nonce("yrmReviewNotice");
		ob_start();
		?>
		<style>
			.yrm-buttons-wrapper .press{
				box-sizing:border-box;
				cursor:pointer;
				display:inline-block;
				font-size:1em;
				margin:0;
				padding:0.5em 0.75em;
				text-decoration:none;
				transition:background 0.15s linear
			}
			.yrm-buttons-wrapper .press-grey {
				background-color:#9E9E9E;
				border:2px solid #9E9E9E;
				color: #FFF;
			}
			.yrm-buttons-wrapper .press-lightblue {
				background-color:#03A9F4;
				border:2px solid #03A9F4;
				color: #FFF;
			}
			.yrm-review-wrapper{
				text-align: center;
				padding: 20px;
			}
			.yrm-review-wrapper p {
				color: black;
			}
			.yrm-review-h1 {
				font-size: 22px;
				font-weight: normal;
				line-height: 1.384;
			}
			.yrm-review-h2{
				font-size: 20px;
				font-weight: normal;
			}
			:root {
				--main-bg-color: #1ac6ff;
			}
			.yrm-review-strong{
				color: var(--main-bg-color);
			}
			.yrm-review-mt20{
				margin-top: 20px
			}
		</style>
		<div class="yrm-review-wrapper">
			<div class="yrm-review-description">
				<?php echo esc_html($firstHeader); ?>
				<h2 class="yrm-review-h2">This is really great for your website score.</h2>
				<p class="yrm-review-mt20">Have your input in the development of our plugin, and we’ll provide better conversions for your site!<br /> Leave your 5-star positive review and help us go further to the perfection!</p>
			</div>
			<div class="yrm-buttons-wrapper">
				<button class="press press-grey yrm-button-1 yrm-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>">I already did</button>
				<button class="press press-lightblue yrm-button-3 yrm-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" onclick="window.open('<?php echo YRM_REVIEW_URL; ?>')">You worth it!</button>
				<button class="press press-grey yrm-button-2 yrm-show-period" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" data-message-type="<?php echo esc_attr($type); ?>">Maybe later</button></div>
			<div> </div>
		</div>
		<?php
		$content = ob_get_clean();
		
		return $content;
	}
	
	public static function getUsageDays()
	{
		$installDate = get_option('YRMInstallDate');
		
		$timeDate = new DateTime('now');
		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
		
		$diff = $timeNow-$installDate;
		
		$days  = floor($diff/(60*60*24));
		
		return $days;
	}
}