<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/20/2017
 * Time: 12:45 PM
 */

namespace App\Http\Controllers;

use App\Repositories\CountryRepository;
use App\Repositories\LogEventRepository;
use App\RequestResponseAPI;
use App\Country;
use App;
use Log;
use Validator;
use DB;
use JWTAuth;
use Route;
use Illuminate\Http\Request;

class API_CountryController extends Controller
{
    protected $countryRepository;

    /**
     * MissionController constructor.
     * @param CountryRepository $mission_repo
     */
    public function __construct(CountryRepository $country_repo)
    {
        $this->countryRepository = $country_repo;
    }

    public function apiIndex(Request $request)
    {
        ///////////////////check token validation/////////////
       //nothing
        ////////////////////////////////////////////////////////
        ///  //validation
        if (!$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_COUNTRY]);

        }
        try {

//        select track data with
            $paramsObj1 = array(
                array("se", "country", "country_id"),
                array("se", "country", "name")
            );

            /////add deleted at condition to query/////////

            $paramsObj3[] =   array("whereRaw",
                "country.deleted_at is null"
            );

            /// ///////////////////////////////////////
            $this->countryRepository->initialize();

            $countries = $this->countryRepository->getFullDetailCountry($paramsObj1, null, $paramsObj3);



                return json_encode([ 'countries' => $countries, 'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_COUNTRY]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository(0, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_COUNTRY]);
        }

    }
}
