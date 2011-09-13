jQuery(function() {

	if ($.fn.textpandable) {
		$("#Form_Body").textpandable({speed:0, maxRows:35});
	}
	var LastTimeStamp = 0;	
	var SlugHandler = function() {
		var Enabled = true;
		//var Enabled = $('#Form_CreateSection')[0].checked;
		if (Enabled) {
			var Text = $('#Form_Title').val();
			$.post(gdn.url('candy/slug'), {Text:Text}, function(text){
				$("#Form_URI").val(text);
			});
		}
		return false;
	}

	$("#GetSlugButton").live('click', SlugHandler);
	
	$('.ToggleButton').live('click', function(){
		var bRemoveSelf = $(this).hasClass('RemoveSelf');
		var classname = $.trim($(this).attr('class').replace('ToggleButton', '')).split(' ')[0];
		var items = $(this).parents('ul').find('.'+classname).not(this);
		var func = (items.is(':visible')) ? 'fadeOut' : 'fadeIn';
		if (bRemoveSelf) $(this).fadeOut('fast');
		//console.log(bRemoveSelf, items, '"'+classname+'"', items.is(':visible'));
		items[func]('fast');
		return false;
	});

	
});