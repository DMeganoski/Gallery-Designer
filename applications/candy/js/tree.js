$(function(){
	$('.Tree .Options a:not(.PopConfirm)').popup({
		afterSuccess: function(json, sender) {
            $("#Content").load(gdn.url('candy/section/tree?DeliveryType=VIEW'));
        }
    });
	
	var divs = '.Tree ul li > div';
	$(divs).live('mouseenter', function(e){
		var $row = $(e.target);
		if (!$row.is('div')) $row = $row.parents('div').first();
		$(divs).removeClass('Hovered');
		$row.addClass('Hovered');
	});

});