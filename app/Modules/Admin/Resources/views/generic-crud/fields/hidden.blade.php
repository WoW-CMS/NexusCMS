<input type="hidden"
       name="{{ $field['name'] }}"
       value="{{ $field['value'] ?? old($field['name'], $record?->{$field['name']} ?? '') }}">
