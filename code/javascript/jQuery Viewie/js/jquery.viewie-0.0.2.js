/**
 * "You analyzed the wrong viewie, all right. The wrong one."
 *     -Judge Hershey, from the movie Judge Dredd
 * 
 * jQuery Viewie plugin, v.0.0.2
 * written by Akoi Meexx.
 * 
 * This plugin creates a viewport from any container element you specify, 
 * configuring each direct child of that container element to be a 
 * viewport slide.
 * This functionality can be seen in many popup modal lightboxes, but I 
 * have had little luck in finding a plugin that let me specify my own 
 * viewports for more direct control.
 * This plugin strives to avoid using external files or graphics for its 
 * styling. Please review this plugin's code comments for additional 
 * details on how to style a Viewie.
 */
/**
 * TODO: 
 * Build navigation function based on per-instance viewie
 * 	-- Tie in keyboard bindings __CORRECTLY__.
 */

(function($){
	var generatedElements = {
		navigation: {
			left: '<a class="viewie-nav-button viewie-nav-left"></a>', 
			right: '<a class="viewie-nav-button viewie-nav-right"></a>', 
		}, 
	};
	
	$.fn.viewie = function(options) {
		var defaults = {
			arrows: {
				left: {
					glyph: "&lsaquo;", 
					position: {
						left: "4px", 
						top: "50%", 
					}, 
				}, 
				right: {
					glyph: "&rsaquo;", 
					position: {
						right: "4px", 
						top: "50%", 
					}, 
				},
				style: {
					backgroundColor: "rgba(255, 255, 255, 0.5)", 
					borderRadius: "12px", 
					color: "#404040", 
					height: "8px", 
					hoverColor: "rgba(255, 255, 255, 0.8)", 
					fontFamily: "Arial, sans-serif", 
					fontSize: "32px", 
					fontWeight: "bold", 
					lineHeight: "0.0em", 
					margin: "0px", 
					padding: "8px 7px", 
					width: "10px", 
					zIndex: "9999", 
				}, 
			}, 
			
			// TODO: Set up auto-cycling. Ties in with navigation
			autoCycle: false, 
			autoCycleWaitTime: 5, /* Wait time between switching to the next element, in seconds */
			
			enableKeyboardNav: true, 
			enableMouseScrollNav: true, 
			
			recycleElements: true, 
			
			// TODO: Set up scaling internal contents.
			scaleContents: false, 
			showArrows: true, 
		};
		var options = $.extend(defaults, options);
		
		var containerStylePresets = {
			display: "block", 
			overflow: "hidden", 
			position: "relative", 
			textAlign: "center", 
		};
		var navigationStylePresets = {
			cursor: "pointer", 
			display: "block", 
			overflow: "hidden", 
			position: "absolute", 
			textDecoration: "none", 
		};
		
		return this.each(function() {
			/**
			 * Add the jquery-viewie class to our container, then style with our bare-minimum
			 * amount of css to make Viewie work.
			 */
			$(this).addClass("jquery-viewie");
			$(this).css(containerStylePresets);
			
			/**
			 * Build navigation -- This is an unsightly, ungodly mess.
			 */
			$(this).attr('data-currentIndex', 2);
			$(this).children().each(function(){
				$(this).hide();
			});
			$(this).children(':first-child').each(function(){
				$(this).show();
			});
			
			/**
			 * Set up mousewheel support if the plugin is available and the option is enabled
			 */
			if($.fn.mousewheel && options.enableMouseScrollNav) {
				$(this).mousewheel(function(event, delta) {
					if ($(event.target).get(0).clientHeight == 0 || $(event.target).get(0).scrollHeight === $(event.target).get(0).clientHeight) {
						event.preventDefault();
						if(delta > 0) {
							$(this).children('.viewie-nav-left').click();
						} else {
							$(this).children('.viewie-nav-right').click();
						}
					}
				});
			}
			/**
			 * Set up Keyboard navigation if the option is selected
			 */
			if(options.enableKeyboardNav) {
				$(document).bind('keydown.fb', function(event) {
					if((event.keyCode == 37  || event.keyCode == 39) && event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA' && event.target.tagName !== 'SELECT') {
						event.preventDefault();
						if(event.keyCode == 37) {
							$('.jquery-viewie').children('.viewie-nav-left').click();
						} else {
							$('.jquery-viewie').children('.viewie-nav-right').click();
						}
					}
				});
			}
			/**
			 * Build the navigation arrows.
			 */
			$(this).prepend(generatedElements.navigation.left);
			$(this).append(generatedElements.navigation.right);
			var navStyles = $.extend({}, navigationStylePresets, options.arrows.style);
			// Style left navigation
			var navLeftStyle = $.extend({}, navStyles, options.arrows.left.position);
			$(this).children(".viewie-nav-left").each(function(){
				$(this).click(function(){
					/**
					 * Epic navigation-y magicks happen here. God have mercy on my soul for this code...
					 */
					var viewieInstance = $(this).parent();
					if($(this).is(':visible')) {
						viewieInstance.children('.viewie-nav-button').hide();
						if(viewieInstance.attr('data-currentIndex') > 2) {
							viewieInstance.children(':not(.viewie-nav-button):visible').each(function(){
								$(this).hide("slide", { direction: "right" }, 1000);
							});
							viewieInstance.attr('data-currentIndex', (viewieInstance.attr('data-currentIndex') - 1));
							viewieInstance.children(':nth-child(' + viewieInstance.attr('data-currentIndex') + ')').delay(1000).show("slide", { direction: "left" }, 1000);
						} else if(viewieInstance.attr('data-currentIndex') <= 2 && options.recycleElements == true) {
							viewieInstance.children(':not(.viewie-nav-button):visible').each(function(){
								$(this).hide("slide", { direction: "right" }, 1000);
							});
							viewieInstance.attr('data-currentIndex', viewieInstance.children().length - 1);
							viewieInstance.children(':nth-child(' + viewieInstance.attr('data-currentIndex') + ')').delay(1000).show("slide", { direction: "left" }, 1000);
						}
						setTimeout(function(){
							viewieInstance.children('.viewie-nav-button').show();
						}, 
						2000);
					}
				});
				$(this).html(options.arrows.left.glyph);
				$(this).css(navLeftStyle);
				$(this).css("top", ($(this).position().top - ($(this).innerHeight() / 2)));
				$(this).hover(
					function(){
						$(this).css('backgroundColor', options.arrows.style.hoverColor);
					}, 
					function(){
						$(this).css('backgroundColor', options.arrows.style.backgroundColor);
					});
				if(!options.showArrows) {
					$(this).hide();
				}
			});
			// Style right navigation
			var navRightStyle = $.extend({}, navStyles, options.arrows.right.position);
			$(this).children(".viewie-nav-right").each(function(){
				$(this).click(function(){
					/**
					 * Epic navigation-y magicks happen here. God have mercy on my soul for this code...
					 */
					var viewieInstance = $(this).parent();
					if($(this).is(':visible')) {
						viewieInstance.children('.viewie-nav-button').hide();
						if(viewieInstance.attr('data-currentIndex') < viewieInstance.children().length - 1) {
							viewieInstance.children(':not(.viewie-nav-button):visible').each(function(){
								$(this).hide("slide", { direction: "left" }, 1000);
							});
							viewieInstance.attr('data-currentIndex', parseInt(viewieInstance.attr('data-currentIndex')) + 1);
							viewieInstance.children(':nth-child(' + viewieInstance.attr('data-currentIndex') + ')').delay(1000).show("slide", { direction: "right" }, 1000);
						} else if(viewieInstance.attr('data-currentIndex') >= (viewieInstance.children().length - 1) && options.recycleElements == true) {
							viewieInstance.children(':not(.viewie-nav-button):visible').each(function(){
								$(this).hide("slide", { direction: "left" }, 1000);
							});
							viewieInstance.attr('data-currentIndex', 2);
							viewieInstance.children(':nth-child(' + viewieInstance.attr('data-currentIndex') + ')').delay(1000).show("slide", { direction: "right" }, 1000);
						}
						setTimeout(function(){
							viewieInstance.children('.viewie-nav-button').show();
						}, 
						2000);
					}
				});
				$(this).html(options.arrows.right.glyph);
				$(this).css(navRightStyle);
				$(this).css("top", ($(this).position().top - ($(this).innerHeight() / 2)));
				$(this).hover(
					function(){
						$(this).css('backgroundColor', options.arrows.style.hoverColor);
					}, 
					function(){
						$(this).css('backgroundColor', options.arrows.style.backgroundColor);
					});
				if(!options.showArrows) {
					$(this).hide();
				}
			});			
		});
	};
})(jQuery);
