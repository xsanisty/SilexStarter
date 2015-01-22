<?php

namespace Admin\Controllers;

use View;

class AdminController{

    public function index(){
        return View::render('@xsanisty-admin/index.twig');
    }

}