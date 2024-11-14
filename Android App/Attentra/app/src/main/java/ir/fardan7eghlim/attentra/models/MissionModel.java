package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.graphics.Bitmap;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;

import ir.fardan7eghlim.attentra.interfaces.MissionInterface;

/**
 * Created by Meysam on 3/18/2017.
 */

public class MissionModel implements MissionInterface {


    public String getMissionGuid() {
        return MissionGuid;
    }

    public void setMissionGuid(String missionGuid) {
        MissionGuid = missionGuid;
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

    public String getStartDateTime() {
        return StartDateTime;
    }

    public void setStartDateTime(String startDateTime) {
        StartDateTime = startDateTime;
    }

    public String getEndDateTime() {
        return EndDateTime;
    }

    public void setEndDateTime(String endDateTime) {
        EndDateTime = endDateTime;
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

    public List<UserModel> getUsers() {
        return Users;
    }

    public void setUsers(List<UserModel> users) {
        Users = users;
    }

    public BigInteger getMissionId() {
        return MissionId;
    }

    public void setMissionId(BigInteger missionId) {
        MissionId = missionId;
    }

    private BigInteger MissionId;
    private String MissionGuid;
    private String  Title;
    private String Description;
    private String  StartDateTime;
    private String  EndDateTime;
    private String CreatedAt;
    private String UpdatedAt;
    private List<UserModel> Users;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public MissionModel()
    {
        this.MissionId = null;
        this.MissionGuid = null;
        this.Title = null;
        this.Description = null;
        this.StartDateTime = null;
        this.EndDateTime = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Users = new ArrayList<UserModel>();

        this.db = null;
        this.cntx = null;


    }

    public MissionModel(Context cntx)
    {
        this.MissionId = null;
        this.MissionGuid = null;
        this.Title = null;
        this.Description = null;
        this.StartDateTime = null;
        this.EndDateTime = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Users = new ArrayList<UserModel>();
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
