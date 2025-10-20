@extends('layouts.admin')

@section('title', 'Nova conta a pagar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('payables.index') }}">Contas a pagar</a></li>
    <li class="breadcrumb-item active">Nova conta</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Cadastrar nova conta</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('payables.store') }}" method="POST">
                        @include('payables._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
