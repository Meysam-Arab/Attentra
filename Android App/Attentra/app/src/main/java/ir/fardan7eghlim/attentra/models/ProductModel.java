package ir.fardan7eghlim.attentra.models;

import android.content.Context;

import java.math.BigInteger;
import java.util.ArrayDeque;
import java.util.ArrayList;
import java.util.HashMap;

import ir.fardan7eghlim.attentra.R;
import ir.fardan7eghlim.attentra.interfaces.PaymentInterface;
import ir.fardan7eghlim.attentra.utils.billing.Inventory;

/**
 * Created by Meysam on 3/7/2017.
 */

public class ProductModel implements PaymentInterface {

    public static final String AT_1000 = "at_1000";
    public static final String AT_2000 = "at_2000";
    public static final String AT_5000 = "at_5000";
    public static final String AT_10000 = "at_10000";
    public static final String AT_20000 = "at_20000";
    public static final String AT_30000 = "at_30000";
    public static final String AT_50000 = "at_50000";
    public static final String AT_100000 = "at_100000";


    public String getProductId() {
        return ProductId;
    }

    public void setProductId(String productId) {
        ProductId = productId;
    }

    public String getAmount() {
        return Amount;
    }

    public void setAmount(String amount) {
        Amount = amount;
    }

    public String getDescriptionPersian() {
        return DescriptionPersian;
    }

    public void setDescriptionPersian(String descriptionPersian) {
        DescriptionPersian = descriptionPersian;
    }

    public String getDescriptionEnglish() {
        return DescriptionEnglish;
    }

    public void setDescriptionEnglish(String descriptionEnglish) {
        DescriptionEnglish = descriptionEnglish;
    }

    public String getTitlePersian() {
        return TitlePersian;
    }

    public void setTitlePersian(String titlePersian) {
        TitlePersian = titlePersian;
    }

    public String getTitleEnglish() {
        return TitleEnglish;
    }

    public void setTitleEnglish(String titleEnglish) {
        TitleEnglish = titleEnglish;
    }



    private String ProductId;
    private String  Amount;
    private String DescriptionPersian;
    private String DescriptionEnglish;
    private String TitlePersian;
    private String TitleEnglish;



    // SQLite database handler
    private SQLiteHandlerModel db;

    private Context cntx;


    public ProductModel()
    {
        this.ProductId = null;
        this.Amount = null;
        this.DescriptionPersian = null;
        this.DescriptionEnglish = null;
        this.TitlePersian = null;
        this.TitleEnglish = null;


        this.db = null;
        this.cntx = null;


    }

    public ProductModel(Context cntx)
    {
        this.ProductId = null;
        this.Amount = null;
        this.DescriptionPersian = null;
        this.DescriptionEnglish = null;
        this.TitlePersian = null;
        this.TitleEnglish = null;



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

    public static ArrayList<String> generateAtList()
    {
        ArrayList<String> at_list = new ArrayList<>();
        at_list.add(AT_1000);
        at_list.add(AT_2000);
        at_list.add(AT_5000);
        at_list.add(AT_10000);
        at_list.add(AT_20000);
        at_list.add(AT_30000);
        at_list.add(AT_50000);
        at_list.add(AT_100000);

        return at_list;
    }

    public static ArrayList<ProductModel> generateProductList(Inventory inv)
    {
        ArrayList<ProductModel> pr_list = new ArrayList<>();
        ProductModel pr_tmp;
        if(inv.getSkuDetails(ProductModel.AT_1000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_1000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_1000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_1000).getTitle());
            pr_list.add(pr_tmp);

        }
        if(inv.getSkuDetails(ProductModel.AT_2000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_2000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_2000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_2000).getTitle());
            pr_list.add(pr_tmp);

        }
        if(inv.getSkuDetails(ProductModel.AT_5000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_5000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_5000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_5000).getTitle());
            pr_list.add(pr_tmp);
        }
        if(inv.getSkuDetails(ProductModel.AT_10000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_10000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_10000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_10000).getTitle());
            pr_list.add(pr_tmp);
        }
        if(inv.getSkuDetails(ProductModel.AT_20000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_20000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_20000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_20000).getTitle());
            pr_list.add(pr_tmp);
        }
        if(inv.getSkuDetails(ProductModel.AT_30000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_30000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_30000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_30000).getTitle());
            pr_list.add(pr_tmp);
        }
        if(inv.getSkuDetails(ProductModel.AT_50000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_50000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_50000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_50000).getTitle());
            pr_list.add(pr_tmp);
        }
        if(inv.getSkuDetails(ProductModel.AT_100000) != null)
        {
            pr_tmp = new ProductModel();
            pr_tmp.setProductId(ProductModel.AT_100000);
            pr_tmp.setAmount(inv.getSkuDetails(ProductModel.AT_100000).getPrice());
            pr_tmp.setTitlePersian(inv.getSkuDetails(ProductModel.AT_100000).getTitle());
            pr_list.add(pr_tmp);
        }
        return pr_list;
    }

    public static HashMap<Integer,String> generateProductHashMap(Inventory inv)
    {
        HashMap<Integer, String> spinnerMap = new HashMap<Integer, String>();
        Integer i = 0;
        if(inv.getSkuDetails(ProductModel.AT_1000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_1000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_2000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_2000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_5000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_5000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_10000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_10000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_20000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_20000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_30000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_30000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_50000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_50000);
            i++;
        }
        if(inv.getSkuDetails(ProductModel.AT_100000) != null)
        {
            spinnerMap.put(i,ProductModel.AT_100000);
            i++;
        }
        return spinnerMap;
    }
    public static ArrayList<String> generateProductTitleList(ArrayList<ProductModel> products)
    {
        ArrayList<String> results = new ArrayList<>();
      for(int i=0; i < products.size(); i++)
      {
          results.add(products.get(i).getTitlePersian());
      }
        return results;
    }

    public static Boolean purchaseExist(Inventory inv)
    {
        if(inv.hasPurchase(AT_1000))
            return true;
        if(inv.hasPurchase(AT_2000))
            return true;
        if(inv.hasPurchase(AT_5000))
            return true;
        if(inv.hasPurchase(AT_10000))
            return true;
        if(inv.hasPurchase(AT_20000))
            return true;
        if(inv.hasPurchase(AT_30000))
            return true;
        if(inv.hasPurchase(AT_50000))
            return true;
        if(inv.hasPurchase(AT_100000))
            return true;
        return false;

    }

    public static String getProductTitle(Inventory inv) {
        if(inv.hasPurchase(AT_1000))
            return AT_1000;
        if(inv.hasPurchase(AT_2000))
            return AT_2000;
        if(inv.hasPurchase(AT_5000))
            return AT_5000;
        if(inv.hasPurchase(AT_10000))
            return AT_10000;
        if(inv.hasPurchase(AT_20000))
            return AT_20000;
        if(inv.hasPurchase(AT_30000))
            return AT_30000;
        if(inv.hasPurchase(AT_50000))
            return AT_50000;
        if(inv.hasPurchase(AT_100000))
            return AT_100000;
        return "0";
    }

    public static Double getAmountByCode(String code)
    {
        if(code.equals(AT_1000))
            return 1000.0;
        if(code.equals(AT_2000))
            return 2000.0;
        if(code.equals(AT_5000))
            return 5000.0;
        if(code.equals(AT_10000))
            return 10000.0;
        if(code.equals(AT_20000))
            return 20000.0;
        if(code.equals(AT_30000))
            return 30000.0;
        if(code.equals(AT_50000))
            return 50000.0;
        if(code.equals(AT_100000))
            return 100000.0;
        return 0.0;
    }
}
