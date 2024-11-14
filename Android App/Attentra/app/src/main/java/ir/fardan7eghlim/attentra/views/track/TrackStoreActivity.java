package ir.fardan7eghlim.attentra.views.track;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.content.ComponentName;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.ServiceConnection;
import android.content.pm.PackageManager;
import android.graphics.drawable.AnimationDrawable;
import android.location.LocationManager;
import android.os.Build;
import android.os.IBinder;
import android.support.annotation.NonNull;
import android.support.annotation.RequiresApi;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Observable;
import java.util.Observer;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.TrackController;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.services.track.TrackingService;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.attendance.AttendanceStoreSelfLocationActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;


public class TrackStoreActivity extends BaseActivity implements Observer {

    boolean mBound = false;

    ImageView fab;
    TextView status;


    private static final String[] INITIAL_PERMS = {
            Manifest.permission.ACCESS_FINE_LOCATION,
    };


    private static final int INITIAL_REQUEST = 1337;
    private static final int LOCATION_REQUEST = INITIAL_REQUEST + 1;




    // UI references.

    private ProgressBar spinner;

//    @RequiresApi(api = Build.VERSION_CODES.M)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_track_store);
        super.onCreateDrawer();

        if (Build.VERSION.SDK_INT >= 23)
        {
            // Marshmallow+
            if (!canAccessLocation())
            {
                requestPermissions(INITIAL_PERMS, INITIAL_REQUEST);
                return;
            }
        }
        else
        {
            // Pre-Marshmallow
            initialize();
        }

//        if (!canAccessLocation()) {
//            requestPermissions(INITIAL_PERMS, INITIAL_REQUEST);
//        }


    }

    private void initialize()
    {
        spinner = (ProgressBar)findViewById(R.id.pb_track_store);
        spinner.setVisibility(View.GONE);

        ////////////////////gps check////////////////////////////
        isGPSEnabled(true);

        fab = (ImageView) findViewById(R.id.fab_store_track);
        status= (TextView) findViewById(R.id.traking_status_ats);
        status.setText(getResources().getText(R.string.msg_EyeClick));
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

//                if (!canAccessLocation()) {
//                    requestPermissions(INITIAL_PERMS, INITIAL_REQUEST);
//                }
                if(isGPSEnabled(true)) {

                    if (Utility.isTrackingServiceRunning()) {

                        showEndDialog();


                    } else {
                        try {
                            if (!Utility.isInternetAvailable()) {
                                Utility.displayToast(getApplicationContext(), getApplicationContext().getString(R.string.msg_ConnectionError), Toast.LENGTH_SHORT);
                                //                            finish();
                                //                            return;
                            } else {
                                sendGenerateTrackGroupRequest();

                            }
                        } catch (IOException e) {
                            e.printStackTrace();
                        } catch (InterruptedException e) {
                            e.printStackTrace();
                        }


//                        // Bind to LocalService
//                        Intent intent = new Intent(getApplicationContext(), TrackingService.class);
//                        getApplicationContext().startService(intent);
//
//                        // Progress dialog
//                        fab.setImageResource(R.drawable.in_tracking);
//                        AnimationDrawable anim = (AnimationDrawable) fab.getDrawable();
//                        anim.start();
//                        status.setText(getResources().getText(R.string.msg_InProgress));
                    }
                }
            }
        });

        if(Utility.isTrackingServiceRunning())
        {
            fab.setImageResource(R.drawable.in_tracking);
            AnimationDrawable anim = (AnimationDrawable) fab.getDrawable();
            anim.start();
            status.setText(R.string.msg_InProgress);
            return;
        }
    }

    private void showEndDialog() {
        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
        alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListener);
        alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListener);
        alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
        alertDialogBuilder.show();
    }

    DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which) {
                case DialogInterface.BUTTON_POSITIVE:

                    Intent intent = new Intent(getApplicationContext(), TrackingService.class);
                    getApplicationContext().stopService(intent);

                    spinner.setVisibility(View.GONE);
                    fab.setImageResource(R.drawable.eye_green);
                    status.setText(R.string.msg_EyeClick);

                    SessionModel session = new SessionModel(getApplicationContext());
                    session.removeStoredTrackGroup();

                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked
                    break;
            }
        }
    };
    private void sendGenerateTrackGroupRequest() {
        DialogModel.show(this);
        TrackController tc = new TrackController(getApplicationContext());
        tc.addObserver(this);

        tc.generate();
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

        if(Utility.isTrackingServiceRunning())
        {
            fab.setImageResource(R.drawable.in_tracking);
            AnimationDrawable anim = (AnimationDrawable) fab.getDrawable();
            anim.start();
            status.setText(R.string.msg_InProgress);
            return;
        }

    }
    @SuppressLint("WrongConstant")
    @Override
    public void update(Observable o, Object arg) {
//        Utility.displayToast(getApplicationContext(),arg.toString(),Toast.LENGTH_LONG);
        DialogModel.hide();
        if(arg != null) {
            if (arg instanceof Boolean) {
                if (Boolean.parseBoolean(arg.toString()) == false) {
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                } else {
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                }
            }    else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(TrackStoreActivity.this, UserLoginActivity.class);
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
//                Utility.displayToast(getApplicationContext(),"hiiii",Toast.LENGTH_LONG);
                if(((ArrayList) arg).size()>0)
                    if (((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_GENERATE_TRACK)) {

                        SessionModel session = new SessionModel(getApplicationContext());
                        session.setStoredTrackGroup(((ArrayList) arg).get(1).toString());
                        // Bind to LocalService
                        Intent intent = new Intent(getApplicationContext(), TrackingService.class);
                        getApplicationContext().startService(intent);

                        // Progress dialog
                        fab.setImageResource(R.drawable.in_tracking);
                        AnimationDrawable anim = (AnimationDrawable) fab.getDrawable();
                        anim.start();
                        status.setText(getResources().getText(R.string.msg_InProgress));

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

    }


//    @Override
//    public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults) {
//
//        switch(requestCode) {
//            case LOCATION_REQUEST:
//                if (canAccessLocation()) {
//                    doLocationThing();
//                }
//                else {
//                }
//                break;
//        }
//    }

    private boolean canAccessLocation() {
        return(hasPermission(Manifest.permission.ACCESS_FINE_LOCATION));
    }

    private boolean hasPermission(String perm) {
        return(PackageManager.PERMISSION_GRANTED==checkCallingOrSelfPermission(perm));
    }

    private void doLocationThing() {
//        Utility.displayToast(this, "location permission granted", Toast.LENGTH_SHORT);
    }

    public void onBackPressed() {

        super.onBackPressed();
    }

    @Override
    protected void onStart() {
        super.onStart();
        fab = (ImageView) findViewById(R.id.fab_store_track);
        status= (TextView) findViewById(R.id.traking_status_ats);
        if(Utility.isTrackingServiceRunning())
        {
            fab.setImageResource(R.drawable.in_tracking);
            AnimationDrawable anim = (AnimationDrawable) fab.getDrawable();
            anim.start();
            status.setText(R.string.btn_Stop);
            return;
        }
    }

    @Override
    protected void onStop() {
        super.onStop();

    }

    @Override
    public void onPause() {
        super.onPause();

        if(Utility.isTrackingServiceRunning())
        {
        }

    }

    @Override
    protected void onResume() {
        super.onResume();


        if(Utility.isTrackingServiceRunning())
        {
            fab.setImageResource(R.drawable.in_tracking);
            AnimationDrawable anim = (AnimationDrawable) fab.getDrawable();
            anim.start();
            status.setText(R.string.msg_InProgress);
            return;
        }

    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater menuInflater = getMenuInflater();
        menuInflater.inflate(R.menu.menu_threedot, menu);

        //  store the menu to var when creating options menu

        MenuItem item = menu.findItem(R.id.menu_TrackNotificationSound);
        item.setCheckable(true);
        if(session.getBooleanItem(SessionModel.KEY_SOUND_PLAY,true))
            item.setChecked(true);
        else
            item.setChecked(false);

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item)
    {

        switch (item.getItemId())
        {
            case R.id.menu_Minimize:
                // Single menu item is selected do something
                // Ex: launching new activity/screen or show alert message
                minimizeApp();
                return true;
            case R.id.menu_TrackNotificationSound:
                if(!item.isChecked())
                {
                    session.saveItem(SessionModel.KEY_SOUND_PLAY,true);
                    item.setChecked(true );
                }
                else
                {
                    session.saveItem(SessionModel.KEY_SOUND_PLAY,false);
                    item.setChecked(false);
                }
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void buildAlertMessageNoGps() {
        final AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage(getString(R.string.GPSseemsEnable))
                .setCancelable(false)
                .setPositiveButton(getString(R.string.msg_yes), new DialogInterface.OnClickListener() {
                    public void onClick(@SuppressWarnings("unused") final DialogInterface dialog, @SuppressWarnings("unused") final int id) {
                        startActivity(new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS));
                    }
                })
                .setNegativeButton(getString(R.string.msg_no), new DialogInterface.OnClickListener() {
                    public void onClick(final DialogInterface dialog, @SuppressWarnings("unused") final int id) {
                        dialog.cancel();
                        finish();
                    }
                });
        final AlertDialog alert = builder.create();
        alert.show();
    }

    private boolean isGPSEnabled(boolean showAlert)
    {
        final LocationManager manager = (LocationManager) getSystemService( this.LOCATION_SERVICE );

        boolean gps_enabled = false;

        try {
            //user must have enabled (checked) gps sattelite in location settings in their smart phone
            gps_enabled = manager.isProviderEnabled(LocationManager.GPS_PROVIDER);
        } catch(Exception ex) {}


        if ( !gps_enabled  ) {
            if(showAlert)
               buildAlertMessageNoGps();
            return false;
        }
        return true;
    }

    /** Defines callbacks for service binding, passed to bindService() */
    private ServiceConnection mConnection = new ServiceConnection() {

        @Override
        public void onServiceConnected(ComponentName className,
                                       IBinder service) {
        }

        @Override
        public void onServiceDisconnected(ComponentName arg0) {
            mBound = false;
        }
    };

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
//        Toast.makeText(AttendanceStoreSelfLocationActivity.this, "در جواب اجازه", Toast.LENGTH_SHORT)
//                .show();
        switch (requestCode)
        {
            case INITIAL_REQUEST:
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED)
                {
                    // Permission Granted
//                    insertDummyContact();
//                    Toast.makeText(AttendanceStoreSelfLocationActivity.this, "اجازه داده شد", Toast.LENGTH_SHORT)
//                            .show();
                    initialize();
                }
                else
                {
                    // Permission Denied
                    Toast.makeText(TrackStoreActivity.this, TrackStoreActivity.this.getString(R.string.msg_LocationAccessDenied), Toast.LENGTH_SHORT)
                            .show();
                    TrackStoreActivity.this.finish();
                }
                break;
            case LOCATION_REQUEST:
                if (canAccessLocation()) {
                    doLocationThing();
                }
                else {
                }
                break;
            default:
                super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        }
    }

}
