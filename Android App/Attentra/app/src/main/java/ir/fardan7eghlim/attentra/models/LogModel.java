package ir.fardan7eghlim.attentra.models;

import java.math.BigInteger;
import java.text.DateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.UUID;

/**
 * Created by Meysam on 1/11/2017.
 */

public class LogModel {



    private BigInteger UserId;
    private BigInteger Id;
    private UUID Guid;
    private String  ContollerName;
    private String ErrorMessage;
    private String CreatedDate;
    private String UpdatedDate;
    private String DeletedDate;

    public BigInteger getUserId() {
        return UserId;
    }

    public void setUserId(BigInteger userId) {
        UserId = userId;
    }

    public BigInteger getId() {
        return Id;
    }

    public void setId(BigInteger id) {
        Id = id;
    }

    public UUID getGuid() {
        return Guid;
    }

    public void setGuid(UUID guid) {
        Guid = guid;
    }

    public String getContollerName() {
        return ContollerName;
    }

    public void setContollerName(String contollerName) {
        ContollerName = contollerName;
    }

    public String getErrorMessage() {
        return ErrorMessage;
    }

    public void setErrorMessage(String errorMessage) {
        ErrorMessage = errorMessage;
    }

    public String getCreatedDate() {
        return CreatedDate;
    }

    public void setCreatedDate(String createdDate) {
        CreatedDate = createdDate;
    }

    public String getUpdatedDate() {
        return UpdatedDate;
    }

    public void setUpdatedDate(String updatedDate) {
        UpdatedDate = updatedDate;
    }

    public String getDeletedDate() {
        return DeletedDate;
    }

    public void setDeletedDate(String deletedDate) {
        DeletedDate = deletedDate;
    }

    public LogModel()
    {
        this.ContollerName = null;
        this.ErrorMessage = null;
        this.Guid = null;
        this.Id = null;
        this.UserId = null;
        this.CreatedDate = null;
        this.UpdatedDate = null;
        this.DeletedDate = null;


    }

    public void insert()
    {
        try
        {
            //send to server ... we must connect to server here
            
        }
        catch (Exception ex)
        {

        }
    }
    public void update()
    {

    }
    public boolean delete(){

        return true;

    }
    public static ArrayList<LogModel> select(LogModel log){

        ArrayList<LogModel> logs = new ArrayList<LogModel>();
        LogModel fakeLog = null;
        for (int i=0;i<10;i++)
        {
            fakeLog = new LogModel();
            fakeLog.setContollerName("UserController");
            fakeLog.setErrorMessage("my error meysam "+String.valueOf(i));
            fakeLog.setId(BigInteger.valueOf(1));
            fakeLog.setGuid(UUID.randomUUID());
            fakeLog.setUserId(BigInteger.valueOf(i));
            fakeLog.setCreatedDate(DateFormat.getDateTimeInstance().toString());
            fakeLog.setUpdatedDate(DateFormat.getDateTimeInstance().toString());
            fakeLog.setDeletedDate(null);

            logs.add(fakeLog);

        }

        return logs;
    }





}
