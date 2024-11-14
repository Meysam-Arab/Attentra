package ir.fardan7eghlim.attentra.views.company;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.media.AudioManager;
import android.media.ToneGenerator;
import android.support.design.widget.FloatingActionButton;
import android.os.Bundle;
import android.text.Html;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
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
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserTypeModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.DialogModel;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class CompanyIndexActivity extends BaseActivity implements Observer {

    public static Activity cia;
    private CustomAdapterList CAL;
    private ArrayList<CompanyModel> companies;
    private ArrayList<CompanyModel> searched_companies;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_company_index);
        super.onCreateDrawer();

        cia=this;

        //search
        ImageView search_btn= (ImageView) findViewById(R.id.search_btn_ci);
        search_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                EditText search_target= (EditText) findViewById(R.id.search_et_ci);

                ArrayList<String> list_name_of_companies=new ArrayList<>();
                for(CompanyModel cm:companies){
                    list_name_of_companies.add(cm.getName());
                }
                ArrayList<Object> objs=new ArrayList<Object>(companies);
                ArrayList<Object> searched_objs=new ArrayList<Object>(companies);
                searched_objs=Utility.searchInList(objs,list_name_of_companies,search_target.getText().toString());
                Object temp=searched_objs;
                searched_companies=(ArrayList<CompanyModel>) temp;
                fillList(searched_companies);
            }
        });

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab_new_company);
        if(!new SessionModel(getApplicationContext()).getCurrentUser().getUserType().equals(UserTypeModel.MiddleCEO))
        {

            fab.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent i = new Intent( CompanyIndexActivity.this, CompanyAddActivity.class);
                    CompanyIndexActivity.this.startActivity(i);            }
            });
        }
        else
        {
            fab.setVisibility(View.GONE);
        }



        DialogModel.show(this);
        CompanyModel company = new CompanyModel();
        CompanyController cc = new CompanyController(getApplicationContext());
        cc.addObserver((Observer) this);

        cc.index(company);
        // Array of strings...
//        String[] mobileArray = {"Android","IPhone","WindowsMobile","Blackberry",
//                "WebOS","Ubuntu","Windows7","Max OS X"};
//        ArrayAdapter adapter = new ArrayAdapter<String>(this, R.layout.row_list_01,
//                R.id.text_first, mobileArray);
//
//        ListView listView = (ListView) findViewById(R.id.lsw_Company_list);
//        listView.setAdapter(adapter);
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

//        //////
//        DialogModel.show(this);
//        CompanyModel company = new CompanyModel();
//        CompanyController cc = new CompanyController(getApplicationContext());
//        cc.addObserver((Observer) this);
//
//        cc.index(company);
//        /////////
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
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError),2);
                    Intent i = new Intent(CompanyIndexActivity.this,UserHomeActivity.class);
                    CompanyIndexActivity.this.startActivity(i);
                    finish();
                }
                else
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationSuccess),Toast.LENGTH_LONG);
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).size()>0)
                {
                    if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_DELETE_COMPANY))
                    {
                        if(Boolean.parseBoolean(((ArrayList) arg).get(1).toString()) == true)
                            CAL.deleteCompany((BigInteger)((ArrayList) arg).get(2));
                        else
                            Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.msg_OperationError),Toast.LENGTH_LONG);

                    }
                    else if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_STORE_AUTO_CEO_ATTENDANCE))
                    {
                        Boolean success = (Boolean) ((ArrayList) arg).get(1);
                        String status = (String) ((ArrayList) arg).get(2);
                        //show user in dialog
                        AlertDialog alertDialog = new AlertDialog.Builder(CompanyIndexActivity.this).create();
                        if(success)
                        {
                            if(status.equals("0"))
                            {
//                                alertDialog.setTitle(getString(R.string.msg_CheckInOperationSuccess));
                                alertDialog.setTitle(Html.fromHtml("<font color='#658906'>"+getString(R.string.msg_CheckInOperationSuccess)+"</font>"));

                            }
                            else if(status.equals("1"))
                            {
//                                alertDialog.setTitle(getString(R.string.msg_CheckOutOperationSuccess));
                                alertDialog.setTitle(Html.fromHtml("<font color='#e31313'>"+getString(R.string.msg_CheckOutOperationSuccess)+"</font>"));

                            }
                            else
                            {
                                alertDialog.setTitle(Html.fromHtml("<font color='#3b89ff'>"+getString(R.string.msg_CheckInOutOperationSuccess)+"</font>"));

                            }
                        }
                        else
                            alertDialog.setTitle(getString(R.string.msg_OperationFail));

                        DatabaseHandler db = new DatabaseHandler(getApplicationContext());

                        alertDialog.setMessage(db.getUserDetails().getName()+" "+db.getUserDetails().getFamily());
                        alertDialog.setButton(AlertDialog.BUTTON_NEUTRAL, getString(R.string.btn_OK),
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int which) {
                                        dialog.dismiss();
                                    }
                                });
                        alertDialog.show();
                        ToneGenerator toneG = new ToneGenerator(AudioManager.STREAM_ALARM, 100);
                        toneG.startTone(ToneGenerator.TONE_CDMA_ALERT_CALL_GUARD, 200);
                    }
                    else
                    {
                        companies = (ArrayList<CompanyModel>) arg;
                        fillList(companies);
                    }
                }

            }
            else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_auth_fail),Toast.LENGTH_LONG);

                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    finish();
                }else {
                    Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())),Toast.LENGTH_LONG);

                }
            }
            else
            {
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError),Toast.LENGTH_LONG);

            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError),Toast.LENGTH_LONG);        }
    }

    //fill list of company
    private void fillList(ArrayList<CompanyModel> companies) {
        //make list
        ListView lv = (ListView) findViewById(R.id.lsw_Company_list);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(companies), RequestRespondModel.TAG_INDEX_COMPANY);
        lv.setAdapter(CAL);
        lv.invalidateViews();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater menuInflater = getMenuInflater();
        menuInflater.inflate(R.menu.menu_checkinoutthreedot, menu);

        //  store the menu to var when creating options menu

        MenuItem item = menu.findItem(R.id.menu_CheckInOutMerge);
        item.setCheckable(true);
        if(session.getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false))
            item.setChecked(true);
        else
            item.setChecked(false);

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item)
    {

        switch (item.getItemId())
        {

            case R.id.menu_CheckInOutMerge:
                if(!item.isChecked())
                {
                    session.saveItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,true);
                    item.setChecked(true );
                }
                else
                {
                    session.saveItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false);
                    item.setChecked(false);
                }
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }

}
