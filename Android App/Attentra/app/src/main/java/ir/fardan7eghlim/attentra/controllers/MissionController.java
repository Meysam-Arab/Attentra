//Meysam Arab//
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
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.MissionModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.AppConfig;

/**
 * Created by Amir on 3/18/2017.
 */

public class MissionController   extends Observable {
    public Context cntx = null;

    public MissionController(Context cntx)
    {
        this.cntx = cntx;
    }

    public void index(final CompanyModel company, Integer skip){
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("company_id", (company.getCompanyId().toString()));
        bodyParams.put("company_guid", (company.getCompanyGuid()));
        bodyParams.put("skip", skip.toString());
        bodyParams.put("tag", RequestRespondModel.TAG_INDEX_MISSION);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.MissionIndex,RequestRespondModel.TAG_INDEX_MISSION,null);

    }

    public void store(CompanyModel company, MissionModel mission, String userIds){
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("company_id", (company.getCompanyId().toString()));
        bodyParams.put("company_guid", (company.getCompanyGuid()));
        bodyParams.put("title", (mission.getTitle()));
        bodyParams.put("start_date_time", (mission.getStartDateTime()));
        bodyParams.put("end_date_time", (mission.getEndDateTime()));
        bodyParams.put("missionperson", userIds);
        bodyParams.put("description", (mission.getDescription()));
        bodyParams.put("tag", RequestRespondModel.TAG_STORE_MISSION);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.MissionStore,RequestRespondModel.TAG_STORE_MISSION,null);

    }
    public void edit(CompanyModel company, MissionModel mission, String userIds) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("company_id", (company.getCompanyId().toString()));
        bodyParams.put("company_guid", (company.getCompanyGuid()));
        bodyParams.put("title", (mission.getTitle()));
        bodyParams.put("start_date_time", (mission.getStartDateTime()));
        bodyParams.put("end_date_time", (mission.getEndDateTime()));
        bodyParams.put("mission_id", (mission.getMissionId().toString()));
        bodyParams.put("mission_guid", (mission.getMissionGuid()));
        bodyParams.put("missionperson", userIds);
        bodyParams.put("description", (mission.getDescription()));
        bodyParams.put("tag", RequestRespondModel.TAG_EDIT_MISSION);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.MissionEdit,RequestRespondModel.TAG_EDIT_MISSION,null);
    }

    public void delete(MissionModel mission) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("mission_id", (mission.getMissionId().toString()));
        bodyParams.put("mission_guid", (mission.getMissionGuid()));
        bodyParams.put("tag", RequestRespondModel.TAG_DELETE_MISSION);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.MissionDelete,RequestRespondModel.TAG_DELETE_MISSION,mission.getMissionId());
    }

    public void listOfMember(MissionModel mission) {
        Map<String, String> headerParams = new HashMap<String, String>();
        Map<String, String> bodyParams = new HashMap<String, String>();

        headerParams.put("Authorization", "Bearer "+ new SessionModel(cntx).getStoredToken());

        bodyParams.put("mission_id", (mission.getMissionId().toString()));
        bodyParams.put("mission_guid", (mission.getMissionGuid()));
        bodyParams.put("tag", RequestRespondModel.TAG_LIST_MEMBERS_MISSION);

        ReqResOperation(Request.Method.POST,headerParams,bodyParams, AppConfig.MissionListMembers,RequestRespondModel.TAG_LIST_MEMBERS_MISSION,null);
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

                            case RequestRespondModel.TAG_INDEX_MISSION:
                                // Mission successfully recieved
                                result = rrm.getItems();
                                break;
                            case RequestRespondModel.TAG_STORE_MISSION:
                                // Mission successfully stored
                                result =true;
                                break;
                            case RequestRespondModel.TAG_EDIT_MISSION:
                                // Mission successfully updated
                                result =true;
                                break;
                            case RequestRespondModel.TAG_DELETE_MISSION:
                                // Mission successfully deleted
                                ArrayList<Object> s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_DELETE_MISSION);
                                s.add(true);
                                s.add(obj);
                                result =s;
                                break;
                            case RequestRespondModel.TAG_LIST_MEMBERS_MISSION:
                                // user_list successfully recieved
                                s=new ArrayList<Object>();
                                s.add(RequestRespondModel.TAG_LIST_MEMBERS_MISSION);
                                s.add(rrm.getUsers());
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
