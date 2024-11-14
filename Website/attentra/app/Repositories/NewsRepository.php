<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 5/9/2017
 * Time: 2:38 PM
 */
namespace App\Repositories;

use App\News;
use ViewComponents\Eloquent\EloquentDataProvider;
use Route;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Log;
use DB;
use File;

class NewsRepository implements NewsRepositoryInterface
{
    protected  $news;
    public function __construct(News $news)
    {

        $this->news = $news;

    }

    public function initialize()
    {
        $this->news -> news_id = null;
        $this->news -> news_guid = null;
        $this->news -> title = null;
        $this->news ->description = null;
        $this->news ->end_date = null;
        $this->news->is_active = null;
        $this->news->deleted_at = null;
    }

    public function initializeByRequest($request)
    {
        $this->news -> news_id = $request ->input('news_id');
        $this->news -> news_guid = $request ->input('news_guid');
        $this->news -> title = $request ->input('title');
        $this->news ->description = $request ->input('description');
        $this->news ->end_date = $request ->input('end_date');
        $this->news->is_active = $request ->input('is_active');
        $this->news->deleted_at = null;

    }

    public function getFullDetailNews( $params1,$params2,$params3)
    {

        $query = $this->news->newQuery();
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
        $newses = $query->get();
        return array($provider,$newses);

    }


    ////simplest query
    public function makeWhere($query){
        if($this->news->news_id != null){
            $query->where('news.'.'news_id', '=', $this->news->news_id);
        }
        if($this->news->news_guid != null){
            $query->where('news.'.'news_guid', '=', $this->news->news_guid);
        }
        if($this->news->title != null){
            $query->where('news.'.'title', '=', $this->news->title);
        }
        if( $this->news->description != null){
            $query->where('news.'.'description', '=', $this->news->description);
        }

        return $query;
    }
    public function all()
    {
        return DB::table('news')
            ->where('deleted_at',null)
            ->get();
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function store(){
        $this->news->news_guid = uniqid('', true);
        $this->news->is_active = 1;
        $this->news->save();
        return $this->news->news_guid;
    }

    public function update($request)
    {
        self::initializeByRequest($request);

        DB::table('news')
            ->where('news_id', $this->news->news_id)
            ->where('news_guid', 'like', $this->news->news_guid)
            ->where('deleted_at',null)
            ->update(['news.title' => $this->news->title,
                'news.description' => $this->news->description,
                'news.is_active' => $this->news->is_active,
                'news.end_date' => $this->news->end_date]);

    }

    public function delete()
    {
        try
        {
            $this->findByIdAndGuid($this->news -> news_id,$this->news->news_guid)->delete();
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function findBy($field, $value)
    {
        // no code findBy() method.
    }

    public function exist($id, $guid)
    {
        $query = $this->news->newQuery();
        $query->where('news_id', '=', $id);
        $query->where('news_guid', 'like', $guid);
        $newses = $query->get();
        if(count($newses) > 0)
            return true;
        return false;
    }

    public function findByIdAndGuid($id, $guid)
    {
        try
        {
            $query = $this->news->newQuery();
            $query->where('news_id', '=', $id);
            $query->where('news_guid', 'like', $guid);
            $newses = $query->get();
            return $newses[0];
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function set($id,$guid)
    {
        $this->news->news_id = $id;
        $this->news->news_guid = $guid;

    }

    public function find($id)
    {
        return $this->news->find($id);
    }

    public function deleteLogo($news_guid){
        //retern previous name of news
        $destinationPath = storage_path().'/app/news';
        $files1 = scandir($destinationPath);
        $nameOfFile="";
        $search =$news_guid;
        $search_length = strlen($search);
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $nameOfFile=$value;
                break;
            }
        }


        //delete previou logo
        File::delete(storage_path().'/app/news/'.$nameOfFile);
    }

    public function UpdateLogoOfNews($request)
    {
        self::deleteLogo($request->input('news_guid'));

        //insert new logo of company to this path    =>  storage_path().'/app/company
        $file = $request->file('fileLogo');
        $fileName = $request->input('news_guid'). '.' .$request->file('fileLogo')->getClientOriginalExtension();
        $destinationPath = storage_path().'/app/news';
        $file->move($destinationPath, $fileName);
    }

    public function API_UpdateLogoOfNews($news_guid,$file)
    {
        self::deleteLogo($news_guid);

        $f = finfo_open();

        $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
        $split = explode( '/', $mime );
        $ext = $split[1];
        //insert new logo of company to this path    =>  storage_path().'/app/company
        $fileName = $news_guid. '.' .$ext;
        $destinationPath = storage_path().'/app/news';
//        $file->move($destinationPath, $fileName);

        file_put_contents($destinationPath."/".$fileName, $file);
    }

    public function set_title($title)
    {
        $this->news->title = $title;
    }

    public function get_title()
    {
        return $this->news->title;
    }

    public function set_image($binary_image)
    {
        $this->news->image = $binary_image;
    }

    public function get_image()
    {
        return $this->news->image;
    }

    public function get_guid()
    {
        return $this->news->news_guid;
    }

    public function select()
    {
        // no code select() method.
    }
}