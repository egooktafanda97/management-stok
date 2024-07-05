@extends('Template.layout')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Tables</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li aria-current="page" class="breadcrumb-item active">Data Hutang Pelanggan</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="example" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NAMA</th>
                                    <th>TIPE USER</th>
                                    <th>JUMLAH HUTANG</th>
                                    <th>PEMBYARAN</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggans as $pelanggan)
                                    <tr>
                                        <td>{{ $pelanggan->nama ?? '' }}</td>
                                        <td>{{ $pelanggan->user_type ?? '' }}</td>
                                        <td>{{ 'Rp. ' . number_format($pelanggan->debtUsers->total) }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ url('pelanggan/hutang', ['id' => $pelanggan->id]) }}"><i
                                                    class='bx bx-pencil'></i> pembayaran
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning"
                                                href="{{ url('pelanggan/edit', ['id' => $pelanggan->id]) }}">
                                                {{-- bx detail --}}
                                                <i class='bx bx-pencil'></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end w-100 align-end">
                        {!! $pelanggans->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda akan menghapus data ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = document.getElementById('delete-form-' + id);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
