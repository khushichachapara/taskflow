<?php
namespace TaskFlow\Controllers;

class TaskController {
    public function index() {
        require __DIR__ . '/../../views/tasks/list.php';
    }
    public function create() {

        require __DIR__ . "/../../views/tasks/create.php";
    }
    public function view()  {
        
    }
    public function store()     {
        
    }
    public function apiList() {
        
    }
    public function edit(){

    }
    public function softdelete(){

    }
}
    