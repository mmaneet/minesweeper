package minesweeper;
import java.util.Scanner;
import java.util.HashMap;

public class UserInput {
	
	private Scanner scan;
	private static final HashMap<String, Integer> interactions= new HashMap<String, Integer>();
	static {
		interactions.put("click", 0);
		interactions.put("flag", 1);
	}
	
	public UserInput() {
		scan = new Scanner(System.in);
	}
	
	public String getDifficulty() {
		System.out.println("What difficulty would you like to play on?");
		String ret = scan.next();
		return ret;
	}
	
	public int[] getInputs(){
		System.out.println("What row would you like to interact with");
		int rowVar = scan.nextInt()-1;
		if (rowVar >= 30) {
			System.out.println("Row Out of Bounds. Try Again.");
			return null;
		}
		System.out.println("What column would you like to interact with");
		int colVar = scan.nextInt()-1;
		if (colVar >= 30) {
			System.out.println("Column Out of Bounds. Try Again.");
			return null;
		}
		System.out.println("What interaction would you like to make");
		String temp = scan.next();
		int interaction;
		try {
			interaction = interactions.get(temp.toLowerCase());
		} catch (NullPointerException e) {
			System.out.println("Invalid interaction. Try again.");
			return null;
		}
		
		int[] ret = new int[]{rowVar, colVar, interaction};
		
		try {
			if (Rules.getValid(rowVar, colVar, interaction)) {
				return ret;
			}
			else return null;
		} catch (ShownException | FlagException e) {
			System.err.println(e);
			return null;
		}
	}

	public int[] getStartingInput(){
		System.out.println("What row would you like to start with");
		int rowVar = scan.nextInt()-1;
		if (rowVar >= 30) {
			System.out.println("Row Out of Bounds. Try Again.");
			return null;
		}
		System.out.println("What column would you like to start with");
		int colVar = scan.nextInt()-1;
		if (colVar >= 30) {
			System.out.println("Column Out of Bounds. Try Again.");
			return null;
		}
		
		int[] ret = new int[]{rowVar, colVar};
		
		return ret;
	}
}
