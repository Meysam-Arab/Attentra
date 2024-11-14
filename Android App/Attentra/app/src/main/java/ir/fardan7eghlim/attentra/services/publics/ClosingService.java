package ir.fardan7eghlim.attentra.services.publics;

/**
 * Created by Meysam on 5/2/2017.
 */


import android.app.Service;
import android.content.Intent;
import android.os.IBinder;
import android.support.annotation.Nullable;
import android.widget.Toast;
import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.services.track.TrackingService;
import ir.fardan7eghlim.attentra.utils.Utility;
import ir.fardan7eghlim.attentra.views.track.TrackStoreActivity;

public class ClosingService extends Service {

    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    @Override
    public void onTaskRemoved(Intent rootIntent) {
        super.onTaskRemoved(rootIntent);

        Utility.deleteCache(getApplicationContext());

        // Handle application closing
//        TrackStoreActivity.hideNotification(getApplicationContext());
//        Utility.displayToast(getApplicationContext(),getApplicationContext().getText(R.string.msg_AppExited).toString(),Toast.LENGTH_LONG);
        //stop tracking service if is running - MeysamTrack
//        if(Utility.isTrackingServiceRunning())
//        {
//            Intent intent = new Intent(getApplicationContext(), TrackingService.class);
//            getApplicationContext().stopService(intent);
//        }
        // Destroy the service
        stopSelf();
    }
}