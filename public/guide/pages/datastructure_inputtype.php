<div class="card card-body">
	<h2 class="my-3">DataStructure Input Type</h2>
	<p class="lead">Input Type will be used mostly in form view, and partially will affect the table view in search box.</p>

	<strong class="d-block mt-5">Normal Input Type</strong>
	<table class="table">
		<thead>
			<tr>
				<th>Input Type</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>text</td>
				<td>Default text input <span class="badge badge-primary">(default)</span></td>
			</tr>
			<tr>
				<td>number</td>
				<td>Receive only numeric value</td>
			</tr>
			<tr>
				<td>email</td>
				<td>For email value</td>
			</tr>
			<tr>
				<td>tel</td>
				<td>For phone number</td>
			</tr>
			<tr>
				<td>password</td>
				<td>For password<br>
					<small>Note : dont forget to give additional ->hideTable(), so password wont show in table view</small>
				</td>
			</tr>
			<tr>
				<td>radio</td>
				<td><small>Note : you must use DataSource for this input type</small></td>
			</tr>
			<tr>
				<td>checkbox</td>
				<td><small>Note : you must use DataSource for this input type</small></td>
			</tr>
			<tr>
				<td>textarea</td>
				<td>Normal textarea field</td>
			</tr>
			<tr>
				<td>color</td>
				<td>HTML5 Color Input Type</td>
			</tr>
			<tr>
				<td>select</td>
				<td>Dropdown input with single value. <br><small>Note : You need to use this with ->dataSource()</small></td>
			</tr>
			<tr>
				<td>select_multiple</td>
				<td>Dropdown input with multiple value. <br><small>Note : You need to use this with ->dataSource()</small><br><small><span style="color:#fff;">Note :</span> Will return array when saved, make sure the field name has bracket (Ex : "doctors[]")</small></td>
			</tr>


		</tbody>
	</table>

	<strong class="d-block mt-5">Custom Input Type</strong>
	<table class="table">
		<thead>
			<tr>
				<th>Input Type</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>tags</td>
				<td>Use bootstrap tagsinput as form input</td>
			</tr>
			<tr>
				<td>slug</td>
				<td>For autoslug field. <br><small>Note : you need to call ->slugTarget('field_name') to get the slug source</small></td>
			</tr>
			<tr>
				<td>richtext</td>
				<td>WYSIWYG field (tinymce)</td>
			</tr>
			<tr>
				<td>gutenberg</td>
				<td>Gutenberg WYSIWYG</td>
			</tr>
			<tr>
				<td>date</td>
				<td>Will use bootstrap datepicker</td>
			</tr>
			<tr>
				<td>time</td>
				<td>Will use timepicker input</td>
			</tr>
			<tr>
				<td>datetime</td>
				<td>Will use bootstrap datetimepicker</td>
			</tr>

			<tr>
				<td>daterange</td>
				<td>2 input for start date and end date. <br><small>Note : will return array when saved, so make sure the field name has bracket (Ex : "date[]")</small></td>
			</tr>
			<tr>
				<td>image</td>
				<td>Input type image with dropzone</td>
			</tr>
			<tr>
				<td>image_multiple</td>
				<td>Input type image with dropzone. Image data stored in concatenated format (image/path1|image/path2|image/path3|...)</td>
			</tr>
			<tr>
				<td>file</td>
				<td>Input type file with dropzone</td>
			</tr>
			<tr>
				<td>file_multiple</td>
				<td>Input type file with dropzone. File data stored in concatenated format (file/path1|file/path2|file/path3|...)</td>
			</tr>
			<tr>
				<td>view</td>
				<td>Create your own custom input HTML. You need to define the view source with ->viewSource('view_path') in DataStructure <span class="badge badge-warning">Real Custom</span></td>
			</tr>			
		</tbody>
	</table>

</div>