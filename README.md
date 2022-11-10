# Neuffer developers-test

We have prepared for you simple test task what as we believe, allow us to estimate your experience.
It is a small php-script, which should be started in console like:

`php console.php --action {action}  --file {file}`

Script will take two required parameters:

`{file}` - csv-source file with numbers, where each row contains two numbers between -100 and 100, and

`{action}` - what action should we do with numbers from `{file}`, and can take next values:

* <b>plus</b> - to count summ of the numbers on each row in the {file}
* <b>minus</b> - to count difference between first number in the row and second
* <b>multiply</b> - to multiply the numbers on each row in the {file} 
* <b>division</b> - to divide  first number in the row and second


As result of the command execution should be csv file with three columns: first number, second number, and result. In CSV-file should be written **ONLY** numbers greater than null. If result less than null - it should be written in logs.

**Example 1**

`php console.php --action plus  --file {file}`, where in file you can find next numbers:

10 20 <br/>
-30 20 <br/>
-3 5 <br/>

As result in CSV file you should write:

10 20 30 <br/>
-3 5 2 

And in log file, something like "_numbers are - 30 and 20 are wrong_"

**Example 2**

`php console.php --action division  --file {file}`, where in file you can find next numbers:

20 10 <br/>
-30 20 <br/>
3 0 <br/>

As result in CSV file you should write:

20 10 2 <br/>

And in log file, something like:
 
_numbers are -30 and 20 are wrong_ <br/>
_numbers are 3 and 0 are wrong, is not allowed_ <br/>

##Task 
You need to refactor code and write it on proper way. Just do your best: update/delete/add code as you wish.

After finishing - please push your code in your github/bitbucket account, and send me link back.

###Requirements

* After refactoring code shoud work
* Code should work on PHP8.0+
* As file source example please use test.csv

###Result
Please put result of your work in your Github or Bitbucket account, and send link back.

