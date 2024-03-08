@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Panel Administrativo</div>

                <div class="card-body">
                    <div class="row">
                        <div class="card col-md-4 bg-primary text-white mx-2" style="width: 30%;">
                            <div class="card-body">
                                <h5 class="card-title">proveedores</h5>
                                <p class="card-text">{{ $proveedores }}</p>
                            </div>
                        </div>
                        <div class="card col-md-4 bg-success text-white mx-2"style="width: 30%;">
                            <div class="card-body">
                                <h5 class="card-title">Vale</h5>
                                <p class="card-text">{{ $valets }}</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
