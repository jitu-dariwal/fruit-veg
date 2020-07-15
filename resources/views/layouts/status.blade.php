@if(isset($status))
    @if((is_numeric($status) && $status == 1) || ($status === 'Y'))
        <span style="display: none; visibility: hidden">1</span>
        <button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>
        @else
        <span style="display: none; visibility: hidden">0</span>
        <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
    @endif
@endif