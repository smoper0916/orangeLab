package lab.orange.kitnews;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.ListView;

import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserFactory;

import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.ArrayList;

public class MainActivity extends AppCompatActivity {

    ListView listView = null;
    ArrayList<NewsListItem> data = new ArrayList<>();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        listView = (ListView)findViewById(R.id.listview);
        test();
        /*
        NewsListItem t1 = new NewsListItem("[공지] 2016학년도 편입생 수강신청 안내");
        NewsListItem t2 = new NewsListItem("[공지] 2016-1학기 학부 수강신청 안내(대학원,계약학과 제외)");
        NewsListItem t3 = new NewsListItem("[공지] 2016-1학기 수강지도 상담 안내");

        data.add(t1);
        data.add(t2);
        data.add(t3);
*/


    }

    private void test(){
        final MainActivity tmp = this;
        new Thread(new Runnable() {

            @Override
            public void run() {
                // TODO Auto-generated method stub
                ArrayList<Post> xmlData = getXmlData(); //아래 메소드를 호출하여 XML data를 파싱해서 String 객체로 얻어오기

                for(int i=0; i<xmlData.size(); i++){
                    data.add(new NewsListItem(xmlData.get(i).getTitle()));
                }
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
                ListViewAdapter adapter = new ListViewAdapter(tmp, R.layout.news_listitem, data);
                listView.setAdapter(adapter);

            }
        }).start();

    }

    //XmlPullParser를 이용하여 Naver 에서 제공하는 OpenAPI XML 파일 파싱하기(parsing)
    private ArrayList<Post> getXmlData(){
        ArrayList<Post> postArrayList = new ArrayList<>();

        //String str= edit.getText().toString(); //EditText에 작성된 Text얻어오기
        //String location = URLEncoder.encode(str); //한글의 경우 인식이 안되기에 utf-8 방식으로 encoding..

        String queryUrl="http://jaehyeok.kr/kit/result.xml"; //요청 URL

        try {
            URL url= new URL(queryUrl); //문자열로 된 요청 url을 URL 객체로 생성.
            InputStream is= url.openStream();  //url위치로 입력스트림 연결

            XmlPullParserFactory factory= XmlPullParserFactory.newInstance();
            XmlPullParser xpp= factory.newPullParser();
            xpp.setInput( new InputStreamReader(is, "UTF-8") );  //inputstream 으로부터 xml 입력받기

            String tag;

            xpp.next();
            int eventType= xpp.getEventType();
            Post tmp = null;
            while( eventType != XmlPullParser.END_DOCUMENT ){
                switch( eventType ){
                    case XmlPullParser.START_DOCUMENT:
                        //buffer.append("start NAVER XML parsing...\n\n");
                        break;

                    case XmlPullParser.START_TAG:
                        tag= xpp.getName();    //테그 이름 얻어오기

                        switch (tag){
                            case "kit":
                                // 시작태그
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
                        tag= xpp.getName();    //테그 이름 얻어오기
                        if(tag.equals("post")) {
                            postArrayList.add(tmp);
                            tmp = null;
                        }

                        break;
                }

                eventType= xpp.next();
            }

        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        return postArrayList;

    }//getXmlData method....
}
