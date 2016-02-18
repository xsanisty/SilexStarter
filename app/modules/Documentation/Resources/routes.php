<?php

/** The documentation index */
Route::get('/doc', 'Documentation\Controller\PageController:index', ['as' => 'documentation']);
Route::get('/doc/{page}', 'Documentation\Controller\PageController:page', ['assert' => ['page' => '.*']]);
