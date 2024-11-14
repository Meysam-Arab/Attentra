package ir.fardan7eghlim.attentra.views.company;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.provider.MediaStore;
import android.os.Bundle;
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
import java.util.Arrays;
import java.util.List;
import java.util.Observable;
import java.util.Observer;
import java.util.TimeZone;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.FileProcessor;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class CompanyEditActivity extends BaseActivity implements Observer{
    private String company_id;
    private String company_guid;
    private String company_name;
    private String pathOfAvatar;
    private String company_timeZone;
    private Bitmap company_avatar;
    private ImageView avatar;
    private ImageView avatar_ch;
    private Bitmap bitmap;
    private EditText et_name;
    private Spinner timeZone;
    private int PICK_IMAGE_REQUEST = 1;
    private int position_timeZone;
    private boolean isTimeZoneChanged=false;
    private CompanyModel company = new CompanyModel();
    private ProgressDialog pDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_company_edit);
        super.onCreateDrawer();

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("company_id") != null && extras.getString("company_guid") != null && extras.getString("company_name") != null && extras.getString("company_timeZone") != null) {
                company_id = extras.getString("company_id");
                company.setCompanyId(new BigInteger(company_id));
                company_guid = extras.getString("company_guid");
                company.setCompanyGuid(company_guid);
                company_name = extras.getString("company_name");
                company.setName(company_name);
                company_timeZone = extras.getString("company_timeZone");
                company.setTimeZone(company_timeZone);
                pathOfAvatar = extras.getString("company_avatar");
            }
        }
        company_avatar=new FileProcessor().loadImageFromStorage(pathOfAvatar,company_guid+".png");
        new FileProcessor().deleteFile(pathOfAvatar,company_guid+".png");

        et_name= (EditText) findViewById(R.id.Name_et_ce);
        et_name.setText(company_name);
        timeZone= (Spinner) findViewById(R.id.timeZone_sp_ce);
        addItemsOnSpinner_timeZone(company_timeZone);
        timeZone.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parentView, View selectedItemView, int position, long id) {
                // your code here
                if(position!=position_timeZone)
                    isTimeZoneChanged=true;
                else
                    isTimeZoneChanged=false;
            }
            @Override
            public void onNothingSelected(AdapterView<?> parentView) {
                // your code here
            }
        });
        timeZone.setSelection(position_timeZone);

        avatar= (ImageView) findViewById(R.id.avatar_iv_ce);
        avatar_ch= (ImageView) findViewById(R.id.avatar_iv_change_ce);
        bitmap=null;
        if(company_avatar==null)
            avatar.setImageResource(R.drawable.company);
        else
            avatar.setImageBitmap(company_avatar);
        //avatar changer
        avatar_ch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showFileChooser();
            }
        });

        //field of contents
        et_name= (EditText) findViewById(R.id.Name_et_ce);

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
    ///show image picker
    private void showFileChooser() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, getString(R.string.Select_Picture)), PICK_IMAGE_REQUEST);
    }
    // add items into spinner dynamically
    public void addItemsOnSpinner_timeZone(String company_timeZone) {
        timeZone = (Spinner) findViewById(R.id.timeZone_sp_ce);
        List<String> list = new ArrayList<String>();
        list= Utility.getTmeZones(getApplicationContext());
        position_timeZone=list.indexOf(company_timeZone);
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        timeZone.setAdapter(dataAdapter);
    }
    //edit functon
    public void editCompany(View v){
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();
        if(bitmap!=null || !et_name.getText().toString().equals(company_name) || isTimeZoneChanged){

            if(bitmap!=null)
                company.setCompanyPicture(bitmap);
            if(!et_name.getText().toString().equals(company_name))
                company.setName(et_name.getText().toString());
            if(isTimeZoneChanged)
                company.setTimeZone(timeZone.getSelectedItem().toString());
            CompanyController cc = new CompanyController(getApplicationContext());
            cc.addObserver((Observer) this);
            cc.edit(company);
        }else{
            Utility.displayToast(getApplicationContext(),getString(R.string.error_no_change_found), Toast.LENGTH_LONG);
        }

    }

    //edit functon
    public void editZone(View v){

            Utility.displayToast(getApplicationContext(),getString(R.string.msg_InWebSystemForNow), Toast.LENGTH_LONG);
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
                    Utility.displayToast(getApplicationContext(),getString(R.string.error_update_fail), Toast.LENGTH_LONG);
                }
                else{
                    CompanyIndexActivity.cia.finish();
                    Intent i = new Intent(CompanyEditActivity.this,CompanyIndexActivity.class);
                    CompanyEditActivity.this.startActivity(i);
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
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
