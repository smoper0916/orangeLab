package lab.orange.kitnews;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

public class PostViewerActivity extends AppCompatActivity {

    private WebView contentWebview;
    private Post thisPost;
    private TextView title, date;
    private Class prev;
    private ImageView backBtn;
    private Intent myIntent;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_post_viewer);

        title = (TextView)findViewById(R.id.post_title);
        date = (TextView)findViewById(R.id.post_date);
        contentWebview = (WebView)findViewById(R.id.content_webview);
        backBtn = (ImageView)findViewById(R.id.back_btn);

        myIntent = getIntent();
        thisPost = (Post)myIntent.getSerializableExtra("post");
        //prev = (Class)myIntent.getSerializableExtra("prev_activity");

        backBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                PostViewerActivity.this.finish();
            }
        });


        title.setText(thisPost.getTitle());
        date.setText(thisPost.getDate());

        // 이 부분에서 받아온 게시글 상세 내용을 파싱하여 html 파일을 서버에 만든다. 이를 webview를 통해 보여준다.
        contentWebview.setWebViewClient(new WebViewClient());
        contentWebview.loadUrl("http://jh4877.iptime.org/nkit/ViewContents.php?module_no="+thisPost.getModule_no()+"&board_no="+thisPost.getBoard_no());
        Toast.makeText(getApplicationContext(), "http://jh4877.iptime.org/nkit/ViewContents.php?module_no="+thisPost.getModule_no()+"&board_no="+thisPost.getBoard_no(),Toast.LENGTH_LONG).show();
    }


}
