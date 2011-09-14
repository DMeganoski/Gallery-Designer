
$(document).ready(function() {
/*--------------------------------------------------- Set up the page ---------------------------------------------*/
	//start by hiding the box
	$('.ProjectBox').hide();
	$('.UploadBox').hide();
	/*-------------------------------------------- define some variables for data ---------------------------------*/
	var frameChoice = 'none';
	var dragging = false;
	// Scan page to aquire php variables
	var userID = $('.Account').attr("userid");
	var transientKey = $('.Account').attr("transientkey");
	var currentProjectID = $('.Account').attr("projectid");
	var imageLocation = $('img.Single').attr("src");
	// action variable for adding or removing items from the project.
	// not currently implemented
	var action = 'add';
	// attempt at creating a button to remove images
	$('img.Upload').hover(function() {
		$(this).append('<div class="Close">X</div>');
	});
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
	// for submtting items from the gallery in the project
	$.fn.doProjectSubmit = function( type, itemID ) {
		$.post("/project/projectselect", {UserID: userID, ProjectID: currentProjectID, Type: type, Slug: itemID, Action: 'add'},
		function(data) {
			$('.Heading').append("<div class=\"Verify\">Item added to project.</div>" + data).updateProjectBox();
	});
	}
	$.fn.doProjectRemove = function(type, itemID, projectID) {
		$.post("/project/projectselect", {UserID: userID, ProjectID: projectID, Type: type, Slug: itemID, Action: 'remove'},
		function(data) {
			$(this).updateProjectBox();
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
	$.fn.updateProjectBox = function() {
		$.post("/project/getproject", {UserID: userID, TransientKey: transientKey, ProjectID: currentProjectID},
				function(data) {
			$('.ProjectBox').html(data);
		});
	}
	$.fn.updateUploadBox = function() {
		$.post("/item/getuploads", {UserID: userID},
				function(data) {
					$('.UploadBox').html(data);
				});
	}
	/*------------------------------------------- Define Events ----------------------------------------------------*/
	$('#ToggleUploads').live('click', function() {
		if ( $('.UploadBox').css("display") == 'none' ){
			$(this).updateUploadBox();
			$('.UploadBox').slideDown('fast');
		} else {
			$('.UploadBox').slideUp('fast');
		}

	});
	// hover function for dragging onto closed box
	$('#ToggleProject').hover(function() {
		if(dragging) {
			$(this).updateProjectBox();
			$('.ProjectBox').slideDown('fast');

		}
	});
	// click function for toggling display of the project box
	$('#ToggleProject').click(function() {
		if ( $('.ProjectBox').css("display") == 'none' ){
			$(this).updateProjectBox();
			$('.ProjectBox').slideDown('fast');

		} else {
			$('.ProjectBox').slideUp('fast');
		}
	});
	$('.TinRemove').live('click', function() {
		var type = $(this).attr('itemtype');
		var itemID = $(this).attr('itemslug');
		var currentProjectID = $('.Account').attr("projectid");
		var projectID = currentProjectID;
		$(this).doProjectRemove(type, itemID, projectID );
	});
	$('.CoverRemove').live('click', function() {
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
	$('.UploadDelete').live('click', function() {
		var itemID = $(this).attr('uploadid');
		$(this).doUploadDelete(itemID);
	});
	$('.FrameRemove').live('click', function() {
		var type = $(this).attr('itemtype');
		var itemID = $(this).attr('itemslug');
		var projectID = currentProjectID;
		$(this).doProjectRemove(type, itemID, projectID );
	});
	$('.ProjectCurrent').click(function() {
		$(this).doCurrentSubmit();
	});
	$('.ProjectDelete').click(function() {
		var projectID = $(this).attr('projectid');
		$(this).doProjectDelete(projectID);
	})
	$('.Selection').click(function() {
		$(this).doProjectSubmit(itemSlug);
	});
	$('.UploadSubmit').live('click', function() {
		var uploadID = $(this).attr("uploadid");
		$(this).doProjectSubmit("uploads", uploadID);
		if (onDesigner) {
			window.location = "/designer";
		}
	});
	$('li#Click').click(function() {
		$('#FrameWrapper').hide('300');
		$('#FrameWrapper').removeClass();
		$(this).removeClass("Active");
		$(this).toggleClass("Active");
		frameChoice = $(this).attr("type");
		$('#FrameWrapper').addClass(frameChoice + "Frame");
	});
	/*----------------------------------------- Define Objects ------------------------------------------------------*/
	$(".ProjectBox").droppable({
			"over": function( event, ui ) {
				$( this )
					.addClass( "ui-state-highlight" );

					},
					"drop": function() {
						var itemType = $('.DetailsWrapper').attr("itemtype");
						var itemSlug = $('.DetailsWrapper').attr("itemslug");
						$(this).doProjectSubmit( itemType, itemSlug );
							$(this).doFrameSubmit();
						$(this).removeClass( "ui-state-highlight" );
					},
					"out": function() {
						$(this).removeClass( "ui-state-highlight" );
					}

			});
	$("#ImageWrapper").draggable({"snap": ".ProjectBox", "revert": "invalid", "opacity": "0.5", "cursor": 'move', "helper": individualHelper,
			"start": function(event,ui) {
				dragging = true;
				$('img.Border').css('z-index', '200');
				//$('.ProjectBox').show()
				//.updateProjectBox();
			},
			"stop": function() {
				$('img.Border').css('z-index', '0');
				$('img.Helper').hide();
				dragging = false;
			}
	});


});
