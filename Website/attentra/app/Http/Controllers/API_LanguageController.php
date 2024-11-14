<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\RequestResponseAPI;
use Config;
use JWTAuth;
use App\Repositories\LanguageRepository;
use App\Repositories\LogEventRepository;
use Route;

class API_LanguageController extends Controller
{

    protected $languageRepository;

    /**
     * LanguageController constructor.
     * @param LanguageRepository $language_repo
     */
    public function __construct(LanguageRepository $language_repo)
    {
        $this->languageRepository = $language_repo;
    }

    public function apiIndex(Request $request)
    {
        ///////////////////check token validation/////////////
//        $token = null;
//        if (session('tokenRefreshed'))
//            $token = session('token');
//        else
//            $token = JWTAuth::parseToken()->getToken()->get();
//        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

            /////validation
            if (!$request->has('tag')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_LANGUAGE]);

            }
            try {

                //select track data with
                $paramsObj1 = array(
                    array("se", "language", "language_id"),
                    array("se", "language", "title"),
                    array("se", "language", "language_direction"),
                    array("se", "language", "code")

                );

                /////add deleted at condition to query/////////

                $paramsObj3[] =   array("whereRaw",
                    "language.deleted_at is null"
                );

                /// ///////////////////////////////////////
                $this->languageRepository->initialize();

                $languages = $this->languageRepository->getFullDetailLanguage($paramsObj1, null, $paramsObj3);



                return json_encode(['languages' => $languages, 'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_LANGUAGE]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository(0, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_LANGUAGE]);
        }

    }
}
