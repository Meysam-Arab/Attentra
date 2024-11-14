package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;
import ir.fardan7eghlim.attentra.interfaces.LanguageInterface;

/**
 * Created by Meysam on 3/7/2017.
 */

public class LanguageModel implements LanguageInterface {


    ///////////direction
    public static final Boolean RTL = true;
    public static final Boolean LTR= false;
    //////////////////////////////////////
    ///////////langs
    public static final String Persian = "1";
    public static final String English= "2";
    //////////////////////////////////////

    public BigInteger getLanguageId() {
        return LanguageId;
    }

    public void setLanguageId(BigInteger languageId) {
        LanguageId = languageId;
    }

    public String getLanguageGuid() {
        return LanguageGuid;
    }

    public void setLanguageGuid(String languageGuid) {
        LanguageGuid = languageGuid;
    }

    public String getTitle() {
        return Title;
    }

    public void setTitle(String title) {
        Title = title;
    }

    public Boolean getLanguageDirection() {
        return LanguageDirection;
    }

    public void setLanguageDirection(Boolean languageDirection) {
        LanguageDirection = languageDirection;
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

    public String getCode() {
        return Code;
    }

    public void setCode(String code) {
        Code = code;
    }


    private BigInteger LanguageId;
    private String LanguageGuid;
    private String Title;
    private Boolean LanguageDirection;
    private String Code;
    private String CreatedAt;
    private String UpdatedAt;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public LanguageModel()
    {
        this.LanguageId= null;
        this.LanguageGuid= null;
        this.Title= null;
        this.LanguageDirection= null;
        this.Code= null;
        this.CreatedAt= null;
        this.UpdatedAt= null;

        this.db = null;
        this.cntx = null;


    }

    public LanguageModel(Context cntx)
    {
        this.LanguageId= null;
        this.LanguageGuid= null;
        this.Title= null;
        this.LanguageDirection= null;
        this.Code= null;
        this.CreatedAt= null;
        this.UpdatedAt= null;
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

    public static String getLanguageString(BigInteger languageCode)
    {
        String code = languageCode.toString();

        switch (code)
        {
            case LanguageModel.Persian:
                return "fa";
            case LanguageModel.English:
                return "en";
            default:
                break;

        }
        return null;
    }

}
