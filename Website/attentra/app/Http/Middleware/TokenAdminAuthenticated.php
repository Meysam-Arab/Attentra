<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/25/2017
 * Time: 3:35 PM
 */

namespace App\Http\Middleware;

use App\Repositories\UserTypeRepository;
use App\RequestResponseAPI;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use \Tymon\JWTAuth\Middleware\BaseMiddleware;
use Log;
use JWTAuth;

class TokenAdminAuthenticated extends BaseMiddleware
{

    public function handle($request, \Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }
        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
//            Log::info('token expire,$e:'.json_encode($e));
            try
            {
                $token=JWTAuth::refresh($token);
                session(['tokenRefreshed' => true]);
                session(['token' => $token]);
                JWTAuth::setToken($token);
//                Log::info('token changed after expire,$e:'.json_encode($token));
            }
            catch (TokenBlacklistedException $tbex)
            {
//                Log::info('token blacklisted in expire,$e:'.json_encode($e));
                return $this->respond('tymon.jwt.blacklisted', RequestResponseAPI::ERROR_TOKEN_BLACKLISTED_CODE, $e->getStatusCode(), [$e]);
            }

//            JWTAuth::setToken($token);
            $user = $this->auth->authenticate($token);
        } catch (JWTException $e) {
//            Log::info('exception whaaa!!,$e:'.json_encode($e));
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }


        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        if ($user->user_type_id != UserTypeRepository::Admin) {
            return response()->json(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => $request->input('tag')]);
//            return json_encode(['error'=>RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => $request->input('tag')]);
        }


        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
