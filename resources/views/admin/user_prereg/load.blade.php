<h4 class="text-danger">Найдены ошибки</h4>
<table class="table table-bordered table-responsive table-sm">
    <thead>
    <tr>
        <td style="width: 4%;"></td>
        <td style="width: 32%;" class="text-center text-dark">A ФИО</td>
        <td style="width: 32%;" class="text-center text-dark">B копания</td>
        <td style="width: 32%;" class="text-center text-dark">C email</td>
        <td style="width: 32%;" class="text-center text-dark">D телефон</td>
        <td style="width: 32%;" class="text-center text-dark">E город</td>
        <td style="width: 32%;" class="text-center text-dark">C регион</td>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $r => $rows)
        <tr>
            <td class="text-center text-dark">{{$r}}</td>
            @foreach($rows as $c => $col)
                @if(isset($errors[$r][$c]))
                    <td data-toggle="tooltip" data-placement="top" title="{{$errors[$r][$c]}}"
                        class="text-center text-big text-white bg-danger">{{$col}}</td>
                @else
                    <td class="text-center text-big text-white bg-success">{{$col}}</td>
                @endif

            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>