<?php

/*
 * Copyright 2014 ayrtonricardo.
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
 * Defines the basics sum methods.
 * @author ayrtonricardo <ayrton_jampa15@hotmail.com>
 */
interface ISum
{

    public function getColumnFieldCounts();

    public function setColumnFieldCounts($fieldsCounts);

    public function enableColumnCount($enable);

    public function getEnabledColumnCount();
}