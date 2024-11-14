package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.interfaces.PaymentInterface;

/**
 * Created by Meysam on 3/7/2017.
 */

public class PaymentModel implements PaymentInterface {


    public static final int MESSAGE_PAYMENT_ZARINPAL_DEFECTIVE_INFORMATION = -1;
    public static final int MESSAGE_PAYMENT_ZARINPAL_INCORRECT_IP_OR_MERCHANT_CODE = -2;
    public static final int MESSAGE_PAYMENT_ZARINPAL_SHAPARAK_RESTRICTION_PAYMENT = -3;
    public static final int MESSAGE_PAYMENT_ZARINPAL_BELOW_SILVER_LEVEL = -4;
    public static final int MESSAGE_PAYMENT_ZARINPAL_REQUEST_NOT_FIND = -11;
    public static final int MESSAGE_PAYMENT_ZARINPAL_CAN_NOT_EDIT_REQUEST = -12;
    public static final int MESSAGE_PAYMENT_ZARINPAL_FINANCIAL_OPERATION_NOT_FIND = -21;
    public static final int MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_FAILED = -22;
    public static final int MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_PAYMENT_MISMATCH = -33;
    public static final int MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_COUNT_EXCEEDED = -34;
    public static final int MESSAGE_PAYMENT_ZARINPAL_METHOD_ACCESS_NOT_ALLOWED = -40;
    public static final int MESSAGE_PAYMENT_ZARINPAL_INCORRECT_ADDETIONAL_DATA = -41;
    public static final int MESSAGE_PAYMENT_ZARINPAL_ID_VALIDATION_TIME = -42;
    public static final int MESSAGE_PAYMENT_ZARINPAL_REQUEST_ARCHIVED= -54;
    public static final int MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL = 100;
    public static final int MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL_PAYMENT_VERIFICATION_ALREDY_DONE = 101;
    public static final int MESSAGE_PAYMENT_BAZAR_OPERATION_SUCCESSFUL = 0;


    public BigInteger getPaymentId() {
        return PaymentId;
    }

    public void setPaymentId(BigInteger paymentId) {
        PaymentId = paymentId;
    }

    public String getPaymentGuid() {
        return PaymentGuid;
    }

    public void setPaymentGuid(String paymentGuid) {
        PaymentGuid = paymentGuid;
    }

    public BigInteger getUserId() {
        return UserId;
    }

    public void setUserId(BigInteger userId) {
        UserId = userId;
    }

    public BigInteger getCurrencyId() {
        return CurrencyId;
    }

    public void setCurrencyId(BigInteger currencyId) {
        CurrencyId = currencyId;
    }

    public String getAmount() {
        return Amount;
    }

    public void setAmount(String amount) {
        Amount = amount;
    }

    public String getDescription() {
        return Description;
    }

    public void setDescription(String description) {
        Description = description;
    }

    public String getAuthority() {
        return Authority;
    }

    public void setAuthority(String authority) {
        Authority = authority;
    }

    public Integer getStatus() {
        return Status;
    }

    public void setStatus(Integer status) {
        Status = status;
    }

    public String getCreatedAt() {
        return CreatedAt;
    }

    public void setCreatedAt(String createdAt) {
        CreatedAt = createdAt;
    }

    public String getUpdatedAt() {
        return UpdatedAt;
    }

    public void setUpdatedAt(String updatedAt) {
        UpdatedAt = updatedAt;
    }

    public String getToken() {
        return Token;
    }

    public void setToken(String token) {
        Token = token;
    }

    public String getPayload() {
        return Payload;
    }

    public void setPayload(String payload) {
        Payload = payload;
    }

    public String getProductCode() {
        return ProductCode;
    }

    public void setProductCode(String productCode) {
        ProductCode = productCode;
    }

    private BigInteger PaymentId;
    private String PaymentGuid;
    private BigInteger UserId;
    private BigInteger CurrencyId;
    private String  Amount;
    private String Description;
    private String Authority;
    private Integer Status;
    private String Token;
    private String Payload;
    private String ProductCode;
    private String CreatedAt;
    private String UpdatedAt;



    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public PaymentModel()
    {
        this.PaymentId = null;
        this.PaymentGuid = null;
        this.Amount = null;
        this.Authority = null;
        this.CurrencyId = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Status = null;
        this.Description = null;
        this.Token = null;
        this.Payload = null;
        this.ProductCode = null;

        this.db = null;
        this.cntx = null;


    }

    public PaymentModel(Context cntx)
    {
        this.PaymentId = null;
        this.PaymentGuid = null;
        this.Amount = null;
        this.Authority = null;
        this.CurrencyId = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Status = null;
        this.Description = null;
        this.Token = null;
        this.Payload = null;
        this.ProductCode = null;

        this.cntx = cntx;

    }




    @Override
    public void insert() {

    }

    @Override
    public void update() {

    }

    @Override
    public boolean delete() {
        return false;
    }

    public String getMessage(int code)
    {
        String result = "";

        switch (code)
        {
            case MESSAGE_PAYMENT_BAZAR_OPERATION_SUCCESSFUL:
                result = cntx.getString(R.string.msg_PaymentBazarOperationSuccessful);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_BELOW_SILVER_LEVEL:
                result = cntx.getString(R.string.msg_PaymentZarinPalBelowSilverLevel);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_DEFECTIVE_INFORMATION:
                result = cntx.getString(R.string.msg_PaymentZarinPalDefectiveInformation);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_CAN_NOT_EDIT_REQUEST:
                result = cntx.getString(R.string.msg_PaymentZarinPalCanNotEditRequest);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_FINANCIAL_OPERATION_NOT_FIND:
                result = cntx.getString(R.string.msg_PaymentZarinPalFinancialOperationNotFind);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_ID_VALIDATION_TIME:
                result = cntx.getString(R.string.msg_PaymentZarinPalIdValidationTime);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_INCORRECT_ADDETIONAL_DATA:
                result = cntx.getString(R.string.msg_PaymentZarinPalIncorrectAdditionalData);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_INCORRECT_IP_OR_MERCHANT_CODE:
                result = cntx.getString(R.string.msg_PaymentZarinPalIncorrectIpOrMerchantCode);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_METHOD_ACCESS_NOT_ALLOWED:
                result = cntx.getString(R.string.msg_PaymentZarinPalMethodAccessNotAllowed);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL:
                result = cntx.getString(R.string.msg_PaymentZarinPalOperationSuccessful);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL_PAYMENT_VERIFICATION_ALREDY_DONE:
                result = cntx.getString(R.string.msg_PaymentZarinPalOperationSuccessfulPaymentVerificationAlredyDone);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_REQUEST_ARCHIVED:
                result = cntx.getString(R.string.msg_PaymentZarinPalRequestArchived);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_REQUEST_NOT_FIND:
                result = cntx.getString(R.string.msg_PaymentZarinPalRequestNotFind);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_SHAPARAK_RESTRICTION_PAYMENT:
                result = cntx.getString(R.string.msg_PaymentZarinPalShaparakRestrictionPayment);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_COUNT_EXCEEDED:
                result = cntx.getString(R.string.msg_PaymentZarinPalTransactionCountExceeded);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_FAILED:
                result = cntx.getString(R.string.msg_PaymentZarinPalTransactionFailed);
                break;
            case MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_PAYMENT_MISMATCH:
                result = cntx.getString(R.string.msg_PaymentZarinPalTransactionPaymentMismatch);
                break;
            default:
                result = cntx.getString(R.string.msg_MessageNotSpecified);
                break;
        }

        return result;
    }
}
