<div class="card card-body">
	<h2 class="my-3">DataStructure Basic</h2>
	<p class="lead"><strong>DataStructure</strong> is a class that will be used in skeleton to define the table & form. You need to define the <strong>field</strong> and <strong>name</strong>. </p>

	<pre class="language-php"><code class="language-php"><?=ctn('datastructure_basic')?></code></pre>

	<p class="lead">
		<strong>field</strong> is used in table view as row key & database field when saved/updated. If the field is multiple values, then the field name must use bracket (ex : "doctors[]") 
		<br><strong>name</strong> is just used as a label in table view and form view. Any method other than this is optional for customizing the module purpose.
	</p>
</div>

<div class="card card-body">
	<h3 class="my-3">Basic Additional Method</h3>
	<table class="table">
		<thead>
			<tr>
				<th>Method</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>inputType($var)</td>
				<td>Define the form input type. (<a href="#datastructure_inputtype" data-value="datastructure_inputtype" class="doc-ajax-load">please check here for more detailed documentation</a>)</td>
			</tr>
			<tr>
				<td>hideForm()</td>
				<td>Hide the structure in Form View. (doesnt affect the table view)</td>
			</tr>
			<tr>
				<td>hideTable()</td>
				<td>hide the field in Table View. (doesnt affect the form view)</td>
			</tr>
			<tr>
				<td>formColumn(int 1-12)</td>
				<td>Set the field column length (based on bootstrap 12 column). <br>Default : 12</td>
			</tr>
			<tr>
				<td>orderable(bool)</td>
				<td>If set to false, then the field is cannot be ordered in table view</td>
			</tr>
			<tr>
				<td>searchable(bool)</td>
				<td>If set to false, then the field search box will be hidden</td>
			</tr>
			<tr>
				<td>createValidation($rule_string)</td>
				<td>Set the validation rule for the field. Will be validated when saved</td>
			</tr>
			<tr>
				<td>updateValidation($rule_string)</td>
				<td>Set the validation rule for the field. Will be validated when updated</td>
			</tr>
			<tr>
				<td>dataSource(DataSource $obj)</td>
				<td>DataSource instance for relational field <a href="#datastructure_datasource" data-value="datastructure_datasource" class="doc-ajax-load">Check Data & Value Source documentation</a></td>
			</tr>
			<tr>
				<td>inputAttribute()</td>
				<td>Give additional attribute to the input in form view</td>
			</tr>

		</tbody>
	</table>
</div>

