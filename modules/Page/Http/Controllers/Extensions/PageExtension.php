<?php
namespace Module\Page\Http\Controllers\Extensions;

// this trait will extend ALL extension traits to the controllers 
//if you dont need to extend this module, you can remove all the Extensions directory too
trait PageExtension
{
	//you can just manually extend any of extension trait below though
	use PageIndexExtension;
	use PageCrudExtension;
	use PageFormExtension;
	use PageDeleteExtension;
}