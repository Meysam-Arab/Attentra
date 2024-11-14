<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Language;
use Illuminate\Support\Facades\Input;

use Exception;
use App\Repositories\LogEventRepository;
use Route;

class LanguageController extends Controller
{
//    //
    public function switchLang($lang)
    {
        try
        {
            if (array_key_exists($lang, Config::get('languages'))) {
                Session::set('applocale', $lang);
                Session::set('applocaleId', 1);
            }

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


        return Redirect::back();
    }

    public function create()
    {
        return view('language.create');
    }

    public function index()
    {
        return "hi index";
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20'
        ]);
        try
        {
            $lang=new Language();
            $lang->language_guid= uniqid('',true);
            $lang->title=$request->input('title');

            if (Input::get('lang_dir') === 'yes') {
                $lang->language_direction = 1;
            } else {
                $lang->language_direction = 0;
            }
            $lang->save();
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }



    }

}
