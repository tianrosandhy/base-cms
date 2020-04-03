<?php
namespace Core\Main\Contracts;

interface Crud
{
	public function index();
	public function create();
	public function store();
	public function edit($id=null);
	public function update($id=null);
	public function delete($id=null);
}