<div class="card card-body">
	<h2>Module Creation</h2>
	
	<div class="alert alert-info">You can easily create new module with basic CRUD table & form with these steps : </div>

	<ul class="lead">
		<li>Run command <strong class="command">php artisan module:create</strong>, then you will be prompted the module name. Type the module name (Ex : Product).</li>
		<li>Then you will be prompted if you want to use the module with dual language support or not. Type "yes" or "no".</li>
		<li>Module scaffold will be created in "modules/{module_name}"</li>
		<li>Register the new module service provider in config <strong>modules.load</strong>. For example, if your module name is "Product", then by default the service provider path will be "\Module\Product\ProductServiceProvider::class"</li>
		<li>Manage the module migration file in modules/{module_name}/Migrations/, then run <strong class="command">php artisan migrate</strong></li>		
	</ul>

</div>