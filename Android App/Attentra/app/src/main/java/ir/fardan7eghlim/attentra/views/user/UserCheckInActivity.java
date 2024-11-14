package ir.fardan7eghlim.attentra.views.user;

import android.Manifest;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.media.AudioManager;
import android.media.ToneGenerator;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.text.Html;
import android.util.Log;
import android.util.SparseArray;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.widget.Toast;

import com.google.android.gms.vision.CameraSource;
import com.google.android.gms.vision.Detector;
import com.google.android.gms.vision.barcode.Barcode;
import com.google.android.gms.vision.barcode.BarcodeDetector;

import java.io.IOException;
import java.math.BigInteger;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.AttendanceController;
import ir.fardan7eghlim.attentra.controllers.MissionController;
import ir.fardan7eghlim.attentra.interfaces.UserCompanyInterface;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.MissionModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.attendance.AttendanceStoreSelfLocationActivity;
import ir.fardan7eghlim.attentra.views.mission.MissionIndexActivity;

public class UserCheckInActivity extends BaseActivity implements Observer {

    Context context;
    private ProgressDialog pDialog;
    private static boolean waitForServerRespond;

    private static final int CAMERA_REQUEST = 1557;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_check_in);
        super.onCreateDrawer();


        context=this;

        waitForServerRespond = false;


        if (Build.VERSION.SDK_INT >= 23)
        {
            // Marshmallow+
            int hasWriteContactsPermission = checkSelfPermission(Manifest.permission.CAMERA);
            if (hasWriteContactsPermission != PackageManager.PERMISSION_GRANTED) {
                requestPermissions(new String[] {Manifest.permission.CAMERA},
                        CAMERA_REQUEST);
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

        final SurfaceView cameraView = (SurfaceView) findViewById(R.id.camera_view);

        BarcodeDetector barcodeDetector =
                new BarcodeDetector.Builder(this)
                        .setBarcodeFormats(Barcode.QR_CODE)
                        .build();
        barcodeDetector.setProcessor(new Detector.Processor<Barcode>() {
            @Override
            public void release() {
            }

            @Override
            public void receiveDetections(Detector.Detections<Barcode> detections) {
                final SparseArray<Barcode> barcodes = detections.getDetectedItems();
                if (barcodes.size() != 0) {
                    if(waitForServerRespond != true)
                    {
                        waitForServerRespond = true;

                        // Progress dialog
                        UserCheckInActivity.this.runOnUiThread(new Runnable() {
                            public void run() {
                                pDialog = new ProgressDialog(UserCheckInActivity.this);
                                pDialog.setCancelable(false);
                                pDialog.setMessage(getString(R.string.dlg_Wait));
                                pDialog.show();                                }
                        });


                        AttendanceModel attendance = new AttendanceModel();
                        attendance.setMission(false);
                        AttendanceController ac = new AttendanceController(context);
                        ac.addObserver((Observer) context);

                        ac.store(attendance, barcodes.valueAt(0).rawValue,false, session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));

                        ToneGenerator toneG = new ToneGenerator(AudioManager.STREAM_ALARM, 100);
                        toneG.startTone(ToneGenerator.TONE_CDMA_ALERT_CALL_GUARD, 200);

                        barcodes.clear();

                    }
                }
            }
        });

        final CameraSource cameraSource = new CameraSource
                .Builder(this, barcodeDetector)
                .setAutoFocusEnabled(true)
                .setRequestedPreviewSize(640, 480)
                .build();

        cameraView.getHolder().addCallback(new SurfaceHolder.Callback() {
            @Override
            public void surfaceCreated(SurfaceHolder holder) {
                try {
                    cameraSource.start(cameraView.getHolder());

                } catch (IOException ie) {
                    Log.e("CAMERA SOURCE", ie.getMessage());
                }
                catch (SecurityException ie) {
                    Log.e("CAMERA SOURCE", ie.getMessage());
                }
            }

            @Override
            public void surfaceChanged(SurfaceHolder holder, int format, int width, int height) {
            }

            @Override
            public void surfaceDestroyed(SurfaceHolder holder) {
                cameraSource.stop();
            }
        });
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
                   Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                }
                waitForServerRespond = false;

            }
//            else if(arg instanceof UserModel)
//            {
//                UserModel user = (UserModel) arg;
//               //show user in dialog
//                AlertDialog alertDialog = new AlertDialog.Builder(UserCheckInActivity.this).create();
//                alertDialog.setTitle(getString(R.string.msg_OperationSuccess));
//                alertDialog.setMessage(user.getName()+" "+user.getFamily());
//                alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, getString(R.string.btn_OK),
//                        new DialogInterface.OnClickListener() {
//                            public void onClick(DialogInterface dialog, int which) {
//                                dialog.dismiss();
//                                waitForServerRespond = false;
//                            }
//                        });
//                alertDialog.show();
//                ToneGenerator toneG = new ToneGenerator(AudioManager.STREAM_ALARM, 100);
//                toneG.startTone(ToneGenerator.TONE_CDMA_ALERT_CALL_GUARD, 200);
//            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_STORE_ATTENDANCE))
                {

                    UserModel user = (UserModel) ((ArrayList) arg).get(1);
                    String status = (String) ((ArrayList) arg).get(2);
                    //show user in dialog
                    AlertDialog alertDialog = new AlertDialog.Builder(UserCheckInActivity.this).create();
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

                    alertDialog.setMessage(user.getName()+" "+user.getFamily());
                    alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, getString(R.string.btn_OK),
                            new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.dismiss();
                                    waitForServerRespond = false;
                                }
                            });
                    alertDialog.show();
                    ToneGenerator toneG = new ToneGenerator(AudioManager.STREAM_ALARM, 100);
                    toneG.startTone(ToneGenerator.TONE_CDMA_ALERT_CALL_GUARD, 200);
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


    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
//        Toast.makeText(AttendanceStoreSelfLocationActivity.this, "در جواب اجازه", Toast.LENGTH_SHORT)
//                .show();
        switch (requestCode)
        {
            case CAMERA_REQUEST:
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
                    Toast.makeText(UserCheckInActivity.this, UserCheckInActivity.this.getString(R.string.msg_LocationAccessDenied), Toast.LENGTH_SHORT)
                            .show();
                    UserCheckInActivity.this.finish();
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
