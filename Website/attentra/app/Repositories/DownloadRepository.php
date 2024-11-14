<?php
/**
 * Created by PhpStorm.
 * User: Parsian
 * Date: 12/13/2016
 * Time: 03:08 PM
 */

namespace App\Repositories;

use App\download;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\DownloadRepositoryInterface;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\FeedbackRepositoryInterface;
use Log;


class DownloadRepository implements DownloadRepositoryInterface
{
    protected $download;

    public function __construct(download $download)
    {

        $this->download = $download;

    }

    public function initialize()
    {
        $this->download -> download_id = null;
        $this->download -> download_guid = null;
        $this->download -> title = null;
        $this->download ->description = null;
        $this->download ->extention = null;
        $this->download ->size = null;
        $this->download->is_active = null;
        $this->download->deleted_at = null;
    }

    public function initializeByRequest($request)
    {
        $this->download -> download_id = $request ->input('download_id');
        $this->download -> download_guid = $request ->input('download_guid');
        $this->download -> title = $request ->input('title');
        $this->download ->description = $request ->input('description');
        $this->download ->extention = $request ->input('extention');
        $this->download ->size = $request ->input('size');
        $this->download->is_active = $request ->input('is_active');
        $this->download->deleted_at = null;
    }

    public function select()
    {
        $query = $this->download->newQuery();
        if($this->download->download_id != null){
            $query->where('download_id', '=', $this->download->download_id);
        }
        $query->where('deleted_at', null);
        $provider = new EloquentDataProvider($query);
        $download = $query->get();



        return array($provider,$download);
    }

    public function getFullDetailDownload( $params1,$params2,$params3)
    {

        $query = $this->download->newQuery();
        //
        if($params1!=null) {

            $query=\App\Utility::fillQueryAlias($query,$params1);
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

        $provider = new EloquentDataProvider($query);
        $downloads = $query->get();
        return array($provider,$downloads);

//        return $query->get();
    }

    ////simplest query
    public function makeWhere($query){
        if($this->download->download_id != null){
            $query->where('download.'.'download_id', '=', $this->download->download_id);
        }
        if($this->download->download_guid != null){
            $query->where('download.'.'download_guid', '=', $this->download->download_guid);
        }
        if($this->download->extention != null){
            $query->where('download.'.'extention', '=', $this->download->extention);
        }
        if( $this->download->size != null){
            $query->where('download.'.'size', '=', $this->download->size);
        }

        return $query;
    }

    public function all()
    {
        // no code
    }

    public function paginate()
    {
        //no code
    }

    public function store()
    {
        // no code
    }

    public function update($request)
    {
        // no code
    }

    public function delete()
    {
        $this->download->find($this->download->download_id)->delete();
    }

    public function findBy($field, $value)
    {
        // no code
    }

    public function exist($id, $guid)
    {
        // no code
    }

    public function set($id,$guid)
    {
        $this->download->download_id = $id;
        $this->download->download_guid = $guid;

    }

    public function find($id)
    {
        return $this->download->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code findByIdAndGuid() method.
    }
}