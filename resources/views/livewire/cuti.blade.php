<div class="page-inner">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">
            <a href="{{route('dashboard')}}">
              <span><i class="fas fa-arrow-left mr-3"></i>Data Pengajuan Cuti</span>
            </a>
            <div class="pull-right">
              @if (!$form && !$modal)
              <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i>
                Batal</button>
              @else
              <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i>
                Tambah Data Pengajuan Cuti</button>
              @endif
            </div>
          </h4>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <livewire:table.cuti-table />
    </div>

    {{-- Modal form --}}
    <div id="form-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
      <div class="modal-dialog" permission="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="my-modal-title">{{$update_mode ? 'Edit':'Tambah'}} Pengajuan Cuti
            </h5>
          </div>
          <div class="modal-body">
            <x-select name="user_id" label="Karyawan">
              <option value="">Pilih Salah satu</option>
              @foreach ($users as $user)
              @if ($user->karyawan)
              <option value="{{$user->id}}">{{$user->karyawan?->nama_karyawan}}</option>
              @endif
              @endforeach
            </x-select>
            <x-text-field type="date" name="tanggal_cuti" label="Tanggal Cuti" />
            <x-text-field type="text" name="lama_cuti" label="lama Cuti" />
            <x-text-field type="text" name="alasan_cuti" label="Alasan Cuti" />
            @if ($this->update_mode)
            <x-select name="status_pengajuan" label="Status Pengajuan">
              <option value="">Pilih Salah satu</option>
              <option value="1">Setujui</option>
              <option value="2">Tolak</option>
            </x-select>
            @endif
            @if ($this->status_pengajuan == 2)
            <x-text-field type="text" name="alasan_penolakan" label="Alasan Penolakan" />
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" wire:click={{$update_mode ? 'update' : 'store' }} class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan</button>
            <button class="btn btn-danger btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
          </div>
        </div>
      </div>
    </div>


    {{-- Modal confirm --}}
    <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
      <div class="modal-dialog" permission="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
          </div>
          <div class="modal-body">
            <p>Apakah anda yakin hapus data ini.?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i class="fa fa-check pr-2"></i>Ya,
              Hapus</button>
            <button class="btn btn-primary btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
  <script>
    document.addEventListener('livewire:load', function(e) {
            window.livewire.on('showModal', (data) => {
                $('#form-modal').modal('show')
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
                $('#form-modal').modal('hide')
            });
        })
  </script>
  @endpush
</div>