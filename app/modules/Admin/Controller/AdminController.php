<?php

namespace Admin\Controller;

use Url;
use View;
use Asset;
use Config;
use Session;
use Sentry;
use Response;
use Request;

class AdminController{

    protected $request;

    public function index(){
        return View::make('@xsanisty-admin/index');
    }

    public function login(){
        return View::make('@xsanisty-admin/login', [
            'message'   => Session::getFlash('message'),
            'email'     => Session::getFlash('email'),
            'remember'  => Session::getFlash('remember'),
        ]);
    }

    public function authenticate(){
        $remember = Request::get('remember', false);
        $email    = Request::get('email');
        $redirect = Request::get('redirect', '/admin');

        try{
            $credential = array(
                'email'     => $email,
                'password'  => Request::get('password')
            );

            // Try to authenticate the user
            $user = Sentry::authenticate($credential, false);

            if($remember){
                Sentry::loginAndRemember($user);
            }else{
                Sentry::login($user, false);
            }

            return Response::redirect(Url::path($redirect));
        }catch(\Exception $e){
            Session::flash('message', $e->getMessage());
            Session::flash('email', $email);
            Session::flash('redirect', $redirect);
            Session::flash('remember', $remember);

            return Response::redirect(Url::to('admin.login'));
        }
    }

    public function logout(){
        Sentry::logout();

        return Response::redirect(Url::to('admin.login'));
    }

}