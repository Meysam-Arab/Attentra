package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import ir.fardan7eghlim.attentra.R;

/**
 * Created by Meysam on 1/11/2017.
 */

public class OperationMessageModel {

    public Context cntx = null;

    private int Code;
    private String Message;

    public int getCode() {
        return Code;
    }

    public void setCode(int code) {
        Code = code;
    }

    public String getMessage() {
        return Message;
    }

    public void setMessage(String message) {
        Message = message;
    }


    public static final int MessageNotSpecified = 0;
    public static final int OperationSuccess = 1;
    public static final int OperationFail = 2;
    public static final int OperationError = 3;


    public OperationMessageModel(int code, Context cntx)
    {
        this.cntx = cntx;
        initialize(code);
    }

    private void initialize(int code)
    {
        this.setCode(code);
        switch (code)
        {
            case MessageNotSpecified:
                this.setMessage(cntx.getResources().getString(R.string.msg_MessageNotSpecified));
                return;
            case OperationSuccess:
                this.setMessage(cntx.getResources().getString(R.string.msg_OperationSuccess));
                return;
            case OperationFail:
                this.setMessage(cntx.getResources().getString(R.string.msg_OperationFail));
                return;
            case OperationError:
                this.setMessage(cntx.getResources().getString(R.string.msg_OperationError));
                return;
            default:
                this.setMessage(cntx.getResources().getString(R.string.msg_MessageNotSpecified));
                return;
        }
    }

}
