<?php

namespace App\Http\Controllers;

use App\UserType;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    /**
     * Show a list of all available userTypes.
     *
     * @return Response
     */
    public function index()
    {
        try
        {
            $userTypes = UserType::all();
            return view('userType.index', ['userTypes' => $userTypes]);
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