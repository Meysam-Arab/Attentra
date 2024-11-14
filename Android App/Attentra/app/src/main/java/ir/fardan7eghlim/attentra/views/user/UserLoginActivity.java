package ir.fardan7eghlim.attentra.views.user;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.Window;
import android.view.inputmethod.EditorInfo;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.Locale;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.UserController;
import ir.fardan7eghlim.attentra.models.LanguageModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.mission.MissionAddActivity;

/**
 * A login screen that offers login via email/password.
 */
public class UserLoginActivity extends AppCompatActivity implements Observer, View.OnClickListener, TextView.OnEditorActionListener {

    /**
     * Keep track of the login task to ensure we can cancel it if requested.
     */
//    private UserLoginTask mAuthTask = null;

    // UI references.
    private AutoCompleteTextView mUserNameView;
    private EditText mPasswordView;
    private View mProgressView;
    private View mLoginFormView;

    private Context cntx;
    private ProgressDialog pDialog;
    private SessionModel session;

    private UserController mUC = null;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_login);

        // Progress dialog
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);

        // SessionModel manager
        session = new SessionModel(getApplicationContext());
        String languageToLoad =session.getLanguageCode();
        if (!languageToLoad.equals(null)) {
            Locale locale = new Locale(languageToLoad);
            Locale.setDefault(locale);
            Configuration config = new Configuration();
            config.locale = locale;
            getBaseContext().getResources().updateConfiguration(config,
                    getBaseContext().getResources().getDisplayMetrics());
        }

        // Check if user is already logged in or not
        if (session.isLoggedIn()) {
//            Log.d("meysam","user is logged in - return to homeactivity");
            // UserModel is already logged in. Take him to main activity
            Intent intent = new Intent(UserLoginActivity.this, UserHomeActivity.class);
            startActivity(intent);
            finish();
        }


        // Set up the login form.
        mUserNameView = (AutoCompleteTextView) findViewById(R.id.actv_user_name);
//        populateAutoComplete();
        mUserNameView.requestFocus();

        mPasswordView = (EditText) findViewById(R.id.et_password);
        mPasswordView.setOnEditorActionListener(this);

        Button mSignInButton = (Button) findViewById(R.id.btn_sign_in);
        mSignInButton.setOnClickListener(this);

        mLoginFormView = findViewById(R.id.frm_login);
        mProgressView = findViewById(R.id.pb_login);

        cntx = this;
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);
        if (session.isLoggedIn()) {
            finish();
        }
//        Utility.displayToast(getApplicationContext(),"in onNewIntent",Toast.LENGTH_LONG);

    }

    @Override
    public void onResume(){
        super.onResume();
        // put your code here...
//        Utility.displayToast(getApplicationContext(),"in onesume",Toast.LENGTH_LONG);
        if (session.isLoggedIn()) {
            finish();
        }
    }
    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    private void attemptLogin()
    {
//        if (mAuthTask != null)
//        {
//            return;
//        }

        // Reset errors.
        mUserNameView.setError(null);
        mPasswordView.setError(null);

        // Store values at the time of the login attempt.
        String userName = mUserNameView.getText().toString();
        String password = mPasswordView.getText().toString();

        boolean cancel = false;
        View focusView = null;

        // Check for a valid password, if the user entered one.
        if (TextUtils.isEmpty(password)) {
            mPasswordView.setError(getString(R.string.error_invalid_password));
            focusView = mPasswordView;
            cancel = true;
        }

        // Check for a valid email address.
        if (TextUtils.isEmpty(userName)) {
            mUserNameView.setError(getString(R.string.error_field_required));
            focusView = mUserNameView;
            cancel = true;
        }

        if (cancel)
        {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        }
        else
        {
            UserModel user = new UserModel();
                user.setUserName(userName);
                user.setPassword(password);
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.
            showProgress(true);
            UserController uc = new UserController(cntx);
                mUC=uc;
                uc.addObserver((Observer) this);

                uc.login(user,cntx);
//            mAuthTask = new UserLoginTask(userName, password);
//            mAuthTask.execute((Void) null);
        }
    }

    /**
     * Shows the progress UI and hides the login form.
     */
//    @TargetApi(Build.VERSION_CODES.HONEYCOMB_MR2)
    private void showProgress(final boolean show)
    {
        // On Honeycomb MR2 we have the ViewPropertyAnimator APIs, which allow
        // for very easy animations. If available, use these APIs to fade-in
        // the progress spinner.
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB_MR2) {
            int shortAnimTime = getResources().getInteger(android.R.integer.config_shortAnimTime);

            mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
            mLoginFormView.animate().setDuration(shortAnimTime).alpha(
                    show ? 0 : 1).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
                }
            });

            mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
            mProgressView.animate().setDuration(shortAnimTime).alpha(
                    show ? 1 : 0).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
                }
            });
        }
        else
        {
            // The ViewPropertyAnimator APIs are not available, so simply show
            // and hide the relevant UI components.
            mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
            mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
        }
    }

    @Override
    public void onClick(View v) {
        if (v.getId() == R.id.btn_sign_in || v.getId() == EditorInfo.IME_NULL) {

            attemptLogin();

        }

    }

    public void forgetPassword(View view){
        Intent i = new Intent(UserLoginActivity.this, UserForgetPasswordActivity.class);
        UserLoginActivity.this.startActivity(i);
    }

    @Override
    public boolean onEditorAction(TextView v, int actionId, KeyEvent event) {
        if (v.getId() == R.id.et_password || v.getId() == EditorInfo.IME_NULL) {

            attemptLogin();
        }
        return true;
    }

    ////observer update when obcervable call it
    @Override
    public void update(Observable o, Object arg) {

//        Log.d("meysam",(arg==null?"null":arg.toString()));
//        mAuthTask = null;
        showProgress(false);
        pDialog.hide();

        if(arg != null) {
            if (arg instanceof Boolean) {
                if(arg.equals(true))
                {
                    if(new SessionModel(getApplicationContext()).isLoggedIn())
                    {
                        Intent i = new Intent(UserLoginActivity.this, UserHomeActivity.class);
                        UserLoginActivity.this.startActivity(i);
                    }
                    else
                    {
                        Utility.displayToast(getApplicationContext(),getString(R.string.dlg_OperationFail), Toast.LENGTH_LONG);
                    }
                }
                else
                {
                    Utility.displayToast(getApplicationContext(),getString(R.string.dlg_OperationFail), Toast.LENGTH_LONG);
                }



            }
            if (arg instanceof Integer) {

//                Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                final Dialog d= new Dialog(UserLoginActivity.this);
                d.requestWindowFeature(Window.FEATURE_NO_TITLE);
                d.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
                d.setContentView(R.layout.message_dialog);
                d.show();
                TextView txt= (TextView) d.findViewById(R.id.message_box_dialog);
                txt.setText(new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())));
                Button btn= (Button) d.findViewById(R.id.btn_mess_01);
                btn.setText(getString(R.string.btn_OK));
                btn.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        d.hide();
                    }
                });

            }
            if (arg instanceof ArrayList) {

                    if (((ArrayList) arg).get(0).equals(RequestRespondModel.TAG_LOGIN_USER)) {
                        if((Boolean)(((ArrayList) arg).get(1)) == true)
                        {
                            final Dialog d= new Dialog(UserLoginActivity.this);
                            d.requestWindowFeature(Window.FEATURE_NO_TITLE);
                            d.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
                            d.setContentView(R.layout.message_dialog);
                            d.show();
                            TextView txt= (TextView) d.findViewById(R.id.message_box_dialog);
                            txt.setText(getString(R.string.msg_PhoneCodeRegisterOk));
                            Button btn= (Button) d.findViewById(R.id.btn_mess_01);
                            btn.setText(getString(R.string.btn_OK));
                            btn.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View view) {
                                    d.hide();
                                    Intent i = new Intent(UserLoginActivity.this, UserHomeActivity.class);
                                    UserLoginActivity.this.startActivity(i);
                                }
                            });
//                            Utility.displayToast(getApplicationContext(),getString(R.string.msg_PhoneCodeRegisterOk), Toast.LENGTH_LONG);
                        }
                        else
                        {
//                            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                            Intent i = new Intent(UserLoginActivity.this, UserHomeActivity.class);
                            UserLoginActivity.this.startActivity(i);
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
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
        }

    }

}

