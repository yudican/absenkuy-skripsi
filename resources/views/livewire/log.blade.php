<div class="page-inner">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">
            <a href="{{route('dashboard')}}">
              <span><i class="fas fa-arrow-left mr-3"></i>Data Log</span>
            </a>
          </h4>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-light">
            <thead class="thead-light">
              <tr>
                <td>User</td>
                <td>Keterangan</td>
                <td>Aksi</td>
                <td>Tanggal</td>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
              <tr>
                <td>{{ $item->user ? $item->user->karyawan->nama_karyawan : '-' }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>
                  @switch($item->aksi)
                  @case('create')
                  <button class="btn btn-success btn-sm">{{ $item->aksi }}</button>
                  @break
                  @case('update')
                  <button class="btn btn-warning btn-sm">{{ $item->aksi }}</button>
                  @break
                  @default
                  <button class="btn btn-danger btn-sm">{{ $item->aksi }}</button>
                  @endswitch
                </td>
                <td>{{ $item->tanggal_log }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    @push('scripts')
    <script>
      document.addEventListener('livewire:load', function(e) {
           
        })
    </script>
    @endpush
  </div>
</div>