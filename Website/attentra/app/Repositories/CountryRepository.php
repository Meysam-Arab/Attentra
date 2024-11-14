<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/20/2017
 * Time: 12:47 PM
 */

namespace App\Repositories;

use App;
use App\Country;
use App\Repositories\Contracts\CountryRepositoryInterface;
use DB;
use Log;
use Input;

class CountryRepository implements CountryRepositoryInterface
{
    protected $country;

    public function __construct(Country $country)
    {

        $this->country = $country;
        ///////////////////////
    }

    public function initialize()
    {
        $this->country->country_id=null;
        $this->country->country_guid=null;
        $this->country->code=null;
        $this->country->name=null;
        $this->country->capital=null;
        $this->country->is_active=null;
        $this->country->deleted_at=null;
    }

    public function initializeByRequest($request)
    {

        $this->country->country_id=$request->input('country_id');
        $this->country->country_guid=$request->input('country_guid');
        $this->country->code=$request->input('code');
        $this->country->name=$request->input('name');
        $this->country->capital=$request->input('capital');
        $this->country->is_active=$request->input('is_active');

    }

    public function getFullDetailCountry( $params1,$params2,$params3,$distinct=null)
    {

        $query = $this->country->newQuery();
        //
        if($params1!=null) {

            $query=\App\Utility::fillQueryAlias($query,$params1,$distinct);
        }
        $query =Self::makeWhere($query);

        //
        if($params2!=null) {
            $query=\App\Utility::fillQueryJoin($query,$params2);

        }
        //filtering
        if($params3!=null) {
            $query=\App\Utility::fillQueryFilter($query,$params3);
        }
        $countries = $query->get();
        return $countries;

//        return $query->get();
    }

    public function makeWhere($query){
        if($this->country->country_id != null){
            $query->where('	country.'.'country_id', '=', $this->country->country_id);
        }
        if($this->country->country_guid != null){
            $query->where('country.'.'country_guid', '=', $this->country->country_guid);
        }
        if($this->country->code != null){
            $query->where('country.'.'code', '=', $this->country->code);
        }
        if( $this->country->name != null){
            $query->where('	country.'.'name', '=', $this->country->name);
        }
        if($this->country->capital != null){
            $query->where('	country.'.'capital', '=', $this->country->capital);
        }
        if( $this->country->is_active != null){
            $query->where('	country.'.'is_active', '=', $this->country->is_active);
        }
        return $query;
    }

    public function select()
    {
        $query = $this->country->newQuery();
        if($this->country->country_id != null){
            $query->where('country_id', '=', $this->country->country_id);
        }
        if($this->country->name != null){
            $query->where('name', '=', $this->country->name);
        }
        $query->where('deleted_at', null);
        $country = $query->get();

        return $country;
    }

    public function all()
    {
        // no code all() method.
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function store()
    {
        $this->country->country_guid = uniqid('',true);
        $this->country->save();
    }

    public function update($request)
    {
        self::initializeByRequest($request);
        $oldCountry= $this->country->find($request['country_id']);


        $oldCountry->code= $request->input('code');
        $oldCountry->name= $request->input('name');
        $oldCountry->capital= $request->input('capital');
        $oldCountry->is_active= $request->input('is_active');
        $oldCountry->save();
    }

    public function delete()
    {
        $RESULT=$this->findByIdAndGuid($this->country->country_id,$this->country->country_guid);
        if(!$RESULT)
            return false;
        else{
            $RESULT->delete();
            return true;
        }


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
        return $this->country->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        try
        {
            $query = $this->country->newQuery();
            $query->where('country_id', '=', $id);
            $query->where('country_guid', 'like', $guid);
            $countries = $query->get();
            if(count($countries)==0)
                return false;
            return $countries[0];
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function set($id,$guid)
    {
        $this->country->country_id = $id;
        $this->country->country_guid = $guid;

    }
}