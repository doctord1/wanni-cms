$(document).ready(function () {
		 
		//alert('Script loaded, You may now continue');	
		
		//new users  carousel
	$('.gallery-carousel').slick({
		autoplay: true,
		autoplaySpeed: 3000,
		accessibility: false,
		centerMode: false,
		dots: false,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
		mobileFirst: false
		});
		
		//new users  carousel
	$('.new-users-carousel').slick({
		autoplay: true,
		autoplaySpeed: 5000,
		accessibility: false,
		centerMode: false,
		dots: false,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
		mobileFirst: false
		});
		
		//ads  carousel
	$('.ads-carousel').slick({
		autoplay: true,
		autoplaySpeed: 7000,
		accessibility: false,
		centerMode: false,
		dots: false,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
		mobileFirst: false
		});
		
	// Slide out panels	
	$('#toggle-sidebar').click(function(){
		$('#sidebar').toggleClass('hidden');
		$('body').toggleClass('content-pushed');
		
		});
	//$('#sidebar a').click(function(){
	//	$('#sidebar').toggleClass('hidden');
	//	$('body').toggleClass('content-pushed');
	//	});
	$('#close-sidebar').click(function(){
		$('#sidebar').addClass('hidden');
		$('body').removeClass('content-pushed');
		});
	$('.cd-panel').on('click', function(event){
		if( $(event.target).is('.cd-panel') || $(event.target).is('.cd-panel-close') ) { 
			$('.cd-panel').removeClass('is-visible');
			event.preventDefault();
			$('.slider-wrapper.theme-default').show();
			$('#sidebar').addClass('hidden');
			$('body').removeClass('content-pushed');
		}
	});
	

		// dropit dropdown menus
		
		$('.menu').dropit({
		action: 'click', // The open action for the trigger
		submenuEl: 'div', // The submenu element
		triggerEl: 'a', // The trigger element
		triggerParentEl: 'span', // The trigger parent element
		afterLoad: function(){}, // Triggers when plugin has loaded
		beforeShow: function(){}, // Triggers before submenu is shown
		afterShow: function(){}, // Triggers after submenu is shown
		beforeHide: function(){}, // Triggers before submenu is hidden
		afterHide: function(){} // Triggers before submenu is hidden
		});	// end dropit menus
		
	var user_url = BasePath + "user/?user=" + SessionUser;
	$( ".login-popout" ).load( BasePath + "user/index.php form");
	$( ".inbox-popout" ).load( BasePath + "addons/messaging/ .popout");
	//$( ".cd-panel-content" ).load( user_url + " .main-content-region");
		
		
			//position bootstrap dropdown
		$('.top_menu_items').click(function(e){
			var leftX = event.pageX;
			  var offset = $( this ).offset();
			var position = {
				"position" : "absolute",
				"left" : offset.left - 30
				}
			$('.dropdown-menu').css(position);
		});	
	
	if ($('.center').html() ==''){
		$(".center").hide();
		}
		
	
	if ($('.payment-button').html() =='none'){
		$(".payment-button").hide();
		}
		
	if ($('.right-sidebar-region').html() ==''){
		$(".main-content-region").css('width', '97%');

		$('.right-sidebar-region').hide();
		}
	if ($('.top-right-sidebar-region').html() ==''){
		$('.top-right-sidebar-region').hide();
		}
	$('.sweet_title #toggle-collapse').click(function(){
		if($(this).html() == '[+]'){
			$(this).html() = '[-]';
			}
		else if($(this).html() == '[-]'){
			$(this).html() = '[+]';
			}
		});
		
	// Add pictures to post -start
	$('#pic-toggle .content').hide();
	$('#pic-close').hide();
	$('#add-picture').click(function(){
			$(this).parent().find('.content').show("slow");
			$('#pic-close').slideToggle();
			$('#add-picture').hide('slow');
	});
	$('#pic-close').click(function(){
			$(this).parent().find('.content').hide("slow");
			$('#pic-close').slideToggle();
			$('#add-picture').show('slow');
	});

	$('fieldset p').click(function(){
			$(this).toggle("slow");
			$(this).parent().find('.content').slideToggle("slow");
			//$('legend').html("Click to close ");
		});
		
	//Add pictures - end
	
	
	//categorize start
	$('.categorize-holder').hide();
	$('.categorize-close').hide();
	
	$('.categorize-pullout').click(function(){
			//$(this).parent().find('.content').show("slow");
			$('.categorize-holder').slideToggle();
			$('.categorize-close').slideToggle();
			$('.categorize-pullout').hide('slow');
	});
	$('.categorize-close').click(function(){
			//$(this).parent().find('.content').show("slow");
			$('.categorize-holder').slideToggle();
			$('.categorize-close').slideToggle();
			$('.categorize-pullout').show('slow');
	});
	
	// Search region hide start
	$('.search-region').hide();
	$('#search-toggle').click(function(){
		$('.search-region').slideToggle().toggleClass('.opened');
		
		if($('.search-region').hasClass('.opened')){	
			$('#search-toggle').text('Close search');
		} else {
			$('#search-toggle').text('Search');
			}
		
		});
		
	//togle interswitch pay for fundraisers
	$('.interswitch-pay').hide();
	$('.site-funds-pay').hide();
	$('.toggle-interswitch').click(function(){
			$('.interswitch-pay').show('slow');
			$('.site-funds-pay').hide('slow');

	});
	$('.toggle-funds').click(function(){
			$('.site-funds-pay').show('slow');
			$('.interswitch-pay').hide('slow');
	});
		
	//push tamer
	$('.pushcrew-modal-branding').hide();
	
	//blinker
	function blink(){
			$('#blink').fadeIn(750).fadeOut(750);
			blinkVar = setTimeout(blink, 2000);
		}
	blink();
	
	
	jQuery(".timeago").timeago();	
	
	//$('textarea').val().replace("\n", "<br />", "g");
	//$('textarea').on('keyup', function() {
   // $('textarea#comment').val($('textarea').val().replace("\n", "<br />", "g"));
	//})
	
	//fire tooltips
	 //$("[data-toggle='tooltip']").tooltip(); 
	
});

$('.bxslider').bxSlider({
	video: true,
	adaptiveHeight:true,
	touchEnabled: true,
	mode: 'fade'
});

 $(window).load(function() {
        $('#slider').nivoSlider();
		controlNav: true;
		});
		
$(document).on(function(){
	//position bootstrap dropdown
	$('.top_menu_items').click(function(e){
		var leftX = event.pageX;
		console.log(leftX);
		var position = {
			"position" : "relative",
			"left" : leftX
			}
	});
});
	
