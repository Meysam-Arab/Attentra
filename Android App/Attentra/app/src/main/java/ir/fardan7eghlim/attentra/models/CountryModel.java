package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.graphics.Bitmap;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.interfaces.CompanyInterface;
import ir.fardan7eghlim.attentra.interfaces.CountryInterface;

/**
 * Created by Meysam on 3/7/2017.
 */

public class CountryModel implements CountryInterface {


    public BigInteger getCountryId() {
        return CountryId;
    }

    public void setCountryId(BigInteger countryId) {
        CountryId = countryId;
    }

    public String getCountryGuid() {
        return CountryGuid;
    }

    public void setCountryGuid(String countryGuid) {
        CountryGuid = countryGuid;
    }

    public String getName() {
        return Name;
    }

    public void setName(String name) {
        Name = name;
    }

    public String getCode() {
        return Code;
    }

    public void setCode(String code) {
        Code = code;
    }

    public String getCapital() {
        return Capital;
    }

    public void setCapital(String capital) {
        Capital = capital;
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

    private BigInteger CountryId;
    private String CountryGuid;
    private String  Name;
    private String Code;
    private String Capital;
    private String CreatedAt;
    private String UpdatedAt;
    private Boolean IsActive;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public CountryModel()
    {
        this.CountryId = null;
        this.CountryGuid = null;
        this.Name = null;
        this.Code = null;
        this.IsActive = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Capital = null;
        this.db = null;
        this.cntx = null;


    }

    public CountryModel(Context cntx)
    {
        this.CountryId = null;
        this.CountryGuid = null;
        this.Name = null;
        this.Code = null;
        this.IsActive = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Capital = null;
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
