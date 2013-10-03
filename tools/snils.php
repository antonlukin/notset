<?php

function wcs($s){
    $m = $j = 0;
    for($i = strlen($s); $i>0; $i--){
        $a[$i] = $s[$j++];
        $m += $i*$a[$i];
    }
    if($m < 100)
        return (int)$m;
    if($m > 101)
        return $m % 101;
    return 0;
}

function findit($x, $c){
    $out = array();
    for($j=0;$j<strlen($x);$j++){
        $s = $x;
        $v = $s[$j];
        for($i=0;$i<=9;$i++){
            $s[$j] = $i;
            if(wcs($s) == $c)
                $out[] = "<li>$s $c</li>";
        }
    }
    return $out;
}

function testit(){
    $error = '<div style="text-align:center;color:#e88;">%s</div>';
    if(!isset($_REQUEST['num']))
        return str_replace('%s', 'Ошибка запроса', $error); 

    $num = $_REQUEST['num'];

    if(!is_numeric($num))
        return str_replace('%s', 'Номер должен состоять из 11 цифр', $error);   



    $snils = substr($num, 0, 9);
    $cs = (int)substr($num, 9,2);

    if(wcs($snils) == $cs)
        return str_replace('%s', 'Контрольная сумма сходится', $error);  

    $out = findit($snils, $cs);
    if(count($out) < 1)
        return str_replace('%s', 'Вариантов не найдено', $error);   

    return '<div>Возможные варианты:</div><ul>'.implode('', $out).'</ul></div>';

}

if(isset($_REQUEST['test'])){
    echo testit();
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head> 
	<meta charset="UTF-8" />
    <title>Поиск ошибки в СНИЛС по контрольной сумме</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
	<meta name="viewport" content="width=600" />   
    <meta name="keywords" content="СНИЛС, контрольная сумма, пенсионное свидетельство" />
    <meta name="description" content="СНИЛС поиск ошибок" />
 	<link rel="shortcut icon" href="//icons.notset.ru/cogs.png"> 
	<link rel="stylesheet" type="text/css" href="//notset.ru/assets/styles/common.css" />
	<link href="//fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic" rel="stylesheet" type="text/css"> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>    
    <script type="text/javascript">
        $(function(){
            function removeSpaces(s) {
                var spaceRe = /[\s-]+/g;
                return s.replace(spaceRe, "");
            }

            $("#test").submit(function(){
                $("#res").html('');
                $("#res").slideUp(200);
                var snils = removeSpaces($("#sval").val());
                if(snils.length != 11){
                    $("#res").slideDown(200, function(){$(this).html('<div style="text-align:center;color:#e88;">Неверный формат</div>')});
                    return false;
                }

                $.post("?test", {num:snils}, function(data){
                    $("#res").slideDown(200, function(){$(this).html(data)});
                });
                return false;
            });
            $("#find").live('click', function(){
                $("#test").trigger('submit');
                return false;
            });
        });
    </script>
</head>
<body>
	<div class="content">
		<h1>Проверка СНИЛС <span>Введите номер вместе с котрольной суммой</span> </h1>
		<div class="block">
			<form action="/?test" id="test" method="post">
				<div class="block-input">
				<input id="sval" name="num" value="" placeholder="СНИЛС" type="text" maxlength="15" />
					<button id="find" type="submit"><img src="//notset.ru/assets/images/search.png" alt="Вперед" /></button>
				</div>     
			</form>
			<div class="block-extended hidden" id="res">
				
			</div>
		</div>
		<header>
			&larr; <a href="http://notset.ru/" title="Сервисы для администрирования и разработки">на главную</a>
		</header>  
	</div> 
</body>
</html>
