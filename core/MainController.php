<?php
namespace project1;

class MainController
{
    protected $view;
    public function __construct()
    {
        $this->view = new View();
    }
}