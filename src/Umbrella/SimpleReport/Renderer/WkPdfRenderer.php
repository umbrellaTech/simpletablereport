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

    /**
     * Get 
     * @return type
     */
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

    /**
     * Define 
     * @param type $useFooter
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function setUseFooter($useFooter)
    {
        $this->useFooter = $useFooter;
        return $this;
    }

    /**
     * Define onde está localizado o HTML template do footer
     * @param string $footerPathTemplate
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function setFooterPathTemplate($footerPathTemplate)
    {
        $this->footerPathTemplate = $footerPathTemplate;
        return $this;
    }

    /**
     * Define a URL onde será criado o HTML parseado do footer
     * @param string $footerPath
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function setFooterPath($footerPath)
    {
        $this->footerPath = $footerPath;
        return $this;
    }

    /**
     * Define a URL onde o footer se encontra, para o wkpdf encontrar o arquivo HTML
     * @param string $footerHtmlUrl
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function setFooterHtmlUrl($footerHtmlUrl)
    {
        $this->footerHtmlUrl = $footerHtmlUrl;
        return $this;
    }

    /**
     * Define o texto que será renderizado no footer
     * @param string $footerText
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function setFooterText($footerText)
    {
        $this->footerText = $footerText;
        return $this;
    }

    /**
     * Define o caminho do PDF a ser gerado
     * @return string
     */
    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Recupera o caminho do PDF gerado
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Verifica se o relatório será renderizado por streaming
     * @return type
     */
    public function isStreaming()
    {
        return $this->isStreaming;
    }

    /**
     * Define se o relatório será renderizado por streaming
     * @param boolean $isStreaming
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function setStreaming($isStreaming)
    {
        $this->isStreaming = $isStreaming;
        return $this;
    }

    /**
     * Renderiza o PDF
     * @return \Umbrella\SimpleReport\Renderer\WkPdfRenderer
     */
    public function render()
    {
        $htmlFile = $this->getHtmlPageContent();
        if ($this->isStreaming()) {
            $response = new StreamedResponse();

            ob_start();
            $self = $this;

            $response->setCallback(function () use($htmlFile, $self, &$lenght) {
                $self->createPdf($htmlFile);
                ob_flush();
                flush();
            });
        } else {
            $response = new Response();
            $this->createPdf($htmlFile);
        }

        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, md5($this->output));
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Cache-Control', 'max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');

        $response->send();

        return $this;
    }

    /**
     * Recupera o conteúdo HTML do template. O método faz o parse do HTML e retorna o conteúdo pronto.
     * @return string O HTML final após o parse
     */
    protected function getHtmlPageContent()
    {
        ob_start();
        $this->htmlRenderer->render();
        $page = ob_get_contents();
        $filename = '/tmp/' . microtime() . '.html';

        $this->parser->setTags(array_merge(array(
            "content" => $page,
            "date" => $this->createAndFormatDate(),
                        ), $this->template->getTags())
        );
        $content = $this->parser->parse();
        file_put_contents($filename, $content);
        ob_end_clean();

        return $filename;
    }

    /**
     * Cria a data data atual e formata para o formato desejado
     * @return string
     */
    protected function createAndFormatDate($format = 'd/m/Y')
    {
        $date = new DateTime();
        return $date->format($format);
    }

    /**
     * Converte um HTML em PDF
     * @param string $htmlFile O conteúdo HTML para ser convertido
     */
    protected function createPdf($htmlFile)
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

    /**
     * Deleta um arquivo do File System
     * @param string $file O caminho para o arquivo
     */
    protected function unlinkFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Pega o footer para ser utilizado no \wkhtmltox_convert()
     * @return array
     */
    protected function getFooter()
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
    protected function appendScript($text)
    {
        $script = '<script>function subst() {var vars={};var x=document.location.search.substring(1).split(\'&\');';
        $script .= 'for(var i in x) {var z=x[i].split(\'=\',2);vars[z[0]] = unescape(z[1]);}var x=[\'frompage\',\'topage\',\'page\',';
        $script .= '\'webpage\',\'section\',\'subsection\',\'subsubsection\']; for(var i in x) { var y = document.getElementsByClassName(x[i]);';
        $script .= 'for(var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];}}</script></head><body onload="subst()">';
        return preg_replace('#\<\/head\>([^\<]+|)\<body([^\>]+|)\>#im', $script, $text);
    }

}
