<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/jquery.dataTables.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/dataTables.buttons.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/buttons.html5.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/buttons.flash.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/jszip.min.js') !!}"></script>
<script type="text/javascript" src="{!! admin_asset('vendor/jquery-datatable/extensions/export/pdfmake.min.js') !!}"></script>
<script>
var tb_data;
$(function(){
	//fixing bug err 500 on first load
	setTimeout(function(){
		tb_data = $("table.data-table").DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth' : false,
			'searching'	: false,
			'filter'	: false,
			'ajax'		: {
				type : 'POST',
				url	: '{{ $url }}',
				dataType : 'json',
				data : function(data){
					{!! $datatable->searchQuery() !!}
					data._token = window.CSRF_TOKEN
				}
			},
			createdRow: function( row, data, dataIndex ) {
		        // Set the data-status attribute, and add a class
		        $( row ).addClass('close-target');
		    },

			"drawCallback": function(settings) {
				$('[data-init-plugin="switchery"]').each(function() {
					var el = $(this);
					new Switchery(el.get(0), {
						size : el.data("size")
					});
				});
			},
			'columns' : [
				{!! $datatable->columns() !!}
			],
			'columnDefs' : [
				{!! $datatable->orderable() !!}
			],
			"aaSorting": [[0,"desc"]],
		});

		//init datepicker
		$(".datepicker").datetimepicker({
			format : 'YYYY-MM-DD'
		}).on('change blur', function(e){
			tb_data.ajax.reload();
		});

		$("[data-daterangepicker] [date-start], [data-daterangepicker] [date-end]").datetimepicker({
			useCurrent : false,
			format : 'YYYY-MM-DD'
		});

	    $("[data-daterangepicker] [date-start]").on("dp.change", function (e) {
	    	$(this).closest('[data-daterangepicker]').find('[date-end]').data("DateTimePicker").minDate(e.date);
	    	tb_data.ajax.reload();
	    });
	    $("[data-daterangepicker] [date-end]").on("dp.change", function (e) {
	    	$(this).closest('[data-daterangepicker]').find('[date-start]').data("DateTimePicker").maxDate(e.date);
	    	tb_data.ajax.reload();
	    });


	}, 500);


	$(document).on('keyup change', '.searchable input', $.debounce(300, function(){
		tb_data.ajax.reload();
	}));
	$(document).on('change', '.searchable select', function(){
		tb_data.ajax.reload();
	});	


	$(document).on('change', '#checker_all_datatable', function(){
		cond = $(this).is(':checked');
		$(".multichecker_datatable").each(function(){
			$(this).prop('checked', cond);
		});
		toggleBatchMode();
	});

	$(document).on('change', '.multichecker_datatable', function(){
		toggleBatchMode();
	});


	$(".multi-delete").on('click', function(e){
		e.preventDefault();
		output = '<p>Are you sure? Once deleted, you will not be able to recover the data</p><button class="btn btn-primary" data-dismiss="modal">Cancel</button> <button class="btn btn-danger" onclick="runRemoveBatch()">Yes, Delete</button>';
		swal('Run Batch Delete?', [output]);
	});
});

function toggleBatchMode(){
	cond = false;
	$(".multichecker_datatable").each(function(){
		if($(this).is(':checked')){
			cond = true;
		}
	});

	if(cond){
		//toggle down
		$(".batchbox").slideDown();
	}
	else{
		//toggle up
		$(".batchbox").slideUp();
		$("#checker_all_datatable").prop('checked', false);
	}
}

function runRemoveBatch(){
	//prepare selected ids
	ids = [];
	$(".multichecker_datatable").each(function(){
		if($(this).is(':checked')){
			ids.push($(this).attr('data-id'));
		}
	});

	if(ids.length > 0){
		$.ajax({
			url : $(".multi-delete").attr('href'),
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				list_id : ids
			},
			success : function(resp){
				if(resp.type == 'success'){
					swal('success', [resp.message]);
					//refresh datatable
					tb_data.ajax.reload();
					$("#checker_all_datatable").prop('checked', false);
					$(".batchbox").slideUp();
				}
				else{
					swal('error', [resp.message]);
				}
			},
			error : function(resp){
				swal('error', ['Sorry, we cannot process your request now.']);
			}
		});			
	}
	else{
		swal('error', ['No data selected']);
	}


}
</script>