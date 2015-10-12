<?php

use SilexStarter\Migration\Migration;

class CreateUserGroupTable extends Migration
{
    /**
     * Run upgrade migration.
     */
    public function up()
    {
        $this->schema->create(
            'users_groups',
            function ($table) {
                $table->integer('user_id')->unsigned();
                $table->integer('group_id')->unsigned();

                // We'll need to ensure that MySQL uses the InnoDB engine to
                // support the indexes, other engines aren't affected.
                $table->engine = 'InnoDB';
                $table->primary(array('user_id', 'group_id'));
            }
        );
    }

    /**
     * Run downgrade migration.
     */
    public function down()
    {
        $this->schema->drop('users_groups');
    }
}
