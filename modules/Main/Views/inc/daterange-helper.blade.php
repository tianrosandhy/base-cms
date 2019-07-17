<div class="row">
	<div class="col-sm-6">
        <input {!! $attr !!} data-start-range placeholder="Start Date" value="{{ isset($oldVal[0]) ? $oldVal['0'] : '' }}" />
	</div>
	<div class="col-sm-6" style="border-left:1px solid #ccc;">
        <input {!! $attr !!} data-end-range placeholder="End Date" value="{{ isset($oldVal[1]) ? $oldVal[1] : '' }}" />
	</div>
</div>
<script>
$(function(){
	$("[data-start-range]").datetimepicker({
		format : 'YYYY-MM-DD',
		useCurrent : false
	});
	$("[data-end-range]").datetimepicker({
		format : 'YYYY-MM-DD',
		useCurrent : false
	});
    $("[data-start-range]").on("dp.change", function (e) {
        $('[data-end-range]').data("DateTimePicker").minDate(e.date);
    });
    $("[data-end-range]").on("dp.change", function (e) {
        $('[data-start-range]').data("DateTimePicker").maxDate(e.date);
    });	
});
</script>