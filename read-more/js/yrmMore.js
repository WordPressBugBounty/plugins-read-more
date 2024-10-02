function YrmMore() {

	this.data = [];
}

YrmMore.prototype.setData = function (dataName, value) {

	this.data[dataName] = value;
};

YrmMore.prototype.getData = function(dataName) {

	return this.data[dataName];
};

YrmMore.prototype.binding = function() {
	var thaT = this;

	this.styles();

};

YrmMore.prototype.setStyles = function () {

	var data = this.getData('readMoreData');


	this.setChengHorizontalAlign(".yrm-btn-wrapper",data['horizontal']);

	this.setFontSize(".yrm-button-text", data['font-size']);
	if(typeof this.proInit == 'function') {
		this.proInit();
	}
};

YrmMore.prototype.buttonDimensions = function() {

	var data = this.getData('readMoreData');
	var id = this.getData('id');

	var width = data['button-width'];
	var height = data['button-height'];

	jQuery(".yrm-toggle-expand-"+id).css({
		'width': width,
		'height': height
	});
};

YrmMore.prototype.styles = function() {
	
	var data = this.getData('readMoreData');
	var fontSize = data['font-size'];

	this.setFontSize(".yrm-button-text", fontSize);
};

YrmMore.prototype.setFontSize = function (element, fontSize) {

	jQuery(element).css({
		'font-size': fontSize
	})
};

YrmMore.prototype.setChengHorizontalAlign = function(element, val) {

	jQuery(element).css({"text-align": val});
};

YrmMore.prototype.livePreview = function() {
	
	this.changeButtonWidth();
	this.changeButtonHeight();
	this.changeButtonFontSize();
	this.changeBtnBackgroundColor();
	this.changeBtnTextColor();
	this.changeBorderRadius();
	this.changeHorizontalAligment();
	this.addFontFamilyOptionsView();
	this.changeButtonFontFamily();
};

YrmMore.prototype.changeButtonFontFamily = function() {

	var that = this;
	jQuery('[name="expander-font-family"]').bind("change", function() {
		var val = jQuery(this).find('option:selected').text();
		var element = ".yrm-toggle-expand";
		that.setButyonFontFamily(element, val);
	});
};

YrmMore.prototype.addFontFamilyOptionsView = function() {

	jQuery('[name="expander-font-family"]').find('option').each(function() {
		var family = jQuery(this).text();
		jQuery(this).css({'font-family': family})
	})
};

YrmMore.prototype.changeButtonWidth = function() {
	jQuery('.expm-btn-width').change(function() {
		var width = jQuery(this).val();
		jQuery(".yrm-toggle-expand").css({
			"width": width
		});
	});
};

YrmMore.prototype.changeButtonHeight = function() {
	jQuery(".expm-btn-height").change(function() {
		var height = jQuery(this).val();
		jQuery(".yrm-toggle-expand").css({
			"height": height
		});
	});
};

YrmMore.prototype.changeButtonFontSize = function() {
	jQuery('.expm-option-font-size').change(function() {
		var size = jQuery(this).val();
		jQuery(".yrm-button-text").css({
			'font-size': size
		})
	});
};

YrmMore.prototype.changeBtnBackgroundColor = function() {
	var that = this;
	if(typeof jQuery.fn.wpColorPicker != 'undefined') {
		jQuery('.background-color').wpColorPicker({
			change: function() {
				var val = jQuery(this).val();
				var element = ".yrm-toggle-expand";
				that.setBackgroundColor(element, val);
			}
		});
	}
};

YrmMore.prototype.changeBorderRadius = function() {

	var that = this;
	jQuery(".btn-border-radius").change(function() {
		
		var value = jQuery(this).val();
		var element = ".yrm-toggle-expand";
		that.setBorderRadius(element, value);
	});
};

YrmMore.prototype.changeBtnTextColor = function() {
	var that = this;
	if(typeof jQuery.fn.wpColorPicker != 'undefined') {
		jQuery(".btn-text-color").wpColorPicker({
			change: function () {
				var val = jQuery(this).val();
				var elemnt = ".yrm-toggle-expand";
				that.setTextColor(elemnt, val);
			}
		});
		jQuery(".btn-hover-color").wpColorPicker({});
	}
};

YrmMore.prototype.changeHorizontalAligment = function() {

	var that = this;
	jQuery("[name='horizontal']").change(function() {
		var val = jQuery(this).val();
		var element = ".expand-btn-wrappper";
		that.setChengHorizontalAlign(element, val);
	});
};

