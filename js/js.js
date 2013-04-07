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
		dateFormat: "yy-mm-dd", 
		//maxDate: '-1',
		maxDate: '+0',
		//minDate: new Date(2012,12 - 1,24),
	});

	$("#to").datepicker({ 
		dateFormat: "yy-mm-dd", 
		//maxDate: '-1',
		maxDate: '+0',
		//minDate: new Date(2013,1 - 1,21),
	});*/
	
    $("#from").datepicker({
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
    });

	$(".toolt").tooltip();
	
	$('#showadv').click(function() {
		$('#advanced').show();
		$('#monthly').hide();
	});
	
	$('#showmon').click(function() {
		$('#advanced').hide();
		$('#monthly').show();
	});
});
