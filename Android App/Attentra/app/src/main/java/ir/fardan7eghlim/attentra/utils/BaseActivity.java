package ir.fardan7eghlim.attentra.utils;

import android.content.ActivityNotFoundException;
import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.NavigationView;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.DisplayMetrics;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import java.math.BigInteger;
import java.util.Locale;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.models.LogModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.models.UserTypeModel;
import ir.fardan7eghlim.attentra.views.aboutus.AboutUsActivity;
import ir.fardan7eghlim.attentra.views.attendance.AttendanceIndexActivity;
import ir.fardan7eghlim.attentra.views.attendance.AttendanceStoreSelfLocationActivity;
import ir.fardan7eghlim.attentra.views.company.CompanyIndexActivity;
import ir.fardan7eghlim.attentra.views.home.HomeActivity;
import ir.fardan7eghlim.attentra.views.module.ModuleMainActivity;
import ir.fardan7eghlim.attentra.views.payment.PaymentIndexActivity;
import ir.fardan7eghlim.attentra.views.track.TrackStoreActivity;
import ir.fardan7eghlim.attentra.views.user.UserCheckInActivity;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserProfileActivity;

/**
 * Created by Meysam on 3/8/2017.
 */

public class BaseActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    protected SessionModel session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        ///decide language based on setings
        session = new SessionModel(getApplicationContext());
//        String languageToLoad = LanguageModel.getLanguageString(session.getLanguage());
        String languageToLoad =session.getLanguageCode();
        if (!languageToLoad.equals(null)) {
            Locale locale = new Locale(languageToLoad);
            Locale.setDefault(locale);
            Configuration config = new Configuration();
            config.locale = locale;
            getBaseContext().getResources().updateConfiguration(config,
                    getBaseContext().getResources().getDisplayMetrics());

//            Locale myLocale = new Locale(languageToLoad);
//            Resources res = getResources();
//            DisplayMetrics dm = res.getDisplayMetrics();
//            Configuration conf = res.getConfiguration();
//            conf.locale = myLocale;
//            res.updateConfiguration(conf, dm);
        }


    }

    @Override
    protected void onResume() {
        super.onResume();

        ///decide language based on setings
//        String languageToLoad = LanguageModel.getLanguageString(session.getLanguage());
        String languageToLoad = session.getLanguageCode();
        if (!languageToLoad.equals(null)) {
            Locale locale = new Locale(languageToLoad);
            Locale.setDefault(locale);
            Configuration config = new Configuration();
            config.locale = locale;
            getBaseContext().getResources().updateConfiguration(config,
                    getBaseContext().getResources().getDisplayMetrics());
//
//            Locale myLocale = new Locale(languageToLoad);
//            Resources res = getResources();
//            DisplayMetrics dm = res.getDisplayMetrics();
//            Configuration conf = res.getConfiguration();
//            conf.locale = myLocale;
//            res.updateConfiguration(conf, dm);
        }

    }

    @Override
    public void onBackPressed() {
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        if(drawer != null)
        {
            if (drawer.isDrawerOpen(GravityCompat.START)) {
                drawer.closeDrawer(GravityCompat.START);
            } else {
                super.onBackPressed();
            }
        }
        else
        {
            super.onBackPressed();
        }

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
//        MenuInflater menuInflater = getMenuInflater();
//        SessionModel session = new SessionModel(this);
//
//        HashMap hm = session.getUserDetails();
//        if (hm.get("type") != null) {
//            if (new Integer(hm.get("type").toString()) == UserTypeModel.CEO) {
//                menuInflater.inflate(R.menu.menu_ceo, menu);
//            } else if (new Integer(hm.get("type").toString()) == UserTypeModel.EMPLOYEE) {
//                menuInflater.inflate(R.menu.menu_ceo, menu);
//            } else {
//                menuInflater.inflate(R.menu.menu_ceo, menu);
//            }
//
//        }
//        return true;
        return false;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
//         minimizeApp();
//        switch (item.getItemId()) {
//            case R.id.menu_CheckIn:
//                // Single menu item is selected do something
//                // Ex: launching new activity/screen or show alert message
//                Utility.displayToast(this, "CheckIn!!", Toast.LENGTH_SHORT);
//                scan_qr_code();
//                return true;
//            case R.id.menu_Profile:
//                Utility.displayToast(this, "Profile!!", Toast.LENGTH_SHORT);
//                show_profile();
//                return true;
//            case R.id.menu_GPSTrackingStart:
//                Utility.displayToast(this, "GPSTrackingStart!!", Toast.LENGTH_SHORT);
//                gps_tracking();
//                return true;
//            case R.id.menu_Companies:
//                Utility.displayToast(this, "companies!!", Toast.LENGTH_SHORT);
//                index_companies();
//                return true;
//            case R.id.menu_Modules:
//                Utility.displayToast(this, "Modules!!", Toast.LENGTH_SHORT);
//                show_Modules();
//                return true;
//            case R.id.menu_Settings:
//                Utility.displayToast(this, "Settings!!", Toast.LENGTH_SHORT);
//                show_Settings();
//                return true;
//            case R.id.menu_Payments:
//                Utility.displayToast(this, "Payments!!", Toast.LENGTH_SHORT);
//                show_Payments_list();
//                return true;
//            case R.id.menu_LogOut:
//                Utility.displayToast(this, "LogOut!!", Toast.LENGTH_LONG);
//                SessionModel session = new SessionModel(getApplicationContext());
//                session.logoutUser(true);
//                finish();
//                System.gc();
//                android.os.Process.killProcess(android.os.Process.myPid());
//                System.exit(1);
////                System.exit(0);
//            default:
//                return super.onOptionsItemSelected(item);
//    }
        return false;

    }

    public void minimizeApp() {
        Intent startMain = new Intent(Intent.ACTION_MAIN);
        startMain.addCategory(Intent.CATEGORY_HOME);
        startMain.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP
                | Intent.FLAG_ACTIVITY_PREVIOUS_IS_TOP);
        startActivity(startMain);
    }

    private void show_Payments_list() {
        Intent intent = new Intent(getApplicationContext(), PaymentIndexActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
    }

    public void show_Modules() {
        Intent intent = new Intent(getApplicationContext(), ModuleMainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
    }

    public void show_Settings() {
        Intent intent = new Intent(getApplicationContext(), UserProfileActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
    }

    public void show_profile() {
        Intent intent = new Intent(getApplicationContext(), UserHomeActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
    }

    public void show_attendance_list() {

        Intent intent = new Intent(getApplicationContext(), AttendanceIndexActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        intent.putExtra("user_id",session.getCurrentUser().getId().toString());
        intent.putExtra("user_guid",session.getCurrentUser().getGuid());
        startActivity(intent);
    }

    public void scan_qr_code() {
        try {

            //start the scanning activity from the com.google.zxing.client.android.SCAN intent
//            Intent intent = new Intent( "com.google.zxing.client.android.SCAN");
//            intent.putExtra("SCAN_MODE", "PRODUCT_MODE");
//            startActivityForResult(intent, 0);

            Intent intent = new Intent(getApplicationContext(), UserCheckInActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);


        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "No Scanner Found", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();

        }

    }
    public void attendance_location() {
        Utility.displayToast(this, getString(R.string.msg_Experimental), Toast.LENGTH_LONG);
        try {

            Intent intent = new Intent(getApplicationContext(), AttendanceStoreSelfLocationActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);



        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }

    public void index_companies() {
        try {

            Intent intent = new Intent(getApplicationContext(), CompanyIndexActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);



        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }

    public void gps_tracking() {
        try {

            Intent intent = new Intent(getApplicationContext(), TrackStoreActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);


        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }

    public void tutorial() {
        try {

            String lang = Locale.getDefault().getLanguage();
            Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://www.attentra.ir/style/main/tutorial/app-"+lang+".pdf"));
            startActivity(browserIntent);


        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    public void aboutUs() {
        try {

            Intent intent = new Intent(getApplicationContext(), AboutUsActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);


        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }

    public void share() {
        try {

            Intent sharingIntent = new Intent();
            sharingIntent.setAction(Intent.ACTION_SEND);
            sharingIntent.putExtra(android.content.Intent.EXTRA_SUBJECT, getString(R.string.ShareSubject));
            sharingIntent.putExtra(android.content.Intent.EXTRA_TEXT, getString(R.string.ShareBody));
            sharingIntent.setType("text/plain");
            startActivity(Intent.createChooser(sharingIntent, getString(R.string.ShareVia)));


        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }

    public void rate() {
        try {

            String PACKAGE_NAME = getApplicationContext().getPackageName();
            Intent intent = new Intent(Intent.ACTION_EDIT);
            intent.setData(Uri.parse("bazaar://details?id=" + PACKAGE_NAME));
            intent.setPackage("com.farsitel.bazaar");
            startActivity(intent);


        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }
    public void reports() {

        Utility.displayToast(this, getString(R.string.msg_InWebSystem), Toast.LENGTH_LONG);

    }
    public void otherProducts() {
        try {

            //set developer id
            String DEVELOPER_ID = "693725359875";
            Intent intent = new Intent(Intent.ACTION_VIEW);
            intent.setData(Uri.parse("bazaar://collection?slug=by_author&aid=" + DEVELOPER_ID));
            intent.setPackage("com.farsitel.bazaar");
            startActivity(intent);

        } catch (ActivityNotFoundException ex) {

            //on catch, show the download dialog
            Utility.displayToast(this, "error", Toast.LENGTH_LONG);
            LogModel log = new LogModel();
            log.setErrorMessage("message: " + ex.getMessage() + " CallStack: " + ex.getStackTrace());
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(getApplicationContext()).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        if (id == R.id.nav_CheckIn) {
            scan_qr_code();

        } else if (id == R.id.nav_Profile) {
            show_profile();

        } else if (id == R.id.nav_LocationCheckIn) {
            attendance_location();

        } else if (id == R.id.nav_GPSTrackingStart) {
            gps_tracking();

        } else if (id == R.id.nav_Company) {
            index_companies();

        } else if (id == R.id.nav_Modules) {
            show_Modules();


        } else if (id == R.id.nav_AttendanceList) {
            show_attendance_list();


        }  else if (id == R.id.nav_Payments) {
            show_Payments_list();


        }else if (id == R.id.nav_Reports) {
            reports();

        } else if (id == R.id.nav_Settings) {
            show_Settings();


        }else if (id == R.id.nav_tutorial) {
            tutorial();


        }else if (id == R.id.nav_about_us) {
            aboutUs();


        }else if (id == R.id.nav_share) {
            share();


        }else if (id == R.id.nav_rate) {
            rate();


        }else if (id == R.id.nav_other_products) {
            otherProducts();


        }else if (id == R.id.nav_Logout) {
            session.logoutUser(true);
            Intent intent = new Intent(getApplicationContext(), HomeActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();

        }

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    protected void onCreateDrawer()
    {
        //////////////////////for navigation///////////////
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.setDrawerListener(toggle);
        toggle.syncState();
        NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
        navigationView.setBackgroundResource(R.drawable.backrepeat);
        ////////////////////////////
        View hView =  navigationView.getHeaderView(0);
        TextView nav_user_name_and_family = (TextView)hView.findViewById(R.id.nav_hdr_user_name_and_family);

        DatabaseHandler db = new DatabaseHandler(getApplicationContext());
        if(!db.isTableExists(DatabaseHandler.TABLE_NAME_USERS))
        {
            Intent intent = new Intent("home_activity_broadcast");
            // You can also include some extra data.
            intent.putExtra("logout", "true");
            LocalBroadcastManager.getInstance(getApplicationContext()).sendBroadcast(intent);
//            return;
        }
        else
        {
            if(db.getUserDetails() != null)
                if(!db.getUserDetails().getName().equals("null") && !db.getUserDetails().getFamily().equals("null"))
                    nav_user_name_and_family.setText(db.getUserDetails().getName() + " " +db.getUserDetails().getFamily() );
        }
        ///////set navigation header values///////////////////


//        TextView nav_email = (TextView)hView.findViewById(R.id.nav_hdr_email);
//        nav_email.setText(db.getUserDetails().getEmail());

        //show payment in header
        TextView nav_payment = (TextView)hView.findViewById(R.id.nav_hdr_payment);
        nav_payment.setText(getApplicationContext().getString(R.string.chargRemidTitle)+" "+db.getUserDetails().getBalance() + " "+getApplicationContext().getString(R.string.Tooman) );

        ImageView nav_user_image = (ImageView)hView.findViewById(R.id.nav_hdr_user_image);
        nav_user_image.setImageBitmap(db.getUserDetails().getProfilePicture());
        ImageView nav_hdr_bg_image= (ImageView) hView.findViewById(R.id.nav_hdr_bg_image);

        Bitmap b = null;
        if(db.getUserDetails() != null)
            b=db.getUserDetails().getProfilePicture();

        if(b==null){

            UserModel user = null;
            if(db.getUserDetails() != null)
                user=db.getUserDetails();
            if(user == null)
                b=BitmapFactory.decodeResource(BaseActivity.this.getResources(),
                       R.drawable.male2);
            else
                b=BitmapFactory.decodeResource(BaseActivity.this.getResources(),
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
        //////////////////////////////////////////////////////
        Menu nav_Menu = navigationView.getMenu();

        MenuItem mi_nav_Company = (MenuItem) nav_Menu.findItem(R.id.nav_Company);
        MenuItem mi_nav_Payments = (MenuItem) nav_Menu.findItem(R.id.nav_Payments);
        MenuItem mi_nav_Settings = (MenuItem) nav_Menu.findItem(R.id.nav_Settings);
        MenuItem mi_nav_Modules = (MenuItem) nav_Menu.findItem(R.id.nav_Modules);
        MenuItem mi_nav_GPSTrackingStart = (MenuItem) nav_Menu.findItem(R.id.nav_GPSTrackingStart);
        MenuItem mi_nav_CheckIn = (MenuItem) nav_Menu.findItem(R.id.nav_CheckIn);
        MenuItem mi_nav_AttendanceList = (MenuItem) nav_Menu.findItem(R.id.nav_AttendanceList);
        MenuItem mi_nav_Profile = (MenuItem) nav_Menu.findItem(R.id.nav_Profile);

        if(session.getCurrentUser().getUserType().equals(UserTypeModel.EMPLOYEE))
        {
            mi_nav_Company.setVisible(false);
            mi_nav_Payments.setVisible(false);
            mi_nav_Modules.setVisible(false);
            mi_nav_CheckIn.setVisible(false);
            nav_payment.setVisibility(View.INVISIBLE);
        }
        else if(session.getCurrentUser().getUserType().equals(UserTypeModel.MiddleCEO))
        {
//            mi_nav_Company.setVisible(false);
            mi_nav_Payments.setVisible(false);
            mi_nav_Modules.setVisible(false);
            nav_payment.setVisibility(View.INVISIBLE);
        }
        else if(session.getCurrentUser().getUserType().equals(UserTypeModel.CEO))
        {
            mi_nav_GPSTrackingStart.setVisible(false);


        }
        else if(session.getCurrentUser().getUserType().equals(UserTypeModel.Admin))
        {
            //nothing for now....
        }
        else if(session.getCurrentUser().getUserType().equals(UserTypeModel.Device))
        {
            mi_nav_Company.setVisible(false);
            mi_nav_Payments.setVisible(false);
            mi_nav_Modules.setVisible(false);
            mi_nav_Profile.setVisible(false);
            mi_nav_GPSTrackingStart.setVisible(false);
            mi_nav_AttendanceList.setVisible(false);
            mi_nav_Settings.setVisible(false);
            nav_payment.setVisibility(View.INVISIBLE);
        }
        else
        {
            //somethings fishy!!!lets kick user out....
            session.logoutUser(true);
            Intent i = new Intent(BaseActivity.this, HomeActivity.class);
            BaseActivity.this.startActivity(i);
            finish();

        }

    }


}
