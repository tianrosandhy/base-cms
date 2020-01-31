<p align="center"><img src="https://maxsol.id/img/nav-bar-logo.png"></p>

## Documentation now uploaded in public: Please open http://{base_url}/guide

### Requirement
- PHP 7.2 >
- Check Laravel 6.0 requirement
- Extension GD & EXIF for image processing

### Installation
- Run command **composer create-project tianrosandhy/cms .** in your current project directory
- Check your .env configuration. Make sure you give the right database connection, APP_URL must be set as your {base_url}, and the SMTP is in correct value (optional). 
- If the database is empty, you will be automatically redirected to {base_url}/install when access {base_url} in browser.
- Fill the installation form, then after installation succeeded, you will be redirected to {base_url}/p4n3lb04rd. You can change the admin url in config **cms.admin.prefix**
