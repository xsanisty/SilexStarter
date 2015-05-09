<?php

namespace Documentation\Controller;

use Response;
use View;

class PageController
{
    public function index()
    {
        return Response::view('@silexstarter-doc/index', [
            'title' => 'SilexStarter Documentation',
            'active' => '',
        ]);
    }

    public function page($page)
    {
        return Response::view('@silexstarter-doc/'.$page, ['active' => $page]);
    }
}
