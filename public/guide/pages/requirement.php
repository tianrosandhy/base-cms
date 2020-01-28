<div class="card card-body">
	<h2 class="my-3">Server Requirement</h2>
	<ul class="lead">
		<li>PHP 7.2 ></li>
		<li>Check Laravel 6.0 requirement</li>
		<li>PHP Extension GD & EXIF for image processing</li>
	</ul>
</div>

<div class="card card-body mt-3">
	<h2 class="my-3">Installation</h2>
	<ul class="lead">
		<li>Run command <strong class="command">composer create-project tianrosandhy/cms</strong> . in your current project directory</li>
		<li>Check your .env configuration. Make sure you give the right database connection, APP_URL must be set as your {base_url}, and the SMTP is in correct value <em>(optional)</em>.</li>
		<li>If the database is empty, you will be automatically redirected to {base_url}/install when access {base_url} in browser.</li>
		<li>Fill the installation form, then after installation succeeded, you will be redirected to {base_url}/p4n3lb04rd. You can change the admin url in config <strong>cms.admin.prefix</strong></li>
	</ul>	
</div>

<a href="#module_create" data-value="module_create" class="mt-3 btn btn-primary doc-ajax-load">Next : Module Management</a>