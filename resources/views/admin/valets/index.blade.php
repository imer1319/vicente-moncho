@extends('layouts.app')

@section('content')
<div class="container">
    <valet-component :proveedores="{{ $proveedores }}"></valet-component>
</div>
@endsection
