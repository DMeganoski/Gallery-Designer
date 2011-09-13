/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
// Frame Changes
	$('li#Click').click(function() {
		$('#FrameWrapper').hide('300').removeClass("PewterFrame GoldEmblemFrame TraditionalFrame ModernFrame WoodFrame GoldLeafFrame", "0");
		$('li.None, li.Pewter, li.GoldEmblem, li.Traditional, li.Modern, li.Wood, li.GoldLeaf').removeClass("Active");
	});
	$('li.None').click(function() {
		$(this).toggleClass("Active");
	});
	$('li.Pewter').click(function() {
		$('#FrameWrapper').addClass("PewterFrame", "0").show('200');
		$(this).toggleClass("Active");
	});
	$('li.GoldEmblem').click(function() {
		$('#FrameWrapper').addClass("GoldEmblemFrame", "0").show('200');
		$(this).toggleClass("Active");
	});
	$('li.Traditional').click(function() {
		$('#FrameWrapper').addClass("TraditionalFrame", "0").show('200');
		$(this).toggleClass("Active");
	});
	$('li.Modern').click(function() {
		$('#FrameWrapper').addClass("ModernFrame", "0").show('200');
		$(this).toggleClass("Active");
	});
	$('li.Wood').click(function() {
		$('#FrameWrapper').addClass("WoodFrame", "0").show('200');
		$('this').toggleClass("Active");
	});
	$('li.GoldLeaf').click(function() {
		$('#FrameWrapper').addClass("GoldLeafFrame", "0").show('200');
		$('li.GoldLeaf').toggleClass("Active");
	});
	$('#ActionLabel').click(function() {
		$('.Progress').toggleClass("Progress");
	});
});
