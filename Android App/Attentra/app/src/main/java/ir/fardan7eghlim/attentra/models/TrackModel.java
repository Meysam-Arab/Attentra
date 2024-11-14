package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.os.BatteryManager;
import android.telephony.SignalStrength;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.interfaces.TrackInterface;

import static android.telephony.CellSignalStrength.SIGNAL_STRENGTH_GOOD;
import static android.telephony.CellSignalStrength.SIGNAL_STRENGTH_GREAT;
import static android.telephony.CellSignalStrength.SIGNAL_STRENGTH_MODERATE;
import static android.telephony.CellSignalStrength.SIGNAL_STRENGTH_NONE_OR_UNKNOWN;
import static android.telephony.CellSignalStrength.SIGNAL_STRENGTH_POOR;

/**
 * Created by Meysam on 3/12/2017.
 */

public class TrackModel implements TrackInterface {


    private BigInteger TrackId;
    private String TrackGuid;
    private BigInteger UserId;
    private String  TrackGroup;
    private Double Latitude;
    private Double Longitude;
    private Float Altitude;
    private Float Accuracy;
    private Float Speed;
    private Float Bearing;
    private Integer BatteryPower;
    private Integer BatteryStatus;
    private Integer ChargeStatus;
    private Integer ChargeType;
    private Integer SignalPower;


    private String  DeletedAt;
    private String  CreatedAt;
    private String  UpdatedAt;


    public Integer getBatteryPower() {
        return BatteryPower;
    }

    public void setBatteryPower(Integer batteryPower) {
        BatteryPower = batteryPower;
    }

    public Integer getBatteryStatus() {
        return BatteryStatus;
    }

    public void setBatteryStatus(Integer batteryStatus) {
        BatteryStatus = batteryStatus;
    }

    public Integer getChargeStatus() {
        return ChargeStatus;
    }

    public void setChargeStatus(Integer chargeStatus) {
        ChargeStatus = chargeStatus;
    }

    public Integer getChargeType() {
        return ChargeType;
    }

    public void setChargeType(Integer chargeType) {
        ChargeType = chargeType;
    }

    public Integer getSignalPower() {
        return SignalPower;
    }

    public void setSignalPower(Integer signalPower) {
        SignalPower = signalPower;
    }

    public BigInteger getTrackId() {
        return TrackId;
    }

    public void setTrackId(BigInteger trackId) {
        TrackId = trackId;
    }

    public String getTrackGuid() {
        return TrackGuid;
    }

    public void setTrackGuid(String trackGuid) {
        TrackGuid = trackGuid;
    }

    public BigInteger getUserId() {
        return UserId;
    }

    public void setUserId(BigInteger userId) {
        UserId = userId;
    }

    public String getTrackGroup() {
        return TrackGroup;
    }

    public void setTrackGroup(String trackGroup) {
        TrackGroup = trackGroup;
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

    public Float getAltitude() {
        return Altitude;
    }

    public void setAltitude(Float altitude) {
        Altitude = altitude;
    }

    public Float getAccuracy() {
        return Accuracy;
    }

    public void setAccuracy(Float accuracy) {
        Accuracy = accuracy;
    }

    public Float getSpeed() {
        return Speed;
    }

    public void setSpeed(Float speed) {
        Speed = speed;
    }

    public Float getBearing() {
        return Bearing;
    }

    public void setBearing(Float bearing) {
        Bearing = bearing;
    }

    public String getDeletedAt() {
        return DeletedAt;
    }

    public void setDeletedAt(String deletedAt) {
        DeletedAt = deletedAt;
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

    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public TrackModel()
    {
        this.TrackId = null;
        this.TrackGuid = null;
        this.UserId = null;
        this.TrackGroup = null;
        this.Latitude = null;
        this.Longitude = null;
        this.Altitude = null;
        this.Accuracy = null;
        this.Speed = null;
        this.Bearing = null;
        this.BatteryPower = null;
        this.BatteryStatus = null;
        this.ChargeStatus = null;
        this.ChargeType = null;
        this.SignalPower = null;
        this.DeletedAt = null;
        this.UpdatedAt = null;
        this.CreatedAt = null;


        this.db = null;
        this.cntx = null;


    }

    public TrackModel(Context cntx)
    {
        this.TrackId = null;
        this.TrackGuid = null;
        this.UserId = null;
        this.TrackGroup = null;
        this.Latitude = null;
        this.Longitude = null;
        this.Altitude = null;
        this.Accuracy = null;
        this.Speed = null;
        this.Bearing = null;
        this.BatteryPower = null;
        this.BatteryStatus = null;
        this.ChargeStatus = null;
        this.ChargeType = null;
        this.SignalPower = null;
        this.DeletedAt = null;
        this.UpdatedAt = null;
        this.CreatedAt = null;
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

    public static String getPlugTypeString(int plugged, Context cntx) {
        String plugType =  cntx.getResources().getString(R.string.lbl_status_unknown);

        switch (plugged) {
            case BatteryManager.BATTERY_PLUGGED_AC://1
                plugType =  cntx.getResources().getString(R.string.lbl_battery_plugged_ac);
                break;
            case BatteryManager.BATTERY_PLUGGED_USB://2
                plugType =  cntx.getResources().getString(R.string.lbl_battery_plugged_usb);
                break;
            case BatteryManager.BATTERY_PLUGGED_WIRELESS://4
                plugType =  cntx.getResources().getString(R.string.lbl_battery_plugged_wireless);
                break;

        }
        return plugType;
    }

    public static String getHealthString(int health, Context cntx) {
        String healthString = cntx.getResources().getString(R.string.lbl_status_unknown);
        switch (health) {
            case BatteryManager.BATTERY_HEALTH_DEAD://4
                healthString = cntx.getResources().getString(R.string.lbl_battery_health_dead);
                break;
            case BatteryManager.BATTERY_HEALTH_GOOD://2
                healthString = cntx.getResources().getString(R.string.lbl_battery_health_good);
                break;
            case BatteryManager.BATTERY_HEALTH_OVER_VOLTAGE://5
                healthString =  cntx.getResources().getString(R.string.lbl_battery_health_over_voltage);
                break;
            case BatteryManager.BATTERY_HEALTH_OVERHEAT://3
                healthString =  cntx.getResources().getString(R.string.lbl_battery_health_over_heat);
                break;
            case BatteryManager.BATTERY_HEALTH_UNSPECIFIED_FAILURE://6
                healthString =  cntx.getResources().getString(R.string.lbl_battery_health_unspecific_failure);
                break;
        }
        return healthString;
    }
    public static String getStatusString(int status, Context cntx) {
        String statusString = cntx.getResources().getString(R.string.lbl_status_unknown);


        switch (status) {
            case BatteryManager.BATTERY_STATUS_CHARGING://2
                statusString = cntx.getResources().getString(R.string.lbl_battery_status_charging);
                break;
            case BatteryManager.BATTERY_STATUS_DISCHARGING://3
                statusString = cntx.getResources().getString(R.string.lbl_battery_status_discharging);
                break;
            case BatteryManager.BATTERY_STATUS_FULL://5
                statusString = cntx.getResources().getString(R.string.lbl_battery_status_full);
                break;
            case BatteryManager.BATTERY_STATUS_NOT_CHARGING://4
                statusString = cntx.getResources().getString(R.string.lbl_battery_status_not_charging);
                break;
        }
        return statusString;
    }

    public static String getGsmLevelString(int status, Context cntx) {
        String statusString = cntx.getResources().getString(R.string.lbl_signal_none_or_unknown);

        switch (status) {
            case SIGNAL_STRENGTH_GREAT:
                statusString = cntx.getResources().getString(R.string.lbl_signal_great);
                break;
            case SIGNAL_STRENGTH_GOOD:
                statusString = cntx.getResources().getString(R.string.lbl_signal_good);
                break;
            case SIGNAL_STRENGTH_MODERATE:
                statusString = cntx.getResources().getString(R.string.lbl_signal_moderate);
                break;
            case SIGNAL_STRENGTH_POOR:
                statusString = cntx.getResources().getString(R.string.lbl_signal_poor);
                break;
        }
        return statusString;

    }

    public static int getGsmLevel(SignalStrength ss) {
        int level;


        // ASU ranges from 0 to 31 - TS 27.007 Sec 8.5
        // asu = 0 (-113dB or less) is very weak
        // signal, its better to show 0 bars to the user in such cases.
        // asu = 99 is a special case, where the signal strength is unknown.
        int asu = ss.getGsmSignalStrength();
        if (asu <= 2 || asu == 99) level = SIGNAL_STRENGTH_NONE_OR_UNKNOWN;//0
        else if (asu >= 12) level = SIGNAL_STRENGTH_GREAT;//4
        else if (asu >= 8)  level = SIGNAL_STRENGTH_GOOD;//3
        else if (asu >= 5)  level = SIGNAL_STRENGTH_MODERATE;//2
        else level = SIGNAL_STRENGTH_POOR;//1
//        if (DBG) log("getGsmLevel=" + level);
        return level;
    }
}
