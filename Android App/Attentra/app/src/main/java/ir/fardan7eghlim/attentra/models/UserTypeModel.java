package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;
import java.util.UUID;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.interfaces.UserTypeInterface;

/**
 * Created by Meysam on 12/21/2016.
 */

public class UserTypeModel implements UserTypeInterface {

    public static final Integer Admin = Integer.valueOf(0);
    public static final Integer CEO = Integer.valueOf(1);
    public static final Integer MiddleCEO = Integer.valueOf(2);
    public static final Integer EMPLOYEE = Integer.valueOf(3);
    public static final Integer None = Integer.valueOf(4);
    public static final Integer Device = Integer.valueOf(5);

    public UserTypeModel(Integer id) {
        Id = id;
        Guid=null;
        Title=null;
    }

    public Integer getId() {
        return Id;
    }

    public void setId(Integer id) {
        Id = id;
    }

    public UUID getGuid() {
        return Guid;
    }

    public void setGuid(UUID guid) {
        Guid = guid;
    }

    public String getTitle() {
        return Title;
    }

    public void setTitle(String title) {
        Title = title;
    }

    private Integer Id;
    private UUID Guid;
    private String  Title;


    public void insert()
    {

    }
    public void update()
    {

    }
    public boolean delete(){

        return true;

    }
    public String convertTypeToString(Context context){
        String temp="";
        switch (this.Id){
            case 0:
                temp=context.getString(R.string.Admin);
                break;
            case 1:
                temp=context.getString(R.string.CEO);
                break;
            case 2:
                temp=context.getString(R.string.MiddleCEO);
                break;
            case 3:
                temp=context.getString(R.string.Employee);
                break;
            case 4:
                temp=context.getString(R.string.None);
                break;
        }
        return temp;
    }

    public static Integer convertStringToType(Context context, String value){

        Integer temp = -1;
        switch (value){
            case "MiddleEO":
                temp=UserTypeModel.MiddleCEO;
                break;
            case "Employee":
                temp=UserTypeModel.EMPLOYEE;
                break;
            case "Device":
                temp=UserTypeModel.Device;
                break;
            case "مدیر بخش":
                temp=UserTypeModel.MiddleCEO;
                break;
            case "کارمند":
                temp=UserTypeModel.EMPLOYEE;
                break;
            case "دستگاه":
                temp=UserTypeModel.Device;
                break;
            default:
                temp = UserTypeModel.EMPLOYEE;
                break;
        }
        return temp;
    }
}
