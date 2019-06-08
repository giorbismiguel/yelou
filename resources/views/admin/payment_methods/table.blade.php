<table class="table table-responsive-sm table-striped" id="paymentMethods-table">
    <thead>
        <th>Name</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($paymentMethods as $paymentMethod)
        <tr>
            <td>{!! $paymentMethod->name !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.paymentMethods.destroy', $paymentMethod->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.paymentMethods.show', [$paymentMethod->id]) !!}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('admin.paymentMethods.edit', [$paymentMethod->id]) !!}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>