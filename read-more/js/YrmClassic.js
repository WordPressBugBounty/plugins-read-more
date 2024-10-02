function YrmClassic() {

	this.id;
}

YrmClassic.prototype = new YrmMore();
YrmClassic.constructor = YrmClassic;

YrmClassic.prototype.init = function () {

	var id = this.id;
	if(typeof readMoreArgs[id] == 'undefined') {
		console.log('Invalid Data');
		return;
	}

	var data = readMoreArgs[id];

	this.setData('readMoreData', data);
	this.setData('id', id);
	this.setStyles();
	this.buttonDimensions();
	this.livePreview();

	var duration = parseInt(data['animation-duration']);

	jQuery('.yrm-toggle-expand-'+id).each(function () {
		var position = -1;
		var initialScroll = -1;

        jQuery(this).unbind('click').bind('click', function () {
            var toggleContentId = jQuery(this).attr('data-rel');
            var currentStatus = jQuery("#"+toggleContentId).data('show-status');
            if (typeof currentStatus == 'undefined') {
                currentStatus = false;
			}

			/*if currentStatus == true must be close read more*/
            if(currentStatus) {
                var mustDisplay = jQuery("#"+toggleContentId).attr('data-show-status');

                if(mustDisplay == "true") {
                    jQuery("#"+toggleContentId).attr('data-show-status', false);
                    jQuery("#" + toggleContentId).slideToggle(duration, 'swing', function () {

                    });
                }
                var currentScroll = document.documentElement.scrollTop;
                var scrollDifference = currentScroll - initialScroll;
                if (position != -1 && data['vertical'] != 'top' && scrollDifference && data['show-less-scroll-top']) {
                    jQuery("html,body").animate({scrollTop: document.documentElement.scrollTop-scrollDifference}, duration, 'swing');
                }
                var moreName = jQuery(this).find(".yrm-button-text").attr('data-more');
                jQuery(this).find(".yrm-button-text").text(data['moreName']);
			}
            else {
                initialScroll = document.documentElement.scrollTop;
                position = jQuery('#'+toggleContentId).offset().top;
                var mustDisplay = jQuery("#"+toggleContentId).attr('data-show-status');

                if(mustDisplay == 'false') {
                    jQuery("#"+toggleContentId).attr('data-show-status', true);
                    jQuery("#"+toggleContentId).slideToggle(duration, 'swing', function () {

                    });
                }
                var lessName = jQuery(this).find(".yrm-button-text").attr('data-less');
                jQuery(this).find(".yrm-button-text").text(data['lessName']);
			}
            jQuery("#"+toggleContentId).data('show-status', !currentStatus);
        });
	});
};