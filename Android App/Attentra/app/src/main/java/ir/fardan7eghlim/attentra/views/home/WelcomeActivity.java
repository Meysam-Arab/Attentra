package ir.fardan7eghlim.attentra.views.home;

import android.app.Activity;
import android.app.Dialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.AnimationDrawable;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Window;
import android.webkit.WebView;
import android.widget.ImageView;

import java.util.Observable;
import java.util.Observer;

import ir.fardan7eghlim.attentra.R;

import static ir.fardan7eghlim.attentra.R.id.imageView;

public class WelcomeActivity extends Activity{

    Dialog f7e;
    ImageView logo;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_welcome);
        f7e=new Dialog(WelcomeActivity.this,android.R.style.Theme_Black_NoTitleBar_Fullscreen);
        f7e.requestWindowFeature(Window.FEATURE_NO_TITLE);
        f7e.getWindow().setBackgroundDrawable(new ColorDrawable(Color.WHITE));
        f7e.setContentView(R.layout.f7e_logo);
        logo= (ImageView) f7e.findViewById(R.id.logoImageView);
        logo.setBackgroundResource(R.drawable.welcome);
        AnimationDrawable anim = (AnimationDrawable) logo.getBackground();
        f7e.show();
        anim.start();
        Thread welcomeThread = new Thread() {

            @Override
            public void run() {
                try {
                    super.run();
                    sleep(5000);  //Delay of 5 seconds
                } catch (Exception e) {

                } finally {
                    Intent i = new Intent(WelcomeActivity.this,
                            HomeActivity.class);
                    startActivity(i);
                    finish();
                }
            }
        };
        welcomeThread.start();
    }
}
