<?php

use SilexStarter\Migration\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run upgrade migration.
     */
    public function up()
    {
        $this->schema->create(
            'permissions',
            function ($table) {
                $table->increments('id');
                $table->string('name', 100)->unique();
                $table->string('category', 100);
                $table->text('description');
            }
        );
    }

    /**
     * Run downgrade migration.
     */
    public function down()
    {
        $this->schema->drop('permissions');
    }
}
