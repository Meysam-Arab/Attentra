package ir.fardan7eghlim.attentra.controllers;

import android.content.Context;
import android.util.Log;

import com.android.volley.AuthFailureError;
import com.android.volley.NoConnectionError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.io.File;
import java.util.ArrayList;
import java.math.BigInteger;
import java.util.HashMap;
import java.util.Map;
import java.util.Objects;
import java.util.Observable;

import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.AppConfig;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.utils.Utility;

/**
 * Created by Meysam on 3/7/2017.
 */

public class CompanyController  extends Observable{

    public Context cntx = null;

    public CompanyController(Context cntx)
    {
        this.cntx = cntx;
    }

    public void index(final CompanyModel company){

//        // Tag used to cancel the request
//        String tag_string_req = RequestRespondModel.TAG_LOGIN_USER;
        // Post params to be sent to the server
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("name", (company.getName() == null?"":company.getName()));
        bodyParams.put("tag", RequestRespondModel.TAG_INDEX_COMPANY);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.CompanyIndexAddress,RequestRespondModel.TAG_INDEX_COMPANY,null);

    }
    public void listOfMember(CompanyModel company) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("company_id", (company.getCompanyId().toString()));
        bodyParams.put("company_guid", (company.getCompanyGuid()));
        bodyParams.put("tag", RequestRespondModel.TAG_LIST_MEMBERS_COMPANY);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.CompanyListMembers,RequestRespondModel.TAG_LIST_MEMBERS_COMPANY,null);
    }
    public void deleteCompany(CompanyModel company) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("company_id", (company.getCompanyId().toString()));
        bodyParams.put("company_guid", (company.getCompanyGuid()));
        bodyParams.put("tag", RequestRespondModel.TAG_DELETE_COMPANY);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.CompanyDelete,RequestRespondModel.TAG_DELETE_COMPANY,company.getCompanyId());
    }
    public void edit(CompanyModel company) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("company_id", (company.getCompanyId().toString()));
        bodyParams.put("company_guid", (company.getCompanyGuid()));
        bodyParams.put("name", (company.getName()));
        bodyParams.put("time_zone", (company.getTimeZone()));
        if(company.getCompanyPicture() != null) bodyParams.put("fileLogo", (Utility.getStringImage(company.getCompanyPicture())));
        bodyParams.put("tag", RequestRespondModel.TAG_EDIT_COMPANY);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.CompanyEdit,RequestRespondModel.TAG_EDIT_COMPANY,null);
    }
    public void changeSelfRollCallStatus(UserModel user, String rollCall) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("selfRollCall", rollCall);
        bodyParams.put("user_id", (user.getId().toString()));


        bodyParams.put("tag", RequestRespondModel.TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.UserCompanySelfRollCallChange,RequestRespondModel.TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY,null);
    }

    public void register( CompanyModel company)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("name", company.getName());
            bodyParams.put("tag", RequestRespondModel.TAG_STORE_COMPANY);//added by meysam in 20170409-13:04
            bodyParams.put("time_zone", company.getTimeZone());
            if(company.getCompanyPicture() != null) bodyParams.put("fileLogo", (Utility.getStringImage(company.getCompanyPicture())));

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.CompanyStore,RequestRespondModel.TAG_STORE_COMPANY,null);
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

                                case RequestRespondModel.TAG_INDEX_COMPANY:
//                                    Log.d("meysam ",  rrm.getCompanies().toString());
                                    // user_list successfully recieved
                                    result = rrm.getCompanies();
                                    break;
                                case RequestRespondModel.TAG_LIST_MEMBERS_COMPANY:
                                    // user_list successfully recieved
                                    result = rrm.getUsers();
                                    break;
                                case RequestRespondModel.TAG_STORE_COMPANY:
                                    // company successfully registered
                                    result = true;
                                    break;
                                case RequestRespondModel.TAG_DELETE_COMPANY:
                                    // user_list successfully recieved
                                    ArrayList<Object> s=new ArrayList<Object>();
                                    s.add(RequestRespondModel.TAG_DELETE_COMPANY);
                                    s.add(true);
                                    s.add(obj);
                                    result =s;
                                    break;
                                case RequestRespondModel.TAG_EDIT_COMPANY:
                                    // user_list successfully recieved
                                    result =true;
                                    break;
                                case RequestRespondModel.TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY:
                                    // company successfully registered
//                                    s=new ArrayList<Object>();
//                                    s.add(RequestRespondModel.TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY);
//                                    s.add(rrm.getItems().get(0));
//                                    result =s;
//                                    break;
                                    result =true;
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
//                        result = RequestRespondModel.ERROR_LOGIN_FAIL_CODE;
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
