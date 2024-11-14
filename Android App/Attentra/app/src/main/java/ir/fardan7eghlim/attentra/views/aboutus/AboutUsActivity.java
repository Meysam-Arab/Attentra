package ir.fardan7eghlim.attentra.views.aboutus;

import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.net.Uri;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.TextView;

import java.util.Locale;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.controllers.AboutUsController;
import ir.fardan7eghlim.attentra.models.AboutUsModel;

public class AboutUsActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_about_us);

//        WebView txt_description = (WebView) findViewById(R.id.txt_aboutus_description);
        AboutUsController aboutC = new AboutUsController(getApplication());
//        txt_description.setText(aboutC.readAboutUs());

        WebView view = (WebView) findViewById(R.id.txt_aboutus_description);
        WebSettings settings = view.getSettings();
        settings.setDefaultTextEncodingName("utf-8");
        String text;
        text = "<html><body><div align=\"center\" ><p align=\"justify\"  style=\"text-align:center;\">";
        text+= aboutC.readAboutUs();
        text+= "</p></div></body></html>";
        view.loadData(text, "text/html; charset=utf-8", "utf-8");
        view.setBackgroundColor(Color.TRANSPARENT);

        TextView tv_version = (TextView) findViewById(R.id.tv_vrsion);
        PackageInfo pInfo = null;
        String version = getString(R.string.error_undefined);
//        int verCode = pInfo.versionCode;
        try {
            pInfo = getPackageManager().getPackageInfo(getPackageName(), 0);
            version = getString(R.string.lbl_version)+" : " + pInfo.versionName;
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }

        tv_version.setText(version);


        Button btn_tutorial = (Button) findViewById(R.id.btn_tutorial);
        btn_tutorial.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String lang = Locale.getDefault().getLanguage();
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://www.attentra.ir/style/main/tutorial/app-"+lang+".pdf"));
                startActivity(browserIntent);
            }
        });

    }
}
