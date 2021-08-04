<div class="page-inner">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">
            <a href="{{route('dashboard')}}">
              <span><i class="fas fa-arrow-left mr-3"></i>Data Absen</span>
            </a>
          </h4>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          {{-- <form @if ($tanggal_mulai && $tanggal_selesai) target="_blank" @endif
            action="{{route('print.riwayat.absensi', ['id' => $npk])}}" method="post">
          @csrf --}}
          <div class="row">
            <div class="col-md-4">
              <x-select name="npk" label="Karyawan">
                <option value="">Pilih Karyawan</option>
                @foreach ($employess as $employee)
                <option value="{{$employee->npk}}">{{$employee->nama_karyawan}}</option>
                @endforeach
              </x-select>
            </div>
            <div class="col-md-4">
              <x-text-date type="date" name="tanggal_mulai" label="Tanggal Mulai" />
            </div>
            <div class="col-md-4">
              <x-text-date type="date" name="tanggal_selesai" min="{{$tanggal_mulai}}" max="{{date('Y-m-d')}}"
                label="Tanggal Selesai" />
            </div>
          </div>
          <button type="button" class="btn btn-primary ml-2" wire:click="filter">Tampilkan</button>
          {{-- </form> --}}
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="header-title">Riwayat Absensi</h4>
        </div>
        <div class="card-body">
          <table class="table table-light">
            <thead class="thead-light">
              <tr>
                <td>Nama Karyawan</td>
                <td>Waktu Absen</td>
                <td>Jenis Absen</td>
                <td>Status Absen</td>
                <td>Keterangan</td>
                {{-- <td>Foto</td> --}}
                {{-- <td>Action</td> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
              <tr>
                <td>{{ $item->user->karyawan->nama_karyawan }}</td>
                <td>{{ $item->waktu_absen }}</td>
                <td>
                  @if ($item->type_absen == 1) Absen Masuk @endif
                  @if ($item->type_absen == 2) Absen Istirahat @endif
                  @if ($item->type_absen == 3) Selesai Istirahat @endif
                  @if ($item->type_absen == 4) Absen Pulang @endif
                </td>
                <td>
                  @if ($item->status_absen == 1)
                  @if ($item->type_absen == 4)
                  <button class="btn btn-success btn-sm">SUDAH ABSEN PULANG</button>
                  @else
                  <button class="btn btn-success btn-sm">TEPAT WAKTU</button>
                  @endif
                  @else
                  @if ($item->type_absen == 4)
                  <button class="btn btn-danger btn-sm">BELUM ABSEN PULANG</button>
                  @else
                  <button class="btn btn-danger btn-sm">TELAT</button>
                  @endif
                  @endif
                </td>
                <td>{{ $item->keterangan }}</td>
                {{-- <td>{{ $item->foto }}</td> --}}
                {{-- <td>
                        <button class="btn btn-success btn-sm" wire:click="getDataById('{{ $item->id }}')"
                id="btn-edit-{{ $item->id }}"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirm-modal"
                  wire:click="getId('{{ $item->id }}')" id="btn-delete-{{ $item->id }}"><i
                    class="fas fa-trash"></i></button>
                </td> --}}
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