package lab.orange.kitnews;

/**
 * Created by 재혁 on 2/11/2016.
 */
public class NewsListItem {
    private String title;
    private String date;

    public String getTitle(){ return  title; }
    public String getDate(){return date;}

    public NewsListItem(String title, String date){
        this.title = title; this.date = date;
    }
}
