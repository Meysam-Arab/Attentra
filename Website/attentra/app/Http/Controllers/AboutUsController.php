<?php
//Meysam Arab - 13950829

namespace App\Http\Controllers;
//namespace ViewComponents\Grids\WebApp;
use ViewComponents\Eloquent\EloquentDataProvider;


use DB;
use App\AboutUs;
use App\Http\Controllers\Controller;
use Exception;
use App\Repositories\LogEventRepository;
use Route;

class AboutUsController extends Controller
{

    public function index()
    {
        try
        {
            $tempAboutUs =new AboutUs();
            $tempAboutUs -> about_us_id = null;//1
            $tempAboutUs -> about_us_guid = null;
            $tempAboutUs -> name = null;
            $tempAboutUs->description = null;
            $tempAboutUs->latitude = null;
            $tempAboutUs->longitude = null;
            $tempAboutUs->postal_code = null;
            $tempAboutUs->tel = null;
            $tempAboutUs->address = null;
            $tempAboutUs->is_active = null;
            $tempAboutUs->is_deleted = null;
            $ttaboutUses = new AboutUs();
            list($provider, $aboutUses) = $ttaboutUses->select($tempAboutUs);

            $provider = new EloquentDataProvider(AboutUs::class);


            return view('aboutus.index', ['aboutUses' => $aboutUses, 'provider'=>$provider]);

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
	
