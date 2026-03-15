@extends('layouts.app')

@section('title', 'Log Kehadiran Kasir')

@section('content')
<div class="container-fluid">
    <!-- Filter Calendar -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-body py-2">
                    <form action="{{ route('attendances.index') }}" method="GET" class="form-inline">
                        <label class="mr-2 font-weight-bold"><i class="fas fa-calendar-alt mr-1 text-info"></i> Filter Periode:</label>
                        <select name="month" class="form-control form-control-sm mr-2">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ (request('month', date('n')) == $m) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                        <select name="year" class="form-control form-control-sm mr-2">
                            @for($y=date('Y'); $y>=date('Y')-5; $y--)
                                <option value="{{ $y }}" {{ (request('year', date('Y')) == $y) ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-info btn-sm px-3 shadow-sm">
                            <i class="fas fa-search"></i> CARI
                        </button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-default btn-sm ml-2">Reset</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-5">
            <!-- 1. MANAGE NAMES (Kelola Nama Karyawan) -->
            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title text-success font-weight-bold">
                        <i class="fas fa-users-cog mr-1"></i> 1. Kelola Nama Karyawan
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Quick Add Form -->
                    <form action="{{ route('attendances.add-employee') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Posisi / Role</label>
                            <select name="role" class="form-control form-control-sm" required>
                                <option value="cashier">Kasir</option>
                                <option value="packing" selected>Packing</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm btn-block shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> SIMPAN NAMA
                        </button>
                    </form>

                    <hr>

                    <!-- List of Managed Names -->
                    <label class="small font-weight-bold mb-2"><i class="fas fa-list mr-1"></i> Daftar Nama Terdaftar:</label>
                    <div style="max-height: 200px; overflow-y: auto;" class="border rounded p-2 bg-light">
                        @foreach($employees as $employee)
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                                <div>
                                    <span class="font-weight-bold small text-dark d-block">{{ $employee->name }}</span>
                                    <span class="badge badge-light border text-muted" style="font-size: 10px;">{{ ucfirst($employee->role) }}</span>
                                </div>
                                <form action="{{ route('attendances.remove-employee', $employee->id) }}" method="POST" onsubmit="return confirm('Hapus nama ini dari daftar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-link text-danger p-0" title="Hapus Nama">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 2. LOG ATTENDANCE (Pencatatan Kehadiran) -->
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title text-primary font-weight-bold">
                        <i class="fas fa-user-check mr-1"></i> 2. Absen Kehadiran
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendances.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="manual" value="1">
                        
                        <div class="form-group">
                            <label for="cashier_names">Pilih Karyawan <span class="text-danger">*</span></label>
                            <select name="cashier_names" id="cashier_names" class="form-control @error('cashier_names') is-invalid @enderror" required>
                                <option value="">-- Temukan Nama --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->name }}" data-role="{{ $employee->role }}">
                                        {{ $employee->name }} ({{ ucfirst($employee->role) }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="role" id="hidden_role">
                        </div>

                        <div class="form-group">
                            <label>Status Kehadiran</label>
                            <div class="d-flex justify-content-between">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="status1" name="status" value="present" checked onclick="toggleReason(false)">
                                    <label for="status1" class="custom-control-label font-weight-normal text-success">Hadir</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="status2" name="status" value="sick" onclick="toggleReason(true)">
                                    <label for="status2" class="custom-control-label font-weight-normal text-warning">Sakit</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="status3" name="status" value="permit" onclick="toggleReason(true)">
                                    <label for="status3" class="custom-control-label font-weight-normal text-info">Izin</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="status4" name="status" value="alpha" onclick="toggleReason(true)">
                                    <label for="status4" class="custom-control-label font-weight-normal text-danger">Alpa</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-none" id="reason_container">
                            <label for="reason">Keterangan / Alasan</label>
                            <textarea name="reason" id="reason" class="form-control" rows="2" placeholder="Sakit flu, Izin urusan keluarga..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block shadow-sm py-2">
                            <i class="fas fa-save mr-1"></i> CONFIRM KEHADIRAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            <!-- History Table Advanced -->
            <div class="card card-apms shadow-sm">
                <div class="card-header bg-primary-apms text-white">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Data Kehadiran Karyawan</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Karyawan</th>
                                    <th>Status</th>
                                    <th>Jam</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td class="small">{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $attendance->employee_name }}</div>
                                        <div class="badge badge-light border small text-muted font-weight-normal">{{ $attendance->role }}</div>
                                    </td>
                                    <td>
                                        @if($attendance->status == 'present')
                                            <span class="badge badge-success px-2 py-1">Hadir</span>
                                        @elseif($attendance->status == 'sick')
                                            <span class="badge badge-warning px-2 py-1 text-white">Sakit</span>
                                        @elseif($attendance->status == 'permit')
                                            <span class="badge badge-info px-2 py-1">Izin</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">Alpa</span>
                                        @endif
                                        
                                        @if($attendance->reason)
                                            <div class="small text-muted mt-1 italic font-italic">"{{ $attendance->reason }}"</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->status == 'present')
                                            <div class="text-xs text-muted mb-1">In: {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}</div>
                                            @if($attendance->time_out)
                                                <div class="text-xs text-danger">Out: {{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->status == 'present' && !$attendance->time_out && $attendance->date->isToday())
                                            <form action="{{ route('attendances.checkout', $attendance->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-outline-danger shadow-sm">
                                                    <i class="fas fa-sign-out-alt"></i> Out
                                                </button>
                                            </form>
                                        @elseif($attendance->time_out)
                                            <i class="fas fa-check-double text-success small" title="Shift Selesai"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-calendar-times transition-3 fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data absensi untuk periode ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right small">
                        {{ $attendances->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleReason(show) {
        const container = document.getElementById('reason_container');
        if (show) {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }

    document.getElementById('cashier_names').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('hidden_role').value = selectedOption.getAttribute('data-role');
    });
</script>
@endpush
@endsection
