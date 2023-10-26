package minesweeper;

public class ShownException extends Exception {
	public ShownException() {
		super("The requested tile is already shown. Please try again.");
	}
}
