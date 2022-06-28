<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $path = \Request::path();
        if (strpos($path, '/') !== false) {
            $path_ar = explode('/',$path);
            $title = '';
            $title = ucwords(str_replace('-',' ',$path_ar[1]));
        } else {
            $title = ucwords(str_replace('-',' ',$path));
        }

        if (strlen($title) <= 3){
            $title = strtoupper($title);
        }
    @endphp
    <title>{{$title}} | {{$comp->company_name}} - {{$comp->tag}}</title>
    @include('layouts.head')
    @php
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        activity("view")
            ->causedBy(\Session::get('company_user_id'))
            ->log($actual_link);
        $query = \Illuminate\Support\Facades\DB::getQueryLog();
        array_pop($query);
        foreach ($query as $item){
            activity("query")
                    ->causedBy(\Session::get('company_user_id'))
                    ->withProperties(['url' => str_replace("\\", "", $actual_link)])
                    ->log($item['query']);
        }
    @endphp
    @yield('css')
    <style>
        input:required{
            border-color: orange;
        }
        select:required{
            border-color: orange;
        }
    </style>
</head>
<body id="kt_body" class="header-fixed header-mobile-fixed page-loading-enabled">
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    @if (!isset($_GET['csms']))
    @include('layouts.header_mobile_')
    @endif
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            @if (!isset($_GET['csms']))
            @include('layouts.header_')
            @endif
            <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" style="background-color: #e6e6e6" id="kt_content">
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            <!--begin::Dashboard-->
                            <!-- Content here -->
                            <noscript>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                           Your browser does not support JavaScript!
                                        </div>
                                        <div class="modal fade show" style="display: block; padding: 17px; background-color: rgb(0, 0, 0, .5)" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content bg-dark text-white">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="alert alert-danger" role="alert">
                                                                    Please turn on you JavaScript to access Cypher
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <ul>
                                                                    <li>
                                                                        Chrome :
                                                                        <ul>
                                                                            <li>Settings > Site Settings > JavaScript : turn on allowed</li>
                                                                        </ul>
                                                                    </li>
                                                                    <li>
                                                                        Microsoft Edge :
                                                                        <ul>
                                                                            <li>Settings > Cookies & Site Permissions > JavaScript : turn on allowed</li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </noscript>
                        @yield('content')
                        <!--end::Dashboard-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                @if (!isset($_GET['csms']))
                @include('layouts.footer')
                @endif
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--begin::Quick Panel-->
    {{-- @include('layouts.quick_panel') --}}
    <!--end::Quick Panel-->
    <!--begin::Quick User -->
    {{-- @include('layouts.quick_user') --}}
    <!--end::Quick User -->
    {{-- @include('layouts.footer') --}}

    @include('layouts.scripts')
    @yield('custom_script')

    <script>
        function btn_one_click(btn, target){
            event.preventDefault();
                   //do something
           $(target).submit()
           $(btn).prop('disabled', true);
        }

        function _post(){
            Swal.fire({
                title: "Submitting Data",
                text: "loading",
                onOpen: function() {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
        }

        function _press_back(){
            history.back()
        }

        $(document).ready(function(){
            window.addEventListener("scroll", function () {
                var body = document.getElementById('kt_body')
                if (body.hasAttribute('data-header-scroll') === true){
                    $(".header-bottom").css('border-bottom', '5px solid #f1f1f6')
                }
            })

            $("input:required").each(function(){
                $(this).change(function(){
                    if ($(this).val() == "" || $(this).val() == undefined){
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).addClass('is-valid')
                    }
                })
            })

            $("span.menu-text").addClass('text-dark')

            var substringMatcher = function(strs) {
                return function findMatches(q, cb) {
                    var matches, substringRegex;

                    // an array that will be populated with substring matches
                    matches = [];

                    // regex used to determine if a string contains the substring `q`
                    substrRegex = new RegExp(q, 'i');

                    // iterate through the pool of strings and for any string that
                    // contains the substring `q`, add it to the `matches` array
                    $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                    });

                    cb(matches);
                };
            };

            var res = function() {
                var tmp = null;
                $.ajax({
                    url : "{{ route('menu.list') }}",
                    type : "get",
                    dataType : "json",
                    global : false,
                    async : false,
                    cache : false,
                    success : function(response){
                        tmp = response
                    },
                });

                return tmp
            }();

            var states = res

            $('#search-menu').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'states',
                source: substringMatcher(states)
            });

            $("#search-menu").focus()
        })
    </script>
</body>
</html>
