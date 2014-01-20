<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Umbrella\SimpleReport\Renderer;

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
    protected $tlp = null;

    public function getTemplate($key = null)
    {
        if ($key) {
            return $this->tlp[$key];
        } else {
            return $this->tlp;
        }
    }

    public function setTemplate($arquivo)
    {
        $this->tlp = $arquivo;
    }

    public function findTemplate()
    {
        try {
            if ($this->tlp) {
                if (!file_exists($this->tlp)) {
                    throw new Exception('Arquivo de template não encontrado');
                }

                $arquivo = fopen($this->tlp, "r");
                $html = "";

                while (!feof($arquivo)) {
                    $html .= fgets($arquivo);
                }

                fclose($arquivo);

                return $html;
            } else {
                throw new Exception('Arquivo de template não foi setado');
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function getTags($key = null)
    {
        if ($key) {
            return $this->tags[$key];
        } else {
            return $this->tags;
        }
    }

    public function setTags($key, $value)
    {
        $this->tags[$key] = $value;
    }

    public function parse()
    {
        $html = $this->findTemplate();

        foreach ($this->getTags() as $_tag => $value) {
            $html = str_replace("{{" . $_tag . "}}", $value, $html);
        }

        return $html;
    }

}
