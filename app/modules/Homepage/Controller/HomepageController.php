<?php

namespace Homepage\Controller;

class HomepageController
{
    public function index(\Silex\Application $app)
    {
        return Response::view('@silexstarter-homepage/index');
    }
}
