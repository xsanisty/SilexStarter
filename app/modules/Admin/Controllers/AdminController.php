<?php

namespace Admin\Controllers;

use View;
use Config;
use Session;

class AdminController{

    public function index(){
        dd(Config::get('xsanisty-admin::sample'));
        Session::flash('test', 'hi');
        return View::render('@xsanisty-admin/index.twig');
    }

}