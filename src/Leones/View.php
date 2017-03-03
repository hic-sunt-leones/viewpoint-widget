<?php

namespace Leones;

use Psr\Http\Message\ResponseInterface;

/**
 * Class View
 * Adapted View class so we can have a layot
 */
class View extends \Slim\Views\PhpRenderer
{
    protected $layout;

    protected $data = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render(ResponseInterface $response, $template, array $data = [])
    {
        $data = array_merge($this->data, $data);

        if ($this->layout){
            $viewOutput = $this->fetch($template, $data);
            $layoutOutput = $this->fetch($this->layout, array('content' => $viewOutput));
            $response->getBody()->write($layoutOutput);
        } else {
            $output = parent::render($response, $template, $data);
            $response->getBody()->write($output);
        }
        return $response;
    }

    /**
     * Add variables to the View
     * The last variables you put in, will be used and overwrite others with the same key
     *
     * @param array $data
     */
    public function addToView(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

}
