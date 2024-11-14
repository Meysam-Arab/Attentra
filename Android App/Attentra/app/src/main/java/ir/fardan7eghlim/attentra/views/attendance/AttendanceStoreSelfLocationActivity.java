package ir.fardan7eghlim.attentra.views.attendance;

import android.Manifest;
import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.media.AudioManager;
import android.media.ToneGenerator;
import android.os.Build;
import android.os.Bundle;
import android.os.SystemClock;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.annotation.RequiresApi;
import android.support.v4.app.ActivityCompat;
import android.text.Html;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.location.LocationResult;

import java.util.ArrayList;
import java.util.Observable;
import java.util.Observer;
import java.util.Timer;
import java.util.TimerTask;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.AttendanceController;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;


public class AttendanceStoreSelfLocationActivity extends BaseActivity implements Observer {
    boolean mBound = false;

//    final private int REQUEST_CODE_ASK_PERMISSIONS = 1337;

    ImageView fab;
    TextView status;

    Timer tmr_timer;
    LocationManager manager;

    private ProgressDialog pDialog;

    private LocationResult locationResult;
    private boolean gpsEnabled = false;
    private boolean networkEnabled = false;


    private static final String[] INITIAL_PERMS = {
            Manifest.permission.ACCESS_FINE_LOCATION,
    };


    private static final int INITIAL_REQUEST = 1337;
    private Context cntx;

    private TextView tv_status;
    private Button btn_store_self_location;

    private AttendanceController ac;


//    @RequiresApi(api = Build.VERSION_CODES.M)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_attendance_store_self_location);
        super.onCreateDrawer();

        cntx = this;

        pDialog = new ProgressDialog(cntx);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));

//        int hasAccessFineLocationPermission = checkSelfPermission(Manifest.permission.ACCESS_FINE_LOCATION);
//        if (hasAccessFineLocationPermission != PackageManager.PERMISSION_GRANTED) {
//            requestPermissions(new String[] {Manifest.permission.ACCESS_FINE_LOCATION},
//                    REQUEST_CODE_ASK_PERMISSIONS);
//            return;
//        }
//        insertDummyContact();
//        Toast.makeText(AttendanceStoreSelfLocationActivity.this, "برک اول", Toast.LENGTH_SHORT)
//                .show();
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
    }

    private void initialize()
    {
//        Toast.makeText(AttendanceStoreSelfLocationActivity.this, "برک دوم", Toast.LENGTH_SHORT)
//                .show();
        manager = (LocationManager) getSystemService(this.LOCATION_SERVICE);
        ////////////////////gps check////////////////////////////
        isGPSEnabled(true);

        ac = new AttendanceController(cntx);
        ac.addObserver(this);


        btn_store_self_location = (Button) findViewById(R.id.btn_roll_by_location);
        btn_store_self_location.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                // meysam - read current location and send to server
//                if (!canAccessLocation()) {
//                    requestPermissions(INITIAL_PERMS, INITIAL_REQUEST);
//                }
                if (isGPSEnabled(true)) {

                    pDialog.show();
                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_processing));
                    if (!getLocation(cntx)) {
                        pDialog.hide();
                        AttendanceStoreSelfLocationActivity.this.finish();

                    }
                }


            }
        });

        tv_status = (TextView) findViewById(R.id.tv_attendance_status_ats);
        tv_status.setText(getApplicationContext().getString(R.string.lbl_status_ready));
    }

    @SuppressLint("WrongConstant")
    @Override
    public void update(Observable o, Object arg) {
        pDialog.dismiss();
        if (arg != null) {
            if (arg instanceof Boolean) {
                if (Boolean.parseBoolean(arg.toString()) == false) {
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_failed));
                } else {
                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_completed));
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    AttendanceStoreSelfLocationActivity.this.finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_STORE_SELF_LOCATION_ATTENDANCE))
                {
                    Boolean success = (Boolean) ((ArrayList) arg).get(1);
                    String status = (String) ((ArrayList) arg).get(2);
                    //show user in dialog
                    AlertDialog alertDialog = new AlertDialog.Builder(AttendanceStoreSelfLocationActivity.this).create();
                    if(success)
                    {
                        if(status.equals("0"))
                        {
//                                alertDialog.setTitle(getString(R.string.msg_CheckInOperationSuccess));
                            alertDialog.setTitle(Html.fromHtml("<font color='#658906'>"+getString(R.string.msg_CheckInOperationSuccess)+"</font>"));

                        }
                        else if(status.equals("1"))
                        {
//                            alertDialog.setTitle(getString(R.string.msg_CheckOutOperationSuccess));
                                alertDialog.setTitle(Html.fromHtml("<font color='#e31313'>"+getString(R.string.msg_CheckOutOperationSuccess)+"</font>"));

                        }
                        else
                        {
                            alertDialog.setTitle(Html.fromHtml("<font color='#3b89ff'>"+getString(R.string.msg_CheckInOutOperationSuccess)+"</font>"));
                        }
                    }
                    else
                        alertDialog.setTitle(getString(R.string.msg_OperationFail));

                    DatabaseHandler db = new DatabaseHandler(getApplicationContext());
                    alertDialog.setMessage(db.getUserDetails().getName()+" "+db.getUserDetails().getFamily());
                    alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, getString(R.string.btn_OK),
                            new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.dismiss();
                                }
                            });
                    alertDialog.show();
                    ToneGenerator toneG = new ToneGenerator(AudioManager.STREAM_ALARM, 100);
                    toneG.startTone(ToneGenerator.TONE_CDMA_ALERT_CALL_GUARD, 200);

                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_ready));

                }
            }
            else if (arg instanceof Integer) {
                if (Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE) {
                    Utility.displayToast(getApplicationContext(), getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    AttendanceStoreSelfLocationActivity.this.finish();
                } else {

                    Utility.displayToast(getApplicationContext(), new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_ready));
                }
            } else {
                Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                tv_status.setText(getApplicationContext().getString(R.string.lbl_status_ready));
            }
        } else {
            Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            tv_status.setText(getApplicationContext().getString(R.string.lbl_status_ready));
        }
    }

    private boolean canAccessLocation() {
        return (hasPermission(Manifest.permission.ACCESS_FINE_LOCATION));
    }

    private boolean hasPermission(String perm) {
        return (PackageManager.PERMISSION_GRANTED == checkCallingOrSelfPermission(perm));
    }

    private boolean isGPSEnabled(boolean showAlert) {


        boolean gps_enabled = false;

        try {
            //user must have enabled (checked) gps sattelite in location settings in their smart phone
            gps_enabled = manager.isProviderEnabled(LocationManager.GPS_PROVIDER);
        } catch (Exception ex) {
        }


        if (!gps_enabled) {
            if (showAlert)
                buildAlertMessageNoGps();
            return false;
        }
        return true;
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

    public boolean getLocation(Context context) {

        if (manager == null)
            manager = (LocationManager) context.getSystemService(Context.LOCATION_SERVICE);

        // exceptions will be thrown if provider is not permitted.
        try {
            gpsEnabled = manager.isProviderEnabled(LocationManager.GPS_PROVIDER);
        } catch (Exception ex) {
        }
        try {
            networkEnabled = manager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
        } catch (Exception ex) {
        }

        // don't start listeners if no provider is enabled
        if (!gpsEnabled && !networkEnabled)
//        if (!gpsEnabled)
            return false;

//        if (gpsEnabled)
//            manager.requestSingleUpdate(LocationManager.GPS_PROVIDER, locationListenerGps, Looper.myLooper());
//        //locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, locationListenerGps);
//        if (networkEnabled)
//            manager.requestSingleUpdate(LocationManager.NETWORK_PROVIDER, locationListenerNetwork, Looper.myLooper());
//        //locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, locationListenerNetwork);

        if (gpsEnabled) {
            if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                // TODO: Consider calling
                //    ActivityCompat#requestPermissions
                // here to request the missing permissions, and then overriding
                //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                //                                          int[] grantResults)
                // to handle the case where the user grants the permission. See the documentation
                // for ActivityCompat#requestPermissions for more details.
//                return ;
            }
            Location gps_location = manager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
            if (gps_location != null && age_minutes(gps_location) < 1) {
                // remove comments - meysam
                boolean isMock = false;
                //  meysam - uncomment...
                if (android.os.Build.VERSION.SDK_INT >= 18) {
                    isMock = gps_location.isFromMockProvider();
                } else {
                    if (!Settings.Secure.getString(cntx.getContentResolver(), Settings.Secure.ALLOW_MOCK_LOCATION).equals("0"))
                        isMock = true;
                    else isMock = false;
                }
                if(isMock)
                {
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    AttendanceStoreSelfLocationActivity.this.finish();
                }
                else
                {
                    // fix is under 1 mins old, we'll use it
                    AttendanceModel attendance = new AttendanceModel(cntx);
                    attendance.setMission(false);
                    attendance.setLatitude(gps_location.getLatitude());
                    attendance.setLongitude(gps_location.getLongitude());

                    ac.storeSelfLocation(attendance,false, session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));
                }


            }
            else
            {
                manager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0,  0, locationListenerGps);
            }
        }
        if(networkEnabled)
        {
            Location network_location = manager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);


            if(network_location != null &&  age_minutes(network_location) < 1)
            {
                // fix is under 1 mins old, we'll use it
                AttendanceModel attendance = new AttendanceModel(cntx);
                attendance.setMission(false);
                attendance.setLatitude(network_location.getLatitude());
                attendance.setLongitude(network_location.getLongitude());
                ac.storeSelfLocation(attendance,false,session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));
            }
            else
            {
                // older than 1 mins, we'll ignore it and wait for new one
                if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                    // TODO: Consider calling
                    //    ActivityCompat#requestPermissions
                    // here to request the missing permissions, and then overriding
                    //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                    //                                          int[] grantResults)
                    // to handle the case where the user grants the permission. See the documentation
                    // for ActivityCompat#requestPermissions for more details.
//            return TODO;
                }
//                manager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, locationListenerGps);
                manager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, locationListenerNetwork);
//            tmr_timer = new Timer();
//            tmr_timer.schedule(new GetLastLocation(), 20000);
            }

        }


        return true;
    }

    LocationListener locationListenerGps = new LocationListener() {
        public void onLocationChanged(Location location) {
//            tmr_timer.cancel();
            manager.removeUpdates(this);
            manager.removeUpdates(locationListenerNetwork);
            //use location as it is the latest value

            // remove comments - meysam
            boolean isMock = false;
            if (android.os.Build.VERSION.SDK_INT >= 18) {
                isMock = location.isFromMockProvider();
            } else {
                if (!Settings.Secure.getString(cntx.getContentResolver(), Settings.Secure.ALLOW_MOCK_LOCATION).equals("0"))
                    isMock = true;
                else isMock = false;
            }
            if(isMock)
            {
                Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                manager.removeUpdates(this);
                AttendanceStoreSelfLocationActivity.this.finish();
            }
            else
            {
                AttendanceModel attendance = new AttendanceModel(cntx);
                attendance.setMission(false);
                attendance.setLatitude(location.getLatitude());
                attendance.setLongitude(location.getLongitude());
                ac.storeSelfLocation(attendance,false,session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));
            }
            /////////////////////////////////////////////////////////


        }

        public void onProviderDisabled(String provider) {
            Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            manager.removeUpdates(this);
            AttendanceStoreSelfLocationActivity.this.finish();

        }

        public void onProviderEnabled(String provider) {

        }

        public void onStatusChanged(String provider, int status, Bundle extras) {

        }
    };
    private final LocationListener locationListenerNetwork = new LocationListener() {
        public void onLocationChanged(Location location) {
//            tmr_timer.cancel();
            manager.removeUpdates(this);
            manager.removeUpdates(locationListenerGps);

            AttendanceModel attendance = new AttendanceModel(cntx);
            attendance.setMission(false);
            attendance.setLatitude(location.getLatitude());
            attendance.setLongitude(location.getLongitude());
            ac.storeSelfLocation(attendance,false,session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));
        }

        public void onProviderDisabled(String provider) {
            Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            manager.removeUpdates(this);
            AttendanceStoreSelfLocationActivity.this.finish();
        }

        public void onProviderEnabled(String provider) {
        }

        public void onStatusChanged(String provider, int status, Bundle extras) {
        }
    };
//    class GetLastLocation extends TimerTask {
//        @Override
//        public void run() {
//            manager.removeUpdates(locationListenerGps);
//            manager.removeUpdates(locationListenerNetwork);
//            if (ActivityCompat.checkSelfPermission(cntx, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(cntx, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
//                // TODO: Consider calling
//                //    ActivityCompat#requestPermissions
//                // here to request the missing permissions, and then overriding
//                //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
//                //                                          int[] grantResults)
//                // to handle the case where the user grants the permission. See the documentation
//                // for ActivityCompat#requestPermissions for more details.
//                return;
//            }
//            Location location = manager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
//            //use location as we have not received the new value from listener
//            // remove comments - meysam
//            boolean isMock = false;
//            if (android.os.Build.VERSION.SDK_INT >= 18) {
//                isMock = location.isFromMockProvider();
//            } else {
//                if (!Settings.Secure.getString(cntx.getContentResolver(), Settings.Secure.ALLOW_MOCK_LOCATION).equals("0"))
//                    isMock = true;
//                else isMock = false;
//            }
//            if(isMock)
//            {
//                Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
//                AttendanceStoreSelfLocationActivity.this.finish();
//            }
//            else
//            {
//                AttendanceModel attendance = new AttendanceModel(cntx);
//                attendance.setMission(false);
//                attendance.setLatitude(location.getLatitude());
//                attendance.setLongitude(location.getLongitude());
//                ac.storeSelfLocation(attendance,false);
//            }
//
//        }
//    }

    public int age_minutes(Location last) {
        return (int) (age_ms(last) / (60*1000));
    }

    public long age_ms(Location last) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1)
            return age_ms_api_17(last);
        return age_ms_api_pre_17(last);
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN_MR1)
    private long age_ms_api_17(Location last) {
        return (SystemClock.elapsedRealtimeNanos() - last
                .getElapsedRealtimeNanos()) / 1000000;
    }

    private long age_ms_api_pre_17(Location last) {
        return System.currentTimeMillis() - last.getTime();
    }

//    @RequiresApi(api = Build.VERSION_CODES.M)
    @Override
    protected void onResume() {

        super.onResume();
    }

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
                    Toast.makeText(AttendanceStoreSelfLocationActivity.this, AttendanceStoreSelfLocationActivity.this.getString(R.string.msg_LocationAccessDenied), Toast.LENGTH_SHORT)
                            .show();
                    AttendanceStoreSelfLocationActivity.this.finish();
                }
                break;
            default:
                super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater menuInflater = getMenuInflater();
        menuInflater.inflate(R.menu.menu_checkinoutthreedot, menu);

        //  store the menu to var when creating options menu

        MenuItem item = menu.findItem(R.id.menu_CheckInOutMerge);
        item.setCheckable(true);
        if(session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false))
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

            case R.id.menu_CheckInOutMerge:
                if(!item.isChecked())
                {
                    session.saveItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,true);
                    item.setChecked(true );
                }
                else
                {
                    session.saveItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false);
                    item.setChecked(false);
                }
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }
}

