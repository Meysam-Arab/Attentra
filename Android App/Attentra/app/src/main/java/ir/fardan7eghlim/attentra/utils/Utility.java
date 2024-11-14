package ir.fardan7eghlim.attentra.utils;

import android.app.ActivityManager;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.media.AudioManager;
import android.media.ToneGenerator;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
import android.provider.Settings;
import android.util.Base64;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GoogleApiAvailability;
import com.google.android.gms.common.GooglePlayServicesUtil;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.math.BigInteger;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.TimeoutException;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.models.LanguageModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.services.track.TrackingService;

import static java.lang.Runtime.getRuntime;

/**
 * Created by Meysam on 2/16/2017.
 */

public class Utility {

    ///////////////////////////////////RESPONSE_CODES_BAZAR//////////////////////////////////////////////////////////////

    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_OK = 0;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_USER_CANCELED = 1;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_BILLING_UNAVAILABLE = 3;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_ITEM_UNAVAILABLE= 4;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_DEVELOPER_ERROR = 5;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_ERROR = 6;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_ITEM_ALREADY_OWNED = 7;
    public static final int RESPONSE_CODE_BILLING_RESPONSE_RESULT_ITEM_NOT_OWNED = 8;


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public  static boolean checkReturnedRequestValidity(String returned_token, String returned_status, Context cntx)
    {
//        if(returned_status.compareTo())
//        {
//
//        }

        SessionModel current_session = new SessionModel(cntx);
        current_session.checkToken(returned_token);
        return true;
    }

    public static boolean isEmailValid(String email)
    {
        // Replace this with our additional logic - meysam
        return email.contains("@");
    }
    public final static boolean isValidEmail(CharSequence target) {
        if (target == null) {
            return false;
        } else {
            return android.util.Patterns.EMAIL_ADDRESS.matcher(target).matches();
        }
    }

    public static boolean isPasswordValid(String password)
    {
        // Replace this with our additional logic - meysam
        return password.length() > 5;
    }

    public static String[] tokenDecode(String JWTEncoded) {
        String[] result = new String[2];
        try {
            String[] split = JWTEncoded.split("\\.");
            result[0]= getJson(split[0]);
            result[1]= getJson(split[1]);
        } catch (UnsupportedEncodingException e) {
            //Error
        }

        return result;
    }

    private static String getJson(String strEncoded) throws UnsupportedEncodingException{
        byte[] decodedBytes = Base64.decode(strEncoded, Base64.URL_SAFE);
        return new String(decodedBytes, "UTF-8");
    }
    ///convert bitmap to string
    public static String getStringImage(Bitmap bmp){
        double z=150.0/(double)bmp.getWidth();
        bmp=FileProcessor.getResizedBitmap(bmp,(int)(bmp.getWidth()*z), (int)(bmp.getHeight()*z));
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        bmp.compress(Bitmap.CompressFormat.JPEG, 60, baos);
        byte[] imageBytes = baos.toByteArray();
        String encodedImage = Base64.encodeToString(imageBytes, Base64.DEFAULT);
        return encodedImage;
    }
    //convert String to bitmap
    public static Bitmap getBitmapImage(String temp){
        byte[] decodedString = Base64.decode(temp, Base64.DEFAULT);
        Bitmap decodedByte = BitmapFactory.decodeByteArray(decodedString, 0, decodedString.length);
        return decodedByte;
    }
    //get time zones
    public static List<String> getTmeZones(Context ctx){
        List<String> temp=new ArrayList<String>();
        InputStream inputStream = ctx.getResources().openRawResource(R.raw.time_zones);

        BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
        String line = null;
        try {
            line = reader.readLine();
        } catch (IOException e) {
            e.printStackTrace();
        }
        String[] splited;
        while (line != null) {
            temp.add(line);

            try {
                line = reader.readLine();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return temp;
    }


    public static float distance(float lat1, float lng1, float lat2, float lng2) {
        double earthRadius = 6371000; //meters
        double dLat = Math.toRadians(lat2-lat1);
        double dLng = Math.toRadians(lng2-lng1);
        double a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) *
                        Math.sin(dLng/2) * Math.sin(dLng/2);
        double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        float dist = (float) (earthRadius * c);

        return dist;
    }

    public static String getDateTimeFromTrackGroup(String trackGroup) {
        StringBuilder resultDateTime = new StringBuilder();
        String temp = trackGroup.substring(trackGroup.length() - 14);
        resultDateTime = new StringBuilder(temp).insert(temp.length()-2, ":").insert(temp.length()-4, ":").insert(temp.length()-6, " ")
                .insert(temp.length()-8, "-").insert(temp.length()-10, "-");


        return resultDateTime.toString();
    }
    //fix date formater
    public static String fixDateFormat_01(String date){
        String[] temp=date.split("/");
        String temp2=temp[0]+"/";
        if(temp[1].length()<2)
            temp2+="0"+temp[1]+"/";
        else
            temp2+=temp[1]+"/";
        if(temp[2].length()<2)
            temp2+="0"+temp[2];
        else
            temp2+=temp[2];
        return temp2;
    }
    public static String fixDateFormat_02(String date){
        String[] temp=date.split("-");
        String temp2=temp[0]+"/";
        if(temp[1].length()<2)
            temp2+="0"+temp[1]+"/";
        else
            temp2+=temp[1]+"/";
        if(temp[2].length()<2)
            temp2+="0"+temp[2];
        else
            temp2+=temp[2];
        return temp2;
    }

    public static String fixDateFormat_02(String date, String DateSplitter){
        String[] temp=date.split(DateSplitter);
        String temp2=temp[0]+"/";
        if(temp[1].length()<2)
            temp2+="0"+temp[1]+"/";
        else
            temp2+=temp[1]+"/";
        if(temp[2].length()<2)
            temp2+="0"+temp[2];
        else
            temp2+=temp[2];
        return temp2;
    }
    //check network
    public static boolean isNetworkAvailable(Context context){
        boolean available = false;
        /** Getting the system's connectivity service */
        ConnectivityManager connMgr = (ConnectivityManager) context.getSystemService(context.CONNECTIVITY_SERVICE);
        /** Getting active network interface  to get the network's status */
        NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
        if(networkInfo !=null && networkInfo.isAvailable())
            available = true;
        /** Returning the status of the network */
        return available;
    }

    public static String getCurrectDateByLanguage(Context cntx, String inputDateTime)
    {
        String languageCode = new SessionModel(cntx).getLanguageCode();
        String newDateTime = inputDateTime;
        String[] datePart = inputDateTime.split(" ");
        String[] Date=datePart[0].split("-");
        String temp="";
        if(languageCode.equals("fa"))
        {
            CalendarTool ct=new CalendarTool();
            ct.setGregorianDate(new Integer(Date[0]),new Integer(Date[1]),new Integer(Date[2]));
            newDateTime= ct.getIranianDate();
            temp=newDateTime + " " + datePart[1];
        }else{
            temp=newDateTime;
        }
        return temp;


    }

    //search in List
    public static ArrayList<Object> searchInList(ArrayList<Object> main_list,ArrayList<String> list,String target){
        ArrayList<Object> returned_list=new ArrayList<>();

        for(int i=0;i<list.size();i++){
            if(list.get(i).contains(target)){
                returned_list.add(main_list.get(i));
            }
        }

        return  returned_list;
    }

    public static boolean isMyServiceRunning(Class<?> serviceClass, Context cntx) {
        ActivityManager manager = (ActivityManager) cntx.getSystemService(cntx.ACTIVITY_SERVICE);
        for (ActivityManager.RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {
            if (service.getClass().getName().equals(serviceClass.getName())) {
                return true;
            }
        }
        return false;
    }

    public static boolean isTrackingServiceRunning() {
          if (TrackingService.serviceRuning != null && TrackingService.serviceRuning != false) {
                return true;
            }
        return false;
    }

    public static String getDeviceName() {
        String manufacturer = Build.MANUFACTURER;
        String model = Build.MODEL;
        if (model.startsWith(manufacturer)) {
            return capitalize(model);
        } else {
            return capitalize(manufacturer) + " " + model;
        }
    }

    public static String getDeviceCode(Context cntx)
    {
        return Settings.Secure.getString(cntx.getContentResolver(),
            Settings.Secure.ANDROID_ID);
    }


    private static String capitalize(String s) {
        if (s == null || s.length() == 0) {
            return "";
        }
        char first = s.charAt(0);
        if (Character.isUpperCase(first)) {
            return s;
        } else {
            return Character.toUpperCase(first) + s.substring(1);
        }
    }


    public static int digiConvertPicture(int i){
        switch (i){
            case 0:
                return R.drawable.d0;
            case 1:
                return R.drawable.d1;
            case 2:
                return R.drawable.d2;
            case 3:
                return R.drawable.d3;
            case 4:
                return R.drawable.d4;
            case 5:
                return R.drawable.d5;
            case 6:
                return R.drawable.d6;
            case 7:
                return R.drawable.d7;
            case 8:
                return R.drawable.d8;
            default:
                return R.drawable.d9;
        }

    }

    private static final String arabic = "\u06f0\u06f1\u06f2\u06f3\u06f4\u06f5\u06f6\u06f7\u06f8\u06f9";
    public static String arabicToDecimal(String number) {
        char[] chars = new char[number.length()];
        for(int i=0;i<number.length();i++) {
            char ch = number.charAt(i);
            if (ch >= 0x0660 && ch <= 0x0669)
                ch -= 0x0660 - '0';
            else if (ch >= 0x06f0 && ch <= 0x06F9)
                ch -= 0x06f0 - '0';
            chars[i] = ch;
        }
        return new String(chars);
    }

    public static String convertDateGorgeian2Persian(String gorgeianDateTime)
    {

        String[] datePart = gorgeianDateTime.split(" ");
        String[] Date=datePart[0].split("-");
        String temp="";
        CalendarTool ct=new CalendarTool();
        ct.setGregorianDate(new Integer(Date[0]),new Integer(Date[1]),new Integer(Date[2]));
        String newDateTime= ct.getIranianDate();
        temp=newDateTime + " " + datePart[1];

        return temp;


    }

//    public static String convertDateGorgeian2Persian(String gorgeianDateTime, String inputDateSplitter, String outputDateSplitter, String outputTimeSplitter)
//    {
//
//        String[] datePart = gorgeianDateTime.split(" ");
//        String[] Date=datePart[0].split(inputDateSplitter);
//        String temp="";
//        CalendarTool ct=new CalendarTool();
//        ct.setGregorianDate(new Integer(Date[0]),new Integer(Date[1]),new Integer(Date[2]));
//        String newDateTime= ct.getIranianDate();
//        temp=newDateTime + " " + datePart[1];
//
//        return temp;
//    }


    public static boolean isInternetAvailable() throws IOException, InterruptedException {

        InetAddress inetAddress = null;
        try {
            Future<InetAddress> future = Executors.newSingleThreadExecutor().submit(new Callable<InetAddress>() {
                @Override
                public InetAddress call() {
                    try {
                        return InetAddress.getByName("attentra.ir");
                    } catch (UnknownHostException e) {
                        return null;
                    }
                }
            });
            inetAddress = future.get(10000, TimeUnit.MILLISECONDS);
            future.cancel(true);
        } catch (InterruptedException e) {
            return false;
        } catch (ExecutionException e) {
            return false;
        } catch (TimeoutException e) {
            return false;
        }
        return inetAddress!=null && !inetAddress.equals("");

    }

    public static boolean googleServicesOK(Context cntx) {

        int isAvailable = GoogleApiAvailability.getInstance().isGooglePlayServicesAvailable(cntx);

        if (isAvailable == ConnectionResult.SUCCESS) {

            return true;

        } else if (GooglePlayServicesUtil.isUserRecoverableError(isAvailable)) {
            Utility.displayToast(cntx, cntx.getString(R.string.msg_InternetNotAvailable), Toast.LENGTH_SHORT);

        } else {

            Utility.displayToast(cntx, cntx.getString(R.string.msg_InternetNotAvailable), Toast.LENGTH_SHORT);

        }
        return false;
    }

    //for toast type : 0 is short and 1 is long
    public static void displayToast(Context cntx, String toastMsg, int toastType){

        try {// try-catch to avoid stupid app crashes
            LayoutInflater inflater = LayoutInflater.from(cntx);

            View mainLayout = inflater.inflate(R.layout.toast_layout, null);
            View rootLayout = mainLayout.findViewById(R.id.toast_layout_root);


            TextView text = (TextView) mainLayout.findViewById(R.id.text);
            text.setText(toastMsg);

            Toast toast = new Toast(cntx);
            //toast.setGravity(Gravity.CENTER_VERTICAL, 0, 0);
//            toast.setGravity(Gravity.CENTER, 0, 0);

            if (toastType==0)//(isShort)
                toast.setDuration(Toast.LENGTH_SHORT);
            else
                toast.setDuration(Toast.LENGTH_LONG);
            toast.setView(rootLayout);
            toast.show();
        }
        catch(Exception ex) {// to avoid stupid app crashes
//            Log.w(TAG, ex.toString());
        }
    }

    public static void deleteCache(Context context) {
        try {
            File dir = context.getCacheDir();
            deleteDir(dir);
        } catch (Exception e) {}
    }

    public static boolean deleteDir(File dir) {
        if (dir != null && dir.isDirectory()) {
            String[] children = dir.list();
            for (int i = 0; i < children.length; i++) {
                boolean success = deleteDir(new File(dir, children[i]));
                if (!success) {
                    return false;
                }
            }
            return dir.delete();
        } else if(dir!= null && dir.isFile()) {
            return dir.delete();
        } else {
            return false;
        }
    }

    public static void playSound(Context cntx)
    {
        AudioManager audio = (AudioManager) cntx.getApplicationContext().getSystemService(Context.AUDIO_SERVICE);
        switch( audio.getRingerMode() ){
            case AudioManager.RINGER_MODE_NORMAL:

                ToneGenerator toneG = new ToneGenerator(AudioManager.STREAM_ALARM, 100);
                toneG.startTone(ToneGenerator.TONE_CDMA_ALERT_CALL_GUARD, 200);

                break;
            case AudioManager.RINGER_MODE_SILENT:
                //meysam - make music mute
                break;
            case AudioManager.RINGER_MODE_VIBRATE:
                //meysam - make music mute
                break;
            default:
                break;
        }

    }

    public static boolean doesDatabaseExist(Context context, String dbName) {
        File dbFile = context.getDatabasePath(dbName);
        return dbFile.exists();
    }

    public static boolean isTimeSpent(String lastTime, int amountMinute)
    {
        long currentDateTime = new Date().getTime();
        long pervouiseDateTime = new BigInteger(lastTime).longValue();
        if((currentDateTime - pervouiseDateTime) > (amountMinute * 60000) )
            return true;
        return false;
    }
}
