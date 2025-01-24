package com.mycompany.app;

import java.io.ByteArrayOutputStream;
import java.io.PrintStream;
import org.junit.Before;
import org.junit.Test;
import org.junit.After;
import static org.junit.Assert.*;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

/**
 * Integration UI test for PHP App.
 */
public class AppTest
{
	WebDriver driver; 
	WebDriverWait wait; 
	String url = System.getenv("MY_APP_URL");
	String validInput = "thisisnotinthelist";
	String invalidInput = "thisisinthelistorisit";

    @Before
    public void setUp() { 
		driver = new HtmlUnitDriver(); 
		wait = new WebDriverWait(driver, 10); 
	} 

	@After
    public void tearDown() { 
		driver.quit(); 
	}	 
	
    @Test
    public void testLoginWithValidInput() 
		throws InterruptedException { 

		//get web page
		driver.get(url);
		//wait until page is loaded or timeout error
		wait.until(ExpectedConditions.titleContains("Login")); 

		//enter input
		driver.findElement(By.name("userInput")).sendKeys(validInput);
		//click submit
		driver.findElement(By.name("submit")).submit();
	
		//check result 
		String expectedResult = "Success"; 
		boolean isResultCorrect = wait.until(ExpectedConditions.titleContains(expectedResult)); 
		assertTrue(isResultCorrect == true); 
	}
		
	@Test
    public void testLoginWithInvalidInput() 
		throws InterruptedException { 

		//get web page
		driver.get(url);
		//wait until page is loaded or timeout error
		wait.until(ExpectedConditions.titleContains("Login")); 

		//enter input
		driver.findElement(By.name("userInput")).sendKeys(invalidInput);
		//click submit
		driver.findElement(By.name("submit")).submit();
	
		//check result
		String expectedResult = "Login"; 
		boolean isResultCorrect = wait.until(ExpectedConditions.titleContains(expectedResult));
		assertTrue(isResultCorrect == true); 
	}

}
