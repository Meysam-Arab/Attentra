package ir.fardan7eghlim.attentra.views.module;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.support.design.widget.NavigationView;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class ModuleMainActivity extends BaseActivity implements Observer {

    private Context context=this;
    private CustomAdapterList CAL;
    private ProgressDialog pDialog;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_module_main);
        super.onCreateDrawer();


        Button typical_modules= (Button) findViewById(R.id.btn_module_public);
        typical_modules.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(context, ModuleIndexActivity.class);
                context.startActivity(i);
            }
        });
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();
        //get list of companies of user
        CompanyModel company = new CompanyModel();
        CompanyController cc = new CompanyController(getApplicationContext());
        cc.addObserver((Observer) this);
        cc.index(company);
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
                    Intent i = new Intent(context,UserHomeActivity.class);
                    context.startActivity(i);
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                List<CompanyModel> companies= (List<CompanyModel>) arg;
                fillList(companies);
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
    //fill list of company
    private void fillList(List<CompanyModel> companies) {
        LinearLayout module_main_list_0f_company= (LinearLayout) findViewById(R.id.module_main_list_0f_Company);
        for(final CompanyModel cm:companies){
            Button button = new Button(context);
            button.setBackgroundResource(R.drawable.button_02);
            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
                    LinearLayout.LayoutParams.MATCH_PARENT,
                    LinearLayout.LayoutParams.WRAP_CONTENT
            );
            float d = context.getResources().getDisplayMetrics().density;
            int margin = (int)(30 * d);
            params.setMargins(margin, 5, margin, 5);
            button.setLayoutParams(params);
            button.setText(cm.getName());
            button.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent i = new Intent(context, ModuleIndexActivity.class);
                    i.putExtra("company_id", cm.getCompanyId().toString());
                    i.putExtra("company_guid", cm.getCompanyGuid());
                    context.startActivity(i);
//                    Utility.displayToast(getApplicationContext(),cm.getName(), Toast.LENGTH_LONG);
                }
            });
            module_main_list_0f_company.addView(button);
        }


//        ListView lv= (ListView) findViewById(R.id.module_main_list_0f_company);
//        List<String> your_array_list = new ArrayList<String>();
//        for(CompanyModel cm:companies){
//            your_array_list.add(cm.getName());
//        }
//        ArrayAdapter<String> arrayAdapter = new ArrayAdapter<String>(
//                this,
//                android.R.layout.simple_list_item_1,
//                your_array_list);
//        lv.setAdapter(arrayAdapter);
//        CAL=new CustomAdapterList(this, new ArrayList<Object>(companies), RequestRespondModel.TAG_INDEX_COMPANY);
//        lv.setAdapter(CAL);
//        lv.invalidateViews();

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
}
