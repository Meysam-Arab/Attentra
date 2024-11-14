<?php
/**
 * Created by PhpStorm.
 * User: Parsian
 * Date: 12/13/2016
 * Time: 03:08 PM
 */

namespace App\Repositories;

use App\Feedback;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\FeedbackRepositoryInterface;
use Log;


class FeedbackRepository implements FeedbackRepositoryInterface
{
    protected $feedback;

    public function __construct(Feedback $feedback)
    {

        $this->feedback = $feedback;
        ///////////////////////
    }
//
    public function initialize()
    {
        $this->feedback -> feedback_id = null;
        $this->feedback -> feedback_guid = null;
        $this->feedback -> title = null;
        $this->feedback->description = null;
        $this->feedback->email = null;
        $this->feedback->tel = null;
        $this->feedback->mobile = null;
        $this->feedback->deleted_at = null;

    }

    public function initializeByRequest($request=null)
    {
       $this->feedback  -> feedback_id = $request ->input('feedback_id');
        $this->feedback -> feedback_guid = $request ->input('feedback_guid');
        $this->feedback -> title = $request ->input('title');
        $this->feedback->description = $request ->input('description');
        $this->feedback->email = $request ->input('email');
        $this->feedback->tel = $request ->input('tel');
        $this->feedback->mobile = $request ->input('mobile');
        $this->feedback->deleted_at = null;
    }

    public function set($id,$guid)
    {
        $this->feedback->feedback_id = $id;
        $this->feedback->feedback_guid = $guid;

    }

    public function select()
    {
        $query = $this->feedback->newQuery();
        if($this->feedback->feedback_id != null){
            $query->where('feedback_id', '=', $this->feedback->feedback_id);
        }
        $query->where('deleted_at', null);
        $provider = new EloquentDataProvider($query);
        $feedback = $query->get();


        return array($provider,$feedback);
    }

    public function all()
    {
        $className = $this->feedback;
        return $className::all();
    }

    public function paginate()
    {

    }

    public function store()
    {

        $this->feedback->feedback_guid = uniqid('',true);
        $this->feedback->save();

    }

    public function update($request)
    {
        // no code update() method.
    }

    public function delete()
    {
        $this->feedback->find($this->feedback->feedback_id)->softDeletes();
    }

    public function findBy($field, $value)
    {
        // no code findBy() method.
    }

    public function exist($Id,$guid)
    {
        $query = $this->feedback->newQuery();
        $query->where('feedback_id', '=', $Id);
        $query->where('feedback_guid', '=', $guid);
        $feedback = $query->get()->first();
        if (count($feedback) == 0){
            return false;
        }
        else{
            return true;
        }
    }



    public function find($id)
    {
        return $this->feedback->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code findByIdAndGuid() method.
    }
}