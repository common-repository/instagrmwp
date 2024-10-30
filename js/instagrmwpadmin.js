<!-- Insert short code to textare -->
function inswp_admin_setting(adid){
           jQuery('#forminsadmin').change(function(){
	          if(adid != ''){			
				 var adminuid = ' userid="'+adid+'"';
		      }else{
			     var adminuid = '';
		      }
	          if(jQuery('#imgprof:checked').val()){			
				 var prof = ' imageprofile="1"';
		      }else{
			     var prof = '';
		      }
		      if(jQuery('#biog:checked').val()){
				 var desf = ' biography="1"';
		      }else{
			     var desf = '';
		      }
		      if(jQuery('#cmedia:checked').val()){
				 var med = ' media="1"';
		      }else{
			     var med = '';
		      }
		      if(jQuery('#cfing:checked').val()){
				 var fing = ' following="1"';
		      }else{
			     var fing = '';
		      }
		      if(jQuery('#cfer:checked').val()){
				 var fer = ' follower="1"';
		      }else{
			     var fer = '';
		      }
		      if(jQuery('#linkins:checked').val()){
				 var lins = ' linkto="1"';
		      }else{
			     var lins = '';
		      }
		      if(jQuery('#ccomment:checked').val()){
				 var ccom = ' countcomment="1"';
		      }else{
			     var ccom = '';
		      }
		      if(jQuery('#clikes:checked').val()){
				 var clk = ' countlikes="1"';
		      }else{
			     var clk = '';
		      }
		      if(jQuery('#userinphoto:checked').val()){
				 var cuip = ' userinphoto="Y"';
		      }else{
			     var cuip = '';
		      }
		      if(jQuery('#feedcount').val()){
                 var adncount= jQuery('#feedcount').val();
				 var adccount = ' count="'+adncount+'"';
			  }else{
				 var adccount ='';
			  }
			  if(jQuery('input[name=inswptab]:checked').val()){
                         var inswptab = jQuery('input[name=inswptab]:checked').val();
				         if(inswptab == 1){
							 jQuery('#selectcolor').fadeIn(500);
						 }else{
							 jQuery('#selectcolor').fadeOut(500);
						 }
		      }
			  if(jQuery('input[name=inscolor]:checked').val()){
                         var tcolor = jQuery('input[name=inscolor]:checked').val();
				         var adtbcolor = ' color="'+tcolor+'"';
		      }else{
			             var adtbcolor = '';
		      }
		      if(jQuery('input[name=imgsz]:checked').val()){
                         var imsize = jQuery('input[name=imgsz]:checked').val();
				         var simsize = ' size="'+imsize+'inswp"';
		      }else{
			             var simsize = '';
		      }
			  if(jQuery('#cdinswp:checked').val()){
				 var inswpcd = ' cdinswp="1"';
		      }else{
			     var inswpcd = '';
		      }
			  if(jQuery('#cdinsapi:checked').val()){
				 var insapicd = ' cdapi="1"';
		      }else{
			     var insapicd = '';
		      }
	          var adset= adminuid+prof+desf+med+fing+fer+lins+ccom+clk+cuip+adccount+adtbcolor+simsize+inswpcd+insapicd;
	          var message = '[instagrmwp '+adset+']';
	          jQuery('#inswpconts').text( message ); 
            }); 
	       }
<!-- Admin post function -->		   
function admin_ins_post_conf(){
				        jQuery("#messpost").html('<span id="loadinggif"><img src="'+inswp_admin_call_ajax.inswp_admin_images_url+'loadbar.gif"></span>').fadeIn(100);
	                    var catse = jQuery("#cats").val();
				        var title = jQuery("#inswptitle").val();
				        var contse = jQuery("#inswpconts").val();
	                     if(catse == ''){
					       jQuery("#messpost").html('<span style="color:#fff">Category empty!</span>').fadeIn(100);
				           jQuery("#messpost").fadeOut(3000);
					       hitory(0);
				         }
				         if(title == ''){
					       jQuery("#messpost").html('<span style="color:#fff">Title empty!</span>').fadeIn(100);
				           jQuery("#messpost").fadeOut(3000);
					       hitory(0);
				         }
				         if(contse == ''){
					       jQuery("#messpost").html('<span style="color:#fff">Content empty!</span>').fadeIn(100);
				           jQuery("#messpost").fadeOut(3000);
					       hitory(0);
				         }
				         jQuery.ajax({
                            type: "POST",
                            url: inswp_admin_call_ajax.ajaxurl,
                            data: "action=admin_inswp_config&inswpcats="+catse+"&inswptitle="+title+"&inswpcons="+contse,
                            cache: false,
                            success: function(){
                                jQuery("#messpost").html('<span style="color:#fff">Success Post!</span>').fadeIn(100);
				                jQuery("#messpost").fadeOut(3000);
				                window.location.href = inswp_admin_call_ajax.inswp_redirect_admin_url;
                            }
                        });
}
<!-- Logout funtion -->
function logout_inswp(id){
			jQuery("#messsign").html('<span id="loadcont"><img src="'+inswp_admin_call_ajax.inswp_admin_images_url+'loadbar.gif"></span>').fadeIn(100);
			jQuery.ajax({
                   type: "POST",
				   url: inswp_admin_call_ajax.ajaxurl,
                   data: "action=message_logout_inswp&idlogout="+id,
                   cache: false,
                   success: function(data){
					   jQuery("#messsign").html('<span style="color:#fff">Logout Success! Please wait auto refresh</span>');
					   jQuery("#messsign").fadeOut(500);
					   window.location.href = inswp_admin_call_ajax.inswp_redirect_admin_url;
                   }
            });
}