<div class="card card-body">
	<h2 class="my-3">Module Config Setup</h2>
	<p class="lead">Each dynamic module has their own config. By default there is 4 config : cms.php, model.php, module-setting.php, and permission.php</p>
</div>

<div class="card mt-3">
	<div class="card-header">
		1. cms.php
	</div>
	<div class="card-body">
		<p class="lead">cms.php config will control the CMS sidebar navigation. you can define sub menu up to 2 more level by creating "submenu" array. By default, if the navigation item has submenu, then the "url" or "route" parameter will be ignored. Please check the format example below</p>

		<pre class="language-php"><code class="language-php"><?=ctn('config_cms')?></code></pre>
	</div>
</div>

<div class="card mt-3">
	<div class="card-header">
		2. model.php
	</div>
	<div class="card-body">
		<p class="lead">Config model.php will register the model alias that you create in that module. You can register the model with alias in this config, so you can access the model easily with <strong>app(config('model.alias'))</strong> or <strong>new CrudRepository('alias')</strong> in your app</p>

		<pre class="language-php"><code class="language-php"><?=ctn('config_model')?></code></pre>
	</div>
</div>

<div class="card mt-3">
	<div class="card-header">
		3. module-setting.php
	</div>
	<div class="card-body">
		<p class="lead">Config module-setting is contain the default text setting, and view name used in index, create, and edit page. By default, all module will use "main::master-table" view in index page, and "main::master-crud" view in create/edit page. If you need to customize the view target in that method, you can change the value in this config. <br><em><small>In much case, I usually just override the index() or edit() method rather than only change the view target</small></em></p>

		<pre class="language-php"><code class="language-php"><?=ctn('config_model')?></code></pre>
	</div>
</div>


<div class="card mt-3">
	<div class="card-header">
		4. permission.php
	</div>
	<div class="card-body">
		<p class="lead">Config permission is contain the list of route that can have dynamic permission management. The format is fixed as 3 level array (Module -> Group Name -> list of permission). Please check the example below :</p>

		<pre class="language-php"><code class="language-php"><?=ctn('config_permission')?></code></pre>

		<p class="lead">the permission lists will be shown in CMS in User Managements -> Priviledge -> Manage Permission. By default, you dont need to specify the super admin priviledge list, because SA can access anything.</p>
	</div>
</div>


