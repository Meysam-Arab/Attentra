package ir.fardan7eghlim.attentra.views.track;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
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
import ir.fardan7eghlim.attentra.controllers.TrackController;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.TrackModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class TrackIndexActivity extends BaseActivity implements Observer{
    public static Activity tia;
    private CustomAdapterList CAL;
    private int skip;
    private ArrayList<TrackModel> tracks;
    private UserModel user;
    private boolean setMoreHide=false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_track_index);
        super.onCreateDrawer();

        tia=this;
        skip=0;
        tracks=new ArrayList<>();
        user=new UserModel();

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("user_id") != null) {
                user.setId(new BigInteger(extras.getString("user_id")));
            }
            if (extras.getString("user_guid") != null) {
                user.setGuid(extras.getString("user_guid"));
            }
        }

        mainTask();
    }
    private void mainTask() {
        DialogModel.show(this);

        TrackController tc = new TrackController(getApplicationContext());
        tc.addObserver((Observer) this);
        tc.index(user,skip);
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

//        mainTask();

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
                    Utility.displayToast(TrackIndexActivity.this,getString(R.string.msg_OperationFail), Toast.LENGTH_SHORT);
                    Intent i = new Intent(TrackIndexActivity.this,UserHomeActivity.class);
                    TrackIndexActivity.this.startActivity(i);
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).size()>0)
                    if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_DELETE_TRACK))
                    {
                        if(Boolean.parseBoolean(((ArrayList) arg).get(1).toString()) == true)
                            CAL.deleteTrackGroup((BigInteger)((ArrayList) arg).get(2));
                        else
                            Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    }else {
                        List<TrackModel> temp= (List<TrackModel>) arg;
                        if(temp.size()<20){
                            setMoreHide=true;
                        }
                        tracks.addAll(temp);
                        fillList(tracks);
                    }
            }
            else if(arg instanceof Integer)
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
    private void fillList(List<TrackModel> tracks) {
        //make list
        ListView lv = (ListView) findViewById(R.id.lsw_track_list);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(tracks), RequestRespondModel.TAG_INDEX_TRACK);
        lv.setAdapter(CAL);
        lv.invalidateViews();
        //add more button
        if(skip>0) {
            findViewById(R.id.footer_layout_in).setVisibility(View.VISIBLE);
            findViewById(R.id.footer_layout_in).setEnabled(true);
        }
        if(skip==0) {
            View footerView = ((LayoutInflater) getApplication().getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.footer_layout, null, false);
            lv.addFooterView(footerView);
            findViewById(R.id.footer_layout_in).setVisibility(View.VISIBLE);
            findViewById(R.id.footer_layout_in).setEnabled(true);

            LinearLayout footer_layout = (LinearLayout) findViewById(R.id.footer_layout);
            footer_layout.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(new Utility().isNetworkAvailable(getApplicationContext())){
                        skip += 20;
                        mainTask();
                        findViewById(R.id.footer_layout_in).setVisibility(View.GONE);
                        findViewById(R.id.footer_layout_in).setEnabled(false);
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
