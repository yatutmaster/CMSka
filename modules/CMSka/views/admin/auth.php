<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title></title>
        <style>
           

*::-moz-selection {
    background: none repeat scroll 0 0 #B3D4FC;
    text-shadow: none;
}
html {
    background: none repeat scroll 0 0 #F0F0F0;
    color: #737373;
    font-size: 20px;
    line-height: 1.4;
    padding: 30px 10px;
}
html, input {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
}
body {

    border: 1px solid #B3B3B3;
    border-radius: 4px;
    box-shadow: 0 1px 10px #A7A7A7, 0 1px 0 #FFFFFF inset;
    margin: 0 auto;
    max-width: 500px;
    padding: 30px 20px 50px;
    background-image: url("/css/admin/cmslogo.jpg");
    background-position: 0 31px;
    background-size: 100% auto;
    height: 178px;
}
h3 {
    margin: 1.5em 0 0.5em;
}
p {
    margin: 1em 0;
}
ul {
    margin: 1em 0;
    padding: 0 0 0 40px;
}
.container {
    margin: 0 auto;
    max-width: 380px;
}
#goog-fixurl ul {
    list-style: none outside none;
    margin: 0;
    padding: 0;
}
#goog-fixurl form {
    margin: 0;
}
#goog-wm-qt, #goog-wm-sb {
    border: 1px solid #BBBBBB;
    border-radius: 2px;
    color: #444444;
    font-size: 16px;
    line-height: normal;
    vertical-align: top;
}
#goog-wm-qt {
    box-shadow: 0 1px 1px #CCCCCC inset;
    height: 20px;
    margin: 5px 10px 0 0;
    padding: 5px;
    width: 220px;
}
#goog-wm-sb {
    -moz-appearance: none;
    background-color: #F5F5F5;
    background-image: linear-gradient(rgba(255, 255, 255, 0), #F1F1F1);
    cursor: pointer;
    display: inline-block;
    height: 32px;
    margin: 5px 0 0;
    padding: 0 10px;
    white-space: nowrap;
}
#goog-wm-sb:hover, #goog-wm-sb:focus {
    background-color: #F8F8F8;
    border-color: #AAAAAA;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
#goog-wm-qt:hover, #goog-wm-qt:focus {
    border-color: #105CB6;
    color: #222222;
    outline: 0 none;
}
input::-moz-focus-inner {
    border: 0 none;
    padding: 0;
}
fieldset {
    border: 0 none;
    font-size: 0.9em;
}
fieldset {
}
.input_auth {
    border: 1px solid #B3B3B3;
    border-radius: 3px;
    float: right;
    width: 67%;
}
input[type="submit"] {
    background: none repeat scroll 0 0 #FCFCFC;
    border: 1px solid #B3B3B3;
    border-radius: 3px;
    color: #737373;
    cursor: pointer;
    float: left;
    margin-left: 33%;
    padding: 2px;
    width: 22%;
}
input[type="submit"]:hover {
    background: none repeat scroll 0 0 #FFE0E0;
}
span {
    display: block;
    font-size: 0.9em;
    margin-left: 13px;
    margin-top: -20px;
}

span.br-link {
    margin-left: 127px;
    margin-top: 103px;
    width: 46%;
}

span.warn-cms {
    margin-top: -25px;
    position: absolute;
}


}

        </style>
    </head>
    <body>
        <div class="container">
         <form action="{{urlsite}}admin" method="post" >
		 		 {%if error%} <span class="warn-cms" style="color:red">Не верено введен логин или пароль.</span> {%endif%}
		    <fieldset for="name">Логин :
			    <input type="text" name='name' id="name" class="input_auth">
			</fieldset>
			
            <fieldset for="pass">Пароль :
			    <input type="password" min=6 max=20 name='pass' id="pass" class="input_auth">
			</fieldset>
			
          <input type="submit" value="Вход" >
		  
		  </form>
         <span class="br-link"><a style="color: black;" href="http://firebrothers.ru/">firebrothers.ru</a></span>
        </div>
    </body>
</html>