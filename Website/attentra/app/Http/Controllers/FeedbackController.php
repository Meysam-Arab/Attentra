<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\FeedbackRepositoryInterface;
use Illuminate\Http\Request;
use App\OperationMessage;
use DB;
use Validator;
use Exception;
use App\Repositories\LogEventRepository;
use Route;
use Log;
use App\Utility;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    protected $feedbackRepository;

    public function __construct(FeedbackRepositoryInterface $feedback)
    {
        $this->feedbackRepository = $feedback;
    }


    public function index() {

        try
        {
            $this->feedbackRepository->initialize(null);
            list($provider, $FeedbackRepository) = $this->feedbackRepository->select($this->feedbackRepository);

            return view('wellcome', ['feedbackRepository' => $FeedbackRepository, 'provider'=>$provider]);
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

    public function store(Request $request)
    {
//        app('App\Utility')->sendMail("hooman.mishani1990@gmail.com", "salam hooman mishani");
//        sleep(2);
        // Validate the request...

        //check captcha
        /////////////////////////////
        $gcaptcha = $request->input('captcha');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LeHzyEUAAAAABXg8x6Eetw4uI24fe0Dz49xtc7j', 'response' => $gcaptcha);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $temp=json_decode($result);
        if($temp->{'success'} ==false)
        {
            $message = trans('messages.msg_ErrorCaptcha');
            return array('success' => false, 'message' => [$message]);
        }
        ////////////////////////////
        $validation = Validator::make($request->all(), [
            'title' => 'required|max:30',
            'description' => 'required|max:200',
            'email'=>'required|email',
            'tel'=>'required|digits_between:7,15',
            'mobile'=>'required|digits_between:7,15'
        ]);


        $this->feedbackRepository->initializeByRequest($request);
//        $user = new UserRepositoryInterface($request);

        try
        {
            if($validation->passes()){
                $this->feedbackRepository->store();
                Utility::sendMailFeedBack('fardan7eghlim@gmail.com', $request->input('title'),$request->input('description') );
                return response()->json([
                    'success' => true
                ]);
            }else{
                $errors = $validation->errors()->all();
                return response()->json([
                    'success' => false,
                    'message' => $errors
                ]);
            }


        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            return redirect()->action(
                'FeedbackController@index')->with('message', $message);
        }


    }

    public function storeOrder(Request $request)
    {
        //check captcha
        /////////////////////////////
        $gcaptcha = $request->input('captcha');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LeHzyEUAAAAABXg8x6Eetw4uI24fe0Dz49xtc7j', 'response' => $gcaptcha);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $temp=json_decode($result);
        if($temp->{'success'} ==false)
        {
            $message = trans('messages.msg_ErrorCaptcha');
            return array('success' => false, 'message' => [$message]);
        }
        ////////////////////////////

        $validation = Validator::make($request->all(), [
            'title' => 'required|max:30',
            'description' => 'required|max:200',
            'email'=>'required|email',
            'tel'=>'required|digits_between:7,15',
            'mobile'=>'required|digits_between:7,15'
        ]);



        $this->feedbackRepository->initializeByRequest($request);
//        $user = new UserRepositoryInterface($request);

        try
        {
            if($validation->passes()){
                $this->feedbackRepository->store();
                Utility::sendMailFeedBack('fardan7eghlim@gmail.com', $request->input('title'),$request->input('description') );

                return response()->json([
                    'success' => true
                ]);
            }else{
                $errors = $validation->errors()->all();
                return response()->json([
                    'success' => false,
                    'message' => $errors
                ]);
            }


        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            return redirect()->action(
                'FeedbackController@index')->with('message', $message);
        }

    }

    public function feedbackList() {
        try
        {
            $this->feedbackRepository->initialize(null);
            list($provider, $feedbas) = $this->feedbackRepository->select($this->feedbackRepository);
            $feedbackRepositories = $this-> feedbackRepository->all();
            return view('feedback.index', ['feedbackRepositories' => $feedbackRepositories, 'provider'=>$provider]);

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

    public function show($feedback_id,$feedback_guid){
        try
        {
            $this->feedbackRepository->set($feedback_id,$feedback_guid);

            list($provider, $feedbackRepositories) = $this->feedbackRepository->select($this->feedbackRepository);

            $feedbackRepository = $feedbackRepositories[0];


            return view('feedback.details', ['feedbackRepository' => $feedbackRepository]);
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

    public function create()
    {
        return view('feedback.create');
    }

    public function destroy($feedback_id,$feedback_guid){

        try
        {
            if (!$this->feedbackRepository->exist($feedback_id,$feedback_guid)){
                return  redirect()->action('HomeController@error');
            }

            $this->feedbackRepository->set($feedback_id,$feedback_guid);

//        $currentUser -> user_id = 1;
            list($provider, $feedbackRepositories) = $this->feedbackRepository->select($this->feedbackRepository);
            $feedbackRepository = $feedbackRepositories[0];
//        $provider = new EloquentDataProvider(user::class);
            $result = $this->feedbackRepository->find($feedback_id)->delete();
            return redirect()->back();

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
