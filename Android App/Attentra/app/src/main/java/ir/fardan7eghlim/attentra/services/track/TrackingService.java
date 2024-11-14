package ir.fardan7eghlim.attentra.services.track;

/**
 * Created by Meysam on 5/2/2017.
 */

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.BatteryManager;
import android.os.Binder;
import android.os.Build;
import android.os.Bundle;
import android.os.IBinder;
import android.provider.Settings;
import android.support.annotation.RequiresApi;
import android.support.v4.app.ActivityCompat;
import android.telephony.PhoneStateListener;
import android.telephony.SignalStrength;
import android.telephony.TelephonyManager;
import android.widget.Toast;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.TrackController;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.TrackModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.track.TrackStoreActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class TrackingService extends Service implements Observer {


    private NotificationManager mNM;

    public static Boolean serviceRuning;
    private static SessionModel session;

    // Unique Identification Number for the Notification.
    // We use it on Notification start, and to cancel it.
    private int NOTIFICATION = R.string.msg_TrackNotification;


    private static long MIN_TIME_INTERVAL;
    private static float MIN_DISTANCE_INTERVAL;


    private static LocationListener locationListener;
    private static double lastLongitude = 0;
    private static double lastLatitude = 0;

    private static boolean canGpsLocation = false;
    private static TrackController tc;
    private static LocationManager locationManager;

    TelephonyManager mTelephonyManager;
    MyPhoneStateListener mPhoneStatelistener;
    public static int mSignalStrength = -1;
    public static int m‌BatteryPower = -1;
    public static int mBatteryStatus = -1;
    public static int mBatteryPluggedType = -1;
    public static int mBatteryHealth = -1;


    /**
     * Class for clients to access.  Because we know this service always
     * runs in the same process as its clients, we don't need to deal with
     * IPC.
     */
    public class LocalBinder extends Binder {
        public TrackingService getService() {
            return TrackingService.this;
        }
    }


//    @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onCreate() {

        this.registerReceiver(this.mBatteryInfoReceiver,
                new IntentFilter(Intent.ACTION_BATTERY_CHANGED));

        serviceRuning = false;
        mNM = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);

        mSignalStrength = -1;
        mBatteryHealth = -1;
        mBatteryPluggedType = -1;
        m‌BatteryPower = -1;
        mBatteryStatus = -1;

        mPhoneStatelistener = new MyPhoneStateListener();
        mTelephonyManager = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
        mTelephonyManager.listen(mPhoneStatelistener, PhoneStateListener.LISTEN_SIGNAL_STRENGTHS);

        // Display a notification about us starting.  We put an icon in the status bar.
        showNotification();


        tc = new TrackController(getApplicationContext());

        if (locationListener == null)
            locationListener = new TrackingService.MyLocationListener();

        if (locationManager == null)
            locationManager = (LocationManager)
                    getSystemService(Context.LOCATION_SERVICE);

        // read from session
        SessionModel session = new SessionModel(getApplicationContext());
        if (session.getStringItem("min_distance_interval") != null && session.getStringItem("min_time_interval") != null) {

            MIN_DISTANCE_INTERVAL = Integer.parseInt(session.getStringItem("min_distance_interval"));
            MIN_TIME_INTERVAL = Integer.parseInt(session.getStringItem("min_time_interval"));
//            Utility.displayToast(getApplicationContext(), "read parameters of location listener from server: mindist: " + MIN_DISTANCE_INTERVAL + " min time:" + MIN_TIME_INTERVAL, Toast.LENGTH_LONG);

        } else

        {
            MIN_DISTANCE_INTERVAL = 200;
            MIN_TIME_INTERVAL = 600000;
        }

        tc = new TrackController(this);
        tc.addObserver((Observer) this);

    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {

        if(session == null)
            session = new SessionModel(getApplicationContext());

//        Utility.displayToast(getApplicationContext(), "service started", Toast.LENGTH_LONG);

        if (!serviceRuning)
            startSendingTrackData();

        return START_STICKY;
    }


    @Override
    public void onDestroy() {
        // Cancel the persistent notification.
        mNM.cancel(NOTIFICATION);

//        stopThisService();
        setNullAllRelatedObjects();
        hideNotification();
        serviceRuning = false;

        session.removeItem(SessionModel.KEY_LAST_LON);
        session.removeItem(SessionModel.KEY_LAST_LAT);
        lastLatitude = 0;
        lastLongitude = 0;
        // Tell the user we stopped.
//        Utility.displayToast(this, "track service stopped", Toast.LENGTH_SHORT);
    }

    @Override
    public IBinder onBind(Intent intent) {
        return mBinder;
    }

    // This is the object that receives interactions from clients.  See
    // RemoteService for a more complete example.
    private final IBinder mBinder = new LocalBinder();

    /**
     * Show a notification while this service is running.
     */
//    @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN)
    private void showNotification() {
        // In this sample, we'll use the same text for the ticker and the expanded notification
        CharSequence text = getText(R.string.msg_TrackNotification);

        // The PendingIntent to launch our activity if the user selects this notification
        PendingIntent contentIntent = PendingIntent.getActivity(this, 0,
                new Intent(this, TrackStoreActivity.class), 0);

        // Set the info for the views that show in the notification panel.
        Notification notification = new Notification.Builder(this)
                .setSmallIcon(R.drawable.ic_launcher)  // the status icon
                .setTicker(text)  // the status text
                .setWhen(System.currentTimeMillis())  // the time stamp
                .setContentTitle(getText(R.string.msg_InProgress))  // the label of the entry
                .setContentText(text)  // the contents of the entry
                .setContentIntent(contentIntent)  // The intent to send when the entry is clicked
                .build();

        // Send the notification.
        mNM.notify(NOTIFICATION, notification);
    }


    @SuppressLint("WrongConstant")
    @Override
    public void update(Observable o, Object arg) {
//        Utility.displayToast(getApplicationContext(), "بازگشت از سرور", Toast.LENGTH_LONG);

        if (arg != null) {
            if (arg instanceof Boolean) {
                if (Boolean.parseBoolean(arg.toString()) == false) {
//                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);

                    ///////////////////////////////////////////////////////////////////////////////////////////
//                    ///sending data again
//                    Location location = null;
//                    double longitude = 0;
//                    double latitude = 0;
//                    if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
//                        //  Consider calling
//                        //    ActivityCompat#requestPermissions
//                        // here to request the missing permissions, and then overriding
//                        //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
//                        //                                          int[] grantResults)
//                        // to handle the case where the user grants the permission. See the documentation
//                        // for ActivityCompat#requestPermissions for more details.
//                        return;
//                    }
//                    location = locationManager
//                            .getLastKnownLocation(LocationManager.GPS_PROVIDER);
//                    if (location != null) {
//                        longitude =  location.getLongitude();
//                        latitude =  location.getLatitude();
//                    }
//                    TrackModel track = new TrackModel();
//                    track.setLatitude(latitude);
//                    track.setLongitude(longitude);
//                    SessionModel session = new SessionModel(getApplicationContext());
//                    track.setTrackGroup(session.getStoredTrackGroup());
//                    UserModel user = session.getCurrentUser();
//                    tc.store(track,user);
                    ///////////////////////////////////////////////////////////////////////////////////////////


                } else {
//                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    if(new SessionModel(getApplicationContext()).getBooleanItem(SessionModel.KEY_SOUND_PLAY,true))
                    {
                        Utility.playSound(getApplicationContext());
                    }

                }
            } else if (arg instanceof Integer) {

                Utility.displayToast(getApplicationContext(), new RequestRespondModel(this).getErrorCodeMessage(Integer.parseInt(arg.toString())), Toast.LENGTH_LONG);

                if (Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE) {
//                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);
                    Intent intents = new Intent(TrackingService.this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    // Clear all notification
                    NotificationManager nMgr = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
                    nMgr.cancelAll();
                    this.stopSelf();
                }
            } else {
                Utility.displayToast(getApplicationContext(), getString(R.string.msg_MessageNotSpecified), Toast.LENGTH_LONG);
            }
        }

    }

    /*---------- Listener class to get coordinates ------------- */
    private class MyLocationListener implements LocationListener {

        @Override
        public void onProviderDisabled(String provider) {
//            Utility.displayToast(getApplicationContext(), "Location provider disabled: " + provider, Toast.LENGTH_LONG);
            stopThisService();

        }

        @Override
        public void onProviderEnabled(String provider) {

//            Utility.displayToast(getApplicationContext(), "Location provider enabled: " + provider, Toast.LENGTH_LONG);
        }

        @Override
        public void onLocationChanged(Location location) {

//            Utility.displayToast(getApplicationContext(), "Location Changed ", Toast.LENGTH_LONG);


            ////////////////////////////////////////////////////////////
            //  meysam - remove comments
            boolean isMock = false;
            if (android.os.Build.VERSION.SDK_INT >= 18) {
                isMock = location.isFromMockProvider();
            } else {
                if (!Settings.Secure.getString(TrackingService.this.getContentResolver(), Settings.Secure.ALLOW_MOCK_LOCATION).equals("0"))
                    isMock = true;
                else isMock = false;
            }
            if(isMock)
            {
                stopThisService();
            }
            /////////////////////////////////////////////////////////

            // getting GPS status
            boolean isGPSEnabled = locationManager
                    .isProviderEnabled(LocationManager.GPS_PROVIDER);

            if (!isGPSEnabled ) {
                // no network provider is enabled and we cant send data or receive location
                stopThisService();
            }

//            Utility.displayToast(getApplicationContext(),"Location changed: Lat: " + location.getLatitude() + " Lng: "
//                    + location.getLongitude(),Toast.LENGTH_LONG);

            double longitude =  location.getLongitude();
            double latitude =  location.getLatitude();


            if((lastLatitude != latitude || lastLongitude != longitude))
            {
//                SessionModel session = new SessionModel(getApplicationContext());
                if(session.getDoubleItem(SessionModel.KEY_LAST_LAT) != latitude &&
                        session.getDoubleItem(SessionModel.KEY_LAST_LON) != longitude)
                {
                    lastLatitude = latitude;
                    lastLongitude = longitude;

                    session.saveItem(SessionModel.KEY_LAST_LAT,latitude);
                    session.saveItem(SessionModel.KEY_LAST_LON,longitude);


                    TrackModel track = new TrackModel();
                    track.setLatitude(latitude);
                    track.setLongitude(longitude);

//                    session = new SessionModel(getApplicationContext());
                    UserModel user = session.getCurrentUser();


//                if(trackGroup == null)
//                {
//                    DateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
//                    Date datetime = new Date();
//                    String date = dateFormat.format(datetime).toString().replaceAll(":", "");
//                    date = date.replaceAll("/", "");
//                    date = date.replaceAll(" ", "");
//
//                    trackGroup = user.getId().toString()+ Utility.arabicToDecimal(date);
//                }
//                else
//                {
                    track.setTrackGroup(session.getStoredTrackGroup());
//                }

                    track.setChargeType(mBatteryPluggedType);
                    track.setBatteryStatus(mBatteryStatus);
                    track.setBatteryPower(m‌BatteryPower);
                    track.setSignalPower(mSignalStrength);
                    track.setChargeStatus(mBatteryHealth);

                    tc.store(track,user);
                }

            }
        }

        @Override
        public void onStatusChanged(String provider, int status, Bundle extras) {}

    }

    public void stopThisService()
    {

        locationManager.removeUpdates(locationListener);
        locationManager = null;
        locationListener = null;
        serviceRuning = false;

        this.stopSelf();

    }

    public void startSendingTrackData()
    {
        ////////////////////////////////////new code meysam 13960208////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
//        Location location = null;
//        double longitude = 0;
//        double latitude = 0;

        if(locationManager == null)
            locationManager = (LocationManager) this
                    .getSystemService(LOCATION_SERVICE);

        // getting GPS status
        boolean isGPSEnabled = locationManager
                .isProviderEnabled(LocationManager.GPS_PROVIDER);

        if (!isGPSEnabled ) {
            stopThisService();
        }
        // if GPS Enabled get lat/long using GPS Services
        else if (isGPSEnabled) {
            canGpsLocation = true;
//            Utility.displayToast(getApplicationContext(),"canGpsLocation",Toast.LENGTH_LONG);
//            SessionModel session = new SessionModel(getApplicationContext());
            if(session.getStoredTrackGroup() == null)
            {
                DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
                Date datetime = new Date();

                String date = dateFormat.format(datetime).replaceAll(":", "");
                date = date.replaceAll("/", "");
                date = date.replaceAll(" ", "");

                UserModel user = session.getCurrentUser();
                session.setStoredTrackGroup(user.getId().toString()+ Utility.arabicToDecimal(date));
            }
            if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                //  Consider calling
                //    ActivityCompat#requestPermissions
                // here to request the missing permissions, and then overriding
                //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                //                                          int[] grantResults)
                // to handle the case where the user grants the permission. See the documentation
                // for ActivityCompat#requestPermissions for more details.
            }
//            Criteria criteria = new Criteria();
//            criteria.setAccuracy(Criteria.ACCURACY_COARSE);
            locationManager.requestLocationUpdates(
                    LocationManager.GPS_PROVIDER,
                    MIN_TIME_INTERVAL,
                    MIN_DISTANCE_INTERVAL, locationListener);
//            location = locationManager
//                    .getLastKnownLocation(LocationManager.GPS_PROVIDER);
//            if (location != null) {
//                latitude = location.getLatitude();
//                longitude = location.getLongitude();
//            }

        }
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

//        if(latitude != 0 && longitude != 0)
//        {
////            initialRun =false;
////            lastLatitude = latitude;
////            lastLongitude = longitude;
//
//            TrackModel track = new TrackModel();
//            track.setLatitude(latitude);
//            track.setLongitude(longitude);
//
//  if(trackGroup == null)
//            {
//                DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
//                Date datetime = new Date();
//
//                String date = dateFormat.format(datetime).replaceAll(":", "");
//                date = date.replaceAll("/", "");
//                date = date.replaceAll(" ", "");
//
//                SessionModel session = new SessionModel(getApplicationContext());
//                UserModel user = session.getCurrentUser();
//                trackGroup = user.getId().toString()+ Utility.arabicToDecimal(date);
//            }
//
//            track.setTrackGroup(trackGroup);
//
//
//            tc = new TrackController(this);
//            tc.addObserver((Observer) this);
//
////            tc.store(track,user);
//
//
//
//        }

//        if(canGpsLocation )
//        {
//
//            locationManager.removeUpdates(locationListener);
//            locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, MIN_TIME_INTERVAL, MIN_DISTANCE_INTERVAL, locationListener);
//
//        }

        serviceRuning = true;
    }

    public void hideNotification()
    {
        mNM.cancel(R.string.msg_OperationSuccess);
        mNM.cancelAll();
        mNM = null;
    }

    private void setNullAllRelatedObjects()
    {
        if(locationManager != null)
        {
            locationManager.removeUpdates(locationListener);
            locationManager = null;
        }
        if(locationListener != null)
            locationListener = null;
        serviceRuning = false;
    }

    private BroadcastReceiver mBatteryInfoReceiver = new BroadcastReceiver(){
        @Override
        public void onReceive(Context arg0, Intent intent) {


            int level = intent.getIntExtra(BatteryManager.EXTRA_LEVEL, 0);
            int scale = intent.getIntExtra(BatteryManager.EXTRA_SCALE, 100);

            boolean isPresent = intent.getBooleanExtra("present", false);

//            Bundle bundle = intent.getExtras();
//            String str = bundle.toString();

            if (isPresent) {
                m‌BatteryPower = (level * 100) / scale;

//                String temp = bundle.getString("technology");
//                String temp1 = bundle.getInt("voltage")+"mV";
//                String temp2 = bundle.getInt("temperature")+"";
//                String temp3 = bundle.getInt("current_avg")+"";
//                String temp4 = TrackModel.getHealthString("health");
//                String temp5 = TrackModel.getStatusString(status) + "(" +TrackModel.getPlugTypeString(pluggedType)+")";
//                String temp6 = percent + "%";

                mBatteryStatus = intent.getIntExtra(BatteryManager.EXTRA_STATUS, 0);
                mBatteryHealth = intent.getIntExtra(BatteryManager.EXTRA_HEALTH, 0);
//                boolean present = intent.getBooleanExtra(
//                        BatteryManager.EXTRA_PRESENT, false);
//                int level = intent.getIntExtra(BatteryManager.EXTRA_LEVEL, 0);
//                int scale = intent.getIntExtra(BatteryManager.EXTRA_SCALE, 0);
//                int icon_small = intent.getIntExtra(
//                        BatteryManager.EXTRA_ICON_SMALL, 0);
                mBatteryPluggedType = intent.getIntExtra(BatteryManager.EXTRA_PLUGGED,
                        0);
//                int voltage = intent.getIntExtra(BatteryManager.EXTRA_VOLTAGE,
//                        0);
//                int temperature = intent.getIntExtra(
//                        BatteryManager.EXTRA_TEMPERATURE, 0);
//                String technology = intent
//                        .getStringExtra(BatteryManager.EXTRA_TECHNOLOGY);
//
//                String temp4 = TrackModel.getHealthString(health);
//                String temp5 = TrackModel.getStatusString(status) + "(" +TrackModel.getPlugTypeString(plugged)+")";


            } else {
//                battery_percentage.setText("Battery not present!!!");
            }
        }
    };

    class MyPhoneStateListener extends PhoneStateListener {

        @Override
        public void onSignalStrengthsChanged(SignalStrength signalStrength) {
            super.onSignalStrengthsChanged(signalStrength);

//            int x = signalStrength.getLevel();
//            int x = TrackModel.getGsmLevel(signalStrength);// 0 - 4
            mSignalStrength = TrackModel.getGsmLevel(signalStrength);
//            int signalStrengthValue;
//            if (signalStrength.isGsm()) {
//                if (signalStrength.getGsmSignalStrength() != 99)//Get the GSM Signal Strength, valid values are (0-31, 99) as defined in TS 27.007 8.5
//                    signalStrengthValue = signalStrength.getGsmSignalStrength() * 2 - 113;
//                else
//                    signalStrengthValue = signalStrength.getGsmSignalStrength();
//            } else {
//                signalStrengthValue = signalStrength.getCdmaDbm();
//            }
//            String temp = "Signal Strength : " + signalStrengthValue;


//            mSignalStrength = signalStrength.getGsmSignalStrength();
//            int signalSupport = signalStrength.getGsmSignalStrength();
//            mSignalStrength = (2 * mSignalStrength) - 113; // -> dBm
//
//            if (signalSupport > 30) {
////                Log.d(getClass().getCanonicalName(), "Signal GSM : Good");
//
//
//            } else if (signalSupport > 20 && signalSupport < 30) {
////                Log.d(getClass().getCanonicalName(), "Signal GSM : Avarage");
//
//
//            } else if (signalSupport < 20 && signalSupport > 3) {
////                Log.d(getClass().getCanonicalName(), "Signal GSM : Week");
//
//
//            } else if (signalSupport < 3) {
////                Log.d(getClass().getCanonicalName(), "Signal GSM : Very week");
//
//
//            }
        }
    }
}