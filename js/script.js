var marks = 0;
var currLength =  0;
var prevLength = 0;
var countWords = 0;
var backSpace = 0;
var isDisqualified = false;

function onKeyUp() {
	var text = document.getElementById("para").value;
	prevLength = currLength;
	currLength = text.length;
	var diff = (prevLength - currLength);
	if(diff > 0) {
		backSpace += diff;
	}
	else if(diff < -15) {
		isDisqualified = true;
		finish("You are being disqualified.");
	}
}

var andCount = 0;

function onKeyPress(e) {
	
	var keynum;
	if(window.event) {
		keynum = e.keyCode;
	}
	else if(e.which) {
		keynum = e.which;
	}
	if(keynum==38) {
		andCount++;
	}
	else if(keynum==32) {
		var text = document.getElementById('para').value;
		var words = text.split(' ');
		document.getElementById('wordCount').innerHTML = "Words: " + words.length;
		console.log('words', words.length);
	}
}

var wordRepeats = 0;

function countIndivisualWord() {
	var text = document.getElementById("para").value;

	if(text[text.length-1] == ' ') {
		if(text[text.length-2] == '.') {}
		else if(text[text.length-2] == '?') {}
		else if(text[text.length-2] == '!') {}
		else {
			marks -= 0.25;
		}
	}
	else if(text[text.length-1] == '.') {}
	else if(text[text.length-1] == '?') {}
	else if(text[text.length-1] == '!') {}
	else {
		marks -= 0.25;
	}

	var words = text.split(" ");
	var count = 0;
	var result = "";
	for(var i=0; i< words.length; i++ ) {
		count = 0;
		if(result.indexOf(words[i]) === -1) {
			for(var j=0; j < words.length; j++ ) {
				if(words[i] === words[j]) {
					count++;
					countWords++;
				}

			}
			result = result + words[i] + " - " + count + ", ";
			if(count >= 2) {
				wordRepeats += count;
			}
		}
	}
}

var abbrUsed = 0;

function checkAbbreviation() {
	var count=0;
	var para = document.getElementById("para").value;
	var words = para.split(" ");
	for(var i=0; i< words.length; i++) {
		count=0;
		for(var j=0; j< words[i].length; j++) {
			if(words[i][j] == '.') {
				count++;
			}
		}
		if(count>=2) {
			abbrUsed++;
		}
	}
}
	

var tim;       
var min = 10;
var sec = 0;
var f = new Date();
	var timer = document.getElementById('timer');

function examTimer() {
    if (parseInt(sec) >0) {
		timer.innerHTML = min + ' m : ' + sec + ' s';
		sec = parseInt(sec) - 1;                
        tim = setTimeout("examTimer()", 1000); 
    }
    else {

	    if (parseInt(min)==0 && parseInt(sec)==0){
	    	timer.innerHTML = min + ' m : ' + sec + ' s';
			finish("Time Over! Click OK to finish the exam."); 
	    }
        else if (parseInt(sec) == 0) {				
		    timer.innerHTML = min + ' m : ' + sec + ' s';
		    min = parseInt(min) - 1;
			sec=59;
            tim = setTimeout("examTimer()", 1000);
        }

    }
}

function finish(msg) {

	marks +=  countWords/20;

	countIndivisualWord();
	checkAbbreviation();

	console.log("backSpace", backSpace);
	console.log("wordsRepeat", wordRepeats);
	console.log("abbrUsed", abbrUsed);

	marks -= (backSpace + wordRepeats + abbrUsed + andCount) * 0.25;
	sessionStorage.setItem("score", marks);
	window.open ('finish-exam.html','_self',false);
	alert(msg);
}

/*function handleVisibilityChange() {
  if (document.visibilityState == "hidden") {
    isDisqualified = true;
    finish("You haved tried to open new window or tab. That's why you are disqualified.");
  }
}*/

//document.addEventListener('visibilitychange', handleVisibilityChange, false);

/*
window.onbeforeunload = confirmExit;
function confirmExit() {
    return "You have attempted to leave this page. Are you sure?";
}
*/