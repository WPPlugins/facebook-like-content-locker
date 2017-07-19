jQuery(document).ready(function()
	{
	    var id = 'fblike_lock_anicetextarea';
	 
	    jQuery('a.toggleVisual').click(
	        function() {
	            tinyMCE.execCommand('mceAddControl', false, id);
	        }
	    );
	 
	    jQuery('a.toggleHTML').click(
	        function() {
	            tinyMCE.execCommand('mceRemoveControl', false, id);
	        }
	    );
		
		var slider = document.getElementById ("fblike_lock_tran");
		OnSliderChanged(slider);
		
	
	});
	
jQuery(document).ready(function() {
    jQuery('#ilctabscolorpicker_color').hide();
    jQuery('#ilctabscolorpicker_color').farbtastic("#fblike_lock_color");
     jQuery("#fblike_lock_color").click(function(){jQuery('#ilctabscolorpicker_color').slideDown()});
     jQuery("#fblike_lock_color").blur(function(){jQuery('#ilctabscolorpicker_color').slideUp()});
	 
	 jQuery('#ilctabscolorpicker_color2').hide();
    jQuery('#ilctabscolorpicker_color2').farbtastic("#fblike_lock_color2");
     jQuery("#fblike_lock_color2").click(function(){jQuery('#ilctabscolorpicker_color2').slideDown()});
     jQuery("#fblike_lock_color2").blur(function(){jQuery('#ilctabscolorpicker_color2').slideUp()});
  });

 function OnSliderChanged (slider) 
	{
		var sliderValue = document.getElementById ("slider1Value");
		if(sliderValue)
		sliderValue.innerHTML = slider.value;
	}