<?php

namespace SilexStarter\Menu;

class MenuItem{

    protected $attributes   = [];
    protected $fields       = ['url', 'label', 'icon', 'active'];
    protected $child        = null;

    public function __construct(array $attributes){

    }
}