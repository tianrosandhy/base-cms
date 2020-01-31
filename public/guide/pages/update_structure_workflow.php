<div class="card card-body">
	<h2 class="my-3">Database Update in Staging</h2>

	<p class="lead">The basic knowledge is all database structure should be in migrations file. But how about when you have a filled table, and you want to change the table structure in local or staging or live?</p>
	<p class="lead">The current recommended workflow still need the migrations file to be most updated. To apply the field change in local, staging or live, you must redeclare the changes in <strong>Modules\Main\Console\UpdateStructure.php</strong>.</p>

	<p class="lead">UpdateStructure console will contain all the changes record, and when you run the command <strong class="command">php artisan update:structure</strong> , you will get the latest updated table structure. </p>

	<pre class="language-php"><code class="language-php"><?=ctn('update_structure')?></code></pre>	

</div>