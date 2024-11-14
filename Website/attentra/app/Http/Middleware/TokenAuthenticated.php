<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/25/2017
 * Time: 3:35 PM
 */

namespace App\Http\Middleware;

use App\RequestResponseAPI;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use \Tymon\JWTAuth\Middleware\BaseMiddleware;
use Log;
use JWTAuth;

class TokenAuthenticated extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, \Closure $next)
//    {
//
//        try {
//
//            if (! $user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['user_not_found'], 404);
//            }
//
//        } catch (TokenExpiredException $e) {
//
//            return response()->json(['token_expired'], $e->getStatusCode());
//
//        } catch (TokenInvalidException $e) {
//
//            return response()->json(['token_invalid'], $e->getStatusCode());
//
//        } catch (JWTException $e) {
//
//            return response()->json(['token_absent'], $e->getStatusCode());
//
//        }
//
//        // the token is valid and we have found the user via the sub claim
//        Log::info('in middleware,user:'.json_encode('user'));
//        return response()->json(compact('user'));
//    }

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
//        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
//            return json_encode(['error'=>true]);
//        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
//            return json_encode(['error'=>true]);
//        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
