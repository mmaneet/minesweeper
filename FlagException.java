package minesweeper;

public class FlagException extends Exception {
	public FlagException() {
		super("The requested tile is flagged, and therefore cannot be clicked. Please try again.");
	}
}
