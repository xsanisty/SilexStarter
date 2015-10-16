<?php

use SilexStarter\Migration\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run upgrade migration.
     */
    public function up()
    {
        $this->schema->create(
            'users',
            function ($table) {
                $table->increments('id');
                $table->integer('company_id');
                $table->string('email');
                $table->string('password');
                $table->string('profile_pic');
                $table->text('permissions')->nullable();
                $table->boolean('activated')->default(0);
                $table->string('activation_code')->nullable();
                $table->timestamp('activated_at')->nullable();
                $table->timestamp('last_login')->nullable();
                $table->string('persist_code')->nullable();
                $table->string('reset_password_code')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->timestamps();

                // We'll need to ensure that MySQL uses the InnoDB engine to
                // support the indexes, other engines aren't affected.
                $table->engine = 'InnoDB';
                $table->unique('email');
                $table->index('activation_code');
                $table->index('reset_password_code');
            }
        );
    }

    /**
     * Run downgrade migration.
     */
    public function down()
    {
        $this->schema->drop('users');
    }
}
