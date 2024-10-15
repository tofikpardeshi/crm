@extends('main')
<!-- Start Content-->


@section('dynamic_page')
    <style>
        .ls {
            border-bottom: 1px solid #eee;
            padding: 10px 0
        }

        .ls-1 {
            font-weight: 400;
        }

        .ls-2 {
            font-weight: 700;
            border-bottom: 1px solid #eee;
        }

        .text-bold {
            font-weight: bold !important
        }

        .text-uppercase {
            text-transform: uppercase
        }

        .table tr th,
        .table tr td {
            font-size: 11px !important
        }

        .text-2 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            min-height: 27px
        }

        .the-doc-image {
            max-height: 166px;
            overflow: hidden;
            min-height: 166px;
            border: 1px solid rgba(0, 0, 0, 0.04)
        }
    </style>
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    @if (session()->has('danger'))
                        <div class="alert alert-danger text-center mt-3" id="notification">
                            {{ session()->get('danger') }}
                        </div>
                    @endif
                    <h4 class="page-title">Gallery > {{ $LeadInfo->lead_name }}</h4>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ url('lead-status/' . encrypt($LeadInfo->id)) }}">
                        <button type="button" class="btn btn-danger waves-effect waves-light mr-1">Back</button>
                    </a>
                </div>
                <br>
                <div class="row ">
                    @foreach ($images as $item)
                   @php
                        $UploadeUserDetails = App\Models\User::where('id', $item->uploaded_by)->select('name')->first()
                   @endphp
                        <div class="col-md-6 col-xl-3">
                            <div class="card-box product-box">
                                @php
                                    $extension = \File::extension($item->images);
                                    // dd($extension);
                                    $audioName = trim($item->images, '/var/www/crm/');  
                                   
                                @endphp
                                <div class="product-action" @if (Auth::user()->roles_id == 1) @else style="display:none" @endif>
                                    <a href="{{ url('delete-docs/' . encrypt($item->id)) }}"
                                        class="btn btn-danger btn-xs waves-effect waves-light">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </div>

                                <div class="bg-light the-doc-image"
                                    @if ($extension == 'jpg' || $extension == 'png' || $extension === 'jpeg') style="display:block" @else style="display:none" @endif>
                                     <a href="{{asset($audioName)}}" target="_blank">
                                        <img src="{{asset($audioName)}}" alt="product-pic" class="img-fluid" />
                                    </a> 
                                </div>
                                
                                <div  
                                @if ($extension == 'mp3' || $extension == 'ogg' ) style="display:block" @else style="display:none" @endif>  
                                 <!-- Debugging output -->
                                    <audio controls>
                                        <source src="{{ asset($audioName) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                            </div>
                               

                                {{-- <div class="bg-light the-doc-image" @if ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx') style="display:block" @else style="display:none" @endif>
                                    @if (file_exists(public_path("/files/$item->images")))
                                        <embed src="files/{{ $item->images }}" id="pdf_display_frame"> 
                                    @endif
                                </div>  --}}

                                 <div class="bg-light the-doc-image"
                                    @if ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx') style="display:block" @else style="display:none" @endif>
                                    @if ($extension == 'pdf')
                                    <p><a href="{{ asset($audioName) }}" target="_blank">View</a></p>
                                        <embed src="{{ asset($audioName) }}" id="pdf_display_frame"> 
                                    @elseif ($extension == 'doc' || $extension == 'docx')
                                        <p>This document format is not supported for direct viewing. <a
                                                href="{{ asset($audioName) }}" download>Download</a> it instead.</p>
                                    @endif
                                </div>
                                
                                <div class="mt-1">
                                    <p class="font-12 mt-0 sp-line-1">Uploaded By : <strong>{{ $UploadeUserDetails->name }}</strong></p>
                                    <p>Date : <strong>{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y H:i') }}</strong></p>
                                </div>

                                <div class="product-info">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            @if ($item->documents == 1)
                                                <h5 class="font-15 mt-0 sp-line-1">
                                                    Customer Reg. Copy
                                                </h5>
                                            @elseif($item->documents == 2)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 2)  @endif>
                                                    Booking Docs
                                                </h5>
                                            @elseif($item->documents == 3)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 3)  @endif>
                                                    Site Visit Pics & Docs
                                                </h5>
                                            @elseif($item->documents == 4)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 4)  @endif>
                                                    BBA / ATS Docs
                                                </h5>

                                            @elseif($item->documents == 5)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 5)  @endif>
                                                    Property Pictures
                                                </h5> 
                                            @elseif($item->documents == 6)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 6)  @endif>
                                                    KYC Buyer
                                                </h5>
                                            @elseif($item->documents == 7)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 7)  @endif>
                                                    KYC Seller
                                                </h5> 
                                            @elseif($item->documents == 8)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 8)  @endif>
                                                    Audio File
                                                </h5>

                                            @elseif($item->documents == 9)
                                                <h5 class="font-15 mt-0 sp-line-1"
                                                    @if ($item->documents == 9)  @endif>
                                                    Others
                                                </h5>
                                            @endif

                                        </div>

                                    </div> <!-- end row -->
                                </div> <!-- end product info-->
                            </div> <!-- end card-box-->
                        </div> <!-- end col-->
                    @endforeach
                    <!--                        @foreach ($images as $item)
    <div class="col">
                                    <div class="card">
                                        <img src="files/{{ $item->images }}" class="img-fluid" alt="...">
                                        <a href="{{ url('delete-docs/' . encrypt($item->id)) }}"
                                            class="action-icon mr-4 btn btn-danger btn-xs text-light">
                                            <i class="mdi mdi-delete"></i></a>
                                    </div>
                                     
                                </div>
    @endforeach-->
                </div>

            </div>
        </div>
    </div> <!-- container -->
@endsection

@section('scripts')
    <script></script>
@endsection

