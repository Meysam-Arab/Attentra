package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.interfaces.UserCompanyInterface;

/**
 * Created by Meysam on 3/13/2017.
 */

public class UserCompanyModel implements UserCompanyInterface{


    public BigInteger getId() {
        return Id;
    }

    public void setId(BigInteger id) {
        Id = id;
    }

    public String getGuid() {
        return Guid;
    }

    public void setGuid(String guid) {
        Guid = guid;
    }

    public BigInteger getUserId() {
        return UserId;
    }

    public void setUserId(BigInteger userId) {
        UserId = userId;
    }

    public BigInteger getCompanyId() {
        return CompanyId;
    }

    public void setCompanyId(BigInteger companyId) {
        CompanyId = companyId;
    }

    public String getCreatedAt() {
        return CreatedAt;
    }

    public void setCreatedAt(String createdAt) {
        CreatedAt = createdAt;
    }

    public String getUpatedAt() {
        return UpatedAt;
    }

    public void setUpatedAt(String upatedAt) {
        UpatedAt = upatedAt;
    }

    public String getDeletedAt() {
        return DeletedAt;
    }

    public void setDeletedAt(String deletedAt) {
        DeletedAt = deletedAt;
    }

    private BigInteger Id;
    private String Guid;
    private BigInteger UserId;
    private BigInteger CompanyId;
    private String CreatedAt;
    private String UpatedAt;
    private String DeletedAt;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;

    public UserCompanyModel()
    {
        this.Id = null;
        this.Guid = null;
        this.UserId = null;
        this.CompanyId = null;
        this.CreatedAt = null;
        this.UpatedAt = null;
        this.DeletedAt = null;

        this.db = null;
        this.cntx = null;
    }

    public UserCompanyModel(Context cntx)
    {
        this.Id = null;
        this.Guid = null;
        this.UserId = null;
        this.CompanyId = null;
        this.CreatedAt = null;
        this.UpatedAt = null;
        this.DeletedAt = null;
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
