<?php
namespace Module\Api\Transformer;

interface ReformInterface
{
  //for set model
  public function model();

  //for build request
  public function builderRequest($model);

  //to get single response format
  public function single($data);

  //if you need to differential batch response, you need to add batch() method
  //public function batch($data);
}