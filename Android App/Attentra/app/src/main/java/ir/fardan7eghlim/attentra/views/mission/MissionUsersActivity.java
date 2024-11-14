package ir.fardan7eghlim.attentra.views.mission;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.widget.ListView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.MissionController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.MissionModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;


public class MissionUsersActivity extends BaseActivity implements Observer{
    private String mission_id;
    private String mission_guid;
    private MissionModel mission = new MissionModel();
    Context context;
    public static Activity mia;
    private ProgressDialog pDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mission_user_list);
        super.onCreateDrawer();
        context = this;
        mia=this;

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("mission_id") != null && extras.getString("mission_guid") != null) {
                mission_id = extras.getString("mission_id");
                mission.setMissionId(new BigInteger(mission_id));
                mission_guid = extras.getString("mission_guid");
                mission.setMissionGuid(mission_guid);
            }
        }
//        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab_new_mission);
//        fab.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                Intent i = new Intent(context, MissionAddActivity.class);
//                i.putExtra("company_id", company_id);
//                i.putExtra("company_guid", company_guid);
//                context.startActivity(i);
//            }
//        });
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();

        MissionController mc = new MissionController(getApplicationContext());
        mc.addObserver((Observer) this);

        mc.listOfMember(mission);
    }
    @Override
    public void update(Observable o, Object arg) {
        pDialog.dismiss();

        if(arg != null)
        {
            if (arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Intent i = new Intent(MissionUsersActivity.this,UserHomeActivity.class);
                    MissionUsersActivity.this.startActivity(i);
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                List<UserModel> users= (ArrayList<UserModel>) ((ArrayList) arg).get(1);
                fillList(users);
            }else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    finish();
                }else {
                    Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                }
            }
            else
            {
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
        }
    }
    //fill list of company
    private void fillList(List<UserModel> users) {
        //make list
        ListView lv = (ListView) findViewById(R.id.list_mission_members_mul);
        lv.setAdapter(new CustomAdapterList(this, new ArrayList<Object>(users), RequestRespondModel.TAG_LIST_MEMBERS_MISSION));
        lv.invalidateViews();
    }
}
