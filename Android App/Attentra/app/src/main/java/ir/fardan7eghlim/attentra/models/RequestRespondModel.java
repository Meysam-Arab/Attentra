package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.List;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.utils.Utility;

/**
 * Created by Meysam on 2/16/2017.
 */

public class RequestRespondModel {

    // STATUS success code
    private static final int STATUS_SUCCESS_CODE = 1;

    // STATUS fail code
    private static final int STATUS_FAIL_CODE = 2;

    // STATUS error code
    private static final int STATUS_ERROR_CODE = 3;

    // STATUS undefined code
    private static final int STATUS_UNDEFINED_CODE = 0;

    ////////////////////////////////////////////////////////

    // tag codes

    //public
    public static final String TAG_UNDEFINED = "undefined";

    //user
    public static final String TAG_LOGIN_USER = "login_user";
    public static final String TAG_LOGOUT_USER = "logout_user";
    public static final String TAG_REGISTER_USER = "register_user";
    public static final String TAG_EDIT_USER = "edit_user";
    public static final String  TAG_DELETE_USER = "delete_user";
    public static final String TAG_REMOVE_PHONE_CODE_USER = "remove_phone_code";


    //tag password
    public static final String TAG_RESET_PASSWORD = "reset_password";
    public static final String TAG_FORGET_PASSWORD= "forget_password";

    //company
    public static final String TAG_INDEX_COMPANY = "index_company";
    public static final String TAG_STORE_COMPANY = "store_company";
    public static final String TAG_ADD_MEMBER_COMPANY = "add_member_company";
    public static final String TAG_LIST_MEMBERS_COMPANY = "list_members_company";
    public static final String TAG_EDIT_COMPANY = "edit_company";
    public static final String TAG_DELETE_COMPANY = "delete_company";
    public static final String TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY = "change_self_roll_call_user_company";


    //mission
    public static final String TAG_INDEX_MISSION = "index_mission";
    public static final String TAG_STORE_MISSION = "store_mission";
    public static final String TAG_EDIT_MISSION = "edit_mission";
    public static final String TAG_DELETE_MISSION = "delete_mission";
    public static final String TAG_LIST_MEMBERS_MISSION = "list_members_mission";

    //track
    public static final String TAG_STORE_TRACK = "store_track";
    public static final String TAG_INDEX_TRACK = "index_track";
    public static final String TAG_LIST_TRACK = "list_track";
    public static final String TAG_DELETE_TRACK  = "delete_track";
    public static final String TAG_GENERATE_TRACK  = "generate_track";
    public static final String TAG_STORE_BULK_TRACK = "store_bulk_track";



    //attendance
    public static final String TAG_STORE_ATTENDANCE = "store_attendance";
    public static final String TAG_STORE_MANUAL_ATTENDANCE = "store_attendance_manual";
    public static final String TAG_EDIT_ATTENDANCE = "edit_attendance";
    public static final String TAG_DELETE_ATTENDANCE = "delete_attendance";
    public static final String TAG_INDEX_ATTENDANCE = "index_attendance";
    public static final String TAG_STORE_AUTO_CEO_ATTENDANCE = "store_attendance_ceo_auto";
    public static final String TAG_STORE_SELF_LOCATION_ATTENDANCE = "store_attendance_self_location";



    //module
    public static final String TAG_INDEX_COMPANY_MODULE = "index_company_module";
    public static final String TAG_INDEX_USER_MODULE = "index_user_module";

    //company user module
    public static final String TAG_STORE_COMPANY_USER_MODULE = "store_company_user_module";


    //country
    public static final String TAG_INDEX_COUNTRY = "index_country";

    //payment
    public static final String TAG_INDEX_PAYMENT = "index_payment";
    public static final String TAG_KEY_PAYMENT = "key_payment";
    public static final String TAG_STORE_PAYMENT = "store_payment";

    //Language
    public static final String TAG_INDEX_LANGUAGE = "index_language";

    //home
    public static final String TAG_INDEX_HOME = "index_home";
    public static final String TAG_GET_VERSION_HOME = "get_version_home";

    ////////////////////////////////////////////////////////

    // ERROR codes

    //public
    public static final int ERROR_UNDEFINED_CODE = -1;
    public static final int ERROR_ITEM_EXIST_CODE = 7;
    public static final int ERROR_INSERT_FAIL_CODE = 8;
    public static final int ERROR_TOKEN_MISMACH_CODE = 4;
    public static final int ERROR_DEFECTIVE_INFORMATION_CODE = 5;
    public static final int ERROR_TOKEN_BLACKLISTED_CODE = 6;
    public static final int ERROR_INVALID_FILE_SIZE_CODE = 12;
    public static final int ERROR_UPDATE_FAIL_CODE = 14;
    public static final int ERROR_DELETE_FAIL_CODE = 16;
    public static final int ERROR_OPERATION_FAIL_CODE = 17;
    public static final int ERROR_ITEM_NOT_EXIST_CODE = 23;
    public static final int ERROR_AUTH_FAIL_CODE = 666;
    public static final int ERROR_WRONG_PHONE_CODE = 25;


    //user
    public static final int ERROR_LOGIN_FAIL_CODE = 3;
    public static final int ERROR_REGISTER_FAIL_CODE = 2;
    public static final int ERROR_USER_EXIST_CODE = 1;
    public static final int  ERROR_UNAUTHURIZED_USER_CODE = 15;
    public static final int ERROR_EMAIL_EXIST_CODE = 22;
    public static final int ERROR_NOT_ENOUGH_CHARGE_CODE = 24;

    //company
    public static final int ERROR_COMPANY_EXIST_CODE = 9;
    public static final int ERROR_COMPANY_STORE_CODE = 10;
    public static final int ERROR_COMPANY_NOT_EXIST = 27;


    //module
    public static final int ERROR_MODULE_EXPIRE_CODE = 11;

    //track
    public static final int ERROR_TRACK_STORE_CODE = 13;
    public static final int  ERROR_TRACK_INDEX_CODE = 18;
    public static final int  ERROR_TRACK_LIST_CODE = 19;

    //password
    public static final int ERROR_INVALID_PASSWORD_CODE = 20;
    public static final int ERROR_MISMATCH_PASSWORD_CODE = 21;

    //home
    public static final int ERROR_INVALID_PUBLIC_KEY_CODE = 26;

    //attendance
    ///////////////////////////////
    public static final int ERROR_LOCATION_NOT_IN_ZONE_CODE = 28;
    public static final int ERROR_USER_NOT_ALLOWED_TO_SELF_ROLL_CODE = 29;
    public static final int ERROR_COMPANY_ZONE_NOT_DEFINED_CODE = 30;
     /////////////////////////////////////////////////////////



    public Integer getError() {
        return error;
    }

    public void setError(Integer error) {
        this.error = error;
    }

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }

    public UserModel getUser() {
        return user;
    }

    public void setUser(UserModel user) {
        this.user = user;
    }

    public String getError_msg() {
        return error_msg;
    }

    public void setError_msg(String error_msg) {
        this.error_msg = error_msg;
    }

    public String getTag() {
        return tag;
    }

    public void setTag(String tag) {
        this.tag = tag;
    }
    public Boolean getMust_logout() {
        return must_logout;
    }

    public void setMust_logout(Boolean must_logout) {
        this.must_logout = must_logout;
    }

    public List<UserModel> getUsers() {
        return users;
    }

    public void setUsers(List<UserModel> users) {
        this.users = users;
    }

    public List<CompanyModel> getCompanies() {
        return companies;
    }

    public void setCompanies(List<CompanyModel> companies) {
        this.companies = companies;
    }

    public List<TrackModel> getTracks() {
        return tracks;
    }

    public void setTracks(List<TrackModel> tracks) {
        this.tracks = tracks;
    }
    public List<Object> getItems() {
        return items;
    }

    public void setItems(List<Object> items) {
        this.items = items;
    }

    public List<ModuleModel> getModules() {
        return modules;
    }

    public void setModules(List<ModuleModel> modules) {
        this.modules = modules;
    }

    public List<CompanyUserModuleModel> getCompanyUserModules() {
        return companyUserModules;
    }

    public void setCompanyUserModules(List<CompanyUserModuleModel> companyUserModules) {
        this.companyUserModules = companyUserModules;
    }

    public List<CountryModel> getCountries() {
        return countries;
    }

    public void setCountries(List<CountryModel> countries) {
        this.countries = countries;
    }

    public Boolean getBoolianItem() {
        return boolianItem;
    }

    public void setBoolianItem(Boolean boolianItem) {
        this.boolianItem = boolianItem;
    }

    public Object getItem() {
        return item;
    }

    public void setItem(Object item) {
        this.item = item;
    }

    /////////////////////////////////////////////
    private Integer error;
    private String token;
    private String error_msg;
    private String tag;
    private Boolean must_logout;
    private Boolean boolianItem;
    //////
    private UserModel user;
    private List<UserModel> users;
    private List<CompanyModel> companies;
    private List<TrackModel> tracks;
    private List<ModuleModel> modules;
    private List<CompanyUserModuleModel> companyUserModules;
    private List<CountryModel> countries;
    private List<Object> items;
    private Object item;

    ///////////////////////////////////////
    private Context cntx;

    ////////////////////////////////
    public RequestRespondModel(Context cntx) {
        this.error = null;
        this.error_msg = null;
        this.user = null;
        this.tag = null;
        this.token = null;
        this.users = new ArrayList<>();
        this.companies = new ArrayList<>();
        this.tracks = new ArrayList<>();
        this.countries = new ArrayList<>();
        this.must_logout = false;

        this.cntx = cntx;
    }

    public void decodeJsonResponse(String response)
    {
        try
        {
            JSONObject jObj = new JSONObject(response);
            this.error = jObj.getInt("error");


            // Check for error node in json
            if (error == 0)
            {
                this.setTag( jObj.getString("tag"));
                switch (this.getTag().toLowerCase())
                {
                    case TAG_LOGIN_USER:
                    {
                        // user successfully logged in
                        // Now store the user in SQLite
                        this.token = jObj.getString("token");

                        this.boolianItem = new Boolean(jObj.getString("phone_registered"));

                        JSONObject user = jObj.getJSONObject("user");
                        this.user = new UserModel();
                        this.user.setId(new BigInteger(user.getString("user_id")));
                        this.user.setGuid(user.getString("user_guid"));
                        this.user.setName(user.getString("name"));
                        this.user.setFamily(user.getString("family"));
                        this.user.setUserName(user.getString("user_name"));
                        this.user.setUserType(new Integer(user.getString("user_type_id")));
                        this.user.setCode(user.getString("code"));
                        this.user.setPayment(user.getString("payment"));
                        this.user.setBalance(user.getString("balance"));
                        this.user.setEmail(user.getString("email"));
                        this.user.setCountryId(new BigInteger((user.isNull( "country_id" )?"0":user.getString("country_id"))));
                        this.user.setGender((user.isNull( "gender" )?"0":user.getString("gender")));
                        if(!user.isNull( "image" ))
                        {
                            this.user.setProfilePicture(Utility.getBitmapImage(user.getString("image")));
                        }
                        this.error = null;
                        break;
                    }
                    case TAG_LIST_MEMBERS_COMPANY:
                    {
                        // user successfully logged in
                        // Now store the user in SQLite
                        this.token = jObj.getString("token");

                        JSONArray users = jObj.getJSONArray("users");
                        UserModel user;
                        this.users = new ArrayList<>();
                       for(int i = 0; i <users.length(); i++ )
                        {
                            user = new UserModel();
                            JSONObject item = users.getJSONObject(i);
                            user.setName(item.getString("name"));
                            user.setFamily(item.getString("family"));
                            user.setEmail(item.getString("email"));
                            user.setUserName(item.getString("user_name"));
                            user.setCode(item.getString("code"));
                            user.setUserType(new Integer(item.getString("user_type_id")));
                            user.setId(new BigInteger(item.getString("user_id")));
                            user.setGuid(item.getString("user_guid"));
                            user.setGender((item.isNull( "gender" )?"0":item.getString("gender")));
                            user.setSelfRollCallAllowed(item.getString("self_roll_call") == "1"? true:false);
                            if(!item.isNull( "image" ))
                            {
                                user.setProfilePicture(Utility.getBitmapImage(item.getString("image")));
                            }
                            this.users.add(user);


                        }

                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_REGISTER_USER:
                    {
                        // user successfully registered
                        // Now store the user in SQLite

                        this.error = null;
                        break;
                    }
                    case TAG_EDIT_USER:
                    {
                        // edit user successfully in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_DELETE_USER:
                    {
                        // user successfully deleted
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_INDEX_COMPANY:
                    {
                        this.token = jObj.getString("token");

                        JSONArray companies = jObj.getJSONArray("companies");
                        CompanyModel company;
                        this.companies = new ArrayList<>();
                        for(int i = 0; i <companies.length(); i++ )
                        {
                            company = new CompanyModel();
                            JSONObject item = companies.getJSONObject(i);
                            company.setName(item.getString("name"));
                            company.setCompanyId(new BigInteger(item.getString("company_id")));
                            company.setCompanyGuid(item.getString("company_guid"));
                            company.setTimeZone(item.getString("time_zone"));
                            if(!item.isNull( "image" ))
                            {
                                company.setCompanyPicture(Utility.getBitmapImage(item.getString("image")));
                            }
                            this.companies.add(company);

                        }
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_STORE_COMPANY:
                    {
                        // track point successfully stored in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_STORE_TRACK:
                    {
                        // track point successfully stored in server
                        this.error = null;
                        break;
                    }
                    case TAG_STORE_BULK_TRACK:
                    {
                        // track points successfully stored in server
                        this.error = null;
                        break;
                    }
                    case TAG_GENERATE_TRACK:
                    {
                        // track point successfully stored in server
                        this.token = jObj.getString("token");
                        this.items = new ArrayList<>();
                        this.items.add(0,jObj.getString("track_group"));
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_ADD_MEMBER_COMPANY:
                    {
                        // member for company successfully stored in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_DELETE_COMPANY:
                    {
                        // member for company successfully stored in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_EDIT_COMPANY:
                    {
                        // member for company successfully stored in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_INDEX_MISSION: {
                    // user successfully logged in
                    // Now store the user in SQLite
                    this.token = jObj.getString("token");

                    JSONArray missions = jObj.getJSONArray("missions");
                    MissionModel mission;
                    this.items = new ArrayList<>();
                    for (int i = 0; i < missions.length(); i++) {
                        mission = new MissionModel();
                        JSONObject item = missions.getJSONObject(i);
                        mission.setMissionId(new BigInteger(item.getString("mission_id")));
                        mission.setMissionGuid(item.getString("mission_guid"));
                        mission.setTitle(item.getString("title"));
                        mission.setDescription(item.getString("description"));
                        mission.setStartDateTime(item.getString("start_date_time"));
                        mission.setEndDateTime(item.getString("end_date_time"));
                        this.items.add(mission);
                    }

                    this.error = this.isTokenValid() == true ? 0 : ERROR_TOKEN_MISMACH_CODE;

                    break;
                }
                    case TAG_LIST_MEMBERS_MISSION:
                    {
                        // user successfully logged in
                        // Now store the user in SQLite
                        this.token = jObj.getString("token");

                        JSONArray users = jObj.getJSONArray("users");
                        UserModel user;
                        this.users = new ArrayList<>();
                        for(int i = 0; i <users.length(); i++ )
                        {
                            user = new UserModel();
                            JSONObject item = users.getJSONObject(i);
                            user.setName(item.getString("name"));
                            user.setFamily(item.getString("family"));
                            user.setEmail(item.getString("email"));
                            user.setUserName(item.getString("user_name"));
                            user.setCode(item.getString("code"));
                            user.setUserType(new Integer(item.getString("user_type_id")));
                            user.setId(new BigInteger(item.getString("user_id")));
                            user.setGuid(item.getString("user_guid"));
                            user.setGender((item.isNull( "gender" )?"0":item.getString("gender")));
                            if(!item.isNull( "image" ))
                            {
                                user.setProfilePicture(Utility.getBitmapImage(item.getString("image")));
                            }
                            this.users.add(user);


                        }

                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_DELETE_MISSION:
                    {
                        // delete mission successfully from server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_EDIT_MISSION:
                    {
                        // edit mission successfully in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_STORE_MISSION:
                    {
                        // mission successfully stored in server
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_INDEX_TRACK:
                    {
                        this.token = jObj.getString("token");

                        JSONArray tracks = jObj.getJSONArray("tracks");
                        TrackModel track;
                        this.tracks = new ArrayList<>();
                        for(int i = 0; i <tracks.length(); i++ )
                        {
                            track = new TrackModel();
                            JSONObject item = tracks.getJSONObject(i);
                            track.setTrackGroup(item.getString("track_group"));

                            this.tracks.add(track);

                        }
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_LIST_TRACK:
                    {
                        this.token = jObj.getString("token");

                        JSONArray tracks = jObj.getJSONArray("tracks");
                        TrackModel track;
                        this.tracks = new ArrayList<>();
                        for(int i = 0; i <tracks.length(); i++ )
                        {
                            track = new TrackModel();
                            JSONObject item = tracks.getJSONObject(i);
                            track.setTrackId(new BigInteger(item.getString("track_id")));
                            track.setTrackGuid(item.getString("track_guid"));
                            track.setUserId(new BigInteger(item.getString("user_id")));
                            track.setTrackGroup(item.getString("track_group"));
                            track.setLongitude(new Double(item.getString("longitude")));
                            track.setLatitude(new Double(item.getString("latitude")));
                            track.setBatteryPower(new Integer(item.getString("battery_power")));
                            track.setSignalPower(new Integer(item.getString("signal_power")));
                            track.setChargeStatus(new Integer(item.getString("charge_status")));
                            track.setBatteryStatus(new Integer(item.getString("battery_status")));
                            track.setChargeType(new Integer(item.getString("charge_type")));
                            track.setCreatedAt(item.getString("created_at"));
                            track.setUpdatedAt(item.getString("updated_at"));


                            //not Completed//////////

                            this.tracks.add(track);

                        }
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_STORE_ATTENDANCE:
                    {
                        // attendance successfully stored in server
                        this.token = jObj.getString("token");

                        this.item = jObj.getString("status");
                        JSONObject user = jObj.getJSONObject("user");
                        this.user = new UserModel();
                        this.user.setName(user.getString("name"));
                        this.user.setFamily(user.getString("family"));
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_STORE_AUTO_CEO_ATTENDANCE:
                    {
                        // attendance successfully stored in server
                        this.token = jObj.getString("token");
                        this.item = jObj.getString("status");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_STORE_SELF_LOCATION_ATTENDANCE:
                    {
                        // attendance successfully stored in server
                        this.token = jObj.getString("token");
                        this.item = jObj.getString("status");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_STORE_MANUAL_ATTENDANCE:
                    {
                        // attendance successfully stored in server
                        this.token = jObj.getString("token");

                        JSONObject user = jObj.getJSONObject("user");
                        this.user = new UserModel();
                        this.user.setName(user.getString("name"));
                        this.user.setFamily(user.getString("family"));

                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_DELETE_ATTENDANCE:
                    {
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_EDIT_ATTENDANCE:
                    {
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_INDEX_ATTENDANCE:
                    {
                        this.token = jObj.getString("token");

                        JSONArray attendances = jObj.getJSONArray("attendances");
                        AttendanceModel attendance;
                        this.items = new ArrayList<>();
                        for(int i = 0; i <attendances.length(); i++ )
                        {
                            attendance = new AttendanceModel();
                            JSONObject item = attendances.getJSONObject(i);
                            attendance.setAttendanceId(new BigInteger(item.getString("attendance_id")));
                            attendance.setAttendanceGuid(item.getString("attendance_guid"));
                            attendance.setUserCompanyId(new BigInteger(item.getString("user_company_id")));
                            attendance.setStartDateTime(item.getString("start_date_time"));
                            attendance.setEndDateTime(item.getString("end_date_time"));
                            attendance.setMission(new Boolean(item.getString("is_mission")));
//                            attendance.setCreatedAt(item.getString("created_at"));
//                            attendance.setUpdatedAt(item.getString("updated_at"));


                            //not Completed//////////

                            this.items.add(attendance);

                        }
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_INDEX_USER_MODULE: {
                        // user successfully logged in
                        // Now store the user in SQLite
                        this.token = jObj.getString("token");

                        JSONArray modules = jObj.getJSONArray("modules");
                        ModuleModel module;
                        this.modules = new ArrayList<ModuleModel>();
                        for (int i = 0; i < modules.length(); i++) {
                            module = new ModuleModel();
                            JSONObject item = modules.getJSONObject(i);
                            module.setModuleId(new BigInteger(item.getString("module_id")));
                            module.setLimitValue(new Integer(item.getString("limit_value")));
                            module.setStatus(new Integer(item.getString("status")));
                            module.setPurchased(item.getString("purchased"));
                            module.setStored(item.getString("stored"));
                            module.setPrice(new Float(item.getString("price")));
                            module.setTitle(item.getString("title"));
                            module.setDescription(item.getString("description"));

                            this.modules.add(module);
                        }

                        this.error = this.isTokenValid() == true ? 0 : ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_INDEX_COMPANY_MODULE: {
                        // user successfully logged in
                        // Now store the user in SQLite
                        this.token = jObj.getString("token");

                        JSONArray modules = jObj.getJSONArray("modules");
                        ModuleModel module;
                        this.modules = new ArrayList<ModuleModel>();
                        for (int i = 0; i < modules.length(); i++) {
                            module = new ModuleModel();
                            JSONObject item = modules.getJSONObject(i);
                            module.setModuleId(new BigInteger(item.getString("module_id")));
                            module.setLimitValue(new Integer(item.getString("limit_value")));
                            module.setStatus(new Integer(item.getString("status")));
                            module.setPurchased(item.getString("purchased"));
                            module.setStored(item.getString("stored"));
                            module.setPrice(new Float(item.getString("price")));
                            module.setTitle(item.getString("title"));
                            module.setDescription(item.getString("description"));

                            this.modules.add(module);
                        }

                        this.error = this.isTokenValid() == true ? 0 : ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_RESET_PASSWORD:
                    {
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_FORGET_PASSWORD:
                    {
                        this.error = null;
                        break;
                    }
                    case TAG_INDEX_COUNTRY:
                    {

                        JSONArray countries = jObj.getJSONArray("countries");
                        CountryModel country;
                        this.countries = new ArrayList<>();
                        for(int i = 0; i <countries.length(); i++ )
                        {
                            country = new CountryModel();
                            JSONObject item = countries.getJSONObject(i);
                            country.setName(item.getString("name"));
                            country.setCountryId(new BigInteger(item.getString("country_id")));

                            this.countries.add(country);

                        }

                        break;
                    }
                    case TAG_INDEX_PAYMENT: {
                        // user successfully logged in
                        // Now store the user in SQLite
                        this.token = jObj.getString("token");

                        JSONArray payments = jObj.getJSONArray("payments");
                        PaymentModel payment;
                        this.items = new ArrayList<>();
                        for (int i = 0; i < payments.length(); i++) {
                            payment = new PaymentModel();
                            JSONObject item = payments.getJSONObject(i);
                            payment.setPaymentId(new BigInteger(item.getString("payment_id")));
                            payment.setAmount(item.getString("amount"));
                            payment.setDescription(item.getString("description"));
                            payment.setPayload(item.getString("authority"));
                            payment.setStatus(new Integer(item.getString("status")));

                            this.items.add(payment);
                        }

                        this.error = this.isTokenValid() == true ? 0 : ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_INDEX_LANGUAGE:
                    {
                        JSONArray languages = jObj.getJSONArray("languages");
                        LanguageModel language;
                        this.items = new ArrayList<>();
                        for(int i = 0; i <languages.length(); i++ )
                        {
                            language = new LanguageModel();
                            JSONObject item = languages.getJSONObject(i);
                            language.setTitle(item.getString("title"));
                            language.setLanguageId(new BigInteger(item.getString("language_id")));
                            language.setLanguageDirection(new Boolean(item.getString("language_direction")));
                            language.setCode(item.getString("code"));

                            this.items.add(language);

                        }
                        break;
                    }
                    case TAG_STORE_COMPANY_USER_MODULE:
                    {
                        // company_user_module successfully stored

                        this.token = jObj.getString("token");

                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_GET_VERSION_HOME:
                    {
                        this.items = new ArrayList<>();

                        this.items.add( jObj.getString("version"));
                        this.items.add( jObj.getString("link"));
                        this.items.add( jObj.getString("min_time_interval"));
                        this.items.add( jObj.getString("min_distance_interval"));
                        this.items.add( jObj.getString("server_check_interval"));
                        this.items.add( jObj.getString("message"));

                        SessionModel session = new SessionModel(cntx);
                        session.saveItem("min_time_interval",jObj.getString("min_time_interval"));
                        session.saveItem("min_distance_interval",jObj.getString("min_distance_interval"));


                        break;
                    }
                    case TAG_INDEX_HOME:
                    {
                        this.token = jObj.getString("token");
                        this.items = new ArrayList<>();

                        this.items.add( jObj.getString("qr_code"));
                        this.items.add( jObj.getString("start_date_time"));
                        this.items.add( jObj.getString("company_name"));

                        JSONObject user = jObj.getJSONObject("user");
                        this.user = new UserModel();
                        this.user.setId(new BigInteger(user.getString("user_id")));
                        this.user.setGuid(user.getString("user_guid"));
                        this.user.setName(user.getString("name"));
                        this.user.setFamily(user.getString("family"));
                        this.user.setUserName(user.getString("user_name"));
                        this.user.setUserType(new Integer(user.getString("user_type_id")));
                        this.user.setCode(user.getString("code"));
                        this.user.setPayment(user.getString("payment"));
                        this.user.setBalance(user.getString("balance"));
                        this.user.setEmail(user.getString("email"));
                        this.user.setCountryId(new BigInteger((user.isNull( "country_id" )?"0":user.getString("country_id"))));
                        this.user.setGender((user.isNull( "gender" )?"0":user.getString("gender")));
                        if(!user.isNull( "image" ))
                        {
                            this.user.setProfilePicture(Utility.getBitmapImage(user.getString("image")));
                        }

                        JSONArray tmp_users = jObj.getJSONArray("users_attendances");
                        UserModel usert;
                        this.users = new ArrayList<UserModel>();
                        for(int i = 0; i <tmp_users.length(); i++ )
                        {
                            usert = new UserModel();
                            JSONObject item = tmp_users.getJSONObject(i);
                            usert.setName(item.getString("user_first_name"));
                            usert.setFamily(item.getString("user_last_name"));
                            usert.setUserType(new Integer(item.getString("user_type")));
                            usert.setUserCompanyName(item.getString("company_name"));
                            usert.setUserAttendanceStartDate(item.getString("start_date_time"));
                            usert.setUserAttendanceEndDate(item.getString("end_date_time"));
                            usert.setId(new BigInteger(item.getString("user_id")));
                            usert.setGuid(item.getString("user_guid"));

                            this.users.add(usert);

                        }
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_DELETE_TRACK:
                    {
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_KEY_PAYMENT:
                    {
                        this.items = new ArrayList<>();

                        this.items.add( jObj.getString("public_key"));
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_STORE_PAYMENT:
                    {

                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;

                        break;
                    }
                    case TAG_REMOVE_PHONE_CODE_USER:
                    {
                        // user phone code successfully deleted
                        this.token = jObj.getString("token");
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    case TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY:
                    {
                        // user phone code successfully deleted
                        this.token = jObj.getString("token");
//                        this.items = new ArrayList<>();
//                        this.items.add(0,jObj.getString("self_roll_call"));
                        this.error = this.isTokenValid() == true?0:ERROR_TOKEN_MISMACH_CODE;
                        break;
                    }
                    default:
                        break;
                }

            }
            else
            {
                // Error in login. Get the error message
                this.error_msg = this.getErrorCodeMessage(new Integer(jObj.getString("error")));

                LogModel log = new LogModel();
                log.setErrorMessage("message: "+ this.error_msg+" CallStack: non");
                log.setContollerName(this.getClass().getName());
                if(new SessionModel(cntx).isLoggedIn())
                    log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
                log.insert();
            }

        }
        catch (JSONException e)
        {
            // JSON error
            LogModel log = new LogModel();
            log.setErrorMessage("message: "+ e.getMessage()+" CallStack: non");
            log.setContollerName(this.getClass().getName());
            log.setUserId(BigInteger.valueOf(Long.parseLong(new SessionModel(cntx).getUserDetails().get(ir.fardan7eghlim.attentra.models.SessionModel.KEY_ID).toString())));
            log.insert();
        }

    }

    public boolean isTokenValid()
    {
        SessionModel session = new SessionModel(cntx);
        if(this.getToken().isEmpty() || this.getToken() == null)
        {
            this.must_logout = true;
            session.logoutUser(true);
            return false;
        }
        else
        {
            if(session.getStoredToken().equals(this.getToken()))
            {
               //do nothing
            }
            else
            {
                session.setToken(this.token);
            }
            return true;
        }
    }

    public String getErrorCodeMessage(int errorCode)
    {
        String result = "";
        switch (errorCode)
        {
            case ERROR_ITEM_EXIST_CODE:
                result = cntx.getResources().getString(R.string.error_item_exist);
                break;
            case ERROR_INSERT_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_insert_fail);;
                break;
            case ERROR_USER_EXIST_CODE:
                result = cntx.getResources().getString(R.string.error_user_exist);
                break;
            case ERROR_REGISTER_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_register_fail);;
                break;
            case ERROR_TOKEN_MISMACH_CODE:
                result = cntx.getResources().getString(R.string.error_token_mismach);;
                break;
            case ERROR_LOGIN_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_login_fail);;
                break;
            case ERROR_DEFECTIVE_INFORMATION_CODE:
                result = cntx.getResources().getString(R.string.error_defective_information);
                break;
            case ERROR_TOKEN_BLACKLISTED_CODE:
                result = cntx.getResources().getString(R.string.error_token_blacklisted);
                break;
            case ERROR_COMPANY_EXIST_CODE:
                result = cntx.getResources().getString(R.string.error_company_exist);
                break;
            case ERROR_COMPANY_STORE_CODE:
                result = cntx.getResources().getString(R.string.error_company_store);
                break;
            case ERROR_MODULE_EXPIRE_CODE:
                result = cntx.getResources().getString(R.string.error_module_expire);
                break;
            case ERROR_INVALID_FILE_SIZE_CODE:
                result = cntx.getResources().getString(R.string.error_invalid_file_size);
                break;
            case ERROR_TRACK_STORE_CODE:
                result = cntx.getResources().getString(R.string.error_track_store);
                break;
            case ERROR_UPDATE_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_update_fail);
                break;
            case ERROR_UNAUTHURIZED_USER_CODE:
                result = cntx.getResources().getString(R.string.error_unauthorized_user);
                break;
            case ERROR_DELETE_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_delete_fail);
                break;
            case ERROR_OPERATION_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_operation_fail);
                break;
            case ERROR_TRACK_INDEX_CODE:
                result = cntx.getResources().getString(R.string.error_track_index);
                break;
            case ERROR_TRACK_LIST_CODE:
                result = cntx.getResources().getString(R.string.error_track_list);
                break;
            case ERROR_INVALID_PASSWORD_CODE:
                result = cntx.getResources().getString(R.string.error_invalid_password);
                break;
            case ERROR_MISMATCH_PASSWORD_CODE:
                result = cntx.getResources().getString(R.string.error_mismatch_password);
                break;
            case ERROR_ITEM_NOT_EXIST_CODE:
                result = cntx.getResources().getString(R.string.error_item_not_exist);
                break;
            case ERROR_NOT_ENOUGH_CHARGE_CODE:
                result = cntx.getResources().getString(R.string.error_not_enough_charge);
                break;
            case ERROR_AUTH_FAIL_CODE:
                result = cntx.getResources().getString(R.string.error_auth_fail);
                break;
            case ERROR_WRONG_PHONE_CODE:
                result = cntx.getResources().getString(R.string.error_wrong_phone_code);
                break;
            case ERROR_INVALID_PUBLIC_KEY_CODE:
                result = cntx.getResources().getString(R.string.error_invalid_piblic_key);
                break;
            case ERROR_EMAIL_EXIST_CODE:
                result = cntx.getResources().getString(R.string.error_invalid_email);
                break;
            case ERROR_COMPANY_NOT_EXIST:
                result = cntx.getResources().getString(R.string.msg_company_not_exist);
                break;
            case ERROR_LOCATION_NOT_IN_ZONE_CODE:
                result = cntx.getResources().getString(R.string.msg_location_not_in_zone);
                break;
            case ERROR_USER_NOT_ALLOWED_TO_SELF_ROLL_CODE:
                result = cntx.getResources().getString(R.string.msg_ErrorUserNotAllowedToSelfRoll);
                break;
            case ERROR_COMPANY_ZONE_NOT_DEFINED_CODE:
                result = cntx.getResources().getString(R.string.msg_ErrorCompanyZoneIsNotSet);
                break;

            default:
                result = cntx.getResources().getSystem().getString(R.string.error_undefined);;
                break;
        }

        return result;


    }
}
