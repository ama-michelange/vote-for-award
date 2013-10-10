// 
$(document).ready(function() {
	$("[rel=tooltip]").tooltip({
		'placement' : 'top',
		'trigger' : 'hover',
		'delay' : {
			'show' : 500,
			'hide' : 100
		}
	});
	$("[data-rel=popover]").popover({
		'placement' : 'auto left',
		'trigger' : 'hover',
		'delay' : {
			'show' : 500,
			'hide' : 200
		}
	});

	$(".datepicker").datepicker({
		//'format' : 'yyyy-mm-dd',
		'format' : 'dd/mm/yyyy',
		'weekStart' : 1
	});

	$("#myModal").on('shown', function() {
		$('input:text:visible:first', this).focus();
	});
	
	$("select").select2({
		// width:"off"
	});
	
});