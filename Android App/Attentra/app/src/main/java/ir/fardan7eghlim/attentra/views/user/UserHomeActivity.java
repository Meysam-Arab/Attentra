package ir.fardan7eghlim.attentra.views.user;

import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.app.ProgressDialog;
import android.content.ActivityNotFoundException;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.support.design.internal.NavigationMenuItemView;
import android.support.design.internal.NavigationMenuView;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.NavigationView;
import android.support.design.widget.Snackbar;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.DisplayMetrics;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AnalogClock;
import android.widget.Button;
import android.widget.Chronometer;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextClock;
import android.widget.TextView;
import android.widget.Toast;

import com.github.amlcurran.showcaseview.OnShowcaseEventListener;
import com.github.amlcurran.showcaseview.ShowcaseView;
import com.github.amlcurran.showcaseview.targets.ActionViewTarget;
import com.github.amlcurran.showcaseview.targets.ViewTarget;
import com.onesignal.OSSubscriptionObserver;
import com.onesignal.OSSubscriptionStateChanges;
import com.onesignal.OneSignal;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.HomeController;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.models.UserTypeModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.QRCode;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.utils.ViewTargets;
import ir.fardan7eghlim.attentra.views.home.DownloadActivity;
import ir.fardan7eghlim.attentra.views.home.HomeActivity;
import ir.fardan7eghlim.attentra.views.home.WelcomeActivity;

public class UserHomeActivity extends BaseActivity implements Observer,View.OnClickListener {
    private ProgressDialog pDialog;
    private CustomAdapterList CAL;
    private TextView status_uh;
    private TextView companyTitle_ua;
    private FrameLayout userList;
    private LinearLayout up_detai;
    private int counter=0;
    private ShowcaseView sv;
    private DatabaseHandler db;
    private Boolean InCreating;
    private HomeController hc;


    private boolean doubleBackToExitPressedOnce = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_home);
        super.onCreateDrawer();

        InCreating = true;
        hc =  new HomeController(getApplicationContext());
        hc.addObserver(this);

        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));

        // Register to receive messages.
        // We are registering an observer (mMessageReceiver) to receive Intents
        // with actions named "custom-event-name".
        LocalBroadcastManager.getInstance(this).registerReceiver(mMeysamBroadcastReceiver,
                new IntentFilter("user_home_activity_broadcast"));

        db = new DatabaseHandler(getApplicationContext());

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Snackbar.make(view, getString(R.string.refreshing), Snackbar.LENGTH_SHORT)
                        .setAction("Action", null).show();
                mainAction();
            }
        });

        mainAction();

        hc.getVersion();

        //learning
        SessionModel session = new SessionModel(this);
        HashMap hm = session.getUserDetails();

        if(session.getStringItem(SessionModel.KEY_FIRST_TIME_VISIT) == null)
        {
            session.saveItem(SessionModel.KEY_FIRST_TIME_VISIT,"yes");
            if (hm.get("type") != null) {
                if (new Integer(hm.get("type").toString()).equals(UserTypeModel.CEO)) {
                    tuturial_ceo();
                }
            else if (new Integer(hm.get("type").toString()).equals(UserTypeModel.EMPLOYEE)) {
                        tuturial_emp();
            }
                else {
                    // for middle ceo
                    tuturial_mceo();
                }
            }
        }

//        if(!session.hasItem(SessionModel.KEY_ONE_SIGNAL_PLAYER_ID))
//        {
//
//            OneSignal.addSubscriptionObserver(new OSSubscriptionObserver() {
//                @Override
//                public void onOSSubscriptionChanged(OSSubscriptionStateChanges stateChanges) {
//                    if (!stateChanges.getFrom().getSubscribed() &&
//                            stateChanges.getTo().getSubscribed()) {
//
//                        // get player ID
//                        String userId = stateChanges.getTo().getUserId();
//
//                        if (userId != null)
//                        {
//                            // meysam - do whatever you want - ex:store id to server
//
//                        }
//                    }
//
//                }
//            });
//        }

    }


    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

//        mainAction();
    }


    private void tuturial_ceo() {
//        RelativeLayout.LayoutParams lps = new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
//        lps.addRule(RelativeLayout.ALIGN_PARENT_BOTTOM);
//        lps.addRule(RelativeLayout.ALIGN_PARENT_LEFT);
//        int margin = ((Number) (getResources().getDisplayMetrics().density * 12)).intValue();
//        lps.setMargins(margin, margin, margin, margin);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        Button customButton = (Button) getLayoutInflater().inflate(R.layout.view_custom_button, null);
        ViewTarget navigationButtonViewTarget = null;
        try {
            navigationButtonViewTarget = ViewTargets.navigationButtonViewTarget(toolbar);
        sv = new ShowcaseView.Builder(this)
                .withMaterialShowcase()
                .setTarget(navigationButtonViewTarget)
                .setContentTitle(getString(R.string.ttrl_ceo_01_h))
                .setContentText(getString(R.string.ttrl_ceo_01))
                .setStyle(R.style.CustomShowcaseTheme2)
                .withMaterialShowcase()
                .replaceEndButton(customButton)
                .build();
        sv.setOnClickListener(this);
//        sv.setButtonPosition(lps);
        } catch (ViewTargets.MissingViewException e) {
            e.printStackTrace();
        }
    }
    private void tuturial_emp() {
        counter=7;
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        Button customButton = (Button) getLayoutInflater().inflate(R.layout.view_custom_button, null);
        ViewTarget navigationButtonViewTarget = null;
        try {
            navigationButtonViewTarget = ViewTargets.navigationButtonViewTarget(toolbar);
            sv = new ShowcaseView.Builder(this)
                    .withMaterialShowcase()
                    .setTarget(navigationButtonViewTarget)
                    .setContentTitle(getString(R.string.ttrl_ceo_01_h))
                    .setContentText(getString(R.string.ttrl_ceo_01))
                    .setStyle(R.style.CustomShowcaseTheme2)
                    .withMaterialShowcase()
                    .replaceEndButton(customButton)
                    .build();
            sv.setOnClickListener(this);
        } catch (ViewTargets.MissingViewException e) {
            e.printStackTrace();
        }
    }
    private void tuturial_mceo() {
        counter=12;
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        Button customButton = (Button) getLayoutInflater().inflate(R.layout.view_custom_button, null);
        ViewTarget navigationButtonViewTarget = null;
        try {
            navigationButtonViewTarget = ViewTargets.navigationButtonViewTarget(toolbar);
            sv = new ShowcaseView.Builder(this)
                    .withMaterialShowcase()
                    .setTarget(navigationButtonViewTarget)
                    .setContentTitle(getString(R.string.ttrl_ceo_01_h))
                    .setContentText(getString(R.string.ttrl_ceo_01))
                    .setStyle(R.style.CustomShowcaseTheme2)
                    .withMaterialShowcase()
                    .replaceEndButton(customButton)
                    .build();
            sv.setOnClickListener(this);
        } catch (ViewTargets.MissingViewException e) {
            e.printStackTrace();
        }
    }

    @Override
    public void onClick(View v) {
        DrawerLayout dl= (DrawerLayout) findViewById(R.id.drawer_layout);
        switch (counter) {
            case 0://ceo 0-6
                dl.openDrawer(GravityCompat.START);
                ViewTarget target = ItemMenuShowCase(0);
                sv.setShowcase(target, true);
                sv.setContentTitle(getString(R.string.ttrl_ceo_02_h));
                sv.setContentText(getString(R.string.ttrl_ceo_02));
                break;
            case 1:
                sv.setContentTitle(getString(R.string.ttrl_ceo_03_h));
                sv.setContentText(getString(R.string.ttrl_ceo_03));
                sv.setShowcase(ItemMenuShowCase(1), true);
                break;
            case 2:
                sv.setContentTitle(getString(R.string.ttrl_ceo_04_h));
                sv.setContentText(getString(R.string.ttrl_ceo_04));
                sv.setShowcase(ItemMenuShowCase(2), true);
                break;
            case 3:
                sv.setContentTitle(getString(R.string.ttrl_ceo_05_h));
                sv.setContentText(getString(R.string.ttrl_ceo_05));
                sv.setShowcase(ItemMenuShowCase(3), true);
                break;
            case 4:
                sv.setContentTitle(getString(R.string.ttrl_ceo_06_h));
                sv.setContentText(getString(R.string.ttrl_ceo_06));
                sv.setShowcase(ItemMenuShowCase(4), true);
                break;
            case 5:
                sv.setContentTitle(getString(R.string.ttrl_ceo_07_h));
                sv.setContentText(getString(R.string.ttrl_ceo_07));
                sv.setShowcase(ItemMenuShowCase(5), true);
                break;
            case 6:
                sv.hide();
                if (dl.isDrawerOpen(Gravity.RIGHT))
                    dl.closeDrawer(Gravity.RIGHT);
                else
                    dl.closeDrawer(Gravity.LEFT);
                break;
            case 7://employe 7-10
                dl.openDrawer(GravityCompat.START);
                target = ItemMenuShowCase(0);
                sv.setShowcase(target, true);
                sv.setContentTitle(getString(R.string.ttrl_emp_01_h));
                sv.setContentText(getString(R.string.ttrl_emp_01));
                break;
            case 8:
                sv.setContentTitle(getString(R.string.ttrl_emp_02_h));
                sv.setContentText(getString(R.string.ttrl_emp_02));
                sv.setShowcase(ItemMenuShowCase(1), true);
                break;
            case 9:
                sv.setContentTitle(getString(R.string.ttrl_emp_03_h));
                sv.setContentText(getString(R.string.ttrl_emp_03));
                sv.setShowcase(ItemMenuShowCase(2), true);
                break;
            case 10:
                sv.setContentTitle(getString(R.string.ttrl_emp_04_h));
                sv.setContentText(getString(R.string.ttrl_emp_04));
                sv.setShowcase(ItemMenuShowCase(3), true);
                break;
            case 11:
                sv.hide();
                if (dl.isDrawerOpen(Gravity.RIGHT))
                    dl.closeDrawer(Gravity.RIGHT);
                else
                    dl.closeDrawer(Gravity.LEFT);
            case 12://mceo 12-10
                dl.openDrawer(GravityCompat.START);
                target = ItemMenuShowCase(0);
                sv.setShowcase(target, true);
                sv.setContentTitle(getString(R.string.ttrl_ceo_02_h));
                sv.setContentText(getString(R.string.ttrl_ceo_02));
                break;
            case 13:
                sv.setContentTitle(getString(R.string.ttrl_ceo_03_h));
                sv.setContentText(getString(R.string.ttrl_ceo_03));
                sv.setShowcase(ItemMenuShowCase(1), true);
                break;
            case 14:
                sv.setContentTitle(getString(R.string.ttrl_emp_02_h));
                sv.setContentText(getString(R.string.ttrl_emp_02));
                sv.setShowcase(ItemMenuShowCase(2), true);
                break;
            case 15:
                sv.setContentTitle(getString(R.string.ttrl_ceo_04_h));
                sv.setContentText(getString(R.string.ttrl_ceo_04));
                sv.setShowcase(ItemMenuShowCase(3), true);
                break;
            case 16:
                sv.setContentTitle(getString(R.string.ttrl_ceo_05_h));
                sv.setContentText(getString(R.string.ttrl_ceo_05));
                sv.setShowcase(ItemMenuShowCase(4), true);
                break;
            case 17:
                sv.setContentTitle(getString(R.string.ttrl_emp_04_h));
                sv.setContentText(getString(R.string.ttrl_emp_04));
                sv.setShowcase(ItemMenuShowCase(5), true);
                break;
            default:
                sv.hide();
                if (dl.isDrawerOpen(Gravity.RIGHT))
                    dl.closeDrawer(Gravity.RIGHT);
                else
                    dl.closeDrawer(Gravity.LEFT);
        }
        counter++;
    }

    private ViewTarget ItemMenuShowCase(int plus){
        DrawerLayout dl= (DrawerLayout) findViewById(R.id.drawer_layout);
        int yt = 0;
        for (int i = 0; i < dl.getChildCount(); i++) {
            if (dl.getChildAt(i) instanceof NavigationView) {
                yt = i;
                break;
            }
        }
        NavigationView navigationView= (NavigationView) dl.getChildAt(yt);
        NavigationMenuView navigationMenuView= (NavigationMenuView) navigationView.getChildAt(0);
        int item = 0;
        for (int i = 0; i < navigationMenuView.getChildCount(); i++) {
            if (navigationMenuView.getChildAt(i) instanceof NavigationMenuItemView) {
                item = i;
                break;
            }
        }
        item+=plus;
        return new ViewTarget(navigationMenuView.getChildAt(item));
    }
    @Override
    protected void onResume() {
        super.onResume();
        NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
        navigationView.setBackgroundResource(R.drawable.backrepeat);
        ////////////////////////////
        ///////set navigation header values///////////////////
        View hView =  navigationView.getHeaderView(0);
        ImageView nav_user_image = (ImageView)hView.findViewById(R.id.nav_hdr_user_image);
        nav_user_image.setImageBitmap(db.getUserDetails().getProfilePicture());
        ImageView nav_hdr_bg_image= (ImageView) hView.findViewById(R.id.nav_hdr_bg_image);
        Bitmap b=db.getUserDetails().getProfilePicture();
        if(b==null){
            UserModel user=db.getUserDetails();
            b= BitmapFactory.decodeResource(getApplicationContext().getResources(),
                    user.getGender().equals(UserModel.FemaleCodeString)?R.drawable.female2:R.drawable.male2);
        }
        if(b!=null && b.getWidth()<b.getHeight()){
            final BitmapFactory.Options bitmapOptions=new BitmapFactory.Options();
            DisplayMetrics metrics = getResources().getDisplayMetrics();
            bitmapOptions.inDensity = metrics.densityDpi;
            bitmapOptions.inTargetDensity=1;
            b.setDensity(Bitmap.DENSITY_NONE);
            b = Bitmap.createBitmap(b, 0, 0, b.getWidth(), b.getWidth());
        }

        Drawable d = new BitmapDrawable(getResources(), b);
        d.setAlpha(80);
        nav_hdr_bg_image.setImageDrawable(d);
//        mainAction();
        // meysam - every x minutes
        if(!InCreating)
        {

            if(session.hasItem(SessionModel.KEY_SERVER_CHECK_INTERVAL))
            {
                int checkServerInterval = session.getIntegerItem(SessionModel.KEY_SERVER_CHECK_INTERVAL);//meysam - minutes

                if(session.hasItem(SessionModel.KEY_LAST_CHECK_SERVER_TIME))
                {
                    if(Utility.isTimeSpent(session.getStringItem(SessionModel.KEY_LAST_CHECK_SERVER_TIME),checkServerInterval))
                    {
                        mainAction();
                    }
                }
                else
                {
                    mainAction();
                }

            }

        }
        InCreating = false;

    }
    //the main actions of this page (for easy to refreshing this page)
    private void mainAction(){

        if(Utility.isNetworkAvailable(this)) {

            SessionModel session = new SessionModel(this);

            pDialog.show();

            userList= (FrameLayout) findViewById(R.id.list_user_frame_uh);

            UserModel user = null;
            user = db.getUserDetails();
            if(user != null)
            {
                if(!user.getName().equals("null") && !user.getFamily().equals("null"))
                {
                    setTitle(user.getName()+" "+user.getFamily());
                }
                else
                {
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_compelete_information),Toast.LENGTH_LONG);
                }
            }

            status_uh = (TextView) findViewById(R.id.status_uh);
            companyTitle_ua = (TextView) findViewById(R.id.companyTitle_ua);


            hc.index(user);

            HashMap hm = session.getUserDetails();
            if (hm.get("type") != null) {
                if (new Integer(hm.get("type").toString()).equals(UserTypeModel.CEO)) {
                    up_detai= (LinearLayout) findViewById(R.id.up_detail_uh);
                    up_detai.setVisibility(View.GONE);

                } else if (new Integer(hm.get("type").toString()).equals(UserTypeModel.EMPLOYEE)) {

                } else {

                }
            }

        }else{
            Utility.displayToast(getApplicationContext(),getResources().getString(R.string.error_no_connection),Toast.LENGTH_LONG);
        }


    }

    @Override
    public void update(Observable o, Object arg) {
        if(pDialog.isShowing())
            pDialog.hide();
        pDialog.dismiss();
        if (arg != null) {
            if (arg instanceof Boolean) {
                if (Boolean.parseBoolean(arg.toString()) == false) {
                    Utility.displayToast(getApplicationContext(), getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    status_uh.setText(R.string.msg_MessageNotSpecified);
                    status_uh.setTextColor(Color.RED);
                    companyTitle_ua.setText(R.string.msg_MessageNotSpecified);
                    userList.setVisibility(View.GONE);
                }
            } else if (arg instanceof ArrayList) {
                if (((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_INDEX_HOME)) {
                    session.saveItem(SessionModel.KEY_LAST_CHECK_SERVER_TIME, new Date().getTime() + "");
                    //qr code
                    ImageView QR_uh = (ImageView) findViewById(R.id.QR_uh);
                    ArrayList<Object> Items = (ArrayList<Object>) ((ArrayList) arg).get(1);
                    //0: qr code
                    QR_uh.setImageBitmap(new QRCode().generate(Items.get(0).toString()));
                    //1:start date (null:user is not in company now,otherwise time of entring
                    String timeEntring = Items.get(1).toString();
                    if (timeEntring.equals("null")) {
                        status_uh.setText(UserHomeActivity.this.getString(R.string.tlt_you_are_not_in_company));
                        status_uh.setTextColor(Color.RED);
                    } else {
                        timeEntring=Utility.getCurrectDateByLanguage(getApplicationContext(),timeEntring);
                        String[] temp=timeEntring.split(" ");
                        status_uh.setText(UserHomeActivity.this.getString(R.string.tlt_you_are_at_work_since)+temp[0] );
                        status_uh.setTextColor(Color.BLACK);
                        setTime(temp[1]);
                    }
                    //2:company name
                    companyTitle_ua.setText(Items.get(2).toString());
                    //list of user
                    ArrayList<UserModel> users = (ArrayList<UserModel>) ((ArrayList) arg).get(2);
                    if(users.size()>0){
                        fillList(users);}
                    else{
                        userList.setVisibility(View.GONE);
                    }


                    UserModel user=db.getUserDetails();
                    user.setBalance(((UserModel) ((ArrayList) arg).get(3)).getBalance());
                    UserModel new_user=user;
                    db.editUser(new_user);

                    NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
                    View hView =  navigationView.getHeaderView(0);
                    TextView nav_payment = (TextView)hView.findViewById(R.id.nav_hdr_payment);
                    nav_payment.setText(getApplicationContext().getString(R.string.chargRemidTitle)+" "+db.getUserDetails().getBalance() + " "+getApplicationContext().getString(R.string.Tooman) );

                }else if(((ArrayList) arg).get(0).equals(RequestRespondModel.TAG_GET_VERSION_HOME))
                {


                    session.saveItem(SessionModel.KEY_SERVER_CHECK_INTERVAL,Integer.parseInt(((ArrayList) arg).get(5).toString()));
                    session.saveItem(SessionModel.KEY_LAST_CHECK_SERVER_TIME, new Date().getTime() + "");
                    if(!((ArrayList) arg).get(6).toString().equals(""))
                    {
                        //meysam - show message
                        //show user in dialog
                        AlertDialog alertDialog = new AlertDialog.Builder(UserHomeActivity.this).create();
                        alertDialog.setTitle(getString(R.string.msg_ImportantNotice));
                        alertDialog.setMessage(((ArrayList) arg).get(6).toString());
                        alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, getString(R.string.btn_OK),
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int which) {
                                        dialog.dismiss();
                                    }
                                });
                        alertDialog.show();
                    }
                    ////////////////////////////////////////////
                    PackageInfo pInfo = null;
                    Float version = null;
                    try {
                        pInfo = getPackageManager().getPackageInfo(getPackageName(), 0);
                    } catch (PackageManager.NameNotFoundException e) {
                        e.printStackTrace();
                    }
                    if(pInfo != null)
                    {
                        version = new Float(pInfo.versionName);
                    }
                    Float current_ver = version;

                    version = new Float(((ArrayList) arg).get(1).toString());
                    Float new_ver = version;

                    if(Math.floor(new_ver) > Math.floor(current_ver))
                    {

                        Intent i=new Intent(UserHomeActivity.this,DownloadActivity.class);
                        i.putExtra("link", ((ArrayList) arg).get(2).toString());
                        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                        UserHomeActivity.this.startActivity(i);
                        finish();

                    }
                    else if(new_ver > current_ver)
                    {

                        Utility.displayToast(getApplicationContext(),getResources().getString(R.string.msg_OldVersion),Toast.LENGTH_LONG);
                    }
                    else
                    {
                        //nothing
                    }
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
                    status_uh.setText(UserHomeActivity.this.getString(R.string.msg_MessageNotSpecified));
                    status_uh.setTextColor(Color.RED);
                    companyTitle_ua.setText(R.string.msg_MessageNotSpecified);
                    userList.setVisibility(View.GONE);
                }
            }
            else
            {
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(), UserHomeActivity.this.getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            status_uh.setText(UserHomeActivity.this.getString(R.string.msg_MessageNotSpecified));
            status_uh.setTextColor(Color.RED);
            companyTitle_ua.setText(R.string.msg_MessageNotSpecified);
            userList.setVisibility(View.GONE);
        }
    }

    //fill list of user
    private void fillList(List<UserModel> users) {
        //make list
        userList.setVisibility(View.VISIBLE);
        ListView lv = (ListView) findViewById(R.id.list_user_s_uh);
        CAL = new CustomAdapterList(this, new ArrayList<Object>(users), RequestRespondModel.TAG_INDEX_HOME);
        lv.setAdapter(CAL);
        lv.invalidateViews();
        lv.setOnTouchListener(new View.OnTouchListener() {
            // Setting on Touch Listener for handling the touch inside ScrollView
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                // Disallow the touch request for parent scroll on touch of child view
                v.getParent().requestDisallowInterceptTouchEvent(true);
                return false;
            }
        });
        pDialog.dismiss();
    }

//    @Override
//    public boolean onKeyDown(int keyCode, KeyEvent event) {
////        if (keyCode == KeyEvent.KEYCODE_BACK) {
////            AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
////            alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListener);
////            alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListener);
////            alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
////            alertDialogBuilder.show();
////        }
////        return false;
//    }
@Override
public void onBackPressed() {
    if (doubleBackToExitPressedOnce) {
        super.onBackPressed();
        return;
    }

    this.doubleBackToExitPressedOnce = true;

    //toast with custom color
//    Toast toast =Utility.displayToast(this,  getString(R.string.msg_PressBackAgain), Toast.LENGTH_SHORT);
//    TextView toastMessage = (TextView) toast.getView().findViewById(android.R.id.message);
////    toastMessage.setTextColor(Color.GREEN);
//    toastMessage.setBackgroundColor(Color.parseColor("#3d7a19"));
////    toastMessage.setDrawingCacheBackgroundColor(Color.parseColor("#3d7a19"));
//    toast.show();
//    Toast toast = Utility.displayToast(getApplicationContext(),  getString(R.string.msg_PressBackAgain), Toast.LENGTH_LONG);
//    View view = toast.getView();
//    view.setBackgroundResource(R.drawable.shape02);
////    TextView text = (TextView) view.findViewById(android.R.id.message);
///*here you can do anything with text*/
//    toast.show();
    Utility.displayToast(getApplicationContext(),getString(R.string.msg_PressBackAgain),Toast.LENGTH_LONG);
    //////////////////////////////////////////////////////////

    new Handler().postDelayed(new Runnable() {

        @Override
        public void run() {
            doubleBackToExitPressedOnce=false;
        }
    }, 2000);
}

    DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which) {
                case DialogInterface.BUTTON_POSITIVE:
                    //Yes button clicked

//                    new SessionModel(getApplicationContext()).logoutUser();
                    //stop tracking service if is running - MeysamTrack
//                    if(Utility.isTrackingServiceRunning())
//                    {
//                        Intent intent = new Intent(getApplicationContext(), TrackingService.class);
//                        getApplicationContext().stopService(intent);
//                    }
                    SessionModel session = new SessionModel(getApplicationContext());
                    if (!session.isLoggedIn()) {
                        // UserModel is already logged in. Take him to main activity
                        Intent intent = new Intent(UserHomeActivity.this, UserLoginActivity.class);
                        startActivity(intent);
//                        finish();
                    }

                    finish();
//                    moveTaskToBack(true);
//                    android.os.Process.killProcess(android.os.Process.myPid());
//                    System.exit(0);
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked
                    break;
            }
        }
    };
    private void setTime(String time){
        String Time[]=time.split(":");
        ImageView sb_digi_1= (ImageView) findViewById(R.id.sb_digi_1);
        ImageView sb_digi_2= (ImageView) findViewById(R.id.sb_digi_2);
        String A=Time[0].substring(0,1);
        String B=Time[0].substring(1,2);
        sb_digi_1.setImageResource(Utility.digiConvertPicture(new Integer(A)));
        sb_digi_2.setImageResource(Utility.digiConvertPicture(new Integer(B)));
        ImageView sb_digi_3= (ImageView) findViewById(R.id.sb_digi_3);
        ImageView sb_digi_4= (ImageView) findViewById(R.id.sb_digi_4);
        A=Time[1].substring(0,1);
        B=Time[1].substring(1,2);
        sb_digi_3.setImageResource(Utility.digiConvertPicture(new Integer(A)));
        sb_digi_4.setImageResource(Utility.digiConvertPicture(new Integer(B)));
        ImageView sb_digi_5= (ImageView) findViewById(R.id.sb_digi_5);
        ImageView sb_digi_6= (ImageView) findViewById(R.id.sb_digi_6);
        A=Time[2].substring(0,1);
        B=Time[2].substring(1,2);
        sb_digi_5.setImageResource(Utility.digiConvertPicture(new Integer(A)));
        sb_digi_6.setImageResource(Utility.digiConvertPicture(new Integer(B)));
    }


    // Our handler for received Intents. This will be called whenever an Intent
// with an action named "custom-event-name" is broadcasted.
    private BroadcastReceiver mMeysamBroadcastReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            // Get extra data included in the Intent

            if(intent.hasExtra("logout"))
            {
                session.logoutUser(false);
//                DatabaseHandler db = new DatabaseHandler(getApplicationContext());
//                db.deleteAllTablerecords();

                Toast.makeText(UserHomeActivity.this,"لطفا دومرتبه وارد شوید",Toast.LENGTH_SHORT);

                //go to register
                Intent i = new Intent(UserHomeActivity.this,WelcomeActivity.class);
                UserHomeActivity.this.startActivity(i);
                UserHomeActivity.this.finish();
            }

        }
    };

    @Override
    protected void onDestroy() {
        LocalBroadcastManager.getInstance(this).unregisterReceiver(mMeysamBroadcastReceiver);

        if(pDialog != null)
        {
            pDialog.dismiss();
            pDialog = null;
        }
        if(hc != null)
        {
            hc.deleteObservers();
            hc = null;
        }
        super.onDestroy();
    }
}
