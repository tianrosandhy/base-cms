<?php
if(!isset($custom_field)){
	$custom_field = [];
}
?>
<table>
	<thead>
		<tr>
			@foreach($skeleton->structure as $structure)
			@if(!$structure->hide_table && !$structure->hide_export && $structure->field <> 'id')
			<th><strong>{{ strlen($structure->name) > 50 ? '' : $structure->name }}</strong></th>
			@endif
			@endforeach

			@foreach($custom_field as $field_name)
			<th><strong>{{ ucfirst($field_name) }}</strong></th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@if(isset($data))
			@foreach($data as $row)
				<?php
				$x = $skeleton->rowFormat($row, true);
				?>
				<tr>
					@foreach($skeleton->structure as $structure)
						@if(!$structure->hide_table && !$structure->hide_export && $structure->field <> 'id')
							<?php $fldname = str_replace('[]', '', $structure->field); ?>
							<td>{{ $x[$fldname] }}</td>
						@endif
					@endforeach

					@foreach($custom_field as $field)
						<td>{{ $x[$field] }}</td>
					@endforeach
				</tr>
			@endforeach
		@endif
	</tbody>
</table>