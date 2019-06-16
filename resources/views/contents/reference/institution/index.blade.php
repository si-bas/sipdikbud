@extends('layouts.default')

@include('plugins.datatables')
@include('plugins.sweetalert2')
@include('plugins.dropify')

@section('content')
<h3 class="heading_b uk-margin-bottom">Bidang/Lembaga Penilitian</h3>
<div class="md-card uk-margin-medium-bottom">
    <div class="md-card-content">
        <div class="uk-grid">
            <div class="uk-width-1-1">
                <button type="button" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light md-btn-icon" data-uk-modal="{target:'#institution-create'}"><i class="uk-icon-plus"></i> Buat Bidang/Lembaga</button>
            </div>
        </div>
        <br>
        <div class="uk-overflow-container">
            <table class="uk-table uk-table-hover" id="institution-table">
                <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th>Nama</th>
                    <th>Jumlah Penelitian</th>
                    <th>Dibuat pada</th>
                    <th style="width: 12%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="uk-modal" id="institution-create">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Formulir Bidang/Lembaga</h3>
        </div>
        
        <form action="{{ route('reference.institution.create.submit') }}" method="POST">
            @csrf
            <div class="uk-form-row">
                <label>Nama Bidang/Lembaga <span class="uk-text-danger">*</span></label>
                <input type="text" class="md-input" name="name" required />
            </div>
            <div class="uk-form-row">
                <label>Deskripsi</label>
                <textarea class="md-input autosized" name="description" cols="30" rows="4"></textarea>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-default uk-modal-close">Tutup</button>
                <button type="submit" class="md-btn md-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="uk-modal" id="institution-update">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Formulir Perubahan Bidang/Lembaga</h3>
        </div>
        
        <form action="{{ route('reference.institution.update.submit') }}" method="POST">
            <span class="form"></span>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-default uk-modal-close">Tutup</button>
                <button type="submit" class="md-btn md-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        var institution_table

        $(function () {
            institution_table = $('#institution-table').DataTable({
                language: defaultLang,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('reference.institution.data') }}"
                },
                columnDefs: [
                    {
                        targets: [0, 2, 4],
                        searchable: false,
                        orderable: false
                    },
                    {
                        targets: [5],
                        visible: false
                    }
                ],
                order: [
                    [5, 'desc']
                ], 
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'collections_count',
                        name: 'collections_count'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ]
            })
        })

        function updateInstitution(id) {
            $.get("{{ route('reference.institution.update.form') }}", {
                id: id
            }).done(function (result) {
                $('#institution-update').find('.form').html(result)
                UIkit.modal('#institution-update').show()
            })
        }

        function deleteInstitution(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Ingin menghapus kategori ini",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    $.post("{{ route('reference.institution.delete.submit') }}", {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE',
                        id: id
                    }).done(function (result) {
                        Swal.fire(
                            'Berhasil!',
                            result,
                            'success'
                        )

                        institution_table.draw(false)
                    })
                }
            })
        }
    </script>
@endpush