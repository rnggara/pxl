@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Add Template Contract</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('hrd.contract.index') }}" class="btn btn-icon btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('hrd.contract.save') }}" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label class="col-form-label col-3">Template Name</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ (!empty($tp)) ? $tp->name : "" }}" required name="template_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-3">Template Targets</label>
                            <div class="col-9">
                                <select name="template_target" class="form-control select2" id="" data-placeholder="All">
                                    <option value=""></option>
                                    @foreach ($emptypes as $item)
                                        <option value="{{ $item->id }}" {{ (!empty($tp) && $tp->targets == $item->id) ? "SELECTED" : "" }} >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Field Name</th>
                                    <th class="text-center">Field Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($row as $item)
                                    <tr>
                                        <td>{{ $item['value'] }}</td>
                                        <td>{!! $item['text'] !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 mb-2">
                        <span class="font-weight-bold">* Note : Type @ to add Field</span>
                    </div>
                    <div class="col-12">
                        <textarea name="content" id="txt-content" cols="30" rows="50">{!! (!empty($tp)) ? $tp->content : "" !!}</textarea>
                    </div>
                </div>
                <div class="form-group row mt-5">
                    <label class="col-form-label col-1"></label>
                    <div class="col-11 text-right">
                        @csrf
                        @if (!empty($tp))
                            <input type="hidden" name="id_tp" value="{{ $tp->id }}">
                        @endif
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="https://cdn.tiny.cloud/1/8xnye9bzn7hvwn32mc07x9x2krxeovqtwbmzkzr665bf333n/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        var specialChars = []
        function add_to_sc(data){
            specialChars.push(data)
        }
        $(document).ready(function(){
            $.ajax({
                url : "{{ route('hrd.contract.get_field') }}",
                type : "get",
                dataType : "json",
                success : function(specialChars){
                    console.log(specialChars)
                    tinymce.init({
                        selector : "#txt-content",
                        plugins: 'codesample code ',
                        codesample_languages: [
                            {text: 'HTML/XML', value: 'markup'},
                            {text: 'JavaScript', value: 'javascript'},
                            {text: 'CSS', value: 'css'},
                            {text: 'PHP', value: 'php'},
                            {text: 'Ruby', value: 'ruby'},
                            {text: 'Python', value: 'python'},
                            {text: 'Java', value: 'java'},
                            {text: 'C', value: 'c'},
                            {text: 'C#', value: 'csharp'},
                            {text: 'C++', value: 'cpp'}
                        ],
                        toolbar: 'codesample code undo redo styleselect bold italic alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                        setup: function (editor) {
                        var onAction = function (autocompleteApi, rng, value) {
                            editor.selection.setRng(rng);
                            editor.insertContent(value);
                            autocompleteApi.hide();
                        };

                        var getMatchedChars = function (pattern) {
                            return specialChars.filter(function (char) {
                                return char.value.indexOf(pattern) !== -1;
                            });
                        };

                        /**
                        * An autocompleter that allows you to insert special characters.
                        * Items are built using the CardMenuItem.
                        */
                        editor.ui.registry.addAutocompleter('specialchars_cardmenuitems', {
                            ch: '@',
                            minChars: 0,
                            columns: 1,
                            highlightOn: ['char_name'],
                            onAction: onAction,
                            fetch: function (pattern) {
                                return new tinymce.util.Promise(function (resolve) {
                                var results = getMatchedChars(pattern).map(function (char) {
                                    return {
                                        type: 'cardmenuitem',
                                        value: char.value,
                                        label: char.text,
                                        items: [
                                            {
                                                type: 'cardcontainer',
                                                direction: 'vertical',
                                                items: [
                                                    {
                                                        type: 'cardtext',
                                                        text: char.value
                                                    },
                                                    {
                                                        type: 'cardtext',
                                                        text: char.text
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                });
                                resolve(results);
                                });
                        }
                        });
                    }
                    })
                }
            })

            $("select.select2").select2({
                width : "100%",
                allowClear : true,
            })

            // var specialChars = [
            //     { text: 'exclamation mark', value: '!' },
            //     { text: 'at', value: '@' },
            //     { text: 'hash', value: '#' },
            //     { text: 'dollars', value: '$' },
            //     { text: 'percent sign', value: '%' },
            //     { text: 'caret', value: '^' },
            //     { text: 'ampersand', value: '&' },
            //     { text: 'asterisk', value: '*' }
            // ];

        })
    </script>
@endsection
