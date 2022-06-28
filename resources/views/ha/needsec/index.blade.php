<div class="card card-custom gutter-b">
    <div class="card-header">
        <div class="card-title">
            <h3>RESTRICTED AREA</h3><br>

        </div>
        <div class="card-toolbar">

            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <h4>You need to register your security key first<br /><br />Thank you</h4>
        <hr />
        <br />
        <h4>Input Password : </h4>
        <form action='{{route('needsec.confirmation')}}' method='POST' class='col-md-4'>
            @csrf
            <input type="hidden" name="type" value="{{$type}}">
            <input type='password' class='form-control' name='searchInput' id='search' required>
            <input type='submit' class='form-control btn btn-success' name='submit' id='submit' value='Login'>
        </form>
    </div>
</div>
