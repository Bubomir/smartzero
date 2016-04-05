jQuery(document).ready(function($) {
	// settings tabs
	//When page loads…
	var activetab = $('input#last-tab').val();
	$(".tab_content").hide(); //Hide all content
	if (activetab){
		$("h2.nav-tab-wrapper a[href$='"+activetab+"']").addClass("nav-tab-active").show(); //Activate first tab
		$(".tab_content"+activetab).show(); //Show first tab content
	} else {
		$("h2.nav-tab-wrapper a:first").addClass("nav-tab-active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
	}
	
	$('h2.nav-tab-wrapper a').click(function(e) {				
		e.preventDefault();
		//jQuery(".chosen-select").select2({ width: '50%' });
		var tab = $(this).attr('href');
		$( 'h2.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$(this).addClass( 'nav-tab-active' );
		$(".tab_content").hide();		
		$("#tab_container " + tab).fadeIn(400, function(){
			//jQuery(".chosen-select").select2({ width: '50%' });
		});
		$("input#last-tab").val(tab);	
			
	});
	
	$('#media-items').bind('DOMNodeInserted',function(){
		$('input[value="Insert into Post"]').each(function(){
				$(this).attr('value','Use This Image');
		});
	});

	$('.repeatable-add').click(function() {
		field = $(this).closest('td').find('.custom_repeatable li:last').clone(true);
		fieldLocation = $(this).closest('td').find('.custom_repeatable li:last');
		$('input', field).val('').attr('name', function(index, name) {
			return name.replace(/(\d+)/, function(fullMatch, n) {
				return Number(n) + 1;
			});
		});
		field.insertAfter(fieldLocation, $(this).closest('td'));
		return false;
	});
	
	$('.repeatable-remove').click(function(){
		$(this).parent().remove();
		return false;
	});
	


	jQuery(".chosen-select").select2();
	jQuery(".select2-ajax-multiple").select2(
		{
		multiple: true,
		minimumInputLength: 3,
            ajax: {
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: function(term, page) {
                    return {
                        q: term,
                        action: 'prospekt_ajax_handler',
                        action_type: 'search_posts'
                    }
                },
                results: function(data, page) {
                    return { results: data };
                }
                
            },
            initSelection: function(element, callback) {
                var data = [];
                var post_ids = element.data('json');
                $(post_ids).each(function(idx, val) {
                    data.push({ id: val.id, text: val.text });
                });
                callback(data);
            }
        }    
            
	);
	
	var showslidertype = jQuery(".chosen-select[name='thm_settings[thm_slider-type]']").val();
	$('#slider-type-'+showslidertype).show();
	
	$(".chosen-select[name='thm_settings[thm_slider-type]']").select2().change(function() {
		showslidertype = jQuery(this).val();
  	 	$('.slider-type').fadeOut();
   		$('#slider-type-'+showslidertype).fadeIn();
   		
	});
	
	var showslidertype = jQuery(".chosen-select[name='_essential_slider_settings[slider-type]']").val();
	$('#slider-type-'+showslidertype).show();
	
	$(".chosen-select[name='_essential_slider_settings[slider-type]']").select2().change(function() {
		showslidertype = jQuery(this).val();
  	 	$('table.slider-type').fadeOut();
   		$('#slider-type-'+showslidertype).fadeIn();
   		
	});
	
	var customcolors = jQuery(".chosen-select[name='thm_settings[thm_site-style]']").val();
	if (customcolors == 'custom' ){
			$('#custom-colors').fadeIn();	
		} else { 
			$('#custom-colors').fadeOut();	
		}
	
	$(".chosen-select[name='thm_settings[thm_site-style]']").select2().change(function() {
		customcolors = jQuery(this).val();
		
		if (customcolors == 'custom' ){
			$('#custom-colors').fadeIn();	
		} else { 
			$('#custom-colors').fadeOut();	
		}
  	 	
   		
   		
	});
	
	
		$('input[type="checkbox"].use_slider ').live('click',function(){
			if($(this).is(":checked") && (!$(' input[type="checkbox"].def_slider').is(":checked")) ){
		        $('.hidden-container').fadeIn();
		    }else{
		        $('.hidden-container').fadeOut();   
		    }
		});
		$(' input[type="checkbox"].def_slider').live('click',function(){
			if(!$(' input[type="checkbox"].use_slider').is(":checked")){
				$('.hidden-container').fadeOut();
			} else if($(this).is(":checked") && $('input[type="checkbox"].use_slider').is(":checked")){
				$('.hidden-container').fadeOut();
		    } else if(!$(this).is(":checked") && $(' input[type="checkbox"].use_slider').is(":checked")){
		        $('.hidden-container').fadeIn();   
		    } else{
		        $('.hidden-container').fadeIn();   
		    }
		});
		
		
		if ($('input[name="thm_settings[thm_home-tabs]"]').is(":checked")){
			$('tr.thm_active-tab').show();
		}
		$('input[name="thm_settings[thm_home-tabs]"]').on('click',function(){
			if($(this).is(":checked")){
				$('tr.thm_active-tab').show();
			} else {
				$('tr.thm_active-tab').hide();
			}
		});
		
	 jQuery("ul.ui-sortable").sortable({
        cursor: 'move',
        update: function(event, ui) {
        			$(this).next().val($(this).sortable('toArray').toString());
        	
        	      }
    });
    
    $(".tax_sotable").each(function( index ) {
		 var terms = jQuery(this).data('terms') ;
		 var termsarray = $.map(terms, function(value, index) {
			    return [index];
			});
		   jQuery(this).select2({tags: termsarray});
		});
    

 

    jQuery(".tax_sotable").on("change", function() { 
    	jQuery(this).next().val(jQuery(this).val());
    	});
    
    jQuery(".tax_sotable").select2("container").find("ul.select2-choices").sortable({
					                containment: "parent",
                					update: function() { 
                						$(this).parent().select2("onSortEnd");
                						; }
					             
	});
	
	
    
	
});

