<p align="center"><img src="https://maxsol.id/img/nav-bar-logo.png"></p>


### Requirement
- PHP 7.2 >
- Check Laravel 6.0 requirement
- Extension GD & EXIF for image processing

#### Installation
- Run command **composer create-project tianrosandhy/cms .** in your current project directory
- Check your .env configuration. Make sure you give the right database connection, APP_URL must be set as your {base_url}, and the SMTP is in correct value (optional). 
- If the database is empty, you will be automatically redirected to {base_url}/install when access {base_url} in browser.
- Fill the installation form, then after installation succeeded, you will be redirected to {base_url}/p4n3lb04rd. You can change the admin url in config **cms.admin.prefix**

### Module Creation
- Run command **php artisan module:create**, then you will be prompted the module name. Type the module name (Ex : Product).  
- Then you will be prompted if you want to use the module with dual language support or not. Type "yes" or "no".
- Module scaffold will be created in "modules/{module_name}"
- Register the new module service provider in config **modules.load**. For example, if your module name is "Product", then by default the service provider path will be "\Module\Product\ProductServiceProvider::class"
- Manage the module migration file in **modules/{module_name}/Migrations/**, then run **php artisan migrate**

### Module Management
#### Config
By default, module will have 4 default config : cms, model, module-setting, and permission.

Config **cms** will control the module sidebar navigation.

```php
return [
  'admin' => [
    'menu'  => [
      'ModuleName' => [
        'route' => 'admin.modulename.index',
        'icon' => 'fa fa-check-circle',
        'sort' => 0
      ],
    ],
  ]
];
```
you can define sub menu up to 2 more level by creating "submenu" array. By default, if the navigation item has submenu, then the "url" or "route" parameter will be ignored. Please check the format example below
```php
return [
  'admin' => [
    'menu'  => [
      'ModuleName' => [
        'route' => 'admin.modulename.index',
        'icon' => 'fa fa-check-circle',
        'sort' => 0,
        'submenu' => [
          'Submenu' => [
            'route' => 'route.name'
          ],
          'Submenu 2' => [
            'url' => 'url_example',
          ],
          'Submenu 3' => [
            'submenu' => [
              'Sub Child' => [
                'route' => 'sub.route.name.'
              ],
              'Sub Child Again' => [
                'url' => 'url_example'
              ]
            ]
          ]
        ]
      ],
    ],
  ]
];
```

Config **model** will register the model alias that you create. If you create the new model in the module, you can register the model with alias in this config, so you can access the model easily with **app(config('model.alias'))** or **new CrudRepository('alias')** 
```php
<?php
return [
	'modulename' => 'Module\ModuleName\Models\ModuleName',
	'another' => 'Module\ModuleName\Models\Another',
];
```

Config **module-setting** is contain the default text setting, and view name used in index, create, and edit page. By default, all module will use "main::master-table" view in index page, and "main::master-crud" view in create/edit page. If you need to customize the view in that method, you can change the value in this config. *In much case, I usually just override the index() or edit() method rather than only change the view*

Config **permission** is contain the list of route that can have dynamic permission management. The format is Module -> Group Name -> list of permission. Please check the example below : 
```php
<?php
//combine permission data
return [
  'Module Name' => [
    'Post Data' => [
      'admin.post.index',
      'admin.post.store',
      'admin.post.update',
      'admin.post.delete',
      'admin.post.switch'
    ],
    'Comments' => [
      'admin.post_comment.index',
      'admin.post_comment.store',
      'admin.post_comment.update',
      'admin.post_comment.delete',
      'admin.post_comment.switch'
    ],
    'Category' => [
      'admin.category.index',
      'admin.category.store',
      'admin.category.update',
      'admin.category.delete',
      'admin.category.switch'
    ],
  ],
];
```
the permission lists will be shown in CMS in User Managements -> Priviledge -> Manage Permission. By default, you dont need to specify the super admin priviledge list, because SA can access anything. 

#### Skeleton
This is the core of your module. By generate the right skeleton, you will have the default index(), create(), and edit() page easily.

First, you need to define the skeleton **$structure (array)** in __construct(). the structure list will be use **DataStructure** instance (source : Module\Main\Services\DataStructure)

The method required in DataStructure is : **field()** to define the field name in database, **name()** to define the shown name. By default all defined structured here will be shown in index table view, index search field, and CRUD Form. 
- If you want to hide the structure in index table, you can use method **hideTable()** 
- if you want to hide the structure in form you can use method **hideForm()**
- If you want to hide the structure from search fields in index, you can use method **searchable(false)**
- If you want to disable the orderable field in index table, you can use method **orderable(false)**

*Note : The full documentation of DataStructure will be update later (still need much improvement). For now, just check the Skeleton of base module for reference.*

After you define the structure lists in __construct(), next you must define the **rowFormat()** that will be used in table view. This method will return array, and all the structure *field* name must be exists as key in this method. The must exists return in this method is  "action" to hold the action buttons.



#### Controller
 By default, you dont need much override in controller if the module is just some easy CRUD Module. The only override you need in controller is just **afterValidation()** and **afterCrud()**. 
 **afterValidation()** is called in store & update method. By default the validation is defined in skeleton. But, if you need additional validation, you can run them here.
 **afterCrud()** is called in store & update too. By default, the data saved is defined in skeleton. But if you need to store additional data in table or another table, you can run them here.

#### View Override
You can add components in the default main::master-table and main::master-crud view.
- yourmodule::partials.index.before-table used before the table view in index()
- yourmodule::partials.index.after-table used after the table view in index()
- yourmodule::partials.index.control-button used in control button in index()
- yourmodule::partials.crud.before-form used before the first form component in crud
- yourmodule::partials.crud.after-form used after the last form component in crud




#### DataStructure Components
(update later)

#### Helper
(update later)

#### Database Update in Staging
(update later) 

