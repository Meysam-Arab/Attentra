<?php

namespace App;


//use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use ViewComponents\Eloquent\EloquentDataProvider;
use Log;
use DB;

class AboutUs extends Model
{

    protected $table = 'about_us';
    protected $primaryKey = 'about_us_id';
//protected attr1;
//protected attr2;
//
//    public function setAttr1($val)
//    {
//        $this->attr1 = $val;
//        return $this;
//    }
//
//    public function setAttr2($val)
//    {
//        $this->attr2 = $val;
//        return $this;
//    }

    ////
    /**
     * Create a new about us instance
     *
     * @param non
     * @return void
     */
    public function __construct()
    {
//        $this -> id = null;
//        $this -> guid = null;
//        $this -> name = null;
//        $this->description = null;
//        $this->latitude = null;
//        $this->longitude = null;
//        $this->postalCode = null;
//        $this->tel = null;
//        $this->tel = null;
//        $this->address = null;
//        $this->isActive = null;
//        $this->isDeleted = null;
//
//        Log::info('in aboutuses constructor: '.$this);
    }
	/**
     * The table associated with the model.
     *
     * @var string
     */


    public function getAboutUses()
	{
		$db = new PDO('mysql:host=localhost;dbname=attentra;charset=utf8mb4', 'root', '');
		$stmt = $db->prepare("CALL SelectAboutUs(?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bindValue(1, 1, PDO::PARAM_INT);	
		$stmt->bindValue(2, '', PDO::PARAM_STR);		
		$stmt->bindValue(3, '', PDO::PARAM_STR);		
		$stmt->bindValue(4, '', PDO::PARAM_STR);		
		$stmt->bindValue(5, '', PDO::PARAM_STR);		
		$stmt->bindValue(6, '', PDO::PARAM_STR);		
		$stmt->bindValue(7, '', PDO::PARAM_STR);		
		$stmt->bindValue(8, '', PDO::PARAM_STR);		
		$stmt->bindValue(9, '', PDO::PARAM_STR);		
		$stmt->bindValue(10, '', PDO::PARAM_BOOL);		
		$stmt->bindValue(11, '', PDO::PARAM_BOOL);	
		$stmt->execute();
		$results= $stmt->fetchAll();
		return $results;
	}
	
	 /**
     * Create a new aboutUs instance.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate the request...

        $aboutUs = new AboutUs;

        $aboutUs->name = $request->name;

        $aboutUs->save();
    }

    /**
     * Create a new aboutUs instance.
     *
     * @param  Request  $request
     * @return Response
     */
    public function select(AboutUs $aboutUs)
    {
        // Validate the request...

        $query = DB::table('about_us')->where('deleted_at',null);
//        $query = aboutuses::select();
//        Log::info('initial query: '.$query);
        if($aboutUs->about_us_id != null){
            $query->where('about_us_id', '=', $aboutUs->about_us_id);
//            Log::info('infinal query: '.$query);
        }

//        if(whatever){
//            $query->where('whatever', '=', $value);
//}

        $aboutUses = $query->get();
        $provider = new EloquentDataProvider($query);


//        $aboutUses = DB::table('aboutuses')
//            ->where('id', '=', $aboutUs->id)
////            ->where('guid', 'like', "%".$aboutUs->guid."%")
////            ->where('name', 'like', "%".$aboutUs->name."%")
////            ->where('description', 'like', "%".$aboutUs->description."%")
////            ->where('latitude', '=', $aboutUs->latitude)
////            ->where('longitude', '=', $aboutUs->longitude)
////            ->where('postalCode', 'like', "%".$aboutUs->postalCode."%")
////            ->where('tel', 'like', "%".$aboutUs->tel."%")
////            ->where('address', 'like', "%".$aboutUs->address."%")
////            ->where('isActive', '=', $aboutUs->isActive)
////            ->where('isDeleted', '=', $aboutUs->isDeleted)
//            ->orderBy('name', 'asc')
//            ->Paginate(5);
//        Log::info('in aboutuses constructor: '.$aboutUses);
//        Log::info('in aboutuses constructor provider object: '.$provider);

//        return $aboutUses;
        return array($provider, $aboutUses);

    }
}

