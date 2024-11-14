//meysam 13960209
package ir.fardan7eghlim.attentra.views.home;


import android.annotation.SuppressLint;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.res.Configuration;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Build;
import android.os.Handler;
import android.support.annotation.RequiresApi;
import android.support.v4.content.ContextCompat;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.util.DisplayMetrics;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.animation.TranslateAnimation;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Observable;
import java.util.Observer;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.HomeController;
import ir.fardan7eghlim.attentra.controllers.LanguageController;
import ir.fardan7eghlim.attentra.models.LanguageModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.aboutus.AboutUsActivity;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;
import ir.fardan7eghlim.attentra.views.user.UserRegisterActivity;

public class HomeActivity extends AppCompatActivity implements View.OnClickListener, Observer {


    Button btn_LogIn = null;
    Button btn_AboutUs = null;
    Button btn_Tutorial = null;
    Button btn_Register = null;
    private ArrayList<LanguageModel> languages;
    String first_defult_lang;
    private SessionModel session;
    private boolean doubleBackToExitPressedOnce = false;

//    @RequiresApi(api = Build.VERSION_CODES.JELLY_BEAN_MR1)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if(session != null)
        {
            if(session.isLoggedIn())
            {
                finish();
            }
        }

        session = new SessionModel(this);
        String languageToLoad =session.getLanguageCode();
        if (!languageToLoad.equals(null)) {
            Locale locale = new Locale(languageToLoad);
            Locale.setDefault(locale);
            Configuration config = new Configuration();
            config.locale = locale;
            getBaseContext().getResources().updateConfiguration(config,
                    getBaseContext().getResources().getDisplayMetrics());


        }
        setContentView(R.layout.activity_home);

        Toolbar toolbar = (Toolbar) findViewById(R.id.language_toolbar);
        setSupportActionBar(toolbar);
        Drawable drawable = ContextCompat.getDrawable(getApplicationContext(),R.drawable.ic_menu_language);
        toolbar.setOverflowIcon(drawable);

        if(session.isLoggedIn())
        {
            Intent i = new Intent(HomeActivity.this,UserHomeActivity.class);
            HomeActivity.this.startActivity(i);
            finish();
        }


        ////calling logout in Dev phase...Meysam
//        new UserController(getApplicationContext()).logOut();

        btn_LogIn = (Button) findViewById(R.id.btn_LogIn);
        btn_AboutUs = (Button) findViewById(R.id.btn_AboutUs);
//        btn_Exit = (Button) findViewById(R.id.btn_Exit);
        btn_Register = (Button) findViewById(R.id.btn_Register);
        btn_Tutorial = (Button) findViewById(R.id.btn_m_Tutorial);

        btn_LogIn.setOnClickListener(this);
        btn_AboutUs.setOnClickListener(this);
//        btn_Exit.setOnClickListener(this);
        btn_Register.setOnClickListener(this);
        btn_Tutorial.setOnClickListener(this);

        //pre for anim
        DisplayMetrics displayMetrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        int width = displayMetrics.widthPixels;
        int height = displayMetrics.heightPixels;
        LinearLayout activity_main= (LinearLayout) findViewById(R.id.activity_main);
        int actW=0;
        int actH=0;
        actW=width;
        actH=height;
        //anim one
        ImageView img_animation = (ImageView) findViewById(R.id.shapeTopHome);
        TranslateAnimation animation = new TranslateAnimation(-400.0f, 30.0f,
                0.0f, 0.0f);          //  new TranslateAnimation(xFrom,xTo, yFrom,yTo)
        animation.setDuration(1500);  // animation duration
        animation.setRepeatCount(0);  // animation repeat count
        animation.setRepeatMode(1);   // repeat animation (left to right, right to left )
        animation.setFillAfter(true);
        img_animation.startAnimation(animation);  // start animation
        //anim two
        ImageView img_animation02 = (ImageView) findViewById(R.id.shapeBottomHome);
        int tmp= (int) (150+(width*0.25));
        TranslateAnimation animation02 = new TranslateAnimation(actW+400.0f, actW-tmp,
                0.0f, 0.0f);          //  new TranslateAnimation(xFrom,xTo, yFrom,yTo)
        animation02.setDuration(1500);  // animation duration
        animation02.setRepeatCount(0);  // animation repeat count
        animation02.setRepeatMode(1);   // repeat animation (left to right, right to left )
        animation02.setFillAfter(true);
        img_animation02.startAnimation(animation02);  // start animation

//        Locale current = getResources().getConfiguration().locale;
//        final SharedPreferences mPrefs = getSharedPreferences("label", 0);
//        session.saveLanguage();
        first_defult_lang = session.getLanguageCode(); //default for exam "en"

        if(Utility.isNetworkAvailable(this)) {
            HomeController hc = new HomeController(getApplicationContext());
            hc.addObserver(this);
            hc.getVersion();


            LanguageController lc = new LanguageController(getApplicationContext());
            lc.addObserver(this);
            lc.index();
        }else{
            Utility.displayToast(getApplicationContext(),getResources().getString(R.string.error_no_connection),Toast.LENGTH_LONG);
        }
    }
    @Override
    protected void onResume() {
        super.onResume();

        if(session.isLoggedIn())
        {
            finish();
        }
    }
    public boolean onOptionsItemSelected(MenuItem item) {
        int position=0;
        for(int i=0;i<languages.size();i++){
            LanguageModel lm=languages.get(i);
            if(lm.getTitle().equals(item.getTitle())){
                position=i;
                break;
            }
        }
        session.saveLanguageCode(languages.get(position).getCode());
        Locale locale = new Locale(languages.get(position).getCode());
        Locale.setDefault(locale);
        Configuration config = new Configuration();
        config.locale = locale;
        getBaseContext().getResources().updateConfiguration(config,
                getBaseContext().getResources().getDisplayMetrics());

        finish();
        startActivity(getIntent());

        return false;

    }

    @Override
    public void onClick(View v) {
        if (v.getId() == btn_LogIn.getId()){
            if(Utility.isNetworkAvailable(this)) {
            Intent i = new Intent(HomeActivity.this, UserLoginActivity.class);
            HomeActivity.this.startActivity(i);
            }else{
                Utility.displayToast(getApplicationContext(),getResources().getString(R.string.error_no_connection),Toast.LENGTH_LONG);
            }
        }
        if (v.getId() == btn_AboutUs.getId()){
            Intent i = new Intent(HomeActivity.this, AboutUsActivity.class);
            HomeActivity.this.startActivity(i);

        }
        if (v.getId() == btn_Register.getId()){
            if(Utility.isNetworkAvailable(this)) {
                Intent i = new Intent(HomeActivity.this, UserRegisterActivity.class);
                HomeActivity.this.startActivity(i);
            }else{
                Utility.displayToast(getApplicationContext(),getResources().getString(R.string.error_no_connection),Toast.LENGTH_LONG);
            }
        }
        if (v.getId() == btn_Tutorial.getId()){
            if(Utility.isNetworkAvailable(this)) {
                String lang = Locale.getDefault().getLanguage();
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://www.attentra.ir/style/main/tutorial/app-"+lang+".pdf"));
                startActivity(browserIntent);
            }else{
                Utility.displayToast(getApplicationContext(),getResources().getString(R.string.error_no_connection),Toast.LENGTH_LONG);
            }
        }
//        if (v.getId() == btn_Exit.getId()){
//            //  - MeysamTrack
////            new UserController(getApplicationContext()).logOut();
//            finish();
//        }
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event)  {
//        if (keyCode == KeyEvent.KEYCODE_BACK ) {
//
//            AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
//
//            alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListener);
//            alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListener);
//            alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
//            alertDialogBuilder;
//
//        }

        return super.onKeyDown(keyCode, event);
    }
    @Override
    public void onBackPressed() {
        if (doubleBackToExitPressedOnce) {
            super.onBackPressed();
            Utility.deleteCache(getApplicationContext());
            return;
        }

        this.doubleBackToExitPressedOnce = true;
        Utility.displayToast(this,  getString(R.string.msg_PressBackAgain), Toast.LENGTH_SHORT);

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
            switch (which){
                case DialogInterface.BUTTON_POSITIVE:
                    //Yes button clicked

                    ///////////////////handeling ads - start//////////////////
//                    int max = 1;
//                    int min = 0;
//                    Random rn = new Random();
//                    float rand = rn.nextInt(max - min + 1) + min;
//
//                    if(rand <= 0.5)
//                    {
//                        //for adad
//                        Adad.showInterstitialAd(cntx);
//                        //////////////////////////////
//                    }
                    ///////////////////handeling ads - end//////////////////
                    //stop tracking service if is running - MeysamTrack
//                    if(Utility.isTrackingServiceRunning())
//                    {
//                        Intent intent = new Intent(getApplicationContext(), TrackingService.class);
//                        getApplicationContext().stopService(intent);
//                    }
                    finish();
                    break;

                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked

                    break;
            }
        }
    };

    @SuppressLint("WrongConstant")
    @Override//meysam 13960209
    public void update(Observable o, Object arg) {
        if(arg instanceof ArrayList)
        {
            if(((ArrayList) arg).get(0).equals(RequestRespondModel.TAG_GET_VERSION_HOME))
            {
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

                    Intent i=new Intent(HomeActivity.this,DownloadActivity.class);
                    i.putExtra("link", ((ArrayList) arg).get(2).toString());
                    i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    HomeActivity.this.startActivity(i);
                    finish();

//                    Utility.displayToast(getApplicationContext(),getResources().getString(R.string.msg_OldVersion),Toast.LENGTH_LONG);

                    //go to download link...
//                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(((ArrayList) arg).get(2).toString()));
//                    startActivity(browserIntent);
//                    Utility.displayToast(getApplicationContext(),getResources().getString(R.string.msg_OldVersion),Toast.LENGTH_LONG);
//                    final Dialog d= new Dialog(HomeActivity.this);
//                    d.requestWindowFeature(Window.FEATURE_NO_TITLE);
//                    d.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
//                    d.setContentView(R.layout.message_dialog);
//                    d.show();
//                    TextView txt= (TextView) d.findViewById(R.id.message_box_dialog);
//                    txt.setText(getResources().getString(R.string.msg_OldVersion));
//                    Button btn= (Button) d.findViewById(R.id.btn_mess_01);
//                    btn.setText(getString(R.string.btn_OK));
//                    btn.setOnClickListener(new View.OnClickListener() {
//                        @Override
//                        public void onClick(View view) {
//                            d.hide();
//                        }
//                    });



                }
                else if(new_ver > current_ver)
                {

                    Utility.displayToast(getApplicationContext(),getResources().getString(R.string.msg_OldVersion),Toast.LENGTH_LONG);
//                    final Dialog d= new Dialog(getApplicationContext());
//                    d.requestWindowFeature(Window.FEATURE_NO_TITLE);
//                    d.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
//                    d.setContentView(R.layout.message_dialog);
//                    d.show();
//                    TextView txt= (TextView) d.findViewById(R.id.message_box_dialog);
//                    txt.setText(getResources().getString(R.string.msg_OldVersion));
//                    Button btn= (Button) d.findViewById(R.id.btn_mess_01);
//                    btn.setText(getString(R.string.btn_OK));
//                    btn.setOnClickListener(new View.OnClickListener() {
//                        @Override
//                        public void onClick(View view) {
//                            d.hide();
//                        }
//                    });
                }
                else
                {
                    //nothing
                }
            }else{
                //list of language
                languages= (ArrayList<LanguageModel>) arg;
                if(languages.size()>0)
                    addItemsOnSpinner_language();
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
            Utility.displayToast(getApplicationContext(),getResources().getString(R.string.msg_ConnectionError),Toast.LENGTH_LONG);
        }
    }
    // add items into spinner dynamically
    private void addItemsOnSpinner_language() {
        Toolbar toolbar = (Toolbar) findViewById(R.id.language_toolbar);
        Menu menu = toolbar.getMenu();
        for(LanguageModel lm:languages){
            menu.add(lm.getTitle());
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater menuInflater = getMenuInflater();
        menuInflater.inflate(R.menu.menu_threedot, menu);

        menu.clear();

        return true;
    }

    @Override
    protected void onPause() {
        super.onPause();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
    }
}
