<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/8/2017
 * Time: 4:43 PM
 */

namespace App\Repositories;

use App\LogEvent;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\LogEventRepositoryInterface;
use Log;

class LogEventRepository implements LogEventRepositoryInterface
{
    protected $logEvent;

    function __construct($user_id,$controller_and_action_name,$error_message)
    {
        //parent::__construct();

        $this->logEvent = new LogEvent();


        $this->logEvent->user_id = $user_id;
        $this->logEvent->controller_and_action_name = $controller_and_action_name;
        $this->logEvent->error_message = $error_message;

    }


    public function all()
    {
        // no code all() method.
    }

    public function delete()
    {
        // no code delete() method.
    }

    public function exist($id, $guid)
    {
        // no code exist() method.
    }

    public function find($id)
    {
        // no code find() method.
    }

    public function findBy($field, $value)
    {
        // no code findBy() method.
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code findByIdAndGuid() method.
    }

    public function initialize()
    {
        // no code initialize() method.
    }

    public function initializeByRequest($request)
    {
        // no code initializeByRequest() method.
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function select()
    {
        // no code select() method.
    }

    public function store()
    {
        $this->logEvent->log_event_guid = uniqid('',true);

        $this->logEvent->save();
    }

    public function update($request)
    {
        // no code update() method.
    }

    /**
     * @return LogEvent
     */
    public function getLogEvent()
    {
        return $this->logEvent;
    }

    /**
     * @param LogEvent $logEvent
     */
    public function setLogEvent($logEvent)
    {
        $this->logEvent = $logEvent;
    }
}
