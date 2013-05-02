$(document).ready(function() {
	$('#ctable td').click(function() {
		if (!$(this).hasClass("firstcol"))
		{
			// GET CLICKED COLUMN INDEX
		    var c = parseInt($(this).index()) + 1;
		    var items = [], labels = [];
		    
		    // GETTING PLOT TITLE
		    var title = $(this).closest('table').find('th').eq(this.cellIndex).text();
		    
		    // GETTING GROUP NAMES
		    $('#ctable tbody tr td:nth-child(1)').each( function(){
		        //add label to array
		        labels.push($(this).text());
		    });
		    
		    // GETTING COLUMN VALUES
		    $('#ctable tbody tr td:nth-child(' + c + ')').each( function(){
		        //add item to array
		        items.push(parseFloat($(this).text()));
		    });
		
			// GIVING BOTTOM SQUARE THE SAME WIDTH OF UPPER SQUARE
			$('#wellp').css('width', $('#wellt').width());		

			// EMPTY DIV, FADEIN (IF FIRST TIME CLICK), SCROLL TO THE BOTTOM
		    $('#plot').html("");    
		    $('#plotrow').fadeIn();
		    $("html, body").animate({ scrollTop: $('#plot').offset().top }, 500);
		    
		    // TAKE THE TABLE COLUMN TH AND PRINT ON BOTTOM SQUARE TITLE DIV
		    $('#plottitle').html(title);
		    
		    // INIT PLOT DIV
		    var r = Raphael("plot", 700, 350);
		    
		    // DEFINE PIECHART
		    pie = r.piechart(
	    		220,
	    		180,
	    		160,
	    		items, 
	    		{
	    			"legend": labels
	    		}
	    	);
	    	
	    	// HOVER OVER SLICES EFFECT
	    	pie.hover(function () {
                this.sector.stop();
                //this.sector.scale(1.05, 1.05, this.cx, this.cy);
                this.sector.scale(1.05, 1.05, this.cx, this.cy);

                if (this.label) {
                    this.label[0].stop();
                    this.label[0].attr({ r: 7.5 });
                    this.label[1].attr({ "font-weight": 800 });
                }
            }, function () {
                this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 100, "linear");

                if (this.label) {
                    this.label[0].animate({ r: 5 }, 100, "linear");
                    this.label[1].attr({ "font-weight": 400 });
                }
            });
		}
	});
});