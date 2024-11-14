package ir.fardan7eghlim.attentra.views.payment;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.PaymentController;
import ir.fardan7eghlim.attentra.models.PaymentModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CustomAdapterList;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class PaymentIndexActivity extends BaseActivity implements Observer {
    Context context=this;
    public static Activity pia;
    private CustomAdapterList CAL;
    private UserModel user=new UserModel();
    private ProgressDialog pDialog;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_payment_index);
        super.onCreateDrawer();

        DatabaseHandler db = new DatabaseHandler(getApplicationContext());
        user=db.getUserDetails();

        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();

        PaymentController pc = new PaymentController(getApplicationContext());
        pc.addObserver((Observer) this);

        pc.index(user);

    }
    public void payNewPayment(View view){
        Intent i = new Intent(context, PaymentAddActivity.class);
        context.startActivity(i);
    }
    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // getIntent() should always return the most recent
        setIntent(intent);

//        pDialog = new ProgressDialog(this);
//        pDialog.setCancelable(false);
//        pDialog.setMessage(getString(R.string.dlg_Wait));
//        pDialog.show();
//
//        PaymentController pc = new PaymentController(getApplicationContext());
//        pc.addObserver((Observer) this);
//
//        pc.index(user);

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
            }
            else if(arg instanceof ArrayList)
            {
                    List<PaymentModel> payments= (List<PaymentModel>) arg;
                    fillList(payments);

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
    private void fillList(List<PaymentModel> payments) {
        //make list
        ListView lv = (ListView) findViewById(R.id.lsw_payment_list);
        CAL=new CustomAdapterList(this, new ArrayList<Object>(payments), RequestRespondModel.TAG_INDEX_PAYMENT);
        lv.setAdapter(CAL);
        lv.invalidateViews();
    }
}
