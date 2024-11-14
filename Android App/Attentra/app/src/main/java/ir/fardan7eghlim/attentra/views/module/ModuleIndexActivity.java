package ir.fardan7eghlim.attentra.views.module;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.support.design.widget.NavigationView;
import android.os.Bundle;
import android.view.View;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.ModuleController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.ModuleModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class ModuleIndexActivity extends BaseActivity implements Observer {
    public static Activity mia;
    private CustomAdapterList CAL;
    private String company_id=null;
    private String company_guid=null;
    private ProgressDialog pDialog;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_module_index);
        super.onCreateDrawer();


        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("company_id") != null && extras.getString("company_guid") != null) {
                company_id = extras.getString("company_id");
                company_guid = extras.getString("company_guid");
            }
        }
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();
        if(company_id==null){
            ModuleController mc= new ModuleController(getApplicationContext());
            mc.addObserver((Observer) this);
            mc.userIndex();
        }else{
            ModuleController mc= new ModuleController(getApplicationContext());
            mc.addObserver((Observer) this);
            CompanyModel cm=new CompanyModel();
            cm.setCompanyId(new BigInteger(company_id));
            cm.setCompanyGuid(company_guid);
            mc.companyIndex(cm);
        }
    }

    @Override
    public void onResume(){
        super.onResume();
        // put your code here...

        DatabaseHandler db = new DatabaseHandler(this);
        NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        View hView =  navigationView.getHeaderView(0);
        TextView nav_payment = (TextView)hView.findViewById(R.id.nav_hdr_payment);
        nav_payment.setText(getApplicationContext().getString(R.string.chargRemidTitle)+" "+db.getUserDetails().getBalance() + " "+getApplicationContext().getString(R.string.Tooman) );

    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

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
                    Intent i = new Intent(ModuleIndexActivity.this,UserHomeActivity.class);
                    ModuleIndexActivity.this.startActivity(i);
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                List<ModuleModel> modules= (List<ModuleModel>) arg;
                fillList(modules);

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
    //fill list of Module
    private void fillList(List<ModuleModel> modules) {
        //make list
        ListView lv = (ListView) findViewById(R.id.lsw_Module_list);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(modules),company_id==null?RequestRespondModel.TAG_INDEX_USER_MODULE:RequestRespondModel.TAG_INDEX_COMPANY_MODULE,company_id);
        lv.setAdapter(CAL);
        lv.invalidateViews();
    }
}
