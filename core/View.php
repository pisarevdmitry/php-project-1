<?php
namespace project1;

class View
{
    protected $twig;
    protected $loader;

    public function __construct($data = [])
    {
        $this->loader = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($this->loader);
    }

    public function render($filename, $data)
    {

        require_once "./views/".$filename.".php";
    }
    public function twigLoad($filename, $data)
    {
        echo $this->twig->render($filename.'.twig', $data);
    }

}