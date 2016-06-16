package lab.orange.kitnews;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserFactory;

import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;

public class SearchViewerActivity extends AppCompatActivity {

    ListView boardListView;
    ImageView backBtn;
    Button searchBtn;
    ArrayList<NewsListItem> data = new ArrayList<>();
    ArrayList<Post> xmlData = new ArrayList<>();
    int module_no;
    TextView totalTextView;
    EditText searchEditText;
    String keyword;


    public static final int PROGRESS_DIALOG = 1001;

    ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search_viewer);

        Intent myIntent = getIntent();
        keyword = myIntent.getStringExtra("searchText");
        boardListView = (ListView) findViewById(R.id.board_listview);
        backBtn = (ImageView) findViewById(R.id.back_btn);
        backBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                SearchViewerActivity.this.finish();
            }
        });
        totalTextView = (TextView)findViewById(R.id.total_TextView);

        searchEditText = (EditText)findViewById(R.id.search_edit_box);
        searchEditText.setText(keyword);

        searchBtn = (Button)findViewById(R.id.search_btn);

        searchBtn.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                if(keyword != searchEditText.getText().toString()) {
                    keyword = searchEditText.getText().toString();
                    getNews();
                }
            }
        });


        showDialog(PROGRESS_DIALOG);

        getNews();
    }

    private class ListViewOnClickListener implements AdapterView.OnItemClickListener {
        public void onItemClick(AdapterView<?> parentView, View clickedView, int position, long id) {
            //Toast.makeText(getApplicationContext(), "Board No : "+xmlData.get(position).getBoard_no(), Toast.LENGTH_SHORT).show();
            Intent postViewerIntent = new Intent(SearchViewerActivity.this, PostViewerActivity.class);
            postViewerIntent.putExtra("post", xmlData.get(position));

            // 이전 액티비티에 대한 정보 전달
            //boardViewerIntent.putExtra("prev_activity", MainActivity.class);

            // 전환
            startActivity(postViewerIntent);
        }
    }

    private void getNews() {
        final SearchViewerActivity tmp = this;
        new Thread(new Runnable() {

            @Override
            public void run() {
                // TODO Auto-generated method stub
                xmlData = getXmlData(keyword); //아래 메소드를 호출하여 XML data를 파싱해서 String 객체로 얻어오기

                for (int i = 0; i < xmlData.size(); i++) {
                    data.add(new NewsListItem(xmlData.get(i).getTitle(), xmlData.get(i).getDate()));
                }

                progressDialog.dismiss();
                /*
                //UI Thread(Main Thread)를 제외한 어떤 Thread도 화면을 변경할 수 없기때문에
                //runOnUiThread()를 이용하여 UI Thread가 TextView 글씨 변경하도록 함
                runOnUiThread(new Runnable() {

                    @Override
                    public void run() {
                        // TODO Auto-generated method stub
                        text.setText(data);  //TextView에 문자열  data 출력
                    }
                });
                */


                runOnUiThread(new Runnable() {

                    @Override
                    public void run() {
                        // TODO Auto-generated method stub
                        ListViewAdapter adapter = new ListViewAdapter(tmp, R.layout.news_listitem_with_border, data, 1);
                        boardListView.setAdapter(adapter);
                        boardListView.setOnItemClickListener(new ListViewOnClickListener());
                    }
                });


            }
        }).start();

    }

    //XmlPullParser를 이용하여 Naver 에서 제공하는 OpenAPI XML 파일 파싱하기(parsing)
    private ArrayList<Post> getXmlData(String keyword) {
        ArrayList<Post> postArrayList = new ArrayList<>();

        //String str= keyword.toString(); //EditText에 작성된 Text얻어오기
        //String location = URLEncoder.encode(str); //한글의 경우 인식이 안되기에 utf-8 방식으로 encoding..

        String queryUrl = "http://jh4877.iptime.org/nkit/ViewSearchResult.php?keyword=" + keyword; //요청 URL
        //Toast.makeText(getBaseContext(), "http://jh4877.iptime.org/nkit/ViewSearchResult.php?keyword=" + "학사", Toast.LENGTH_LONG).show();
        try {
            URL url = new URL(queryUrl); //문자열로 된 요청 url을 URL 객체로 생성.
            InputStream is = url.openStream();  //url위치로 입력스트림 연결

            XmlPullParserFactory factory = XmlPullParserFactory.newInstance();
            XmlPullParser xpp = factory.newPullParser();
            xpp.setInput(new InputStreamReader(is, "UTF-8"));  //inputstream 으로부터 xml 입력받기

            String tag;

            xpp.next();
            int eventType = xpp.getEventType();
            Post tmp = null;
            int total = 0;
            while (eventType != XmlPullParser.END_DOCUMENT) {
                switch (eventType) {
                    case XmlPullParser.START_DOCUMENT:
                        //buffer.append("start NAVER XML parsing...\n\n");
                        break;

                    case XmlPullParser.START_TAG:
                        tag = xpp.getName();    //테그 이름 얻어오기

                        switch (tag) {
                            case "kit":
                                // 시작태그
                                break;
                            case "total_count":
                                xpp.next();
                                total = Integer.parseInt(xpp.getText());
                                break;
                            case "post":
                                tmp = new Post();
                                String no = xpp.getAttributeValue(0);
                                break;
                            case "board_no":
                                xpp.next();
                                tmp.setBoard_no(Integer.parseInt(xpp.getText()));
                                break;

                            case "module_no":
                                xpp.next();
                                tmp.setModule_no(Integer.parseInt(xpp.getText()));
                                break;

                            case "title":
                                xpp.next();
                                tmp.setTitle(xpp.getText());
                                break;
                            case "date":
                                xpp.next();
                                tmp.setDate(xpp.getText());
                                break;
                        }

                        break;

                    case XmlPullParser.TEXT:
                        break;

                    case XmlPullParser.END_TAG:
                        tag = xpp.getName();    //테그 이름 얻어오기
                        if (tag.equals("post")) {
                            postArrayList.add(tmp);
                            tmp = null;
                        }

                        break;
                }

                eventType = xpp.next();

                totalTextView.setText("검색된 게시글 수 : " + total + "개");
            }

        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        return postArrayList;

    }//getXmlData method....

    //progressDialog를 이용하여 로딩을 표시
    public Dialog onCreateDialog(int id) {
        switch (id) {
            case (PROGRESS_DIALOG):
                progressDialog = new ProgressDialog((this));
                progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
                progressDialog.setMessage("데이터 불러오는 중");

                return progressDialog;
        }
        return null;
    }
}
