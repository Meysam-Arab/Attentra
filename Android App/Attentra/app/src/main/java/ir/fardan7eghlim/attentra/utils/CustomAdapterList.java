package ir.fardan7eghlim.attentra.utils;

import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.AlertDialog;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.AttendanceController;
import ir.fardan7eghlim.attentra.controllers.CompanyController;
import ir.fardan7eghlim.attentra.controllers.MissionController;
import ir.fardan7eghlim.attentra.controllers.TrackController;
import ir.fardan7eghlim.attentra.controllers.UserController;
import ir.fardan7eghlim.attentra.models.AttendanceModel;
import ir.fardan7eghlim.attentra.models.CompanyModel;
import ir.fardan7eghlim.attentra.models.MissionModel;
import ir.fardan7eghlim.attentra.models.ModuleModel;
import ir.fardan7eghlim.attentra.models.PaymentModel;
import ir.fardan7eghlim.attentra.models.RequestRespondModel;
import ir.fardan7eghlim.attentra.models.SessionModel;
import ir.fardan7eghlim.attentra.models.TrackModel;
import ir.fardan7eghlim.attentra.models.UserModel;
import ir.fardan7eghlim.attentra.models.UserTypeModel;
import ir.fardan7eghlim.attentra.views.attendance.AttendanceEditActivity;
import ir.fardan7eghlim.attentra.views.attendance.AttendanceIndexActivity;
import ir.fardan7eghlim.attentra.views.company.CompanyEditActivity;
import ir.fardan7eghlim.attentra.views.company.CompanyUserListActivity;
import ir.fardan7eghlim.attentra.views.mission.MissionEditActivity;
import ir.fardan7eghlim.attentra.views.mission.MissionIndexActivity;
import ir.fardan7eghlim.attentra.views.mission.MissionUsersActivity;
import ir.fardan7eghlim.attentra.views.module.ModulePurchActivity;
import ir.fardan7eghlim.attentra.views.track.TrackIndexActivity;
import ir.fardan7eghlim.attentra.views.track.TrackListActivity;
import ir.fardan7eghlim.attentra.views.user.UserHomeActivity;
import ir.fardan7eghlim.attentra.views.user.UserLoginActivity;

public class CustomAdapterList extends BaseAdapter implements Observer{

    private Context context;
    private String Tag;
    private List<Object> List;
    private static LayoutInflater inflater=null;
    private CustomAdapterList CAL=this;
    private Object foregn_key_obj;
    private Button btn_dialog_05;
    private String request_type;
    private Map<String, Button> btns_for_change = new HashMap<String, Button>();
    private BigInteger current_user_id_for_change;

    public CustomAdapterList(Context c, List<Object> list,String tag) {
        // Auto-generated constructor stub
        List=list;
        Tag=tag.toLowerCase();
        context=c;
        inflater = (LayoutInflater)context.
                 getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        request_type = null;
        current_user_id_for_change = null;


    }
    public CustomAdapterList(Context c, List<Object> list,String tag,Object obj) {
        // Auto-generated constructor stub
        List=list;
        Tag=tag.toLowerCase();
        context=c;
        foregn_key_obj=obj;
        inflater = (LayoutInflater)context.
                getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        request_type = null;
        current_user_id_for_change = null;

    }
    @Override
    public int getCount() {
        // Auto-generated method stub
        return List.size();
    }

    @Override
    public Object getItem(int position) {
        // Auto-generated method stub
        return position;
    }

    @Override
    public long getItemId(int position) {
        // Auto-generated method stub
        return position;
    }

    public void updateAdapter(List<Object> list) {
        this.List= list;
        //and call notifyDataSetChanged
        notifyDataSetChanged();
    }

    @Override
    public void update(Observable o, Object arg) {
        DialogModel.hide();
        if(arg != null)
        {
            if ( arg instanceof Boolean)
            {
                if(Boolean.parseBoolean(arg.toString()) == false )
                {
                    Utility.displayToast(context,context.getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
                    request_type = null;
                    current_user_id_for_change = null;

                }
                else{
                    Utility.displayToast(context,context.getString(R.string.msg_OperationSuccess), Toast.LENGTH_LONG);
                    if(request_type.equals(RequestRespondModel.TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY))
                    {
                        if (btns_for_change.get(current_user_id_for_change.toString()).getText().equals(context.getString(R.string.btn_AllowSelfRoll)))
                            btns_for_change.get(current_user_id_for_change.toString()).setText(context.getString(R.string.btn_DisallowSelfRoll));
                        else
                            btns_for_change.get(current_user_id_for_change.toString()).setText(context.getString(R.string.btn_AllowSelfRoll));
                    }
                    else
                    {
                        //meysam - do nothing
                    }
                    request_type = null;
                    current_user_id_for_change = null;

                }
            }
            else if(arg instanceof Integer)
            {
                if(Integer.parseInt(arg.toString()) == RequestRespondModel.ERROR_AUTH_FAIL_CODE )
                {
                    Utility.displayToast(context.getApplicationContext(),context.getApplicationContext().getString(R.string.error_auth_fail), Toast.LENGTH_LONG);
                    SessionModel session = new SessionModel(context.getApplicationContext());
                    session.logoutUser(true);

                    Intent intents = new Intent(context, UserLoginActivity.class);
                    intents.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                            | Intent.FLAG_ACTIVITY_CLEAR_TOP
                            | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    context.startActivity(intents);

                }else {
                    Utility.displayToast(context.getApplicationContext(),new RequestRespondModel(context).getErrorCodeMessage(new Integer(arg.toString())), Toast.LENGTH_LONG);

                }
            }
            else
            {
                Utility.displayToast(context.getApplicationContext(),context.getString(R.string.msg_OperationError), Toast.LENGTH_LONG);
            }
        }
    }

    public class Holder
    {
        LinearLayout row_bg;
        TextView text_first;
        TextView text_date_from;
        TextView text_date_to;
        ImageView show_option_detail_btn;
        ImageView avatar;
        FrameLayout progressBar;
        FrameLayout row_bg_avatar;
        LinearLayout base_row_content;

        public Holder(LinearLayout row_bg, TextView text_first, TextView text_date_from, TextView text_date_to, ImageView show_option_detail_btn,   ImageView avatar,FrameLayout progressBar,FrameLayout row_bg_avatar,LinearLayout base_row_content) {
            this.row_bg = row_bg;
            this.text_first = text_first;
            this.text_date_from = text_date_from;
            this.text_date_to = text_date_to;
            this.show_option_detail_btn = show_option_detail_btn;
            this.avatar=avatar;
            this.progressBar=progressBar;
            this.row_bg_avatar=row_bg_avatar;
            this.base_row_content=base_row_content;
        }
    }
    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {

        // Auto-generated method stub
        final Holder holder=new Holder((LinearLayout) new LinearLayout(context.getApplicationContext()),(TextView)new TextView(context.getApplicationContext()),  (TextView) new TextView(context.getApplicationContext()), (TextView) new TextView(context.getApplicationContext()), (ImageView) new ImageView(context.getApplicationContext()), (ImageView) new ImageView(context.getApplicationContext()),new FrameLayout(context.getApplicationContext()),new FrameLayout(context.getApplicationContext()),new LinearLayout(context.getApplicationContext()));
        final View rowView= inflater.inflate(R.layout.row_list_01, null);
        holder.text_first= (TextView) rowView.findViewById(R.id.text_first);
        holder.text_date_from= (TextView) rowView.findViewById(R.id.text_date_from);
        holder.text_date_to= (TextView) rowView.findViewById(R.id.text_date_to);
        holder.show_option_detail_btn= (ImageView) rowView.findViewById(R.id.show_option_detail_btn);
        holder.row_bg= (LinearLayout) rowView.findViewById(R.id.row_bg);
        holder.avatar= (ImageView) rowView.findViewById(R.id.avatar_in_row);
        holder.row_bg_avatar= (FrameLayout) rowView.findViewById(R.id.row_bg_avatar);
        holder.base_row_content= (LinearLayout) rowView.findViewById(R.id.base_row_content);

        final Dialog d=new Dialog(context);
        d.requestWindowFeature(Window.FEATURE_NO_TITLE);
        d.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        d.setContentView(R.layout.option_dialog);

        holder.progressBar= (FrameLayout) rowView.findViewById(R.id.row_progress_01);
        holder.progressBar.setVisibility(View.GONE);

        final Button btn_dialog_01= (Button) d.findViewById(R.id.btn_dialog_01);
        final Button btn_dialog_02= (Button) d.findViewById(R.id.btn_dialog_02);
        final Button btn_dialog_03= (Button) d.findViewById(R.id.btn_dialog_03);
        final Button btn_dialog_04= (Button) d.findViewById(R.id.btn_dialog_04);
        btn_dialog_05= (Button) d.findViewById(R.id.btn_dialog_05);
        final Button btn_dialog_dlt= (Button) d.findViewById(R.id.btn_dialog_dlt);
        final TextView messForDelete= (TextView) d.findViewById(R.id.messForDelete);
        final Button btn_dialog_edt= (Button) d.findViewById(R.id.btn_dialog_edt);
        final Button btn_dialog_yDlt= (Button) d.findViewById(R.id.btn_dialog_yDlt);
        final Button btn_dialog_nDlt= (Button) d.findViewById(R.id.btn_dialog_nDlt);
        final LinearLayout delete_link_message= (LinearLayout) d.findViewById(R.id.delete_link_message);
        delete_link_message.setVisibility(View.GONE);

        holder.show_option_detail_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //for option code here
                d.show();
            }
        });
        btn_dialog_dlt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //for option code here
                delete_link_message.setVisibility(View.VISIBLE);
                messForDelete.setText(context.getString(R.string.dlg_DoYouReallyWantToDelete));
            }
        });
        btn_dialog_nDlt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //for option code here
                delete_link_message.setVisibility(View.GONE);
            }
        });

        switch (Tag){
            case RequestRespondModel.TAG_LIST_MEMBERS_COMPANY://if we have list of users
                final UserModel user=(UserModel) List.get(position);
                //avatar
                if(user.getProfilePicture()==null){
                    if(user.getGender().equals("1"))
                        holder.avatar.setImageResource(R.drawable.female);
                    else
                        holder.avatar.setImageResource(R.drawable.male);
                }else
                    holder.avatar.setImageBitmap(user.getProfilePicture());

                holder.text_first.setText(user.getName()+" "+user.getFamily());
                holder.text_date_from.setText(new UserTypeModel(user.getUserType()).convertTypeToString(context));
                holder.text_date_to.setVisibility(View.GONE);
                btn_dialog_01.setText(context.getString(R.string.Details));
                btn_dialog_01.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.hide();
                        showDetailsOfUser(user);
                    }
                });
                btn_dialog_02.setText(context.getString(R.string.Attendance));
                btn_dialog_02.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, AttendanceIndexActivity.class);
                        i.putExtra("user_id", user.getId().toString());
                        i.putExtra("user_guid", user.getGuid());
                        d.hide();
                        context.startActivity(i);
                    }
                });
                btn_dialog_03.setText(context.getString(R.string.Tracking));
                btn_dialog_03.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, TrackIndexActivity.class);
                        i.putExtra("user_id", user.getId().toString());
                        i.putExtra("user_guid", user.getGuid());
                        d.hide();
                        context.startActivity(i);
                    }
                });
                btn_dialog_04.setText(context.getString(R.string.Delete_phoneCode));
                btn_dialog_04.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
                        alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListenerPhoneCode);
                        alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListenerPhoneCode);
                        alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
                        alertDialogBuilder.show();
                        foregn_key_obj=user;
                        request_type = RequestRespondModel.TAG_REMOVE_PHONE_CODE_USER;
                        d.hide();
                    }
                });
                if(user.getSelfRollCallAllowed())
                    btn_dialog_05.setText(context.getString(R.string.btn_DisallowSelfRoll));
                else
                    btn_dialog_05.setText(context.getString(R.string.btn_AllowSelfRoll));
                btn_dialog_05.setVisibility(View.VISIBLE);
                btns_for_change.put(user.getId().toString(),btn_dialog_05);
                btn_dialog_05.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
                        alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListenerSelfRollCall);
                        alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListenerSelfRollCall);
                        alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
                        alertDialogBuilder.show();
                        ArrayList temp = new ArrayList<Object>();
                        temp.add(0,user);
                        if(btns_for_change.get(user.getId().toString()).getText().equals(context.getString(R.string.btn_DisallowSelfRoll)))
                            temp.add("0");
                        else
                            temp.add("1");
                        foregn_key_obj = temp;
                        request_type = RequestRespondModel.TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY;
                        current_user_id_for_change = user.getId();

//                        d.hide();
                    }
                });

                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.show();
                    }
                });
                btn_dialog_yDlt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        UserController uc = new UserController(context);
                        uc.addObserver((Observer) context);
                        uc.delete(user);
                        d.hide();
                        DialogModel.show(context);
                    }
                });
//                btn_dialog_edt.setOnClickListener(new View.OnClickListener() {
//                    @Override
//                    public void onClick(View v) {
//                        Utility.displayToast(context,context.getResources().getString(R.string.msg_MessageHeadToWebSite),Toast.LENGTH_LONG);
//                    }
//                });
                btn_dialog_edt.setVisibility(View.GONE);
                break;
            case RequestRespondModel.TAG_INDEX_COMPANY://if we have list of company
                final CompanyModel company=(CompanyModel) List.get(position);
                //avatar
                if(company.getCompanyPicture()==null)
                    holder.avatar.setImageResource(R.drawable.company);
                else
                    holder.avatar.setImageBitmap(company.getCompanyPicture());

                holder.text_date_from.setText(company.getName());
                holder.text_date_from.setTextSize(TypedValue.COMPLEX_UNIT_PX, Float.parseFloat("40"));
                holder.text_date_to.setVisibility(View.GONE);
                holder.text_first.setVisibility(View.GONE);

                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.show();
                    }
                });

                btn_dialog_01.setText(context.getString(R.string.btn_CheckYourselfIn));
                btn_dialog_01.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
                        alertDialogBuilder.setPositiveButton(R.string.dlg_Yes, dialogClickListener);
                        alertDialogBuilder.setNegativeButton(R.string.dlg_No, dialogClickListener);
                        alertDialogBuilder.setMessage(R.string.dlg_msg_Exit);
                        alertDialogBuilder.show();
                        foregn_key_obj=company.getCompanyId();
                        d.hide();

                    }
                });
                btn_dialog_02.setText(context.getString(R.string.List_Of_Users));
                btn_dialog_02.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, CompanyUserListActivity.class);
                        i.putExtra("company_id", company.getCompanyId().toString());
                        i.putExtra("company_guid", company.getCompanyGuid());
                        d.hide();
                        context.startActivity(i);

                    }
                });
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setText(context.getString(R.string.Missions));
                btn_dialog_04.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, MissionIndexActivity.class);
                        i.putExtra("company_id", company.getCompanyId().toString());
                        i.putExtra("company_guid", company.getCompanyGuid());
                        d.hide();
                        context.startActivity(i);

                    }
                });
                btn_dialog_edt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, CompanyEditActivity.class);
                        i.putExtra("company_id", company.getCompanyId().toString());
                        i.putExtra("company_guid", company.getCompanyGuid());
                        i.putExtra("company_name", company.getName());
                        i.putExtra("company_timeZone", company.getTimeZone());
//                        i.putExtra("company_avatar", company.getCompanyPicture());
                        String pathOfAvatar=new FileProcessor().saveToInternalStorage(context,company.getCompanyPicture(),"Temp",company.getCompanyGuid()+".png");
                        i.putExtra("company_avatar", pathOfAvatar);
                        d.hide();
                        context.startActivity(i);
                    }
                });
                btn_dialog_yDlt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        CompanyController cc = new CompanyController(context);
                        cc.addObserver((Observer) context);
                        cc.deleteCompany(company);
                        d.hide();
                        DialogModel.show(context);
                    }
                });
                break;
            case RequestRespondModel.TAG_INDEX_MISSION://if we have list of missions
                final MissionModel mission=(MissionModel) List.get(position);
                holder.avatar.setImageResource(R.drawable.mission_green);
                holder.text_first.setText(mission.getTitle());
                holder.text_date_from.setText(Utility.getCurrectDateByLanguage(context,mission.getStartDateTime()));
                holder.text_date_from.setTextSize(TypedValue.COMPLEX_UNIT_PX, Float.parseFloat("22"));
                holder.text_date_to.setText(Utility.getCurrectDateByLanguage(context,mission.getEndDateTime()));
                holder.text_date_to.setTextSize(TypedValue.COMPLEX_UNIT_PX, Float.parseFloat("22"));
//                btn_dialog_02.setVisibility(View.GONE);
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setVisibility(View.GONE);
                btn_dialog_01.setText(context.getString(R.string.Details));
                btn_dialog_01.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        showDetailsOfMission(mission);
                        d.hide();
                    }
                });
                btn_dialog_02.setText(context.getString(R.string.Employees_in_Mission));
                btn_dialog_02.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, MissionUsersActivity.class);
                        i.putExtra("mission_id",mission.getMissionId().toString());
                        i.putExtra("mission_guid",mission.getMissionGuid());
                        d.hide();
                        context.startActivity(i);
                    }
                });
                btn_dialog_yDlt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        MissionController mc = new MissionController(context);
                        mc.addObserver((Observer) context);
                        mc.delete(mission);
                        d.hide();
                        DialogModel.show(context);
                    }
                });
                btn_dialog_edt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, MissionEditActivity.class);
                        i.putExtra("mission_id",mission.getMissionId().toString());
                        i.putExtra("mission_guid",mission.getMissionGuid());
                        i.putExtra("mission_title",mission.getTitle());
                        i.putExtra("mission_desc",mission.getDescription());
                        i.putExtra("mission_start",mission.getStartDateTime());
                        i.putExtra("mission_end",mission.getEndDateTime());
                        i.putExtra("company_id", ((CompanyModel)foregn_key_obj).getCompanyId().toString());
                        i.putExtra("company_guid", ((CompanyModel)foregn_key_obj).getCompanyGuid().toString());
                        d.hide();
                        context.startActivity(i);
                    }
                });
                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
//                        showDetailsOfMission(mission);
                        d.show();
                    }
                });
                break;
            case RequestRespondModel.TAG_INDEX_TRACK://if we have list of tracks of user
                final TrackModel track=(TrackModel) List.get(position);
                holder.avatar.setImageResource(R.drawable.marker);
                holder.avatar.getLayoutParams().width = 90;
//                holder.avatar.setMaxWidth();
                holder.text_first.setVisibility(View.GONE);
                holder.text_date_from.setText(context.getString(R.string.StartDate)+": "+Utility.getCurrectDateByLanguage(context,Utility.getDateTimeFromTrackGroup(track.getTrackGroup())));
                holder.text_date_to.setVisibility(View.GONE);
                btn_dialog_01.setVisibility(View.GONE);
                btn_dialog_02.setVisibility(View.GONE);
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setVisibility(View.GONE);
                btn_dialog_edt.setVisibility(View.GONE);
                btn_dialog_yDlt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        TrackController mc = new TrackController(context);
                        mc.addObserver((Observer) context);
                        mc.delete(track);
                        d.hide();
                        DialogModel.show(context);
                    }
                });
                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        //for option code here
                        TrackModel track=(TrackModel) List.get(position);
//                        Toast.makeText(context,track.getTrackGroup().toString(),Toast.LENGTH_LONG);
                        Intent i = new Intent(context, TrackListActivity.class);
                        i.putExtra("track_group",track.getTrackGroup().toString());
                        context.startActivity(i);
                    }
                });

                break;
            case RequestRespondModel.TAG_LIST_MEMBERS_MISSION://if we have list of users in mission
                final UserModel temp_user=(UserModel) List.get(position);
                //avatar
                if(temp_user.getProfilePicture()==null){
                    if(temp_user.getGender().equals("1"))
                        holder.avatar.setImageResource(R.drawable.female);
                    else
                        holder.avatar.setImageResource(R.drawable.male);
                }else
                    holder.avatar.setImageBitmap(temp_user.getProfilePicture());

                holder.text_first.setText(temp_user.getName()+" "+temp_user.getFamily());
                holder.text_date_from.setText(new UserTypeModel(temp_user.getUserType()).convertTypeToString(context));
                holder.text_date_to.setVisibility(View.GONE);
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setVisibility(View.GONE);
                btn_dialog_01.setText(context.getString(R.string.Details));
                btn_dialog_01.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.hide();
                        showDetailsOfUser(temp_user);
                    }
                });
                btn_dialog_02.setText(context.getString(R.string.Tracking));
                btn_dialog_02.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, TrackIndexActivity.class);
                        i.putExtra("user_id", temp_user.getId().toString());
                        i.putExtra("user_guid", temp_user.getGuid());
                        d.hide();
                        context.startActivity(i);
                    }
                });
                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.show();
                    }
                });
                btn_dialog_dlt.setVisibility(View.GONE);
                btn_dialog_edt.setVisibility(View.GONE);
                break;
            case RequestRespondModel.TAG_INDEX_ATTENDANCE://if we have list of attendance
                final AttendanceModel attendance=(AttendanceModel) List.get(position);
                if(attendance.getEndDateTime().isEmpty() || attendance.getEndDateTime().equals("null")){
                    holder.avatar.setImageResource(R.drawable.hourglass);
                    holder.text_date_to.setText(context.getString(R.string.msg_present));
                }else{
                    holder.avatar.setImageResource(R.drawable.selected);
                    holder.text_date_to.setText(Utility.getCurrectDateByLanguage(context,attendance.getEndDateTime()));
                }
                holder.text_first.setVisibility(View.GONE);
                holder.text_date_from.setText(Utility.getCurrectDateByLanguage(context,attendance.getStartDateTime()));
                btn_dialog_01.setVisibility(View.GONE);
                btn_dialog_02.setVisibility(View.GONE);
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setVisibility(View.GONE);


                btn_dialog_yDlt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        AttendanceController ac = new AttendanceController(context);
                        ac.addObserver((Observer) context);
                        ac.delete(attendance);
                        d.hide();
                        DialogModel.show(context);
                    }
                });
                btn_dialog_edt.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, AttendanceEditActivity.class);
                        i.putExtra("user_id", (String) foregn_key_obj);
                        i.putExtra("attendance_id", attendance.getAttendanceId().toString());
                        i.putExtra("attendance_guid", attendance.getAttendanceGuid());
                        i.putExtra("attendance_sdt", attendance.getStartDateTime());
                        if(!attendance.getEndDateTime().equals("null")) i.putExtra("attendance_edt", attendance.getEndDateTime());
                        d.hide();
                        context.startActivity(i);
                    }
                });
                if(new SessionModel(context).getCurrentUser().getUserType().equals(UserTypeModel.EMPLOYEE) ||
                        new SessionModel(context).getCurrentUser().getUserType().equals(UserTypeModel.Device) )
                {
                    btn_dialog_yDlt.setVisibility(View.GONE);
                    btn_dialog_edt.setVisibility(View.GONE);
                    holder.show_option_detail_btn.setVisibility(View.GONE);
                }
                else
                {

                    holder.row_bg.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            d.show();
                        }
                    });

                }
                break;
            case RequestRespondModel.TAG_INDEX_COMPANY_MODULE:
            case RequestRespondModel.TAG_INDEX_USER_MODULE://if we have list of modules
                final ModuleModel moduleModel=(ModuleModel) List.get(position);
//                List<ModuleModel> companyModules= (java.util.List<ModuleModel>) foregn_key_obj;
                holder.text_first.setText(moduleModel.getTitle());
                if(moduleModel.getDescription().length()> 50)
                    holder.text_date_from.setText(moduleModel.getDescription().substring(0,50)+"...");
                else
                    holder.text_date_from.setText(moduleModel.getDescription());
                String temp="";
                if(Tag == RequestRespondModel.TAG_INDEX_COMPANY_MODULE)
                {
//                    if(moduleModel.getStored()!=null && !moduleModel.getStored().isEmpty() && !moduleModel.getStored().equals("null"))
//                        temp+=context.getString(R.string.used)+Utility.getCurrectDateByLanguage(context,moduleModel.getStored());
                    if(moduleModel.getPurchased()!=null && !moduleModel.getPurchased().isEmpty() && !moduleModel.getPurchased().equals("null"))
                        temp+=" "+context.getString(R.string.DeadLine)+" "+Utility.getCurrectDateByLanguage(context,moduleModel.getPurchased());

                }
                else
                {
                    if(moduleModel.getStored()!=null && !moduleModel.getStored().isEmpty() && !moduleModel.getStored().equals("null"))
                        temp+=context.getString(R.string.used)+moduleModel.getStored();
                    if(moduleModel.getPurchased()!=null && !moduleModel.getPurchased().isEmpty() && !moduleModel.getPurchased().equals("null"))
                        temp+=" "+context.getString(R.string.from)+" "+moduleModel.getPurchased();

                }
                holder.text_date_to.setText(temp);
                if(!ModuleModel.TimeRelatedModuleIds.contains(moduleModel.getModuleId()))
                    if(moduleModel.getStored()!=null && !moduleModel.getStored().isEmpty() && moduleModel.getPurchased()!=null && !moduleModel.getPurchased().isEmpty() && !moduleModel.getPurchased().equals("null") && !moduleModel.getStored().equals("null")) {
                        ProgressBar pb = (ProgressBar) rowView.findViewById(R.id.progress_01);
                        pb.setMax(new Integer(moduleModel.getPurchased()));
                        pb.setProgress(new Integer(moduleModel.getStored()));
                        holder.progressBar.setVisibility(View.VISIBLE);
                    }
                btn_dialog_01.setText(context.getString(R.string.Description));
                btn_dialog_01.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        showDescription(moduleModel.getDescription());
                        d.hide();
                    }
                });
                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.show();
                    }
                });
                btn_dialog_02.setText(context.getString(R.string.Purch)+"("+Math.round(moduleModel.getPrice())+" "+")"+context.getString(R.string.Tooman));
                final String company_id= (String) foregn_key_obj;
                btn_dialog_02.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent i = new Intent(context, ModulePurchActivity.class);
                        i.putExtra("modul_id", moduleModel.getModuleId().toString());
                        i.putExtra("modul_price", moduleModel.getPrice().toString());
                        i.putExtra("modul_description", moduleModel.getDescription());
                        if(company_id!=null)
                            i.putExtra("company_id", company_id);
                        d.hide();
                        context.startActivity(i);
                    }
                });
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setVisibility(View.GONE);
                btn_dialog_edt.setVisibility(View.GONE);
                btn_dialog_dlt.setVisibility(View.GONE);
                if(moduleModel.getStatus()==1){
                    //it s over
                    holder.row_bg.setBackgroundResource(R.drawable.button_04);
                    holder.avatar.setImageResource(R.drawable.finish_flag);
                }else if(moduleModel.getStatus()==2){
                    //it has not been bought
                    holder.row_bg.setBackgroundResource(R.drawable.button_05);
                    holder.avatar.setImageResource(R.drawable.warning);
                }else{
                    holder.avatar.setImageResource(R.drawable.checked);
                }
                if(moduleModel.getPurchased().equals("null")){
                    holder.avatar.setImageResource(R.drawable.bank);
                }
                break;
            case RequestRespondModel.TAG_INDEX_PAYMENT: //if we have list of payments
                final PaymentModel paymentModel=(PaymentModel) List.get(position);
                btn_dialog_02.setVisibility(View.GONE);
                btn_dialog_03.setVisibility(View.GONE);
                btn_dialog_04.setVisibility(View.GONE);
                btn_dialog_edt.setVisibility(View.GONE);
                btn_dialog_dlt.setVisibility(View.GONE);
                holder.avatar.setImageResource(R.drawable.bank);
                holder.text_first.setText(Math.round(new Float(paymentModel.getAmount()))+context.getString(R.string.Tooman));
                holder.text_date_from.setText(context.getString(R.string.Description)+":"+paymentModel.getDescription());
                holder.text_date_to.setText(context.getString(R.string.status)+":"+new PaymentModel(context).getMessage(new Integer(paymentModel.getStatus())));
                if(paymentModel.getStatus() != PaymentModel.MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL)
                {
                    holder.row_bg.setBackgroundResource(R.drawable.button_04);
                }
//                holder.show_option_detail_btn.setVisibility(View.GONE);
                holder.row_bg.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        d.show();
                    }
                });
                btn_dialog_01.setText(context.getString(R.string.Description));
                btn_dialog_01.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        showDescription(context.getString(R.string.lbl_followeup)+" "+paymentModel.getPayload());
                        d.hide();
                    }
                });
                break;
            case RequestRespondModel.TAG_INDEX_HOME:
                UserModel user_temp= (UserModel) List.get(position);
                holder.row_bg_avatar.setVisibility(View.GONE);
                holder.text_first.setText(user_temp.getName()+" "+user_temp.getFamily());
                holder.text_date_from.setText(user_temp.getUserCompanyName());
                String t1=user_temp.getUserAttendanceStartDate();
                String t2=user_temp.getUserAttendanceEndDate();
                String t="";
                if(t1.equals("null")){
                    t=context.getString(R.string.notHere);
                    holder.text_date_to.setTextColor(Color.parseColor("#944343"));
                }else if(t2.equals("null")){
                    t1=Utility.getCurrectDateByLanguage(context,t1);
                    t=t1+" ~ "+context.getString(R.string.isHere);
                    holder.text_date_to.setTextColor(Color.BLACK);
                    holder.text_date_to.setTextSize(TypedValue.COMPLEX_UNIT_PX, Float.parseFloat("25"));
                }else{
                    t1=Utility.getCurrectDateByLanguage(context,t1);
                    t2=Utility.getCurrectDateByLanguage(context,t2);
                    t=t1+" ~ "+t2;
                    holder.text_date_to.setTextSize(TypedValue.COMPLEX_UNIT_PX, Float.parseFloat("25"));
                }
                holder.text_date_to.setText(t);
                holder.show_option_detail_btn.setVisibility(View.GONE);
                LinearLayout.LayoutParams param2 = new LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        1.0f
                );
                holder.base_row_content.setLayoutParams(param2);
                break;
        }


        return rowView;
    }

    private void showDetailsOfUser(UserModel user) {
        final Dialog d2=new Dialog(context);
        d2.requestWindowFeature(Window.FEATURE_NO_TITLE);
        d2.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        d2.setContentView(R.layout.detail_dialog);
        ImageView avatar= (ImageView) d2.findViewById(R.id.avatar_iv_dd);
        avatar.setImageBitmap(user.getProfilePicture());
        TextView t1= (TextView) d2.findViewById(R.id.userType_sp_dd);
        t1.setText(new UserTypeModel(user.getUserType()).convertTypeToString(context));
        TextView t2= (TextView) d2.findViewById(R.id.Name_et_dd);
        t2.setText(user.getName());
        TextView t3= (TextView) d2.findViewById(R.id.Family_et_dd);
        t3.setText(user.getFamily());
        TextView t4= (TextView) d2.findViewById(R.id.Gender_sp_dd);
        t4.setText(user.getGender().equals(UserModel.MaleCodeString)?context.getString(R.string.male):context.getString(R.string.female));
        TextView t5= (TextView) d2.findViewById(R.id.userName_et_dd);
        t5.setText(user.getUserName());
        TextView t6= (TextView) d2.findViewById(R.id.Email_et_dd);
        t6.setText(user.getEmail());
        TextView t7= (TextView) d2.findViewById(R.id.Code_et_dd);
        t7.setText(user.getCode() == "null"?"":user.getCode());
        d2.show();
    }
    private void showDetailsOfMission(MissionModel mission) {
        final Dialog d2=new Dialog(context);
        d2.requestWindowFeature(Window.FEATURE_NO_TITLE);
        d2.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        d2.setContentView(R.layout.message_dialog);
        TextView txt= (TextView) d2.findViewById(R.id.message_box_dialog);
        txt.setText(mission.getDescription());
        Button btn= (Button) d2.findViewById(R.id.btn_mess_01);
        btn.setText(context.getString(R.string.btn_OK));
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                d2.hide();
            }
        });
        d2.show();
    }

    private void showDescription(String text){
        final Dialog d2=new Dialog(context);
        d2.requestWindowFeature(Window.FEATURE_NO_TITLE);
        d2.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        d2.setContentView(R.layout.simple_detail);
        TextView tv= (TextView) d2.findViewById(R.id.simpleDetail_Text01);
        tv.setText(text);
        d2.show();
    }
    //delete company
    public void deleteCompany(BigInteger company_id){
        List<CompanyModel> temp=(List<CompanyModel>)(List<?>)List;
        for(int i=0;i<temp.size();i++){
            if(temp.get(i).getCompanyId()==company_id){
                temp.remove(i);
                updateAdapter(new ArrayList<Object>(temp));
                return;
            }
        }
    }
    //delete mission
    public void deleteMission(BigInteger mission_id){
        List<MissionModel> temp=(List<MissionModel>)(List<?>)List;
        for(int i=0;i<temp.size();i++){
            if(temp.get(i).getMissionId()==mission_id){
                temp.remove(i);
                updateAdapter(new ArrayList<Object>(temp));
                return;
            }
        }
    }
    //delete mission
    public void deleteAttendance(BigInteger attendance_id){
        List<AttendanceModel> temp=(List<AttendanceModel>)(List<?>)List;
        for(int i=0;i<temp.size();i++){
            if(temp.get(i).getAttendanceId()==attendance_id){
                temp.remove(i);
                updateAdapter(new ArrayList<Object>(temp));
                return;
            }
        }
    }
    //delete track group
    public void deleteTrackGroup(BigInteger track_id){
        List<TrackModel> temp=(List<TrackModel>)(List<?>)List;
        for(int i=0;i<temp.size();i++){
            if(temp.get(i).getTrackId()==track_id){
                temp.remove(i);
                updateAdapter(new ArrayList<Object>(temp));
                return;
            }
        }
    }
    //delete user group
    public void deleteUser(BigInteger user_id){
        List<UserModel> temp=(List<UserModel>)(List<?>)List;
        for(int i=0;i<temp.size();i++){
            if(temp.get(i).getId()==user_id){
                temp.remove(i);
                updateAdapter(new ArrayList<Object>(temp));
                return;
            }
        }
    }
    //dialog for ceo attendance
    DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which) {
                case DialogInterface.BUTTON_POSITIVE:
                    //Yes button clicked
                    DialogModel.show(context);
                    AttendanceController ac=new AttendanceController(context);
                    ac.addObserver((Observer) context);
                    ac.storeAutoCeo(new AttendanceModel(), (BigInteger) foregn_key_obj,false, new SessionModel(context).getBooleanItem(SessionModel.KEY_CHECK_IN_OUT_MERGE,false));
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked
                    break;
            }
        }
    };
    //dialog for phone code
    DialogInterface.OnClickListener dialogClickListenerPhoneCode = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which) {
                case DialogInterface.BUTTON_POSITIVE:
                    //Yes button clicked
                    DialogModel.show(context);
                    UserController ac=new UserController(context);
                    ac.addObserver((Observer) CustomAdapterList.this);
                    ac.removePhoneCode((UserModel) foregn_key_obj);
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked
                    break;
            }
        }
    };

    //dialog for phone code
    DialogInterface.OnClickListener dialogClickListenerSelfRollCall = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which) {
                case DialogInterface.BUTTON_POSITIVE:
                    //Yes button clicked
                    DialogModel.show(context);
                    CompanyController cc=new CompanyController(context);
                    cc.addObserver((Observer) CustomAdapterList.this);
                    cc.changeSelfRollCallStatus((UserModel) ((ArrayList) foregn_key_obj).get(0),((ArrayList) foregn_key_obj).get(1).toString());
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    //No button clicked
                    break;
            }
        }
    };
}