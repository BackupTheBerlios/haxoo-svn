window.addEvent('domready', function() {
	var status = {
		'true': 'open',
		'false': 'close'
	};	
	//-vertical
	var myVerticalSlide = new Fx.Slide('bottom-slide');
	$('v_slidein').addEvent('click', function(e){
		e.stop();
		myVerticalSlide.slideIn();
	});
	$('v_slideout').addEvent('click', function(e){
		e.stop();
		myVerticalSlide.slideOut();
	});
	//automagic hide	
	myVerticalSlide.hide();	
});