<?php

namespace SilexStarter\Menu;

class MenuItem{

    /**
     * Menu attributes as listed in the $fields
     * @var array
     */
    protected $attributes = [];

    /**
     * Addittional attribute container
     * @var array
     */
    protected $metaAttributes = [];

    /**
     * Available fields, if user try to set attribute not listed in this fields, it will be discarded
     * @var array
     */
    protected $fields = ['url', 'label', 'icon', 'class', 'id', 'name'];

    /**
     * The children menu container
     * @var null|SilexStarter\Menu\ChildMenuContainer
     */
    protected $child = null;

    /**
     * Is menu active
     * @var boolean
     */
    protected $active = false;

    /**
     * Current menu level
     * @var integer
     */
    protected $level = 0;


    public function __construct(array $attributes){
        foreach ($this->fields as $field) {
            $this->attributes[$field] = (isset($attributes[$field])) ? $attributes[$field] : null;
        }

        if(isset($attributes['meta'])){
            $this->metaAttributes = $attributes['meta'];
        }

        $this->child = new ChildMenuContainer($this);
        $this->child->setLevel($this->level+1);
    }

    /**
     * Set the menu attributes
     * @param string $name  the attribute name
     * @param string $value the attribute value
     */
    public function setAttribute($name, $value){
        if(in_array($name, $this->fields)){
            $this->attributes[$name] = $value;
        }
    }

    /**
     * Get the attibute value
     * @param  string $name the attribute name
     * @return mixed        the attribute value
     */
    public function getAttribute($name){
        if(in_array($name, $this->fields)){
            return $this->attributes[$name];
        }
    }

    /**
     * Check if the menu is active
     * @return boolean
     */
    public function isActive(){
        return $this->active;
    }

    /**
     * Set the active state of the menu
     * @param boolean $active
     */
    public function setActive($active = true){
        $this->active = $active;
    }

    public function hasActiveChildren(){

    }

    public function hasChildren(){
        return $this->child->hasItem();
    }

    public function getChildren(){
        return $this->child;
    }

    public function getLevel(){
        return $this->level;
    }

    public function setLevel($level){
        $this->level = $level;
        $this->child->setLevel($level+1);
    }

    public function __get($name){
        return $this->getAttribute($name);
    }

    public function __set($name, $value){
        return $this->setAttribute($name, $value);
    }
}