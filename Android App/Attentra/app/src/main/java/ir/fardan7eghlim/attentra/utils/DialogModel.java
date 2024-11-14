package ir.fardan7eghlim.attentra.utils;

import android.app.ProgressDialog;
import android.content.Context;

import ir.fardan7eghlim.attentra.R;

/**
 * Created by Amir on 5/7/2017.
 */

public class DialogModel {
    public static ProgressDialog pd;

    public static void show(Context context){
        pd=new ProgressDialog(context);
        pd.setCancelable(false);
        pd.setMessage(context.getString(R.string.dlg_Wait));
        pd.show();
    }
    public static void hide(){
        pd.hide();
        pd.dismiss();
        pd=null;
    }

}
