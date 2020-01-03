<div class="card card-body">
	<h2 class="my-3">Module Skeleton Setup</h2>
	<p class="lead">Skeleton is the core of the module. All basic CRUD is handled here. The skeleton is need to have $route, $model, and $structure[]. You can check the basic skeleton example below : </p>
	<pre class="language-php"><code class="language-php"><?=ctn('skeleton')?></code></pre>
	<p class="lead">The required method in this part is <strong>rowFormat()</strong>. This method will return array, and used to render the datatable in index view. You get the array key from the registered <strong>$structure</strong> in __construct(), and there is additional key "action" to hold the action buttons.</p>
</div>