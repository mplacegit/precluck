<div id="add_affiliate_widget" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="affiliate_modal_header">Создание виджета<button class="modal_exit glyphicon glyphicon-remove-sign" type="button" data-dismiss="modal" data-toggle="tooltip" data-placement="bottom" title="Закрыть"></button></div>
			<hr class="modal_hr">
			<form class="form-horizontal" role="form" method="post" action="{{ url('add_pads')}}">
				{!! csrf_field() !!}
				<input type="hidden" name="user_id" value="{{$userProf->user_id}}">
				<div class="form-group">
					<label for="pad" class="col-md-4 control-label">Выберите площадку</label>
					<div class="col-md-6">
					<select name="pad" id="pad_for_widget" class="form-control">
						<option data-type="0">Выберите площадку</option>
						@foreach ($partnerPads as $ppad)
							@if ($ppad->status==1)
								<option value="{{$ppad->id}}" data-type="{{$ppad->type}}">{{$ppad->domain}}</option>
							@endif
						@endforeach
					</select>
					</div>
				</div>
				<div class="form-group">
					<div id="type_for_widget">
					
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-1 col-md-10 text-center" id="save_for_widget">

					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@push('cabinet_home_js')
<script>
$(function(){
	$('#pad_for_widget').change(function(){
		if ($('#pad_for_widget option:selected').data('type')=='1'){
		jQuery('#type_for_widget').html(
		'<label for="pad" class="col-md-4 control-label">Выберите тип виджета</label>' + 
		'<div class="col-md-6">' + 
		'<select name="url[]" class="form-control">' +
		'<option>Товарка</option>' +
		'</div>'
		);
		jQuery('#save_for_widget').html(
		'<button type="submit" class="btn btn-primary">Сохранить</button>'
		);
		}
		else if($('#pad_for_widget option:selected').data('type')=='2'){
		jQuery('#type_for_widget').html(
		'<label for="pad" class="col-md-4 control-label">Выберите тип виджета</label>' + 
		'<div class="col-md-6">' + 
		'<select name="url[]" class="form-control">' +
		'<option>Видео</option>' +
		'</div>'
		);
		jQuery('#save_for_widget').html(
		'<button type="submit" class="btn btn-primary">Сохранить</button>'
		);
		}
		else if($('#pad_for_widget option:selected').data('type')=='3'){
		jQuery('#type_for_widget').html(
		'<label for="pad" class="col-md-4 control-label">Выберите тип виджета</label>' + 
		'<div class="col-md-6">' + 
		'<select name="url[]" class="form-control">' +
		'<option>Товарка</option>' +
		'<option>Видео</option>' +
		'</div>'
		);
		jQuery('#save_for_widget').html(
		'<button type="submit" class="btn btn-primary">Сохранить</button>'
		);
		}
		else if($('#pad_for_widget option:selected').data('type')=='0'){
			jQuery('#type_for_widget').html(
			' ');
			jQuery('#save_for_widget').html(
			' ');
		}
	})
})
</script>
@endpush