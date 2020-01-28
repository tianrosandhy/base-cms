<div class="card card-body">
	<div class="container-fluid table-search-filter" style="display:none;">
		<div class="row">
		<?php
		$lp = 0;
		?>
		@foreach($structure as $row)
			@if($row->hide_table == false)
				@if($row->searchable)
				<div class="col-sm-4">
					<div class="form-group custom-form-group searchable">
						<label class="text-uppercase">Search {{ $row->name }}</label>
						<div>
							<?php
							$rfield = str_replace('[]', '', $row->field);
							?>
							@if($row->data_source == 'text')
	                            @if(in_array($row->input_type, ['date', 'datetime']))
								<div class="row" data-daterangepicker>
									<div class="col">
										<input type="text" name="datatable_filter[{{ $rfield }}][]" id="datatable-search-{{ $rfield }}-1" placeholder="Start" date-start class="form-control">
										<div class="closer">&times;</div>
									</div>
									<div class="col" style="border-left:1px solid #ccc;">
										<input type="text" name="datatable_filter[{{ $rfield }}][]" id="datatable-search-{{ $rfield }}-2" placeholder="End" date-end class="form-control">
										<div class="closer">&times;</div>
									</div>
								</div>
								@else
								<div style="position:relative">
									<input type="text" name="datatable_filter[{{ $rfield }}]" id="datatable-search-{{ $rfield }}" placeholder="Search {{ $row->name }}" class="form-control {{ ($row->input_type == 'date' || $row->input_type == 'daterange') ? 'datepicker' : '' }}" <?php 
										//manage data-mask if available
										if($row->input_type == 'date'){
											echo 'data-mask="0000-00-00"';
										}
										if($row->input_type == 'time'){
											echo 'data-mask="00:00"';
										}
										if($row->input_type == 'tel'){
											echo 'data-mask="00000000000000"';
										}
									?>>
									<div class="closer">&times;</div>
								</div>
								@endif
							@else
								@if(isset($row->data_source->output))
									<?php $source = $row->data_source->output; ?>
								@else
									<?php $source = $row->data_source; ?>
								@endif
								<div style="position:relative">
									<select name="datatable_filter[{{ $rfield }}]" id="datatable-search-{{ $rfield }}" class="form-control select2">
										<option value="">Search {{ $row->name }}</option>
										@foreach($source as $ids => $datas)
										<option value="{{ $ids }}">{{ $datas }}</option>
										@endforeach
									</select>
									<div class="closer">&times;</div>
								</div>
							@endif
						</div>
					</div>
				</div>
				<?php
				$lp++;
				if($lp % 3 == 0){
					echo '</div><div class="row">';
				}
				?>
				@endif
			@endif
		@endforeach
		</div>
	</div>

	<div style="position:relative; z-index:2;">
		<a href="#" class="btn btn-secondary btn-sm table-search-btn active"><i class="fa fa-sm fa-search fa-fw"></i> <span>Show</span> Search Box</a>
	</div>

	<div style="overflow-x:scroll; padding:1em 0;">
		<table class="table data-table">
			<thead>
				<tr>
					@foreach($structure as $row)
						@if($row->hide_table == false)
							<th data-field="{{ $row->field }}" data-orderable="{{ $row->orderable }}"  id="datatable-{{ $row->field }}">{!! $row->name !!}</th>
						@endif
					@endforeach
					<th></th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>	
	</div>
</div>
