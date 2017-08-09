@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
	@if (Session::has('message_success'))
		<div class="alert alert-success">
			{{ session('message_success') }}
		</div>
	@endif
	@if ($errors->has('domain'))
		<div class="alert alert-warning">
			{{ $errors->first('domain') }}
		</div>
		@push ('validator_error')
		<script>
			$(document).ready(function(){
				$("#add_affiliate_domain").modal('show');
			});
		</script>
		@endpush
	@endif
        <div class="col-lg-3 col-md-3">
			<div class="affiliate_cabinet_block text-center">
				<div class="heading text-left">Профиль</div>
				<hr class="affilaite_hr">
				<img src="images/cabinet/no_foto.png" class="img-circle cabinet_avatar">
				<div class="affiliate_name">{{$user->name}}</div>
				<div class="affiliate_role">@if ($user->hasRole('affiliate'))Вебмастер@elseif ($user->hasRole('advertiser'))Рекламодатель@elseif($user->hasRole('manager'))Менеджер@elseif($user->hasRole('super_manager'))Ст. менеджер@elseif($user->hasRole('admin'))Администратор@endif</div>
				<div class="affiliate_email">{{$user->email}}</div>
				<div class="cabinet_gliph">
					<a href="" data-toggle="tooltip" data-placement="bottom" title="Редактировать профиль"><span class="glyphicon glyphicon-user gliph_affiliate"></span></a>
					<a href="" data-toggle="tooltip" data-placement="bottom" title="Справка"><span class="glyphicon glyphicon-question-sign gliph_affiliate"></span></a>
					<a href="" data-toggle="tooltip" data-placement="bottom" title="Новости"><span class="glyphicon glyphicon-envelope gliph_affiliate"></span></a>
					<a href="" data-toggle="tooltip" data-placement="bottom" title="Выйти"><span class="glyphicon glyphicon-log-out gliph_affiliate"></span></a>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="affiliate_cabinet_block">
				<div class="heading text-left">Баланс</div>
				<hr class="affilaite_hr">
				<div class="affiliate_balance text-center">@if ($userProf->balance==0)0.00 @else{{$userProf->balance}}@endif <span class="rur">q</span></div>
				<p class="text-center"><a href="#" class="btn btn-primary" role="button">Заказать выплату</a></p>
				<div class="affiliate_detal_balance"><span>Сегодня:</span><div class="right">0.00 <span class="rur">q</span></div></div>
				<div class="affiliate_detal_balance"><span>Вчера:</span><div class="right green">+10.00 <span class="rur">q</span></div></div>
				<div class="affiliate_detal_balance"><span>Неделя:</span><div class="right green">+50.00 <span class="rur">q</span></div></div>
				<div class="affiliate_detal_balance"><span>Месяц:</span><div class="right green">+70.00 <span class="rur">q</span></div></div>			
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="affiliate_cabinet_block text-center">
				<div class="heading text-left">Контакты</div>
				<hr class="affilaite_hr">
				@if ($manager!='0')
					<div class="affiliate_manager">Ваш менеджер:</div>
					<img src="images/cabinet/no_foto.png" class="img-circle manager_avatar">
					<div class="affiliate_name">{{$manager->name}}</div>
					<div class="manager_skype"><b>Skype:</b> live:support_29234</div>
					<div class="manager_skype"><b>Email:</b> {{$manager->email}}</div>
				@else
					<div class="no_manager">После добавления площадки за Вами будет закреплен персональный менеджер, если у Вас есть какие либо вопросы, обратитесь в службу поддержки: <b>support@market-place.su</b></div>
				@endif
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="affiliate_cabinet_block">
				<div class="heading text-left">Последние новости</div>
				<hr class="affilaite_hr">
			</div>
		</div>
    </div>
	 <div class="row" style="margin-top: 30px;">
	 <div class="col-lg-3 col-md-3">
			<div class="affiliate_cabinet_block">
				<div class="heading text-left">Площадки <a href="#" data-toggle="modal" data-target="#add_affiliate_domain" class="affiliate_add_domain"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-placement="bottom" title="Добавить площадку"></span></a></div>
				<hr class="affilaite_hr">
				<div id="affiliate_all_pads">
				@foreach ($partnerPads as $pad)
						<div class="affiliate_pad">
							<div data-toggle="tooltip" data-placement="bottom" title="{{$pad->domain}}" class="affiliate_all_pads_domain">
								{{$pad->domain}}
							</div>
							@if ($pad->status==0)
							<span data-toggle="tooltip" data-placement="bottom" title="На модерации" class="glyphicon glyphicon-time affiliate_all_pads_domain_gliph blue"></span>
							@elseif ($pad->status==2)
							<span data-toggle="tooltip" data-placement="bottom" title="Отклонена" class="glyphicon glyphicon-remove-circle affiliate_all_pads_domain_gliph red"></span>
							@elseif ($pad->status==1)
								@if ($pad->type==1 or $pad->type==3)
									<span data-toggle="tooltip" data-placement="bottom" title="Одобрена на товарный виджет" class="glyphicon glyphicon glyphicon-shopping-cart affiliate_all_pads_domain_gliph green"></span>
								@endif
								@if ($pad->type==2 or $pad->type==3)
									<span data-toggle="tooltip" data-placement="bottom" title="Одобрена на видео виджет" class="glyphicon glyphicon glyphicon glyphicon-facetime-video affiliate_all_pads_domain_gliph green"></span>
								@endif
							@endif
							<span data-toggle="tooltip" data-placement="bottom" title="Редактировать" class="glyphicon glyphicon glyphicon glyphicon glyphicon-cog affiliate_all_pads_domain_gliph blue pads_config"></span>
						</div>
				@endforeach
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="affiliate_cabinet_block">
				<div class="heading text-left">Эффективность площадок за сегодня</div>
				<hr class="affilaite_hr">
				{!! $chart->render() !!}
			</div>
		</div>
		<div class="col-lg-6 col-md-6">
			<div class="affiliate_cabinet_block">
				<div class="heading text-left">Виджеты <a href="#" data-toggle="modal" data-target="#add_affiliate_widget" class="affiliate_add_domain"><span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-placement="bottom" title="Создать виджет"></span></a></div>
				<hr class="affilaite_hr">
			</div>
		</div>
	</div>
</div>
@include ('affiliate.cabinet.add_affiliate_domain')
@include ('affiliate.cabinet.add_affiliate_widget')
@endsection
@push('cabinet_home')
	<link href="{{ asset('css/cabinet/home.css') }}" rel="stylesheet">
	<link href="{{ asset('css/rouble.css') }}" rel="stylesheet">
	<link href="{{ asset('css/modal.css') }}" rel="stylesheet">
	<link href="{{ asset('css/custom_scroll/jquery.custom-scroll.css') }}" rel="stylesheet">
	{!! Charts::assets() !!}
@endpush
@push('cabinet_home_js')
	<script>
		$(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
	<script src="{{ asset('js/custom_scroll/jquery.custom-scroll.min.js') }}"></script>
	<script>
	$('#affiliate_all_pads').customScroll({
  offsetTop: 32,
  offsetRight: 16,
  offsetBottom: -32,
  vertical: true,
  horizontal: false
});
	</script>
@endpush