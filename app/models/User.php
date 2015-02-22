<?php

class User extends Cartalyst\Sentry\Users\Eloquent\User {

    public static function boot(){

        parent::boot();

        static::creating(function($model) {
            $model->uuid = Rhumsaa\Uuid\Uuid::uuid4()->__toString();
        });

        static::saving(function($model){
            //die('model saving');
        });

        static::deleting(function($model){
            //die('deleting');
        });
    }
}