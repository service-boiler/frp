@foreach($engineers as $engineer)
    <option @if(isset($engineer_id) && $engineer_id == $engineer->id)
            selected
            @endif
            value="{{ $engineer->id }}">
       {{ optional($engineer->certificates()->where('type_id', $certificate_type_id)->first())->id }}
        @if($engineer->certificates()->where('type_id', $certificate_type_id)->doesntExist())
            @lang('site::certificate.error.not_exist')
        @endif
    </option>
@endforeach
