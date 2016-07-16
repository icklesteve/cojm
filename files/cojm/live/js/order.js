$(document).ready(function() {





$("#newcost").change(function() { editnewcost(); });
$("#buttoncancelpricelock").click(function() { cancelpricelock();	});	
	


		



		
    $('#jschangfavto').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=to&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',
	  width : 500 ,
	  	position : ['left + 20', 'top + 30'],
    'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel', callback: function() {}},
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  }); 






			$('#jschangfavvia1').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via1&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia2').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via2&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia3').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via3&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia4').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via4&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia5').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via5&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia6').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via6&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia7').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via7&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia8').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via8&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia9').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via9&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia10').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via10&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia11').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via11&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia12').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via12&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia13').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via13&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia14').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via14&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia15').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via15&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia16').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via16&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia17').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via17&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia18').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via18&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia19').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via19&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  });  
			$('#jschangfavvia20').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxselectfav.php?addr=via20&clientid=' + oldclientorder + '&jobid=' + id )},
	  'type':     'question',  'width': '500', position : ['left + 20', 'top + 30'], 'title': 'Please select new address',
	        'buttons':  [ {caption: 'Cancel' },
			{caption: 'Select', callback: function() { document.getElementById('selectfav').submit(); }} ] });  }); 













$(function() {
	 $("input#targetcollectiondate").datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false,
 onClose: function() {
checktargetcollectiondate();
  }
 });	
 });


 
 	$(function() {
		 $( "#collectionworkingwindow" ).datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false,   
			beforeShow: function(input, instance) { 
if (initialcollectionworkingwindow=="") {
$("#collectionworkingwindow").val(initialtargetcollectiondate);
}
 }, 
 onClose: function() {
checkcollectionworkingwindow();
  }	

		});	});
 

 
 
 	$(function() {
		$("#starttravelcollectiontime").datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false,
	 onClose: function() {
checkstarttravelcollectiontime();
  }		

	});	});
 
 
 $(function() {
		$("#waitingstarttime").datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false, 
		 onClose: function() {
checkwaitingstarttime();
  }			});
	});
 
 
	$(function() {
	$("#collectiondate").datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false, 
 onClose: function() {
checkcollectiondate();
  }			});	});



  
  	$(function() {
		$( "#starttrackpause" ).datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false, 
			beforeShow: function(input, instance) {
if (initialstarttrackpause=="") {
// alert(" delivery ww not set, adding main target collection ");
		$("#starttrackpause").val(initialcollectiondate);
} }, 
 onClose: function() {
checkstarttrackpause();
  }	
		});
	});
  
  
  
  
  
  
  
	$(function() {
		$( "#finishtrackpause" ).datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
		  beforeShow: function(input, instance) { 
if (initialfinishtrackpause=="") {
if (initialShipDate!=="") {
	$("#finishtrackpause").val(initialShipDate);
} else if (initialstarttrackpause!=="") {
	$("#finishtrackpause").val(initialstarttrackpause);
} else if (initialcollectiondate!=="") {
	$("#finishtrackpause").val(initialcollectiondate);
}
} 
 }, 
	changeMonth:false,
 onClose: function() {
checkfinishtrackpause();
  }		});
	});
	
	
	
	
	
	$(function() {
		 $( "#duedate" ).datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm", 			
			changeMonth:false	,
 onClose: function() {
checkduedate();
  }			
		});
	});	



	$(function() {
		$( "#deliveryworkingwindow" ).datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
		beforeShow: function(input, instance) { 
if (initialdeliveryworkingwindow=="") {
// alert(" delivery ww not set, adding main target collection ");

//  $(input).datetimepicker("setDate",  new Date(initialduedate) );

  	$("#deliveryworkingwindow").val(initialduedate);
  
  

}
 }, 
 onClose: function() {
checkdeliveryworkingwindow();
  },
		changeMonth:false
		});
	});	


	$(function() {
		$("#ShipDate").datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
		  onClose: function() {
checkShipDate();
  },
changeMonth:false
		}); });	
		
		
		
		
		
	$(function() {
		$("#jobrequestedtime").datetimepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
			addSliderAccess: true,
	        sliderAccessArgs: { touchonly: false },
            dateFormat: "dd/mm/yy",
			timeFormat : "hh:mm",
			changeMonth:false, 			
  onClose: function() {
checkjobrequestedtime();
  } 
		});
	});



	
	
	
	
	





 $("#newstatus").change(function () {
 message='';
testtimes();
newstatus=$("select#newstatus").val();

if (oktosubmit==1) { sendstatus(); } else { 
$("select#newstatus").val(initialstatus);
allok=0;
showmessage(); 
}
});







 
$( "#podsurname" ).change(function() {
var podsurname=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxpodsurname',
		formbirthday:formbirthday,
		id:id,
		podsurname:podsurname},
		type:'post',
        success: function(data) {
	$('#emissionsaving').append(data);	
		},
		complete: function(data) {
	showmessage();
		}
});
});
  















$(".cbbcheckbox").change(function() {
waitingmins=$("select#waitingmins").val();
cbbname=this.name;
if(this.checked) { cbbchecked=1; } else { cbbchecked=0; }
// alert(cbbname + cbbchecked + waitingmins);	
		
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxcbb',
		cbbname:cbbname,
		cbbchecked:cbbchecked,
		waitingmins:waitingmins,
		id:id,
		formbirthday:formbirthday,
		},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); }
});

});



$("#opsmaparea").change(function () {
 var opsmaparea=$("select#opsmaparea").val();
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxchangeopsmaparea',
		formbirthday:formbirthday,
		id:id,
		opsmaparea:opsmaparea},
		type:'post',
        success: function(data) {
	$('#client').append(data);	
		},
		complete: function(data) {
showmessage();
	$(document).ready(function() { 
 $("html, body").animate({ scrollTop: $(document).height() }, 500);
	});
		}
});
});









$("#serviceid").change(function () {
 var serviceid=$("select#serviceid").val();
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxchangeserviceid',
		formbirthday:formbirthday,
		id:id,
		serviceid:serviceid},
		type:'post',
        success: function(data) {
	$('#client').append(data);	
		},
		complete: function(data) {
showmessage();
	$(document).ready(function() { 
// $("html, body").animate({ scrollTop: $(document).height() }, 500);
	});
		}
});
});







$('#uploadpodfile').change(function(){
$('progress').attr({value:0,max:1});
$('#uploadpodprogress').show();
$("#formbirthday").val(formbirthday);

    var file = this.files[0];
    var name = file.name;
    var size = file.size;
    var type = file.type;
    // more validation
	
	var formData = new FormData($('#uploadpodform')[0]);
    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
        type: 'POST',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        //Ajax events
//        beforeSend: beforeSendHandler,
 success: function(data){
$('#emissionsaving').append(data);

},
complete: function(data) {
showmessage();
},	
	
//        error: errorHandler,
        // Form data
        data: formData,
        //Options to tell jQuery not to process data or worry about content-type.
        cache: false,
        contentType: false,
        processData: false
    });
});







$("span#ajaxremovepod").hover(function(){
    $(this).css("opacity", "0.6");
	$("img.orderpod").css("opacity", "0.3");
	
    }, function(){
    $(this).css("opacity", "0.3");
	$("img.orderpod").css("opacity", "1.0");
	});

$("#ajaxremovepod").click(function(){
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxremovepod',
		formbirthday:formbirthday,
		id:id,
		publicid:publictrackingref},
		type:'post',
        success: function(data) {
$('#emissionsaving').append(data);
	},
		complete: function(data) {
		showmessage();
		}
});
});






































$("#opsmapsubarea").change(function () {
 var opsmapsubarea=$("select#opsmapsubarea").val();
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxchangeopsmapsubarea',
		formbirthday:formbirthday,
		id:id,
		opsmapsubarea:opsmapsubarea},
		type:'post',
        success: function(data) {
	$('#client').append(data);	
		},
		complete: function(data) {
showmessage();
	$(document).ready(function() { 
 $("html, body").animate({ scrollTop: $(document).height() }, 500);
	});
		}
});
});




  $( "#jobcomments" ).change(function() {
var jobcomments=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxjobcomments',
		formbirthday:formbirthday,
		id:id,
		jobcomments:jobcomments},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); }
}); });



  $( "#privatejobcomments" ).change(function() {
var privatejobcomments=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxprivatejobcomments',
		formbirthday:formbirthday,
		id:id,
		privatejobcomments:privatejobcomments},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); }
}); });



  
  $( "#requestor" ).change(function() {
var requestor=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxrequestor',
		formbirthday:formbirthday,
		id:id,
		requestor:requestor},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); }
}); });
  
  
  
  
  $( "#clientjobreference" ).change(function() {
var clientjobreference=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxclientjobreference',
		formbirthday:formbirthday,
		id:id,
		clientjobreference:clientjobreference},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); }
}); });
 
 





 
 
   $( "#numberitems" ).change(function() {
var numberitems=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxnumberitems',
		formbirthday:formbirthday,
		id:id,
		numberitems:numberitems},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); }
}); });
 
 $(function() { $('#targetcollectiondate').on('blur' , function() { checktargetcollectiondate(); }); });
 $(function() { $('#starttravelcollectiontime').on('blur' , function() { checkstarttravelcollectiontime(); }); }); 
 $(function() { $('#waitingstarttime').on('blur' , function() { checkwaitingstarttime(); }); });
 $(function() { $('#collectiondate').on('blur' , function() { checkcollectiondate(); }); });
 $(function() { $('#starttrackpause').on('blur' , function() { checkstarttrackpause(); }); });
 $(function() { $('#finishtrackpause').on('blur' , function() { checkfinishtrackpause(); }); });
 $(function() { $('#duedate').on('blur' , function() { checkduedate(); }); }); 
 $(function() { $('#deliveryworkingwindow').on('blur' , function() { checkdeliveryworkingwindow(); }); });
 $(function() { $('#ShipDate').on('blur' , function() { checkShipDate(); }); });
 $(function() { $('#jobrequestedtime').on('blur' , function() { checkjobrequestedtime(); }); });
 
 
 
$("#allowww").click(function(){$("#collectionworkingwindow").slideToggle("fast");});
$("#allowdww").click(function(){$("#deliveryworkingwindow").slideToggle("fast");});

 $("#toggleresumechoose").click(function(){
	$("#toggleresume").slideToggle("fast");
	$("#toggleresumechoose").hide();
	});

 

    setTimeout( function() {
  
 if ((olddeporder)<1) {
	$("div#clientdep.fsr input.ui-autocomplete-input").addClass("autoinputerror").removeClass(""); } 
	else {
	$("div#clientdep.fsr input.ui-autocomplete-input").addClass("").removeClass("autoinputerror"); 	
	}
   },1100);
  
 

$(function(){ $(".normal").autosize();	});
$(function() { $( "#orderselectdep" ).orderselectdep(); $( "#toggle" ).click(function() { $( "#orderselectdep" ).toggle(); });	});			
$(function() { $( "#combobox" ).combobox(); $( "#toggle" ).click(function() { $( "#combobox" ).toggle();	});	}); 



$('#orderaudit').bind('click', function(e) {	e.preventDefault();  $.Zebra_Dialog('', {
    'source':  {'ajax': ('ajaxaudit.php?orderid=' + id + '&auditpage=order&page')},
	'type': 'false',
	'scrolling': 'yes',
    'title': ('Audit log for ' + id)
}); });


 
 
}) // ends document ready



  function process(date){
	var datetime = date.split(" ");  
   var parts = datetime[0].split("/");
   var timeparts = datetime[1].split(":");
   newdate = new Date(parts[2], parts[1] - 1, parts[0], timeparts[0], timeparts[1]);
   return newdate;
 }





function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}  

function ordermapupdater() {
$("#orderajaxmap").html("").show();

  	    $.ajax({
        url: 'ajaxordermap.php',  //Server script to process data
		data: {
		page:'ajaxclientjobreference',
		formbirthday:formbirthday,
		id:id},
		type:'post',
        success: function(data) { $('#orderajaxmap').html(data);  },
		complete: function(data) { 
//		showmessage(); 
		
		}
});
}




 function testtimes() {
	 
	 
showhidebystatus(); // updates which buttons to show	 
 
 // if existing message start a new line
 if (message!=='') {  message += '<br />'; }
 oktosubmit=1;
 
 message+='Status is ' + initialstatus + ' <br /> '; 

 
if (initialtargetcollectiondate=="") {
   message+='No Target Collection Time <br /> ';
oktosubmit=0;
} 
 
if (initialduedate=="") {
   message+='No Target Delivery Time <br /> ';
oktosubmit=0;
} 
 
 
if (initialcollectiondate!=="") {
if (initialShipDate!=="") {	
if (process(initialcollectiondate) > process(initialShipDate)){
  message+='Collection (' + initialcollectiondate + ') needs to be earlier than delivery (' + initialShipDate +') <br /> ';
oktosubmit=0;
  } 
}}


if (initialduedate!=="") {
if (initialdeliveryworkingwindow!=="") {
if (process(initialdeliveryworkingwindow)<process(initialduedate)) {
 message+='Delivery window start (' + initialduedate + ') needs to be earlier than Delivery window finish (' + initialdeliveryworkingwindow + ') <br />';
oktosubmit=0;
}}}


if (initialcollectionworkingwindow!=="") {
if (initialtargetcollectiondate!=="") {
if (process(initialcollectionworkingwindow)<process(initialtargetcollectiondate)) {
 message+='Collection window start (' + initialtargetcollectiondate + ') needs to be earlier than Collection window finish (' + initialcollectionworkingwindow + ') <br />';
oktosubmit=0;
}}}

 
if (initialstarttrackpause!=="") {
if (initialfinishtrackpause!=="") {
if (process(initialstarttrackpause)>process(initialfinishtrackpause)) {
 message+='Pause (' + initialstarttrackpause + ') needs to be earlier than Resume (' + initialfinishtrackpause + ') <br />';
oktosubmit=0; 
}}} 
 

if (initialstarttrackpause!=="") {
if (initialcollectiondate!=="") {
if (process(initialcollectiondate)>process(initialstarttrackpause)) {
 message+='Collection (' + initialcollectiondate + ') needs to be earlier than Pause (' + initialstarttrackpause + ') <br />';
oktosubmit=0; 
}}} 

 
if (initialfinishtrackpause!=="") {
if (initialShipDate!=="") {
if (process(initialfinishtrackpause)>process(initialShipDate)) {
 message+='Resume (' + initialfinishtrackpause + ') needs to be earlier than Complete (' + initialShipDate + ') <br />';
oktosubmit=0; 
}}} 



if (initialstarttrackpause=="") {
if (initialfinishtrackpause!=="") {
 message+='Resumed but no Paused time<br />';
oktosubmit=0;
}}

 
if (initialwaitingstarttime!=="") {
if (initialcollectiondate!=="") {
if (process(initialcollectiondate)<process(initialwaitingstarttime)) {
 message+='En Site at PU (' + initialwaitingstarttime + ') needs to be earlier than Collection (' + initialcollectiondate + ') <br />';
oktosubmit=0;
}}}
 
 
 
if (initialwaitingstarttime!=="") {
if (initialstarttravelcollectiontime!=="") {
if (process(initialstarttravelcollectiontime)>process(initialwaitingstarttime)) {
 message+='En Route to PU (' + initialstarttravelcollectiontime + ') needs to be earlier than On Site (' + initialwaitingstarttime + ') <br />';
oktosubmit=0;
}}}
 
 
if (initialcollectiondate!=="") {
if (initialstarttravelcollectiontime!=="") {
if (process(initialstarttravelcollectiontime)>process(initialcollectiondate)) {
 message+='En Route to PU (' + initialstarttravelcollectiontime + ') needs to be earlier than Collection (' + initialcollectiondate + ') <br />';
oktosubmit=0;
}}}
 
 
if (initialwaitingstarttime!=="") {
if (initialcollectiondate!=="") {
waitingminstest=~~((process(initialcollectiondate)-process(initialwaitingstarttime))/1000/60);
if (waitingminstest>waitingtimedelay) { 	// global test minutes more than than diff so see if waiting mins in cbb
if (waitingmins<4) {
	
message+=waitingminstest + 'mins from en site to collection with no waiting time in dropdown <br />';
// message+=waitingmins + 'mins waiting time selected in dropdown <br />';
oktosubmit=0;
}}}}



if (initialduedate!=="") {
if (initialtargetcollectiondate!=="") {
if (process(initialduedate)<process(initialtargetcollectiondate)) {
 message+='Target Collection  (' + initialtargetcollectiondate + ') needs to be earlier than Target Delivery (' + initialduedate + ') <br />';
oktosubmit=0;
}}}




if (haspod==1) { 
if (podsurname=="") {
message+='Needs POD Surname <br />';
oktosubmit=0;
}}



if (initialstatus>59) {
if (initialcollectiondate=="") {
message+='Needs PU Time <br />';
oktosubmit=0; 
}}


if (initialstatus>85) {
if (initialShipDate=="") {
message+='Needs Delivery Time <br />';
oktosubmit=0; 
}} 

 if (initialstatus>61) {
if (initialstarttrackpause!=="") {
if (initialfinishtrackpause=="") {
message+='Needs Resume Time <br />';
oktosubmit=0;
}}}
 

// if ( oktosubmit==1 )  { } else { allok=0; }
  
} // ends check time function
 


 
 
 


function editnewcost() {
var newcost=$("#newcost").val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxeditcost',
		formbirthday:formbirthday,
		id:id,
		newcost:newcost},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage(); 
	}
		});
}

function cancelpricelock() {  
//	alert( "cancel clicked" );
//     e.preventDefault();		  
// var newcost=$( this ).val();
  	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxcancelpricelock',
		formbirthday:formbirthday,
		id:id},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { showmessage();  }
}); }



function depcomboboxchanged() {
// alert("department changed");
var newdeporder=$("select#orderselectdep").val();
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxchangedep',
		formbirthday:formbirthday,
		id:id,
		newdeporder:newdeporder,
		olddeporder:olddeporder},
		type:'post',
        success: function(data) {
	$('#client').append(data);	
		},
		complete: function(data) {
showmessage();
		}
});
}


function comboboxchanged() {
// alert("order2.php 3333 Client Changed");
var newclientorder=$("select#combobox").val();
// alert(newclientorder);
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxchangeclient',
		formbirthday:formbirthday,
		id:id,
		newclientorder:newclientorder,
		oldclientorder:oldclientorder},
		type:'post',
        success: function(data) {
// $('#uploadpodfile').show();
// $('#podimagecontainer').hide();
	$('#client').append(data);	
		},
		complete: function(data) {
showmessage();
		}
});
}
 
















 // collectionworkingwindow
  $(function() { $('#collectionworkingwindow').on('blur' , function() { checkcollectionworkingwindow(); }); });

 function checkcollectionworkingwindow() {
	 
if (initialstatus<100) {
	 
  collectionworkingwindow=$("#collectionworkingwindow").val().trim();
 if (collectionworkingwindow!==initialcollectionworkingwindow) {
		initialcollectionworkingwindow=collectionworkingwindow;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxcollectionworkingwindow',
		formbirthday:formbirthday,
		id:id,
		collectionworkingwindow:collectionworkingwindow},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
} else {
	allok=0;
message=statustoohigh;
showmessage();
$('#collectionworkingwindow').val(initialcollectionworkingwindow);
}
	};
 







 function checkstarttravelcollectiontime() {
	 if (initialstatus<100) {
  starttravelcollectiontime=$("#starttravelcollectiontime").val().trim();
 if (starttravelcollectiontime!==initialstarttravelcollectiontime) {
		initialstarttravelcollectiontime=starttravelcollectiontime;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxstarttravelcollectiontime',
		formbirthday:formbirthday,
		id:id,
		starttravelcollectiontime:starttravelcollectiontime},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
	 } else {
allok=0;
message=statustoohigh;
showmessage();
$('#starttravelcollectiontime').val(initialstarttravelcollectiontime);
}
};



 function checkwaitingstarttime() {
 if (initialstatus<100) {
  waitingstarttime=$("#waitingstarttime").val().trim();
 if (waitingstarttime!==initialwaitingstarttime) {
		initialwaitingstarttime=waitingstarttime;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxwaitingstarttime',
		formbirthday:formbirthday,
		id:id,
		waitingstarttime:waitingstarttime},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
  	 } else {
allok=0;
message=statustoohigh;
showmessage();
$('#waitingstarttime').val(initialwaitingstarttime);
}
	};

	
	




 function checkcollectiondate() {
	 
	  if (initialstatus<100) {
  collectiondate=$("#collectiondate").val().trim();
 if (collectiondate!==initialcollectiondate) {
		initialcollectiondate=collectiondate;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxcollectiondate',
		formbirthday:formbirthday,
		id:id,
		collectiondate:collectiondate},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
  	 } else {
allok=0;
message=statustoohigh;
showmessage();
$('#collectiondate').val(initialcollectiondate);
}
	};





 function checkstarttrackpause() {
	  if (initialstatus<100) {
  starttrackpause=$("#starttrackpause").val().trim();
 if (starttrackpause!==initialstarttrackpause) {
		initialstarttrackpause=starttrackpause;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxstarttrackpause',
		formbirthday:formbirthday,
		id:id,
		starttrackpause:starttrackpause},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
    	 } else {
allok=0;
message=statustoohigh;
showmessage();
$('#starttrackpause').val(initialstarttrackpause);
}
	};









 function checkfinishtrackpause() {
	 	  if (initialstatus<100) {
  finishtrackpause=$("#finishtrackpause").val().trim();
 if (finishtrackpause!==initialfinishtrackpause) {
		initialfinishtrackpause=finishtrackpause;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxfinishtrackpause',
		formbirthday:formbirthday,
		id:id,
		finishtrackpause:finishtrackpause},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
   } else {
allok=0;
message=statustoohigh;
showmessage();
$('#finishtrackpause').val(initialfinishtrackpause);
}
	};

 







 function checkduedate() {
	 	 	  if (initialstatus<100) {
  duedate=$("#duedate").val().trim();
 if (duedate!==initialduedate) {
		initialduedate=duedate;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxduedate',
		formbirthday:formbirthday,
		id:id,
		duedate:duedate},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
     } else {
allok=0;
message=statustoohigh;
showmessage();
$('#duedate').val(initialduedate);
}
	};




 function checkdeliveryworkingwindow() {
	 	 	  if (initialstatus<100) {
  deliveryworkingwindow=$("#deliveryworkingwindow").val().trim();
 if (deliveryworkingwindow!==initialdeliveryworkingwindow) {
		initialdeliveryworkingwindow=deliveryworkingwindow;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxdeliveryworkingwindow',
		formbirthday:formbirthday,
		id:id,
		deliveryworkingwindow:deliveryworkingwindow},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
       } else {
allok=0;
message=statustoohigh;
showmessage();
$('#deliveryworkingwindow').val(initialdeliveryworkingwindow);
}
	};	
	

 function checkShipDate() {
	  	  if (initialstatus<100) {
  ShipDate=$("#ShipDate").val().trim();  
  
 if (ShipDate!==initialShipDate) {
		initialShipDate=ShipDate;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxShipDate',
		formbirthday:formbirthday,
		id:id,
		ShipDate:ShipDate},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
         } else {
allok=0;
message=statustoohigh;
showmessage();
$('#ShipDate').val(initialShipDate);
}
	}	
	
	
	
	
	
	
	
	
	 function checkjobrequestedtime() {
	 	  if (initialstatus<100) {
  jobrequestedtime=$("#jobrequestedtime").val().trim();
 if (jobrequestedtime!==initialjobrequestedtime) {

		initialjobrequestedtime=jobrequestedtime;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxjobrequestedtime',
		formbirthday:formbirthday,
		id:id,
		jobrequestedtime:jobrequestedtime},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) { testtimes(); showmessage(); }
});  
  } // times different
           } else {
allok=0;
message=statustoohigh;
showmessage();
$('#jobrequestedtime').val(initialjobrequestedtime);
}
	}
	
	
	
	
	 function checktargetcollectiondate() {
	 
	 
	 
//	  newstatus=$("select#newstatus").val();

if (initialstatus<100) {
	 
  targetcollectiondate=$("#targetcollectiondate").val().trim();
 if (targetcollectiondate!==initialtargetcollectiondate) {
//		$("#emissionsaving").append(" DIFFERENT ");
//	  	$("#emissionsaving").append(" old " + initialcollectiondate);
//	  	$("#emissionsaving").append(" new " + collectiondate);
		initialtargetcollectiondate=targetcollectiondate;

	$.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxtargetcollectiondate',
		formbirthday:formbirthday,
		id:id,
		targetcollectiondate:targetcollectiondate},
		type:'post',
        success: function(data) { $('#emissionsaving').append(data); },
		complete: function(data) {  testtimes(); showmessage(); }
});  
  } // times different
} else { // status too high
allok=0;
message=statustoohigh;
showmessage();
$('#targetcollectiondate').val(initialtargetcollectiondate);
}

	}
 
 