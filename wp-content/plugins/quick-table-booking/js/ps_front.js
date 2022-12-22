(function($) {
 "use strict";  	

	$(window).on('load',function() {
		var dates = pstb_localize_object.pstb_holidays;
		var days = pstb_localize_object.pstb_week_off
		pstb_dt_success_callback(dates,days);
	});  

	function pstb_dt_success_callback(dates,days) {	
		var array = dates;
		$('#booking_date').datepicker({
		    minDate: 0,        
		    dateFormat: 'dd-mm-yy',
		    beforeShowDay: function(date) {            
		        var string = $.datepicker.formatDate('dd-mm-yy', date);            
		        var day = date.getDay();
		        day = day.toString(); 
		        if((array.indexOf(string) == -1) && (days.indexOf(day) == -1)){	        	
		        	return [true];
		        }
		        else{	        	
		        	return [false];
		        }	        
		    },        
		});	
	}


	/* disable unavailable time slots */
	$(document).on('change','#booking_date',function() {	
		 
		 if($('body').hasClass('pstb_dr_appoint_booking_opt') || $('body').hasClass('pstb_saloon_booking_opt')){

			$.ajax({
			    url: pstb_localize_object.ajax_url,
				type: "POST",
			    data: { 
			    	'booking_date': $(this).val(),
			    	'action':'pstb_disable_time_slots'
			    },            
			    async: true,            
			    success: function(data){				
					var $arr = $.parseJSON(data);
					$("#booking_time option").each(function() {
						var $thisOption = $(this);
						var $val = $(this).val();								
						if ($.inArray($val,$arr) != -1){
							$thisOption.attr("disabled", "disabled");
							$thisOption.attr("title", "Full");
						}
						else{
							$thisOption.removeAttr("disabled");	
							$thisOption.removeAttr("title");					
						}
					});
			    }
			});

		}

	});


	function pstb_convert_12_to_24_hour(gtime){
		var time = gtime.replaceAll("-", ":");
		var hours = Number(time.match(/^(\d+)/)[1]);
		var minutes = Number(time.match(/:(\d+)/)[1]);
		var AMPM = time.match(/\s(.*)$/)[1];
		if(AMPM == "PM" && hours<12){
			hours = hours+12;
		}
		if(AMPM == "AM" && hours==12){
			hours = hours-12;
		}	
		if(minutes > 0){			
			hours = hours + 0.5;		
		}
		return hours;
	}
	
	jQuery.validator.addMethod("endtimegreaterthan", function(value, element) {
	
		var start_time = $('#booking_time').val();
		var end_time = $('#booking_time_until').val();
		var date = $('#booking_date').val();

		var sh = pstb_convert_12_to_24_hour(start_time);
		var eh = pstb_convert_12_to_24_hour(end_time);		

		 if(sh >= eh){			
			return false;
		} 
		return true;
	}, "* End Time should be greater than Start Time");


	$("#pstb_booking_form_step1").validate({
		rules: {
			booking_date: {
				required: true		
			},
			booking_time: {
				required: true		
			},
			booking_time_until: {
				required: true,
				endtimegreaterthan:true		
			},
			booking_persons: {
				required: true		
			},
		}
	});	

	$("#pstb_booking_form_table_list").validate({
		rules: {
			sel_table: {
				required: true		
			}			
		}
	});	

	$("#pstb_booking_form_step2").validate({
		rules: {
			billing_first_name: {
				required: true		
			},
			billing_last_name: {
				required: true		
			},
			billing_phone: {
				required: true		
			},
			billing_email: {
				required: true		
			},
		},
		submitHandler: function (form) {

		$('#pstb_booking_form_step2').hide();
		$('.booking-message-box').html('<p>Please Wait...</p>');
		
		var step1_data = $('#pstb_booking_form_step1').serializeArray();
		var step2_data = $('#pstb_booking_form_step2').serializeArray();
		var table_data = $('#pstb_booking_form_table_list').serializeArray();
		
        $.cookie('tb_step1_data', $('#pstb_booking_form_step1').serialize(), { path: '/' });
        $.cookie('tb_step2_data', $('#pstb_booking_form_step2').serialize(), { path: '/' });	
        $.cookie('tb_table_data', $('#pstb_booking_form_table_list').serialize(), { path: '/' });	
		

		var book_option = $("input[name='booking_option']:checked").val();

		if(book_option=='with_food'){
			var url = $("input[name='shop_url']").val();
			location.replace(url);
		}
		else{			

			 $.ajax({
			    url: pstb_localize_object.ajax_url,
				type: "POST",
			    data: { 
			    	'step1_data': step1_data,
			    	'step2_data': step2_data,
			    	'table_data': table_data,
			    	'action':'pstb_add_booking_data'
			    },
			    dataType: "json", 			         
			    async: true,            
			    success: function(data){			    				    	
					if(data.ans==1){
						var html = '';
						html += '<p class="success">Your booking is Confirmed.</p>';
						html += '<table>';
						html += '<tbody>';
						html += '<tr>';
						html += '<td>Booking Code:</td><td>'+data.bcode+'</td>';
						html += '</tr>';
						html += '<tr>';
						html += '<td>Booking Date:</td><td>'+data.bdate+'</td>';
						html += '</tr>';
						html += '<tr>';
						if(data.btimeuntil == ''){
							html += '<td>Booking Time:</td><td>'+data.btime+'</td>';
						}
						else{
							html += '<td>Booking Time:</td><td>'+data.btime+' To '+data.btimeuntil+'</td>';
						}
						
						html += '</tr>';

						if($('body').hasClass('pstb_table_booking_opt') || $('body').hasClass('pstb_hotel_room_booking_opt')){

							html += '<tr>';
							html += '<td>No of Persons:</td><td>'+data.bpersons+'</td>';
							html += '</tr>';						
							html += '<tr>';
							html += '<td>Booking Object:</td><td>'+data.btable_title+'</td>';
							html += '</tr>';
							html += '<tr>';
							html += '<td>Booking Object Section:</td><td>'+data.btable_section+'</td>';
							html += '</tr>';

						}


						html += '<tr>';
						html += '<td>First Name:</td><td>'+data.bfirst_name+'</td>';
						html += '</tr>';
						html += '<tr>';
						html += '<td>Last Name:</td><td>'+data.blast_name+'</td>';
						html += '</tr>';
						html += '<tr>';
						html += '<td>Phone Number:</td><td>'+data.bphone+'</td>';
						html += '</tr>';
						html += '<tr>';
						html += '<td>Email Address:</td><td>'+data.bemail+'</td>';
						html += '</tr>';
						html += '<tr>';
						html += '<td>Message:</td><td>'+data.bcustomer_note+'</td>';
						html += '</tr>';						
						html += '</tbody>';
						html += '</table>';		

						$('.booking-message-box').html(html);

					}
					else{

						$('.booking-message-box').html('<p class="error">Error while Booking. Please try again later..</p>');
					}
								
			    }
	    	}); 

		}		
		return false;		
		}
	});
	
	$(document).on('click','.pstb-first-step', function () {
        if ($('#pstb_booking_form_step1').valid()) {            
            $('#pstb_booking_form_step1').hide();
            $('.booking-message-box').html('<p>Please Wait...</p>');			
			
			var data; 

			console.log('select:'+$("select[name='booking_time_until']").length);

		    if($('body').hasClass('pstb_table_booking_opt') || $('body').hasClass('pstb_hotel_room_booking_opt')){

				if($("select[name='booking_time_until']").length){
					 data = { 
				    	'persons': $("input[name='booking_persons']").val(),
				    	'time': $("select[name='booking_time']").val(),
				    	'date': $("input[name='booking_date']").val(),
				    	'time_until': $("select[name='booking_time_until']").val(),
				    	'action':'pstb_disp_tables'
				    }
				}
				else{

					 data = { 
				    	'persons': $("input[name='booking_persons']").val(),
				    	'time': $("select[name='booking_time']").val(),
				    	'date': $("input[name='booking_date']").val(),			    	
				    	'action':'pstb_disp_tables'
				    }

				}          


				 $.ajax({
				    url: pstb_localize_object.ajax_url,
					type: "POST",
				    data: data, 
				    dataType: "html", 		           
				    async: true,            
				    success: function(data){
				    	$('.booking-message-box').html('');	
				    	$('#pstb_booking_form_table_list').show();
				    	$('#pstb_booking_form_table_list').html(data);	

				    	
				    	var totalradio = $('#pstb_booking_form_table_list input:radio').length;
				    	var disable_radio = $('#pstb_booking_form_table_list input:radio:disabled').length;

				    	if(totalradio == disable_radio){

				    		$('pstb-table-step').prop('disable','true');
				    	}

				    }
		   		 }); 

		   }
		   else{
		   		
            	$.ajax({
			    url: pstb_localize_object.ajax_url,
				type: "POST",
			    data: { 
			    	'persons': $("input[name='booking_persons']").val(),
			    	'time': $("select[name='booking_time']").val(),
			    	'date': $("input[name='booking_date']").val(),
			    	'action':'pstb_check_person_availability_timeslot'
			    }, 
			    dataType: "json", 		           
			    async: true,            
			    success: function(data){
			    	if(data.ans==0){	
			    		$('#pstb_booking_form_step2').show();
			    		$('.booking-message-box').html('');	
			    	}else{
			    		$('#pstb_booking_form_step1').show();	
			    		var html = '<p>Please Choose different Time Slot..</p>'; 
			    		$('.booking-message-box').html(html);		    		
			    	}

								
			    }
	   		 });        

		   }

        }
    });    
    
    $(document).on('click','.psbk-goto-first', function () {
        $('#pstb_booking_form_table_list').hide();
        $('#pstb_booking_form_step2').hide();
        $('#pstb_booking_form_step1').show();
    });

    $(document).on('click','.pstb-table-step', function () {
        if ($('#pstb_booking_form_table_list').valid()) {            
            $('#pstb_booking_form_table_list').hide();
            $('#pstb_booking_form_step2').show();
        }
    });

    $(document).on('click','.psbk-goto-table-listing', function () {
        $('#pstb_booking_form_table_list').show();
        $('#pstb_booking_form_step2').hide();
    });


    /* autofill checkout repeated fields if its blank */

    /* 
    $(document).ready(function(){  
	if ($('#billing_first_name').length && $('#billing_first_name').val()==''){

	} 
    });	
    */

})(jQuery);