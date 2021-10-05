<!DOCTYPE html>
<html lang="ru">
<head>
    <title>title</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="QuadStudio"/>
    <meta name="viewport"
          content="width=device-width, shrink-to-fit=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style>

        body{
            font-family: Tahoma, Helvetica, Arial, monospace;
			font-size: 12pt;
        }

        .content {
            background-color: #FFF;
            padding: 20px;
            border-top: 2px solid #ed9068;
        }

		li {
			list-style-type: none; 
		   }

	   a {
            font-weight: 400;
            color: #ed9068;
            text-decoration: none;
            background-color: transparent;
            -webkit-text-decoration-skip: objects;
        }


    </style>
</head>
<body>
<div class="content">

<strong>Посетитель сайта market.ferroli.ru оставил заявку на покупку оборудования.</strong><br />
    <p>Имя: {!!$content->contact!!} <br />
    Телефон: {!!$content->phone_number!!} <br />
    Комментарии к заказу: {!!$content->comment!!}
	</p>

	<p>
<strong>Содержимое заказа:</strong><ul>
@foreach($content->items as $item) 
<li>{{$item->product->name}} - {{$item->quantity}} шт, цена:  {{Cart::price_format($item->price)}}</li>
@endforeach
</ul>
</p>

</div>
</body>
</html>