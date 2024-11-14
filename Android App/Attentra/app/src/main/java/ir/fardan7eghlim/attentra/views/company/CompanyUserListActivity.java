package ir.fardan7eghlim.attentra.views.company;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.design.widget.FloatingActionButton;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class CompanyUserListActivity extends BaseActivity implements Observer {

    private String company_id;
    private String company_guid;
    private ListView list_of_users;
    private ProgressDialog pDialog;
    private CustomAdapterList CAL;
    private ArrayList<UserModel> listOfUser;
    private ArrayList<UserModel> searched_listOfUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_company_user_list);
        super.onCreateDrawer();

        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("company_id") != null && extras.getString("company_guid") != null) {
                company_id = extras.getString("company_id");
                company_guid = extras.getString("company_guid");
            }
        }

        //search
        ImageView search_btn= (ImageView) findViewById(R.id.search_btn_cul);
        search_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                EditText search_target= (EditText) findViewById(R.id.search_et_cul);

                ArrayList<String> list_name_of_users=new ArrayList<>();
                for(UserModel um:listOfUser){
                    list_name_of_users.add(um.getName()+" "+um.getFamily());
                }
                ArrayList<Object> objs=new ArrayList<Object>(listOfUser);
                ArrayList<Object> searched_objs=new ArrayList<Object>(listOfUser);
                searched_objs= Utility.searchInList(objs,list_name_of_users,search_target.getText().toString());
                Object temp=searched_objs;
                searched_listOfUser=(ArrayList<UserModel>) temp;
                fillList(searched_listOfUser);
            }
        });

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab_memberCompany);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent( CompanyUserListActivity.this, CompanyAddMemberActivity.class);
                i.putExtra("company_id", company_id);
                CompanyUserListActivity.this.startActivity(i);            }
        });

        CompanyModel company= new CompanyModel();
        company.setCompanyId(new BigInteger(company_id));
        company.setCompanyGuid(company_guid);

        // Progress dialog
        DialogModel.show(this);

        CompanyController cc=new CompanyController(getApplication());
        cc.addObserver((Observer) this);
        cc.listOfMember(company);
    }

    @Override
    public void update(Observable o, Object arg) {
        DialogModel.hide();

        if(arg != null)
        {
            if (arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Intent i = new Intent(CompanyUserListActivity.this,CompanyIndexActivity.class);
                    CompanyUserListActivity.this.startActivity(i);
                    finish();
                }else{
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                }
            }else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).size()>0)
                    if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_DELETE_USER))
                    {
                        if(Boolean.parseBoolean(((ArrayList) arg).get(1).toString()) == true)
                            CAL.deleteUser((BigInteger)((ArrayList) arg).get(2));
                        else
                            Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    }else {
                        listOfUser= (ArrayList<UserModel>) arg;
                        fillList(listOfUser);
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
    //fill list of company
    private void fillList(ArrayList<UserModel> users) {
        //make list
        ListView lv = (ListView) findViewById(R.id.list_company_members_cul);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(users), RequestRespondModel.TAG_LIST_MEMBERS_COMPANY);
        lv.setAdapter(CAL);
        lv.invalidateViews();
    }
}
