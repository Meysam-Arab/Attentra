package ir.fardan7eghlim.attentra.views.payment;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.support.design.widget.NavigationView;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Observable;
import java.util.Observer;
import java.util.UUID;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.PaymentController;
import ir.fardan7eghlim.attentra.models.PaymentModel;
import ir.fardan7eghlim.attentra.models.ProductModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.utils.billing.IabHelper;
import ir.fardan7eghlim.attentra.utils.billing.IabResult;
import ir.fardan7eghlim.attentra.utils.billing.Inventory;
import ir.fardan7eghlim.attentra.utils.billing.Purchase;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class PaymentAddActivity extends BaseActivity implements Observer {
    private ProgressDialog pDialog;
    private Context cntx;
    private Spinner spinner;
    HashMap<Integer,String> spinnerMap;
    private ArrayList<ProductModel> products;
    private static String payload;
    private static Inventory mInventory;
    SessionModel session;

    // The helper object
    private IabHelper mHelper;
    // public key
    private String base64EncodedPublicKey;
    Button btn_PurchaseBalance;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_payment_add);
        super.onCreateDrawer();

        cntx = this;
        session = new SessionModel(cntx);
        products = new ArrayList<>();

//        spinnerMap = new HashMap<Integer, String>();


        pDialog = new ProgressDialog(cntx);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));

// You can find it in your Bazaar console, in the Dealers section.
// It is recommended to add more security than just pasting it in your source code;

        btn_PurchaseBalance = (Button) findViewById(R.id.btn_Purchase_Balance);
        btn_PurchaseBalance.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                increaseCharge();
            }
        });

        //request public key
        PaymentController pc = new PaymentController(getApplicationContext());
        pc.addObserver(this);
        pc.key();

        pDialog.show();

        //set spinner
        spinner= (Spinner) findViewById(R.id.sp_balances);

    }

    private void fillSpinner(ArrayList<String> list) {

        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, R.layout.spinner_01,list);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(dataAdapter);
    }

    public void increaseCharge(){
        String at_id = spinnerMap.get(spinner.getSelectedItemPosition());
        int x = 313;
        payload = UUID.randomUUID().toString();
        pDialog.show();
        //BAZZAR API
        mHelper.launchPurchaseFlow((Activity) cntx, at_id, x, mPurchaseFinishedListener, payload);


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
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).size()>0)
                    if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_KEY_PAYMENT))
                    {
                        //public key successfully returned from server
                        base64EncodedPublicKey = ((ArrayList) arg).get(1).toString();

                        // compute your public key and store it in base64EncodedPublicKey
                        mHelper = new IabHelper(cntx, base64EncodedPublicKey);

                        pDialog.show();
                        //check connection to service
                        mHelper.startSetup(new IabHelper.OnIabSetupFinishedListener() {
                            public void onIabSetupFinished(IabResult result) {
                                pDialog.dismiss();
                                if (!result.isSuccess()) {
                                    // Oh noes, there was a problem.
//                                    Utility.displayToast(getApplicationContext(),"Problem setting up In-app Billing: " + result,Toast.LENGTH_LONG);
                                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_ConnectionError), Toast.LENGTH_LONG);
                                    finish();
                                }
                                // Hooray, IAB is fully set up!

                                pDialog.show();
                                mHelper.queryInventoryAsync(true, ProductModel.generateAtList(),mGotInventoryListener);

                            }
                        });
                    } else if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_STORE_PAYMENT))
                            {
                                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);

                                btn_PurchaseBalance.setVisibility(View.VISIBLE);
                                clearStats();

                                Double price_at_all=new Double(session.getPayment().getAmount());
                                DatabaseHandler db = new DatabaseHandler(this);
                                UserModel user=db.getUserDetails();
                                Double last_balance=new Double(user.getBalance());
                                UserModel new_user=user;
                                new_user.setBalance((last_balance+price_at_all)+"");
                                db.editUser(new_user);

                                NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
                                View hView =  navigationView.getHeaderView(0);
                                TextView nav_payment = (TextView)hView.findViewById(R.id.nav_hdr_payment);
                                nav_payment.setText(getApplicationContext().getString(R.string.chargRemidTitle)+" "+db.getUserDetails().getBalance() + " "+getApplicationContext().getString(R.string.Tooman) );

                                session.removePayment();
                            }
            }else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    session.logoutUser(true);

                    Intent intents = new Intent(this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    finish();
                }else {
                    Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                    finish();
                }
            }
            else
            {
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                finish();
            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            finish();
        }


    }
    @Override
    public void onDestroy() {
        super.onDestroy();
        if (mHelper != null) mHelper.dispose();
        mHelper = null;
    }

    //فهرست کالاهای مصرف نشده کاربر
    //اگر درخواست لیست کالاها را بدهیم فعال ها را فقط می دهد
    IabHelper.QueryInventoryFinishedListener mGotInventoryListener = new IabHelper.QueryInventoryFinishedListener() {
        public void onQueryInventoryFinished(IabResult result, Inventory inventory) {
            pDialog.dismiss();
            if (result.isFailure()) {
                Utility.displayToast(getApplicationContext(), getString(R.string.error_operation_fail),Toast.LENGTH_LONG);
                finish();
                return;
            }
            else {
                mInventory = inventory;
                products = ProductModel.generateProductList(inventory);
                spinnerMap = ProductModel.generateProductHashMap(inventory);
                fillSpinner(ProductModel.generateProductTitleList(products));

                //if purchase was not consumed
                if(ProductModel.purchaseExist(inventory))
                {

                    String sku = ProductModel.getProductTitle(inventory);
                    //check if session exist
                    if(session.hasPayment())
                    {
                        //session for payment exist
                        btn_PurchaseBalance.setVisibility(View.GONE);

                        pDialog.show();
                        mHelper.consumeAsync(mInventory.getPurchase(sku), mConsumeFinishedListener);
                    }
                    else
                    {
                        PaymentModel payment = new PaymentModel(cntx);
                        payment.setPayload(inventory.getPurchase(sku).getDeveloperPayload());
                        payment.setToken(inventory.getPurchase(sku).getToken());
                        payment.setProductCode(inventory.getPurchase(sku).getSku());
                        payment.setAmount(ProductModel.getAmountByCode(sku).toString());

                        session.savePayment(payment);

//                        pDialog.show();
                        mHelper.consumeAsync(mInventory.getPurchase(sku), mConsumeFinishedListener);
                    }
                }
                else
                {
                    //check if session exist
                    if(session.hasPayment())
                    {
                        //session for payment exist
                        btn_PurchaseBalance.setVisibility(View.GONE);
                        pDialog.show();

                        PaymentController pc = new PaymentController(getApplicationContext());
                        pc.addObserver((Observer) cntx);
                        pc.store(session.getPayment());

                    }
                }
            }
        }
    };

    //زمانی که یک خرید به پایان می رسد
    IabHelper.OnIabPurchaseFinishedListener mPurchaseFinishedListener = new IabHelper.OnIabPurchaseFinishedListener() {
        public void onIabPurchaseFinished(IabResult result, Purchase purchase) {
            pDialog.dismiss();

            if (result.isFailure()) {
                Utility.displayToast(getApplicationContext(),getString(R.string.error_user_canceled),Toast.LENGTH_LONG);

                return;
            }

            PaymentModel payment = new PaymentModel(cntx);
            payment.setPayload(purchase.getDeveloperPayload());
            showDetailsOfPayment(payment);
//            pDialog.show();
            mHelper.consumeAsync(purchase, mConsumeFinishedListener);

        }
    };

    IabHelper.OnConsumeFinishedListener mConsumeFinishedListener =
            new IabHelper.OnConsumeFinishedListener() {
                public void onConsumeFinished(Purchase purchase, IabResult result) {
//                    pDialog.dismiss();
                    if (result.isSuccess()) {
                        // provision the in-app purchase to the user
                        // (for example, credit 50 gold coins to player's character)
                        PaymentModel payment = new PaymentModel(cntx);
                        payment.setPayload(purchase.getDeveloperPayload());
                        payment.setToken(purchase.getToken());
                        payment.setProductCode(purchase.getSku());
                        payment.setAmount(ProductModel.getAmountByCode(purchase.getSku()).toString());

                        if(!session.hasPayment())
                        {
                            session.savePayment(payment);
                        }
                        PaymentController pc = new PaymentController(getApplicationContext());
                        pc.addObserver((Observer) cntx);
                        pc.store(payment);
                    }
                    else {
                        // handle error

                        Utility.displayToast(cntx,getString(R.string.error_operation_fail),Toast.LENGTH_LONG);
                        finish();
                    }
                }
            };

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
//        pDialog.dismiss();
//        Utility.displayToast(getApplicationContext(),"onActivityResult(" + requestCode + "," + resultCode + "," + data,Toast.LENGTH_LONG);
        // Pass on the activity result to the helper for handling
        if (!mHelper.handleActivityResult(requestCode, resultCode, data)) {
            super.onActivityResult(requestCode, resultCode, data);
        } else {
//            Utility.displayToast(getApplicationContext(),"onActivityResult handled by IABUtil.",Toast.LENGTH_LONG);
        }
    }

    private void clearStats()
    {
        payload = null;
        mInventory = null;
    }

    private void showDetailsOfPayment(PaymentModel payment) {
        final Dialog d2=new Dialog(cntx);
        d2.requestWindowFeature(Window.FEATURE_NO_TITLE);
        d2.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        d2.setContentView(R.layout.message_dialog);
        TextView txt= (TextView) d2.findViewById(R.id.message_box_dialog);
        txt.setText(cntx.getString(R.string.lbl_followeup)+" "+ payment.getPayload());
        Button btn= (Button) d2.findViewById(R.id.btn_mess_01);
        btn.setText(cntx.getString(R.string.btn_OK));
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                d2.hide();
            }
        });
        d2.show();
    }
}
