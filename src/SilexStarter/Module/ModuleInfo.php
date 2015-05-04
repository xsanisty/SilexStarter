<?php

namespace SilexStarter\Module;

class ModuleInfo
{
    protected $info;
    protected $infoFields = [
        'author_name',
        'author_email',
        'repository',
        'website',
        'name',
        'description',
        'version',
    ];

    public function __construct(array $info)
    {
        foreach ($this->infoFields as $field) {
            $this->info[$field] = isset($info[$field]) ? $info[$field] : null;
        }
    }

    /**
     * info getter, so it possible to access $object->info.
     *
     * @param string $info [description]
     *
     * @return mixed [description]
     */
    public function __get($info)
    {
        if (in_array($info, $this->infoFields)) {
            return $this->info[$info];
        }

        return;
    }

    /**
     * info setter, so it possible to assign value to info using $object->info = value.
     *
     * @param string $info  [description]
     * @param mixeed $value [description]
     */
    public function __set($info, $value)
    {
        if (in_array($info, $this->infoFields)) {
            $this->info[$info] = $value;
        }
    }
}
