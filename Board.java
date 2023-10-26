package minesweeper;
import java.util.HashMap;
import java.util.concurrent.TimeUnit;
import java.util.Random;
import java.util.ArrayList;

public class Board {
	
	public String[][] BLD; //boardListDisplay
	public BoardItem[][] BLT; //boardListTrue
	private String[][] headers;
	private Rules rule;
	
	private final static HashMap<String, Integer> difficulties = new HashMap<String, Integer>();
	public ArrayList<int[]> bombSpots = new ArrayList<int[]>();
	
	static {
		difficulties.put("easy" , 0);
		difficulties.put("normal", 1);
		difficulties.put("medium", 1);
		difficulties.put("hard", 2);
		difficulties.put("difficult", 2);
	}
	
	private int difficulty;
	private int time;
	private int turn;
	private long startTime = System.nanoTime();
	
	public Board(String difficulty) {
		time = 0;
		turn = 0;
		generateBoard(difficulty);
	}
	
	public void generateBoard(String difficulty) {
		this.difficulty = difficulties.get(difficulty.toLowerCase());
		int temp = (this.difficulty * 5) + 20;
		BLD = new String[temp][temp];
		BLT = new BoardItem[temp][temp];
		headers = new String[2][temp];
		
		for (int i = 0; i < BLT.length; i++) {
			for (int j = 0; j < BLT[0].length; j++) {
				BLT[i][j] = new BoardItem(0);
			}
		}
		
		for (int i = 0; i < headers[0].length; i++) {
			String number = String.valueOf(i+1);
			if (number.length() == 1) {
				number = "0" + number;
			}
			headers[0][i] = number.substring(0,1);
			headers[1][i] = number.substring(1);
			
		}
		initDisplay();
	}
	
	public void initializeBoard(int row, int col) {
		int bombTarget = (int) (Math.pow(BLT.length, 2) / 10);
//		System.out.println(bombReq);
		Solver solver = new Solver(this, bombTarget, row, col);
		
		solver.generateBoard();
		while (!solver.solveBoard()) {
			solver.generateBoard();
		}
	
		for (int[] i : bombSpots) {
			BLT[i[0]][i[1]].setValue("%");
		}
//		printBombSpots();
		rule.generateNums();
		
	}
	
	public void showBoard() {
		long currentTime = System.nanoTime();
		long elapsedTime = currentTime - startTime;
		time = (int) TimeUnit.SECONDS.convert(elapsedTime, TimeUnit.NANOSECONDS);
		turn += 1;
		printHeaderInternal();
		printBoardInternal();
	}
	
	public String getTime() {
		long currentTime = System.nanoTime();
		long elapsedTime = currentTime - startTime;
		time = (int) TimeUnit.SECONDS.convert(elapsedTime, TimeUnit.NANOSECONDS);
		
		String timeDisplay;
		String minutes = String.valueOf((int) (time/60));
		String seconds = String.valueOf((int) (time%60));
		
		if (seconds.length() == 1) {
			seconds = "0" + seconds;
		}
		if (minutes.length() == 1) {
			minutes = "0" + minutes;
		}
		
		timeDisplay = minutes + ":" + seconds;
		
		return timeDisplay;
	}
	
	public String getTurns() {
		return String.valueOf(turn);
	}
	
	private void initDisplay() {
		for (int i = 0; i < BLT[0].length; i++) {
			for (int j = 0; j < BLT.length; j++) {
				BLD[i][j] = "?";
			}
		}
	}
	
	public void updateDisplay(int row, int col) {
		BLD[row][col] = BLT[row][col].getPrintValue();
		BLT[row][col].show();
	}
	
	private void printHeaderInternal() {
		
		String timeDisplay = getTime();
		String turnDisplay;
		String Spacer = "";
		
		for (int i = 0; i < this.difficulty * 5; i++) {
			Spacer += " ";
		}
		
		System.out.println(Spacer + "|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|" + Spacer);
		System.out.println(Spacer + "|            MINESWEEPER              |" + Spacer);
		System.out.println(Spacer + "|Turn: 00         ||     Time: " + timeDisplay + "  |" + Spacer);
//		System.out.println(Spacer + "|Turn: 00         ||     Time: 00:00  |" + Spacer);
		System.out.println(Spacer + "|_____________________________________|" + Spacer);
	}
	
	private void printBoardInternal() {
		System.out.print("\033[36mXX ");
		for (String n : headers[0]) {
			System.out.print(n + " ");
		}
		System.out.print("\n");
		
		System.out.print("XX ");
		for (String n : headers[1]) {
			System.out.print(n + " ");
		}
		System.out.print("\033[37m\n");
		
		for (int i = 0; i < BLD.length; i++) {
			System.out.print("\033[36m" + headers[0][i]);
			System.out.print(headers[1][i] + "\033[37m ");
			for (int j = 0; j < BLD[0].length; j++) {
				System.out.print(BLD[i][j] + " ");
			}
			System.out.print("\n");
		}
	}
	
	public void printAnswerKey() {
		System.out.print("\033[36mXX ");
		for (String n : headers[0]) {
			System.out.print(n + " ");
		}
		System.out.print("\n");
		
		System.out.print("XX ");
		for (String n : headers[1]) {
			System.out.print(n + " ");
		}
		System.out.print("\033[37m\n");
		
		for (int i = 0; i < BLT.length; i++) {
			System.out.print("\033[36m" + headers[0][i]);
			System.out.print(headers[1][i] + "\033[37m ");
			for (int j = 0; j < BLT[0].length; j++) {
				System.out.print(BLT[i][j] + " ");
			}
			System.out.print("\n");
		}
	}
	
	public void setCoord(int row, int col, String val) {
		BLT[row][col].setValue(val);
	}
	
	public Boolean isBomb(int row, int col) {
		return BLT[row][col].isBomb();
	}
	
	public void setRules(Rules r) {
		rule = r;
	}
	
	public void printBombSpots() {
		for (int[] i : bombSpots) {
			System.out.println(i[0] + ":" + i[1]);
		}
	}
}
