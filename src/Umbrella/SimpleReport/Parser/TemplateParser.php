<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Umbrella\SimpleReport\Parser;

use Exception;

/**
 * Description of TemplateRenderer
 *
 * @author Ítalo Lelis <italo@voxtecnologia.com.br>
 * @author Valter <valter@voxtecnologia.com.br>
 */
class TemplateParser
{

    protected $tags = array();
    protected $template = null;

    public function __construct(\Umbrella\SimpleReport\Api\ITemplate $template)
    {
        $this->template = $template;
    }

    public function setTemplate(\Umbrella\SimpleReport\Api\ITemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($values)
    {
        foreach ($values as $key => $value) {
            $this->addTag($key, $value);
        }
        return $this;
    }

    public function addTag($key, $value)
    {
        $this->tags[$key] = $value;
        return $this;
    }

    public function removeTag($key)
    {
        if (!isset($key)) {
            return null;
        }
        unset($this->tags[$key]);
    }

    protected function findTemplate()
    {
        $file = $this->template->getPath();
        if (!file_exists($file)) {
            throw new Exception('Arquivo de template não encontrado');
        }
        return file_get_contents($file);
    }

    public function parse()
    {
        $html = $this->findTemplate();

        foreach ($this->tags as $tag => $value) {
            $html = str_replace("{{" . $tag . "}}", $value, $html);
        }

        return $html;
    }

}
