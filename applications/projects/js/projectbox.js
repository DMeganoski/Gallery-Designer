/**
 * @todo add functions for verification before removal
 */
$(document).ready(function() {
/*--------------------------------------------------- Set up the page ---------------------------------------------*/
	//start by hiding the box
	$('.ProjectBox').hide();
	$('.UploadBox').hide();
	$('.ButtonBox').hide();
	$('ul.TextList').hide();
	/*-------------------------------------------- define some variables for data ---------------------------------*/
	// Selected frame for background
	var frameChoice = "None";
	// variable for determining whether or not to expand the project box on hover
	var dragging = false;
	var accountBox = $('.Account');
	// Username, taken from account box on every page
	var userID = $('.Account').attr("userid");
	// transient key, taken from account box on every page
	var transientKey = $('.Account').attr("transientkey");
	// currently open project, taken from account box on every page
	var currentProjectID = $('.Account').attr("projectid");
	// image location for helper, on the design page
	var imageLocation = $('img.Single').attr("src");
	// gallery browse page item id
	var browseID = $('.Gallery').attr("itemid");
	// gallery browse page item id
	var browsetype = $('.Gallery').attr("type");
	// action variable for adding or removing items from the project.
	// not currently implemented
	var action = 'add';
	// attempt at creating a button to remove images
	$('img.Upload').hover(function() {
		$(this).append('<div class="Close">X</div>');
	});
	// function for getting the url of the page. Designer page gets special treatment
	var url = location.href;
	var onDesigner = false;
	$('#DesignBox').append(onDesigner);
	if (url == "http://tinsdirect/designer") {
		onDesigner = true;
	} else {
		onDesigner = false;
	}
	/*----------------------------------------------- Custom Functions --------------------------------------------*/
	// helper for draggable items
	function individualHelper( event ) {
		return "<div class=\"DetailsWrapper Dragging\"><div id=\"FrameWrapper\" class=\"" + frameChoice + "Frame\"></div><img src=\"" + imageLocation + "\" class=\"Helper Individual\"></img></div>";
	}
	function browseHelper( src ) {
		var imageLocation = $('img.Gallery').attr("src");
		return "<img src=\"" + imageLocation + "\" class=\"Helper\"></img>";
	}
	// for submtting items from the gallery in the project
	$.fn.doProjectSubmit = function( type, itemID ) {
		$.post("/project/projectselect", {UserID: userID, ProjectID: currentProjectID, Type: type, Slug: itemID, Action: 'add'},
		function(data) {
			var verifyBox = $('.Verify');
			$(verifyBox).queue(function() {
				$(this).html("Item Added to Project.<br/>" + data);
				$(this).show();
				$(this).updateProjectBox( type );
				$(this).fadeTo('200', '1');
				$(this).delay('1000');
				$(this).fadeTo('1600', '0.3');
				$(this).hide('slow');
				$(this).dequeue();
	});
	}
	)};
	// for removing items from the project
	$.fn.doProjectRemove = function(type, itemID, projectID) {
		$.post("/project/projectselect", {UserID: userID, ProjectID: projectID, Type: type, Slug: itemID, Action: 'remove'},
		function(data) {
			$(this).updateProjectBox( type );
		});
	}
	// for deleting uploaded images
	$.fn.doUploadDelete = function(itemID) {
		$.post("/item/uploaddelete", { ItemID: itemID, UserID: userID , TransientKey: transientKey},
		function(data) {
			$('.UploadNotify').html(data)
			.updateUploadBox();
		});
	}
	// for deleting projects entirely
	$.fn.doProjectDelete = function(projectID) {
		$.post('/project/delete', { ProjectID: projectID, UserID: userID, TransientKey: transientKey },
			function(data) {
				location.reload();
			});
	}
	// for subitting frame choices
	$.fn.doFrameSubmit = function() {
		$.post("/project/frameselect", {UserID: userID, ProjectID: currentProjectID, Frame: frameChoice},
		function(data) {
		}
	);
	}
	// for submitting current project
	$.fn.doCurrentSubmit = function() {
		var newProjectID = $(this).attr("projectid");
		$.post("/project/setcurrent", {UserID: userID, ProjectID: newProjectID},
		function(data) {
			location.reload();
		});
	}
	// for refreshing the project box
	$.fn.updateProjectBox = function(type) {
		$.post("/project/getproject", {UserID: userID, TransientKey: transientKey, ProjectID: currentProjectID, Type: type },
			function(data) {
				var projectBox = $('.ProjectBox');
				$(projectBox).slideUp().queue(function(n) {
					$(this).html(data).slideDown();
					n();
				});
		});
	}
	$.fn.updateUploadBox = function() {
		$.post("/item/getuploads", {UserID: userID},
				function(data) {
					$('.UploadBox').html(data);
				});
	}
	/*------------------------------------------- Define Events ----------------------------------------------------*/
	/*------------------------------------------- Used in Project Box --------------------------------------------------*/

	// click function for toggling display of the project box
	$('#ToggleProject').live('click', function() {
		if ( $('.ButtonBox').css("display") == 'none' ){
			$('.ButtonBox').slideDown();
		} else {
			$('.ButtonBox').slideUp();
			if ( $('.ProjectBox').css("display") == 'block' ){
				$('.ProjectBox').slideUp();
			}
		}
	});
	// hover function for dragging onto closed box
	$('#ToggleProject').hover(function() {
		if(dragging) {
			$('.ButtonBox').slideDown();
			$(this).updateProjectBox();
		}
	});
	$('.TabBox').live('click', function() {
		var type = $(this).attr('id');
		$(this).updateProjectBox(type);
	});
/*----------------------------------- Remove Functions for Project Box ----------------------------------------------*/
	// button to remove tin from project
	$('.TinRemove').live('click', function() {
		var type = $(this).attr('itemtype');
		var itemID = $(this).attr('itemslug');
		var currentProjectID = $('.Account').attr("projectid");
		var projectID = currentProjectID;
		$(this).doProjectRemove(type, itemID, projectID );
	});
	// button to remove background from project
	$('.BackgroundRemove').live('click', function() {
		var type = $(this).attr('itemtype');
		var itemID = $(this).attr('itemslug');
		var projectID = currentProjectID;
		$(this).doProjectRemove(type, itemID, projectID );
	});
	$('.UploadRemove').live('click', function() {
		var itemID = $(this).attr('id');
		var projectID = currentProjectID;
		$(this).doProjectRemove('uploads', itemID, projectID);
	});

	$('.FrameRemove').live('click', function() {
		var type = $(this).attr('itemtype');
		var itemID = $(this).attr('itemslug');
		var projectID = currentProjectID;
		$(this).doProjectRemove(type, itemID, projectID );
	});
/*------------------------------------- All Projects Page -----------------------------------------------------------*/
	$('.ProjectCurrent').click(function() {
		$(this).doCurrentSubmit();
	});
	$('.ProjectDelete').click(function() {
		var projectID = $(this).attr('projectid');
		$(this).doProjectDelete(projectID);
	});
	$('.Selection').click(function() {
		$(this).doProjectSubmit(itemSlug);
	});
/*---------------------------------------- Used in Upload Box -------------------------------------------------------*/
	// Button for toggling upload box display
	$('#ToggleUploads').live('click', function() {
		if ( $('.UploadBox').css("display") == 'none' ){
			$(this).updateUploadBox();
			$('.UploadBox').slideDown('fast');
		} else {
			$('.UploadBox').slideUp('fast');
		}

	});
	// Submit button in upload box to add upload to project
	$('.UploadSubmit').live('click', function() {
		var uploadID = $(this).attr("uploadid");
		$(this).doProjectSubmit("uploads", uploadID);
		if (onDesigner) {
			window.location = "/designer";
		}
	});
	// Button for deleting uploaded items
	$('.UploadDelete').live('click', function() {
		var itemID = $(this).attr('uploadid');
		$(this).doUploadDelete(itemID);
	});
/*------------------------------------------- Used in ItemController ------------------------------------------------*/
	// Frame choice helper for individual item page
	$('li#Click').click(function() {
		$('#FrameWrapper').hide('300');
		$('#FrameWrapper').removeClass();
		$(this).removeClass("Active");
		$(this).toggleClass("Active");
		frameChoice = $(this).attr("type");
		$('#FrameWrapper').addClass(frameChoice + "Frame");
	});
	/*----------------------------------------- Define Objects ------------------------------------------------------*/
	$(".Project").droppable({
			"over": function( event, ui ) {
				$( this )
					.addClass( "ui-state-highlight" );

					},
					"drop": function() {
						var itemType = $('.DetailsWrapper').attr("itemtype");
						var itemSlug = $('.DetailsWrapper').attr("itemslug");
						$(this).doFrameSubmit();
						$(this).doProjectSubmit( itemType, itemSlug );
						//$(this).doFrameSubmit();
						$(this).removeClass( "ui-state-highlight" );
					},
					"out": function() {
						$(this).removeClass( "ui-state-highlight" );
					}

			});
	$("#ImageWrapper").draggable({"snap": ".ProjectBox", "revert": "invalid", "opacity": "0.5", "cursor": 'move', "helper": individualHelper,
			"start": function(event,ui) {
				dragging = true;
				//$('.ProjectBox').show()
				//.updateProjectBox();
			},
			"stop": function() {
				$('img.Helper').hide();
				dragging = false;
			}
	});
	var src = $('img.Gallery').attr("src");
	$(".Gallery").draggable({"snap": ".ProjectBox", "revert": "invalid", "opacity": "0.5", "cursor": 'move', "helper": browseHelper(src),
			"start": function(event,ui) {
				dragging = true;
				//$('.ProjectBox').show()
				//.updateProjectBox();
			},
			"stop": function() {
				dragging = false;
				$('img.Helper').hide();

			}
	});


});
