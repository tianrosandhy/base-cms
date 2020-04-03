<?php
namespace Module\Post\Http\Controllers\Extensions;

// this trait will extend ALL extension traits to the controllers 
//if you dont need to extend this module, you can remove all the Extensions directory too
trait PostExtension
{
	//you can just manually extend any of extension trait below though
	use PostIndexExtension;
	use PostCrudExtension;
	use PostFormExtension;
	use PostDeleteExtension;
}