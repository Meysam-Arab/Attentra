<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 12/6/2016
 * Time: 1:40 PM
 */
// app/Repositories/Contracts/PostRepositoryInterface.php

namespace App\Repositories\Contracts;

interface BaseInterface
{


    public function initialize();
    public function initializeByRequest($request);

//
//    public function update($request);
    ///////////static methods////////////////

    public function select();

    public function all();
//    public function paginate();
    public function store();
    public function update($request);
    public function delete();

    public function findBy($field,$value);


//
    public function exist($id, $guid);

//    public static function createNewInstance();


    public function find($id);

    public function findByIdAndGuid($id, $guid);

}

?>