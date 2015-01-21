<?php

namespace Umbrella\SimpleReport;


use Easy\Collections\Dictionary;
use Easy\Collections\MapInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Umbrella\SimpleReport\Api\DatasourceInterface;
use Umbrella\SimpleReport\Api\FieldSet;
use Umbrella\SimpleReport\Api\RendererInterface;
use Umbrella\SimpleReport\Renderer\HtmlRenderer;

class SimpleReport
{

    /**
     * @var Twig_Environment
     */
    protected $renderer;

    /**
     * @var RendererInterface
     */
    protected $tableRenderer;

    public function __construct(RendererInterface $tableRenderer, Dictionary $options = null)
    {
        $this->tableRenderer = $tableRenderer;
        $loader = new Twig_Loader_Filesystem($options->get('paths')->toArray());
        $this->renderer = $twig = new Twig_Environment($loader, $options->toArray());
    }

    /**
     * @return Twig_Environment
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param Twig_Environment $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }


    public function addGlobal($name, $value)
    {
        $this->renderer->addGlobal($name, $value);
        return $this;
    }

    public function addGlobals($vars)
    {
        foreach ($vars as $name => $value) {
            $this->addGlobal($name, $value);
        }
        return $this;
    }

    public function render($view, array $context = array())
    {
        $this->tableRenderer->render();
        $table = $this->tableRenderer->getStringBuffer();

        return $this->renderer->render($view, array_merge(array(
            'table' => $table
        ), $context));
    }

}