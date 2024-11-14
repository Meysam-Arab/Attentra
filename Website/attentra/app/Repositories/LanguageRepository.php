<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/19/2017
 * Time: 6:40 PM
 */


namespace App\Repositories;

use App;
use App\Language;
use App\Repositories\Contracts\LanguageRepositoryInterface;
use DB;
use Log;
use Input;

class LanguageRepository implements LanguageRepositoryInterface
{
    protected $language;

    const  None = 0;         // نامشخص
    const Persian = 1;     // فارسی
    const English = 2;   // انگلیسی


    public function __construct(Language $language)
    {

        $this->language = $language;
    }

    public function initialize()
    {
        $this->language->language_id=null;
        $this->language->language_guid=null;
        $this->language->title=null;
        $this->language->language_direction=null;
        $this->language->code=null;
        $this->language->deleted_at=null;
    }

    public function initializeByRequest($request)
    {
        $this->language->language_id=$request->input('language_id');
        $this->language->language_guid=$request->input('language_guid');
        $this->language->title=$request->input('title');
        $this->language->code=$request->input('code');
        $this->language->language_direction=$request->input('language_direction');

    }
    public function select()
    {
        $query = $this->language->newQuery();
        if($this->language->language_id != null){
            $query->where('language_id', '=', $this->language->language_id);
        }
        if($this->language->title != null){
            $query->where('title', '=', $this->language->title);
        }
        if($this->language->code != null){
            $query->where('code', '=', $this->language->code);
        }
        $query->where('deleted_at', null);
        $language = $query->get();

        return $language;
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

    public function getFullDetailLanguage( $params1,$params2,$params3,$distinct=null)
    {

        $query = $this->language->newQuery();
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
        $languages = $query->get();
        return $languages;

//        return $query->get();
    }

    public function makeWhere($query){
        if($this->language->language_id != null){
            $query->where('	language.'.'language_id', '=', $this->language->language_id);
        }
        if($this->language->country_guid != null){
            $query->where('language.'.'language_guid', '=', $this->language->language_guid);
        }
        if($this->language->title != null){
            $query->where('language.'.'title', 'like', $this->language->title);
        }
        if( $this->language->language_direction != null){
            $query->where('language.'.'language_direction', '=', $this->language->language_direction);
        }
        if( $this->language->code != null){
            $query->where('language.'.'code', '=', $this->language->code);
        }

        return $query;
    }

    public static function getIdByCode($code)
    {
        $lp = new LanguageRepository(new Language());
        $languages = $lp->select(new Language());
        foreach ($languages as $language) {
            if ($language->code == $code) {
                return $language->language_id;
            }
        }
    }


}