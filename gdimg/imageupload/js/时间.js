function toDoubleNum(num){
	return num<10 ? "0"+num:num;
}
function toThreeNum(num){
	if(num<10){
		str = "00"+num;
	}else if(num<100){
		str = "0" +num;
	}
	return str
}
function week(w){
	switch(w){
		case 0:return "周日"
		case 1:return "周一"
		case 2:return "周二"
		case 3:return "周三"
		case 4:return "周四"
		case 5:return "周五"
		case 6:return "周六"
	}
}
function time(){
		var b = new Date();
		var year = b.getFullYear();
		var month = b.getMonth();
		var date = b.getDate();
		var day = b.getDay();
		var hour = b.getHours();
		var min = b.getMinutes();
		var sec = b.getSeconds();
		var sre = year+"年"+days(month)+"月"+days(date)+"日"+week(day)+days(hour)+":"+days(min)+":"+days(sec);
		return sre
		}