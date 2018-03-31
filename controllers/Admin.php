<?php
namespace project1;

class Admin extends MainController
{
    public function index()
    {
        $this->view->render('admin', []);
    }
}