<div class="card card-body">
	<h2 class="my-3">Data Source</h2>
	<p class="lead">For relational data source input type such as select or select_multiple, you can define the options data source by ->dataSource() method in DataStructure instance. This method can receive associative array or DataSource instance as parameter.</p>
	
	<pre class="language-php"><code class="language-php"><?=ctn('datasource_array')?></code></pre>

	<p class="lead">
		For default array data source, you can just insert the associative array as a parameter. The key will be used as data, and the value will used as value label.
	</p>
	<p class="lead">
		If you need data source from another object, you can use <strong>DataSource</strong> instance with model() and options() method. <strong>model()</strong> is fill with model alias of the target object. <strong>options()</strong> first parameter is to grab the value label field from the model, second parameter is the filter condition, and the third parameter is for defined the object primary key (default : id)
	</p>
	<p class="lead">
		If you need data source from custom handler, you can use method <strong>customHandler()</strong> in <strong>DataSource</strong> instance, and create your own logic in closure. Please make sure the Closure will return associative array (key used as data, value used as label).
	</p>

</div>

<div class="card card-body">
	<h2 class="my-3">Value Source</h2>
	<p class="lead">By default, value from one structure is grabbed from its object field in database. If you want to manipulate the value source of the DataStructure, you can insert Closure as parameter with method ->valueData() or ->arraySource().</p>
	<p class="lead">->valueData() is used in single value input, and ->arraySource() is used in multiple values input. For valueData(), just make sure the Closure return the string or integer value, and for arraySource(), make sure the Closure will return the array of value key.</p>

	<pre class="language-php"><code class="language-php"><?=ctn('datasource_value')?></code></pre>

</div>

