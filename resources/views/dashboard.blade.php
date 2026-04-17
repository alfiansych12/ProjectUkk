@if(Auth::user()->role === 'admin')
    @include('admin.dashboard')
@elseif(Auth::user()->role === 'petugas')
    @include('petugas.dashboard')
@else
    @include('peminjam.dashboard')
@endif
