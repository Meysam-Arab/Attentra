package ir.fardan7eghlim.attentra.utils;

import android.content.Context;
import android.content.ContextWrapper;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.widget.ImageView;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

import ir.fardan7eghlim.attentra.R;

/**
 * Created by Amir on 3/15/2017.
 */

public class FileProcessor {
    public String saveToInternalStorage(Context c,Bitmap bitmapImage,String path,String name){
        ContextWrapper cw = new ContextWrapper(c);
        // path to /data/data/yourapp/app_data/imageDir
        File directory = cw.getDir(path, Context.MODE_PRIVATE);
        // Create imageDir
        File mypath=new File(directory,name);

        FileOutputStream fos = null;
        try {
            fos = new FileOutputStream(mypath);
            // Use the compress method on the BitMap object to write image to the OutputStream
            bitmapImage.compress(Bitmap.CompressFormat.PNG, 100, fos);
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            try {
                fos.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return directory.getAbsolutePath();
    }
    public Bitmap loadImageFromStorage(String path,String name)
    {
        Bitmap b=null;
        try {
            File f=new File(path, name);
            b = BitmapFactory.decodeStream(new FileInputStream(f));
            return b;
        }
        catch (FileNotFoundException e)
        {
            e.printStackTrace();
        }
        return b;
    }
    public boolean deleteFile(String path,String name){
        File f=new File(path, name);
        return f.delete();
    }
    public static Bitmap getResizedBitmap(Bitmap bm, int newWidth,int newHeight) {
        int width = bm.getWidth();
        int height = bm.getHeight();
        float scaleWidth = ((float) newWidth) / width;
        float scaleHeight = ((float) newHeight) / height;
        // CREATE A MATRIX FOR THE MANIPULATION
        Matrix matrix = new Matrix();
        // RESIZE THE BIT MAP
        matrix.postScale(scaleWidth, scaleHeight);

        // "RECREATE" THE NEW BITMAP
        Bitmap resizedBitmap = Bitmap.createBitmap(bm, 0, 0, width, height,
                matrix, false);

        return resizedBitmap;
    }
}
