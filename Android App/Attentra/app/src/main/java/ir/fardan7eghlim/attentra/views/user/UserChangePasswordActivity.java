package ir.fardan7eghlim.attentra.views.user;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;
import java.util.Observable;
import java.util.Observer;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.UserController;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;

public class UserChangePasswordActivity extends BaseActivity implements Observer {

    private EditText old_password;
    private EditText new_password;
    private EditText new2_password;
    private Context context=this;
    private UserModel user=new UserModel();
    private ProgressDialog pDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_change_password);
        super.onCreateDrawer();

        DatabaseHandler db = new DatabaseHandler(getApplicationContext());
        user=db.getUserDetails();

        old_password= (EditText) findViewById(R.id.prePassword_et_cpss);
        new_password= (EditText) findViewById(R.id.Password_et_cpss);
        new2_password= (EditText) findViewById(R.id.Password2_et_cpss);
    }
    public void changePassword(View view){
        if(new_password.getText().toString().equals(new2_password.getText().toString())){
            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
            pDialog.setMessage(getString(R.string.dlg_Wait));
            pDialog.show();
            UserController uc = new UserController(getApplicationContext());
            uc.addObserver((Observer) this);
            uc.resetPassword(user,old_password.getText().toString(),new_password.getText().toString());
        }else{
            Utility.displayToast(context,context.getResources().getString(R.string.error_incorrect_password),Toast.LENGTH_SHORT);
        }
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
                }else{
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
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
