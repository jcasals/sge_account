$(document).ready(function() {
	$("#form").validate({errorPlacement: function(error,element) {return true;}});

	$('#ctable').dataTable( {
        "bInfo": false,
        "iDisplayLength": -1,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
    });
	
    /*$("#from").datepicker({
		defaultDate: "+1w",
		dateFormat: "yy-mm-dd", 
		onClose: function( selectedDate ) {
			$("#to").datepicker( "option", "minDate", selectedDate );
		}
    });
    
    $("#to").datepicker({
		defaultDate: "+1w",
		dateFormat: "yy-mm-dd", 
		onClose: function( selectedDate ) {
			$("#from").datepicker( "option", "maxDate", selectedDate );
		}
    });*/
    
    var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	 
	var checkin = $('#from').datepicker({
		format: "yyyy-mm-dd",
		onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			checkout.setValue(newDate);
		}
		checkin.hide();
		//$('#to')[0].focus();
	}).data('datepicker');
	
	var checkout = $('#to').datepicker({
		format: "yyyy-mm-dd",
	  	onRender: function(date) {
	    	return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
	  	}
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');

	$(".toolt").tooltip();
	
	$('#showadv').click(function() {
		$('#advanced').show();
		$('#monthly').hide();
	});
	
	$('#showmon').click(function() {
		$('#advanced').hide();
		$('#monthly').show();
	});
	
	/*$('td').hover(function() {
	    var t = parseInt($(this).index()) + 1;
	    $('td:nth-child(' + t + ')').addClass('hovercol');
	},
	
	function() {
	    var t = parseInt($(this).index()) + 1;
	    $('td:nth-child(' + t + ')').removeClass('hovercol');
	});
	
	$('#ctable td').click(function() {
		if (!$(this).hasClass("firstcol"))
		{
			var c = parseInt($(this).index()) + 1;
			
			alert($('#ctable td:nth-child(1)').map(function(){
			    return $(this).text();
			}).get());
			
			alert($('#ctable td:nth-child(' + c + ')').map(function(){
			    return $(this).text();
			}).get());
		}
	});*/
});
