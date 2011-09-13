/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){

				//create the loader
				var loader = $("<div></div>", {
						id: "loader"
					}).css("display", "none"),
					bar = $("<span></span>").css("opacity", 0.2),
					loadingInterval = null;

				//create bars
				for (var x = 0; x < 3; x++) {
					bar.clone().addClass("bar-" + x).appendTo(loader);
				}

				//add to page
				loader.insertAfter(".Load");

				//animate
				function runLoader() {
					var firstBar = loader.children(":first"),
						secondBar = loader.children().eq(1),
						thirdBar = loader.children(":last");

					firstBar.fadeTo("fast", 1, function(){
						firstBar.fadeTo("fast", 0.2, function() {
							secondBar.fadeTo("fast", 1, function() {
								secondBar.fadeTo("fast", 0.2, function() {
									thirdBar.fadeTo("fast", 1, function() {
										thirdBar.fadeTo("fast", 0.2);
									});
								});
							});
						});
					});
				};

				$(".Load").toggle(function() {
					loader.show();
					loadingInterval = setInterval(function() { runLoader(); }, 1200);
				}, function() {
					loader.hide();
					clearInterval(loadingInterval);
				});

			});
