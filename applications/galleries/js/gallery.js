
$(document).ready(function() {
	$('.PanelActions li').hide();
	$('.Sublist').hide();
	// Toggles Categories
	$('#CategoryLabel').click(function() {
		$('.PanelCategories li').toggle();
	});
	// Toggles Actions
	$('#ActionLabel').live('click', function() {
		$('.PanelActions li').toggle();
	});
	$('ol.Category').hide();
	$('table.Heading').click(function() {
		$(this).next('ol').toggle('fast');
	});
	var toggle = function(direction, display) {
    return function() {
      var self = this;
      var ul = $("ul", this);
      if( ul.css("display") == display && !self["block" + direction] ) {
        self["block" + direction] = true;
        ul["slide" + direction]("fast", function() {
          self["block" + direction] = false;
        });
      }
    };
  }
  $("li.Navigation").hover(toggle("Down", "none"), toggle("Up", "block"));
  $("li.Navigation ul").hide();
/*--------------------------------- Functions -----------------------------------*/

/*--------------------------------- QTips ---------------------------------------*/
	$('.Icon').qtip({ /* For links containing no text */
		content: {
			text: function(api) {
				return $(this).attr('title');
			}
		},
		position: {
		  my: 'top center', /* where to put tooltip in relation to the object */
		  at: 'bottom center' /* where to put object in relation to the tooltip */
		},
		style: {
			classes: 'ui-tooltip-light ui-tooltip-shadow'
		}
	});
	$("li.Image").hover(function() {
		$(this).css({'z-index' : '10'}); /*Add a higher z-index value so this image stays on top*/
		$(this).find('img').addClass("hover").stop() /* Add class of "hover", then stop animation queue buildup*/
		.animate({
			width: '150px', /* Set new width */
			height: '150px' /* Set new height */
		}, 200); /* this value of "200" is the speed of how fast/slow this hover animates */

	} , function() {
	$(this).css({'z-index' : '0'}); /* Set z-index back to 0 */
	$(this).find('img').removeClass("hover").stop()  /* Remove the "hover" class , then stop animation queue buildup*/
		.animate({
			top: '0',
			left: '0',
			width: '120px', /* Set width back to default */
			height: '120px' /* Set height back to default */
		}, 400);
	});

	/*$('li.Image img').qtip({
	content: {
		text: function(api) {
			// Retrieve content from custom attribute of the $('li.Image img') elements.
			return $(this).attr('content');
		},
		title: {
			text: 'Image Info',
			button: 'Close'
		}
	},
	position: {
		  my: 'bottom center',
		  at: 'top center'
	},
	style: {
    classes: 'ui-tooltip-light ui-tooltip-shadow'
   }

});*/

});


