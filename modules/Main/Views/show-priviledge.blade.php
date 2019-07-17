<form action="{{ admin_url('setting/save-permission/'.$id) }}" method="post">
	{{ csrf_field() }}
	<table class="table">
		<thead>
			<tr>
				<th>Group</th>
				<th>Title</th>
				<th>Priviledge Item</th>
			</tr>
		</thead>
		<tbody>
			@foreach($all as $group => $data)
				@foreach($data as $title => $list)
				<tr>
					<td>{!! $loop->iteration == 1 ? '<strong>'.$group.'</strong>' : '' !!}</td>
					<td><label>
						<input type="checkbox" class="group-checkbox">
						{{ $title }}
					</label></td>
					<td>
						@foreach($list as $item)
						<div class="priviledge-check">
							<label><input type="checkbox" name="check[]" value="{{ $item }}" {{ in_array($item, $checked) ? 'checked' : '' }}><?php
								//penamaan yg dimunculkan ambil explode terakhir aja
								$exp = explode('.', $item);
								$nm = $exp[(count($exp)-1)];
								echo $nm;
							?></label>
						</div>
						@endforeach
					</td>
				</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>
	<div class="padd">
		<button class="btn btn-primary">Save Permission Data</button>
	</div>
</form>
