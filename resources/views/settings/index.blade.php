@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan Aplikasi</h3>
                </div>
                <div class="card-body">
                    <h5>Backup & Restore</h5>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('settings.backup') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-download"></i> Backup Database
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('settings.restore') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-upload"></i> Restore Database
                                </button>
                            </form>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mt-4">Informasi Sistem</h5>
                    <hr>

                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Nama Aplikasi</strong></td>
                            <td>{{ config('app.name') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Versi Laravel</strong></td>
                            <td>{{ \Illuminate\Foundation\Application::VERSION }}</td>
                        </tr>
                        <tr>
                            <td><strong>Environment</strong></td>
                            <td>{{ config('app.env') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Database</strong></td>
                            <td>{{ config('database.default') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
