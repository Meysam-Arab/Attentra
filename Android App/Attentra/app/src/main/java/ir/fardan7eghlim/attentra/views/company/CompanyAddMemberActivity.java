package ir.fardan7eghlim.attentra.views.company;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.provider.MediaStore;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;

import java.io.IOException;
import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.UserController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.models.UserTypeModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class CompanyAddMemberActivity extends BaseActivity implements  Observer {
    private Spinner Gender;
    private Spinner userType;
    private ImageView avatar;
    private ImageView avatar_ch;
    private int PICK_IMAGE_REQUEST = 1;
    private Bitmap bitmap;
    private EditText et_name;
    private EditText et_family;
    private EditText et_user_name;
    private EditText et_code;
    private EditText et_password;
    private EditText et_password2;
    private EditText et_email;
    private Button sumit;
    private String company_id;
    private ProgressDialog pDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_company_add_member);
        super.onCreateDrawer();


        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("company_id") != null) {
                company_id = extras.getString("company_id");
            }
        }

        // Progress dialog
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);

        addItemsOnSpinner_gender();
        addItemsOnSpinner_userType();

        avatar= (ImageView) findViewById(R.id.avatar_iv_cam);
        avatar_ch= (ImageView) findViewById(R.id.avatar_iv_change_cam);
        bitmap=null;
        avatar.setImageResource(R.drawable.male);
        //avatar changer
        avatar_ch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showFileChooser();
            }
        });

        Gender.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parentView, View selectedItemView, int position, long id) {
                // your code here
                if(bitmap==null){
                    if(Gender.getSelectedItemId()==0){
                        avatar.setImageResource(R.drawable.male);
                    }else{
                        avatar.setImageResource(R.drawable.female);
                    }
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parentView) {
                // your code here
            }
        });

        //field of contents
        et_name= (EditText) findViewById(R.id.Name_et_cam);
        et_family= (EditText) findViewById(R.id.Family_et_cam);
        et_user_name= (EditText) findViewById(R.id.userName_et_cam);
        et_email= (EditText) findViewById(R.id.Email_et_cam);
        et_password= (EditText) findViewById(R.id.Password_et_cam);
        et_password2= (EditText) findViewById(R.id.Password2_et_cam);
        et_code= (EditText) findViewById(R.id.Code_et_cam);
        sumit= (Button) findViewById(R.id.btn_AddCompanyMember_cam);

    }
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == PICK_IMAGE_REQUEST && resultCode == RESULT_OK && data != null && data.getData() != null) {
            Uri filePath = data.getData();
            try {
                //Getting the Bitmap from Gallery
                bitmap = MediaStore.Images.Media.getBitmap(getContentResolver(), filePath);
                //Setting the Bitmap to ImageView
                avatar.setImageBitmap(bitmap);
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }
    // add items into spinner dynamically
    public void addItemsOnSpinner_gender() {
        Gender = (Spinner) findViewById(R.id.Gender_sp_cam);
        List<String> list = new ArrayList<String>();
        list.add(getString(R.string.male));
        list.add(getString(R.string.female));
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        Gender.setAdapter(dataAdapter);
    }
    public void addItemsOnSpinner_userType() {
        userType = (Spinner) findViewById(R.id.userType_sp_cam);
        List<String> list = new ArrayList<String>();
        list.add(getString(R.string.Employee));
        list.add(getString(R.string.MiddleCEO));
        list.add(getString(R.string.Device));
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        userType.setAdapter(dataAdapter);
    }
    ///show image picker
    private void showFileChooser() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, getString(R.string.Select_Picture)), PICK_IMAGE_REQUEST);
    }

    public void addMember(View v){
        if(!et_password.getText().toString().equals(et_password2.getText().toString())){
            Utility.displayToast(getApplicationContext(),getString(R.string.error_incorrect_password), Toast.LENGTH_LONG);
            return;
        }
        /////////////////////////////////////////////////


        // Check for a valid password, if the user entered one.
        if (TextUtils.isEmpty(et_password.getText().toString()) || !Utility.isPasswordValid(et_password.getText().toString())) {
            et_password.setError(getString(R.string.error_invalid_password));
            Utility.displayToast(getApplicationContext(),getString(R.string.error_invalid_password), Toast.LENGTH_LONG);
            return;
        }
        //check if didnt fill email
        if (TextUtils.isEmpty(et_email.getText().toString())) {
            et_email.setError(getString(R.string.error_invalid_email));
            Utility.displayToast(getApplicationContext(),getString(R.string.error_invalid_email), Toast.LENGTH_LONG);
            return;
        }
        if (!Utility.isValidEmail(et_email.getText().toString())) {
            et_email.setError(getString(R.string.error_invalid_email));
            Utility.displayToast(getApplicationContext(),getString(R.string.error_invalid_email), Toast.LENGTH_LONG);
            return;
        }


        // Check for a valid email address.
        if (TextUtils.isEmpty(et_user_name.getText().toString()) || et_user_name.getText().toString().length() < 3) {
            Utility.displayToast(getApplicationContext(),getString(R.string.error_user_name_short), Toast.LENGTH_LONG);
            return;
        }

        /////////////////////////////////////////////////

        UserModel user=new UserModel();
//        user.setUserType((int) (userType.getSelectedItemId()+1));
        user.setUserType(UserTypeModel.convertStringToType(getApplicationContext(),userType.getSelectedItem().toString()));
        user.setName(et_name.getText().toString());
        user.setFamily(et_family.getText().toString());
        user.setEmail(et_email.getText().toString());
        user.setUserName(et_user_name.getText().toString());
        user.setPassword(et_password.getText().toString());
        if(et_code.getText() != null)
            user.setCode(et_code.getText().toString());
        user.setGender((String) Gender.getSelectedItem());
        if(bitmap!=null) user.setProfilePicture(bitmap);

        CompanyModel company= new CompanyModel();
        company.setCompanyId(new BigInteger(company_id));

        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();

        UserController uc = new UserController(getApplication());
        uc.addObserver((Observer) this);
        uc.addCompanyMember(user,company);

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
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                }
                else{
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    Intent i = new Intent(getApplicationContext(),CompanyIndexActivity.class);
                    CompanyAddMemberActivity.this.startActivity(i);
                    finish();
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
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
        }
    }
}
