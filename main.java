package minesweeper;
import java.util.ArrayList;
import java.lang.Math;
public class main {
	public static void main(String[] args) {
		
		UserInput input = new UserInput();
		String difficulty = input.getDifficulty();
		
		Board board = new Board(difficulty);
		Rules rule = new Rules(board);
		
		board.showBoard();
		
		int[] sInputs = input.getStartingInput();
		while (sInputs == null) {
			sInputs = input.getStartingInput();
		}
		board.initializeBoard(sInputs[0], sInputs[1]);
		int[] temp = new int[] {sInputs[0], sInputs[1], 0};
		rule.updateBoard(temp);
//		tester test = new tester(rule);
//		test.testContains();
		
		board.showBoard();
		board.printAnswerKey();
		
		while (true) {
			int[] inputs = input.getInputs();
			while (inputs == null) {
				inputs = input.getInputs();
			}
			rule.updateBoard(inputs);
			board.showBoard();
		}

	}
}

/*
 * TODO make the colors different for diff numbers
 * 		change difficulty bomb counts
 * 		write gameOver method
 * 		do actual assignments
 * DONE	turn counter 
 * DONE	specific input errors
 * 		unflagging shouldnt reveal
 * 		write win method
 */





