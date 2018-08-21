var marks = 0;
var currLength =  0;
var prevLength = 0;
var countWords = 0;
var backSpace = 0;
var totalWords = 0;
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
		finish("You are being disqualified.", 0);
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
		countTotalWords(text);
	}
}

function countTotalWords(text) {
	var words = text.split(' ');
	var wordCount = document.getElementById('wordCount'); 
	totalWords = words.length;
	
	if(totalWords < 190 || totalWords > 210) {
		wordCount.style.color = "red";
		wordCount.style.fontWeight = "normal";
	}
	else {
		wordCount.style.color = "green";
		wordCount.style.fontWeight = "bold";
	}

	wordCount.innerHTML = "Words: " + totalWords;
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
var min = 30;
var sec = 0;
var f = new Date();
	var timer = document.getElementById('timer');

function examTimer() {
	if(parseInt(min)==0) {
    	if(parseInt(sec)%2==0) {
    		timer.style.color = "black";
    		timer.style.fontWeight = "normal";
    	}
    	else {
    		timer.style.color = "red";
    		timer.style.fontWeight = "bold";
    	}
    }
    
    if (parseInt(sec) >0) {
		timer.innerHTML = min + ' m : ' + sec + ' s';
		sec = parseInt(sec) - 1;                
        tim = setTimeout("examTimer()", 1000); 
    }
    else {

	    if (parseInt(min)==0 && parseInt(sec)==0){
	    	timer.innerHTML = min + ' m : ' + sec + ' s';
			finish("Time Over! Click OK to finish the exam.", 0); 
	    }
        else if (parseInt(sec) == 0) {				
		    timer.innerHTML = min + ' m : ' + sec + ' s';
		    min = parseInt(min) - 1;
			sec=59;
            tim = setTimeout("examTimer()", 1000);
        }

    }
}

function finish(msg, type) {
	console.log("msg", "yaha tk to aaya h");
	countTotalWords(document.getElementById('para').value);
	countIndivisualWord();
	checkAbbreviation();

	document.getElementById('backSpace').value = backSpace;
	document.getElementById('wordsRepeat').value = wordRepeats;
	document.getElementById('abbrUsed').value = abbrUsed;
	document.getElementById('andCount').value = andCount;
	document.getElementById('totalWords').value = totalWords;
	document.getElementById('isDisqualified').value = isDisqualified;

	console.log("msg", "yha bhi aaya h");

	if(type==1) {
		if(confirm(msg)) {
			console.log("msg", "aaya babu aaya");
			document.getElementById('form').submit();
			document.getElementById('btnsubmit').disabled=true;
			document.getElementById('btnsubmit').value = "Finishing Exam, Please Wait!";
			return true;
		}
		else {
			console.log("msg", "ni aa paya!");
			return false;
		}
	}
	else {
		alert(msg);
		document.getElementById('form').submit();
		document.getElementById('btnsubmit').disabled=true;
		document.getElementById('btnsubmit').value = "Finishing Exam, Please Wait!";
		return true;
	}

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