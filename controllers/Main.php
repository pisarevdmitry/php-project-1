<?php
namespace project1;

class Main extends MainController
{
    public function index()
    {
        $this->view->render('main', []);
    }
}