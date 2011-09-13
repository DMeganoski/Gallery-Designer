jQuery(function() {
	
	var LibraryRoot = gdn.combinePaths(gdn.definition('WebRoot'), 'js/library');
	var None = 'undefined';
	
	if (typeof($.fn.livequery) != 'function') $.getScript(gdn.combinePaths(LibraryRoot, 'jquery.livequery.js'));
	if (typeof($.fn.popup) != 'function') {
		$.getScript(gdn.combinePaths(LibraryRoot, 'jquery.popup.js'), function(){
			$('a.Popup').popup();
			$('a.PopConfirm').popup({'confirm': true, 'followConfirm': true});
		});		
	}
	if (typeof($.fn.ajaxForm) != 'function') {
		$.getScript(gdn.combinePaths(LibraryRoot, 'jquery.form.js'));
	}
	if (typeof($.fn.handleAjaxForm) != 'function') {
		$.getScript(gdn.combinePaths(LibraryRoot, 'jquery.gardenhandleajaxform.js'), function(){
			$('.AjaxForm').handleAjaxForm();
		});
	}
	
	var BoolButtonClick = function(){
		var self = this;
		var action = this.href;
		$(self).after('<span class="TinyProgress">&#160;</span>');
		$.ajax({
			type: "POST",
			url: action,
			data: {DeliveryType:'BOOL', DeliveryMethod:'JSON'},
			dataType: 'json',
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$('.Popup, .Overlay').remove(); // Remove any old popups
				$.popup.settings.sender = self;
				var Message = $.PopupErrorMessage(XMLHttpRequest, textStatus);
				$.popup({}, Message);
			},
			success: function(json) {
				$("a.BoolButton").unbind("click");
				if (json.RedirectUrl) setTimeout("document.location='" + gdn.url(json.RedirectUrl) + "';", 1);
				if (json.Targets) gdn.processTargets(json.Targets);
				return false;
			},
			complete: function(XMLHttpRequest, textStatus) {
				$(self).next().remove();
			}
		});
		return false;
	}
	
	$("a.BoolButton").live("click", BoolButtonClick);
	
	
	// Inline edit.
	if ($.fn.inlineEdit) {
		var Settings = {
			classbuttons: 'SmallButton',
			dataType: 'json',
			beforeload: function(eventarguments) {
				var element = eventarguments.element;
				var id = element[0].id.substr(5);
				var Url = gdn.url("/candy/chunk/update/"+ id + "/" + gdn.definition('TransientKey') + '?DeliveryType=DATA');
				//var val = $(element).text();
				$.ajax({
					async: false,
					type: "GET",
					url: Url,
					beforeSend: function(jqxhr, settings) {
						if (typeof(gdn.informMessage) != None) gdn.informMessage('Loading...', {Sprite: 'Download'});
						$('div.InformWrapper.Dismissable a.Close').click();
					},
					dataType: 'json',
					success: function(data) {
						var content = data.Content;
						eventarguments.val = content.Body;
						return false;
					}
				});
			},
			success: function(json, textStatus, xhr, eventarguments) {
				gdn.inform(json);
				eventarguments.html = json.NewBody;
			},
			error: function() {
				var arguments = arguments[0];
				var XMLHttpRequest = arguments[0], textStatus = arguments[1];
				$('.Popup, .Overlay').remove(); // Remove any old popups
				var Message = $.PopupErrorMessage(XMLHttpRequest, textStatus);
				$.popup({}, Message);
			}
		};
		
		$('div[id^=Chunk]').each(function(Index, Element) {
			$(Element).attr('rel', 'Body');
			var id = Element.id.substr(5);
			var o = $.extend({ }, Settings);
			if ($(Element).is('.EditableTextarea')) $.extend(o, {type: 'textarea', classname: 'TextBox'});
			o.url = gdn.url('/candy/chunk/update/' + id + '/' + gdn.definition('TransientKey') + '?DeliveryType=BOOL&DeliveryMethod=JSON');
			$(Element).inlineEdit(o);
			//console.log(o, id, Element);
		});
		
		// $('.EditableTextarea').inlineEdit({
			// url: Url,
			// type: 'textarea',
			// classname: 'TextBox',
			// classbuttons: 'SmallButton'
		// });

/*		$('.EditableText').inlineEdit({
			url: Url,
			type: 'text',
			classname: 'InputBox',
			complete: $.noop,
			classbuttons: 'SmallButton'
		});*/
/*		$('.EditableSelect').inlineEdit({
			type: 'select',
			complete: $.noop,
			select: []
		});
		$('.EditableRadio').inlineEdit({
			type: 'radio',
			complete: $.noop,
			select: []
		});
		$('.EditableCheck').inlineEdit({
			type: 'check',
			complete: onComplete,
			select: []
		});*/
	}
	

});

jQuery.PopupErrorMessage = function(XMLHttpRequest, textStatus) {
	var Message = '';
	var ErrorText = '';
	var json = false;
	var Wrap = true;
	// textStatus = { "timeout", "error", "abort", and "parsererror" } // textStatus = error;
	try {
		json = jQuery.parseJSON(XMLHttpRequest.responseText);
		if (json && json.Exception) ErrorText = json.Exception;
	} catch(e){
		// Invalid JSON
		Wrap = false;
		// Looks like it is html (DeliveryType=VIEW, DeliveryMethod=XHTML)
	}
	if (!ErrorText) ErrorText = XMLHttpRequest.responseText;
	if (Wrap) Message = '<h1>Error</h1><div class="Wrap AjaxError">' + ErrorText + '</div>';
	else Message = ErrorText;
	return Message;
}


/*
* doWhen jQuery plugin
* Copyright 2011, Emmett Pickerel
* Released under the MIT Licence.
*/
//!function(a){var b,c,d;b={interval:100},c=function(a){a.test()&&(clearInterval(a.iid),a.cb.call(a.context||window,a.data))},d=function(a){a.iid=setInterval(function(){c(a)},a.interval)},a.doWhen=function(c,e,f){d(a.extend({test:c,cb:e},b,f))}}(window.jQuery);
	
	
