package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.interfaces.AttendanceInterface;

/**
 * Created by Meysam on 3/7/2017.
 */

public class AttendanceModel implements AttendanceInterface {


    public BigInteger getAttendanceId() {
        return AttendanceId;
    }

    public void setAttendanceId(BigInteger attendanceId) {
        AttendanceId = attendanceId;
    }

    public String getAttendanceGuid() {
        return AttendanceGuid;
    }

    public void setAttendanceGuid(String attendanceGuid) {
        AttendanceGuid = attendanceGuid;
    }

    public BigInteger getUserCompanyId() {
        return UserCompanyId;
    }

    public void setUserCompanyId(BigInteger userCompanyId) {
        UserCompanyId = userCompanyId;
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

    public Boolean getMission() {
        return IsMission;
    }

    public void setMission(Boolean mission) {
        IsMission = mission;
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

    public String getDateTime() {
        return DateTime;
    }

    public void setDateTime(String dateTime) {
        DateTime = dateTime;
    }

    public String getQrCode() {
        return QrCode;
    }

    public void setQrCode(String qrCode) {
        QrCode = qrCode;
    }

    public Boolean isExiting() {
        return Exiting;
    }

    public void setExiting(Boolean exiting) {
        Exiting = exiting;
    }

    public Double getLatitude() {
        return Latitude;
    }

    public void setLatitude(Double latitude) {
        Latitude = latitude;
    }

    public Double getLongitude() {
        return Longitude;
    }

    public void setLongitude(Double longitude) {
        Longitude = longitude;
    }


    private BigInteger AttendanceId;
    private String AttendanceGuid;
    private BigInteger  UserCompanyId;
    private String StartDateTime;
    private String EndDateTime;
    private String DateTime;
    private Boolean IsMission;
    private String QrCode;
    private Boolean Exiting;
    private Double Latitude;
    private Double Longitude;
    private String CreatedAt;
    private String UpdatedAt;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public AttendanceModel()
    {
        this.AttendanceId= null;
        this.AttendanceGuid= null;
        this.UserCompanyId= null;
        this.StartDateTime= null;
        this.EndDateTime= null;
        this.IsMission= null;
        this.CreatedAt= null;
        this.UpdatedAt= null;
        this.QrCode=null;
        this.Exiting=null;

        this.db = null;
        this.cntx = null;


    }

    public AttendanceModel(Context cntx)
    {
        this.AttendanceId= null;
        this.AttendanceGuid= null;
        this.UserCompanyId= null;
        this.StartDateTime= null;
        this.EndDateTime= null;
        this.IsMission= null;
        this.CreatedAt= null;
        this.UpdatedAt= null;
        this.QrCode=null;
        this.Exiting=null;
        this.cntx = cntx;

    }

    @Override
    public void insert() {

    }

    public void insert(String user_id) {

    }

    @Override
    public void update() {

    }

    @Override
    public boolean delete() {
        return false;
    }
}
