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
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.Toast;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;


public class CompanyAddActivity extends BaseActivity  implements Observer{


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
    private SessionModel session;
    private EditText et_company_name;
    private CompanyModel company = new CompanyModel();
    private ProgressBar spinner;
    private ProgressDialog pDialog;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_company_add);
        super.onCreateDrawer();

        spinner = (ProgressBar)findViewById(R.id.pb_company_store);


        et_company_name = (EditText) findViewById(R.id.et_company_name_rc);
        et_company_name.setText(company_name);
        timeZone = (Spinner) findViewById(R.id.timeZone_sp_rc);
        addItemsOnSpinner_timeZone("Asia/Tehran");
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

        avatar = (ImageView) findViewById(R.id.iv_cmp_img_rc);
        avatar_ch = (ImageView) findViewById(R.id.iv_cmp_img_change_rc);
        bitmap = null;
        if(company_avatar==null)
        {
            avatar.setImageResource(R.drawable.company);
        }
        else{
            avatar.setImageBitmap(company_avatar);
        }

        //avatar changer
        avatar_ch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showFileChooser();
            }
        });


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
    private void addItemsOnSpinner_timeZone(String company_timeZone) {
        timeZone = (Spinner) findViewById(R.id.timeZone_sp_rc);
        List<String> list = new ArrayList<String>();
        list = Utility.getTmeZones(getApplicationContext());
        position_timeZone = list.indexOf(company_timeZone);
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        timeZone.setAdapter(dataAdapter);
    }

    //
    public void registerCompany(View view){
        // Progress dialog
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();
        spinner.setVisibility(View.VISIBLE);

        if(bitmap !=null)
            company.setCompanyPicture(bitmap);
        if(et_company_name.getText() != null)
            company.setName(et_company_name.getText().toString());
        company.setTimeZone(timeZone.getSelectedItem().toString());
        CompanyController cc = new CompanyController(getApplicationContext());
        cc.addObserver((Observer) this);
        cc.register(company);
    }

    @Override
    public void update(Observable o, Object arg) {
        pDialog.dismiss();
        spinner.setVisibility(View.GONE);

        if(arg != null)
        {
            if (arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                }
                else{
                    CompanyIndexActivity.cia.finish();
                    Intent i = new Intent(CompanyAddActivity.this,CompanyIndexActivity.class);
                    CompanyAddActivity.this.startActivity(i);
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    finish();
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
}
