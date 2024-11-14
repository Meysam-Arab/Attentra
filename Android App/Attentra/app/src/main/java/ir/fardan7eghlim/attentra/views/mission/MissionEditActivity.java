package ir.fardan7eghlim.attentra.views.mission;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.util.SparseBooleanArray;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TimePicker;
import android.widget.Toast;

import java.math.BigInteger;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.controllers.MissionController;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.LanguageModel;
import ir.fardan7eghlim.attentra.models.MissionModel;
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

public class MissionEditActivity extends BaseActivity implements Observer{

    private EditText title;
    private EditText desc;
    private Button start_date_btn;
    private Button end_date_btn;
    private Button start_time_btn;
    private Button end_time_btn;
    private ListView lv;
    private MissionModel mission;

    private String company_id;
    private String company_guid;
    private CompanyModel company;
    private PersianDatePickerDialog picker;
    private Context context=this;
    private ProgressDialog pDialog;
    private String start_date=null;
    private String end_date=null;
    private int start_hour=0;
    private int start_minute=0;
    private int end_hour=0;
    private int end_minute=0;
    static final int TIME_DIALOG_ID = 999;
    static final int TIME_DIALOG_ID_1 = 998;
    private boolean flag=true;
    private ArrayList<String> listOfIdOfUsers;
    private ArrayList<String> userInMission=new ArrayList<>();;
    private Calendar myCalendar = Calendar.getInstance();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mission_edit);
        super.onCreateDrawer();


        // Progress dialog
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        pDialog.setMessage(getString(R.string.dlg_Wait));
        pDialog.show();

        title= (EditText) findViewById(R.id.title_et_me);
        desc= (EditText) findViewById(R.id.description_et_me);
        start_date_btn= (Button) findViewById(R.id.start_date_me);
        end_date_btn= (Button) findViewById(R.id.end_date_me);
        start_time_btn= (Button) findViewById(R.id.start_time_me);
        end_time_btn= (Button) findViewById(R.id.end_time_me);
        lv= (ListView) findViewById(R.id.user_of_mission_me);

        SessionModel session = new SessionModel(getApplicationContext());
        final String languageToLoad = session.getLanguageCode();

        Button start_date_me= (Button) findViewById(R.id.start_date_me);
        start_date_me.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(languageToLoad.equals("fa"))
                    showCalendarStart(v);
                else
                    datePicker(true);
            }
        });
        Button start_time_me= (Button) findViewById(R.id.start_time_me);
        start_time_me.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                flag=true;
                showDialog(TIME_DIALOG_ID);
            }
        });
        Button end_date_me= (Button) findViewById(R.id.end_date_me);
        end_date_me.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(languageToLoad.equals("fa"))
                    showCalendarEnd(v);
                else
                    datePicker(false);
            }
        });
        Button end_time_me= (Button) findViewById(R.id.end_time_me);
        end_time_me.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                flag=false;
                showDialog(TIME_DIALOG_ID_1);
            }
        });

        company = new CompanyModel();
        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("company_id") != null && extras.getString("company_guid") != null) {
                company_id = extras.getString("company_id");
                company.setCompanyId(new BigInteger(company_id));
                company_guid = extras.getString("company_guid");
                company.setCompanyGuid(company_guid);
            }
        }

        mission=new MissionModel();
        if(getIntent().getExtras()!=null) {
            Bundle extras = getIntent().getExtras();
            if (extras.getString("mission_id") != null && extras.getString("mission_guid") != null && extras.getString("mission_title") != null && extras.getString("mission_desc") != null && extras.getString("mission_start") != null && extras.getString("mission_end") != null) {
                String temp=extras.getString("mission_id");
                mission.setMissionId(new BigInteger(temp));
                mission.setMissionGuid(extras.getString("mission_guid"));
                mission.setTitle(extras.getString("mission_title"));
                title.setText(extras.getString("mission_title"));
                mission.setDescription(extras.getString("mission_desc"));
                desc.setText(extras.getString("mission_desc"));
                temp=extras.getString("mission_start");
                mission.setStartDateTime(temp);
                start_date=temp.substring(0,temp.indexOf(" "));

                if(languageToLoad.equals("fa")) {
                    String[] Date = start_date.split("-");
                    CalendarTool ct = new CalendarTool();
                    ct.setGregorianDate(new Integer(Date[0]), new Integer(Date[1]), new Integer(Date[2]));
                    start_date = Utility.fixDateFormat_01(ct.getIranianDate());
                }else
                {
                    start_date = Utility.fixDateFormat_02(start_date);
                }
                start_date_btn.setText(start_date);
                String temp2=temp.substring(temp.indexOf(" ")+1);
                start_time_btn.setText(temp2);
                start_hour= new Integer(temp2.substring(0,2));
                start_minute= new Integer(temp2.substring(3,5));
                temp=extras.getString("mission_end");
                mission.setEndDateTime(temp);
                end_date=temp.substring(0,temp.indexOf(" "));
                if(languageToLoad.equals("fa")) {
                    String[] Date2 = end_date.split("-");
                    CalendarTool ct2 = new CalendarTool();
                    ct2.setGregorianDate(new Integer(Date2[0]), new Integer(Date2[1]), new Integer(Date2[2]));
                    end_date = Utility.fixDateFormat_01(ct2.getIranianDate());
                }else
                {
                    end_date = Utility.fixDateFormat_02(end_date);
                }
                end_date_btn.setText(end_date);
                temp2=temp.substring(temp.indexOf(" ")+1);
                end_time_btn.setText(temp2);
                end_hour= new Integer(temp2.substring(0,2));
                end_minute= new Integer(temp2.substring(3,5));
            }
        }
        //get list of users of company
        CompanyModel company= new CompanyModel();
        company.setCompanyId(new BigInteger(company_id));
        company.setCompanyGuid(company_guid);
        CompanyController cc=new CompanyController(getApplication());
        cc.addObserver((Observer) this);
        cc.listOfMember(company);
    }
    public void editMission(View view){
        String missionperson = "";
        SparseBooleanArray checked = lv.getCheckedItemPositions();
        for (int i = 0; i < lv.getCount(); i++) {
            if (checked.get(i)) {
                missionperson += listOfIdOfUsers.get(i) + ",";
            }
        }
        if(!title.getText().toString().equals("") && !desc.getText().toString().equals("") && !missionperson.equals("")) {
            mission.setTitle(title.getText().toString());
            mission.setDescription(desc.getText().toString());

            SessionModel session = new SessionModel(getApplicationContext());
            final String languageToLoad = session.getLanguageCode();

            if(languageToLoad.equals("fa")) {
                String[] Date = start_date.split("/");
                CalendarTool ct = new CalendarTool();
                ct.setIranianDate(new Integer(Date[0]), new Integer(Date[1]), new Integer(Date[2]));
                start_date = Utility.fixDateFormat_01(ct.getGregorianDate());
            }
            mission.setStartDateTime(start_date + " " + zeroAdder(start_hour) + ":" + zeroAdder(start_minute)+ ":00");
            if(languageToLoad.equals("fa")) {
                String[] Date = end_date.split("/");
                CalendarTool ct1 = new CalendarTool();
                ct1.setIranianDate(new Integer(Date[0]), new Integer(Date[1]), new Integer(Date[2]));
                end_date = Utility.fixDateFormat_01(ct1.getGregorianDate());
            }
            mission.setEndDateTime(end_date + " " + zeroAdder(end_hour) + ":" + zeroAdder(end_minute)+ ":00");
            missionperson = missionperson.substring(0, missionperson.length() - 1);
            pDialog.show();
            MissionController mc = new MissionController(getApplicationContext());
            mc.addObserver((Observer) this);
            mc.edit(company,mission,missionperson);
        }else{
            Utility.displayToast(getApplicationContext(),getString(R.string.error_defective_information),Toast.LENGTH_LONG);
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
                        Button start_date_me= (Button) findViewById(R.id.start_date_me);
                        start_date_me.setText(start_date);

                        Utility.displayToast(context, persianCalendar.getPersianYear() + "/" + persianCalendar.getPersianMonth() + "/" + persianCalendar.getPersianDay(), Toast.LENGTH_SHORT);
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
                        Button end_date_me= (Button) findViewById(R.id.end_date_me);
                        end_date_me.setText(end_date);
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
                        timePickerListener, start_hour, start_minute,false);
            case TIME_DIALOG_ID_1:
                // set time picker as current time
                flag=false;
                return new TimePickerDialog(this,
                        timePickerListener, end_hour, end_minute,false);
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
                        Button start_time_me = (Button) findViewById(R.id.start_time_me);
                        start_time_me.setText(new StringBuilder().append(pad(start_hour))
                                .append(":").append(pad(start_minute)));
                    }else{
                        end_hour = selectedHour;
                        end_minute = selectedMinute;

                        // set current time into textview
                        Button end_time_me = (Button) findViewById(R.id.end_time_me);
                        end_time_me.setText(new StringBuilder().append(pad(end_hour))
                                .append(":").append(pad(end_minute)));
                    }

                }
            };

    private static String pad(int c) {
        if (c >= 10)
            return String.valueOf(c);
        else
            return "0" + String.valueOf(c);
    }

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
                    MissionIndexActivity.mia.finish();
                    Intent i = new Intent(MissionEditActivity.this,MissionIndexActivity.class);
                    i.putExtra("company_id", company.getCompanyId().toString());
                    i.putExtra("company_guid", company.getCompanyGuid());
                    MissionEditActivity.this.startActivity(i);
                    Utility.displayToast(getApplicationContext(),getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    finish();
                }
            }
            else if(arg instanceof ArrayList)
            {
                if(((ArrayList) arg).get(0).toString().equals(RequestRespondModel.TAG_LIST_MEMBERS_MISSION))
                {
                    ArrayList<UserModel> temp=new ArrayList<>();
                    temp= (ArrayList<UserModel>) ((ArrayList) arg).get(1);
                    for(UserModel u:temp){
                        userInMission.add(u.getId().toString());
                    }
                    for(int i=0;i<lv.getCount();i++){
                        if(userInMission.contains(listOfIdOfUsers.get(i))){
                            lv.setItemChecked(i,true);
                        }
                    }
                }else {
                    List<UserModel> users= (List<UserModel>) arg;
                    fillList(users);
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
    //fill list
    private void fillList(List<UserModel> users) {
        //make list
        ListView lv = (ListView) findViewById(R.id.user_of_mission_me);
        List<String> your_array_list = new ArrayList<String>();
        listOfIdOfUsers=new ArrayList<String>();
        for(UserModel user:users){
            your_array_list.add(user.getName()+" "+user.getFamily());
            listOfIdOfUsers.add(user.getId().toString());
        }

        ArrayAdapter<String> arrayAdapter = new ArrayAdapter<String>(
                context,
                android.R.layout.simple_list_item_multiple_choice,
                your_array_list);
        lv.setChoiceMode(ListView.CHOICE_MODE_MULTIPLE);
        lv.setAdapter(arrayAdapter);
        //get list of users in missions
        MissionController mc = new MissionController(getApplicationContext());
        mc.addObserver((Observer) this);
        mc.listOfMember(mission);
    }
    private String zeroAdder(int t){
        String temp=t+"";
        if(t<10)
            temp="0"+temp;
        return temp;
    }
    public View getViewByPosition(int pos, ListView listView) {
        final int firstListItemPosition = listView.getFirstVisiblePosition();
        final int lastListItemPosition = firstListItemPosition + listView.getChildCount() - 1;

        if (pos < firstListItemPosition || pos > lastListItemPosition ) {
            return listView.getAdapter().getView(pos, null, listView);
        } else {
            final int childIndex = pos - firstListItemPosition;
            return listView.getChildAt(childIndex);
        }
    }
    //Miladi date picker
    private void datePicker(final boolean start){
        DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear,
                                  int dayOfMonth) {
                // Auto-generated method stub
                myCalendar.set(Calendar.YEAR, year);
                myCalendar.set(Calendar.MONTH, monthOfYear);
                myCalendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);
                updateLabel(start);
            }
        };
        new DatePickerDialog(MissionEditActivity.this, date, myCalendar
                .get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),
                myCalendar.get(Calendar.DAY_OF_MONTH)).show();
    }
    private void updateLabel(boolean start) {
        String myFormat = "yyyy/MM/dd"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);
        String date=sdf.format(myCalendar.getTime());
        if(start){
            start_date=date;
            Button start_date_ma= (Button) findViewById(R.id.start_date_me);
            start_date_ma.setText(start_date);
            Utility.displayToast(context, date, Toast.LENGTH_SHORT);
        }
        else{
            end_date=date;
            Button start_date_ma= (Button) findViewById(R.id.end_date_me);
            start_date_ma.setText(end_date);
            Utility.displayToast(context, date, Toast.LENGTH_SHORT);
        }
    }
}
