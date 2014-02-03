<?php

/*
 * Copyright 2014 kelsoncm <falecom@kelsoncm.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Umbrella\SimpleReport\Renderer;

use DateTime;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Umbrella\SimpleReport\Api\IDatasource;
use Umbrella\SimpleReport\Api\ITemplate;
use Umbrella\SimpleReport\BaseRenderer;
use Umbrella\SimpleReport\Parser\TemplateParser;

/**
 * Classe utilizada para gerar relatórios em PDF com o wkhtmltopdf
 * @author Ítalo Lelis <italo@voxtecnologia.com.br>
 */
class WkPdfRenderer extends BaseRenderer
{

    /**
     * @var string 
     */
    private $output;

    /**
     * @var HtmlRenderer
     */
    private $htmlRenderer;

    /**
     * @var TemplateParser 
     */
    private $parser;

    /**
     * @var boolean 
     */
    private $isStreaming = false;

    /**
     * Inicializa uma nova instancia da classe WkPdfRenderer
     * @param IDatasource $datasource Uma instância de IDatasource
     * @param ITemplate $template Uma instância de ITemplate
     */
    public function __construct(IDatasource $datasource, ITemplate $template)
    {
        parent::__construct($datasource, $template);
        $this->htmlRenderer = new HtmlRenderer($datasource, $template);
        $this->parser = new TemplateParser($template);
    }

    /**
     * Retorna o 
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
    }

    public function isStreaming()
    {
        return $this->isStreaming;
    }

    public function setStreaming($isStreaming)
    {
        $this->isStreaming = $isStreaming;
        return $this;
    }

    public function render()
    {
        $htmlFile = $this->getHtmlPageContent();

        if ($this->isStreaming()) {
            $response = new StreamedResponse();

            ob_start();
            $self = $this;
            $response->setCallback(function () use($htmlFile, $self) {
                dump('lol');
                $self->renderPdf($htmlFile);
                ob_flush();
                flush();
            });
        } else {
            $response = new \Symfony\Component\HttpFoundation\Response();
            $this->renderPdf($htmlFile);
        }

        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, md5($this->output));
        $response->headers->set('Content-Disposition', $d);
        $response->headers->set('Content-type', 'application/pdf');
        $response->send();

        return $this;
    }

    protected function getHtmlPageContent()
    {
        ob_start();
        $this->htmlRenderer->render();
        $page = ob_get_contents();
        $filename = '/tmp/' . microtime() . '.html';

        $this->parser->setTags(array_merge(array(
            "content" => $page,
            "date" => $this->createDate(),
                        ), $this->template->getTags())
        );
        $content = $this->parser->parse();
        file_put_contents($filename, $content);
        ob_end_clean();

        return $filename;
    }

    protected function createDate()
    {
        $date = new DateTime();
        return $date->format('d/m/Y');
    }

    public function renderPdf($htmlFile)
    {
        \wkhtmltox_convert('pdf', array(
            'out' => $this->output,
            'imageQuality' => '75'
                ), array(
            array(
                'page' => 'file://' . $htmlFile
            ))
        );

        $this->setPermissions($htmlFile, $this->output);
        $this->unlinkFile($htmlFile);
        readfile($this->output);
    }

    /**
     * Seta as permissões nos arquivos de html e pdf
     * @param string $htmlFile
     * @param string $pdfFile
     */
    protected function setPermissions($htmlFile, $pdfFile)
    {
        chmod($htmlFile, 0777);
        chmod($pdfFile, 0777);
    }

    public function unlinkFile($file)
    {
        unlink($file);
    }

}
