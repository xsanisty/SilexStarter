<?php

/** The documentation index */
Route::get('/doc', 'Documentation\Controller\PageController:index');
Route::get('/doc/{page}', 'Documentation\Controller\PageController:page')
     ->assert('page', '.*');
