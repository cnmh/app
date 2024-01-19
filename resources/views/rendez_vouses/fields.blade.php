<!-- Id Consultation Field -->




<!-- Date Rendez Vous Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_rendez_vous', __('models/rendezVouses.fields.date_rendez_vous').':') !!}
    {!! Form::datetimeLocal('date_rendez_vous',  now(),  ['class' => 'form-control','id'=>'date_rendez_vous']) !!}
</div>

<input type="hidden" name="consultation_id" value="{{isset($rendezVous) ? $rendezVous->consultation_id : $consultation_id}}">

@push('page_scripts')
    <script type="text/javascript">
        $('#date_rendez_vous').datepicker()
    </script>
@endpush



<!-- Remarques Field -->
<div class="form-group col-sm-12">
    {!! Form::label('remarques', __('models/rendezVouses.fields.remarques').':') !!}
    {!! Form::textarea('remarques', isset($rendezVous)?$rendezVous->remarques : null, ['class' => 'form-control rendez-remarque', 'maxlength' => 65535, 'maxlength' => 65535]) !!}
</div>

