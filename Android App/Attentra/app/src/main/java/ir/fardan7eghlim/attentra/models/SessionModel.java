package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import java.math.BigInteger;
import java.util.HashMap;
import java.util.Locale;

import ir.fardan7eghlim.attentra.models.SQLiteHandler.DatabaseHandler;
import ir.fardan7eghlim.attentra.services.track.TrackingService;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

/**
 * Created by Meysam on 12/21/2016.
 */

public class SessionModel {
    // Shared Preferences
    SharedPreferences pref;

    // Editor for Shared preferences
    SharedPreferences.Editor editor;

    // Context
    Context _context;

    // Shared pref mode
    int PRIVATE_MODE = 0;

    // Sharedpref file name
    private static final String PREF_NAME = "AttentraPref";

    // All Shared Preferences Keys
    private static final String IS_LOGIN = "IsLoggedIn";

    // UserModel name (make variable public to access from outside)
    public static final String KEY_NAME = "name";

    // UserModel name (make variable public to access from outside)
    public static final String KEY_FAMILY = "family";

    // UserModel name (make variable public to access from outside)
    public static final String KEY_USER_NAME = "userName";

    // ID (make variable public to access from outside)
    public static final String KEY_ID = "id";

    // Guid (make variable public to access from outside)
    public static final String KEY_GUID = "guid";

    // Type (make variable public to access from outside)
    public static final String KEY_TYPE = "type";

    // Email address (make variable public to access from outside)
    public static final String KEY_EMAIL = "email";

    // Token (make variable public to access from outside)
    public static final String KEY_TOKEN = "token";

    // system language
    public static final String KEY_LANGUAGE = "language";

    // system language code
    public static final String KEY_LANGUAGE_CODE = "language_code";

    // Token (make variable public to access from outside)
    public static final String KEY_TRACK_GROUP = "track_group";

    // image
    public static final String KEY_PROFILE_IMAGE = "profileImage";

    // first time visit
    public static final String KEY_FIRST_TIME_VISIT = "first_time";

    // last latitude
    public static final String KEY_LAST_LAT = "last_lat";

    // last longitode
    public static final String KEY_LAST_LON = "last_lon";

    // payment payload
    public static final String KEY_PAYMENT_PAYLOAD = "payment_payload";

    // payment token
    public static final String KEY_PAYMENT_TOKEN = "payment_token";

    // payment product code
    public static final String KEY_PAYMENT_PRODUCT_CODE = "payment_product_code";

    // payment amount
    public static final String KEY_PAYMENT_AMOUNT = "payment_amount";

    // sound play
    public static final String KEY_SOUND_PLAY = "sound_play";

    // sync date time
    public static final String KEY_SYNC_DATE_TIME = "sync_date_time";

    // sync date time
    public static final String KEY_SERVER_CHECK_INTERVAL = "server_check_interval";

    // last sync date time
    public static final String KEY_LAST_CHECK_SERVER_TIME = "last_check_server_time";

//     for one signal
//    public static final String KEY_ONE_SIGNAL_PLAYER_ID = "one_signal_player_id";

    //     for merging check in/out
    public static final String KEY_CHECK_IN_OUT_MERGE = "check_in_out_merge";

    // Constructor
    public SessionModel(Context context){
        this._context = context;
        pref = _context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
        editor = pref.edit();
    }



    /**
     * Create login session
     * */
    public void createLoginSession(String id, String guid, String type, String token){
        // Storing login value as TRUE
        editor.putBoolean(IS_LOGIN, true);

        // Storing id in pref
        editor.putString(KEY_ID, id);

        // Storing guid in pref
        editor.putString(KEY_GUID, guid);

        // Storing type in pref
        editor.putString(KEY_TYPE, type);

        // Storing type in pref
        editor.putString(KEY_TOKEN, token);

        // commit changes
        editor.commit();
    }

    /**
     * Create login session
     * */
    public void createLoginSession(UserModel user){
        // Storing login value as TRUE
        editor.putBoolean(IS_LOGIN, true);

        // Storing id in pref
        editor.putString(KEY_ID, user.getId().toString());

        // Storing guid in pref
        editor.putString(KEY_GUID, user.getGuid());

        // Storing type in pref
        editor.putString(KEY_TYPE, user.getUserType().toString());

        // Storing type in pref
        editor.putString(KEY_TOKEN, user.getToken());

        // Storing name in pref
        editor.putString(KEY_NAME, user.getName());

        // Storing family in pref
        editor.putString(KEY_FAMILY, user.getFamily());

        // Storing email in pref
        editor.putString(KEY_EMAIL, user.getEmail());

        // Storing username in pref
        editor.putString(KEY_USER_NAME, user.getUserName());


        // commit changes
        editor.commit();
    }


    /**
     * Check login method wil check user login status
     * If false it will redirect user to login page
     * Else won't do anything
     * */
    public void checkLogin(){
        // Check login status
        if(!this.isLoggedIn()){
            // user is not logged in redirect him to Login Activity
            Intent i = new Intent(_context, UserLoginActivity.class);
            // Closing all the Activities
            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            // Add new Flag to start new Activity
            i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

            // Staring Login Activity
            _context.startActivity(i);
        }

    }



    /**
     * Get stored session data
     * */
    public HashMap<String, String> getUserDetails(){
        HashMap<String, String> user = new HashMap<String, String>();
        // user name
        user.put(KEY_NAME, pref.getString(KEY_NAME, null));

        // user email id
        user.put(KEY_EMAIL, pref.getString(KEY_EMAIL, null));

        // user id
        user.put(KEY_ID, pref.getString(KEY_ID, null));


        // user guid
        user.put(KEY_GUID, pref.getString(KEY_GUID, null));


        // user type
        user.put(KEY_TYPE, pref.getString(KEY_TYPE, null));

        // return user
        return user;
    }

    /**
     * Get stored session data
     * */
    public UserModel getCurrentUser(){

        UserModel current_user = new UserModel();
        // name
        current_user.setName( pref.getString(KEY_NAME, null));


        // user email id
        current_user.setEmail( pref.getString(KEY_EMAIL, null));

        // user id
        current_user.setId(BigInteger.valueOf(Integer.parseInt( pref.getString(KEY_ID, null))));


        // user guid
        current_user.setGuid( pref.getString(KEY_GUID, null));


        // user type
        current_user.setUserType( Integer.valueOf(Integer.parseInt(pref.getString(KEY_TYPE, null))));

        // return user
        return current_user;
    }

    /**
     * Clear session details
     * */
    public void logoutUser(Boolean deleteUsersRecordsInDB){
        // Clearing all data from Shared Preferences

        //stop tracking service if is running
        if(Utility.isTrackingServiceRunning())
        {
            Intent intent = new Intent(_context, TrackingService.class);
            _context.stopService(intent);
        }



        if(deleteUsersRecordsInDB)
        {
            try
            {
                //clear sqlite user data
                DatabaseHandler db = new DatabaseHandler(this._context);
                db.deleteUsers();
            }
            catch (Exception ex)
            {
                //meysam - do nothing...
            }
        }

        // Storing login value as TRUE
        editor.remove(IS_LOGIN);

        // Storing id in pref
        editor.remove(KEY_ID);

        // Storing guid in pref
        editor.remove(KEY_GUID);

        // Storing type in pref
        editor.remove(KEY_TYPE);

        // Storing type in pref
        editor.remove(KEY_TOKEN);
        editor.commit();

    }

    /**
     * Quick check for login
     * **/
    // Get Login State
    public boolean isLoggedIn(){
        return pref.getBoolean(IS_LOGIN, false);
    }

    /**
     * Quick check for token
     * **/
    // check returned token with current session token
    public boolean checkToken(String new_token){

        if ( pref.getString(KEY_TOKEN,"").compareTo(new_token) == 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public String getStoredToken()
    {
        return pref.getString(KEY_TOKEN, null);
    }

    public void setToken(String token)
    {
        // Storing type in pref
        editor.putString(KEY_TOKEN, token);

        // commit changes
        editor.commit();
    }


    /**
     * Create login session
     * */
    public void saveLanguageCode(String language_code){
        // Storing language value as inputed
        editor.putString(KEY_LANGUAGE_CODE, language_code);

        // commit changes
        editor.commit();
    }

    /**
     * get stored language
     * */
    public String getLanguageCode(){

        Locale current = _context.getResources().getConfiguration().locale;

        // get language value
        String  lanCode = pref.getString(KEY_LANGUAGE_CODE, current.getLanguage());

        return  lanCode;
    }

    /**
     * Create login session
     * */
    public void saveItem(String itemName,Object item){
        if(item instanceof String)
        {
            editor.putString(itemName, item.toString());

        }
        else if(item instanceof Float)
        {
            editor.putFloat(itemName, new Float(item.toString()));
        }
        else if(item instanceof Double)
        {
            editor.putFloat(itemName, new Float(item.toString()));
        }
        else if(item instanceof Integer)
        {
            editor.putInt(itemName, new Integer(item.toString()));
        }
        else if(item instanceof BigInteger)
        {
            editor.putLong(itemName, (long) item);

        }
        else if(item instanceof Boolean)
        {
            editor.putBoolean(itemName, new Boolean(item.toString()));
        }
        else
        {
            editor.putString(itemName, item.toString());
        }

        // commit changes
        editor.commit();
    }

    /**
     * get stored string
     * */
    public String getStringItem(String key){

        return  pref.getString(key, null);
    }

    /**
     * get stored string
     * */
    public Boolean getBooleanItem(String key, Boolean defaultValue){

        return  pref.getBoolean(key, defaultValue);
    }


    /**
     * get stored double
     * */
    public Double getDoubleItem(String key){

        return  new Double(pref.getFloat(key,new Float(0.0)));
    }

    public String getStoredTrackGroup()
    {
        return pref.getString(KEY_TRACK_GROUP, null);
    }

    public void setStoredTrackGroup(String trackGroup)
    {
        // Storing type in pref
        editor.putString(KEY_TRACK_GROUP, trackGroup);

        // commit changes
        editor.commit();
    }

    public void removeStoredTrackGroup()
    {
        // removing type in pref
        editor.remove(KEY_TRACK_GROUP);
        editor.apply();

        // commit changes
        editor.commit();
    }

    public void removeItem(String key)
    {
        // removing type in pref
        editor.remove(key);
        editor.apply();

        // commit changes
        editor.commit();
    }

    /**
     * Create payment session
     * */
    public void savePayment(PaymentModel payment){

        // Storing id in pref
        editor.putString(KEY_PAYMENT_PAYLOAD, payment.getPayload());

        // Storing guid in pref
        editor.putString(KEY_PAYMENT_TOKEN, payment.getToken());

        // Storing type in pref
        editor.putString(KEY_PAYMENT_PRODUCT_CODE, payment.getProductCode());

        // Storing type in pref
        editor.putString(KEY_PAYMENT_AMOUNT, payment.getAmount());

        // commit changes
        editor.commit();
    }

    public void removePayment()
    {
        // removing payment
        editor.remove(KEY_PAYMENT_PRODUCT_CODE);
        editor.remove(KEY_PAYMENT_TOKEN);
        editor.remove(KEY_PAYMENT_PAYLOAD);
        editor.remove(KEY_PAYMENT_AMOUNT);
        editor.apply();

        // commit changes
        editor.commit();
    }

    public PaymentModel getPayment()
    {
        PaymentModel payment = new PaymentModel();
        payment.setPayload(pref.getString(KEY_PAYMENT_PAYLOAD, null));
        payment.setProductCode(pref.getString(KEY_PAYMENT_PRODUCT_CODE, null));
        payment.setToken(pref.getString(KEY_PAYMENT_TOKEN, null));
        payment.setAmount(pref.getString(KEY_PAYMENT_AMOUNT, null));

        return payment;
    }

    public Boolean hasPayment()
    {
        String tmp =  pref.getString(KEY_PAYMENT_PRODUCT_CODE, "");
        if(tmp == "")
            return false;
        return true;
    }

    public int getIntegerItem(String key){
        return  pref.getInt(key, 0);
    }

    public Boolean hasItem(String key)
    {
        return pref.contains(key);
    }
}
