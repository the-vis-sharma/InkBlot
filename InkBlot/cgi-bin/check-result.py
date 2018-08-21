#!D:\Python 3.6.2\python.exe

import cgi
import language_check
import pymysql
from pymongo import MongoClient


print("Content-type:text/html\r\n\r\n")
print("<html>")
print("<head><meta http-equiv='refresh' content='0; url=../finish-exam.php'></head>")
print("<body>")

form = cgi.FieldStorage()
tool = language_check.LanguageTool('en-US')

para = form.getfirst("para", "No Value")
examId = int(form.getfirst("examId", -1))

userId = int(form.getfirst("userId", -1))

backspace = int(form.getfirst("backSpace", 0))

wordsRepeat = int(form.getfirst("wordsRepeat", 0))

totalWords = int(form.getfirst("totalWords", 0))

andCount = int(form.getfirst("andCount", 0))

abbrUsed = int(form.getfirst("abbrUsed", 0))

max_marks = int(form.getfirst("max_marks", 50))

neg_marks = float(form.getfirst("neg_marks", 1))

isDisqualified = form.getfirst("isDisqualified", 0)

match = tool.check(para)

'''
print("<b>Easy: </b>" + para)
print("<b><br>ExamId: </b>", examId)
print("<b><br>userId: </b>", userId)
print("<b><br>Backspace: </b>", backspace)
print("<b><br>Word Repeated: </b>", wordsRepeat)
print("<b><br>Total Words: </b>", totalWords)
print("<b><br>And(&) Pressed: </b>", andCount)
print("<b><br>Abbreviation Used: </b>", abbrUsed)
print("<b><br>Disqualified: </b>", isDisqualified)
'''


pretox = 0
prefromx = 0
spellingMistakes = 0
grammerMistake = 0
highlightedPara = ""

#print("<ul>")

mistake = []

for i in range(len(match)):
	if(i==0):
		highlightedPara = para[0:match[i].fromx] + "<u>" + para[match[i].fromx:match[i].tox] + "</u>"
		pretox = match[i].tox
		prefromx = match[i].fromx
	elif(i==(len(match)-1)):
		if(match[i].fromx != prefromx and match[i].tox != pretox):
			highlightedPara += para[pretox:match[i].fromx] + "<u>" + para[match[i].fromx:match[i].tox] + "</u>" + para[match[i].tox:len(para)-1]
		else:
			highlightedPara += para[match[i].tox:len(para)-1]
	else:
		if(match[i].fromx != prefromx and match[i].tox != pretox):
			highlightedPara += para[pretox:match[i].fromx] + "<u>" + para[match[i].fromx:match[i].tox] + "</u>"
			pretox = match[i].tox
			prefromx = match[i].fromx

	if(match[i].msg=="Possible spelling mistake found"):
		spellingMistakes+=1
	else:
		grammerMistake+=1
	
	mistake.append(match[i].msg)
	#print("<li>", match[i].msg, "</li>") 

#print("</ul>")

marks = 0 

user_stats = {}
user_stats['examId'] = examId
user_stats['username'] = userId
user_stats['sub_eassy'] = para
user_stats['hint_eassy'] = highlightedPara
user_stats['backspace'] = backspace
user_stats['word_repeated'] = wordsRepeat
user_stats['and_count'] = andCount
user_stats['abbr_used'] = abbrUsed
user_stats['word_count'] = totalWords
user_stats['grammar_mistakes'] = grammerMistake
user_stats['spelling_mistakes'] = spellingMistakes
user_stats['mistake'] = mistake

client = MongoClient('mongodb://localhost/')

db = client.get_database('InkBlotDB')

col = db.examId.insert(user_stats)

#print("inserted: ", col)

if(totalWords < 190 or totalWords > 210):
	isDisqualified = 1

marks = 0	
marks = max_marks
marks -= (backspace + wordsRepeat//2 + andCount + abbrUsed + grammerMistake + spellingMistakes) * neg_marks;

if(isDisqualified==1):
	marks -= (max_marks * 0.40)

if(marks >= (max_marks * 0.36)):
	result = 'Pass'
else:
	result = 'Failed'

connection = pymysql.connect(host='localhost', user="root", passwd="root", db="inkblotdb")

try:
    with connection.cursor() as cursor:
        sql = "INSERT INTO `result` (`userId`, `examId`, `obtained_marks`, `is_disqualified`, `result`) VALUES (%s, %s, %s, %s, %s)"
        cursor.execute(sql, (userId, examId, marks, isDisqualified, result))

    connection.commit()
finally:
    connection.close()
'''
print("<b><br>Spelling Mistakes: </b>", spellingMistakes)
print("<b><br>Grammer Mistakes: </b>", grammerMistake)
print("<b><br>Max Marks: </b>", max_marks)
print("<b><br>Neg Marks: </b>", neg_marks)
print("<b><br>or neg: </b>", (max_marks * 0.40))
print("<b><br>Marks: </b>", marks)
print("<b><br>high: </b>", highlightedPara)
print("dic: ", user_stats)
'''
print("</p></body></html>")

