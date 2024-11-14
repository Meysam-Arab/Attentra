<?php

namespace App\Http\Middleware;
use App\Repositories\Contracts\UserTypeRepositoryInterface;
use Closure;
use Redirect;
use Session;
use Auth;


class CeoAuthenticated
{
    protected $userTypeRepo;

    public function __construct(UserTypeRepositoryInterface $userTypeRepo)
    {

        $this->userTypeRepo = $userTypeRepo;
        ///////////////////////
//        Log::info('inside __construct in user repository:'.json_encode($this->user));
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()&&(auth()->user()->user_type_id == $this->userTypeRepo->getAdminCode()|auth()->user()->user_type_id == $this->userTypeRepo->getCEOCode())  ) {
            if(! Session::has('CompanyCount'))
            {
                Session::flush(); // removes all session data
                Auth::logout();
                return redirect('/')->with('messagecheck','login');
            }
            return $next($request);
        }
        else{
            if(auth()->check())
                return abort(401, 'Unauthorized');
            else
                return redirect('/')->with('messagecheck','login');
//            return abort(401, 'Unauthorized');
        }

//        return abort(401, 'Unauthorized');
    }
}
