<?php
namespace project1;

class Twig extends MainController
{
    public function index()
    {
        $this->view->twigLoad('twig', []);
    }
}