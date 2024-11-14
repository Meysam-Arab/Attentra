package ir.fardan7eghlim.attentra.models;

/**
 * Created by Meysam on 2/16/2017.
 */
import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

import java.math.BigInteger;
import java.util.HashMap;

import ir.fardan7eghlim.attentra.utils.Utility;

public class SQLiteHandlerModel extends SQLiteOpenHelper {

    private static final String TAG = SQLiteHandlerModel.class.getSimpleName();

    // All Static variables
    // Database Version
    private static final int DATABASE_VERSION = 1;

    // Database Name
    private static final String DATABASE_NAME = "attentra_api";

    // Login table name
    private static final String TABLE_USER = "user";

    // Login Table Columns names
    private static final String KEY_ID = "id";
    private static final String KEY_NAME = "name";
    private static final String KEY_USER_NAME = "user_name";
    private static final String KEY_USER_TYPE_ID = "user_type_id";
    private static final String KEY_CODE = "code";
    private static final String KEY_PAYMENT = "payment";
    private static final String KEY_BALANCE = "balance";
    private static final String KEY_COUNTRY_ID = "country_id";
    private static final String KEY_EMAIL = "email";
    private static final String KEY_GENDER = "gender";
    private static final String KEY_UID = "uid";
    private static final String KEY_CREATED_AT = "created_at";
    private static final String KEY_UPDATED_AT = "updated_at";
    private static final String KEY_PROFILE_PICTURE = "profile_picture";



    public SQLiteHandlerModel(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    // Creating Tables
    @Override
    public void onCreate(SQLiteDatabase db) {
        String CREATE_LOGIN_TABLE = "CREATE TABLE  IF NOT EXISTS " + TABLE_USER + "("
                + KEY_ID + " INTEGER PRIMARY KEY," + KEY_NAME + " TEXT,"
                + KEY_EMAIL + " TEXT UNIQUE,"
                + KEY_USER_NAME + " TEXT UNIQUE,"
                + KEY_UID + " TEXT,"
                + KEY_USER_TYPE_ID + " INTEGER,"
                + KEY_CODE + " TEXT,"
                + KEY_PAYMENT + " REAL,"
                + KEY_BALANCE + " REAL,"
                + KEY_COUNTRY_ID + " INTEGER,"
                + KEY_GENDER + " INTEGER,"
                + KEY_PROFILE_PICTURE + " BLOB"
                + KEY_UPDATED_AT + " TEXT"
                + KEY_CREATED_AT + " TEXT" + ")";
        db.execSQL(CREATE_LOGIN_TABLE);

        Log.d("meysam", "Database tables created");
    }

    // Upgrading database
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        // Drop older table if existed
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_USER);

        // Create tables again
        onCreate(db);
    }

    /**
     * Storing user details in sqlite database
     * */
    public void addUser(UserModel user) {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put(KEY_ID, user.getId().toString()); // ID
        values.put(KEY_NAME, user.getName()); // Name
        values.put(KEY_EMAIL, user.getEmail()); // Email
        values.put(KEY_USER_NAME, user.getUserName()); // UserName
        values.put(KEY_UID, user.getGuid()); // Email
        values.put(KEY_USER_TYPE_ID, user.getUserType()); //User Type Id
        values.put(KEY_CODE, user.getCode()); //Code
        values.put(KEY_PAYMENT, user.getPayment()); //Payment
        values.put(KEY_BALANCE, user.getBalance()); //Balance
        values.put(KEY_COUNTRY_ID, (user.getCountryId() == null? null:user.getCountryId().toString())); //Country Id
        values.put(KEY_GENDER, user.getGender()); //Gender
        values.put(KEY_PROFILE_PICTURE,(user.getProfilePicture() == null?null:user.getProfilePicture().toString()) ); //Profile Picture
        values.put(KEY_UPDATED_AT, user.getUpdatedAt()); //Updated At
        values.put(KEY_CREATED_AT, user.getCreatedAt()); // Created At

        // Inserting Row
        long id = db.insert(TABLE_USER, null, values);
        db.close(); // Closing database connection

        Log.d(TAG, "New user inserted into sqlite: " + id);
    }

    /**
     * Getting user data from database
     * */
    public UserModel getUserDetails() {
        UserModel user = new UserModel();
        String selectQuery = "SELECT  * FROM " + TABLE_USER;

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);
        // Move to first row
        cursor.moveToFirst();
        if (cursor.getCount() > 0) {
            user.setId(BigInteger.valueOf(cursor.getInt(0)));
            user.setName( cursor.getString(1));
            user.setEmail( cursor.getString(2));
            user.setUserName( cursor.getString(3));
            user.setGuid( cursor.getString(4));
            user.setUserType(cursor.getInt(5));
            user.setCode(cursor.getString(6));
            user.setPayment(cursor.getString(7));
            user.setBalance(cursor.getString(8));
            user.setCountryId(BigInteger.valueOf(cursor.getInt(9)));
            user.setGender(cursor.getString(10));
            user.setProfilePicture(Utility.getBitmapImage(cursor.getString(11)));
            user.setUpdatedAt( cursor.getString(12));
            user.setCreatedAt(cursor.getString(13));
        }
        cursor.close();
        db.close();
        // return user
        Log.d(TAG, "Fetching user from Sqlite: " + user.toString());

        return user;
    }

    /**
     * Re crate database Delete all tables and create them again
     * */
    public void deleteUsers() {
        SQLiteDatabase db = this.getWritableDatabase();
        // Delete All Rows
        db.delete(TABLE_USER, null, null);
        db.close();

        Log.d(TAG, "Deleted all user info from sqlite");
    }

}
