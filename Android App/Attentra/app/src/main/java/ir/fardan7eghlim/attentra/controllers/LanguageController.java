package ir.fardan7eghlim.attentra.controllers;

import android.content.Context;

import com.android.volley.AuthFailureError;
import com.android.volley.NoConnectionError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;
import java.util.Observable;

import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.AppConfig;

/**
 * Created by Meysam on 3/7/2017.
 */

public class LanguageController extends Observable{

    public Context cntx = null;

    public LanguageController(Context cntx)
    {
        this.cntx = cntx;
    }

    public void index(){

//        // Tag used to cancel the request
//        String tag_string_req = RequestRespondModel.TAG_LOGIN_USER;
        // Post params to be sent to the server
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

//        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("tag", RequestRespondModel.TAG_INDEX_LANGUAGE);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.LanguageIndex,RequestRespondModel.TAG_INDEX_LANGUAGE,null);

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

                                case RequestRespondModel.TAG_INDEX_LANGUAGE:
                                    result = rrm.getItems();
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
//                    else if(error instanceof NoConnectionError)
//                    {
//                        result = RequestRespondModel.ERROR_AUTH_FAIL_CODE;
//
//                    }
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
