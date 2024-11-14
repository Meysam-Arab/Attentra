package ir.fardan7eghlim.attentra.views.attendance;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationManager;
import android.media.AudioManager;
import android.media.ToneGenerator;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.annotation.RequiresApi;
import android.support.v4.app.ActivityCompat;
import android.text.Html;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GoogleApiAvailability;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;

import java.util.ArrayList;
import java.util.Observable;
import java.util.Observer;
import java.util.Timer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.AttendanceController;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.company.CompanyIndexActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class AttendanceStoreSelfLocationActivityV2 extends BaseActivity implements Observer, LocationListener,
        GoogleApiClient.ConnectionCallbacks,
        GoogleApiClient.OnConnectionFailedListener {

    private ProgressDialog pDialog;

    private Context cntx;

    private TextView tv_status;
    private Button btn_store_self_location;

    private AttendanceController ac;


    final String TAG = "GPS";
    private long UPDATE_INTERVAL = 2 * 1000;  /* 10 secs */
    private long FASTEST_INTERVAL = 2000; /* 2 sec */
    static final int MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION = 1;

    final private int REQUEST_CODE_ASK_PERMISSIONS = 1337;
    private static final String[] INITIAL_PERMS = {
            Manifest.permission.ACCESS_FINE_LOCATION,
    };


    private static final int INITIAL_REQUEST = 1337;

    GoogleApiClient gac;
    LocationRequest locationRequest;

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


        ac = new AttendanceController(cntx);
        ac.addObserver(this);

        if (Build.VERSION.SDK_INT >= 23) {
            // Marshmallow+
            if (!canAccessLocation()) {
                requestPermissions(INITIAL_PERMS, INITIAL_REQUEST);
                return;
            }
        } else {
            // Pre-Marshmallow
            initialize();
        }


    }

    private void initialize() {


        btn_store_self_location = (Button) findViewById(R.id.btn_roll_by_location);
        btn_store_self_location.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                pDialog.show();
                tv_status.setText(getApplicationContext().getString(R.string.lbl_status_processing));


                if (ActivityCompat.checkSelfPermission(AttendanceStoreSelfLocationActivityV2.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(AttendanceStoreSelfLocationActivityV2.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                    // TODO: Consider calling
                    //    ActivityCompat#requestPermissions
                    // here to request the missing permissions, and then overriding
                    //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                    //                                          int[] grantResults)
                    // to handle the case where the user grants the permission. See the documentation
                    // for ActivityCompat#requestPermissions for more details.
                    return;
                }
                LocationServices.FusedLocationApi.requestLocationUpdates(gac, locationRequest, (com.google.android.gms.location.LocationListener) AttendanceStoreSelfLocationActivityV2.this);

            }
        });

        tv_status = (TextView) findViewById(R.id.tv_attendance_status_ats);
        tv_status.setText(getApplicationContext().getString(R.string.lbl_status_ready));

        int resultCode = GoogleApiAvailability.getInstance().isGooglePlayServicesAvailable(this);
        switch (resultCode) {
            case ConnectionResult.SUCCESS:
                Log.d(TAG, "Google Play Services is ready to go!");
                break;
            default:
                showPlayServicesError(resultCode);
                return;
        }

        LocationManager service = (LocationManager) getSystemService(LOCATION_SERVICE);
        boolean enabled = service.isProviderEnabled(LocationManager.GPS_PROVIDER);

        if (!enabled) {
            Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
            startActivity(intent);
        }

        isGooglePlayServicesAvailable();

        if(!isLocationEnabled())
            showAlert();

        locationRequest = new LocationRequest();
        locationRequest.setInterval(UPDATE_INTERVAL);
        locationRequest.setFastestInterval(FASTEST_INTERVAL);
        locationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        gac = new GoogleApiClient.Builder(this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();
    }


    @SuppressLint("WrongConstant")
    @Override
    public void update(Observable o, Object arg) {
        pDialog.hide();
        pDialog.dismiss();
        if (arg != null) {
            if (arg instanceof Boolean)
            {
                if (Boolean.parseBoolean(arg.toString()) == false) {
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_failed));
                } else {
                    tv_status.setText(getApplicationContext().getString(R.string.lbl_status_completed));
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    AttendanceStoreSelfLocationActivityV2.this.finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE))
                {
                    Boolean success = (Boolean) ((ArrayList) arg).get(1);
                    String status = (String) ((ArrayList) arg).get(2);
                    //show user in dialog
                    AlertDialog alertDialog = new AlertDialog.Builder(AttendanceStoreSelfLocationActivityV2.this).create();
                    if(success)
                    {
                        if(status.equals("0"))
                        {
//                                alertDialog.setTitle(getString(R.string.msg_CheckInOperationSuccess));
                            alertDialog.setTitle(Html.fromHtml("<font color='#658906'>"+getString(R.string.msg_CheckInOperationSuccess)+"</font>"));

                        }
                        else if(status.equals("1"))
                        {
//                                alertDialog.setTitle(getString(R.string.msg_CheckOutOperationSuccess));
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
                }
            }
            else if (arg instanceof Integer)
            {
                if (Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE) {
                    Utility.displayToast(getApplicationContext(), getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    AttendanceStoreSelfLocationActivityV2.this.finish();
                } else {
                    Utility.displayToast(getApplicationContext(), new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                }
            } else {
                Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);

            }
        } else {
            Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);

        }
    }



//    @RequiresApi(api = Build.VERSION_CODES.M)
    @Override
    protected void onResume() {

        super.onResume();
    }

    @Override
    public void onLocationChanged(Location location) {
        if (location != null) {
            updateUI(location);
        }
    }

    private void updateUI(Location loc) {
        AttendanceModel attendance = new AttendanceModel(cntx);
        attendance.setMission(false);
        attendance.setLatitude(loc.getLatitude());
        attendance.setLongitude(loc.getLongitude());
        ac.storeSelfLocation(attendance,false,session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();

    }

    @Override
    protected void onStart() {
        gac.connect();
        super.onStart();
    }

    @Override
    protected void onStop() {
        gac.disconnect();
        super.onStop();
    }

    @Override
    public void onConnected(@Nullable Bundle bundle) {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION)
                != PackageManager.PERMISSION_GRANTED
                && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(AttendanceStoreSelfLocationActivityV2.this,
                    new String[]{Manifest.permission.ACCESS_FINE_LOCATION},
                    MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION);

            return;
        }
        Log.d(TAG, "onConnected");

        Location ll = LocationServices.FusedLocationApi.getLastLocation(gac);
        Log.d(TAG, "LastLocation: " + (ll == null ? "NO LastLocation" : ll.toString()));

        LocationServices.FusedLocationApi.requestLocationUpdates(gac, locationRequest, (com.google.android.gms.location.LocationListener) this);
    }

    @Override
    public void onConnectionSuspended(int i) {}

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {
        Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "onConnectionFailed: \n" + connectionResult.toString(),
                Toast.LENGTH_LONG).show();
        Log.d("DDD", connectionResult.toString());
    }

//    @Override
//    public void onRequestPermissionsResult(
//            int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
//
//        switch (requestCode)
//        {
//            case MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION: {
//                // If request is cancelled, the result arrays are empty.
//                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
//                    Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "Permission was granted!", Toast.LENGTH_LONG).show();
//
//                    try{
//                        LocationServices.FusedLocationApi.requestLocationUpdates(gac, locationRequest, (com.google.android.gms.location.LocationListener) this);
//                    } catch (SecurityException e) {
//                        Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "SecurityException:\n" + e.toString(), Toast.LENGTH_LONG).show();
//                    }
//                } else {
//                    Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "Permission denied!", Toast.LENGTH_LONG).show();
//                }
//                return;
//            }
//        }
//    }

    private boolean isLocationEnabled() {
        LocationManager locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        return locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER) ||
                locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
    }

    private boolean isGooglePlayServicesAvailable() {
        final int PLAY_SERVICES_RESOLUTION_REQUEST = 9000;
        GoogleApiAvailability apiAvailability = GoogleApiAvailability.getInstance();
        int resultCode = apiAvailability.isGooglePlayServicesAvailable(this);
        if (resultCode != ConnectionResult.SUCCESS) {
            if (apiAvailability.isUserResolvableError(resultCode)) {
                apiAvailability.getErrorDialog(this, resultCode, PLAY_SERVICES_RESOLUTION_REQUEST)
                        .show();
            } else {
                Log.d(TAG, "This device is not supported.");
                finish();
            }
            return false;
        }
        Log.d(TAG, "This device is supported.");
        return true;
    }

    private void showAlert() {
        final AlertDialog.Builder dialog = new AlertDialog.Builder(this);
        dialog.setTitle("Enable Location")
                .setMessage("Your Locations Settings is set to 'Off'.\nPlease Enable Location to " +
                        "use this app")
                .setPositiveButton("Location Settings", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface paramDialogInterface, int paramInt) {

                        Intent myIntent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                        startActivity(myIntent);
                    }
                })
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface paramDialogInterface, int paramInt) {

                    }
                });
        dialog.show();
    }

    /*
* When Play Services is missing or at the wrong version, the client
* library will assist with a dialog to help the user update.
*/
    private void showPlayServicesError(int errorCode) {
        GoogleApiAvailability.getInstance().showErrorDialogFragment(this, errorCode, 10,
                new DialogInterface.OnCancelListener() {
                    @Override
                    public void onCancel(DialogInterface dialogInterface) {
                        finish();
                    }
                });
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
//        Toast.makeText(AttendanceStoreSelfLocationActivity.this, "در جواب اجازه", Toast.LENGTH_SHORT)
//                .show();
        switch (requestCode)
        {
            case REQUEST_CODE_ASK_PERMISSIONS:
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
                    Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, AttendanceStoreSelfLocationActivityV2.this.getString(R.string.msg_LocationAccessDenied), Toast.LENGTH_SHORT)
                            .show();
                    AttendanceStoreSelfLocationActivityV2.this.finish();
                }
                break;
            case MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION: {
                // If request is cancelled, the result arrays are empty.
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "Permission was granted!", Toast.LENGTH_LONG).show();

                    try{
                        LocationServices.FusedLocationApi.requestLocationUpdates(gac, locationRequest, (com.google.android.gms.location.LocationListener) this);
                    } catch (SecurityException e) {
                        Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "SecurityException:\n" + e.toString(), Toast.LENGTH_LONG).show();
                    }
                } else {
                    Toast.makeText(AttendanceStoreSelfLocationActivityV2.this, "Permission denied!", Toast.LENGTH_LONG).show();
                }
                return;
            }
            default:
                super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        }
    }

    private boolean canAccessLocation() {
        return (hasPermission(Manifest.permission.ACCESS_FINE_LOCATION));
    }
    private boolean hasPermission(String perm) {
        return (PackageManager.PERMISSION_GRANTED == checkCallingOrSelfPermission(perm));
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
