package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.graphics.Bitmap;

import java.io.File;
import java.math.BigInteger;
import java.util.ArrayList;
import java.util.UUID;
import ir.fardan7eghlim.attentra.interfaces.UserInterface;

/**
 * Created by Meysam on 12/20/2016.
 */

public class UserModel implements UserInterface {


    public static String Female= "female";//1
    public static String  Male= "male";//0

    public static String FemaleCodeString= "1";
    public static String  MaleCodeString= "0";

    public static int FemaleCode= 1;
    public static int  MaleCode= 0;

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

    public String getName() {
        return Name;
    }

    public void setName(String name) {
        Name = name;
    }

    public String getFamily() {
        return Family;
    }

    public void setFamily(String family) {
        Family = family;
    }



    public Integer getUserType() {
        return UserType;
    }

    public void setUserType(Integer userType) {
        UserType = userType;
    }

    public String getToken() {
        return Token;
    }

    public void setToken(String token) {
        Token = token;
    }


    public String getEmail() {
        return Email;
    }

    public void setEmail(String email) {
        Email = email;
    }

    public String getUserName() {
        return UserName;
    }

    public void setUserName(String userName) {
        UserName = userName;
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


    public String getPassword() {
        return Password;
    }

    public void setPassword(String password) {
        Password = password;
    }

    public String getCode() {
        return Code;
    }

    public void setCode(String code) {
        Code = code;
    }

    public Bitmap getProfilePicture() {
        return ProfilePicture;
    }

    public void setProfilePicture(Bitmap profilePicture) {
        ProfilePicture = profilePicture;
    }

    public String getGender() {
        return Gender;
    }

    public void setGender(String gender) {
        Gender = gender;
    }


    public String getPayment() {
        return Payment;
    }

    public void setPayment(String payment) {
        Payment = payment;
    }

    public String getBalance() {
        return Balance;
    }

    public void setBalance(String balance) {
        Balance = balance;
    }

    public BigInteger getCountryId() {
        return CountryId;
    }

    public void setCountryId(BigInteger countryId) {
        CountryId = countryId;
    }


    public String getUserAttendanceStartDate() {
        return UserAttendanceStartDate;
    }

    public void setUserAttendanceStartDate(String userAttendanceStartDate) {
        UserAttendanceStartDate = userAttendanceStartDate;
    }

    public String getUserAttendanceEndDate() {
        return UserAttendanceEndDate;
    }

    public void setUserAttendanceEndDate(String userAttendanceEndDate) {
        UserAttendanceEndDate = userAttendanceEndDate;
    }

    public String getUserCompanyName() {
        return UserCompanyName;
    }

    public void setUserCompanyName(String userCompanyName) {
        UserCompanyName = userCompanyName;
    }

    public Boolean getSelfRollCallAllowed() {
        return SelfRollCallAllowed;
    }

    public void setSelfRollCallAllowed(Boolean selfRollCallAllowed) {
        SelfRollCallAllowed = selfRollCallAllowed;
    }

    private Integer UserType;
    private BigInteger Id;
    private String Guid;
    private String  Name;
    private String Family;
    private String Token;
    private String Email;
    private String UserName;
    private String CreatedAt;
    private String UpdatedAt;
    private String Password;
    private String Code;
    private Bitmap ProfilePicture;
    private String Gender;
    private String Payment;
    private String Balance;
    private BigInteger CountryId;
    private String UserAttendanceStartDate;
    private String UserAttendanceEndDate;
    private String UserCompanyName;
    private Boolean SelfRollCallAllowed;


    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public UserModel(Integer userType, BigInteger id, String guid, String name, String family, String token, String email, String userName, String createdAt, String updatedAt, String password, String code, Bitmap profilePicture, String gender, String payment, String balance, BigInteger countryId) {
        UserType = userType;
        Id = id;
        Guid = guid;
        Name = name;
        Family = family;
        Token = token;
        Email = email;
        UserName = userName;
        CreatedAt = createdAt;
        UpdatedAt = updatedAt;
        Password = password;
        Code = code;
        ProfilePicture = profilePicture;
        Gender = gender;
        Payment = payment;
        Balance = balance;
        CountryId = countryId;
        UserAttendanceStartDate = null;
        UserAttendanceEndDate = null;
        UserCompanyName = null;
        SelfRollCallAllowed = null;
        this.db = null;
        this.cntx = null;
    }

    public UserModel()
    {
        this.Name = null;
        this.Family = null;
        this.Guid = null;
        this.Id = null;
        this.UserType = null;
        this.Token = null;
        this.Email = null;
        this.UserName = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Password = null;
        this.Code = null;
        this.ProfilePicture = null;
        this.Gender = null;
        this.Payment = null;
        this.Balance = null;
        this.CountryId = null;
        this.UserAttendanceStartDate = null;
        this.UserAttendanceEndDate = null;
        this.UserCompanyName = null;
        SelfRollCallAllowed = null;


        this.db = null;
        this.cntx = null;


    }

    public UserModel(Context cntx)
    {
        this.Name = null;
        this.Family = null;
        this.Guid = null;
        this.Id = null;
        this.UserType = null;
        this.Token = null;
        this.Email = null;
        this.UserName = null;
        this.CreatedAt = null;
        this.UpdatedAt = null;
        this.Password = null;
        this.Code = null;
        this.ProfilePicture = null;
        this.Gender = null;
        this.Payment = null;
        this.Balance = null;
        this.CountryId = null;
        this.UserAttendanceStartDate = null;
        this.UserAttendanceEndDate = null;
        this.UserCompanyName = null;
        this.cntx = cntx;
        SelfRollCallAllowed = null;

    }

    public void insert()
    {
        try
        {

        }
        catch (Exception ex)
        {

        }
    }
    public void update()
    {

    }
    public boolean delete(){

        return false;

    }
//    public static ArrayList<UserModel> select(UserModel userModel){
//
//        ArrayList<UserModel> userModels = new ArrayList<UserModel>();
//        UserModel fakeUserModel = null;
//        for (int i=0;i<10;i++)
//        {
//            fakeUserModel = new UserModel();
//            fakeUserModel.setName("Meysam");
//            fakeUserModel.setFamily("Arab"+Integer.toString(i));
//            fakeUserModel.setId(BigInteger.valueOf(1));
//            fakeUserModel.setGuid(UUID.randomUUID().toString());
//            fakeUserModel.setUserType(UserTypeModel.CEO);
//            userModels.add(fakeUserModel);
//
//        }
//
//        return userModels;
//    }



}
