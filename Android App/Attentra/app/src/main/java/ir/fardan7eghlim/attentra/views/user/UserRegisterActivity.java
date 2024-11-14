package ir.fardan7eghlim.attentra.views.user;

import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.text.Html;
import android.text.TextUtils;
import android.text.method.LinkMovementMethod;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CountryController;
import ir.fardan7eghlim.attentra.controllers.UserController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.CountryModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.company.CompanyIndexActivity;

public class UserRegisterActivity extends BaseActivity implements Observer {


    // UI references.
    private EditText et_userName;
    private EditText et_password;
    private EditText et_repPassword;
    private EditText et_Email;
    private CheckBox chk_terms;

    private Context cntx;
    private ProgressDialog pDialog;
    private SessionModel session;
    private Spinner country;
    private ArrayList<CountryModel> countries;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_register);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        cntx=getApplication();

        // Progress dialog
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();

        //fetching list of counry from server
        countries=new ArrayList<CountryModel>();
        CountryController cc=new CountryController(getApplicationContext());
        cc.addObserver((Observer) this);
        cc.index();

        chk_terms = (CheckBox) findViewById(R.id.chk_terms_of_use);

        TextView tv_term_of_use = (TextView) findViewById(R.id.tv_term_of_use);
        String terms = "<a href='http://www.attentra.ir/license'>"+getResources().getString(R.string.msg_term_of_use)+"</a>";
        tv_term_of_use.setText(Html.fromHtml(terms));
        tv_term_of_use.setMovementMethod(LinkMovementMethod.getInstance());
        tv_term_of_use.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                TermsOfService();
            }
        });


        Button btn_register = (Button) findViewById(R.id.btn_Register);
        btn_register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                registerUser();
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
                if(Boolean.parseBoolean(arg.toString()) == true )
                {
                    Intent i = new Intent(UserRegisterActivity.this, UserLoginActivity.class);
                    UserRegisterActivity.this.startActivity(i);
                    Utility.displayToast(getApplicationContext(),getString(R.string.dlg_OperationSuccess), Toast.LENGTH_LONG);
                }
                else
                {
                    Utility.displayToast(getApplicationContext(),getString(R.string.dlg_OperationFail), Toast.LENGTH_LONG);
                }
            }
            else if(arg instanceof ArrayList)
            {
                countries= (ArrayList<CountryModel>) arg;
                addItemsOnSpinner_country();

            }else if(arg instanceof Integer)
            {
              Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
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
    public void addItemsOnSpinner_country() {
        country = (Spinner) findViewById(R.id.countries_sp_ur);
        List<String> list = new ArrayList<String>();
        for(CountryModel cm:countries){
            list.add(cm.getName());
        }
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        country.setAdapter(dataAdapter);
        //select iran
//        country.setSelection(list.indexOf("iran"));
    }

    public void registerUser()
    {
        et_userName = (EditText) findViewById(R.id.et_UserName);
        et_password = (EditText) findViewById(R.id.et_Password);
        et_repPassword = (EditText) findViewById(R.id.et_RepPassword);
        et_Email= (EditText) findViewById(R.id.et_Email);

        // Reset errors.
        et_userName.setError(null);
        et_password.setError(null);
        et_repPassword.setError(null);
        et_Email.setError(null);


        boolean cancel = false;
        View focusView = null;

        // Store values at the time of the login attempt.
        String userName = et_userName.getText().toString();
        String password = et_password.getText().toString();
        String rePassword = et_repPassword.getText().toString();
        String email = et_Email.getText().toString();
        if(!chk_terms.isChecked()){
            cancel = true;
            chk_terms.setError(getString(R.string.error_term_of_use));
            focusView = chk_terms;
        }
        // Check for a valid password, if the user entered one.
        if (TextUtils.isEmpty(password) || !Utility.isPasswordValid(password)) {
            et_password.setError(getString(R.string.error_invalid_password));
            focusView = et_password;
            cancel = true;
        }
        //check if didnt fill email
        if (TextUtils.isEmpty(email)) {
            et_password.setError(getString(R.string.error_defective_information));
            focusView = et_Email;
            cancel = true;
        }
        if (!Utility.isValidEmail(email)) {
            et_Email.setError(getString(R.string.error_invalid_email));
            focusView = et_Email;
            cancel = true;
        }

        // Check for a valid reapeat password, if the user entered one.
        if (!password.equals(rePassword)) {
            et_repPassword.setError(getString(R.string.error_invalid_password));
            focusView = et_repPassword;
            cancel = true;
        }

        // Check for a valid email address.
        if (TextUtils.isEmpty(userName) || userName.length() < 3) {
            et_userName.setError(getString(R.string.error_field_required));
            focusView = et_userName;
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
            user.setEmail(email);

            //get counrty id
            String choiced_country=country.getSelectedItem().toString();
            for(CountryModel cm:countries){
                if(cm.getName().equals(choiced_country)){
                    user.setCountryId(cm.getCountryId());
                    break;
                }
            }

            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.

            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
            pDialog.setMessage(getString(R.string.dlg_Wait));
            pDialog.show();


            UserController uc = new UserController(cntx);
            uc.addObserver((Observer) this);
            uc.register(user);
        }


    }

    public void TermsOfService()
    {
//        Utility.displayToast(getApplicationContext(),"testttt",Toast.LENGTH_LONG);
    }
}
