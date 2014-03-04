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

namespace Umbrella\SimpleReport\Renderer\Xlsx\Helper;

use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxBaseHelper;

/**
 * Description of XLSXSharedStringsHelper
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxDocPropsCoreHelper extends XlsxBaseHelper {

    public function render() {
        return  '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n" .
                '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
                . '<dc:creator></dc:creator>'
                . '<cp:lastModifiedBy></cp:lastModifiedBy>'
                . '<dcterms:created xsi:type="dcterms:W3CDTF">2014-01-08T01:24:07Z</dcterms:created>'
                . '<dcterms:modified xsi:type="dcterms:W3CDTF">2014-01-08T01:26:13Z</dcterms:modified>'
                . '</cp:coreProperties>';
    }
   
}
