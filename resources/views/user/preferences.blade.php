@extends('layout.master')

@section('content')
    <div class="container-settings">
        {!! Form::open(['route' => 'user.preferences.update', 'method' => 'put', 'class' => 'form-horizontal']) !!}
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h3>Preferences</h3>
                    <h4>User Information</h4>
                    
                    <!-- User identifier input -->
                    <div class="form-group {{ $errors->first('user_identifier') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">User Identifier *</label>
                        <div class="col-sm-9">
                            @if($errors->first('user_identifier'))
                                <span class="help-block">{{ $errors->first('user_identifier') }}</span>
                            @endif
                            <div class="input-group">
                                <span class="input-group-addon">http://mock.restboat.com/</span>
                                <input type="text" required class="form-control" name="user_identifier"
                                       value="{{ Input::old('user_identifier', $preferences->user_identifier ) }}" placeholder="User Identifier">
                                <span class="input-group-addon">/</span>
                            </div>
                            <span class="help-block">Which uses to uniquely identify that the requests are send by you. To make it a little secured, you may use a random url compatible string like <code>1t5a_Me55</code> that are hard to guess.</span>
                        </div>
                    </div>

                    <!-- Show email field if note set (failed to fetch from social login) -->
                    @if(empty(Auth::user()->email))
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email"
                                       value="{{ Auth::user()->email }}" placeholder="your email">
                                <span class="help-block">Help description or important tips or anything.</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Timezone input -->
                    <div class="form-group {{ $errors->first('timezone') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Timezone *</label>
                        <div class="col-sm-9">
                            @if($errors->first('timezone'))
                                <span class="help-block">{{ $errors->first('timezone') }}</span>
                            @endif
                            <select name="timezone" class="form-control">
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone }}" {{ $timezone == $preferences->timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="help-block">The request send by you will be logged based on this timezone.</span>
                        </div>
                    </div>

                    <h4>Default Response</h4>

                    <!-- Default response status input -->
                    <div class="form-group {{ $errors->first('default_response_status') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Status Code</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" required name="default_response_status"
                                   value="{{ Input::old('default_response_status', $preferences->default_response_status) }}" placeholder="Response Code">
                        </div>
                        @if($errors->first('default_response_status'))
                            <span class="help-block">{{ $errors->first('default_response_status') }}</span>
                        @endif
                    </div>

                    <!-- Default response type input -->
                    <div class="form-group {{ $errors->first('default_response_type') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Content Type</label>
                        <div class="col-sm-9">
                            @if($errors->first('default_response_type'))
                                <span class="help-block">{{ $errors->first('default_response_type') }}</span>
                            @endif
                            <label class="radio-inline">
                                <input type="radio" name="default_response_type" value="application/json"
                                        {{ Input::old('default_response_type', $preferences->default_response_type) == 'application/json' ? 'checked' : '' }}>
                                JSON
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="default_response_type" value="application/xml"
                                        {{ Input::old('default_response_type', $preferences->default_response_type) == 'application/xml' ? 'checked' : '' }}>
                                XML
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="default_response_type" value="text/html"
                                        {{ Input::old('default_response_type', $preferences->default_response_type) == 'text/html' ? 'checked' : '' }}>
                                HTML
                            </label>
                        </div>
                    </div>

                    <!-- Default response content input -->
                    <div class="form-group {{ $errors->first('default_response_content') ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Response Content</label>
                        <div class="col-sm-9">
                            @if($errors->first('default_response_content'))
                                <span class="help-block">{{ $errors->first('default_response_content') }}</span>
                            @endif
                            <textarea name="default_response_content" required rows="10" class="form-control"
                                      placeholder="Response content goes here..">{{ Input::old('default_response_content', $preferences->default_response_content) }}</textarea>
                            <span class="help-block">A response with above settings will send back to your requests, for which a specific response is not set.</span>
                        </div>
                    </div>

                    <h4>Additional Security</h4>

                    <!-- Additional security options -->
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="true" name="boat_token_enable"
                                        {{ Input::old('boat_token_enable', $preferences->boat_token_enable ) ? 'checked' : '' }}>
                                    Enable additional security
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional security token input -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Security Token</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" readonly=""
                                       name="boat_token" value="{{ $preferences->boat_token }}">
                                <span class="input-group-btn">
                                    <a class="btn btn-default" href="{{ route('user.preferences.token.update') }}"><i class="fa fa-refresh"></i></a>
                                </span>
                            </div>
                            <span class="help-block">If you feel, your mock server sandbox <strong>need more security</strong>, enable this setting.
                                You have to send a <code>Authorization</code> header along with each requests as following.<br/>
                                <code>Token token="{{ $preferences->boat_token }}"</code>. <br/>All requests without this header will be rejected.
                            </span>
                        </div>
                    </div>

                    <hr>
                    <div class="text-center">
                        <button class="btn btn-info" type="submit">Save Preferences</button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@stop

