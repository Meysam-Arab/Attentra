<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 3/12/2017
 * Time: 2:27 PM
 */

namespace App\Http\Controllers;
use App\Repositories\TrackRepository;
use App\Repositories\Contracts\TrackRepositoryInterface;
use Illuminate\Http\Request;
use Auth;
use App\OperationMessage;
use DB;
use Illuminate\Support\Facades\Input;
use App\Track;
use Redirect;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Repositories\LogEventRepository;
use Route;
use File;
use Lang;
class TrackController extends Controller
{
    protected $trackRepo;

    public function __construct(TrackRepository $track)
    {
        $this->trackRepo = $track;
    }

    public function Index($user_id,$user_guid)
    {

        //TODO validation here


//        select track data with
        $paramsObj1 = array(
            array("se", "track", "track_group")
        );
        $paramsObj3 = array(
            array("whereRaw",
                "track.user_id = (SELECT user_id  
                                    FROM user  
                                    WHERE user_id ='".$user_id ."'and
                                    user_guid='".$user_guid."')"
            ),
            array("groupBy",
                "track.track_group"
            ),
            array("orderBy",
                "track.track_id", "DESC"
            )
        );
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "track.deleted_at is null"
        );
        /// ///////////////////////////////////////
        try
        {
            $this->trackRepo->initialize();

            $tracks = $this->trackRepo->getFullDetailTrack($paramsObj1, null, $paramsObj3);


            //TODO show view here
            return view('track/index', ['tracks' => $tracks,'user_id'=>$user_id,'user_guid'=>$user_guid]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            //TODO return error here

        }


    }

    public function trackList(Request $request)
    {


        //TODO validation here

//        select track data with
        $paramsObj1 = array(
            array("st", "track")
        );
        $paramsObj3 = array(
            array("whereRaw",
                "track.track_group ='" . $request->input('track_group') . "'"
            ),
            array("whereRaw",
                "track.track_id >'" . $request->input('last_loaded_id') . "'"

            )
        );
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "track.deleted_at is null"
        );
        /// ///////////////////////////////////////
        try
        {
            $this->trackRepo->initialize();

            $tracks = $this->trackRepo->getFullDetailTrack($paramsObj1, null, $paramsObj3);


            //TODO show view here

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            //TODO return error here

        }


    }

    public function showMap($user_id,$user_guid,$track_group)
    {
        //TODO validation here


//        select track data with
        $paramsObj1 = array(
            array("st", "track")
        );
        $paramsObj3 = array(
            array("whereRaw",
                "track.track_group = '".$track_group."'"
            ),
            array("orderBy",
                "track.track_id", "ASC"
            )
        );
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "track.deleted_at is null"
        );
        /// ///////////////////////////////////////
        try
        {
            $this->trackRepo->initialize();

            $tracks = $this->trackRepo->getFullDetailTrack($paramsObj1, null, $paramsObj3);

            $status_lable=[
                Lang::get('messages.lbl_battery_percent'),
                Lang::get('messages.lbl_battery_health'),
                Lang::get('messages.lbl_battery_status'),
                Lang::get('messages.lbl_signal'),
                Lang::get('messages.lbl_battery_plugged'),
                Lang::get('messages.lbl_Date'),
                Lang::get('messages.lbl_Time'),

            ];
            log::info(json_encode($tracks));
            for($index=0;$index<count($tracks);$index++){
                $temp=TrackRepository::getBatryStatusString( $tracks[$index]->charge_status);
                $tracks[$index]->charge_status=$temp;

                $tracks[$index]->battery_status=TrackRepository::getCharcheStatusString( $tracks[$index]->battery_status);
                $tracks[$index]->charge_type=TrackRepository::getPlugTypeString( $tracks[$index]->charge_type);
                $tracks[$index]->signal_power=TrackRepository::getGsmLevelString( $tracks[$index]->signal_power);



            }

            //TODO show view here
            return view('track/map', ['tracks' => $tracks,'status_lable' => $status_lable,'user_id'=>$user_id,'user_guid'=>$user_guid,'track_group'=>$track_group]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            //TODO return error here

        }

        return view('track/map');
    }

    public function geticons($iconname)
    {
        try
        {
            return File::get(storage_path('app/public/icons/'.$iconname));
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

    public function delete($track_group){
        try
        {
            Track::where('track_group', $track_group)->delete();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);
            session(['message'=> $message]);

            return redirect()->back();
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            //TODO return error here

        }
    }

}