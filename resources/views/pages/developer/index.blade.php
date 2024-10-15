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
                            <li class="breadcrumb-item active">Developers</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Developers</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            
            
            <div class="col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="card-box">
                
                   
                    <form method="post" action="{{ route('add-developer') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <label for="simpleinput">Name Of Developer
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nameOfDev"  class="form-control">
                                @error('nameOfDev')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4" style="padding-top:14px;">
                                <button name="submit" value="submit" type="submit"
                                    class="btn btn-success waves-effect waves-light mt-2 btn-darkblue">Add</button>
                            </div>

                        </div>
                    </form>



                </div> <!-- end card-box-->
            </div> <!-- end col -->
      
            <div class="col-md-8 col-sm-12 col-xs-12 col-12">
                @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible text-center">
                    <h5>{{ Session::get('success') }}</h5>
                </div>
            @endif
                <div class="card-box">
                    @can('Developer Reports')
                        <div class="d-flex justify-content-end">
                            <a href="{{url('download-excel')}}"  class="btn btn-info waves-effect waves-light mt-3">
                                Developer Reports 
                            </a>
                        </div>  
                    @endcan 
                    <div class="table-responsive mt-3">
                        <table class="table table-centered table-nowrap table-hover mb-0" id="demo-foo-filtering">
                            <thead>
                                <tr>
                                    <th>Name Of Developer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody 
                            class="table table-centered table-nowrap table-hover mb-0" >
                                @foreach ($Developers as $Developer)
                                    <!-- Modal -->
                                    @php
                                        $isDevAssign = DB::table('teams')
                                        ->where('name_of_developer',$Developer->id)->first();

                                        
                                    @endphp
                                    <div class="modal fade" id="exampleModal-{{ $Developer->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    {{-- <h5 class="modal-title" id="exampleModalLabel">Update Status</h5> --}}
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <form method="post"
                                                                action="{{ url('developer-update') }}">
                                                                @csrf


                                                                <input type="hidden" name="updatenameOfDeveloperID"
                                                                    value="{{ $Developer->id }}">

                                                                <div class="form-group mb-3">

                                                                    <label>Name of Developers</label>
                                                                    <input type="text" name="nameOfDeveloper" 
                                                                    class="form-control"
                                                                    value="{{ $Developer->name_of_developer }}">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button name="submit" value="submit" type="submit"
                                                                        class="btn btn-primary waves-effect waves-light">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <tr>
                                        <td>{{ $Developer->name_of_developer }}</td>
                                        <td>
                                            <a href="{{ url('developer-update/'.$Developer->id) }}" class="action-icon"
                                                data-toggle="modal"
                                                data-target="#exampleModal-{{ $Developer->id }}"> 
                                                <i class="mdi mdi-square-edit-outline">
                                                    </i></a>
                                                   
                                             @if ($isDevAssign == null )
                                                <a  
                                                href="{{ url('developer-delete/'.$Developer->id) }}" class="action-icon"> 
                                                    <i class="mdi mdi-delete text-danger"></i>
                                                </a> 
                                             @else
                                             <a  class="action-icon" > 
                                                 <i class="mdi mdi-delete"></i>
                                             </a>
                                             @endif       
                                             
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                      {{-- @if(method_exists($Developers, 'links')) 
                     <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $Developers->links('pagination::bootstrap-4'); }}
                    </ul> 
                    @endif --}}
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
</style>
<script>
    $('#demo-foo-filtering').dataTable( {
        // lengthMenu: [
        //     [100,75, 50,25,  -1],
        //     [100,75, 50,25, 'All'],
        // ],
        processing: true, 
    });

    
</script>

@endsection

