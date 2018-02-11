<div class="form-group">
    {{ form()->label($name, null, ['class' => 'control-label']) }}
    {{ form()->text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
</div>
