<?php

class MustachePresenter{
    private $mustache;
    private $partialsPathLoader;

    public function __construct($partialsPathLoader)
    {
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
                'partials_loader' => new Mustache_Loader_FilesystemLoader($partialsPathLoader)
            ));
        $this->partialsPathLoader = $partialsPathLoader;
    }

    public function render($contentFile, $data = array())
    {
        echo $this->generateHtml($contentFile, $data);
    }

    public function generateHtml($contentFile, $data = array())
    {
        $headerFile = $this->partialsPathLoader . '/header.mustache';
        $footerFile = $this->partialsPathLoader . '/footer.mustache';
        $contentFile = 'view/' . $contentFile . '.mustache';
        if (!file_exists($headerFile) || !file_exists($contentFile) || !file_exists($footerFile)) {
            die("One or more template files are missing.");
        }
        $contentAsString = file_get_contents($headerFile);
        $contentAsString .= file_get_contents($contentFile);
        $contentAsString .= file_get_contents($footerFile);
        return $this->mustache->render($contentAsString, $data);
    }
}