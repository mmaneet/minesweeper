package minesweeper;

import java.util.ArrayDeque;
import java.util.Deque;

public class tester {
	
	Rules rule;
	public tester(Rules rule) {
		this.rule = rule;
	}
	
	
	//test the contains method in rules.java to see if it returns "false" when a list isnt in the deque.
	public void testContains() {
		Deque<Integer[]> a = new ArrayDeque<>();
		Integer[] w = new Integer[] {1, 2};
		Integer[] x = new Integer[] {2, 3};
		Integer[] y = new Integer[] {3, 2};
		Integer[] z = new Integer[] {2, 1};
		
		a.add(x);
		a.add(y);
		a.add(z);
		Boolean val = rule.contains(a,w);
		System.out.print(!val);
	}
	
}
