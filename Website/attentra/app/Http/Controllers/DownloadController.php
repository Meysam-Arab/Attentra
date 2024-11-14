<?php

namespace App\Http\Controllers;

use App\download;
use App\TransDownload;
use App\Repositories\Contracts\DownloadRepositoryInterface;
use Illuminate\Http\Request;
//use ViewComponents\Eloquent\EloquentDataProvider;
use Input;
use Validator;
use Redirect;
use Session;

use DB;
use File;

use Exception;
use App\Repositories\LogEventRepository;
use Route;
class downloadController extends Controller
{
    protected $DownloadRepository;

    public function __construct(DownloadRepositoryInterface $download)
    {
        $this->DownloadRepository = $download;
    }



    public function create()
    {
        return view('download/create');
    }

    public function store(Request $request)
    {
        try
        {
            $rules = [
                'title1' => 'required|max:255',
                'Description1' => 'required',
                'title2' => 'required|max:255',
                'Description2' => 'required',
                'file' => 'required|mimes:jpeg,png,bmp,gif,svg,mp4,qt'
            ];
            $v = Validator::make($request->all(), $rules);
            if($v->fails()){

                return redirect()->back()->withErrors($v->errors())->withInput($request->except('file'));

            } else {
                $file = $request->file('file');
                //var_dump($request->file('file'));
                if ($file->isValid()) {
                    $dl = new download();
                    $dl->download_guid = uniqid('', true);
                    $fileName = $dl->download_guid . '.' . $file->guessClientExtension();
//                . '_' . $file->getClientOriginalName();
                    $destinationPath = storage_path().'/uploads';
                    $file->move($destinationPath, $fileName);
//                $dl->title = $request->input('title');
//                $dl->Description = $request->input('Description');
                    $dl->extention = $file->guessClientExtension();
                    $dl->size = $file->getClientSize();



                    $dl->is_active = 1;

                    $dl->save();

                    $dlRow = DB::table('download')
                        ->where('download_guid',$dl->download_guid)
                        ->where('deleted_at',null)
                        ->get();

                    $transDownload=new TransDownload();
                    $transDownload->transe_download_guid = uniqid('', true);
                    $transDownload->lang_id=1;
                    $transDownload->download_id=$dlRow[0]->download_id;
                    $transDownload->title=$request->input('title1');
                    $transDownload->description=$request->input('Description1');
                    $transDownload->deleted_at=null;
                    $transDownload->save();

                    $transDownload=new TransDownload();
                    $transDownload->transe_download_guid = uniqid('', true);
                    $transDownload->lang_id=2;
                    $transDownload->download_id=$dlRow[0]->download_id;
                    $transDownload->title=$request->input('title2');
                    $transDownload->description=$request->input('Description2');
                    $transDownload->deleted_at=null;
                    $transDownload->save();


                    $message='The post successfully inserted.';
                    return view('download/create')->with('message', $message);

                } else {
                    $message='uploaded file is not valid.';
                    return redirect()->back()->with('error', $message);
                }
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

    }

    public function uploadlList()
    {
        try
        {
            Session::set('applocaleId', 2);
            $paramsObj1=array(
                array("st","download"),
                array("as","transe_download","title","downloadTitle"),
                array("as","transe_download","description","downloadDes"),
                array("as","language","title","languageTitle")
            );

            //join
            $paramsObj2=array(
                array("join",
                    "transe_download",
                    array("transe_download.download_id","=","download.download_id")
                ),
                array("join",
                    "language",
                    array("language.language_id","=","transe_download.lang_id")
                )
            );
            //conditions
            $paramsObj3=array(
                array("whereRaw",
                    "transe_download.lang_id='".session('applocaleId')."'"
                )

            );

            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "download.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "transe_download.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "language.deleted_at is null"
            );


            /// ///////////////////////////////////////
            $this->DownloadRepository->initialize(null);
            //fetch advertisments
            list($provider, $DownloadRepository)=$this->DownloadRepository->getFullDetailDownload($paramsObj1,$paramsObj2,$paramsObj3);


//        list($provider, $DownloadRepository) = $this->DownloadRepository->select($this->DownloadRepository);
            $DownloadRepositories = $this-> DownloadRepository->all();
            return view('download/list', ['DownloadRepositories' => $DownloadRepositories, 'provider'=>$provider]);


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

    public function index()
    {
        try
        {
            Session::set('applocaleId', 2);
            $paramsObj1=array(
                array("st","download"),
                array("as","transe_download","title","downloadTitle"),
                array("as","transe_download","description","downloadDes"),
                array("as","language","title","languageTitle")
            );

            //join
            $paramsObj2=array(
                array("join",
                    "transe_download",
                    array("transe_download.download_id","=","download.download_id")
                ),
                array("join",
                    "language",
                    array("language.language_id","=","transe_download.language_id")
                )
            );
            //conditions
            $paramsObj3=array(
                array("whereRaw",
                    "transe_download.lang_id='".session('applocaleId')."'"
                )

            );
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "download.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "transe_download.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "language.deleted_at is null"
            );


            /// ///////////////////////////////////////

            $this->DownloadRepository->initialize(null);
            //fetch advertisments
            list($provider, $DownloadRepository)=$this->DownloadRepository->getFullDetailDownload($paramsObj1,$paramsObj2,$paramsObj3);

            $DownloadRepositories = $this-> DownloadRepository->all();

            return view('download/index', ['DownloadRepositories' => $DownloadRepositories, 'provider'=>$provider]);

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

    public function destroy($download_id,$download_guid){
        $paramsObj1=array(
            array("st","download"),
            array("as","download","download_guid","download_gid"),
            array("as","transe_download","title","downloadTitle"),
            array("as","transe_download","description","downloadDes"),
            array("as","language","title","languageTitle")
        );

        //join
        $paramsObj2=array(
            array("join",
                "transe_download",
                array("transe_download.download_id","=","download.download_id")
            ),
            array("join",
                "language",
                array("language.language_id","=","transe_download.lang_id")
            )
        );
        //conditions
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "download.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "transe_download.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "language.deleted_at is null"
        );


        /// ///////////////////////////////////////

        try
        {
            $this->DownloadRepository->initialize(null);
            //fetch advertisments
            list($provider, $DownloadRepository)=$this->DownloadRepository->getFullDetailDownload($paramsObj1,$paramsObj2,$paramsObj3);

            $this->DownloadRepository->set($download_id,$download_guid);
//        list($provider, $DownloadRepository) = $this->DownloadRepository->select($this->DownloadRepository);
            $DownloadRepositories = $this-> DownloadRepository->all();
            File::delete('uploads/'.$DownloadRepository[0]['download_guid'].'.'.$DownloadRepository[0]['extention']);

            $this->DownloadRepository->delete();


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
