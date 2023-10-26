package minesweeper;

public class BoardItem {
	/*
	 * Value: 0-8
	 * Bomb: \033[35m%\033[37m
	 * Flag: \033[31mF\033[37m
	 */
	private String value;
	
	private boolean isBomb = false;
	private boolean isFlagged = false;
	private boolean isNum = false;
	private boolean isShown = false;
	
	public BoardItem(String value) {
		setValue(value);
	}
	
	public BoardItem(int value){
		this(String.valueOf(value));
	}
	
	public boolean isBomb() {
		return isBomb;
	}
	
	public boolean isFlagged() {
		return isFlagged;
	}
	
	public boolean isShown() {
		return isShown;
	}
	
	public boolean isNum() {
		return isNum;
	}
	
	public boolean isZero() {
		if (isNum && Integer.parseInt(value) == 0) {
			return true;
		}
		else return false;
	}
	
	public String getPrintValue() {
		
		if (isFlagged) {
			return "\033[31mF\033[37m";
		}
		else if (isBomb) {
			return "\033[35m%\033[37m";
		}
		else {
			if (Integer.parseInt(value) == 0) {
				return "\033[32m \033[37m";
			}
			else return "\033[32m" + value + "\033[37m";
		}
	}
	
	public void setValue(String value) {
		this.value = value;
		try {
			int num = Integer.parseInt(value);
			if (num >= 0) {
				isNum = true;
			}
		} catch (NumberFormatException e) {
			isBomb = true;
		}
	}
	
	public void setValue(int value) {
		setValue(String.valueOf(value));
	}
	
	public void setFlag(boolean flag) {
		isFlagged = flag;
	}
	
	public void show() {
		if (!isFlagged) {
			isShown = true;
		}
	}
	
	public String toString() {
		return getPrintValue();
	}
	
}
