package lab.orange.kitnews;

/**
 * Created by 재혁 on 2/11/2016.
 */
public class Post {
    private int board_no;
    private int module_no;
    private String title;
    private String date;

    public int getBoard_no() {
        return board_no;
    }

    public void setBoard_no(int board_no) {
        this.board_no = board_no;
    }

    public int getModule_no() {
        return module_no;
    }

    public void setModule_no(int module_no) {
        this.module_no = module_no;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }
}
