<dt class="col-sm-4 text-left text-sm-right field-{{$rnd=random_int(10000,99000)}}">
    <span class="text-danger delete-field" data-field="{{$rnd}}"><i class="fa fa-close"></i></span> &nbsp; &nbsp;{{'${'.$name.'}'}}</dt>
<dd class="col-sm-8 field-{{$rnd}}">{{$title}}
<input type="hidden" form="form" name="templfields[{{$rnd}}][name]" value="{{$name}}">
<input type="hidden"  form="form" name="templfields[{{$rnd}}][title]" value="{{$title}}">
</dd>