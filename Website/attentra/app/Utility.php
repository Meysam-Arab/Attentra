<?php
/**
 * Created by PhpStorm.
 * User: Amir
 * Date: 12/4/2016
 * Time: 10:13 AM
 */
namespace App;
use Log;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mail;


class Utility extends Authenticatable
{
    //function for filtering query dynemically
    public static function fillQueryFilter($query,$params)
    {
        foreach ($params as $param) {
            switch ($param[0]) {
                case 'orderBy':
                    $query->orderBy($param[1], $param[2]);
                    break;
                case 'take':
                    $query->take($param[1]);
                    break;
                case 'skip':
                    $query->skip($param[1]);
                    break;
                case 'where':
                    $query->where($param[1], $param[2], $param[3]);
                    break;
                case 'groupBy':
                    $query->groupBy($param[1]);
                    break;
                case 'having':
                    $query->having($param[1], $param[2], $param[3]);
                    break;
                case 'orWhere':
                    $query->orWhere($param[1], $param[2]);
                    break;
                case 'whereRaw':
                    $query->whereRaw($param[1]);
                    break;
                case 'orWhereRaw':
                    $query->orWhereRaw($param[1]);
                    break;
                case 'between':
                    $query->whereBetween($param[1],[$param[2], $param[3]]);
                    break;
                case 'orbetween':
                    $query->orWhereBetween($param[1],[$param[2], $param[3]]);
                    break;
                case 'whereIn':
                    $query->whereIn($param[1], $param[2]);
                    break;
            }
        }
        return $query;
    }


    //function for filtering query dynemically
    public static function fillQueryJoin($query,$params)
    {
        foreach ($params as $param) {
            switch ($param[0]) {
                case 'join':
                    $query->join($param[1],$param[2][0],$param[2][1],$param[2][2]);
//                    $query->join($param[1], function ($query,$pt) {
//                        $query->on($pt[2][0], $pt[2][1], $pt[2][2]);
//                        foreach ($pt[3] as $item){
//                            $query->where($item[0], $item[1], $item[2]);
//                        }
//                      });
                    break;
                    case 'leftjoin':
                    $query->leftjoin($param[1],$param[2][0],$param[2][1],$param[2][2]);
                    break;
                case 'crossjoin':
                    $query->crossjoin($param[1],$param[2][0],$param[2][1],$param[2][2]);
                    break;
            }
        }
        return $query;
    }
    //function for aliasing query dynemically
    public static function fillQueryAlias($query,$params,$distinctFlag = NULL)
    {
        $temp=array();
        foreach ($params as $param) {
            switch ($param[0]) {
                case 'se':
                    array_push($temp,$param[1].'.'.$param[2]);
                    break;
                case 'st':
                    array_push($temp,$param[1].'.*');
                    break;
                case 'as':
                    array_push($temp,$param[1].'.'.$param[2].' as '.$param[3]);
                    break;
            }
        }
        IF($distinctFlag==true){
            $query-> select($temp)->distinct();
        }else
            $query-> select($temp);


        return $query;
    }
    //remove foreign guid
    public static function removeForeignGuid($list,$a,$b)
    {
        foreach ($list as $obj)
        {
            if($obj[$a]!=Auth()->user()->id)
            {
                $obj[$b]=null;
            }
        }
        return $list;
    }
    //size of file in link
    public static function curl_get_file_size($url) {
        // Assume failure.
        $result = -1;
        $curl = curl_init( $url );
        // Issue a HEAD request and follow any redirects.
        curl_setopt( $curl, CURLOPT_NOBODY, true );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        //curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );
        $data = curl_exec( $curl );
        curl_close( $curl );
        if( $data ) {
            $content_length = "unknown";
            $status = "unknown";

            if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
                $status = (int)$matches[1];
            }

            if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
                $content_length = (int)$matches[1];
            }
            // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            if( $status == 200 || ($status > 300 && $status <= 308) ) {
                $result = $content_length;
            }
        }
        return $result;
    }

    public static function convert($string)
    {
        $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $estern = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return str_replace($estern, $western, $string);
    }
    public static function cleanDateTime($dateTime)
    {
        $dateTime = str_replace("/", "", $dateTime);
        $dateTime = str_replace(" ", "", $dateTime);
        $dateTime = str_replace(":", "", $dateTime);
        $dateTime = str_replace("-", "", $dateTime);

        return $dateTime;
    }
    public static function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public  static  function sendMail($email, $message){

        $messageText = $message;
        Mail::raw($message, function($message) use ( $email,$messageText)
        {
            $message->from('info@attentra.ir', 'No Reply');

            $message->to($email)->subject('تغییر رمز عبور');

            $message->setBody('
<div style="font-family:IRANSans,\'B Yekan\',\'2 Yekan\',Yekan,Tahoma,\'Helvetica Neue\',Arial,sans-serif;background-color: #f3f3f3;display: block;height: 1000px;width: 966px;margin:10px auto;">
    <div style="display: block;height: 500px;width: 650px;margin:0px auto;">
        <div >
            <p style="display: block;height: 50px; background-color: #7cc576;margin:0 auto;font-size: 30px;text-align:center;padding-top: 15px; ">
          آتنترا - اولین سامانه برخط و جامع ردیابی افراد
            </p>
        </div >
        <div style="background-color: white;padding-top: 20px;height: 600px;">
            <div style="background-color: #d4d4d4;height: 120px; width: 600px;margin:0 auto;font-size: 20px;text-align:right;padding-top: 0px;">
                <div style="float:right;color:white;background-color:#ff2626;height:120px;text-align:center;display: block;width: 100px;">

                </div>
                <div style="float:right;display: block;width: 500px;">
                    <p  style="margin:0;display: block;font-size: 20px;text-align:right;padding: 0px 5px 0 0; ">کاربر گرامی</p>

                    <p style="display: block;font-family:IRANSans,\'B Yekan\',\'2 Yekan\',Yekan,Tahoma,\'Helvetica Neue\',Arial,sans-serif;font-size:16px;text-align:right;padding: 0px 5px 0 0; ">
                       رمز عبور جدید شما به قرار زیر میباشد
                    </p >
                </div>
            </div>
            <div style="background-color: #4cae4c;height: 65px;width: 570px;margin:10px 40px;font-size: 20px;text-align:right;">
                <div style="background-color: #4cae4c;height: 35px;width: 570px;font-size: 20px;text-align:right;">
                    :رمز عبور شما
                </div>
                <div style="background-color: #5cb85c;height: 30px;width: 570px;font-size: 20px;text-align:right;">
                    '.$messageText.'
                </div>
            </div>
            <div style="background-color: #d4d4d4;height: 95px;width: 600px;margin:10px auto;font-size: 20px;text-align:right;padding-top: 15px;">
با تقدیم احترام
            </div>
        </div>
    </div>
    <div style="display: block;height: 230px;width: 650px;margin:0px auto;">
        <div style="display:block;height: 30px;background-color: #0a0a0a;color:white;padding-top: 10px;">
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">

				نرم افزار آتنترا
            </div>
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
   تماس با ما
            </div>

        </div>
        <div style="display:block;height: 100px;background-color: #0d1827;color:white;padding-top: 10px;">
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">

                <div style="color:#0b93d5">
				 <a href="http://www.attentra.ir" target ="_blank">سایت رسمی</a>
				</div>


            </div>
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
                <div>شماره تماس واحد پشتیبانی</div>
                <div style="color:#0b93d5">071-32221402</div>
                <div>ایمیل واحد پشتیبانی</div>
                <div style="color:#0b93d5">support@attentra.ir</div>
            </div>
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">

            </div>
        </div>
        <div style="display:block;height: 40px;background-color: #0a0a0a;color:white;padding-top: 10px;">
            <div style="float: right;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding-right: 15px;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
               محصولی از
			    <a href="http://www.fardan7eghlim.ir" target ="_blank">شرکت هوش مصنوعی فردان هفت اقلیم</a>
            </div>
        </div>
    </div>
</div>
', 'text/html'); // for HTML rich messages
        });
    }

    public  static  function sendMailFeedBack($email, $title, $description){

        $messageText = " عنوان: ".$title.PHP_EOL." توضیحات: ".$description;
//        Mail::raw($messageText, function($messageText) use ( $email,$messageText)
//        {
//            $messageText->from('info@attentra.ir', 'No Reply - new Feedback');
//
//            $messageText->to($email)->subject('پیام جدید در قسمت ارتباط با ما');
//
//            $messageText->setBody($messageText); // for HTML rich messages
//        });

        Mail::raw($messageText, function($message) use ( $email,$messageText) {
            $message->from('info@attentra.ir', 'No Reply');
            $message->to($email)->subject('پیام جدید در قسمت ارتباط با ما');
            $message->setBody('<div style="font-family:IRANSans,\'B Yekan\',\'2 Yekan\',Yekan,Tahoma,\'Helvetica Neue\',Arial,sans-serif;background-color: #f3f3f3;display: block;height: 1000px;width: 966px;margin:10px auto;">
    <div style="background-color: #5cb85c;height: 30px;width: 570px;font-size: 20px;text-align:right;">
                    '.$messageText.'
                </div></div>
', 'text/html'); // for HTML rich messages
        });

    }

    public static function pointInCircle($point, $circle){//http://alienryderflex.com/polygon/
        //meysam - point as an array ([lat, lng]) and the polygon as an array of
        // points ([[lat, lng],[lat, lng],...])
        $return = false;

//        Log::info('point'.json_encode($point));
//        Log::info('circle'.json_encode($circle));
//
        $latA    = $point[0]*(M_PI/180); // M_PI is a php constant
        $longA     = $point[1]*(M_PI/180);
        $latB     = $circle[0]*(M_PI/180);
        $longB    = $circle[1]*(M_PI/180);

//        Log::info('$longA'.json_encode($longA));
//        Log::info('$latA'.json_encode($latA));
//        Log::info('$longB'.json_encode($longB));
//        Log::info('$latB'.json_encode($latB));

        $subBA       = bcsub ($longB, $longA, 20);
        $cosLatA     = cos($latA);
        $cosLatB     = cos($latB);
        $sinLatA     = sin($latA);
        $sinLatB     = sin($latB);

        $distance = 6371*acos($cosLatA*$cosLatB*cos($subBA)+$sinLatA*$sinLatB);
//        Log::info('$distance km:'.json_encode($distance));
        $distance = $distance * 1000;
//        Log::info('$distance meter:'.json_encode($distance));

        /////////////////////v2///////////////
//        $distance = Utility::vincentyGreatCircleDistance($point[0], $point[1], $circle[0], $circle[1], $earthRadius = 6371000);
//        $distance = Utility::distance($point[0], $point[1], $circle[0], $circle[1], "K")*1000;
        /// /////////////////////////////////
//        Log::info('$distance meter:'.json_encode($distance));
//        Log::info('$circle meter:'.json_encode($circle[2]));

        if($distance < $circle[2])
            $return = true;
        return $return ;

    }
//
//public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
//
//        $theta = $lon1 - $lon2;
//        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
//        $dist = acos($dist);
//        $dist = rad2deg($dist);
//        $miles = $dist * 60 * 1.1515;
//        $unit = strtoupper($unit);
//
//        if ($unit == "K") {
//            return ($miles * 1.609344);
//        } else if ($unit == "N") {
//            return ($miles * 0.8684);
//        } else {
//            return $miles;
//        }
//    }
//
//    public static function pointInPolygon($point, $polygon){//http://alienryderflex.com/polygon/
//        //meysam - point as an array ([lat, lng]) and the polygon as an array of
//        // points ([[lat, lng],[lat, lng],...])
//        $return = false;
//        foreach($polygon as $k=>$p){
//            if(!$k) $k_prev = count($polygon)-1;
//            else $k_prev = $k-1;
//
//            if(($p[1]< $point[1] && $polygon[$k_prev][1]>=$point[1] || $polygon[$k_prev][1]< $point[1] && $p[1]>=$point[1]) && ($p[0]<=$point[0] || $polygon[$k_prev][0]<=$point[0])){
//                if($p[0]+($point[1]-$p[1])/($polygon[$k_prev][1]-$p[1])*($polygon[$k_prev][0]-$p[0])<$point[0]){
//                    $return = !$return;
//                }
//            }
//        }
//        return $return;
//    }
//
////    public static function pointInPolygon($point, $polygon, $ipointOnVertex = true)
////    {
////        $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
////        $pointOnVertex = $ipointOnVertex;
////
////        // Transform string coordinates into arrays with x and y values
////        $point = self::pointStringToCoordinates($point);
////        $vertices = array();
////        foreach ($polygon as $vertex) {
////            $vertices[] = self::pointStringToCoordinates($vertex);
////        }
////
////        // Check if the point sits exactly on a vertex
////        if ($pointOnVertex == true and self::pointOnVertex($point, $vertices) == true) {
////            return "vertex";
////        }
////
////        // Check if the point is inside the polygon or on the boundary
////        $intersections = 0;
////        $vertices_count = count($vertices);
////
////        for ($i=1; $i < $vertices_count; $i++) {
////            $vertex1 = $vertices[$i-1];
////            $vertex2 = $vertices[$i];
////            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
////                return "boundary";
////            }
////            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
////                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
////                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
////                    return "boundary";
////                }
////                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
////                    $intersections++;
////                }
////            }
////        }
////        // If the number of edges we passed through is odd, then it's in the polygon.
////        if ($intersections % 2 != 0) {
////            return "inside";
////        } else {
////            return "outside";
////        }
////    }
////
////    private static function pointOnVertex($point, $vertices) {
////        foreach($vertices as $vertex) {
////            if ($point == $vertex) {
////                return true;
////            }
////        }
////
////    }
////
////    private static function pointStringToCoordinates($pointString) {
////        $coordinates = explode(" ", $pointString);
////        return array("x" => $coordinates[0], "y" => $coordinates[1]);
////    }
//
//    /**
//     * Calculates the great-circle distance between two points, with
//     * the Vincenty formula.
//     * @param float $latitudeFrom Latitude of start point in [deg decimal]
//     * @param float $longitudeFrom Longitude of start point in [deg decimal]
//     * @param float $latitudeTo Latitude of target point in [deg decimal]
//     * @param float $longitudeTo Longitude of target point in [deg decimal]
//     * @param float $earthRadius Mean earth radius in [m]
//     * @return float Distance between points in [m] (same as earthRadius)
//     */
//    public static function vincentyGreatCircleDistance(
//        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
//    {
//        // convert from degrees to radians
//        $latFrom = deg2rad($latitudeFrom);
//        $lonFrom = deg2rad($longitudeFrom);
//        $latTo = deg2rad($latitudeTo);
//        $lonTo = deg2rad($longitudeTo);
//
//        $lonDelta = $lonTo - $lonFrom;
//        $a = pow(cos($latTo) * sin($lonDelta), 2) +
//            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
//        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
//
//        $angle = atan2(sqrt($a), $b);
//        return $angle * $earthRadius;
//    }


    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static function pointInPolygon($point, $polygon){//http://alienryderflex.com/polygon/
        //meysam - point as an array ([lat, lng]) and the polygon as an array of
        // points ([[lat, lng],[lat, lng],...])
        $return = false;
        foreach($polygon as $k=>$p){
            if(!$k) $k_prev = count($polygon)-1;
            else $k_prev = $k-1;

            if(($p[1]< $point[1] && $polygon[$k_prev][1]>=$point[1] || $polygon[$k_prev][1]< $point[1] && $p[1]>=$point[1]) && ($p[0]<=$point[0] || $polygon[$k_prev][0]<=$point[0])){
                if($p[0]+($point[1]-$p[1])/($polygon[$k_prev][1]-$p[1])*($polygon[$k_prev][0]-$p[0])<$point[0]){
                    $return = !$return;
                }
            }
        }
        return $return;
    }

//    public static function pointInPolygon($point, $polygon, $ipointOnVertex = true)
//    {
//        $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
//        $pointOnVertex = $ipointOnVertex;
//
//        // Transform string coordinates into arrays with x and y values
//        $point = self::pointStringToCoordinates($point);
//        $vertices = array();
//        foreach ($polygon as $vertex) {
//            $vertices[] = self::pointStringToCoordinates($vertex);
//        }
//
//        // Check if the point sits exactly on a vertex
//        if ($pointOnVertex == true and self::pointOnVertex($point, $vertices) == true) {
//            return "vertex";
//        }
//
//        // Check if the point is inside the polygon or on the boundary
//        $intersections = 0;
//        $vertices_count = count($vertices);
//
//        for ($i=1; $i < $vertices_count; $i++) {
//            $vertex1 = $vertices[$i-1];
//            $vertex2 = $vertices[$i];
//            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
//                return "boundary";
//            }
//            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
//                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
//                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
//                    return "boundary";
//                }
//                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
//                    $intersections++;
//                }
//            }
//        }
//        // If the number of edges we passed through is odd, then it's in the polygon.
//        if ($intersections % 2 != 0) {
//            return "inside";
//        } else {
//            return "outside";
//        }
//    }
//
//    private static function pointOnVertex($point, $vertices) {
//        foreach($vertices as $vertex) {
//            if ($point == $vertex) {
//                return true;
//            }
//        }
//
//    }
//
//    private static function pointStringToCoordinates($pointString) {
//        $coordinates = explode(" ", $pointString);
//        return array("x" => $coordinates[0], "y" => $coordinates[1]);
//    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public static function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
?>

