<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\FeedbackController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // return view('home');
        return view('welcome')
            ->nest('content','feedback/create',compact('feedback'));

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function error()
    {



        return view('errors.resourceNotExist');
    }

    public  function getAPK()
    {
//        $filename='5957a03bea5449.79935983';
        try {
//            return Redirect::to(url('dist/luckylord.apk')) ;
//            return response()->file(url('style/imgs/2.jpg'));
            $path = storage_path('app/attentra.apk');

            return response()->file($path ,[
                'Content-Type'=>'application/vnd.android.package-archive',
                'Content-Disposition'=> 'attachment; filename="attentra.apk"',
            ]) ;

        } catch (Exception $e) {

        }
    }
}
