package minesweeper;

import java.util.ArrayList;

public class Solver {

	Board board;
	int bombTarget;
	int row;
	int col;
	
	int[][] realBoard;
	int[][] knownBoard;
		
	public Solver(Board b, int bT, int row, int col) {
		board = b;
		bombTarget = bT;
		this.row = row;
		this.col = col;
	}
	
	public void generateBoard() {
		BoardItem[][] BLT = board.BLT;
		ArrayList<int[]> bombSpots = board.bombSpots;
		
		while (bombTarget > 0) {
//			printBombSpots();
//			System.out.println(bombReq);
			for (int i = 0; i < BLT.length; i++) {
				for(int j = 0; j < BLT[0].length; j++) {
					if (((Math.abs(i-row) == 1) ||(Math.abs(i-row) == 0)) && ((Math.abs(j-col) == 1) || (Math.abs(j-col) == 0))) {
						continue;
					}
					int[] coords = new int[] {i,j};
//					System.out.println(coords[0] + ":" + coords[1]);
//					printBombSpots();
					if (bombSpots.contains(coords)){
						continue;
					}
					
					if (Math.random() * 100 <= 5) {
						bombTarget -= 1;
						bombSpots.add(coords);
					}
					
					if (bombTarget <= 0) {
						break;
					}
				}
				
				if (bombTarget == 0) {
					break;
				}
			}
		}
	}
	
	public Boolean solveBoard() {
		while (true) {
			if (rule1()) {
				continue;
			} else if (rule2()) {
				continue;
			} else if (rule3()) {
				continue;
			} else if (rule4()) {
				continue;
			} else if (rule5()) {
				continue;
			} else if (rule6()) {
				continue;
			} else {
				break;
			}
		}
		return true;
	}
	
	public Boolean rule1() {
		/*
		 * The first rule is obvious: All mines for the surrounding spaces have been found. 
		 * Open the remaining spaces and mark them as points of interest.
		 * Mark the current point as done.
		 */
		
		return false;
	}
	
	public Boolean rule2() {
		/*
		 * The next rule is also obvious: All surrounding spaces have been filled and the only remaining spaces are mines. 
		 * Fill spaces with permanent mines.
		 * Mark the current point as done.
		 */
		return false;
	}
	
	public Boolean rule3() {
		/*
		 * After that, things get a bit trickier. 
		 * The next most common pattern is "1 2 1" with 3 adjacent unknown spaces 
		 * (after counting up the mines left for the adjacent points). 
		 * When that pattern is encountered either vertically or horizontally, 
		 * then there can't be a mine in the middle space and the other two spaces have to be the mines.
		 */
		
		return false;
	}
	
	public Boolean rule4() {
		/*
		 * The first complex rule is to open logically impossible positions where 
		 * only one mine can be placed in two different adjacent spots, 
		 * but there are three open adjacent spaces in a row/column (i.e. open the third spot). 
		 * For example, for a logical "1 1 ?" there has to be a mine in one of the two 1 positions and the ? has to be an open space.
		 */
		return false;
	}
	
	public Boolean rule5() {
		return false;
	}
	
	public Boolean rule6() {
		return false;
	}
}
