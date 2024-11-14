package ir.fardan7eghlim.attentra.utils;

/**
 * Created by Meysam on 2/16/2017.
 */

public class AppConfig {

    ////////////////////////////////////////////////////////////////
//    private static final String Host_IP = "http://136.243.120.147/~attentra";
    private static final String Host_IP = "http://attentra.ir";
//    private static final String Host_IP = "http://10.0.2.2:4797";
//    private static final String Host_IP = "http://192.168.1.51:4797/attentra/public";
    ////////////////////////////////////////////////////////////////
    // Server user login url
    private static final String URL_LOGIN = "/api/user/apiLogin";

    // Server user logout url
    private static final String URL_LOGOUT= "/api/user/apiLogout";

    // Server user register url
    private static final String URL_REGISTER = "/api/user/apiRegister";

    // Server user register url
    private static final String URL_USER_EDIT = "/api/user/apiUpdate";

    // Server company index url
    private static final String URL_COMPANY_INDEX = "/api/company/apiIndex";

    // Server company index url
    private static final String URL_COMPANY_ADD_MEMBER = "/api/company/apiAddCompanyMember";

    // Server company index url
    private static final String URL_TRACK_STORE = "/api/track/apiStore";

    // Server company list member
    private static final String URL_COMPANY_LIST_MEMBERS = "/api/company/apiListMembers";

    // Server company edit
    private static final String URL_COMPANY_EDIT = "/api/company/apiUpdate";

    // Server company delete
    private static final String URL_COMPANY_DELETE = "/api/company/apiDelete";

    // Server user delete
    private static final String URL_USER_DELETE = "/api/user/apiDelete";

    // Server mission index
    private static final String URL_MISSION_INDEX = "/api/mission/apiIndex";

    // Server mission index
    private static final String URL_MISSION_STORE = "/api/mission/apiStore";

    // Server company store url
    private static final String URL_COMPANY_STORE = "/api/company/apiStore";

    // Server track index
    private static final String URL_TRACK_INDEX = "/api/track/apiIndex";

    // Server track list
    private static final String URL_TRACK_LIST = "/api/track/apiList";

    // Server track generate
    private static final String URL_TRACK_GENERATE = "/api/track/apiGenerate";

    // Server edit mission
    private static final String URL_MISSION_EDIT = "/api/mission/apiUpdate";

    // Server mission delete
    private static final String URL_MISSION_DELETE = "/api/mission/apiDelete";

    // Server list members mission
    private static final String URL_MISSION_LIST_MEMBERS = "/api/mission/apiListMembers";

    // Server attendance store url
    private static final String URL_ATTENDANCE_STORE = "/api/attendance/apiStore";

    // Server attendance store manual url
    private static final String URL_ATTENDANCE_MANUAL_STORE = "/api/attendance/apiStoreManual";

    // Server attendance edit url
    private static final String URL_ATTENDANCE_EDIT = "/api/attendance/apiEdit";

    // Server attendance delete url
    private static final String URL_ATTENDANCE_DELETE = "/api/attendance/apiDelete";

    // Server attendance index url
    private static final String URL_ATTENDANCE_INDEX = "/api/attendance/apiIndex";

    // Server user module index url
    private static final String URL_USER_MODULE_INDEX = "/api/module/apiUserModuleIndex";

    // Server company module index url
    private static final String URL_COMPANY_MODULE_INDEX = "/api/module/apiCompanyModuleIndex";

    // Server company user module store url
    private static final String URL_COMPANY_USER_MODULE_STORE = "/api/companyusermodule/apiStore";

    // Server reset password url
    private static final String URL_RESET_PASSWORD = "/api/user/apiPasswordReset";

    // Server forget password url
    private static final String URL_FORGOT_PASSWORD = "/api/user/apiPasswordForget";

    // Server country index url
    private static final String URL_COUNTRY_INDEX = "/api/country/apiIndex";

    // Server payment index url
    private static final String URL_PAYMENT_INDEX = "/api/payment/apiIndex";

    // Server language index url
    private static final String URL_LANGUAGE_INDEX = "/api/language/apiIndex";

    // Server get version url
    private static final String URL_HOME_GET_VERSION = "/api/home/apiGetVersion";

    // Server get home url
    private static final String URL_HOME_INDEX = "/api/home/apiIndex";

    // Server track list
    private static final String URL_TRACK_DELETE = "/api/track/apiDelete";


    // Server attendance store auto for ceo url
    private static final String URL_ATTENDANCE_CEO_AUTO_STORE = "/api/attendance/apiStoreAutoCeo";


    // Server get public key
    private static final String URL_PAYMENT_KEY = "/api/payment/apiKey";

    // Server store payment
    private static final String URL_PAYMENT_STORE = "/api/payment/apiStore";

    // Server remove phone code
    private static final String URL_REMOVE_PHONE_CODE = "/api/user/apiRemovePhoneCode";

    // Server attendance store self location url
    private static final String URL_ATTENDANCE_SELF_LOCATION_STORE = "/api/attendance/apiStoreSelfLocation";

    // Server attendance store self location url
    private static final String URL_USER_COMPANY_SELF_ROLL_CALL_CHANGE = "/api/company/apiChangeSelfRollCall";

    // addresses
    public static final String LoginAddress = AppConfig.Host_IP + AppConfig.URL_LOGIN;
    public static final String RegisterAddress = AppConfig.Host_IP + AppConfig.URL_REGISTER;
    public static final String LogoutAddress = AppConfig.Host_IP + AppConfig.URL_LOGOUT;
    public static final String CompanyIndexAddress = AppConfig.Host_IP + AppConfig.URL_COMPANY_INDEX;
    public static final String CompanyAddMember = AppConfig.Host_IP + AppConfig.URL_COMPANY_ADD_MEMBER;
    public static final String TrackStore = AppConfig.Host_IP + AppConfig.URL_TRACK_STORE;
    public static final String CompanyListMembers = AppConfig.Host_IP + AppConfig.URL_COMPANY_LIST_MEMBERS;
    public static final String CompanyEdit = AppConfig.Host_IP + AppConfig.URL_COMPANY_EDIT;
    public static final String CompanyDelete = AppConfig.Host_IP + AppConfig.URL_COMPANY_DELETE;
    public static final String UserDelete = AppConfig.Host_IP + AppConfig.URL_USER_DELETE;
    public static final String MissionIndex = AppConfig.Host_IP + AppConfig.URL_MISSION_INDEX;
    public static final String MissionStore = AppConfig.Host_IP + AppConfig.URL_MISSION_STORE;
    public static final String CompanyStore = AppConfig.Host_IP + AppConfig.URL_COMPANY_STORE;
    public static final String TrackIndex = AppConfig.Host_IP + AppConfig.URL_TRACK_INDEX;
    public static final String TrackList = AppConfig.Host_IP + AppConfig.URL_TRACK_LIST;
    public static final String TrackGenerate = AppConfig.Host_IP + AppConfig.URL_TRACK_GENERATE;
    public static final String MissionEdit = AppConfig.Host_IP + AppConfig.URL_MISSION_EDIT;
    public static final String MissionDelete = AppConfig.Host_IP + AppConfig.URL_MISSION_DELETE;
    public static final String MissionListMembers = AppConfig.Host_IP + AppConfig.URL_MISSION_LIST_MEMBERS;
    public static final String AttendanceStore = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_STORE;
    public static final String UserEdit = AppConfig.Host_IP + AppConfig.URL_USER_EDIT;
    public static final String AttendanceStoreManual = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_MANUAL_STORE;
    public static final String AttendanceEdit = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_EDIT;
    public static final String AttendanceDelete = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_DELETE;
    public static final String AttendanceIndex = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_INDEX;
    public static final String UserModuleIndex = AppConfig.Host_IP + AppConfig.URL_USER_MODULE_INDEX;
    public static final String CompanyModuleIndex = AppConfig.Host_IP + AppConfig.URL_COMPANY_MODULE_INDEX;
    public static final String CompanyUserModuleStore = AppConfig.Host_IP + AppConfig.URL_COMPANY_USER_MODULE_STORE;
    public static final String ResetPassword = AppConfig.Host_IP + AppConfig.URL_RESET_PASSWORD;
    public static final String ForgotPassword = AppConfig.Host_IP + AppConfig.URL_FORGOT_PASSWORD;
    public static final String CountryIndex = AppConfig.Host_IP + AppConfig.URL_COUNTRY_INDEX;
    public static final String PaymentIndex = AppConfig.Host_IP + AppConfig.URL_PAYMENT_INDEX;
    public static final String LanguageIndex = AppConfig.Host_IP + AppConfig.URL_LANGUAGE_INDEX;
    public static final String HomeGetVersion = AppConfig.Host_IP + AppConfig.URL_HOME_GET_VERSION;
    public static final String HomeIndex = AppConfig.Host_IP + AppConfig.URL_HOME_INDEX;
    public static final String TrackDelete = AppConfig.Host_IP + AppConfig.URL_TRACK_DELETE;
    public static final String AttendanceCeoAutoStore = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_CEO_AUTO_STORE;
    public static final String PaymentKey = AppConfig.Host_IP + AppConfig.URL_PAYMENT_KEY;
    public static final String UserRemovePhoneCode = AppConfig.Host_IP + AppConfig.URL_REMOVE_PHONE_CODE;
    public static final String PaymentStore = AppConfig.Host_IP + AppConfig.URL_PAYMENT_STORE;
    public static final String AttendanceSelfLocationStore = AppConfig.Host_IP + AppConfig.URL_ATTENDANCE_SELF_LOCATION_STORE;
    public static final String UserCompanySelfRollCallChange = AppConfig.Host_IP + AppConfig.URL_USER_COMPANY_SELF_ROLL_CALL_CHANGE;



}
