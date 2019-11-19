<?php
namespace Module\Main\Contracts;

use Illuminate\Database\Eloquent\Model;

interface WithRevision
{
	public function reformatRevision($revision_data=[]);

	public function activateRevision($instance, $revision_data=[]);

}