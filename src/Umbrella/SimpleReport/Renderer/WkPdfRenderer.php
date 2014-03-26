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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Umbrella\SimpleReport\Api\IDatasource;
use Umbrella\SimpleReport\Api\IRenderer;
use Umbrella\SimpleReport\Api\ITemplate;
use Umbrella\SimpleReport\Parser\TemplateParser;

/**
 * Classe utilizada para gerar relatórios em PDF com o wkhtmltopdf
 * @author Ítalo Lelis <italo@voxtecnologia.com.br>
 */
class WkPdfRenderer implements IRenderer
{

    /**
     * @var string 
     */
    private $output;

    /**
     * @var ITemplate
     */
    private $template;

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
     * @var int 
     */
    private $length = 0;

    /**
     * @var boolean 
     */
    private $useFooter = false;

    /**
     * @var string 
     */
    private $footerPathTemplate;

    /**
     * @var string 
     */
    private $footerPath;

    /**
     * @var string 
     */
    private $footerHtmlUrl;

    /**
     * @var array 
     */
    private $footerText = array();

    /**
     * Inicializa uma nova instancia da classe WkPdfRenderer
     * @param IDatasource $datasource Uma instância de IDatasource
     * @param ITemplate $template Uma instância de ITemplate
     */
    public function __construct(IDatasource $datasource, ITemplate $template, IHtmlRenderer $htmlRenderer)
    {
        $this->template = $template;
        $this->htmlRenderer = $htmlRenderer;
        $this->parser = new TemplateParser($template);
    }

    public function getUseFooter()
    {
        return $this->useFooter;
    }

    public function getFooterPathTemplate()
    {
        return $this->footerPathTemplate;
    }

    public function getFooterPath()
    {
        return $this->footerPath;
    }

    public function getFooterHtmlUrl()
    {
        return $this->footerHtmlUrl;
    }

    public function getFooterText()
    {
        return $this->footerText;
    }

    public function setUseFooter($useFooter)
    {
        $this->useFooter = $useFooter;
        return $this;
    }

    public function setFooterPathTemplate($footerPathTemplate)
    {
        $this->footerPathTemplate = $footerPathTemplate;
        return $this;
    }

    public function setFooterPath($footerPath)
    {
        $this->footerPath = $footerPath;
        return $this;
    }

    public function setFooterHtmlUrl($footerHtmlUrl)
    {
        $this->footerHtmlUrl = $footerHtmlUrl;
        return $this;
    }

    public function setFooterText($footerText)
    {
        $this->footerText = $footerText;
        return $this;
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

            $response->setCallback(function () use($htmlFile, $self, &$lenght) {
                $self->renderPdf($htmlFile);
                ob_flush();
                flush();
            });
        } else {
            $response = new Response();
            $this->renderPdf($htmlFile);
        }

        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, md5($this->output));
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Cache-Control', 'max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');
//        ini_set('zlib.output_compression', '0');

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
            array_merge(array(
                'page' => "file://{$htmlFile}",
                    ), $this->getFooter())
        ));

        $this->setPermissions($htmlFile, $this->output);
        $this->unlinkFile($htmlFile);
        $this->unlinkFile($this->footerPath);

        $this->length = readfile($this->output);
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
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * adiciona a propriedade do footer
     */
    private function getFooter()
    {
        if ($this->useFooter) {
            $text = implode('<br />', $this->footerText);
            fopen($this->footerPath, 'a');
            chmod($this->footerPath, 0755);
            $text = preg_replace(
                    array('#\$\{TEXTO\}#i', '#\$\{([^\}]+)\}#ie'), array($text, "'<span class=\"'.strtolower('$1').'\">'.strtolower('$1').'</span>'"), $this->appendScript(file_get_contents($this->footerPathTemplate))
            );
            file_put_contents($this->footerPath, $text);
            return array('footer.htmlUrl' => $this->footerHtmlUrl);
        }
        return array();
    }

    /**
     * Adiciona o script para gerar as variáveis dinamicas
     * 
     * @param string $text
     * @return mixed
     */
    private function appendScript($text)
    {
        $script = '<script>function subst() {var vars={};var x=document.location.search.substring(1).split(\'&\');';
        $script .= 'for(var i in x) {var z=x[i].split(\'=\',2);vars[z[0]] = unescape(z[1]);}var x=[\'frompage\',\'topage\',\'page\',';
        $script .= '\'webpage\',\'section\',\'subsection\',\'subsubsection\']; for(var i in x) { var y = document.getElementsByClassName(x[i]);';
        $script .= 'for(var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];}}</script></head><body onload="subst()">';
        return preg_replace('#\<\/head\>([^\<]+|)\<body([^\>]+|)\>#im', $script, $text);
    }

    /**
     * Atribui um template do footer diferente
     * 
     * @param realpath $footerPathTemplate
     */
    public function setFooterTemplate($footerPathTemplate)
    {
        $this->footerPathTemplate = $footerPathTemplate;
        return $this;
    }

    /**
     * Força a exibição do Footer
     * 
     * @param boolean $useFooter
     * @return Application_Pdf_WkPdf
     */
    public function showFooter($useFooter = true)
    {
        $this->useFooter = $useFooter;
        return $this;
    }

}
