<?php

class BookController{

    public function index(){
        return View::render('index.twig');
    }

    public function show($id){
        return 'I am book with id: '.$id;
    }

    public function edit($id){
        return 'Editing book with id: '.$id;
    }

    public function update($id){
        return 'Updating book with id: '.$id;
    }

    public function create(){
        return 'Create book form here';
    }

    public function store(){
        return 'Book successfully created';
    }

    public function destroy($id){
        return 'Destroying book with id: '.$id;
    }
}