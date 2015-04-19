<?php

namespace SilexStarter\Provider;

use Cartalyst\Sentry\Sessions\SessionInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SymfonySessionInterface;

class SentrySymfonySession implements SessionInterface
{
    private $session;
    private $key;

    public function __construct(SymfonySessionInterface $session, $key = null)
    {
        $this->session = $session;
        $this->key = $key ? $key : 'cartalyst_sentry';
    }

    public function getKey()
    {
        return $this->key;
    }

    public function put($value)
    {
        $this->session->set($this->key, $value);
    }

    public function get()
    {
        return $this->session->get($this->key);
    }

    public function forget()
    {
        $this->session->remove($this->key);
    }
}
