<table class="table table-responsive-sm table-striped" id="transportationStates-table">
    <thead>
        <th>Name</th>
        <th>Description</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($transportationStates as $transportationStates)
        <tr>
            <td>{!! $transportationStates->name !!}</td>
            <td>{!! $transportationStates->description !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.transportationStates.destroy', $transportationStates->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.transportationStates.show', [$transportationStates->id]) !!}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('admin.transportationStates.edit', [$transportationStates->id]) !!}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>