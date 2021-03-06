<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ConfigElementInterface;
use PhpSpec\Util\Filesystem;
use PrettyXml\Formatter;

class ConfigGenerator
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var array
     */
    private $elementGenerators = array();

    /**
     * @param string $path
     */
    public function __construct($path, Filesystem $filesystem, Formatter $formatter)
    {
        $this->path = $path . DIRECTORY_SEPARATOR;
        $this->filesystem = $filesystem;
        $this->formatter = $formatter;
    }

    public function addElementGenerator(ConfigElementInterface $elementGenerator)
    {
        $this->elementGenerators[] = $elementGenerator;
    }

    private function getDirectoryPath($moduleName)
    {
        $modulePath = str_replace('_', DIRECTORY_SEPARATOR, $moduleName);
        return $this->path . $modulePath . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR;
    }

    private function getCurrentConfigXml($moduleName)
    {
        if (!$this->moduleFileExists($moduleName)) {
            $values = array(
                '%module_name%' => $moduleName
            );
            return strtr(file_get_contents(__DIR__ . '/templates/config.template'), $values);
        }
        return $this->filesystem->getFileContents($this->getFilePath());
    }

    private function getFilePath()
    {
        return $this->directory . 'config.xml';
    }

    private function moduleFileExists()
    {
        return $this->filesystem->pathExists($this->getFilePath());
    }

    private function getIndentedXml(\SimpleXMLElement $xml)
    {
        return $this->formatter->format($xml->asXML());
    }

    /**
     * @param string $xml
     */
    private function writeConfigFile($xml)
    {
        if (!$this->filesystem->isDirectory($this->directory)) {
            $this->filesystem->makeDirectory($this->directory);
        }
        $this->filesystem->putFileContents($this->getFilePath(), $xml);
    }
}
