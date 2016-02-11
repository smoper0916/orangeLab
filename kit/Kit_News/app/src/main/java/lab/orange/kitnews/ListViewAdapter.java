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

    public ListViewAdapter(Context context, int layout, ArrayList<NewsListItem> data){
        this.inflater=(LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.data=data;
        this.layout=layout;
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
        NewsListItem listviewitem = data.get(position);
        TextView title=(TextView)convertView.findViewById(R.id.title);
        title.setText(listviewitem.getTitle());
        return convertView;
    }
}
