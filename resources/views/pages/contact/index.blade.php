@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Modal -->
        <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h4 class="modal-title" id="myCenterModalLabel">Profile</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="media mb-3">
                            <img class="d-flex mr-3 rounded-circle avatar-lg"
                                src="{{ url('') }}/assets/images/users/user-8.jpg" alt="Generic placeholder image">
                            <div class="media-body">
                                <h4 class="mt-0 mb-1">Jade M. Walker</h4>
                                <p class="text-muted">Branch manager</p>
                                <p class="text-muted"><i class="mdi mdi-office-building"></i> Vine Corporation</p>

                                <a href="javascript: void(0);" class="btn- btn-xs btn-info">Send Email</a>
                                <a href="javascript: void(0);" class="btn- btn-xs btn-secondary">Call</a>
                                <a href="javascript: void(0);" class="btn- btn-xs btn-secondary">Edit</a>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4 text-uppercase bg-light p-2"><i class="mdi mdi-account-circle mr-1"></i>
                            Personal
                            Information</h5>
                        <div class="">
                            <h4 class="font-13 text-muted text-uppercase">About Me :</h4>
                            <p class="mb-3">
                                Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                1500s, when an unknown printer took a galley of type.
                            </p>

                            <h4 class="font-13 text-muted text-uppercase mb-1">Date of Birth :</h4>
                            <p class="mb-3"> March 23, 1984 (34 Years)</p>

                            <h4 class="font-13 text-muted text-uppercase mb-1">Company :</h4>
                            <p class="mb-3">Vine Corporation</p>

                            <h4 class="font-13 text-muted text-uppercase mb-1">Added :</h4>
                            <p class="mb-3"> April 22, 2016</p>

                            <h4 class="font-13 text-muted text-uppercase mb-1">Updated :</h4>
                            <p class="mb-0"> Dec 13, 2017</p>

                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item active">Contacts</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Contacts</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button>
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                        href="{{ route('create-contact') }}">Add New</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Assigned To</th>
                                        <th>job title</th>
                                        <th>Contact type</th>
                                        <th style="width: 82px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $contact) 
                                    <tr>
                                        <td class="table-user">
                                            <div class="text-sm-right">
                                                <a href="javascript:void(0);"
                                                    class="text-body waves-effect waves-light   font-weight-semibold"
                                                    data-toggle="modal" data-target="#custom-modal">{{ $contact->contact_name }}</a>
                                            </div>

                                        </td>
                                        <td>
                                            {{-- <a href="whatsapp://send?text=Hello World!&phone=+9198********1">Ping me on WhatsApp</a> --}}

                                            {{ $contact->contact_number }}
                                        </td>
                                        <td>
                                            {{ $contact->email }}
                                        </td>
                                        <td>
                                            {{ $contact->name }}
                                        </td>
                                        <td>
                                            {{ $contact->job_title }}
                                        </td>

                                        <td>
                                            {{ $contact->contact_type }}
                                        </td>
                                        <td>
                                            <a href="{{ url('edit-contact/' . $contact->id) }}" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline">
                                                </i></a>
                                                <a href="{{ url('delete-contact/' . $contact->id) }}"
                                                    class="action-icon">
                                                    <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            <li class="page-item">
                                <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                    <span aria-hidden="true">�</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                            <li class="page-item">
                                <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                    <span aria-hidden="true">�</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
