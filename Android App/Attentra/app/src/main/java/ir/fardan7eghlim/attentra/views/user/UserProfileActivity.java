package ir.fardan7eghlim.attentra.views.user;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.provider.MediaStore;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
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
import ir.fardan7eghlim.attentra.controllers.CountryController;
import ir.fardan7eghlim.attentra.controllers.LanguageController;
import ir.fardan7eghlim.attentra.controllers.UserController;
import ir.fardan7eghlim.attentra.models.CountryModel;
import ir.fardan7eghlim.attentra.models.LanguageModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;

public class UserProfileActivity extends BaseActivity implements Observer{
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
    private EditText et_email;
    private ProgressDialog pDialog;
    private UserModel user=new UserModel();
    private UserModel u=new UserModel();
    private boolean avatarChanged=false;
    private Spinner country;
    private ArrayList<CountryModel> countries;
    private Spinner language;
    private ArrayList<LanguageModel> languages;
    private DatabaseHandler db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_profile);
        super.onCreateDrawer();

        // Progress dialog
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();

        db = new DatabaseHandler(getApplicationContext());
        user=db.getUserDetails();

        addItemsOnSpinner_gender();
        Gender.setSelection(new Integer(user.getGender()));
        addItemsOnSpinner_userType(user.getUserType());
        userType.setSelection(user.getUserType()-1);
        userType.setEnabled(false);

        avatar= (ImageView) findViewById(R.id.avatar_iv_up);
        avatar_ch= (ImageView) findViewById(R.id.avatar_iv_change_up);

        bitmap=user.getProfilePicture();
        if(bitmap==null)
            avatar.setImageResource(R.drawable.male);
        else
            avatar.setImageBitmap(bitmap);
//        avatar.getLayoutParams().width = 200;
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
        et_name= (EditText) findViewById(R.id.Name_et_up);
        if(!user.getName().equals("null"))
            et_name.setText(user.getName());
        et_family= (EditText) findViewById(R.id.Family_et_up);
        if(!user.getFamily().equals("null"))
            et_family.setText(user.getFamily());
        et_user_name= (EditText) findViewById(R.id.userName_et_up);
        et_user_name.setEnabled(false);
        et_user_name.setText(user.getUserName());
        et_email= (EditText) findViewById(R.id.Email_et_up);
        et_email.setText(user.getEmail());
        et_code= (EditText) findViewById(R.id.Code_et_up);
        if(!user.getCode().equals("null"))
            et_code.setText(user.getCode());

        //fetching list of counry from server
        countries=new ArrayList<CountryModel>();
        CountryController cc=new CountryController(getApplicationContext());
        cc.addObserver((Observer) this);
        cc.index();
        //fetching list of language from server
        languages=new ArrayList<LanguageModel>();
        LanguageController lc=new LanguageController(getApplicationContext());
        lc.addObserver((Observer) this);
        lc.index();
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
                avatarChanged=true;
                avatar.setImageBitmap(bitmap);
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }
    // add items into spinner dynamically
    public void addItemsOnSpinner_gender() {
        Gender = (Spinner) findViewById(R.id.Gender_sp_up);
        List<String> list = new ArrayList<String>();
        list.add(getString(R.string.male));
        list.add(getString(R.string.female));
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        Gender.setAdapter(dataAdapter);
    }
    public void addItemsOnSpinner_userType(Integer userType) {
        this.userType = (Spinner) findViewById(R.id.userType_sp_up);
        List<String> list = new ArrayList<String>();
        list.add(getString(R.string.CEO));
        list.add(getString(R.string.MiddleCEO));
        list.add(getString(R.string.Employee));
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        this.userType.setAdapter(dataAdapter);
    }
    ///show image picker
    private void showFileChooser() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, getString(R.string.Select_Picture)), PICK_IMAGE_REQUEST);
    }
    public void editUser(View v){
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();
        //language
        SessionModel session=new SessionModel(getApplicationContext());
        for(LanguageModel lm:languages){
            if(lm.getTitle().equals(language.getSelectedItem())){
                session.saveLanguageCode(lm.getCode());
                Utility.deleteCache(getApplicationContext());
                break;
            }
        }
        //user detail
        u.setId(user.getId());
        u.setGuid(user.getGuid());
        u.setUserType((int) (userType.getSelectedItemId()+1));
        u.setName(et_name.getText().toString());
        u.setFamily(et_family.getText().toString());
        u.setEmail(et_email.getText().toString());
        u.setUserName(et_user_name.getText().toString());
        u.setCode(et_code.getText().toString());
        u.setGender((String) Gender.getSelectedItem());
        //get counrty id
        String choiced_country=country.getSelectedItem().toString();
        for(CountryModel cm:countries){
            if(cm.getName().equals(choiced_country)){
                u.setCountryId(cm.getCountryId());
                break;
            }
        }
        if(avatarChanged) u.setProfilePicture(bitmap);

        UserController uc = new UserController(getApplication());
        uc.addObserver((Observer) this);
        uc.edit(u);
    }
    public void changePassActivity(View view){
        Intent i = new Intent(UserProfileActivity.this, UserChangePasswordActivity.class);
        i.putExtra("user_id", user.getId().toString());
        i.putExtra("user_guid", user.getGuid());
        UserProfileActivity.this.startActivity(i);
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
                    db.editUser(u);
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    Intent intent = new Intent(UserProfileActivity.this, UserHomeActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                    startActivity(intent);
                }
            }else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).get(0) instanceof CountryModel){
                    countries= (ArrayList<CountryModel>) arg;
                    addItemsOnSpinner_country();
                }
                if(((ArrayList) arg).get(0) instanceof LanguageModel){
                    languages= (ArrayList<LanguageModel>) arg;
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
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
        }
    }
    public void addItemsOnSpinner_country() {
        country = (Spinner) findViewById(R.id.countries_sp_up);
        List<String> list = new ArrayList<String>();
        for(CountryModel cm:countries){
            list.add(cm.getName());
        }
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        country.setAdapter(dataAdapter);
        //select iran
        country.setSelection(list.indexOf(getCountry(user.getCountryId())));
    }
    public void addItemsOnSpinner_language() {
        language = (Spinner) findViewById(R.id.language_sp_up);
        List<String> list = new ArrayList<String>();
        SessionModel session=new SessionModel(getApplicationContext());
        String current_lang="";
        for(LanguageModel lm:languages){
            list.add(lm.getTitle());
            if(lm.getCode().equals(session.getLanguageCode())){
                current_lang=lm.getTitle();
            }
        }
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        language.setAdapter(dataAdapter);
        //select current language
        language.setSelection(list.indexOf(current_lang));
    }
    public String getCountry(BigInteger id){
        String temp="";
        for(CountryModel cm:countries){
            if(cm.getCountryId().equals(id)){
                temp=cm.getName();
                break;
            }
        }
        return temp;
    }
    public String getLanguage(BigInteger id){
        String temp="";
        for(LanguageModel lm:languages){
            if(lm.getLanguageId().equals(id)){
                temp=lm.getTitle();
                break;
            }
        }
        return temp;
    }
}
