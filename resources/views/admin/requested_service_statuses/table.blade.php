<table class="table table-responsive-sm table-striped" id="requestedServiceStatuses-table">
    <thead>
        <th>Name</th>
        <th>Description</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($requestedServiceStatuses as $requestedServiceStatus)
        <tr>
            <td>{!! $requestedServiceStatus->name !!}</td>
            <td>{!! $requestedServiceStatus->description !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.requestedServiceStatuses.destroy', $requestedServiceStatus->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.requestedServiceStatuses.show', [$requestedServiceStatus->id]) !!}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('admin.requestedServiceStatuses.edit', [$requestedServiceStatus->id]) !!}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>