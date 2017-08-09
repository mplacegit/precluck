@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
			<form class="form-horizontal" method="post" action="{{url('widget/save')}}">
			{{ csrf_field() }}
			<input name="id_widget" value="{{$id_widget}}" readonly hidden>
			<div class="panel-group" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Общие настройки</a>
						</h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="form-group">
								<label for="ttype" class="col-md-5 control-label">Тип шаблона</label>
								<div class="col-md-6">
								<select name="ttype" id="ttype" class="form-control">
									@foreach ($templateTypes as $tType)
										<option data-type="{{$tType->id}}" value="{{$tType->name}}">{{$tType->title}}</option>
									@endforeach
								</select>
								</div>
							</div>
							<div class="form-group">
								<div>
									<label for="template" class="col-md-5 control-label">Шаблон</label>
									<div class="col-md-6">
									<select name="template" id="template_block" class="form-control">
										@foreach ($widgetTemplates as $wTemplate)
											@if ($wTemplate->type==1)
												<option value="{{$wTemplate->name}}">{{$wTemplate->title}}</option>
											@endif
										@endforeach
									</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div id="mobile_block">
									<label for="mobile_block" class="col-md-5 control-label">Мобильная версия</label>
									<div class="col-md-6">
									<input type="checkbox" name="mobile">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Внешний вид</a>
						</h4>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group">
								<label for="width-template" class="col-md-5 control-label">Ширина</label>
								<div class="col-md-6">
									<input name="width-template" id="width-template" type="number" min="10" value="200" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="height-template" class="col-md-5 control-label">Высота</label>
								<div class="col-md-6">
									<input name="height-template" type="number" min="10" value="200" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="column" class="col-md-5 control-label">Кол-во колонок</label>
								<div class="col-md-6">
									<input name="column" type="number" min="1" max="10" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="row" class="col-md-5 control-label">Кол-во рядов</label>
								<div class="col-md-6">
									<input name="row" type="number" min="1" max="10" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="background-color" class="col-md-5 control-label">Цвет фона</label>
								<div class="col-md-6">
									<input type="text" name="background-color" class="form-control" id="background-color-input" readonly value="#ffffff">
									<div id="background-color"><div style="background: #ffffff"></div></div>
								</div>
							</div>
							<div class="form-group">
								<label for="border-color" class="col-md-5 control-label">Цвет рамки</label>
								<div class="col-md-6">
									<input type="text" name="border-color" class="form-control" id="border-color-input" readonly value="#ffffff">
									<div id="border-color"><div style="background: #ffffff"></div></div>
								</div>
							</div>
							<div class="form-group">
								<label for="border-width" class="col-md-5 control-label">Толщина рамки</label>
								<div class="col-md-6">
									<span class="min_left">0</span><input type="range" name="border-width" class="form-control" id="border-width" min="0" max="10" step="1"><span class="max_right">10</span>
								</div>
							</div>
							<div class="form-group">
								<label for="border-radius" class="col-md-5 control-label">Закруглить углы</label>
								<div class="col-md-6">
									<span class="min_left">0</span><input type="range" name="border-radius" class="form-control" id="border-width" min="0" max="5" step="1"><span class="max_right">5</span>
								</div>
							</div>
							<div class="form-group">
								<label for="row" class="col-md-5 control-label">Цвет кнопки</label>
								<div class="col-md-6">
									
								</div>
							</div>
							<div class="form-group">
								<label for="row" class="col-md-5 control-label">Шрифт</label>
								<div class="col-md-6">
									
								</div>
							</div>
							<div class="form-group">
								<label for="row" class="col-md-5 control-label">Размер шрифтов</label>
								<div class="col-md-6">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Пункт Группы Свертывания #3</a>
						</h4>
					</div>
					<div id="collapseThree" class="panel-collapse collapse">
						<div class="panel-body">
						тут категории т.д.
						</div>
					</div>
				</div>
			</div>
			<button type="submit" class="btn btn-primary">
				Сохранить
			</button>
			</form>
		</div>
		<div class="col-md-7">
		<ul class="nav nav-tabs" role="tablist" id="myTabs">
			<li role="presentation" class="active"><a href="#desctop" aria-controls="desctop" role="tab" data-toggle="tab">Десктоп</a></li>
			<li role="presentation"><a href="#mobile" aria-controls="mobile" role="tab" data-toggle="tab">Мобильный</a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="desctop"></div>
			<div role="tabpanel" class="tab-pane" id="mobile">...</div>
		  </div>
		</div>
    </div>
</div>
@endsection
@push('cabinet_home')
<link rel="stylesheet" type="text/css" href="{{ asset('colorpicker/css/colorpicker.css') }}" />
<style>
#background-color, #border-color{
display: inline-block;
float: right;
border: 1px solid #ccd0d2;
width: 36px;
height: 36px;
border-radius: 4px;
}
#background-color div, #border-color div{
height: 100%;
width: 100%;
border-radius: 4px;
cursor: pointer;
}
#background-color-input, #border-color-input{
width: 80%;
float: left;
}
#background-color-input:focus, #border-color-input:focus{
border: 1px solid #ccd0d2;
box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
#border-width, #border-radius{
padding: 6px 0;
border: none;
box-shadow: none;
width: 80%;
float: left;
}
#border-width:focus, #border-radius:focus{
border: none;
box-shadow: none;
}
.min_left, .max_right{
width: 10%;
text-align: center;
line-height: 36px;
display: inline-block;
}
.min_left{
float: left;
}
.max_right{
float: right;
}
</style>
@endpush
@push('cabinet_home_js')
<script src="http://precluck.market-place.su/projects/widgeteditor.js?v=2"></script>
<script src="{{ asset('colorpicker/js/colorpicker.js') }}"></script>
<script>
	$(document).ready(function() {
		
		$('#background-color').ColorPicker({
        color: '#ffffff',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb) {
            $('#background-color div').css('backgroundColor', '#' + hex);
			$('#background-color-input').val('#'+hex);
        }
		});
		$('#border-color').ColorPicker({
        color: '#ffffff',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb) {
			$('#border-color div').css('backgroundColor', '#' + hex);
			$('#border-color-input').val('#'+hex);
        }
		});
		
		var desctop=$('#desctop');
		console.log(desctop);
		var s=new WidgetEditor(desctop);
		s.createIframe();
		console.log(s);
		
		$('#ttype').change(function(){
			if ($('#ttype option:selected').data('type')=='1'){
				jQuery ('#template_block').html(
					'@foreach ($widgetTemplates as $wTemplate)' +
						'@if ($wTemplate->type==1)' +
						'<option value="{{$wTemplate->name}}">{{$wTemplate->title}}</option>' +
						'@endif' +
					'@endforeach'
				);
			}
			else if ($('#ttype option:selected').data('type')=='2'){
				jQuery ('#template_block').html(
					'@foreach ($widgetTemplates as $wTemplate)' +
						'@if ($wTemplate->type==2)' +
						'<option value="{{$wTemplate->name}}">{{$wTemplate->title}}</option>' +
						'@endif' +
					'@endforeach'
				);
			}
			else if ($('#ttype option:selected').data('type')=='3'){
				jQuery ('#template_block').html(
					'@foreach ($widgetTemplates as $wTemplate)' +
						'@if ($wTemplate->type==3)' +
						'<option value="{{$wTemplate->name}}">{{$wTemplate->title}}</option>' +
						'@endif' +
					'@endforeach'
				);
			}
		var data = {
		name: $('#template_block option:selected').val(),
		width: $('#width-template').val()+"px",
		height: "300px"
		};
		s.reloadIframe(data);
		});
		
		$('#width-template').change(function(){
			var data = {
				name: $('#template_block option:selected').val(),
				width: $('#width-template').val()+"px",
				height: "300px"
				};
			s.reloadIframe(data);
		});
		
		/*$('#template_block').change(function(){
			
		});*/
	});
</script>
<script>
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
</script>
@endpush