@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Bem vindo, {{ auth()->user()->name }}!</h5>
                    <p class="card-text">
                        Voce esta autenticado. Utilize o menu lateral para navegar pelo painel.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
