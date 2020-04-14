<select name="{{ $name }}[]" id="{{ $name }}" class="selectpicker" data-style="btn-info btn-outline" data-size="4" title="所有城市" multiple>
    @php
      $is_selected = false;
      if(!empty($index)){
          if(!is_array($index)){
             $index = [$index];
          }
      }
      if(!empty($default)){
          if(!is_array($default)){
             $default = [$default];
          }
      }
    @endphp
    @if(count($options))
        @if(empty($index) && isset($empty['key']) && isset($empty['value']))
            @php
              $is_selected = true;
            @endphp
            <option value="{{ $empty['key'] }}" selected>{{ $empty['value'] }}</option>
        @endif
        @foreach($options as $key => $value)
            <option value="{{ $key }}"
                    @if((!empty($index)) && in_array($key, $index) xor ($is_selected===false && empty($index) && !empty($default) && in_array($key, $default) ) ) selected @endif
            >{{ $value }}
            </option>
        @endforeach
    @endif
</select>