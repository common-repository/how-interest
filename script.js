/*	
	Copyright 2011  howlin.ie  (email : hello@howlin.ie)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/


jQuery(document).ready(function(){
	
	
	
	jQuery('#how_interest_form').submit(function(){
		

		
		// gather and set
		var name 	= jQuery('#'+name_id).val();
		var phone 	= jQuery('#'+phone_id).val();
		var email 	= jQuery('#'+email_id).val();
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		
		// validate
		if (name == "") { alert(alert_name); jQuery('#'+name_id).focus(); return false; }
		if (email == "") { alert(alert_email); jQuery('#'+email_id).focus(); return false; }
		if (!emailReg.test(email)) { alert(alert_email_invalid); jQuery('#'+email_id).focus(); return false; }
		if (phone == "") { alert(alert_phone); jQuery('#'+phone_id).focus(); return false; }
		
		var data_string = "action=how_interest_submit&name="+name+"&email="+email+"&phone="+phone;
		//alert(data_string);
		
		jQuery.ajax({
			type: 'POST',
			url: ajax_loc.ajaxurl,
			data: data_string,
			success: function(){
				jQuery('#how_interest_form_container')
					.html("<div id='how_interest_form_container_success'></div>")
					.append("<p>"+success_text+"</p>")  
					.hide()  
					.fadeIn(500, function() {  
						jQuery('#how_interest_form_container_success');
					});
			}
		});
		
		return false;
	});
});