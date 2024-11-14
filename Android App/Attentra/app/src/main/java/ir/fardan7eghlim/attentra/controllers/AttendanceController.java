package ir.fardan7eghlim.attentra.controllers;

/**
 * Created by Meysam on 4/9/2017.
 */

import android.content.Context;
import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NoConnectionError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.RetryPolicy;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import java.math.BigInteger;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Observable;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.AppConfig;


/**
 * Created by Meysam on 3/7/2017.
 */

public class AttendanceController  extends Observable {

    public Context cntx = null;

    public AttendanceController(Context cntx)
    {
        this.cntx = cntx;
    }

    public void index(final AttendanceModel attendance, String userId, Integer skip){
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());


        bodyParams.put("tag", RequestRespondModel.TAG_INDEX_ATTENDANCE);
        bodyParams.put("user_id", userId);
        bodyParams.put("skip", skip.toString());
        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceIndex,RequestRespondModel.TAG_INDEX_ATTENDANCE,null);

    }

    public void store( AttendanceModel attendance, String qr_code, Boolean exiting, Boolean inOutMerge)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_STORE_ATTENDANCE);
            bodyParams.put("qr_code", qr_code);
//            bodyParams.put("date_time", attendance.getDateTime());
            bodyParams.put("is_mission", attendance.getMission().toString());
            bodyParams.put("exiting", exiting.toString());
            if(inOutMerge)
                bodyParams.put("in_out_merge", inOutMerge.toString());

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceStore,RequestRespondModel.TAG_STORE_ATTENDANCE,null);
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
    public void storeAutoCeo( AttendanceModel attendance, BigInteger companyId, Boolean exiting, Boolean inOutMerge)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE);
            bodyParams.put("company_id", companyId.toString());

            attendance.setMission(false);//for now we set false until further versions...
            bodyParams.put("is_mission", attendance.getMission().toString());
            bodyParams.put("exiting", exiting.toString());
            if(inOutMerge)
                bodyParams.put("in_out_merge", inOutMerge.toString());
            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceCeoAutoStore,RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE,null);
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
    public void storeSelfLocation(AttendanceModel attendance, Boolean exiting, Boolean inOutMerge)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_STORE_SELF_LOCATION_ATTENDANCE);
            bodyParams.put("coordinates", attendance.getLatitude().toString()+","+attendance.getLongitude().toString());
            attendance.setMission(false);//for now we set false until further versions...
            bodyParams.put("is_mission", attendance.getMission().toString());
            bodyParams.put("exiting", exiting.toString());
            if(inOutMerge)
                bodyParams.put("in_out_merge", inOutMerge.toString());

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceSelfLocationStore,RequestRespondModel.TAG_STORE_SELF_LOCATION_ATTENDANCE,null);
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
    public void storeManual( AttendanceModel attendance, String userId)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_STORE_MANUAL_ATTENDANCE);
            bodyParams.put("is_mission", (attendance.getMission().toString()));
            bodyParams.put("user_id", userId);
            bodyParams.put("start_date_time", (attendance.getStartDateTime() == null?"":attendance.getStartDateTime()));
            bodyParams.put("end_date_time", (attendance.getEndDateTime() == null?"":attendance.getEndDateTime()));


            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceStoreManual,RequestRespondModel.TAG_STORE_MANUAL_ATTENDANCE,null);
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
    public void edit( AttendanceModel attendance, String userId)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_EDIT_ATTENDANCE);
            bodyParams.put("is_mission", (attendance.getMission().toString()));
            bodyParams.put("user_id", userId);
            bodyParams.put("start_date_time", (attendance.getStartDateTime() == null?"":attendance.getStartDateTime()));
            bodyParams.put("end_date_time", (attendance.getEndDateTime() == null?"":attendance.getEndDateTime()));
            bodyParams.put("attendance_id", (attendance.getAttendanceId().toString()));
            bodyParams.put("attendance_guid", (attendance.getAttendanceGuid().toString()));

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceEdit,RequestRespondModel.TAG_EDIT_ATTENDANCE,null);
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
    public void delete( AttendanceModel attendance)
    {
        try
        {
            // Post params to be sent to the server
            Map<String, String> headerParams = new HashMap<String, String>();
            Map<String, String> bodyParams = new HashMap<String, String>();

            headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

            bodyParams.put("tag", RequestRespondModel.TAG_DELETE_ATTENDANCE);
            bodyParams.put("attendance_id", (attendance.getAttendanceId().toString()));
            bodyParams.put("attendance_guid", (attendance.getAttendanceGuid().toString()));

            ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.AttendanceDelete,RequestRespondModel.TAG_DELETE_ATTENDANCE,attendance.getAttendanceId());
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
                            case RequestRespondModel.TAG_STORE_ATTENDANCE:
                                // attendance successfully registered
//                                result = rrm.getUser();
                                ArrayList<Object> s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_STORE_ATTENDANCE);
                                s.add(rrm.getUser());
                                s.add(rrm.getItem());
                                result =s;
                                break;
                            case RequestRespondModel.TAG_STORE_MANUAL_ATTENDANCE:
                                result = true;
                                break;
                            case RequestRespondModel.TAG_INDEX_ATTENDANCE:
//                                throw new AuthFailureError();
                                result = rrm.getItems();
                            break;
                            case RequestRespondModel.TAG_EDIT_ATTENDANCE:
                                result = true;
                                break;
                            case RequestRespondModel.TAG_DELETE_ATTENDANCE:
                                s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_DELETE_ATTENDANCE);
                                s.add(true);
                                s.add(obj);
                                result =s;
                                break;
                            case RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE:
                                // attendance for ceo successfully registered
                                s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE);
                                s.add(true);
                                s.add(rrm.getItem());
                                result =s;
//                                result = true;
                                break;
                            case RequestRespondModel.TAG_STORE_SELF_LOCATION_ATTENDANCE:
                                // attendance for ceo successfully registered
                                s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_STORE_SELF_LOCATION_ATTENDANCE);
                                s.add(true);
                                s.add(rrm.getItem());
                                result =s;
//                                result = true;
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
//                    if(ex instanceof AuthFailureError)
//                    {
//                        result = RequestRespondModel.ERROR_AUTH_FAIL_CODE;
//
//                    }
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
//                    result = RequestRespondModel.ERROR_LOGIN_FAIL_CODE;
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

        //meysam - volly retrying overrided
        RetryPolicy mRetryPolicy = null;
       if(tag.equals(RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE) ||
                tag.equals(RequestRespondModel.TAG_STORE_ATTENDANCE) ||
                tag.equals(RequestRespondModel.TAG_STORE_MANUAL_ATTENDANCE) ||
                tag.equals(RequestRespondModel.TAG_STORE_SELF_LOCATION_ATTENDANCE) )
        {
            mRetryPolicy = new DefaultRetryPolicy(
                    15000,
                    0,
                    (float) 2.0);
        }
        else
        {
            mRetryPolicy = new DefaultRetryPolicy(
                    DefaultRetryPolicy.DEFAULT_TIMEOUT_MS,
                    3,
                    DefaultRetryPolicy.DEFAULT_BACKOFF_MULT);
            sr.setRetryPolicy(mRetryPolicy);
        }



        sr.setRetryPolicy(mRetryPolicy);

        AppController.getInstance().addToRequestQueue(sr);
    }

}

