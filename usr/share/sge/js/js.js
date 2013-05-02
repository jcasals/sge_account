$(document).ready(function() {
	// INIT VALIDATION FORM FOR ADVANCED SEARCH MENU
	$("#form").validate({errorPlacement: function(error,element) {return true;}});

	// DEFINING CTABLE WITH DATATABLES FOR SORTING
	$('#ctable').dataTable( {
        "bInfo": false,
        "iDisplayLength": -1,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
    });
	
	// FROM DATEPICKER
    $("#from").datepicker({
		defaultDate: "+1w",
		dateFormat: "yy-mm-dd", 
		maxDate: '+0',
		onClose: function( selectedDate ) {
			$("#to").datepicker( "option", "minDate", selectedDate );
		}
    });
    
    // TO DATEPICKER
    $("#to").datepicker({
		defaultDate: "+1w",
		dateFormat: "yy-mm-dd", 
		maxDate: '+0',
		onClose: function( selectedDate ) {
			if ($(this).val() != ''){
				$("#from").datepicker( "option", "maxDate", selectedDate );
			}
		}
    });

	// TOOLTIPS
	$(".toolt").tooltip();
	
	// SWITCHING CLASSES BETWEEN NORMAL AND ADVANCED SEARCH MENU
	$('.switch').click(function() {
		$('#advanced').toggle();
		$('#monthly').toggle();
	});
});

// FUNCTION TO PRINT TABLE (AND PIECHART IF SOME COLUMN WAS CLICKED)
function printa()
{
	w = window.open("");
	w.document.write("<style> body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 20px; } table { border-collapse: collapse; width: 100%; font-size: 13px; } table th, table td { border: 1px solid black; padding: 5px; } table td, tfoot th { text-align: right; } .firstcol { text-align: center; } a { text-decoration: none; color: black; } .firstcol a { color: #05C; } #caption { font-size: 10px; } </style>");
	w.document.write("<center><h3>" + $("#title").text() + "</h3></center>");
	w.document.write(document.getElementById('ctable').outerHTML);
	w.document.write("<br><center><i id='caption'>* CPU and Wall times values represented in hours</i></center>");
	w.document.write("<center><h4>" + $("#plottitle").text() + "</h4></center>");
	w.document.write("<center>" + document.getElementById('plot').outerHTML + "</center>");
	w.print();
	w.close();
}