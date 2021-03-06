<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use PhpSpec\Util\Filesystem;

class ModuleGenerator
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path, Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        $this->path = $path;
    }

    public function generate($moduleName)
    {
        $modulePath = explode("_", $moduleName);
        $path = $this->path . $modulePath[0] . DIRECTORY_SEPARATOR .$modulePath[1] . DIRECTORY_SEPARATOR;

        if (!$this->fileSystem->pathExists($path . 'etc')) {
            $this->fileSystem->makeDirectory($path . 'etc');
        }

        $values = array(
            '%module_name%' => $moduleName
        );

        $this->fileSystem->putFileContents(
            $path . 'etc' . DIRECTORY_SEPARATOR . 'module.xml',
            strtr(file_get_contents(__DIR__ . '/templates/module.template'), $values)
        );



//        if ($this->moduleFileExists($moduleName)) {
//            return;
//        }
//
//        $values = array(
//            '%module_name%' => $moduleName
//        );
//
//        if (!$this->fileSystem->pathExists($this->path)) {
//            $this->fileSystem->makeDirectory($this->path);
//        }
//
//        $this->fileSystem->putFileContents(
//            $this->getFilePath($moduleName),
//            strtr(file_get_contents(__DIR__ . '/templates/module.template'), $values)
//        );
    }

    private function getFilePath($moduleName)
    {
        return $this->path . $moduleName . '.xml';
    }

    private function moduleFileExists($moduleName)
    {
        return $this->fileSystem->pathExists($this->getFilePath($moduleName));
    }
}
