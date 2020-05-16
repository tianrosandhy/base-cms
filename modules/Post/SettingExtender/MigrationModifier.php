<?php
namespace Module\Post\SettingExtender;

use Core\Main\SettingExtension\DatabaseStructureModifier;

class MigrationModifier extends DatabaseStructureModifier
{
	public function handle(){
		/*
		Setelah update migration, langsung list setiap perubahannya (drop / update / add field)
		param 1 : nama tabel
		param 2 : closure utk add/change/drop field
		*/

		// example
		/*
        $this->handleTable('posts', function($table){
            $table->tinyinteger('is_homepage')->nullable();
        });
        */
	}
}