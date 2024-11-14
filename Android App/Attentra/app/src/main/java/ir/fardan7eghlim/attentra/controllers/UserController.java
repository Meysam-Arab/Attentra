package ir.fardan7eghlim.attentra.controllers;

import android.content.Context;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Observable;

import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.utils.AppConfig;
import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.Utility;

/**
 * Created by Meysam on 12/21/2016.
 */

public class UserController extends Observable {

    public Context cntx = null;

    public UserController(Context cntx)
    {
        this.cntx = cntx;
    }


    public void login(final UserModel user, final Context cntx){

//        // Tag used to cancel the request
//        String tag_string_req = RequestRespondModel.TAG_LOGIN_USER;
        // Post params to be sent to the server
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        bodyParams.put("user_name", user.getUserName());
        bodyParams.put("password", user.getPassword());
        bodyParams.put("phone_code", Utility.getDeviceCode(cntx)+"#"+Utility.getDeviceName());
        bodyParams.put("tag", RequestRespondModel.TAG_LOGIN_USER);
        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.LoginAddress,RequestRespondModel.TAG_LOGIN_USER,null);

    }


    public void register(final UserModel user)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            bodyParams.put("user_name", user.getUserName());
            bodyParams.put("password", user.getPassword());
            bodyParams.put("email", user.getEmail());
            if(user.getCode() != null)
                bodyParams.put("code", user.getCode());
            bodyParams.put("country_id", user.getCountryId().toString());
            bodyParams.put("phone_code", Utility.getDeviceCode(cntx)+"#"+Utility.getDeviceName());
            bodyParams.put("tag", RequestRespondModel.TAG_REGISTER_USER);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.RegisterAddress,RequestRespondModel.TAG_REGISTER_USER,null);
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
    public void edit(final UserModel user)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("name", user.getName());
            bodyParams.put("family", user.getFamily());
            bodyParams.put("email", user.getEmail());
            bodyParams.put("user_id", user.getId().toString());
            bodyParams.put("user_guid", user.getGuid());
            bodyParams.put("code", user.getCode());
            bodyParams.put("country_id", user.getCountryId().toString());
            bodyParams.put("gender", user.getGender());
            bodyParams.put("tag", RequestRespondModel.TAG_EDIT_USER);
            if(user.getProfilePicture() != null) bodyParams.put("fileLogo", (Utility.getStringImage(user.getProfilePicture())));

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.UserEdit,RequestRespondModel.TAG_EDIT_USER,null);
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
    public void delete(UserModel user) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("user_id", (user.getId().toString()));
        bodyParams.put("user_guid", (user.getGuid()));
        bodyParams.put("tag", RequestRespondModel.TAG_DELETE_USER);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.UserDelete,RequestRespondModel.TAG_DELETE_USER,user.getId());
    }

    public void removePhoneCode(UserModel user) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("user_id", (user.getId().toString()));
        bodyParams.put("user_guid", (user.getGuid()));
        bodyParams.put("tag", RequestRespondModel.TAG_REMOVE_PHONE_CODE_USER);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.UserRemovePhoneCode,RequestRespondModel.TAG_REMOVE_PHONE_CODE_USER,user.getId());
    }

    public void addCompanyMember(final UserModel user,final CompanyModel company){
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("name", user.getName());
            bodyParams.put("family", user.getFamily());
            bodyParams.put("user_name", user.getUserName());
            bodyParams.put("gender", user.getGender());
            bodyParams.put("email", user.getEmail());
            bodyParams.put("password", user.getPassword());
            bodyParams.put("user_type_id", user.getUserType().toString());
            if(user.getProfilePicture() != null) bodyParams.put("fileLogo", (Utility.getStringImage(user.getProfilePicture())) );
            bodyParams.put("code", user.getCode());
            bodyParams.put("company_id", company.getCompanyId().toString());
            bodyParams.put("tag", RequestRespondModel.TAG_ADD_MEMBER_COMPANY);

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.CompanyAddMember,RequestRespondModel.TAG_ADD_MEMBER_COMPANY,null);
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

    public void logOut()
    {

//        UserModel current_user = new SessionModel(cntx).getCurrentUser();
//// Post params to be sent to the server
//        Map<String, String> params = new HashMap<String, String>();
//        params.put("user_name", current_user.getUserName());
//        params.put("password", current_user.getPassword());
//        params.put("tag", RequestRespondModel.TAG_LOGOUT_USER);
//
//        ReqResOperation(Request.Method.POST,params,AppConfig.LogoutAddress,RequestRespondModel.TAG_LOGOUT_USER);

        new SessionModel(cntx).logoutUser(true);
    }

    public void resetPassword(UserModel user, String oldPassword, String newPassword) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("user_id", (user.getId().toString()));
        bodyParams.put("user_guid", (user.getGuid()));
        bodyParams.put("old_password", oldPassword);
        bodyParams.put("new_password", newPassword);
        bodyParams.put("tag", RequestRespondModel.TAG_RESET_PASSWORD);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.ResetPassword,RequestRespondModel.TAG_RESET_PASSWORD,null);
    }

    public void forgetPassword(String email) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        bodyParams.put("email", email);
        bodyParams.put("tag", RequestRespondModel.TAG_RESET_PASSWORD);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.ForgotPassword,RequestRespondModel.TAG_FORGET_PASSWORD,null);
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
                            case RequestRespondModel.TAG_LOGIN_USER:
                                // user successfully logged in
                                //add to session
                                ArrayList<Object> s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_LOGIN_USER);
                                s.add(rrm.getBoolianItem());
                                result =s;

                                SessionModel session = new SessionModel(cntx);
                                DatabaseHandler db = new DatabaseHandler(cntx);
                                db.addUser(rrm.getUser());
                                session.createLoginSession( String.valueOf(rrm.getUser().getId()), rrm.getUser().getGuid().toString(), String.valueOf(rrm.getUser().getUserType()), rrm.getToken());
                                break;
                            case RequestRespondModel.TAG_REGISTER_USER:
                                // user successfully registered
                                result = true;
                                break;
                            case RequestRespondModel.TAG_EDIT_USER:
                            result =true;
                            break;
                            case RequestRespondModel.TAG_DELETE_USER:
                                ArrayList<Object> s2=new ArrayList<Object>();
                                s2.add(RequestRespondModel.TAG_DELETE_USER);
                                s2.add(true);
                                s2.add(obj);
                                result =s2;
                                break;
                            case RequestRespondModel.TAG_ADD_MEMBER_COMPANY:
                                // member added successfuly
                                result = true;
                                break;
                            case RequestRespondModel.TAG_RESET_PASSWORD:
                                // member added successfuly
                                result = true;
                                break;
                            case RequestRespondModel.TAG_FORGET_PASSWORD:
                                // member added successfuly
                                result = true;
                                break;
                            case RequestRespondModel.TAG_REMOVE_PHONE_CODE_USER:
                                // user phone code successfully deleted
                                result = true;
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
//                            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
                            log.insert();
                        }


                    }

                } catch (Exception ex) {
                    // JSON error
                    LogModel log = new LogModel();
                    log.setErrorMessage("message: "+ex.getMessage()+" CallStack: "+ex.getStackTrace());
                    log.setContollerName(this.getClass().getName());
//                    log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
                    log.insert();

                    result = false;
                }

                setChanged();
                notifyObservers(result);
//
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
