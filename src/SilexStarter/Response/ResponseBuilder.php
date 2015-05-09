<?php

namespace SilexStarter\Response;

use Twig_Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

class ResponseBuilder
{
    protected $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content
     * @param int    $status
     * @param array  $headers
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Generate response based on twig template.
     *
     * @param string $template  the template name / template path
     * @param array  $data      the data needed for rendering the template
     * @param int    $status    the http status
     * @param array  $headers   the response headers
     *
     */
    public function view($template, array $data = [], $status = 200, array $headers = [])
    {
        return new Response($this->twig->render($template.'.twig', $data), $status, $headers);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param string|array $data
     * @param int          $status
     * @param array        $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [])
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Return a new JSONP response from the application.
     *
     * @param string       $callback
     * @param string|array $data
     * @param int          $status
     * @param array        $headers
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [])
    {
        return $this->json($data, $status, $headers)->setCallback($callback);
    }

    /**
     * Return a new streamed response from the application.
     *
     * @param \Closure $callback
     * @param int      $status
     * @param array    $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
    }

    /**
     * Create a new file download response.
     *
     * @param \SplFileInfo|string $file
     * @param string              $name
     * @param array               $headers
     * @param null|string         $disposition
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, $disposition);

        if (!is_null($name)) {
            return $response->setContentDisposition($disposition, $name, str_replace('%', '', Str::ascii($name)));
        }

        return $response;
    }

    /**
     * Return redirect response.
     *
     * @param string $url    New url where user will be redirected
     * @param int    $status HTTP redirect status
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }
}
