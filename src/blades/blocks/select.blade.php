<select name="{{ $name }}" id="{{ $name }}">
    @php
        $is_selected = false;
    @endphp
    @if(count($options))
        @if(empty($index) && isset($empty) && isset($empty['key']) && isset($empty['value']))
            @php
                $is_selected = true;
            @endphp
            <option value="{{ $empty['key'] }}" selected>{{ $empty['value'] }}</option>
        @endif
        @foreach($options as $key => $value)

            <option value="{{ $key }}" @if((!empty($index)) && $key==$index || ($is_selected===false && !empty($default) && $key==$default)) selected @endif>{{ $value }}</option>
        @endforeach
    @endif
</select>