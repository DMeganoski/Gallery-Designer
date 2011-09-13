
$(document).ready(function() {
/*--------------------------------------------------- Set up the page ---------------------------------------------*/
	//start by hiding the box
	$('.ProjectBox').hide();
	/*-------------------------------------------- define some variables for data ---------------------------------*/
	var frameChoice = 'none';
	var dragging = false;
	// User ID
	var userID = $('.Account').attr("userid");
	var transientKey = $('.Account').attr("transientkey");
	var currentProjectID = $('.Account').attr("projectid");
	var newProjectID = $('ul.Project').attr("projectid");
	var imageLocation = $('img.Individual').attr("src");
	var action = 'add';
	$('.ProjectBox img').hover(function() {
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
		$.post("/project/projectselect", { UserID: userID, ProjectID: currentProjectID, Type: type, Slug: itemID, Action: 'add' },
		function(data) {
			$('.Heading').append("<div class=\"Verify\">Item added to project.</div>" + data).updateProjectBox();
	});
	}
	$.fn.doProjectRemove = function(type, itemID, projectID) {
		$.post("/project/projectselect", { UserID: userID, ProjectID: projectID, Type: type, Slug: itemID, Action: 'remove' },
		function(data) {
			$(this).updateProjectBox();
		});
	}
	// for subitting frame choices
	$.fn.doFrameSubmit = function() {
		$.post("/project/frameselect", { UserID: userID, ProjectID: currentProjectID, Frame: frameChoice },
		function(data) {
		}
	);
	}
	// for submitting current project
	$.fn.doCurrentSubmit = function() {
		$.post("/project/setcurrent", { UserID: userID, ProjectID: newProjectID },
		function(data) {
			location.reload();
		});
	}
	// for refreshing the project box
	$.fn.updateProjectBox = function() {
		$.post("/project/getproject", { UserID: userID, TransientKey: transientKey, ProjectID: currentProjectID },
				function(data) {
			$('.ProjectBox').html(data);
		});
	}

	/*------------------------------------------- Define Events ----------------------------------------------------*/
	$('#ToggleUploads').click(function() {
		$('.ProjectBox').toggle('fast');
		$.post("/item/getuploads", { UserID: userID },
		function(data) {
			$('.ProjectBox').html(data);
		});
	});
	$('#ToggleProject').hover(function() {
		if(dragging) {
			$('.ProjectBox').show('fast')
			.updateProjectBox();
		}
	});
	$('#ToggleProject').click(function() {
		if( $('.ProjectBox').css("display") == 'none' ){
			$('.ProjectBox').show('fast')
			.updateProjectBox();
		}
		else{
			$('.ProjectBox').hide('fast');

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

	});
	$('.UploadDelete').live('click', function() {

	});
	$('.FrameRemove').live('click', function() {
		var type = $(this).attr('itemtype');
		var itemID = $(this).attr('itemslug');
		var projectID = currentProjectID;
		$(this).doProjectRemove(type, itemID, projectID );
	});
	$('.Current').click(function() {
		$(this).doCurrentSubmit();
	});
	$('.Selection').click(function() {
		$(this).doProjectSubmit(itemSlug);
	});
	$('.UploadSubmit').live('click', function() {
		var uploadID = $(this).attr("uploadid");
		$(this).doProjectSubmit("uploads", uploadID);
		if (onDesigner) {
			$.post('/designer/', {},
		function(data) {
			 var content = $( data ).find( '#Content' );
          $( "#Content" ).empty().append( content );
		});
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
	$("#ImageWrapper").draggable({ "snap": ".ProjectBox", "revert": "invalid", "opacity": "0.5", "cursor": 'move', "helper": individualHelper,
			"start": function(event,ui) {
				//$('.ProjectBox').show()
				//.updateProjectBox();
			},
			"stop": function() {
				$('img.Helper').hide();
			}
	});


});
