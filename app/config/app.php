<?php

return [

    /*
     * Just an environment variable, this can be 'development' or 'production'
     */
    'environment'           => 'development',

    /**
     * Console environment variable
     */
    'console_name'          => 'xpress',
    'console_version'       => '1.0',

    /*
     * When enabled, this will make use the XStatic static proxy as the shortcut to the registered
     * services, to people who hate this :D, better to set this false and access the service directly.
     */
    'enable_static_proxy'   => true,

    /*
     * When enabled, this will make SilexStarter to be modular app, it will seek and activate the registered
     * modules in config/modules.php
     *
     * You can place your module into app/modules directory, or even load it as composer package, as long as
     * it provide the proper ModuleProvider.
     */
    'enable_module'         => true,

    /*
     * When controller as service is enabled, SilexStarter will try to find all avaiable controllers in
     * all registered controller folders including module's controller if enabled, and register it as a
     * service.
     *
     * This may affect performance when you have huge collection of controller, but it enable you to inject
     * dependency at the constructor level. Maybe we need some cache mechanism to improvie this?
     *
     * When disabled, it will fallback into default silex approach using the controller provider / closure callback
     */
    'controller_as_service' => true,

    /*
     * Just debug flag
     */
    'debug'                 => true,

    /*
     * This require the silex webprofiler to be installed, and the service provider registered
     *
     * "require" : { "silex/web-profiler" : "1.*" }
     *
     * Maybe you will also need to clear cache each time this enabled or disabled
     */
    'enable_profiler'       => true,

    /*
     * This will be used by translation service provider as default locale
     */
    'locale'                => 'en_US',

    /*
     * Path information
     */
    'path.root'             => ROOT_PATH,
    'path.vendor'           => VENDOR_PATH,
    'path.app'              => APP_PATH,
    'path.public'           => PUBLIC_PATH,
    'path.module'           => MODULE_PATH,
];
