package ir.fardan7eghlim.attentra.controllers;

import android.content.Context;

import java.util.Observable;

import ir.fardan7eghlim.attentra.models.AboutUsModel;

/**
 * Created by Sazegar pardazan on 07/03/2017.
 */

public class AboutUsController extends Observable {

    public Context cntx = null;

    public AboutUsController(Context cntx)
    {
        this.cntx = cntx;
    }

    public String readAboutUs()
    {
        AboutUsModel  about = new AboutUsModel( cntx);
        about.initialize();
        return about.getAboutUsText();

    }

}
