package ir.fardan7eghlim.attentra.views.track;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Rect;
import android.graphics.Typeface;
import android.os.StrictMode;
import android.os.Bundle;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.PolylineOptions;
import java.io.IOException;
import java.math.BigInteger;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.TrackController;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.TrackModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class TrackListActivity extends BaseActivity implements Observer,OnMapReadyCallback {

    private GoogleMap mMap;
//    private BigInteger lastPointId;
    private List<TrackModel> tracks;
    private String trackGroupCode;
    private TrackModel lastTrack;
    private TrackController tc;
    private TrackModel track;
//    private MarkerOptions lastMarker;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_track_list);

        try {
            if(!Utility.isInternetAvailable())
            {
                Utility.displayToast(getApplicationContext(), getApplicationContext().getString(R.string.msg_ConnectionError), Toast.LENGTH_SHORT);
                finish();
                return;
            }
            if( !Utility.googleServicesOK(getApplicationContext()))
            {
                Utility.displayToast(getApplicationContext(), getApplicationContext().getString(R.string.msg_GoogleServicesError), Toast.LENGTH_SHORT);
                finish();
                return;
            }
        } catch (IOException e) {
            e.printStackTrace();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("track_group") != null) {
                trackGroupCode = extras.getString("track_group");

            }
        }

        tracks =new ArrayList<TrackModel>();
//        lastPointId = new BigInteger("0");
        lastTrack = new TrackModel();
        lastTrack.setTrackId( new BigInteger("0"));
        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);


        track = new TrackModel();
        track.setTrackGroup(trackGroupCode);
        tc = new TrackController(getApplicationContext());
        tc.addObserver((Observer) this);

        DialogModel.show(this);
        tc.list(track, lastTrack.getTrackId());


//        Timer myTimer = new Timer();
//        myTimer.schedule(new TimerTask() {
//            @Override
//            public void run() {
//
//                if(lastTrack == null || lastTrack.getTrackId().equals(new BigInteger("0")))
//                {
////                    Log.d("meysam","in timer null");
//                }
//                else
//                {
////                    Log.d("meysam","in timer");
//                    tc.list(track, lastTrack.getTrackId());
//                }
//
//            }
//
//        }, 0, 30000);

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
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_SHORT);
                    try {
                        if(!Utility.isInternetAvailable())
                            finish();
                    } catch (IOException e) {
                        e.printStackTrace();
                    } catch (InterruptedException e) {
                        e.printStackTrace();
                    }
                    if(!Utility.googleServicesOK(getApplicationContext()))
                        finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                List<TrackModel> lastTracks = (List<TrackModel>) arg;
                for (int i = 0; i < lastTracks.size(); i++) {

                    if((i+1) == lastTracks.size())
                    {
                        //last loaded track
                        mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(new LatLng(lastTracks.get(i).getLatitude(), lastTracks.get(i).getLongitude()) , 14.0f));
                        mMap.addMarker(new MarkerOptions()
                                .position(new LatLng(lastTracks.get(i).getLatitude(), lastTracks.get(i).getLongitude()))
                                .title(Utility.convertDateGorgeian2Persian(lastTracks.get(i).getCreatedAt()+ "\n" + ""))
                                .snippet(getResources().getString(R.string.lbl_signal)+": "+TrackModel.getGsmLevelString(lastTracks.get(i).getSignalPower(),getApplicationContext())+"\n"
                                        +getResources().getString(R.string.lbl_battery_percent)+": "+lastTracks.get(i).getBatteryPower()+"%"+"\n"
                                        +getResources().getString(R.string.lbl_battery_status)+": "+TrackModel.getStatusString(lastTracks.get(i).getBatteryStatus(),getApplicationContext())+"\n"
                                        +getResources().getString(R.string.lbl_battery_health)+": "+TrackModel.getHealthString(lastTracks.get(i).getChargeStatus(),getApplicationContext())+"\n"
                                        +getResources().getString(R.string.lbl_battery_plugged)+": "+TrackModel.getPlugTypeString(lastTracks.get(i).getChargeType(),getApplicationContext()))

                                .icon(BitmapDescriptorFactory.fromResource(R.drawable.person)));

                        mMap.setInfoWindowAdapter(new GoogleMap.InfoWindowAdapter() {

                            @Override
                            public View getInfoWindow(Marker arg0) {
                                return null;
                            }

                            @Override
                            public View getInfoContents(Marker marker) {

                                LinearLayout info = new LinearLayout(TrackListActivity.this);
                                info.setOrientation(LinearLayout.VERTICAL);

                                TextView title = new TextView(TrackListActivity.this);
                                title.setTextColor(Color.BLACK);
                                title.setGravity(Gravity.CENTER);
                                title.setTypeface(null, Typeface.BOLD);
                                title.setText(marker.getTitle());

                                TextView snippet = new TextView(TrackListActivity.this);
                                snippet.setTextColor(Color.GRAY);
                                snippet.setText(marker.getSnippet());

                                info.addView(title);
                                info.addView(snippet);

                                return info;
                            }
                        });
                        // Add a thin red line from London to New York.
                        // Add a thin red line from London to New York.
                        if(!lastTrack.getTrackId().equals(new BigInteger("0"))) {

                            LatLng from = new LatLng(lastTrack.getLatitude(), lastTrack.getLongitude());
                            LatLng to = new LatLng(lastTracks.get(i).getLatitude(), lastTracks.get(i).getLongitude());

                            mMap.addPolyline(new PolylineOptions()
                                    .add(from,to)
                                    .width(5)

                            .color(Color.RED));

                            DrawArrowHead(mMap, from, to);

                        }
                        lastTrack = lastTracks.get(i);
                    }
                    else
                    {
//                        mMap.addMarker(new MarkerOptions().position(new LatLng(lastTracks.get(i).getLatitude(), lastTracks.get(i).getLongitude())).title(Utility.getCurrectDateByLanguage(getApplicationContext(), lastTracks.get(i).getCreatedAt())).icon(BitmapDescriptorFactory.fromResource(R.drawable.person)));
                        mMap.addMarker(new MarkerOptions()
                                .position(new LatLng(lastTracks.get(i).getLatitude(), lastTracks.get(i).getLongitude()))
                                .title(Utility.convertDateGorgeian2Persian(lastTracks.get(i).getCreatedAt()+ "\n" + ""))
                                .snippet(getResources().getString(R.string.lbl_signal)+": "+TrackModel.getGsmLevelString(lastTracks.get(i).getSignalPower(),getApplicationContext())+"\n"
                                        +getResources().getString(R.string.lbl_battery_percent)+": "+lastTracks.get(i).getBatteryPower()+"%"+"\n"
                                        +getResources().getString(R.string.lbl_battery_status)+": "+TrackModel.getStatusString(lastTracks.get(i).getBatteryStatus(),getApplicationContext())+"\n"
                                        +getResources().getString(R.string.lbl_battery_health)+": "+TrackModel.getHealthString(lastTracks.get(i).getChargeStatus(),getApplicationContext())+"\n"
                                        +getResources().getString(R.string.lbl_battery_plugged)+": "+TrackModel.getPlugTypeString(lastTracks.get(i).getChargeType(),getApplicationContext()))

                                .icon(BitmapDescriptorFactory.fromResource(R.drawable.person)));
                        // Add a thin red line from London to New York.
                        if(!lastTrack.getTrackId().equals(new BigInteger("0")))
                        {
                            LatLng from = new LatLng(lastTrack.getLatitude(), lastTrack.getLongitude());
                            LatLng to = new LatLng(lastTracks.get(i).getLatitude(), lastTracks.get(i).getLongitude());

                            mMap.addPolyline(new PolylineOptions()
                                    .add(from,to)
                                    .width(5)
                                    .color(Color.RED));
                            DrawArrowHead(mMap, from, to);
                        }
                        lastTrack = lastTracks.get(i);
                    }

                }
                tracks.addAll(lastTracks);
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
                Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);

                Intent i = new Intent(TrackListActivity.this,TrackIndexActivity.class);
                TrackListActivity.this.startActivity(i);
                finish();

            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
        }

    }

    @Override
    public void onMapReady(GoogleMap googleMap) {

        mMap = googleMap;
        mMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);

        // Set a preference for minimum and maximum zoom.
        mMap.setMinZoomPreference(6.0f);
        mMap.setMaxZoomPreference(20.0f);

        mMap.getUiSettings().setZoomControlsEnabled(true);



//        mMap.setOnMapClickListener(new GoogleMap.OnMapClickListener() {
//
//            @Override
//            public void onMapClick(LatLng arg0) {
//
//                // Auto-generated method stub
//                Log.d("meysam", arg0.latitude + "-" + arg0.longitude);
//                Utility.displayToast(getApplicationContext(),"map was touched!!", Toast.LENGTH_LONG);
//
//            }
//        });
//
//        mMap.setOnMapLongClickListener(new GoogleMap.OnMapLongClickListener() {
//            @Override
//            public void onMapLongClick(LatLng latLng) {
//                Utility.displayToast(getApplicationContext(),"map was long touched!!", Toast.LENGTH_LONG);
//
//            }
//
//        });

//        mMap.setOnCameraMoveListener(new GoogleMap.OnCameraMoveListener(){
//
//
//            @Override
//            public void onCameraMove() {
////                Utility.displayToast(getApplicationContext(),"on camera move!!", Toast.LENGTH_SHORT);
////                Log.d("meysam","on camera move");
////                if(lastTrack == null || lastTrack.getTrackId().equals(new BigInteger("0")))
////                {
////                    Utility.displayToast(getApplicationContext(),"on camera move null!!", Toast.LENGTH_SHORT);
////                    Log.d("meysam","on camera move null");
////                }
////                else
////                {
////                    Log.d("meysam","on camera move last");
////                    Utility.displayToast(getApplicationContext(),"on camera move last!!", Toast.LENGTH_SHORT);
////                    tc.list(track, lastTrack.getTrackId());
////                }
//
//
//            }
//        });

    }



    private final double degreesPerRadian = 180.0 / Math.PI;

    private void DrawArrowHead(GoogleMap mMap, LatLng from, LatLng to){
        // obtain the bearing between the last two points
        double bearing = GetBearing(from, to);

        // round it to a multiple of 3 and cast out 120s
        double adjBearing = Math.round(bearing / 3) * 3;
        while (adjBearing >= 120) {
            adjBearing -= 120;
        }

        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);

        // Get the corresponding triangle marker from Google
        URL url;
        Bitmap image = null;

        try {
            url = new URL("http://www.google.com/intl/en_ALL/mapfiles/dir_" + String.valueOf((int)adjBearing) + ".png");
            try {
                image = BitmapFactory.decodeStream(url.openConnection().getInputStream());
            } catch (IOException e) {
                //  Auto-generated catch block
                e.printStackTrace();
            }
        } catch (MalformedURLException e) {
            //  Auto-generated catch block
            e.printStackTrace();
        }

        if (image != null){

            // Anchor is ratio in range [0..1] so value of 0.5 on x and y will center the marker image on the lat/long
            float anchorX = 0.5f;
            float anchorY = 0.5f;

            int offsetX = 0;
            int offsetY = 0;

            // images are 24px x 24px
            // so transformed image will be 48px x 48px

            //315 range -- 22.5 either side of 315
            if (bearing >= 292.5 && bearing < 335.5){
                offsetX = 24;
                offsetY = 24;
            }
            //270 range
            else if (bearing >= 247.5 && bearing < 292.5){
                offsetX = 24;
                offsetY = 12;
            }
            //225 range
            else if (bearing >= 202.5 && bearing < 247.5){
                offsetX = 24;
                offsetY = 0;
            }
            //180 range
            else if (bearing >= 157.5 && bearing < 202.5){
                offsetX = 12;
                offsetY = 0;
            }
            //135 range
            else if (bearing >= 112.5 && bearing < 157.5){
                offsetX = 0;
                offsetY = 0;
            }
            //90 range
            else if (bearing >= 67.5 && bearing < 112.5){
                offsetX = 0;
                offsetY = 12;
            }
            //45 range
            else if (bearing >= 22.5 && bearing < 67.5){
                offsetX = 0;
                offsetY = 24;
            }
            //0 range - 335.5 - 22.5
            else {
                offsetX = 12;
                offsetY = 24;
            }

            Bitmap wideBmp;
            Canvas wideBmpCanvas;
            Rect src, dest;

            // Create larger bitmap 4 times the size of arrow head image
            wideBmp = Bitmap.createBitmap(image.getWidth() * 2, image.getHeight() * 2, image.getConfig());

            wideBmpCanvas = new Canvas(wideBmp);

            src = new Rect(0, 0, image.getWidth(), image.getHeight());
            dest = new Rect(src);
            dest.offset(offsetX, offsetY);

            wideBmpCanvas.drawBitmap(image, src, dest, null);

            mMap.addMarker(new MarkerOptions()
                    .position(to)
                    .icon(BitmapDescriptorFactory.fromBitmap(wideBmp))
                    .anchor(anchorX, anchorY));
        }
    }

    private double GetBearing(LatLng from, LatLng to){
        double lat1 = from.latitude * Math.PI / 180.0;
        double lon1 = from.longitude * Math.PI / 180.0;
        double lat2 = to.latitude * Math.PI / 180.0;
        double lon2 = to.longitude * Math.PI / 180.0;

        // Compute the angle.
        double angle = - Math.atan2( Math.sin( lon1 - lon2 ) * Math.cos( lat2 ), Math.cos( lat1 ) * Math.sin( lat2 ) - Math.sin( lat1 ) * Math.cos( lat2 ) * Math.cos( lon1 - lon2 ) );

        if (angle < 0.0)
            angle += Math.PI * 2.0;

        // And convert result to degrees.
        angle = angle * degreesPerRadian;

        return angle;
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if(keyCode == KeyEvent.KEYCODE_BACK)
        {
            finish();
            return true;
        }
        return super.onKeyDown(keyCode, event);
    }
}
