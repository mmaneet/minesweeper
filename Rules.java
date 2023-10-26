package minesweeper;
import java.util.ArrayList;
import java.util.ArrayDeque;
import java.util.Deque;

public class Rules {
	
	public static Board board;
	
	public Rules(Board board) {
		this.board = board;
		board.setRules(this);
	}
	
	public static Boolean getValid(int rowP, int colP, int interaction) throws ShownException, FlagException{
		if (board.BLT[rowP][colP].isShown() && !board.BLT[rowP][colP].isFlagged()) {
			throw new ShownException();
		}
		else if (board.BLT[rowP][colP].isFlagged() && interaction == 0) {
			throw new FlagException();
		}
		else return true;
	}
	
	public void updateBoard(int[] input) {
		//click = 0
		//flag = 1
		int row = input[0];
		int col = input [1];
		int interaction = input[2];
		BoardItem current = board.BLT[row][col];
		if (interaction == 0) {
			if (current.isBomb()) {
				gameOverLoss();
			}
			else if (!current.isZero()) {
				board.updateDisplay(row, col);
			}
			else {
				ArrayList<Integer[]> zeros = getZeros(row, col);
				for (Integer[] coords : zeros) {
					board.updateDisplay(coords[0].intValue(), coords[1].intValue());
				}
			}
		}
		else {
			current.setFlag(!current.isFlagged());
			board.updateDisplay(row, col);
		}
	}
	
	public ArrayList<Integer[]> getZeros(int row, int col){
		
		ArrayList<Integer[]> zeros = new ArrayList<Integer[]>();
		Deque<Integer[]> deque = new ArrayDeque<>();
		Deque<Integer[]> visited = new ArrayDeque<>();
		Integer[] coords = new Integer[] {row, col};
		zeros.add(coords);
		deque.add(coords);
		visited.add(coords);
		
		while (!deque.isEmpty()) {
			Integer[] x = deque.poll();
			
			for(int k = 1; k <= 4; k++) {
				int nrow = x[0];
				int ncol = x[1];
				switch (k) {
					case 1:
						nrow -= 1;
						break;
					case 2:
						nrow += 1;
						break;
					case 3:
						ncol -= 1;
						break;
					case 4:
						ncol += 1;
						break;
				}
				try {
				Integer[] temp = new Integer[] {nrow, ncol};
				if (contains(visited, temp)) {
					continue;
				}else if (board.BLT[nrow][ncol].isZero() && !contains(visited, temp)) {
					deque.add(temp);
					visited.add(temp);
					zeros.add(temp);
				}else if (!board.BLT[nrow][ncol].isZero() && !contains(visited, temp)) {
					visited.add(temp);
					zeros.add(temp);
				}
				}catch(ArrayIndexOutOfBoundsException e){
					
				}
			}
		}
		return zeros;
	}
	
	public void generateNums() {
		for (int i = 0; i < board.BLT[0].length; i++) {
			for (int j = 0; j < board.BLT.length; j++) {
				if (board.isBomb(i, j)) {
					continue;
				}
				int numcount = 0;
				for(int k = 1; k < 9; k++) {
					int nrow = 0;
					int ncol = 0;
					switch (k) {
						case 1:
							nrow = i-1;
							ncol = j-1;
							break;
						case 2:
							nrow = i-1;
							ncol = j;
							break;
						case 3:
							nrow = i-1;
							ncol = j+1;
							break;
						case 4:
							nrow = i;
							ncol = j-1;
							break;
						case 5:
							nrow = i;
							ncol = j+1;
							break;
						case 6:
							nrow = i+1;
							ncol = j-1;
							break;
						case 7:
							nrow = i+1;
							ncol = j;
							break;
						case 8:
							nrow = i+1;
							ncol = j+1;
							break;
					}
					try {
						if (board.isBomb(nrow, ncol)) {
							numcount += 1;
						}
					} catch (ArrayIndexOutOfBoundsException e){
					}
				}
				board.setCoord(i, j, String.valueOf(numcount));
			}
		}
		
	}
	
	public void gameOverLoss() {
		String time = board.getTime();
		String turns = board.getTurns();
		System.out.println("Uh oh! You clicked a bomb. No motor coordination lookin headass 😭 gtf. Anyways try again next time.");
		System.out.print("You used " + turns + " turns. You spent " + time + " on this failiure of a game 😭 loser ahh");
		System.exit(0);
	}
	
	public Boolean contains(Deque<Integer[]> a, Integer[] b) {
		Boolean ret = false;
		for (Integer[] x : a) {
			if (x[0].intValue() == b[0].intValue() && x[1].intValue() == b[1].intValue()) {
				ret = true;
			}
			else continue;
		}
		return ret;
	}


}
