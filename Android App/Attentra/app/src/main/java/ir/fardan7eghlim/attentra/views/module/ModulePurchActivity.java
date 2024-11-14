package ir.fardan7eghlim.attentra.views.module;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.support.v7.app.AlertDialog;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyUserModuleController;
import ir.fardan7eghlim.attentra.models.CompanyUserModuleModel;
import ir.fardan7eghlim.attentra.models.ModuleModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class ModulePurchActivity extends BaseActivity implements Observer {
    private ProgressDialog pDialog;
    private ModuleModel module;
    private Spinner spinner;
    private int[] factor_company={1,2,3,4};
    private int[] factor_employee={1,2,5,10,30};
    private int[] factor_point={500,1000,2000,5000,10000};
    private int[] factor_month={1,2,3,6,12};
    private String company_id=null;
    private Integer time=null;
    private Integer limit=null;
    private UserModel user;
    private Context cntx;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_module_purch);
        module=new ModuleModel();
        cntx = this;
        pDialog = new ProgressDialog(cntx);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        DatabaseHandler db = new DatabaseHandler(getApplicationContext());
        user=db.getUserDetails();

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("modul_id") != null) {
                module.setModuleId(new BigInteger(extras.getString("modul_id")));
            }
            if (extras.getString("modul_price") != null) {
                module.setPrice(new Float(extras.getString("modul_price")));
            }
            if (extras.getString("modul_description") != null) {
                module.setDescription(extras.getString("modul_description"));
            }
            if (extras.getString("company_id") != null) {
                company_id=extras.getString("company_id");
            }
        }
        //set description
        final TextView description= (TextView) findViewById(R.id.description_et_amp);
        description.setText(module.getDescription());
        //set price
        final TextView price= (TextView) findViewById(R.id.price_et_amp);
        price.setText(module.getPrice().toString());
        //set spinner
        spinner= (Spinner) findViewById(R.id.time_amp);
        if(ModuleModel.TimeRelatedModuleIds.contains(module.getModuleId())){
            addItemsOnSpinner(getListOF_month());
        }else if(ModuleModel.PersonRelatedModuleIds.contains(module.getModuleId())){
            addItemsOnSpinner(getListOF_employee());
        }else if(ModuleModel.PointRelatedModuleIds.contains(module.getModuleId())){
            addItemsOnSpinner(getListOF_point());
        }else{
            addItemsOnSpinner(getListOF_company());
        }
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parentView, View selectedItemView, int position, long id) {
                Double base_price=new Double(module.getPrice().toString());
                if(ModuleModel.TimeRelatedModuleIds.contains(module.getModuleId())){
                    time=factor_month[position];
                    limit=null;
                    price.setText(Math.round((base_price*factor_month[position]))+"");
                }else if(ModuleModel.PersonRelatedModuleIds.contains(module.getModuleId())){
                    limit=factor_employee[position];
                    time=null;
                    price.setText(Math.round((base_price*factor_employee[position]))+"");
                }else if(ModuleModel.PointRelatedModuleIds.contains(module.getModuleId())){
                    limit=factor_point[position];
                    time=null;
                    price.setText(Math.round((base_price*factor_point[position]))+"");
                }else{
                    limit=factor_company[position];
                    time=null;
                    price.setText(Math.round((base_price*factor_company[position]))+"");
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parentView) {
                // your code here
                price.setText("");
                time=null;
                limit=null;
            }

        });
        Button btn_purch = (Button) findViewById(R.id.btn_module_public);
        btn_purch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(cntx);
            alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListener);
            alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListener);
            alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
            alertDialogBuilder.show();
            }
        });
    }
    public void purch(){

        final TextView price= (TextView) findViewById(R.id.price_et_amp);
        Double price_at_all=new Double(price.getText().toString());
        if(new Double(user.getBalance())<price_at_all){
            Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_not_enough_charge), Toast.LENGTH_LONG);
            return;
        }
        else
        {
            pDialog.show();
            CompanyUserModuleModel cumm=new CompanyUserModuleModel();
            cumm.setModuleId(module.getModuleId());
            if(company_id!=null) cumm.setCompanyId(new BigInteger(company_id));
            if(limit!=null) cumm.setLimitCount(limit);
            String timeTemp=null;
            if(time!=null) timeTemp=time.toString();
            CompanyUserModuleController mc= new CompanyUserModuleController(getApplicationContext());
            mc.addObserver((Observer) this);
            mc.store(cumm,timeTemp);
        }

    }

    public ArrayList<String> getListOF_month(){
        ArrayList<String> temp=new ArrayList<>();
        temp.add(getString(R.string.mdl_one)+" "+getString(R.string.mdl_month));
        temp.add(getString(R.string.mdl_two)+" "+getString(R.string.mdl_month));
        temp.add(getString(R.string.mdl_three)+" "+getString(R.string.mdl_month));
        temp.add(getString(R.string.mdl_six)+" "+getString(R.string.mdl_month));
        temp.add(getString(R.string.mdl_year));
        return temp;
    }
    public ArrayList<String> getListOF_company(){
        ArrayList<String> temp=new ArrayList<>();
        temp.add(getString(R.string.mdl_one)+" "+getString(R.string.mdl_company));
        temp.add(getString(R.string.mdl_two)+" "+getString(R.string.mdl_company));
        temp.add(getString(R.string.mdl_three)+" "+getString(R.string.mdl_company));
        temp.add(getString(R.string.mdl_four)+" "+getString(R.string.mdl_company));
        return temp;
    }
    public ArrayList<String> getListOF_employee(){
        ArrayList<String> temp=new ArrayList<>();
        temp.add(getString(R.string.mdl_one)+" "+getString(R.string.mdl_employee));
        temp.add(getString(R.string.mdl_two)+" "+getString(R.string.mdl_employee));
        temp.add(getString(R.string.mdl_five)+" "+getString(R.string.mdl_employee));
        temp.add(getString(R.string.mdl_ten)+" "+getString(R.string.mdl_employee));
        temp.add(getString(R.string.mdl_thirty)+" "+getString(R.string.mdl_employee));
        return temp;
    }
    public ArrayList<String> getListOF_point(){
        ArrayList<String> temp=new ArrayList<>();
        temp.add(getString(R.string.mdl_5hundred)+" "+getString(R.string.mdl_point));
        temp.add(getString(R.string.mdl_one)+" "+getString(R.string.mdl_thousant)+" "+getString(R.string.mdl_point));
        temp.add(getString(R.string.mdl_two)+" "+getString(R.string.mdl_thousant)+" "+getString(R.string.mdl_point));
        temp.add(getString(R.string.mdl_five)+" "+getString(R.string.mdl_thousant)+" "+getString(R.string.mdl_point));
        temp.add(getString(R.string.mdl_ten)+" "+getString(R.string.mdl_thousant)+" "+getString(R.string.mdl_point));
        return temp;
    }
    // add items into spinner dynamically
    private void addItemsOnSpinner(ArrayList<String> list) {
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(dataAdapter);
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
                    Intent i = new Intent(ModulePurchActivity.this,UserHomeActivity.class);
                    ModulePurchActivity.this.startActivity(i);
                    finish();
                }else{
                    final TextView price= (TextView) findViewById(R.id.price_et_amp);
                    Double price_at_all=new Double(price.getText().toString());
                    DatabaseHandler db = new DatabaseHandler(this);
                    UserModel user=db.getUserDetails();
                    Double last_balance=new Double(user.getBalance());
                    UserModel new_user=user;
                    new_user.setBalance((last_balance-price_at_all)+"");
                    db.editUser(new_user);

                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
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

    DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which) {
                case DialogInterface.BUTTON_POSITIVE:
                    //Yes button clicked
                    purch();
//                    finish();
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked
                    break;
            }
        }
    };
}
