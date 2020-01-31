<div class="card card-body">
	<h2 class="my-3">Controller Override</h2>
	<p class="lead">By default, you dont need much override in controller if the module is just some easy CRUD Module. The only override you need in controller is just <strong>afterValidation()</strong> and <strong>afterCrud()</strong>.
	</p>
	<p class="lead"><strong>afterValidation()</strong> is called in store & update method. By default the validation is defined in skeleton. But, if you need additional validation in your current module, you can run them here. <strong>afterCrud()</strong> is called in store & update too. By default, the data saved is defined in skeleton. But if you need to store additional data in table or another table, you can run them here.</p>
</div>

<div class="card card-body mt-3">
	<h2 class="my-3">View Append & Prepend</h2>
	
	<p class="lead">
		You can add components in the default main::master-table and main::master-crud view.		
	</p>
	<ul>
		<li><strong>yourmodule::partials.index.before-table</strong> used before the table view in index()</li>
		<li><strong>yourmodule::partials.index.after-table</strong> used after the table view in index()</li>
		<li><strong>yourmodule::partials.index.control-button</strong> used in control button in index()</li>
		<li><strong>yourmodule::partials.crud.before-form</strong> used before the first form component in crud</li>
		<li><strong>yourmodule::partials.crud.after-form</strong> used after the last form component in crud</li>
	</ul>
</div>