@if ($stock <= 2)
    <span class="badge badge-critico">Crítico</span>
@elseif ($stock <= 5)
    <span class="badge badge-bajo">Bajo</span>
@else
    <span class="badge badge-normal">Normal</span>
@endif