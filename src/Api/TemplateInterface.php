<?php

/*
 * Copyright 2013 kelsoncm.
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
namespace Umbrella\SimpleReport\Api;

/**
 * Defines the basics templates methods.
 * @author kelsoncm <falecom@kelsoncm.com>
 * @author Ítalo Lelis de Vietro <italo@voxtecnologia.com.br>
 */
interface TemplateInterface
{

    public function setFields(FieldSet $fieldSet);

    public function getFields();

    public function setParams(array $params);

    public function getParams();

    public function addParam($param, $value);

    public function getParam($param);

    public function getTags();

    public function setTags(array $tags);

    public function getPath();

    public function setPath($path);
}
