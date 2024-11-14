<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/20/2017
 * Time: 5:32 PM
 */

namespace App\Repositories;

use App;
use App\Currency;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use DB;
use Log;
use Input;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    protected $currency;


    const IRR = 1;
    const USD = 2;

    public function __construct(CurrencyRepository $currency)
    {

        $this->currency = $currency;
    }


    public function initialize()
    {
        // no code initialize() method.
    }

    public function initializeByRequest($request)
    {
        // no code initializeByRequest() method.
    }

    public function select()
    {
        // no code select() method.
    }

    public function all()
    {
        // no code all() method.
    }

    public function store()
    {
        // no code store() method.
    }

    public function update($request)
    {
        // no code update() method.
    }

    public function delete()
    {
        // no code delete() method.
    }

    public function findBy($field, $value)
    {
        // no code findBy() method.
    }

    public function exist($id, $guid)
    {
        // no code exist() method.
    }

    public function find($id)
    {
        // no code find() method.
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code findByIdAndGuid() method.
    }
}