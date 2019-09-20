<?php
if(!isset($end_attr)){
	$end_attr = $attr;
}
?>
<div class="row">
	<div class="col-sm-6">
        <input autocomplete="off" {!! $attr !!} {{ isset($monthly) ? 'data-month-start-range' : 'data-start-range' }} placeholder="Start Date" value="{{ isset($oldVal[0]) ? $oldVal['0'] : '' }}" />
	</div>
	<div class="col-sm-6" style="border-left:1px solid #ccc;">
        <input autocomplete="off" {!! $end_attr !!} {{ isset($monthly) ? 'data-month-end-range' : 'data-end-range' }} placeholder="End Date" value="{{ isset($oldVal[1]) ? $oldVal[1] : '' }}" />
	</div>
</div>
@push ('script')
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


	$("[data-month-start-range]").datetimepicker({
		format : 'MMM YYYY',
		useCurrent : false
	});
	$("[data-month-end-range]").datetimepicker({
		format : 'MMM YYYY',
		useCurrent : false
	});
    $("[data-month-start-range]").on("dp.change", function (e) {
        $('[data-month-end-range]').data("DateTimePicker").minDate(e.date);
    });
    $("[data-month-end-range]").on("dp.change", function (e) {
        $('[data-month-start-range]').data("DateTimePicker").maxDate(e.date);
    });	
});
</script>
@endpush