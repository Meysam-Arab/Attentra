package ir.fardan7eghlim.attentra.views.mission;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.support.design.widget.FloatingActionButton;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.controllers.MissionController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.MissionModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.company.CompanyIndexActivity;
import ir.fardan7eghlim.attentra.views.company.CompanyUserListActivity;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;


public class MissionIndexActivity extends BaseActivity implements Observer{
    private String company_id;
    private String company_guid;
    private CompanyModel company = new CompanyModel();
    Context context=this;
    public static Activity mia;
    private CustomAdapterList CAL;
    private int skip;
    private List<MissionModel> missions;
    private boolean setMoreHide=false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mission_index);
        super.onCreateDrawer();
        mia=this;
        skip=0;
        missions=new ArrayList<>();


        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("company_id") != null && extras.getString("company_guid") != null) {
                company_id = extras.getString("company_id");
                company.setCompanyId(new BigInteger(company_id));
                company_guid = extras.getString("company_guid");
                company.setCompanyGuid(company_guid);
            }
        }
        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab_new_mission);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(context, MissionAddActivity.class);
                i.putExtra("company_id", company_id);
                i.putExtra("company_guid", company_guid);
                context.startActivity(i);
            }
        });

        mainTask();
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

//        mainTask();
    }
    private void mainTask() {
        DialogModel.show(this);

        MissionController mc = new MissionController(getApplicationContext());
        mc.addObserver((Observer) this);
        mc.index(company,skip);
    }
    @Override
    public void update(Observable o, Object arg) {
        DialogModel.hide();

        if(arg != null)
        {
            if (arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Intent i = new Intent(MissionIndexActivity.this,UserHomeActivity.class);
                    MissionIndexActivity.this.startActivity(i);
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).size()>0)
                    if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_DELETE_MISSION))
                    {
                        if(Boolean.parseBoolean(((ArrayList) arg).get(1).toString()) == true)
                            CAL.deleteMission((BigInteger)((ArrayList) arg).get(2));
                        else
                            Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    }else {
                        List<MissionModel> temp= (List<MissionModel>) arg;
                        if(temp.size()<20){
                            setMoreHide=true;
                        }
                        missions.addAll(temp);
                        fillList(missions);
                    }
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
    private void fillList(List<MissionModel> missions) {
        //make list
        ListView lv = (ListView) findViewById(R.id.lsw_mission_list);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(missions), RequestRespondModel.TAG_INDEX_MISSION,company);
        lv.setAdapter(CAL);
        lv.invalidateViews();
        //add more button
        if(skip>0) {
            findViewById(R.id.footer_layout_in).setVisibility(View.VISIBLE);
        }
        if(skip==0) {
            View footerView = ((LayoutInflater) getApplication().getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.footer_layout, null, false);
            lv.addFooterView(footerView);
            findViewById(R.id.footer_layout_in).setVisibility(View.VISIBLE);

            LinearLayout footer_layout = (LinearLayout) findViewById(R.id.footer_layout);
            footer_layout.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(new Utility().isNetworkAvailable(getApplicationContext())){
                        skip += 20;
                        mainTask();
                        findViewById(R.id.footer_layout_in).setVisibility(View.GONE);
                    }else{
                        Utility.displayToast(getBaseContext(), "اینترنت در دسترس نیست!!!", Toast.LENGTH_SHORT);
                    }
                }
            });
        }
        if(setMoreHide){
            findViewById(R.id.footer_layout_in).setVisibility(View.GONE);
            findViewById(R.id.footer_layout_in).setEnabled(false);
        }else{
            findViewById(R.id.footer_layout_in).setVisibility(View.VISIBLE);
            findViewById(R.id.footer_layout_in).setEnabled(true);
        }
    }
}
