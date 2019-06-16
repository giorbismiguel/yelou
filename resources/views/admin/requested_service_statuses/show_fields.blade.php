<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $requestedServiceStatus->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $requestedServiceStatus->name !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('description', 'Name:') !!}
    <p>{!! $requestedServiceStatus->description !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $requestedServiceStatus->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $requestedServiceStatus->updated_at !!}</p>
</div>

