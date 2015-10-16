<?php

use SilexStarter\Migration\Migration;

class CreateGroupTable extends Migration
{
    /**
     * Run upgrade migration.
     */
    public function up()
    {
        $this->schema->create(
            'groups',
            function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description');
                $table->text('permissions')->nullable();
                $table->timestamps();

                // We'll need to ensure that MySQL uses the InnoDB engine to
                // support the indexes, other engines aren't affected.
                $table->engine = 'InnoDB';
                $table->unique('name');
            }
        );
    }

    /**
     * Run downgrade migration.
     */
    public function down()
    {
        $this->schema->drop('groups');
    }
}
