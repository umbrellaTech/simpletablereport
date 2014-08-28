<?php

/*
 * Copyright 2013 kelsoncm <falecom@kelsoncm.com>.
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
namespace Umbrella\SimpleReport;

use Umbrella\SimpleReport\Api\ConfigurationInterface;

/**
 * Description of BaseConfigurationLoader
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class ConfigurationLoader
{
    private static $instance;
    private $configuration;

    /**
     * 
     * @return ConfigurationLoader
     */
    public static function getInstance()
    {
        if (!ConfigurationLoader::$instance) {
            ConfigurationLoader::$instance = new ConfigurationLoader();
            ConfigurationLoader::$instance->load();
        }
        return ConfigurationLoader::$instance;
    }

    /**
     * 
     * @return ConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getRootDir()
    {
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') ? : getcwd();
    }

    protected function load()
    {
        $configFile = __DIR__ . "/../../../config.ini";
        $ini_array = parse_ini_file($configFile);
        $configurationClassName = $ini_array['simpletablereport.configurationClassName'];
        $this->configuration = new $configurationClassName($ini_array);
    }
}
