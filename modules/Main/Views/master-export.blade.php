<?php
if(!isset($custom_field)){
	$custom_field = [];
}
?>
<table>
	<thead>
		<tr>
			@foreach($skeleton->structure as $structure)
			@if(!$structure->hide_table && $structure->field <> 'id')
			<th>{{ strlen($structure->name) > 50 ? '' : $structure->name }}</th>
			@endif
			@endforeach

			@foreach($custom_field as $field_name)
			<th>{{ ucfirst($field_name) }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($data as $row)
			<?php
			$x = $skeleton->rowFormat($row, true);
			?>
			<tr>
				@foreach($skeleton->structure as $structure)
					@if(!$structure->hide_table && $structure->field <> 'id')
						<?php $fldname = str_replace('[]', '', $structure->field); ?>
						<td>{{ $x[$fldname] }}</td>
					@endif
				@endforeach

				@foreach($custom_field as $field)
					<td>{{ $x[$field] }}</td>
				@endforeach
			</tr>
		@endforeach
	</tbody>
</table>