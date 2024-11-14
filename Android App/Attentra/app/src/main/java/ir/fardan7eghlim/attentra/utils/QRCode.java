package ir.fardan7eghlim.attentra.utils;

import android.graphics.Bitmap;
import android.graphics.Color;
import android.util.Log;
import android.widget.ImageView;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.EncodeHintType;
import com.google.zxing.WriterException;
import com.google.zxing.common.BitMatrix;
import com.google.zxing.qrcode.QRCodeWriter;
import com.google.zxing.qrcode.decoder.ErrorCorrectionLevel;

import java.io.File;
import java.io.IOException;
import java.util.EnumMap;
import java.util.Map;

import ir.fardan7eghlim.attentra.R;

/**
 * Created by Meysam on 3/7/2017.
 */

public class QRCode {

    public Bitmap generate(String myCodeText)
    {
//        String myCodeText = "http://crunchify.com/";
        String filePath = "/Users/<userName>/Documents/CrunchifyQR.png";
        Bitmap bmp = null;

        int size = 250;
//        String fileType = "png";
//        File myFile = new File(filePath);
        try {

            Map<EncodeHintType, Object> hintMap = new EnumMap<EncodeHintType, Object>(EncodeHintType.class);
            hintMap.put(EncodeHintType.CHARACTER_SET, "UTF-8");

            // Now with zxing version 3.2.1 you could change border size (white border size to just 1)
            hintMap.put(EncodeHintType.MARGIN, 1); /* default = 4 */
            hintMap.put(EncodeHintType.ERROR_CORRECTION, ErrorCorrectionLevel.L);
            QRCodeWriter qrCodeWriter = new QRCodeWriter();

            QRCodeWriter writer = new QRCodeWriter();
            try {
                BitMatrix bitMatrix = writer.encode(myCodeText, BarcodeFormat.QR_CODE, size, size);
                int width = bitMatrix.getWidth();
                int height = bitMatrix.getHeight();
                bmp = Bitmap.createBitmap(width, height, Bitmap.Config.RGB_565);
                for (int x = 0; x < width; x++) {
                    for (int y = 0; y < height; y++) {
                        bmp.setPixel(x, y, bitMatrix.get(x, y) ? Color.BLACK : Color.WHITE);
                    }
                }
//                ((ImageView) findViewById(R.id.img_result_qr)).setImageBitmap(bmp);

            } catch (WriterException e) {
                e.printStackTrace();
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

        return bmp;

//        Log.d("meysam","You have successfully created QR Code.");
    }


}
