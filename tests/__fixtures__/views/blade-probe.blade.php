@raster(width: 100, preview: true, data: fn ($label = 'default') => ['label' => $label])
<script id="state" type="application/json">{!! json_encode(['label' => $label, 'width' => $raster->width()]) !!}</script>
