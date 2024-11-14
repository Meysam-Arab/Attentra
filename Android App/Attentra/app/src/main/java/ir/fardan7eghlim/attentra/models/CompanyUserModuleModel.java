package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.interfaces.CompanyUserModuleInterface;

/**
 * Created by Meysam on 4/13/2017.
 */

public class CompanyUserModuleModel implements CompanyUserModuleInterface{


    public BigInteger getCompanyUserModuleId() {
        return CompanyUserModuleId;
    }

    public void setCompanyUserModuleId(BigInteger companyUserModuleId) {
        CompanyUserModuleId = companyUserModuleId;
    }

    public String getCompanyUserModuleGuid() {
        return CompanyUserModuleGuid;
    }

    public void setCompanyUserModuleGuid(String companyUserModuleGuid) {
        CompanyUserModuleGuid = companyUserModuleGuid;
    }

    public BigInteger getCompanyId() {
        return CompanyId;
    }

    public void setCompanyId(BigInteger companyId) {
        CompanyId = companyId;
    }

    public BigInteger getUserId() {
        return UserId;
    }

    public void setUserId(BigInteger userId) {
        UserId = userId;
    }

    public BigInteger getModuleId() {
        return ModuleId;
    }

    public void setModuleId(BigInteger moduleId) {
        ModuleId = moduleId;
    }

    public Boolean getActive() {
        return IsActive;
    }

    public void setActive(Boolean active) {
        IsActive = active;
    }

    public Integer getLimitCount() {
        return LimitCount;
    }

    public void setLimitCount(Integer limitCount) {
        LimitCount = limitCount;
    }

    public String getEndDate() {
        return EndDate;
    }

    public void setEndDate(String endDate) {
        EndDate = endDate;
    }

    public Float getCost() {
        return Cost;
    }

    public void setCost(Float cost) {
        Cost = cost;
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

    private BigInteger CompanyUserModuleId;
    private String CompanyUserModuleGuid;
    private BigInteger CompanyId;
    private BigInteger UserId;
    private BigInteger ModuleId;
    private Boolean IsActive;
    private Integer  LimitCount;
    private String  EndDate;
    private Float  Cost;
    private String CreatedAt;
    private String UpdatedAt;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;



    public CompanyUserModuleModel()
    {
        this.CompanyUserModuleId = null;
        this.CompanyUserModuleGuid = null;
        this.CompanyId = null;
        this.UserId = null;
        this.IsActive = null;
        this.ModuleId = null;
        this.EndDate = null;
        this.LimitCount = null;
        this.Cost = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;


        this.db = null;
        this.cntx = null;


    }

    public CompanyUserModuleModel(Context cntx)
    {
        this.CompanyUserModuleId = null;
        this.CompanyUserModuleGuid = null;
        this.CompanyId = null;
        this.UserId = null;
        this.IsActive = null;
        this.ModuleId = null;
        this.EndDate = null;
        this.LimitCount = null;
        this.Cost = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
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
