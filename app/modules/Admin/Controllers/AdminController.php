<?php

namespace Admin\Controllers;

use View;
use Config;
use Session;

class AdminController{

    public function index(){
        Session::flash('test', 'hi');
        return View::render('@xsanisty-admin/index.twig');
    }

}