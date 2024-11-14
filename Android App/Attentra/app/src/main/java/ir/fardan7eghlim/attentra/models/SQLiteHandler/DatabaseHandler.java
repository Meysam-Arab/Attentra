package ir.fardan7eghlim.attentra.models.SQLiteHandler;

import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.database.DatabaseErrorHandler;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.graphics.Bitmap;
import android.support.v4.content.LocalBroadcastManager;
import android.util.Log;

import java.math.BigInteger;

import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.FileProcessor;
import ir.fardan7eghlim.attentra.utils.Utility;

/**
 * Created by Meysam on 4/23/2018.
 */

public class DatabaseHandler extends SQLiteOpenHelper
{
    private Context cntx;

    // All Static variables
    // Database Version

    private static final int DATABASE_VERSION = 1;//meysam - last count added for app version 2.4.0.0

    // Database Name
    private static final String DATABASE_NAME = "attentra_api";



    public DatabaseHandler(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
        cntx=context;
    }



    public DatabaseHandler(Context context, String name, SQLiteDatabase.CursorFactory factory, int version) {
        super(context, name, factory, version);
    }

    public DatabaseHandler(Context context, String name, SQLiteDatabase.CursorFactory factory, int version, DatabaseErrorHandler errorHandler) {
        super(context, name, factory, version, errorHandler);
    }
    @Override
    public void onCreate(SQLiteDatabase db) {
        createTables(db);
    }

    public void createTables(SQLiteDatabase db)
    {

        String CREATE_USER_TABLE = "CREATE TABLE  IF NOT EXISTS " + TABLE_NAME_USERS + "("
                + KEY_USER_ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                + KEY_USER_NAME + " TEXT,"
                + KEY_USER_FAMILY + " TEXT,"
                + KEY_USER_EMAIL + " TEXT UNIQUE,"
                + KEY_USER_USER_NAME + " TEXT UNIQUE,"
                + KEY_USER_UID + " TEXT,"
                + KEY_USER_USER_TYPE_ID + " INTEGER,"
                + KEY_USER_CODE + " TEXT,"
                + KEY_USER_PAYMENT + " REAL,"
                + KEY_USER_BALANCE + " REAL,"
                + KEY_USER_COUNTRY_ID + " INTEGER,"
                + KEY_USER_GENDER + " INTEGER,"
                + KEY_USER_PROFILE_PICTURE + " BLOB,"
                + KEY_USER_UPDATED_AT + " TEXT,"
                + KEY_USER_CREATED_AT + " TEXT" + ")";
        db.execSQL(CREATE_USER_TABLE);
//        String CREATE_ATTENDANCE_TABLE = "CREATE TABLE  IF NOT EXISTS " + TABLE_NAME_ATTENDANCE + "("
//                + KEY_ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
//                + KEY_USER_ID + " INTEGER,"
//                + KEY_IS_MISSION + " INTEGER,"
//                + KEY_START_DATETIME + " TEXT,"
//                + KEY_END_DATETIME + " TEXT"
//                + KEY_QR_CODE + " TEXT,"
//                + KEY_EXITING + " INTEGER,"+ ")";
//        db.execSQL(CREATE_ATTENDANCE_TABLE);

//        if (Utility.doesDatabaseExist(cntx,"attentra_api")) {
//            // Simplest implementation is to drop all old tables and recreate them
//
//            SQLiteDatabase mDatabase = this.getReadableDatabase();
//
//            Cursor cursor = mDatabase.rawQuery("INSERT INTO attentraDB.users_table SELECT * FROM attentra_api.user", null);
//
//        }

    }

    public void dropTables(SQLiteDatabase db)
    {

        // Simplest implementation is to drop all old tables and recreate them
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_USERS);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_WORDS);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_TAG_USERS);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_FarsiWords);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_UNIVERSAL_MATCH);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_CHATS);
        onCreate(db);
    }

    public void deleteAllTablerecords()
    {
        SQLiteDatabase db = this.getWritableDatabase();
        db.execSQL("delete from "+ TABLE_NAME_USERS);
//        db.execSQL("delete from "+ TABLE_WORDS);
//        db.execSQL("delete from "+ TABLE_TAG_USERS);
//        db.execSQL("delete from "+ TABLE_FarsiWords);
//        db.execSQL("delete from "+ TABLE_UNIVERSAL_MATCH);
//        db.execSQL("delete from "+ TABLE_CHATS);
        db.close();
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {

        if (oldVersion != newVersion) {
            // Simplest implementation is to drop all old tables and recreate them
            dropTables(db);
            Intent intent = new Intent("user_home_activity_broadcast");
            // You can also include some extra data.
            intent.putExtra("logout", "true");
            LocalBroadcastManager.getInstance(cntx).sendBroadcast(intent);
        }



//        // Drop older table if existed
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_TAG_QUESTIONS);
//
//        // Create tables again
//        onCreate(db);

//        switch(oldVersion) {
//            case 1:
//                db.execSQL(DATABASE_CREATE_color);
//                // we want both updates, so no break statement here...
//            case 2:
//                db.execSQL(DATABASE_CREATE_someothertable);
//        }

    }

    public void dropAll(Context cntx) {

        SQLiteDatabase db = this.getWritableDatabase();
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_USERS);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_WORDS);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_TAG_USERS);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_FarsiWords);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_UNIVERSAL_MATCH);
//        db.execSQL("DROP TABLE IF EXISTS " + TABLE_CHATS);
        onCreate(db);
    }

    public boolean isTableExists(String tableName)
    {
        SQLiteDatabase mDatabase = this.getReadableDatabase();
//        if(openDb)
//        {
//            if(mDatabase == null || !mDatabase.isOpen()) {
//                mDatabase = getReadableDatabase();
//            }

            if(!mDatabase.isReadOnly()) {
                mDatabase.close();
                mDatabase = getReadableDatabase();
            }
//        }

        Cursor cursor = mDatabase.rawQuery("select DISTINCT tbl_name from sqlite_master where tbl_name = '"+tableName+"'", null);
        if(cursor!=null) {
            if(cursor.getCount()>0) {
                cursor.close();
                return true;
            }
            cursor.close();
        }
        return false;
    }



    /////////////////////meysam - user table specifications - start/////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////
    // user table name
    public static final String TABLE_NAME_USERS = "user";

    // user Table Columns names
    private static final String KEY_USER_ID = "id";
    private static final String KEY_USER_NAME = "name";
    private static final String KEY_USER_FAMILY= "family";
    private static final String KEY_USER_USER_NAME = "user_name";
    private static final String KEY_USER_USER_TYPE_ID = "user_type_id";
    private static final String KEY_USER_CODE = "code";
    private static final String KEY_USER_PAYMENT = "payment";
    private static final String KEY_USER_BALANCE = "balance";
    private static final String KEY_USER_COUNTRY_ID = "country_id";
    private static final String KEY_USER_EMAIL = "email";
    private static final String KEY_USER_GENDER = "gender";
    private static final String KEY_USER_UID = "uid";
    private static final String KEY_USER_CREATED_AT = "created_at";
    private static final String KEY_USER_UPDATED_AT = "updated_at";
    private static final String KEY_USER_PROFILE_PICTURE = "profile_picture";


    /**
     * Storing user details in sqlite database
     * */
    public void addUser(UserModel user) {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put(KEY_USER_ID, user.getId().toString()); // ID
        values.put(KEY_USER_NAME, user.getName()); // Name
        values.put(KEY_USER_FAMILY, user.getFamily()); // family
        values.put(KEY_USER_EMAIL, user.getEmail()); // Email
        values.put(KEY_USER_USER_NAME, user.getUserName()); // UserName
        values.put(KEY_USER_UID, user.getGuid()); // Email
        values.put(KEY_USER_USER_TYPE_ID, user.getUserType()); //User Type Id
        values.put(KEY_USER_CODE, user.getCode()); //Code
        values.put(KEY_USER_PAYMENT, user.getPayment()); //Payment
        values.put(KEY_USER_BALANCE, user.getBalance()); //Balance
        values.put(KEY_USER_COUNTRY_ID, (user.getCountryId() == null? null:user.getCountryId().toString())); //Country Id
        values.put(KEY_USER_GENDER, user.getGender()); //Gender
        String pathOfAvatar="";
        if(user.getProfilePicture() != null){
            pathOfAvatar=new FileProcessor().saveToInternalStorage(cntx,user.getProfilePicture(),"ProfilePicture",user.getId()+".png");
        }
        values.put(KEY_USER_PROFILE_PICTURE,(user.getProfilePicture() == null?null:pathOfAvatar) ); //Profile Picture
        values.put(KEY_USER_UPDATED_AT, user.getUpdatedAt()); //Updated At
        values.put(KEY_USER_CREATED_AT, user.getCreatedAt()); // Created At

        // Inserting Row
        long id = db.insert(TABLE_NAME_USERS, null, values);
        db.close(); // Closing database connection

//        Log.d(TAG, "New user inserted into sqlite: " + id);
    }

    public void editUser(UserModel user) {
        SQLiteDatabase db = this.getReadableDatabase();
        ContentValues values = new ContentValues();
        if(user.getName()!=null) values.put(KEY_USER_NAME, user.getName()); // Name
        if(user.getFamily()!=null) values.put(KEY_USER_FAMILY, user.getFamily()); // family
        if(user.getEmail()!=null) values.put(KEY_USER_EMAIL, user.getEmail()); // Email
        if(user.getCode()!=null) values.put(KEY_USER_CODE, user.getCode()); //Code
        if(user.getPayment()!=null) values.put(KEY_USER_PAYMENT, user.getPayment()); //Payment
        if(user.getBalance()!=null) values.put(KEY_USER_BALANCE, user.getBalance()); //Balance
        if(user.getCountryId()!=null) values.put(KEY_USER_COUNTRY_ID, (user.getCountryId() == null? null:user.getCountryId().toString())); //Country Id
        if(user.getGender()!=null) values.put(KEY_USER_GENDER, user.getGender().equals("female")?"1":"0"); //Gender
        String pathOfAvatar="";
        if(user.getProfilePicture() != null){
            pathOfAvatar=new FileProcessor().saveToInternalStorage(cntx,user.getProfilePicture(),"ProfilePicture",user.getId()+".png");
        }
        if(user.getProfilePicture()!=null) values.put(KEY_USER_PROFILE_PICTURE,(user.getProfilePicture() == null?null:pathOfAvatar) ); //Profile Picture
        if(user.getUpdatedAt()!=null) values.put(KEY_USER_UPDATED_AT, user.getUpdatedAt()); //Updated At
        if(user.getCreatedAt()!=null) values.put(KEY_USER_CREATED_AT, user.getCreatedAt()); // Created At

        // Inserting Row
        long id = db.update(TABLE_NAME_USERS, values,KEY_USER_ID+"="+user.getId().toString(),null);
        db.close(); // Closing database connection
    }

    /**
     * Getting user data from database
     * */
    public UserModel getUserDetails() {
        UserModel user = new UserModel();
        String selectQuery = "SELECT  * FROM " + TABLE_NAME_USERS;

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);
        // Move to first row
        cursor.moveToFirst();
        if (cursor.getCount() > 0) {
            user.setId(new BigInteger(cursor.getString(0)));
            user.setName( cursor.getString(1));
            user.setFamily( cursor.getString(2));
            user.setEmail( cursor.getString(3));
            user.setUserName( cursor.getString(4));
            user.setGuid( cursor.getString(5));
            user.setUserType(cursor.getInt(6));
            user.setCode(cursor.getString(7));
            user.setPayment(cursor.getString(8));
            user.setBalance(cursor.getString(9));
            user.setCountryId(new BigInteger(cursor.getString(10)));
            user.setGender(cursor.getString(11));
            String pathOfAvatar=cursor.getString(12);
            Bitmap bitmap=null;
            if(pathOfAvatar != null){
                bitmap=new FileProcessor().loadImageFromStorage(pathOfAvatar,cursor.getString(0)+".png");
            }
            user.setProfilePicture(bitmap);
            user.setUpdatedAt( cursor.getString(13));
            user.setCreatedAt(cursor.getString(14));
        }
        cursor.close();
        db.close();
        // return user
//        Log.d(TAG, "Fetching user from Sqlite: " + user.toString());

        return user;
    }

    /**
     * Re crate database Delete all tables and create them again
     * */
    public void deleteUsers() {
        SQLiteDatabase db = this.getWritableDatabase();
        // Delete All Rows
        db.delete(TABLE_NAME_USERS, null, null);

//        new FileProcessor().deleteFile("ProfilePicture",new SessionModel(cntx).getCurrentUser().getGuid()+".png");
        db.close();

//        Log.d(TAG, "Deleted all user info from sqlite");
    }


    /////////////////////meysam - user table specifications - end/////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////meysam - attendance table specifications - start/////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////


//    // attendance table name
//    private static final String TABLE_NAME_ATTENDANCE = "attendance_table";
//
//    // attendance Table Columns names
//    private static final String KEY_ATTENDANCE_ID = "attendance_id";
//    private static final String KEY_ATTENDANCE_USER_ID = "attendance_user_id";
//    private static final String KEY_ATTENDANCE_IS_MISSION = "attendance_is_mission";
//    private static final String KEY_ATTENDANCE_START_DATETIME= "attendance_start_datetime";
//    private static final String KEY_ATTENDANCE_END_DATETIME = "attendance_end_datetime";
//    private static final String KEY_ATTENDANCE_QR_CODE = "attendance_qr_code";
//    private static final String KEY_ATTENDANCE_EXITING = "attendance_exiting";
//    private static final String KEY_ATTENDANCE_CREATEDAT = "attendance_created_at";
//    private static final String KEY_ATTENDANCE_UPDATEDAT = "attendance_updated_at";

//
//    /**
//     * Storing user details in sqlite database
//     * */
//    public void addAttendanceManual(AttendanceModel attendance, String user_id) {
//        SQLiteDatabase db = this.getWritableDatabase();
//
//        ContentValues values = new ContentValues();
//        values.put(KEY_ATTENDANCE_ID, attendance.getAttendanceId().toString()); // ID
//        values.put(KEY_ATTENDANCE_IS_MISSION, attendance.getMission());
//        values.put(KEY_ATTENDANCE_USER_ID, user_id);
//        values.put(KEY_ATTENDANCE_START_DATETIME, attendance.getStartDateTime());
//        values.put(KEY_ATTENDANCE_END_DATETIME, attendance.getEndDateTime());
//
//        // Inserting Row
//        long id = db.insert(TABLE_NAME_ATTENDANCE, null, values);
//        db.close(); // Closing database connection
//    }
//    public void addAttendanceAuto(AttendanceModel attendance) {
//        SQLiteDatabase db = this.getWritableDatabase();
//
//        ContentValues values = new ContentValues();
//        values.put(KEY_ID, attendance.getAttendanceId().toString()); // ID
//        values.put(KEY_IS_MISSION, attendance.getMission());
//        values.put(KEY_QR_CODE, attendance.getQrCode());
//        values.put(KEY_START_DATETIME, attendance.getStartDateTime());
//        values.put(KEY_EXITING, attendance.isExiting());
//
//        // Inserting Row
//        long id = db.insert(TABLE_NAME, null, values);
//        db.close(); // Closing database connection
//    }
//    /**
//     * Storing user details in sqlite database
//     * */
//    public void addAttendance(QuestionModel question, String tag) {
//        SQLiteDatabase db = this.getWritableDatabase();
//
//        ContentValues values = new ContentValues();
//
//        byte[] byteArray = null;
//        if(question.getImage() != null)
//        {
//            ByteArrayOutputStream stream = new ByteArrayOutputStream();
//            question.getImage().compress(Bitmap.CompressFormat.PNG, 100, stream);
//            byteArray = stream.toByteArray();
//        }
//
//
//        values.put(KEY_TAG_QUESTIONS_TAG, tag);
//        if(question.getId() != null)
//            values.put(KEY_TAG_QUESTIONS_ID, question.getId().toString());
//        values.put(KEY_TAG_QUESTIONS_GUID, question.getGuid());
//        values.put(KEY_TAG_QUESTIONS_CATEGORYID, question.getCategoryId());
//        values.put(KEY_TAG_QUESTIONS_DESCRIPTION, question.getDescription());
//        values.put(KEY_TAG_QUESTIONS_ANSWER, question.getAnswer());
//        values.put(KEY_TAG_QUESTIONS_IMAGE, byteArray);
//        values.put(KEY_TAG_QUESTIONS_PENALTY, question.getPenalty());
//        values.put(KEY_TAG_QUESTIONS_FINALHAZELREWARD, question.getFinalHazelReward());
//        values.put(KEY_TAG_QUESTIONS_FINALLUCKREWARD, question.getFinalLuckReward());
//        values.put(KEY_TAG_QUESTIONS_MAXHAZEKREWARD, question.getMaxHazelReward());
//        values.put(KEY_TAG_QUESTIONS_MAXLUCKREWARD, question.getMaxLuckReward());
//        values.put(KEY_TAG_QUESTIONS_MINHAZELREWARD, question.getMinHazelReward());
//        values.put(KEY_TAG_QUESTIONS_MINLUCKREWARD, question.getMinLuckReward());
//        values.put(KEY_TAG_QUESTIONS_POSITION_CODE, question.getPositionCode());
//        values.put(KEY_TAG_QUESTIONS_QUESTION_POSITION, question.getQuestionPosition());
//
//
//
//        StringBuilder sb = new StringBuilder();
//        if(question.getAnswerCells() != null &&
//                question.getAnswerCells().size()>0)
//        {
//            for(String s: question.getAnswerCells()) {
//                sb.append(s).append(',');
//            }
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWERCELLS, sb.toString());
//        }
//
//
//
//        if(question.getAnsweredCells() != null &&
//                question.getAnsweredCells().size()>0)
//        {
//            sb = new StringBuilder();
//            for(String s: question.getAnsweredCells()) {
//                sb.append(s).append(',');
//            }
//
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWEREDCELLS, sb.toString());
//        }
//
//
//
//
//        if(question.getAnsweredLetters() != null &&
//                question.getAnsweredLetters().size()>0)
//        {
//            sb = new StringBuilder();
//            for(String s: question.getAnsweredLetters()) {
//                sb.append(s).append(',');
//            }
//
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWEREDLETTERS, sb.toString());
//        }
//
//
//
//
//        values.put(KEY_TAG_QUESTIONS_CREATEDAT, question.getCreatedAt());
//        values.put(KEY_TAG_QUESTIONS_UPDATEDAT, question.getUpdatedAt());
//        values.put(KEY_TAG_QUESTIONS_ISANSWERED, question.getAnswered()?"1":"0");
//        values.put(KEY_TAG_QUESTIONS_ISCORRECT, question.getCorrect()?"1":"0");
//
//        // Inserting Row
//        try
//        {
//            long id = db.insertOrThrow(TABLE_TAG_QUESTIONS, null, values);
//
//        }
//        catch (Exception ex)
//        {
//            int temp = 0;
//        }
//        db.close(); // Closing database connection
//
////        Log.d(TAG, "New match inserted into sqlite: " + id);
//    }
//
//    /**
//     * Storing user details in sqlite database
//     * */
//    public void addQuestion(QuestionModel question, String tag, SQLiteDatabase tdb) {
//
//
//        ContentValues values = new ContentValues();
//
//        byte[] byteArray = null;
//        if(question.getImage() != null)
//        {
//            ByteArrayOutputStream stream = new ByteArrayOutputStream();
//            question.getImage().compress(Bitmap.CompressFormat.PNG, 100, stream);
//            byteArray = stream.toByteArray();
//        }
//
//
//        values.put(KEY_TAG_QUESTIONS_TAG, tag);
//        if(question.getId() != null)
//            values.put(KEY_TAG_QUESTIONS_ID, question.getId().toString());
//        values.put(KEY_TAG_QUESTIONS_GUID, question.getGuid());
//        values.put(KEY_TAG_QUESTIONS_CATEGORYID, question.getCategoryId());
//        values.put(KEY_TAG_QUESTIONS_DESCRIPTION, question.getDescription());
//        values.put(KEY_TAG_QUESTIONS_ANSWER, question.getAnswer());
//        values.put(KEY_TAG_QUESTIONS_IMAGE, byteArray);
//        values.put(KEY_TAG_QUESTIONS_PENALTY, question.getPenalty());
//        values.put(KEY_TAG_QUESTIONS_FINALHAZELREWARD, question.getFinalHazelReward());
//        values.put(KEY_TAG_QUESTIONS_FINALLUCKREWARD, question.getFinalLuckReward());
//        values.put(KEY_TAG_QUESTIONS_MAXHAZEKREWARD, question.getMaxHazelReward());
//        values.put(KEY_TAG_QUESTIONS_MAXLUCKREWARD, question.getMaxLuckReward());
//        values.put(KEY_TAG_QUESTIONS_MINHAZELREWARD, question.getMinHazelReward());
//        values.put(KEY_TAG_QUESTIONS_MINLUCKREWARD, question.getMinLuckReward());
//        values.put(KEY_TAG_QUESTIONS_POSITION_CODE, question.getPositionCode());
//        values.put(KEY_TAG_QUESTIONS_QUESTION_POSITION, question.getQuestionPosition());
//
//
//
//        StringBuilder sb = new StringBuilder();
//        if(question.getAnswerCells() != null &&
//                question.getAnswerCells().size()>0)
//        {
//            for(String s: question.getAnswerCells()) {
//                sb.append(s).append(',');
//            }
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWERCELLS, sb.toString());
//        }
//
//
//
//        if(question.getAnsweredCells() != null &&
//                question.getAnsweredCells().size()>0)
//        {
//            sb = new StringBuilder();
//            for(String s: question.getAnsweredCells()) {
//                sb.append(s).append(',');
//            }
//
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWEREDCELLS, sb.toString());
//        }
//
//
//
//
//        if(question.getAnsweredLetters() != null &&
//                question.getAnsweredLetters().size()>0)
//        {
//            sb = new StringBuilder();
//            for(String s: question.getAnsweredLetters()) {
//                sb.append(s).append(',');
//            }
//
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWEREDLETTERS, sb.toString());
//        }
//
//
//
//
//        values.put(KEY_TAG_QUESTIONS_CREATEDAT, question.getCreatedAt());
//        values.put(KEY_TAG_QUESTIONS_UPDATEDAT, question.getUpdatedAt());
//        values.put(KEY_TAG_QUESTIONS_ISANSWERED, question.getAnswered()?"1":"0");
//        values.put(KEY_TAG_QUESTIONS_ISCORRECT, question.getCorrect()?"1":"0");
//
//        // Inserting Row
//        try
//        {
//            long id = tdb.insertOrThrow(TABLE_TAG_QUESTIONS, null, values);
//
//        }
//        catch (Exception ex)
//        {
//            int temp = 0;
//        }
//    }
//
//    public void editQuestion(QuestionModel question, String tag) {
//        SQLiteDatabase db = this.getReadableDatabase();
//        ContentValues values = new ContentValues();
//
//
//        if(question.getId()!=null) values.put(KEY_TAG_QUESTIONS_ID, question.getId().toString());
//        if(question.getGuid()!=null) values.put(KEY_TAG_QUESTIONS_GUID, question.getGuid());
//        if(question.getDescription()!=null) values.put(KEY_TAG_QUESTIONS_DESCRIPTION, question.getDescription());
//        if(question.getAnswer()!=null) values.put(KEY_TAG_QUESTIONS_ANSWER, question.getAnswer());
//        if(question.getFinalLuckReward()!=null) values.put(KEY_TAG_QUESTIONS_FINALHAZELREWARD, question.getFinalHazelReward());
//        if(question.getFinalLuckReward()!=null) values.put(KEY_TAG_QUESTIONS_FINALLUCKREWARD, question.getFinalLuckReward());
//        if(question.getMaxHazelReward()!=null) values.put(KEY_TAG_QUESTIONS_MAXHAZEKREWARD, question.getMaxHazelReward());
//        if(question.getMaxLuckReward()!=null) values.put(KEY_TAG_QUESTIONS_MAXLUCKREWARD, question.getMaxLuckReward());
//        if(question.getMinHazelReward()!=null) values.put(KEY_TAG_QUESTIONS_MINHAZELREWARD, question.getMinHazelReward());
//        if(question.getMinLuckReward()!=null) values.put(KEY_TAG_QUESTIONS_MINLUCKREWARD, question.getMinLuckReward());
//        if(question.getPositionCode()!=null) values.put(KEY_TAG_QUESTIONS_POSITION_CODE, question.getPositionCode());
//        if(question.getQuestionPosition()!=null) values.put(KEY_TAG_QUESTIONS_QUESTION_POSITION, question.getQuestionPosition());
//
//        if(question.getImage()!=null)
//        {
//            byte[] byteArray;
//            ByteArrayOutputStream stream = new ByteArrayOutputStream();
//            question.getImage().compress(Bitmap.CompressFormat.PNG, 100, stream);
//            byteArray = stream.toByteArray();
//            values.put(KEY_TAG_QUESTIONS_IMAGE, byteArray);
//        }
//        StringBuilder sb = new StringBuilder();
//        if(question.getAnswerCells()!=null)
//        {
//            for(String s: question.getAnswerCells()) {
//                sb.append(s).append(',');
//            }
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWERCELLS, sb.toString());
//        }
//
//        if(question.getAnsweredLetters()!=null)
//        {
//            sb = new StringBuilder();
//            for(String s: question.getAnsweredLetters()) {
//                sb.append(s).append(',');
//            }
//
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWEREDLETTERS, sb.toString());
//        }
//        if(question.getAnsweredCells()!=null)
//        {
//            sb = new StringBuilder();
//            for(String s: question.getAnsweredCells()) {
//                sb.append(s).append(',');
//            }
//
//            sb.deleteCharAt(sb.length()-1); //delete last comma
//            values.put(KEY_TAG_QUESTIONS_ANSWEREDCELLS, sb.toString());
//        }
//        if(question.getAnswered()!=null)  values.put(KEY_TAG_QUESTIONS_ISANSWERED,question.getAnswered()?"1":"0");
//        if(question.getCorrect()!=null) values.put(KEY_TAG_QUESTIONS_ISCORRECT,question.getCorrect()?"1":"0");
//        if(question.getCategoryId()!=null) values.put(KEY_TAG_QUESTIONS_CATEGORYID, question.getCategoryId());
//        if(question.getCreatedAt()!=null) values.put(KEY_TAG_QUESTIONS_CREATEDAT, question.getCreatedAt());
//        if(question.getPenalty()!=null) values.put(KEY_TAG_QUESTIONS_PENALTY, question.getPenalty());
//        if(question.getUpdatedAt()!=null) values.put(KEY_TAG_QUESTIONS_UPDATEDAT, question.getUpdatedAt());
//
//        // Edit Row
//        long id = db.update(TABLE_TAG_QUESTIONS, values,KEY_TAG_QUESTIONS_TAG+"="+tag,null);
//        db.close(); // Closing database connection
//    }
//
//    // Getting single question by tag
//    public QuestionModel getQuestionByTag(String questionTag) {
//        SQLiteDatabase db = this.getReadableDatabase();
//
//        Cursor cursor = db.query(TABLE_TAG_QUESTIONS, new String[] { KEY_TAG_QUESTIONS_ID,
//                        KEY_TAG_QUESTIONS_GUID,
//                        KEY_TAG_QUESTIONS_DESCRIPTION,
//                        KEY_TAG_QUESTIONS_IMAGE,
//                        KEY_TAG_QUESTIONS_FINALHAZELREWARD,
//                        KEY_TAG_QUESTIONS_FINALLUCKREWARD,
//                        KEY_TAG_QUESTIONS_MAXHAZEKREWARD,
//                        KEY_TAG_QUESTIONS_MAXLUCKREWARD,
//                        KEY_TAG_QUESTIONS_MINHAZELREWARD,
//                        KEY_TAG_QUESTIONS_MINLUCKREWARD,
//                        KEY_TAG_QUESTIONS_ANSWER,
//                        KEY_TAG_QUESTIONS_ANSWERCELLS,
//                        KEY_TAG_QUESTIONS_ANSWEREDCELLS,
//                        KEY_TAG_QUESTIONS_ANSWEREDLETTERS,
//                        KEY_TAG_QUESTIONS_CATEGORYID,
//                        KEY_TAG_QUESTIONS_CREATEDAT,
//                        KEY_TAG_QUESTIONS_ISANSWERED,
//                        KEY_TAG_QUESTIONS_ISCORRECT,
//                        KEY_TAG_QUESTIONS_PENALTY,
//                        KEY_TAG_QUESTIONS_UPDATEDAT,
//                        KEY_TAG_QUESTIONS_POSITION_CODE,
//                        KEY_TAG_QUESTIONS_QUESTION_POSITION}, KEY_TAG_QUESTIONS_TAG + "=?",
//                new String[] { questionTag }, null, null, null, null);
//        if (cursor != null && cursor.getCount() > 0)
//            cursor.moveToFirst();
//
//        QuestionModel question = new QuestionModel();
//        if(cursor.getCount() > 0)
//        {
//            question.setId(new BigInteger(cursor.getString(0)));
//            question.setGuid(cursor.getString(1));
//            question.setDescription(cursor.getString(2));
//            byte[] byteArray = cursor.getBlob(3);
//            if(byteArray != null)
//            {
//                Bitmap bm = BitmapFactory.decodeByteArray(byteArray, 0 ,byteArray.length);
//                question.setImage(bm);
//            }
//            else
//            {
//                question.setImage(null);
//            }
//
//            if(cursor.getString(4) != null)
//                question.setFinalHazelReward(new Integer(cursor.getString(4)));
//            if(cursor.getString(5) != null)
//                question.setFinalLuckReward(new Integer(cursor.getString(5)));
//            if(cursor.getString(6) != null)
//                question.setMaxHazelReward(new Integer(cursor.getString(6)));
//            if(cursor.getString(7) != null)
//                question.setMaxLuckReward(new Integer(cursor.getString(7)));
//            if(cursor.getString(8) != null)
//                question.setMinHazelReward(new Integer(cursor.getString(8)));
//            if(cursor.getString(9) != null)
//                question.setMinLuckReward(new Integer(cursor.getString(9)));
//            if(cursor.getString(10) != null)
//                question.setAnswer(cursor.getString(10));
//            if(cursor.getString(11) != null)
//                question.setAnswerCells( new ArrayList<String>(Arrays.asList(cursor.getString(11).split("\\s*,\\s*"))));
//            if(cursor.getString(12) != null)
//                question.setAnsweredCells(new ArrayList<String>(Arrays.asList(cursor.getString(12).split("\\s*,\\s*"))));
//            if(cursor.getString(13) != null)
//                question.setAnsweredLetters(new ArrayList<String>(Arrays.asList(cursor.getString(13).split("\\s*,\\s*"))));
//            if(cursor.getString(14) != null)
//                question.setCategoryId(new Integer(cursor.getString(14)));
//            if(cursor.getString(15) != null)
//                question.setCreatedAt(cursor.getString(15));
//            if(cursor.getString(16) != null)
//                question.setAnswered(cursor.getString(16).equals("1")?true:false);
//            if(cursor.getString(17) != null)
//                question.setCorrect(cursor.getString(17).equals("1")?true:false);
//            if(cursor.getString(18) != null)
//                question.setPenalty(new Integer(cursor.getString(18)));
//            if(cursor.getString(19) != null)
//                question.setUpdatedAt(cursor.getString(19));
//            if(cursor.getString(20) != null)
//                question.setPositionCode(cursor.getString(20));
//            if(cursor.getString(21) != null)
//                question.setQuestionPosition(cursor.getString(21));
//        }
//
//        // return question
//        return question;
//    }
//
//    // Getting single question by id
//    public QuestionModel getQuestionById(BigInteger questionId) {
//        SQLiteDatabase db = this.getReadableDatabase();
//
//        Cursor cursor = db.query(TABLE_TAG_QUESTIONS, new String[] { KEY_TAG_QUESTIONS_ID,
//                        KEY_TAG_QUESTIONS_GUID,
//                        KEY_TAG_QUESTIONS_DESCRIPTION,
//                        KEY_TAG_QUESTIONS_IMAGE,
//                        KEY_TAG_QUESTIONS_FINALHAZELREWARD,
//                        KEY_TAG_QUESTIONS_FINALLUCKREWARD,
//                        KEY_TAG_QUESTIONS_MAXHAZEKREWARD,
//                        KEY_TAG_QUESTIONS_MAXLUCKREWARD,
//                        KEY_TAG_QUESTIONS_MINHAZELREWARD,
//                        KEY_TAG_QUESTIONS_MINLUCKREWARD,
//                        KEY_TAG_QUESTIONS_ANSWER,
//                        KEY_TAG_QUESTIONS_ANSWERCELLS,
//                        KEY_TAG_QUESTIONS_ANSWEREDCELLS,
//                        KEY_TAG_QUESTIONS_ANSWEREDLETTERS,
//                        KEY_TAG_QUESTIONS_CATEGORYID,
//                        KEY_TAG_QUESTIONS_CREATEDAT,
//                        KEY_TAG_QUESTIONS_ISANSWERED,
//                        KEY_TAG_QUESTIONS_ISCORRECT,
//                        KEY_TAG_QUESTIONS_PENALTY,
//                        KEY_TAG_QUESTIONS_UPDATEDAT,
//                        KEY_TAG_QUESTIONS_POSITION_CODE,
//                        KEY_TAG_QUESTIONS_QUESTION_POSITION}, KEY_TAG_QUESTIONS_ID + "=?",
//                new String[] { String.valueOf(questionId) }, null, null, null, null);
//        if (cursor != null)
//            cursor.moveToFirst();
//
//        QuestionModel question = new QuestionModel();
//
//        if(cursor.getString(0) != null)
//            question.setId(new BigInteger(cursor.getString(0)));
//        if(cursor.getString(1) != null)
//            question.setGuid(cursor.getString(1));
//        if(cursor.getString(2) != null)
//            question.setDescription(cursor.getString(2));
//        if(cursor.getBlob(3) != null)
//        {
//            byte[] byteArray = cursor.getBlob(3);
//            Bitmap bm = BitmapFactory.decodeByteArray(byteArray, 0 ,byteArray.length);
//            question.setImage(bm);
//        }
//
//        if(cursor.getString(4) != null)
//            question.setFinalHazelReward(new Integer(cursor.getString(4)));
//        if(cursor.getString(5) != null)
//            question.setFinalLuckReward(new Integer(cursor.getString(5)));
//        if(cursor.getString(6) != null)
//            question.setMaxHazelReward(new Integer(cursor.getString(6)));
//        if(cursor.getString(7) != null)
//            question.setMaxLuckReward(new Integer(cursor.getString(7)));
//        if(cursor.getString(8) != null)
//            question.setMinHazelReward(new Integer(cursor.getString(8)));
//        if(cursor.getString(9) != null)
//            question.setMinLuckReward(new Integer(cursor.getString(9)));
//        if(cursor.getString(10) != null)
//            question.setAnswer(cursor.getString(10));
//        if(cursor.getString(11) != null)
//            question.setAnswerCells(new ArrayList<String>(Arrays.asList(cursor.getString(11).split("\\s*,\\s*"))));
//        if(cursor.getString(12) != null)
//            question.setAnsweredCells(new ArrayList<String>(Arrays.asList(cursor.getString(12).split("\\s*,\\s*"))));
//        if(cursor.getString(13) != null)
//            question.setAnsweredLetters(new ArrayList<String>(Arrays.asList(cursor.getString(13).split("\\s*,\\s*"))));
//        if(cursor.getString(14) != null)
//            question.setCategoryId(new Integer(cursor.getString(14)));
//        if(cursor.getString(15) != null)
//            question.setCreatedAt(cursor.getString(15));
//        if(cursor.getString(16) != null)
//            question.setAnswered(cursor.getString(16).equals("1")?true:false);
//        if(cursor.getString(17) != null)
//            question.setCorrect(cursor.getString(17).equals("1")?true:false);
//        if(cursor.getString(18) != null)
//            question.setPenalty(new Integer(cursor.getString(18)));
//        if(cursor.getString(19) != null)
//            question.setUpdatedAt(cursor.getString(19));
//        if(cursor.getString(20) != null)
//            question.setPositionCode(cursor.getString(20));
//        if(cursor.getString(21) != null)
//            question.setQuestionPosition(cursor.getString(21));
//        // return question
//        return question;
//    }
//    // Deleting single contact
//    public void deleteQuestionById(BigInteger question_id) {
//        SQLiteDatabase db = this.getWritableDatabase();
//        db.delete(TABLE_TAG_QUESTIONS, KEY_TAG_QUESTIONS_ID + " = ?",
//                new String[] { String.valueOf(question_id) });
//        db.close();
//    }
//
//    // Deleting single contact
//    public void deleteQuestionsByTag(String question_tag) {
//        SQLiteDatabase db = this.getWritableDatabase();
//        db.delete(TABLE_TAG_QUESTIONS, KEY_TAG_QUESTIONS_TAG + " = ?",
//                new String[] { question_tag });
//        db.close();
//    }
//
//    // Getting All Questions related to a specific tag
//    public ArrayList<QuestionModel> getQuestionsByTag(String questionTag) {
//        ArrayList<QuestionModel> questions = new ArrayList<QuestionModel>();
//        // Select All Query
//        String selectQuery = "SELECT  * FROM " + TABLE_TAG_QUESTIONS + " WHERE "+ KEY_TAG_QUESTIONS_TAG +" = '"+questionTag+"' ORDER BY " +
//                KEY_TAG_QUESTIONS_ID+" ASC";
//
//        SQLiteDatabase db = this.getWritableDatabase();
//        Cursor cursor = db.rawQuery(selectQuery, null);
//
//        // looping through all rows and adding to list
//        if (cursor.moveToFirst()) {
//            do {
//                QuestionModel question = new QuestionModel();
//
//                if(cursor.getString(0) != null)
//                    question.setId(new BigInteger(cursor.getString(0)));
//                if(cursor.getString(1) != null)
//                    question.setGuid(cursor.getString(1));
//                if(cursor.getString(2) != null)
//                    question.setAnswer(cursor.getString(2));
//                if(cursor.getString(3) != null)
//                    question.setAnswerCells(new ArrayList<String>(Arrays.asList(cursor.getString(3).split("\\s*,\\s*"))));
//                if(cursor.getString(4) != null)
//                    question.setAnsweredLetters(new ArrayList<String>(Arrays.asList(cursor.getString(4).split("\\s*,\\s*"))));
//                if(cursor.getString(5) != null)
//                    question.setAnsweredCells(new ArrayList<String>(Arrays.asList(cursor.getString(5).split("\\s*,\\s*"))));
//                if(cursor.getString(6) != null)
//                    question.setCategoryId(new Integer(cursor.getString(6)));
//                if(cursor.getString(7) != null)
//                    question.setDescription(cursor.getString(7));
//                if(cursor.getString(8) != null)
//                    question.setFinalHazelReward(new Integer(cursor.getString(8)));
//                if(cursor.getString(9) != null)
//                    question.setFinalLuckReward(new Integer(cursor.getString(9)));
//                if(cursor.getString(10) != null)
//                    question.setMaxHazelReward(new Integer(cursor.getString(10)));
//                if(cursor.getString(11) != null)
//                    question.setMaxLuckReward(new Integer(cursor.getString(11)));
//                if(cursor.getString(12) != null)
//                    question.setMinHazelReward(new Integer(cursor.getString(12)));
//                if(cursor.getString(13) != null)
//                    question.setMinLuckReward(new Integer(cursor.getString(13)));
//                if(cursor.getString(14) != null)
//                    question.setPenalty(new Integer(cursor.getString(14)));
//                if(cursor.getString(15) != null)
//                    question.setAnswered(cursor.getString(15).equals("1")?true:false);
//                if(cursor.getString(16) != null)
//                    question.setCorrect(cursor.getString(16).equals("1")?true:false);
//                if(cursor.getString(17) != null)
//                    question.setCreatedAt(cursor.getString(17));
//                if(cursor.getString(18) != null)
//                    question.setUpdatedAt(cursor.getString(18));
//                if(cursor.getString(19) != null)
//                    question.setPositionCode(cursor.getString(19));
//                if(cursor.getString(20) != null)
//                    question.setQuestionPosition(cursor.getString(20));
//
//                byte[] byteArray = cursor.getBlob(21);
//                if(byteArray != null)
//                {
//                    Bitmap bm = BitmapFactory.decodeByteArray(byteArray, 0 ,byteArray.length);
//                    question.setImage(bm);
//                }
//
//                // Adding contact to list
//                questions.add(question);
//            } while (cursor.moveToNext());
//        }
//
//        // return question list
//        return questions;
//    }
//
//    public void saveTableQuestions(ArrayList<QuestionModel> questions, String tag)
//    {
//        deleteQuestionsByTag(tag);
//        SQLiteDatabase db = this.getWritableDatabase();
//        db.beginTransaction();
//
//        try
//        {
//            for(int i = 0; i < questions.size(); i++)
//            {
//                addQuestion(questions.get(i), tag, db);
//            }
//            db.setTransactionSuccessful();
//        }
//        catch (Exception ex)
//        {
//        }
//        finally {
//
//            db.endTransaction();
//        }
//    }
    /////////////////////meysam - attendance table specifications - end/////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////


}
