package ir.fardan7eghlim.attentra.views.attendance;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.TimePicker;
import android.widget.Toast;
import java.math.BigInteger;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;
import java.util.Observable;
import java.util.Observer;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.AttendanceController;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.utils.BaseActivity;
import ir.fardan7eghlim.attentra.utils.CalendarTool;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;
import ir.hamsaa.persiandatepicker.Listener;
import ir.hamsaa.persiandatepicker.PersianDatePickerDialog;
import ir.hamsaa.persiandatepicker.util.PersianCalendar;

public class AttendanceStoreActivity extends BaseActivity implements Observer {
    private PersianDatePickerDialog picker;
    private Context context=this;
    private String start_date=null;
    private String end_date=null;
    private ProgressDialog pDialog;
    private int start_hour=-1;
    private int start_minute=-1;
    private int end_hour=-1;
    private int end_minute=-1;
    static final int TIME_DIALOG_ID = 999;
    static final int TIME_DIALOG_ID_1 = 998;
    private boolean flag=true;
    private UserModel user=new UserModel();
    private Calendar myCalendar = Calendar.getInstance();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_attendance_store);
        super.onCreateDrawer();


        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("user_id") != null) {
                user.setId(new BigInteger(extras.getString("user_id")));
            }
            if (extras.getString("user_guid") != null) {
                user.setGuid(extras.getString("user_guid"));
            }
        }

        //data
        SessionModel session = new SessionModel(getApplicationContext());
        final String languageToLoad = session.getLanguageCode();

        Button start_date_as= (Button) findViewById(R.id.start_date_as);
        start_date_as.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(languageToLoad.equals("fa"))
                    showCalendarStart(v);
                else
                    datePicker(true);
            }
        });
        Button start_time_as= (Button) findViewById(R.id.start_time_as);
        start_time_as.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                flag=true;
                showDialog(TIME_DIALOG_ID);
            }
        });
        Button end_date_as= (Button) findViewById(R.id.end_date_as);
        end_date_as.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(languageToLoad.equals("fa"))
                    showCalendarEnd(v);
                else
                    datePicker(false);
            }
        });
        Button end_time_as= (Button) findViewById(R.id.end_time_as);
        end_time_as.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                flag=false;
                showDialog(TIME_DIALOG_ID_1);
            }
        });

    }

    public void addAttendance(View view){
        //check for network
        if(start_date!=null & start_hour!=-1 & start_minute!=-1){
            // Progress dialog
            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
            pDialog.setMessage(getString(R.string.dlg_Wait));
            pDialog.show();
            AttendanceModel attendance = new AttendanceModel();
            attendance.setMission(false);

            SessionModel session = new SessionModel(getApplicationContext());
            final String languageToLoad = session.getLanguageCode();

            if(languageToLoad.equals("fa")) {
                String[] Date = start_date.split("/");
                CalendarTool ct = new CalendarTool();
                ct.setIranianDate(new Integer(Date[0]), new Integer(Date[1]), new Integer(Date[2]));
                start_date = Utility.fixDateFormat_01(ct.getGregorianDate());
            }
            attendance.setStartDateTime(start_date + " " + zeroAdder(start_hour) + ":" + zeroAdder(start_minute)+ ":00");
            if(end_date!=null & end_hour!=-1 & end_minute!=-1){
                if(languageToLoad.equals("fa")) {
                    String[] Date = end_date.split("/");
                    CalendarTool ct1 = new CalendarTool();
                    ct1.setIranianDate(new Integer(Date[0]), new Integer(Date[1]), new Integer(Date[2]));
                    end_date = Utility.fixDateFormat_01(ct1.getGregorianDate());
                }
                attendance.setEndDateTime(end_date + " " + zeroAdder(end_hour) + ":" + zeroAdder(end_minute)+ ":00");
            }
            if(Utility.isNetworkAvailable(context)) {
                AttendanceController ac = new AttendanceController(getApplicationContext());
                ac.addObserver((Observer) this);
                ac.storeManual(attendance,user.getId().toString());
            }else{
                attendance.insert(user.getId().toString());
            }
        }else{
            Utility.displayToast(context,context.getString(R.string.error_defective_information), Toast.LENGTH_SHORT);
        }
    }

    public void showCalendarStart(View v) {
        picker = new PersianDatePickerDialog(this)
                .setPositiveButtonString("انتخاب کن")
                .setNegativeButton("بیخیال")
                .setTodayButton("امروز")
                .setTodayButtonVisible(true)
                .setMaxYear(1420)
                .setMinYear(1396)
                .setActionTextColor(Color.GRAY)
                .setListener(new Listener() {
                    @Override
                    public void onDateSelected(PersianCalendar persianCalendar) {
                        start_date=persianCalendar.getPersianYear() + "/" + zeroAdder(persianCalendar.getPersianMonth()) + "/" + zeroAdder(persianCalendar.getPersianDay());
                        Button start_date_ma= (Button) findViewById(R.id.start_date_as);
                        start_date_ma.setText(start_date);
                        Utility.displayToast(context, persianCalendar.getPersianYear() + "/" + persianCalendar.getPersianMonth() + "/" + persianCalendar.getPersianDay(), Toast.LENGTH_LONG);
                    }
                    @Override
                    public void onDisimised() {
                    }
                });
        picker.show();
    }
    public void showCalendarEnd(View v) {
        picker = new PersianDatePickerDialog(this)
                .setPositiveButtonString("انتخاب کن")
                .setNegativeButton("بیخیال")
                .setTodayButton("امروز")
                .setTodayButtonVisible(true)
                .setMaxYear(1420)
                .setMinYear(1396)
                .setActionTextColor(Color.GRAY)
                .setListener(new Listener() {
                    @Override
                    public void onDateSelected(PersianCalendar persianCalendar) {
                        end_date=persianCalendar.getPersianYear() + "/" + zeroAdder(persianCalendar.getPersianMonth()) + "/" + zeroAdder(persianCalendar.getPersianDay());
                        Button end_date_ma= (Button) findViewById(R.id.end_date_as);
                        end_date_ma.setText(end_date);
                        Utility.displayToast(context, persianCalendar.getPersianYear() + "/" + persianCalendar.getPersianMonth() + "/" + persianCalendar.getPersianDay(), Toast.LENGTH_SHORT);
                    }
                    @Override
                    public void onDisimised() {
                    }
                });
        picker.show();
    }
    @Override
    protected Dialog onCreateDialog(int id) {
        switch (id) {
            case TIME_DIALOG_ID:
                // set time picker as current time
                flag=true;
                return new TimePickerDialog(this,
                        timePickerListener, start_hour==-1?0:start_hour, start_minute==-1?0:start_minute,false);
            case TIME_DIALOG_ID_1:
                // set time picker as current time
                flag=false;
                return new TimePickerDialog(this,
                        timePickerListener, end_hour==-1?0:end_hour, end_minute==-1?0:end_minute,false);
        }
        return null;
    }
    private TimePickerDialog.OnTimeSetListener timePickerListener =
            new TimePickerDialog.OnTimeSetListener() {
                public void onTimeSet(TimePicker view, int selectedHour,
                                      int selectedMinute) {
                    if (flag) {
                        start_hour = selectedHour;
                        start_minute = selectedMinute;
                        // set current time into textview
                        Button start_time_ma = (Button) findViewById(R.id.start_time_as);
                        start_time_ma.setText(new StringBuilder().append(pad(start_hour))
                                .append(":").append(pad(start_minute)));
                    }else{
                        end_hour = selectedHour;
                        end_minute = selectedMinute;

                        // set current time into textview
                        Button end_time_as = (Button) findViewById(R.id.end_time_as);
                        end_time_as.setText(new StringBuilder().append(pad(end_hour))
                                .append(":").append(pad(end_minute)));
                    }

                }
            };
    @Override
    public void update(Observable o, Object arg) {
        pDialog.dismiss();
        if(arg != null)
        {
            if (arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                }else{
                    AttendanceIndexActivity.aia.finish();
                    Intent i = new Intent(context, AttendanceIndexActivity.class);
                    i.putExtra("user_id", user.getId().toString());
                    i.putExtra("user_guid", user.getGuid());
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    context.startActivity(i);
                    finish();
                }
            }else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(getApplicationContext(),getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(this, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intents);
                    finish();
                }else {
                    Utility.displayToast(getApplicationContext(),new RequestRespondModel(this).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);
                }
            }
            else
            {
                Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);

            }
        }
        else
        {
            Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationError), Toast.LENGTH_LONG);

        }
    }
    private static String pad(int c) {
        if (c >= 10)
            return String.valueOf(c);
        else
            return "0" + String.valueOf(c);
    }
    private String zeroAdder(int t){
        String temp=t+"";
        if(t<10)
            temp="0"+temp;
        return temp;
    }
    //Miladi date picker
    private void datePicker(final boolean start){
        DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear,
                                  int dayOfMonth) {
                //  Auto-generated method stub
                myCalendar.set(Calendar.YEAR, year);
                myCalendar.set(Calendar.MONTH, monthOfYear);
                myCalendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);
                updateLabel(start);
            }
        };
        new DatePickerDialog(AttendanceStoreActivity.this, date, myCalendar
                .get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),
                myCalendar.get(Calendar.DAY_OF_MONTH)).show();
    }
    private void updateLabel(boolean start) {
        String myFormat = "yyyy/MM/dd"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);
        String date=sdf.format(myCalendar.getTime());
        if(start){
            start_date=date;
            Button start_date_ma= (Button) findViewById(R.id.start_date_as);
            start_date_ma.setText(start_date);
            Utility.displayToast(context, date, Toast.LENGTH_SHORT);
        }
        else{
            end_date=date;
            Button start_date_ma= (Button) findViewById(R.id.end_date_as);
            start_date_ma.setText(end_date);
            Utility.displayToast(context, date, Toast.LENGTH_SHORT);
        }
    }
}
