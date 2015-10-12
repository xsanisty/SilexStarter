<?php

use SilexStarter\Migration\Migration;

class CreateThrottleTable extends Migration
{
    /**
     * Run upgrade migration.
     */
    public function up()
    {
        $this->schema->create(
            'throttle',
            function ($table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable();
                $table->string('ip_address')->nullable();
                $table->integer('attempts')->default(0);
                $table->boolean('suspended')->default(0);
                $table->boolean('banned')->default(0);
                $table->timestamp('last_attempt_at')->nullable();
                $table->timestamp('suspended_at')->nullable();
                $table->timestamp('banned_at')->nullable();

                // We'll need to ensure that MySQL uses the InnoDB engine to
                // support the indexes, other engines aren't affected.
                $table->engine = 'InnoDB';
                $table->index('user_id');
            }
        );
    }

    /**
     * Run downgrade migration.
     */
    public function down()
    {
        $this->schema->drop('throttle');
    }
}
