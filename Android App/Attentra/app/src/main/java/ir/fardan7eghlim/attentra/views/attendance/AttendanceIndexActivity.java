package ir.fardan7eghlim.attentra.views.attendance;

import android.annotation.SuppressLint;
import android.app.Activity;
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
import ir.fardan7eghlim.attentra.controllers.AttendanceController;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.models.UserTypeModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.company.CompanyAddActivity;
import ir.fardan7eghlim.attentra.views.company.CompanyIndexActivity;
import ir.fardan7eghlim.attentra.views.track.TrackStoreActivity;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class AttendanceIndexActivity extends BaseActivity implements Observer {
    public static Activity aia;
    private CustomAdapterList CAL;
    private UserModel user=new UserModel();
    private int skip;
    private List<AttendanceModel> attendances;
    private boolean setMoreHide=false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_attendance_index);
        super.onCreateDrawer();
        aia=this;
        skip=0;
        attendances=new ArrayList<>();

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("user_id") != null) {
                user.setId(new BigInteger(extras.getString("user_id")));
            }
            if (extras.getString("user_guid") != null) {
                user.setGuid(extras.getString("user_guid"));
            }
        }

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab_new_attendance);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent( AttendanceIndexActivity.this, AttendanceStoreActivity.class);
                i.putExtra("user_id", user.getId().toString());
                i.putExtra("user_guid", user.getGuid());
                AttendanceIndexActivity.this.startActivity(i);}
        });


        mainTask();

        SessionModel session = new SessionModel(getApplicationContext());
        if (!session.getCurrentUser().getUserType().equals(UserTypeModel.CEO))
        {
            fab.setVisibility(View.GONE);
        }

        if(!session.getCurrentUser().getUserType().equals(UserTypeModel.CEO) && user.getId().equals(session.getCurrentUser().getId()))
        {
            fab.setVisibility(View.GONE);
        }
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
        AttendanceModel attendance = new AttendanceModel();
        AttendanceController ac = new AttendanceController(getApplicationContext());
        ac.addObserver((Observer) this);
        ac.index(attendance,user.getId().toString(),skip);
    }

    @SuppressLint("WrongConstant")
    @Override
    public void update(Observable o, Object arg) {
        DialogModel.hide();
        if(arg != null)
        {
            if (arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    Intent i = new Intent(AttendanceIndexActivity.this,UserHomeActivity.class);
                    AttendanceIndexActivity.this.startActivity(i);
                    finish();
                }
            }
            else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(AttendanceIndexActivity.this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    finish();
                }else {
                    Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                }
            }
            else if(arg instanceof ArrayList) {
                if(((ArrayList) arg).size()>0)
                    if (((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_DELETE_ATTENDANCE)) {
                        if (Boolean.parseBoolean(((ArrayList) arg).get(1).toString()) == true)
                            CAL.deleteAttendance((BigInteger) ((ArrayList) arg).get(2));
                        else
                            Utility.displayToast(getApplicationContext(), getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    } else {
                        List<AttendanceModel> temp= (List<AttendanceModel>) arg;
                        if(temp.size()<20){
                            setMoreHide=true;
                        }
                        attendances.addAll(temp);
                        fillList(attendances);
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
    private void fillList(List<AttendanceModel> attendances) {
        //make list
        ListView lv = (ListView) findViewById(R.id.lsw_Attendance_list);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(attendances), RequestRespondModel.TAG_INDEX_ATTENDANCE,user.getId().toString());
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
                        Utility.displayToast(getBaseContext(), "اینترنت در دسترس نیست!!!", Toast.LENGTH_LONG);
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

    @Override
    protected void onResume() {

//        mainTask();

        super.onResume();
    }

    @Override
    protected void onPause() {
        finish();
        super.onPause();
    }
}
