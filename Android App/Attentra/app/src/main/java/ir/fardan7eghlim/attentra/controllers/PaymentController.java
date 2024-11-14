package ir.fardan7eghlim.attentra.controllers;

import android.content.Context;

import com.android.volley.AuthFailureError;
import com.android.volley.NoConnectionError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Observable;

import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.PaymentModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.AppConfig;

/**
 * Created by Meysam on 3/7/2017.
 */

public class PaymentController extends Observable{

    public Context cntx = null;

    public PaymentController(Context cntx)
    {
        this.cntx = cntx;
    }

    public void index(UserModel user){

//        // Tag used to cancel the request
//        String tag_string_req = RequestRespondModel.TAG_LOGIN_USER;
        // Post params to be sent to the server
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();
        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("user_id", user.getId().toString());
        bodyParams.put("user_guid", user.getGuid());
        bodyParams.put("tag", RequestRespondModel.TAG_INDEX_PAYMENT);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.PaymentIndex,RequestRespondModel.TAG_INDEX_PAYMENT,null);

    }

    public void key(){

        // Tag used to cancel the request
        // Post params to be sent to the server
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("tag", RequestRespondModel.TAG_KEY_PAYMENT);
        bodyParams.put("name", "cafe_bazar");



        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.PaymentKey,RequestRespondModel.TAG_KEY_PAYMENT,null);

    }

    public void store(PaymentModel payment){

        // Tag used to cancel the request
        // Post params to be sent to the server
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("tag", RequestRespondModel.TAG_STORE_PAYMENT);
        bodyParams.put("product_id", payment.getProductCode());
        bodyParams.put("token", payment.getToken());
        bodyParams.put("payload", payment.getPayload());



        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.PaymentStore,RequestRespondModel.TAG_STORE_PAYMENT,null);

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

                                case RequestRespondModel.TAG_INDEX_PAYMENT:
                                    result = rrm.getItems();
                                    break;
                                case RequestRespondModel.TAG_KEY_PAYMENT:
                                {
                                    // public key successfully recieved
                                    ArrayList<Object> s= new ArrayList<>();
                                    s.add(0,RequestRespondModel.TAG_KEY_PAYMENT);
                                    s.add(rrm.getItems().get(0));
                                    result =  s;
                                    break;
                                }
                                case RequestRespondModel.TAG_STORE_PAYMENT:
                                {
                                    ArrayList<Object> s= new ArrayList<>();
                                    s.add(0,RequestRespondModel.TAG_STORE_PAYMENT);
                                    s.add(true);
                                    result =  s;
                                    break;
                                }
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
