@foreach($equipment->products()->get() as $product)
@include('site::part.create_revision_part', ['product' => $product, 'start_serial' => !empty($product->pivot->start_serial) ? $product->pivot->start_serial : ''])
@endforeach