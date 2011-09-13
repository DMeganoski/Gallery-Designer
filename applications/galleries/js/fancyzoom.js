(function($){
$.fn.fancyZoom = function(options){

  var options   = options || {};
  var directory = options && options.directory ? options.directory : 'images';
  var zooming   = false;

  if ($('#zoom').length == 0) {
    var ext = $.browser.msie ? 'gif' : 'png';
    var html = '<div id="zoom" style="display:none;"> \
                  <table id="zoom_table"> \
                    <tbody> \
                      <tr> \
                        <td class="tl" /> \
                        <td class="tm" /> \
                        <td class="tr" /> \
                      </tr> \
                      <tr> \
                        <td class="ml" /> \
                        <td class="mm"> \
                          <div id="zoom_content"> \
                          </div> \
                        </td> \
                        <td class="mr" /> \
                      </tr> \
                      <tr> \
                        <td class="bl" /> \
                        <td class="bm" /> \
                        <td class="br" /> \
                      </tr> \
                    </tbody> \
                  </table> \
                  <a href="#" title="Close" id="zoom_close"> \
                    <div><span>Close</span></div> \
                  </a> \
                </div>';

    $('body').append(html);

    $('html').click(function(e){if($(e.target).parents('#zoom:visible').length == 0) hide();});
    $(document).keyup(function(event){
        if (event.keyCode == 27 && $('#zoom:visible').length > 0) hide();
    });

    $('#zoom_close').click(hide);
  }

  var zoom          = $('#zoom');
  var zoom_table    = $('#zoom_table');
  var zoom_close    = $('#zoom_close');
  var zoom_content  = $('#zoom_content');
  var middle_row    = $('td.ml,td.mm,td.mr');

  this.each(function(i) {
    $($(this).attr('href')).hide();
    $(this).click(show);
  });

  return this;

  function show(e) {
    if (zooming) return false;
		zooming         = true;
		var content_div = $($(this).attr('href'));
  	var zoom_width  = options.width;
		var zoom_height = options.height;

		var width       = window.innerWidth || (window.document.documentElement.clientWidth || window.document.body.clientWidth);
  	var height      = window.innerHeight || (window.document.documentElement.clientHeight || window.document.body.clientHeight);
  	var x           = window.pageXOffset || (window.document.documentElement.scrollLeft || window.document.body.scrollLeft);
  	var y           = window.pageYOffset || (window.document.documentElement.scrollTop || window.document.body.scrollTop);
  	var window_size = {'width':width, 'height':height, 'x':x, 'y':y}

		var width              = (zoom_width || content_div.width()) + 60;
		var height             = (zoom_height || content_div.height()) + 60;
		var d                  = window_size;

		// ensure that newTop is at least 0 so it doesn't hide close button
		var newTop             = Math.max((d.height/2) - (height/2) + y, 0);
		var newLeft            = (d.width/2) - (width/2);
		var curTop             = e.pageY;
		var curLeft            = e.pageX;

		zoom_close.attr('curTop', curTop);
		zoom_close.attr('curLeft', curLeft);
		zoom_close.attr('scaleImg', options.scaleImg ? 'true' : 'false');

    $('#zoom').hide().css({
			position	: 'absolute',
			top				: curTop + 'px',
			left			: curLeft + 'px',
			width     : '1px',
			height    : '1px'
		});

    fixBackgroundsForIE();
    zoom_close.hide();

    if (options.closeOnClick) {
      $('#zoom').click(hide);
    }

		if (options.scaleImg) {
  		zoom_content.html(content_div.html());
  		$('#zoom_content img').css('width', '100%');
		} else {
		  zoom_content.html('');
		}

    $('#zoom').animate({
      top     : newTop + 'px',
      left    : newLeft + 'px',
      opacity : "show",
      width   : width,
      height  : height
    }, 500, null, function() {
      if (options.scaleImg != true) {
    		zoom_content.html(content_div.html());
  		}
			unfixBackgroundsForIE();
			zoom_close.show();
			zooming = false;
    })
    return false;
  }

  function hide() {
    if (zooming) return false;
		zooming         = true;
	  $('#zoom').unbind('click');
		fixBackgroundsForIE();
		if (zoom_close.attr('scaleImg') != 'true') {
  		zoom_content.html('');
		}
		zoom_close.hide();
		$('#zoom').animate({
      top     : zoom_close.attr('curTop') + 'px',
      left    : zoom_close.attr('curLeft') + 'px',
      opacity : "hide",
      width   : '1px',
      height  : '1px'
    }, 500, null, function() {
      if (zoom_close.attr('scaleImg') == 'true') {
    		zoom_content.html('');
  		}
      unfixBackgroundsForIE();
			zooming = false;
    });
    return false;
  }

  function switchBackgroundImagesTo(to) {
    $('#zoom_table td').each(function(i) {
      var bg = $(this).css('background-image').replace(/\.(png|gif|none)\"\)$/, '.' + to + '")');
      $(this).css('background-image', bg);
    });
    var close_img = zoom_close.children('img');
    var new_img = close_img.attr('src').replace(/\.(png|gif|none)$/, '.' + to);
    close_img.attr('src', new_img);
  }

  function fixBackgroundsForIE() {
    if ($.browser.msie && parseFloat($.browser.version) >= 7) {
      switchBackgroundImagesTo('gif');
    }
	}

  function unfixBackgroundsForIE() {
    if ($.browser.msie && $.browser.version >= 7) {
      switchBackgroundImagesTo('png');
    }
	}
}
})(jQuery);