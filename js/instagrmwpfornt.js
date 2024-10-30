<!-- Cross plateform other width screen check scrollHeight width height of widthscreen devices -->
jQuery( function() {
	   var wscreen = jQuery(document).width();
	   var wart = jQuery('#imgins').width();
	   var chwth = 100/wart ;
	   if(wscreen < 480){
		   var mls = ((wart-175)/2)*chwth;
		   var inswpms = mls-0.1;
		   jQuery('.smallinswp').css('margin','12px '+inswpms.toFixed(1)+'%');
		   var mlm = ((wart-220)/2)*chwth; 
		   var inswpmm =  mlm-0.1;
		   jQuery('.middleinswp').css('margin','12px '+inswpmm.toFixed(1)+'%');
		   jQuery('.largeinswp').css('margin','25px 0');
		}else if(wscreen > 480 && wscreen <= 800 ){
		   var mls = (((wart-(175*2))/4) - 1)*chwth;
		   var inswpms = mls-0.1;
		   jQuery('.smallinswp').css('margin','12px '+inswpms.toFixed(1)+'%');
		   var mlm = (((wart-(220*2))/4) - 1)*chwth; 
		   var inswpmm =  mlm-0.1;  
		   jQuery('.middleinswp').css('margin','12px '+inswpmm.toFixed(1)+'%'); 
		   jQuery('.largeinswp').css('margin','25px 0'); 
	   }else if(wscreen > 800){
		   var mls = (((wart-(175*3))/6) - 1)*chwth;
		   var inswpms = mls-0.1; 
		   jQuery('.smallinswp').css('margin','12px '+inswpms.toFixed(1)+'%');
		   var mlm = (((wart-(220*2))/4) - 1)*chwth;
		   var inswpmm =  mlm-0.1;
		   jQuery('.middleinswp').css('margin','15px '+inswpmm.toFixed(1)+'%');
		   jQuery('.largeinswp').css('margin','25px 0');
	   }
});
<!-- Zoom image check scrollHeight width height of widthscreen devices -->		
function inswp_zoom(idd,aid){
			jQuery("#inswbox").fadeIn(1000);
			jQuery("#inswbox").on( "click",function() {
			      inswp_close_zoom(idd+aid);
            });
			var maxY = document.body.scrollHeight;
			var widthscreen = jQuery(document).width();
			var heightscreen = jQuery(document).height();
            var topscb = jQuery(document).scrollTop();
			var topnew = topscb + 140;
			if(widthscreen > 300 && widthscreen <= 800){
						   jQuery(".zoomimginswp"+idd+aid).css("top",""+topnew+"px");
						   jQuery("#inswbox").css({"height":maxY,"position":"fixed"});
			}else if(widthscreen > 800){
						   jQuery(".zoomimginswp"+idd+aid).css("top","14%");
			}
			jQuery(".zoomimginswp"+idd+aid).html('<span id="loadcont"><img src="'+inswp_frontpage_call_ajax.imageload+'"></span>').fadeIn(100);
			jQuery.ajax({
                   type: "POST",
				   url: inswp_frontpage_call_ajax.ajaxurl,
                   data: "action=zoom_media_inswp&mediainswp="+idd+"&artid="+aid+"&whsc="+widthscreen,
                   cache: false,
                   success: function(data){
					   jQuery(".zoomimginswp"+idd+aid).html(data);
					   if(widthscreen > 300 && widthscreen <= 800){
						   jQuery("#obmedia"+idd+aid).css("height","60%");
					   }else if(widthscreen > 800){
						   jQuery("#obmedia"+idd+aid).css("height","100%");
					   }
					   
                   }
				   
            });
}
<!-- Close zoom function-->
function inswp_close_zoom(idd){
			jQuery("#inswbox").fadeOut(500);
			jQuery("#obmedia"+idd).css("display","none");
			jQuery(".zoomimginswp"+idd).css("display","none");
}
