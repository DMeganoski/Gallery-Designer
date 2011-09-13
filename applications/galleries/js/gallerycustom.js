/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
	$('ul.SubList').hide();
	$('h2.Format').click(function() {
		$(this).next("ul").toggle('200');
	});
	$('ul.HowTo').hide();
	$('h2.Step').click(function() {
		$(this).next("ul").toggle('fast');
	});
});

