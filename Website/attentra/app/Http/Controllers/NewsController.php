<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 5/9/2017
 * Time: 2:37 PM
 */

namespace App\Http\Controllers;

use App\OperationMessage;
use App\Repositories\NewsRepository;
use Validator;
use Redirect;
use Session;
use Auth;
use DB;
use File;
use App;

use Exception;
use App\Repositories\LogEventRepository;
use Route;

class NewsController extends Controller
{
    protected $NewsRepository;

    public function __construct(NewsRepository $news)
    {
        $this->NewsRepository = $news;

    }

    public function index($article_Page_Number=null)
    {
        try {
            $page_number=1;
            $paramsObj1 = array(
                array("st", "news"),
                array("as", "transe_news", "title", "newsTitle"),
                array("as", "transe_news", "description", "newsDes"),
                array("as", "language", "title", "languageTitle")
            );

            //join
            $paramsObj2 = array(
                array("join",
                    "transe_news",
                    array("transe_news.news_id", "=", "news.news_id")
                ),
                array("join",
                    "language",
                    array("language.language_id", "=", "transe_news.language_id")
                )
            );

            if (App::getLocale() == 'pr') {
                $paramsObj3 = array(
                    array("whereRaw",
                        "transe_news.language_id='1'"
                    )

                );
            } elseif (App::getLocale() == 'en') {
                $paramsObj3 = array(
                    array("whereRaw",
                        "transe_news.language_id='2'"
                    )

                );
            }
            $paramsObj3[] = array("orderBy",
                "news.created_at", "DESC"
            );
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "news.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "transe_news.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "language.deleted_at is null"
            );
            /// ///////////////////////////////////////

            $this->NewsRepository->initialize(null);
            //fetch advertisments
            list($provider, $NewsRepository) = $this->NewsRepository->getFullDetailNews($paramsObj1, $paramsObj2, $paramsObj3);
            try{
                if($article_Page_Number==null || ceil(count($NewsRepository)/12)<$article_Page_Number ||!is_numeric($article_Page_Number))
                    $page_number=1;
                else
                    $page_number=$article_Page_Number;
            }catch (Exception $e)
            {
                $page_number=1;
            }

            return view('news/index', ['newses' => $NewsRepository, 'provider' => $provider,'page_number'=>$page_number]);

        } catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }


    public function show($newsId, $newsGuid)
    {
        try {
            $paramsObj1 = array(
                array("st", "news"),
                array("as", "transe_news", "title", "newsTitle"),
                array("as", "transe_news", "description", "newsDes"),
                array("as", "language", "title", "languageTitle")
            );

            //join
            $paramsObj2 = array(
                array("join",
                    "transe_news",
                    array("transe_news.news_id", "=", "news.news_id")
                ),
                array("join",
                    "language",
                    array("language.language_id", "=", "transe_news.language_id")
                )
            );

            if (App::getLocale() == 'pr') {
                $paramsObj3 = array(
                    array("whereRaw",
                        "transe_news.language_id='1'"
                    )

                );
            } elseif (App::getLocale() == 'en') {
                $paramsObj3 = array(
                    array("whereRaw",
                        "transe_news.language_id='2'"
                    )

                );
            }
            $paramsObj3[] = array("whereRaw",
                "news.news_id=" . $newsId
            );
            $paramsObj3[] = array("whereRaw",
                "news.news_guid= '" . $newsGuid . "'"
            );
            $paramsObj3[] = array("orderBy",
                "news.created_at", "DESC"
            );
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "news.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "transe_news.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "language.deleted_at is null"
            );
            /// ///////////////////////////////////////

            $this->NewsRepository->initialize(null);
            //fetch advertisments
            list($provider, $NewsRepository) = $this->NewsRepository->getFullDetailNews($paramsObj1, $paramsObj2, $paramsObj3);
            if($NewsRepository!=null){
                $news = $NewsRepository[0];
                $news->image = $this->getImage($news->news_guid);
            }else
                $news=null;


            return view('news/show', ['news' => $news, 'provider' => $provider]);

        } catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function getImage($filename)
    {
        try {
            $file = File::get(storage_path('app/news/' . $filename.'.jpeg'));
            return $file;

        } catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }
}
