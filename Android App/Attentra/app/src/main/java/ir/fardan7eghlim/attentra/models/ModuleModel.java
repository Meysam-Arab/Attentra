package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import ir.fardan7eghlim.attentra.interfaces.ModuleInterface;

/**
 * Created by Meysam on 4/13/2017.
 */

public class ModuleModel implements ModuleInterface {

    //status
    public static final int StatusOK=0;
    public static final int StatusEnd=1;
    public static final int StatusVerge=2;
    ////////////////////////////////
    public static final BigInteger attendanceModule= new BigInteger("1");

    public static final BigInteger missionModule=new BigInteger("2");

    public static final BigInteger newEmployeeModule=new BigInteger("3");

    public static final BigInteger newCompanyModule=new BigInteger("4");

    public static final BigInteger newTrackingModule=new BigInteger("5");

    /////////////////////////////////

    public static final ArrayList<BigInteger> TimeRelatedModuleIds = new ArrayList<BigInteger>(Arrays.asList(new BigInteger("2"), new BigInteger("1")));
    public static final ArrayList<BigInteger> PersonRelatedModuleIds = new ArrayList<BigInteger>(Arrays.asList(new BigInteger("3")));
    public static final ArrayList<BigInteger> PointRelatedModuleIds = new ArrayList<BigInteger>(Arrays.asList(new BigInteger("5")));
    public static final ArrayList<BigInteger> ItemRelatedModuleIds = new ArrayList<BigInteger>(Arrays.asList(new BigInteger("4")));


    public BigInteger getModuleId() {
        return ModuleId;
    }

    public void setModuleId(BigInteger moduleId) {
        ModuleId = moduleId;
    }

    public String getModuleGuid() {
        return ModuleGuid;
    }

    public void setModuleGuid(String moduleGuid) {
        ModuleGuid = moduleGuid;
    }

    public String getTitle() {
        return Title;
    }

    public void setTitle(String title) {
        Title = title;
    }

    public String getDescription() {
        return Description;
    }

    public void setDescription(String description) {
        Description = description;
    }

    public BigInteger getLanguageId() {
        return LanguageId;
    }

    public void setLanguageId(BigInteger languageId) {
        LanguageId = languageId;
    }

    public Boolean getActive() {
        return IsActive;
    }

    public void setActive(Boolean active) {
        IsActive = active;
    }

    public Integer getLimitValue() {
        return LimitValue;
    }

    public void setLimitValue(Integer limitValue) {
        LimitValue = limitValue;
    }

    public String getEndDate() {
        return EndDate;
    }

    public void setEndDate(String endDate) {
        EndDate = endDate;
    }

    public Float getPrice() {
        return Price;
    }

    public void setPrice(Float price) {
        Price = price;
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

    public Integer getStatus() {
        return Status;
    }

    public void setStatus(Integer status) {
        Status = status;
    }

    public String getPurchased() {
        return Purchased;
    }

    public void setPurchased(String purchased) {
        Purchased = purchased;
    }

    public String getStored() {
        return Stored;
    }

    public void setStored(String stored) {
        Stored = stored;
    }

    private BigInteger ModuleId;
    private String ModuleGuid;
    private String  Title;
    private String Description;
    private BigInteger LanguageId;
    private Boolean IsActive;
    private Integer  LimitValue;
    private String  EndDate;
    private Float  Price;
    private Integer  Status;
    private String Purchased;
    private String Stored;
    private String CreatedAt;
    private String UpdatedAt;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;



    public ModuleModel()
    {
        this.ModuleId = null;
        this.ModuleGuid = null;
        this.Title = null;
        this.Description = null;
        this.IsActive = null;
        this.LanguageId = null;
        this.LimitValue = null;
        this.EndDate = null;
        this.Price = null;
        this.Status = null;
        this.Purchased = null;
        this.Stored = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;


        this.db = null;
        this.cntx = null;


    }

    public ModuleModel(Context cntx)
    {
        this.ModuleId = null;
        this.ModuleGuid = null;
        this.Title = null;
        this.Description = null;
        this.IsActive = null;
        this.LanguageId = null;
        this.LimitValue = null;
        this.EndDate = null;
        this.Price = null;
        this.Status = null;
        this.Purchased = null;
        this.Stored = null;
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
