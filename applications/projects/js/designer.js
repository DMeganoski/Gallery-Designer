/*
 * Variety of fucntions related specifically to the designer page.
 * Contains most of the ajax and dragging functionality.
 */
$(window).load(function(){
	$('body#projects_designer_index #Content img').each(function(){
		$(this).width($(this).width() * 0.2);

	});

});
/*-------------------------------------- Jquery functions --------------------------*/
$(document).ready(function() {
	/*----------------------------------- prepare page -----------------------------*/
	$('#NoticeBox').hide();
	var borderImg = $('.Border').attr('src');

	if (borderImg == '/uploads/item/borders/1S.png') {
		$('.Border').css('left', '90').css('top', '90');
	}
	if (borderImg == '/uploads/item/borders/2C.png') {
		$('.Border').css('left', '60').css('top', '60');
	} else if (borderImg == '/uploads/item/borders/3C.png') {
		$('.Border').css('left', '40');
	}
	/*------------------------------- Get Previously Set Positions -----------------*/
	var ElementID = $(this).attr('id');
	var frameChoice = 'none';
	var projectBackground = $('#DesignBox').attr("background");
	var currentProjectID = $('.Account').attr("projectid");
	function designHelper( src ) {
		return "<img src=\"" + src + "\" class=\"Helper\"></img>";
	}
	/*---------------------------- Define Custom functions -------------------------*/
	$.fn.doProjectPlace = function( imgID, top, left ) {
		$.post("/designer/placement", {
			//UserID: userID,
			ProjectID: currentProjectID,
			//type: itemType,
			imgID: imgID,
			top: top,
			left: left
		}, function(data) {
			var noticeBox = $('#NoticeBox');
			$(noticeBox).queue(function() {
			$(this).html("Item position sent.<br/>" + data);
			$(this).show();
			$(this).fadeTo('200', '1');
			$(this).delay('1000');
			$(this).fadeTo('1600', '0.3');
			$(this).hide('400');
			$(this).dequeue();
		});
	});
	}
	$.fn.resizeImages = function ( h, w, percent) {

	}
	$.fn.doProjectCommit = function () {
		var projectID = $(this).attr('projectid');
		var currentStage = $(this).attr('projectstage');
		var r=confirm("Are you sure you want to commit this order?");
				if (r==true) {
					$.post('/project/setstage', { Stage: parseInt(currentStage) + 1, ProjectID: projectID },
						function(data) {

						});
				} else {

				}
	}

	/*--------------------------- Define Drags and Drops --------------------------*/
	/*$("#DesignBox").droppable({
			"over": function( event, ui ) {
				$( this )
					.addClass( "ui-state-highlight" );

					},
					"drop": function() {

					},
					"out": function() {
						$(this).removeClass( "ui-state-highlight");
					}

	});
*/
	$(".Draggable").draggable({
		"containment": "parent",
		"opacity": "0.5",
		"cursor": 'move',
		"grid": [10, 10],
		"stack": ".Draggable",
		"beforeStart": function(event,ui) {
			$('img.Border').css('z-index', '4');
		},
		"stop": function(event,ui) {
			$('img.Border').css('z-index', '0');
			var pos = $(this).position();
			var imgID = $(this).attr('id');

			$(this).doProjectPlace( imgID, pos.top, pos.left );
			$('img.Helper').hide();
		}
	}).each(function() {
		var imgID = $(this).attr('id');
		var currentProjectID = $(this).attr('projectid');
		$.post('/project/getplacement', { projectID: currentProjectID, imgID: imgID },
			function(data) {
				console.log(data.top);
				console.log(data.left);
				console.log(data.imgID);
				$(this).css('top', data.top + "px");
				$(this).css('left', data.left + "px");
			}, "json");
	});
	$('.Slider').draggable({
		"containment": "parent",
		"axis": 'y',
		"cursor": "move"
	});
	$('.SubmitProjectCheck').live('click', function() {
		$(this).doProjectCommit();
		});

});

