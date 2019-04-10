<select name="{{ $name }}" id="{{ $name }}">
    @if(count($options))
        @if(isset($empty) && isset($empty['key']) && isset($empty['value']))
            <option value="{{ $empty['key'] }}" selected>{{ $empty['value'] }}</option>
        @endif
        @foreach($options as $key => $value)

            <option value="{{ $key }}" @if(isset($default) && $key==$default) selected @endif>{{ $value }}</option>
        @endforeach
    @endif
</select>