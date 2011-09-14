/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
	$('.PanelActions li').hide();
	$('.Sublist').hide();
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
});
