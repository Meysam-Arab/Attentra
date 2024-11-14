package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.graphics.Bitmap;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.interfaces.CompanyInterface;

/**
 * Created by Meysam on 3/7/2017.
 */

public class CompanyModel implements CompanyInterface {

    public BigInteger getCompanyId() {
        return CompanyId;
    }

    public void setCompanyId(BigInteger companyId) {
        CompanyId = companyId;
    }

    public String getCompanyGuid() {
        return CompanyGuid;
    }

    public void setCompanyGuid(String companyGuid) {
        CompanyGuid = companyGuid;
    }

    public String getName() {
        return Name;
    }

    public void setName(String name) {
        Name = name;
    }

    public String getTimeZone() {
        return TimeZone;
    }

    public void setTimeZone(String timeZone) {
        TimeZone = timeZone;
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

    public Boolean getActive() {
        return IsActive;
    }

    public void setActive(Boolean active) {
        IsActive = active;
    }
    public Bitmap getCompanyPicture() {
        return CompanyPicture;
    }

    public void setCompanyPicture(Bitmap companyPicture) {
        CompanyPicture = companyPicture;
    }


    private BigInteger CompanyId;
    private String CompanyGuid;
    private String  Name;
    private String TimeZone;
    private Bitmap CompanyPicture;
    private String CreatedAt;
    private String UpdatedAt;
    private Boolean IsActive;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public CompanyModel()
    {
        this.CompanyId = null;
        this.CompanyGuid = null;
        this.Name = null;
        this.TimeZone = null;
        this.IsActive = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.CompanyPicture = null;
        this.db = null;
        this.cntx = null;


    }

    public CompanyModel(Context cntx)
    {
        this.CompanyId = null;
        this.CompanyGuid = null;
        this.Name = null;
        this.TimeZone = null;
        this.IsActive = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.CompanyPicture = null;
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
}
