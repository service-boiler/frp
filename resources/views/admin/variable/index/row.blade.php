<div class="items-col col-12">
    <div class="card mb-1">
        <div class="card-body pt-1 pb-1">
            <div class="row">
                <div class="col-4 col-sm-4">
                    <a class="mb-0"
                       href="{{ route('admin.variables.edit', $variable) }}">{{ $variable->name }}</a>
                       <br />{{ $variable->comment }}
                </div>
                <div class="col-8 col-sm-8 text-left">
                    {!! $variable->value !!} 
                </div>
                
                
            </div>
        </div>
    </div>
</div>