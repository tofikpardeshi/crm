@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Homents</a></li>
                            <li class="breadcrumb-item active">CRM Links and Url</li>
                        </ol>
                    </div>
                    <h4 class="page-title">CRM Links and Url</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
 
            <div class="col-md-12 col-sm-12 col-xs-12 col-12">
                @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible text-center">
                    <h5>{{ Session::get('success') }}</h5>
                </div>
            @endif
                <div class="card-box">
                    <div class="d-flex justify-content-end">
                        <a href="{{ url('/crm-toolbar-create') }}" class="btn btn-primary waves-effect waves-light mt-3">New CRM Link</a>
                    </div>
                    <div class="table-responsive mt-3">

                        <table class="table table-centered table-nowrap table-hover mb-0" id="demo-foo-filtering">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Action</th>
                                    <th>Project Name</th>
                                    <!-- <th>Website Link</th> -->
                                    <th>User Name</th>
                                    <th>Password</th>
                                    <th>Created By</th> 
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crmToolbars as $toolbar)
                                    @php
                                        
                                        $Projects = App\Models\Project::where('id', $toolbar->projects_id)
                                            ->select('project_name', 'id')
                                            ->first();

                                            $websiteLink = $toolbar->website;

                                            // Add protocol if missing
                                            if (!preg_match('~^(?:f|ht)tps?://~i', $websiteLink)) {
                                                $websiteLink = 'http://' . $websiteLink;
                                            }
                                        
                                    @endphp
                                    <tr>
                                        <td>{{ $toolbar->snumber ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ $websiteLink }}" class="btn btn-info" target="_blank">
                                                View
                                            </a>
                                            <a href="{{ url('toolbar-edit/' . $toolbar->snumber) }}"
                                                class="action-icon btn btn-warning btn-ls text-light mr-2">
                                                <i class="mdi mdi-square-edit-outline"> </i>
                                            </a>  
                                        </td>
                                        <td>{{ $toolbar->projects_id ?? 'N/A' }}</td>
                                        <!-- <td>
                                            <a href="{{ $websiteLink }}" target="blank">Link</a>
                                        </td> -->
                                        <td>{{ $toolbar->email ?? 'N/A' }}</td>
                                        <td>{{ str_repeat('*', min(strlen($toolbar->password), 8)) }}</td>
                                        <td>{{ $toolbar->created_by ?? 'N/A' }}</td>
                                        <td> 
                                            <a href="{{ url('delte-toolbar/' . $toolbar->snumber) }}"
                                                class="action-icon mr-4 btn btn-danger btn-xs text-light"> <i
                                                    class="mdi mdi-delete"></i>
                                                </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $locations->links('pagination::bootstrap-4'); }}
                    </ul> --}}
                </div>
            </div>
        </div>


    </div> <!-- container -->
@endsection

@section('scripts')

<style>
    #demo-foo-filtering_length{
        display: none;
    }
    #demo-foo-filtering_info{
        display: none;
    }
</style>

<script>
    $('#demo-foo-filtering').dataTable( {
        lengthMenu: [
            [100,75, 50,25,  -1],
            [100,75, 50,25, 'All'],
        ],
        processing: true, 
    });

    
</script>

@endsection


