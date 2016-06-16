package lab.orange.kitnews;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import java.util.ArrayList;

/**
 * Created by 재혁 on 2/11/2016.
 */
public class ListViewAdapter extends BaseAdapter {
    private LayoutInflater inflater;
    private ArrayList<NewsListItem> data;
    private int layout;
    private int item;

    public ListViewAdapter(Context context, int layout, ArrayList<NewsListItem> data, int item){
        this.inflater=(LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.data=data;
        this.layout=layout;
        this.item = item;
    }

    @Override
    public int getCount(){return data.size();}
    @Override
    public String getItem(int position){return data.get(position).getTitle();}
    @Override
    public long getItemId(int position){return position;}
    @Override
    public View getView(int position, View convertView, ViewGroup parent){
        if(convertView==null){
            convertView=inflater.inflate(layout,parent,false);
        }

        switch (item) {
            case 0:
                NewsListItem listviewitem = data.get(position);
                TextView title = (TextView) convertView.findViewById(R.id.news_title);
                TextView date = (TextView) convertView.findViewById(R.id.news_date);
                title.setText(listviewitem.getTitle());
                date.setText(listviewitem.getDate());
                break;
            case 1:
                NewsListItem listviewitem1 = data.get(position);
                TextView title1 = (TextView) convertView.findViewById(R.id.news_border_title);
                TextView date1 = (TextView) convertView.findViewById(R.id.news_border_date);
                title1.setText(listviewitem1.getTitle());
                date1.setText(listviewitem1.getDate());
                break;
        }
        return convertView;
    }
}
