function in_array(needle, haystack, strict) {  
    var found = false, key, strict = !!strict;  
    for (key in haystack) {  
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {  
            found = true;  
            break;  
        }  
    }  
    return found;  
}  
 
function array_search( needle, haystack, strict ) {  
    var strict = !!strict;  
    for(var key in haystack){  
        if( (strict && haystack[key] === needle) || (!strict && haystack[key] == needle) ){  
            return key;  
        }  
    }  
    return false;  
}  
 
function string2url(str) {  
    var en = Array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m');  
    var ru = Array('й','ц','у','к','е','ё','н','г','ш','щ','з','х','ъ','ф','ы','в','а','п','р','о','л','д','ж','э','я','ч','с','м','и','т','ь','б','ю','-',' ');   
 var ru2en = Array('y','c','u','k','e','yo','n','g','sh','sh','z','h','','f','i','v','a','p','r','o','l','d','j','e','ya','ch','s','m','i','t','','b','yu','-','-');  
    var digits = Array('0','1','2','3','4','5','6','7','8','9');  
    var ret = '';  
 
    str = str.toLowerCase()
 
    for (var i=0; i<str.length; i++) {  
        var ch = str.charAt(i);  
        var key=false;  
     if (!in_array(ch, en)  && !in_array(ch, digits)) {   
         if ((key=array_search(ch, ru))!==false) 
		     ret += ru2en[key];  
         else 
			 ret += '';  
 
     } else ret+=ch;  
 
    }  
    return ret;  
}

