package ir.fardan7eghlim.attentra.controllers;

/**
 * Created by Meysam on 3/12/2017.
 */

import android.content.Context;
import android.util.Log;

import com.android.volley.AuthFailureError;
import com.android.volley.NoConnectionError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Observable;

import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.TrackModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.AppConfig;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;

public class TrackController extends Observable{
    public Context cntx = null;
    public ArrayList<TrackModel> tracks;

    public TrackController(Context cntx)
    {
        this.cntx = cntx;
        this.tracks = new ArrayList<>();
    }
    public void store(final TrackModel track, UserModel user)
    {
        try
        {

            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

//            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("latitude", track.getLatitude().toString());
            bodyParams.put("longitude", track.getLongitude().toString());
            bodyParams.put("user_id", user.getId().toString());
            bodyParams.put("user_guid", user.getGuid());
            bodyParams.put("track_group", track.getTrackGroup());
            bodyParams.put("battery_power", track.getBatteryPower().toString());
            bodyParams.put("signal_power", track.getSignalPower().toString());
            bodyParams.put("battery_status", track.getBatteryStatus().toString());
            bodyParams.put("charge_status", track.getChargeStatus().toString());
            bodyParams.put("charge_type", track.getChargeType().toString());
            bodyParams.put("tag", RequestRespondModel.TAG_STORE_TRACK);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.TrackStore,RequestRespondModel.TAG_STORE_TRACK,null);
        }
        catch (Exception ex)
        {
            LogModel log = new LogModel();
            log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(this.cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    public void generate()
    {
        try
        {

            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_GENERATE_TRACK);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.TrackGenerate,RequestRespondModel.TAG_GENERATE_TRACK,null);
        }
        catch (Exception ex)
        {
            LogModel log = new LogModel();
            log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(this.cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    public void delete(final TrackModel track)
    {
        try
        {

            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("track_group", track.getTrackGroup());
            bodyParams.put("tag", RequestRespondModel.TAG_DELETE_TRACK);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.TrackDelete,RequestRespondModel.TAG_DELETE_TRACK,track.getTrackId());
        }
        catch (Exception ex)
        {
            LogModel log = new LogModel();
            log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(this.cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    public void index( UserModel user, Integer skip)
    {
        try
        {

            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("user_id", user.getId().toString());
            bodyParams.put("user_guid", user.getGuid());
            bodyParams.put("skip", skip.toString());
            bodyParams.put("tag", RequestRespondModel.TAG_INDEX_TRACK);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.TrackIndex,RequestRespondModel.TAG_INDEX_TRACK,null);
        }
        catch (Exception ex)
        {
            LogModel log = new LogModel();
            log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(this.cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    public void list( TrackModel track, BigInteger lastLoadedId)
    {
        try
        {

            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("last_loaded_id", lastLoadedId.toString());
            bodyParams.put("track_group", track.getTrackGroup());
            bodyParams.put("tag", RequestRespondModel.TAG_INDEX_TRACK);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.TrackList,RequestRespondModel.TAG_LIST_TRACK,null);
        }
        catch (Exception ex)
        {
            LogModel log = new LogModel();
            log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(this.cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    private void ReqResOperation(int method,Map<String, String> headerParams,Map<String, String> bodyParams, String address, String tag, final Object obj)
    {
        final Map<String, String>[] local_params = new Map[2];
        local_params[0] = headerParams;
        local_params[1] = bodyParams;

        StringRequest sr = new StringRequest(Request.Method.POST, address , new Response.Listener<String>() {

            Object result = null;
            @Override
            public void onResponse(String response) {

                RequestRespondModel rrm = new RequestRespondModel(cntx);
                try {


                    rrm.decodeJsonResponse(response.toString());


                    // Check for error node in json
                    if (rrm.getError() == null || rrm.getError() == 0) {

                        switch (rrm.getTag())
                        {

                            case RequestRespondModel.TAG_STORE_TRACK:
                                // track successfully stored

                                result = true;
                                break;
                            case RequestRespondModel.TAG_GENERATE_TRACK:
                                // trackGroup successfully generated
                                ArrayList<Object> s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_GENERATE_TRACK);
                                s.add(rrm.getItems().get(0));

                                result =s;

                                break;
                            case RequestRespondModel.TAG_INDEX_TRACK:
                                // track successfully stored
                                result = rrm.getTracks();
                                break;
                            case RequestRespondModel.TAG_LIST_TRACK:
                                // track successfully stored

                                result = rrm.getTracks();
                                break;
                            case RequestRespondModel.TAG_DELETE_TRACK:
                                // track successfully deleted
                                s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_DELETE_TRACK);
                                s.add(true);
                                s.add(obj);
                                result =s;
                                break;
                            default:

                                break;
                        }

                    } else {

                        if(rrm.getMust_logout())
                        {
                            result = false;
                        }
                        else
                        {
                            result = rrm.getError();

                            // Error in login. Get the error message
                            LogModel log = new LogModel();
                            log.setErrorMessage("message: "+ rrm.getError_msg()+" CallStack: non");
                            log.setContollerName(this.getClass().getName());
                            log.insert();
                        }


                    }

                } catch (Exception ex) {
                    // JSON error
                    LogModel log = new LogModel();
                    log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
                    log.setContollerName(this.getClass().getName());
                    log.insert();

                    result = false;
                }

                setChanged();
                notifyObservers(result);
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Object result = false;
                if(error instanceof AuthFailureError)
                {
                    result = RequestRespondModel.ERROR_AUTH_FAIL_CODE;

                }
//                else if(error instanceof NoConnectionError)
//                {
//                    result = RequestRespondModel.ERROR_AUTH_FAIL_CODE;
//
//                }
                else{
                    LogModel log = new LogModel();
                    log.setErrorMessage("message: "+error.getMessage()+" CallStack: "+error.getStackTrace());
                    log.setContollerName(this.getClass().getName());
                    log.setUserId(null);
                    log.insert();
                }
                setChanged();
                notifyObservers(result);
            }
        }){
            @Override
            protected Map<String,String> getParams(){
                if (local_params[1] == null) local_params[1] = new HashMap<>();
                return local_params[1];
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                if (local_params[0] == null) local_params[0] = new HashMap<>();
                return local_params[0];
            }
        };

        AppController.getInstance().addToRequestQueue(sr);
    }
}
