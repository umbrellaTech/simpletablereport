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

use Umbrella\SimpleReport\Api\IDatasource;
use Umbrella\SimpleReport\Api\ITemplate;
use Umbrella\SimpleReport\BaseRenderer;

/**
 * Description of HTMLRenderer
 *
 * @author Ítalo Lelis <italo@voxtecnologia.com.br>
 */
class WkPdfRenderer extends BaseRenderer
{

    private $output;
    private $imageQuality;
    private $htmlRenderer;
    private $parser;

    public function __construct(IDatasource $datasource, ITemplate $template)
    {
        parent::__construct($datasource, $template);
        $this->htmlRenderer = new HtmlRenderer($datasource, $template);
        $this->parser = new TemplateParser();
    }

    protected function initParams()
    {
        $this->output = $this->template->getParam('out');
        $this->imageQuality = $this->template->getParam('imageQuality');
    }

    public function render()
    {
        $this->initParams();
        $htmlFile = $this->getHtmlPageContent();
        $this->renderPdf($htmlFile);
    }

    protected function getHtmlPageContent()
    {
        ob_start();
        $this->htmlRenderer->render();
        $page = ob_get_contents();
        $filename = '/tmp/pdf/' . md5($page) . '.html';

        $this->parser->setTemplate($this->template->getParam('template'));
        $this->parser->setTags("content", $page);
        $content = $this->parser->parse();

        file_put_contents($filename, $content);
        ob_end_clean();

        return 'file://' . $filename;
    }

    protected function renderPdf($htmlContent)
    {
        \wkhtmltox_convert('pdf', array(
            'out' => $this->output,
            'imageQuality' => $this->imageQuality
                ), array(
            array(
                'page' => $htmlContent
            ))
        );
    }

}
