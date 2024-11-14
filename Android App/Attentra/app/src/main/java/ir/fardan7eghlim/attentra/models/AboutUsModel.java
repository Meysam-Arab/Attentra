package ir.fardan7eghlim.attentra.models;

import android.content.Context;
import android.content.res.Configuration;

import java.util.Locale;
import java.util.Scanner;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.interfaces.AboutUsInterface;

/**
 * Created by Sazegar pardazan on 07/03/2017.
 */

public class AboutUsModel implements AboutUsInterface {

    public String getAboutUsText() {
        return AboutUsText;
    }

    public void setAboutUsText(String aboutUsText) {
        AboutUsText = aboutUsText;
    }

    private String AboutUsText;


    public AboutUsModel()
    {
        this.AboutUsText = null;
    }

    private Context cntx;

    public AboutUsModel(Context cntx){
        this.AboutUsText = null;
        this.cntx = cntx;
    }


    @Override
    public void insert() {

    }

    @Override
    public void update() {

    }

    @Override
    public boolean delete() {
        return false;
    }

    public void initialize()
    {

        // SessionModel manager
        SessionModel session = new SessionModel(cntx);
        String languageToLoad =session.getLanguageCode();
        if (!languageToLoad.equals(null)) {
            Locale locale = new Locale(languageToLoad);
            Locale.setDefault(locale);
            Configuration config = new Configuration();
            config.locale = locale;
            cntx.getResources().updateConfiguration(config,
                    cntx.getResources().getDisplayMetrics());
        }

        Scanner s = null;
        if (Locale.getDefault().getDisplayLanguage().equals("English"))
        {
             s = new Scanner(cntx.getResources().openRawResource(R.raw.about_us_eng));
        }
        else{
             s = new Scanner(cntx.getResources().openRawResource(R.raw.about_us_per));
        }
        try {
            String tmp_txt = "";
            while (s.hasNext()) {
//                String word = s.next();
                tmp_txt += s.nextLine();
            }
            this.setAboutUsText(tmp_txt);
        }
        catch (Exception ex)
        {
            throw ex;
        }
        finally {
            s.close();
        }
    }
}
