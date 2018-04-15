@extends('layouts.default')
@section('title','重置密码')
@section('content')
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">更新密码</div>
		<div class="panel-body">
			@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
			@endif
			<form action="{{ route('password.update') }}" class="form-horizontal" method="POST">
				{{ csrf_field() }}
				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group{{ $errors->has('email')?' has-error':'' }}">
					<label for="email" class="col-md-4 control-label">邮箱地址：</label>
					<div class="col-md-6">
						<input type="email" id="email" class="form-control" name="email" value="{{ $email or old('email') }}" require autofocus>
						@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('password')?' has-error':'' }}">
					<label for="password" class="col-md-4 control-label">密码：</label>
					<div class="col-md-6">
						<input type="password" id="password" class="form-control" name="password" value="{{ $password or old('password') }}" require autofocus>
						@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('password_confirmation')?' has-error':'' }}">
					<label for="password_confirmation" class="col-md-4 control-label">密码：</label>
					<div class="col-md-6">
						<input type="password" id="password_confirmation" class="form-control" name="password_confirmation" value="{{ $password_confirmation or old('password_confirmation') }}" require autofocus>
						@if ($errors->has('password_confirmation'))
						<span class="help-block">
							<strong>{{ $errors->first('password_confirmation') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<button class="btn btn-primary" type="submit">修改密码</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop