@extends('layouts.template')

@section('css')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #section-to-print, #section-to-print * {
            visibility: visible;
        }
        #section-to-print {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
@endsection

@section('content')
    <div class="card card-custom gutter-b ">
        <div class="card-header">
            <h3 class="card-title">Barcode Generate</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="printDiv('print-barcode')">Print</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <iframe src="{{route('barcode.generate', $item->id)}}?act=p" name="print-barcode" id="print-barcode" height="0" width="0" frameborder="0"></iframe>
            <div class="row" id="section-to-print">
                <div class="col-12 text-center">
                    {!! $qr !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function printDiv(whichFrame) {
            window.frames[whichFrame].focus();
            window.frames[whichFrame].print();
        }
        $(document).ready(function(){

        })
    </script>
@endsection
